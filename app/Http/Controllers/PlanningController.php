<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Http\Requests\Planning\importHubRequest;
use App\Mail\ModificationHoraire;
use App\Models\Collaborateur;
use App\Models\CollaborateurDate;
use App\Models\Date;
use App\Models\Hub;
use App\Models\JoursFerie;
use App\Models\Planning;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PlanningController extends Controller
{

    private  $cells = [
        'G' => '8h00',
        'H' => '8h30',
        'I' => '9h00',
        'J' => '9h30',
        'K' => '10h00',
        'L' => '10h30',
        'M' => '11h00',
        'N' => '11h30',
        'O' => '12h00',
        'P' => '12h30',
        'Q' => '13h00',
        'R' => '13h30',
        'S' => '14h00',
        'T' => '14h30',
        'U' => '15h00',
        'V' => '15h30',
        'W' => '16h00',
        'X' => '16h30',
        'Y' => '17h00',
        'Z' => '17h30',
        'AA' => '18h00',
        'AB' => '18h30',
        'AC' => '19h00',
        'AD' => '19h30',
        'AE' =>'20h00',
        'AF' => '20h30',
        'AG' => '21h00'
    ];

    public function __construct()
    {
        $this->middleware(['role:Coordinateur|Administrateur|Responsable'], ['only' => ['import']]);
    }


    /**
     * @param importHubRequest $request
     * @param Hub $hub
     * @return false|string
     */
    public function import (importHubRequest $request, Hub $hub): bool|string
    {
        $name = 'planning-'.Carbon::now()->format('Y').'.'.$request->file('file')->getClientOriginalExtension();
        $request->file('file')->storeAs('planning/'.$hub->ville, $name);
         $store = Planning::firstOrCreate([
                'file' => $name,
                'hub_id' => $hub->id
            ]);

         if (!$store) {
             return response()->json(false, 400);
         }
        $inputFileName = Storage::path('planning/'.$hub->ville.'/'.$name);
        $inputFileType = IOFactory::identify($inputFileName);

        $reader = IOFactory::createReader($inputFileType);

        $reader->setLoadSheetsOnly(' PLANNING ');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($inputFileName);

        $sheet = $spreadsheet->getActiveSheet();

        $collect = collect();

        for ($i = 0; $i < $sheet->getHighestRow(); $i++) {
            if ($sheet->getCell('B' . $i)->getCalculatedValue() !== null && is_numeric($sheet->getCell('B' . $i)->getCalculatedValue())) {
                $excel_date = $sheet->getCell('B'. $i)->getCalculatedValue();
                $unix_date = ($excel_date - 25569) * 86400;

                $time = strtotime(date('l d F Y', strtotime(now()))) - strtotime(date('l d F Y', strtotime('- '.($this->getLundi() - 1).' days')));
                $oldTime = strtotime(now()) - $time;


                $numberMembers = $i;
                $members = collect();

                while (($sheet->getCell('D'. $numberMembers)->getOldCalculatedValue() !== null || $sheet->getCell('D'. $numberMembers)->getValue() !== null)
                && ($sheet->getCell('F'. $numberMembers)->getOldCalculatedValue() !== null || $sheet->getCell('F'. $numberMembers)->getValue() !== null))
                {
                    $type = $sheet->getCell('F' . $numberMembers)->getOldCalculatedValue() ?
                        $sheet->getCell('F' . $numberMembers)->getOldCalculatedValue() :
                        $sheet->getCell('F' . $numberMembers)->getValue();

                    $member['collaborateur'] = $sheet->getCell('D'. $numberMembers)->getOldCalculatedValue() ?
                        $sheet->getCell('D'. $numberMembers)->getOldCalculatedValue() :
                        $sheet->getCell('D'. $numberMembers)->getValue();

                    $horaires = $sheet->getCell('C' . $numberMembers)->getOldCalculatedValue() ?
                        $sheet->getCell('C'. $numberMembers)->getOldCalculatedValue() :
                        $sheet->getCell('C'. $numberMembers)->getValue();

                    $teletravail = $sheet->getCell('E' . $numberMembers)->getOldCalculatedValue() ?
                        $sheet->getCell('E'. $numberMembers)->getOldCalculatedValue() :
                        $sheet->getCell('E'. $numberMembers)->getValue();

                    $debutJournee = 'OFF';
                    $finJournee = 'OFF';


                    if (strpos($horaires, '-') && !empty($horaires)) {
                        $horaire = explode('-', $horaires);
                        $array = str_split($horaire[0]);

                        $debutJournee = $horaire[0];
                        if (!is_numeric($array[0]))
                        {
                            $string = explode(':', $horaire[0]);
                            $debutJournee = trim($string[1]);
                        }

                        $finJournee = $horaire[1];
                    }
                    $debutPause = null;
                    $finPause = null;

                    foreach ($this->cells as $cell => $value) {
                        $dej = $sheet->getCell($cell . $numberMembers)->getOldCalculatedValue() ?
                            $sheet->getCell($cell . $numberMembers)->getOldCalculatedValue() :
                            $sheet->getCell($cell . $numberMembers)->getValue();

                        if ($dej === 'DEJ') {
                            if (empty($debutPause)) {
                                $debutPause = $value;
                            }
                        }
                        if ($dej !== 'DEJ' && !empty($debutPause) && empty($finPause)) {
                            $finPause = $value;
                        }
                    }

                    $horaires = [
                        'debut_journee' => $debutJournee,
                        'debut_pause' => $debutPause,
                        'fin_pause' => $finPause,
                        'fin_journee' => $finJournee,
                        'teletravail' => $teletravail === 'TLT',
                        'type' => $type,
                    ];
                    $member['horaire'] = $horaires;
                    $members->push($member);
                    $numberMembers++;
            }

                $object = [
                    date("l d F Y", $unix_date) => $members->toArray()
                ];
                $collect->push($object);
        }
}
        $spreadsheet->__destruct();
        $spreadsheet = null;
        unset($spreadsheet);

        $reader = null;
        unset($reader);

        $allPlannings = $collect->toArray();

        $saveCollaborateurs = collect();
        $collaborateurs = Collaborateur::where('hub_id', Auth::user()->hub_id)->get();
        foreach ($collaborateurs as $item) {

            $users = User::where('collaborateur_id', $item->id)->get();

            $saveCollaborateurs->push([
                'name' => $item->name,
                'users' => $users
            ]);

            foreach ($users as $user) {
                $user->update([
                    'collaborateur_id' => null
                ]);
            }
            $item->dates()->detach();
            $item->delete();
        }

        $hub = Hub::find(Auth::user()->hub_id);

        foreach ($allPlannings as $plannings) {
            foreach ($plannings as $key => $values) {
                $date = Date::firstOrCreate([
                    'date' => date('Y-m-d', strtotime($key))
                ]);

                foreach ($values as $value) {
                    $collaborateur = Collaborateur::firstOrCreate([
                        'name' => $value['collaborateur'],
                        'hub_id' => $hub->id,
                    ]);

                    $hub->dates()->attach($date, [
                        'collaborateur_id' => $collaborateur->id,
                        'horaire' => json_encode($value['horaire'])
                    ]);
                }
            }
        }

        foreach ($saveCollaborateurs as $save) {

            $collaborateur = Collaborateur::where('hub_id', Auth::user()->hub_id)
                ->where('name', $save['name'])
                ->first();

            if ($collaborateur) {
                foreach ($save['users'] as $user) {
                    $user->update([
                        'collaborateur_id' => $collaborateur->id
                    ]);
                }
            }
        }

        date_default_timezone_set('Europe/Paris');
        $hub->update([
            'import_horodatage' => date('Y-m-d H:i:s', strtotime('now'))
        ]);

        return $hub->horodatage();
    }

    /**
     * @param Request $request
     */
    public function loadPlanning (Request $request)
    {
        $hub = Hub::findOrFail(Auth::user()->hub_id);

        $collaborateurId = Auth::user()->collaborateur_id;

        $collaborateur = null;
          if ($collaborateurId !== null && $request->id === null) {
            $collaborateur = Collaborateur::with('dates')
                ->where('hub_id', $hub->id)
                ->find($collaborateurId);
            }

          if ($collaborateur === null) {
              if ($request->id) {
                  $collaborateur = Collaborateur::with('dates')
                      ->where('hub_id', $hub->id)
                      ->find($request->id);
              } else {
                  $collaborateur = Collaborateur::with('dates')
                      ->where('hub_id', $hub->id)
                      ->first();
              }
          }

        $collaborateurs = Collaborateur::where('hub_id', $hub->id)->get();

        $collect = collect();
        if ($collaborateur) {
            foreach ($collaborateur->dates as $date) {
                if ($request->showPlanning === 'true' || strtotime(date('l d F Y', strtotime($date->date))) > strtotime(date('l d F Y', strtotime('- '.$this->getLundi().' days')))) {
                    $horaires = $this->getHoraire($date->pivot->horaire);
                    $object = [
                        'date_id' => $date->id,
                        'date' => $this->formatDateFr($date->date),
                        'horaires' => $horaires,
                        'horaire_id' => $date->pivot->id,
                        'type' => $this->getType($date->pivot->horaire, $horaires),
                        'today' => $this->formatDateFr(now()) === $this->formatDateFr($date->date)
                    ];
                    $collect->push($object);
                }
            }
            $collaborateur = $collaborateur->toArray();
            unset($collaborateur['dates']);
        }

        $collection = $collect->sliding(7, 7);
        foreach ($collection as $item) {
            $item['time'] = $this->timeHours($item);
        }
        $collect = collect();
        foreach ($collection as $items) {
            $newItem = true;
            foreach ($items as $item) {
                if ($newItem) {
                    $collect->push(['time' => $items['time']]);
                    $newItem = false;
                }
                if (is_array($item)) {
                    $collect->push($item);
                }
            }
        }

        if (!$request->loadData && $request->showPlanning === null) {
            return Inertia::render('Planning', [
                'collaborateurs' => $collaborateurs,
                'collaborateur' => $collaborateur,
                'plannings' => $collect->toArray()
            ]);
        } else {
            return response()->json([
                'collaborateurs' => $collaborateurs,
                'collaborateur' => $collaborateur,
                'plannings' => $collect->toArray()
            ]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadPlanningDate (Request $request): \Illuminate\Http\JsonResponse
    {
        $collaborateurs = Collaborateur::with(['dates' => function ($query) use ($request) {
                            $query->where('dates.id', $request->date);
                        }
                    ])->where('hub_id', Auth::user()->hub_id)
                    ->get();

        $getDate = Date::select('date')->find($request->date);
        $showDate = $this->formatDateFr($getDate->date);

        $collect = collect();
        if ($collaborateurs) {
            foreach ($collaborateurs as $collaborateur) {
               foreach ($collaborateur->dates as $date) {
                $horaires = $this->getHoraire($date->pivot->horaire);
                $object = [
                        'collaborateur' => $collaborateur->name,
                        'horaires' => $horaires,
                        'type' => $this->getType($date->pivot->horaire, $horaires)
                    ];
                $collect->push($object);
           }
          }
        }

    $collect = $collect->unique();

            return response()->json([
                'planning' => $collect->toArray(),
                'date' => [
                    'id' => $request->date,
                    'format' => $showDate,
                    'previous' => $request->previous,
                    'next' => $request->next,
                    'index' => $request->index
                ]
            ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function loadPlanningWeek (Request $request): \Illuminate\Http\JsonResponse
    {
        $date = Date::find($request->date);
        $date = new DateTime($date->date);
        $week = $date->format('W');
        $year = $date->format('Y');
        $days = $this->getStartAndEndDate($week, $year);

        $collaborateurs = Collaborateur::with(['dates' => function ($query) use ($request, $days) {
            $query->whereBetween('dates.date', [$days['week_start'], $days['week_end']]);
        }
        ])->where('hub_id', Auth::user()->hub_id)
            ->get();

        $collect = collect();
        if ($collaborateurs) {
            foreach ($collaborateurs as $collaborateur) {
                foreach ($collaborateur->dates as $date) {
                    $horaires = $this->getHoraire($date->pivot->horaire);
                    $object = [
                        'collaborateur' => $collaborateur->name,
                        'horaires' => $horaires,
                        'type' => $this->getType($date->pivot->horaire, $horaires)
                    ];
                    $collect->push($object);
                }
            }
        }

        $data = $collect->groupBy('collaborateur')->unique();

        foreach ($data as $item) {
            $item['time'] = $this->timeHours($item);
        }

        $today = null;
        $dateJour = date('Y-m-d',strtotime(now()));

        if (strtotime($days['week_start']) <= strtotime($dateJour) && strtotime($days['week_end']) >= strtotime($dateJour)) {
            $today = explode(' ', $this->formatDateFr(now()))[0];
        }

        return response()->json([
            'planning' => $data,
            'today' => $today,
            'weeks' => [date('d-m-Y', strtotime($days['week_start'])), date('d-m-Y', strtotime($days['week_end']))],
        ]);
    }

    /**
     * @param $data
     * @return array|string|null
     * @throws \Exception
     */
    private function timeHours($data)
    {
        date_default_timezone_set('Europe/Paris');
        $minutes = null;
        foreach ($data as $value) {

            if ($value['horaires'] && $value['horaires']->debut_journee && $value['horaires']->fin_journee) {

                $origin = new DateTimeImmutable(Str::of($value['horaires']->debut_journee)->replace('h', ':'));
                $target = new DateTimeImmutable(Str::of($value['horaires']->fin_journee)->replace('h', ':'));

                $interval = $origin->diff($target);
                $timeJournee = $interval->format('%H:%I');

                if ($value['horaires']->debut_pause && $value['horaires']->fin_pause) {

                    $origin = new DateTimeImmutable(Str::of($value['horaires']->debut_pause)->replace('h', ':'));
                    $target = new DateTimeImmutable(Str::of($value['horaires']->fin_pause)->replace('h', ':'));
                    $interval = $origin->diff($target);
                    $timepause = $interval->format('%H:%I');


                    $origin = new DateTimeImmutable($timepause);
                    $target = new DateTimeImmutable($timeJournee);
                    $interval = $origin->diff($target);
                    $hours = $interval->format('%H:%I');

                } else {
                    $hours = $timeJournee;
                }

                $minutes += $this->convertMinute($hours);
            }
        }

            if ($minutes) {
                $hrs = $minutes / 60;
                $mins = $minutes % 60;

                return ((int)$hrs . "h" . ((int)$mins === 0 ? '00' : (int)$mins));
            } else {
                return '00h00';
            }
    }

    /**
     * @param $hours
     * @return float|int|string
     */
    protected function convertMinute ($hours)
    {
        $t = explode(':', $hours);
        return $t[0] * 60 + $t[1];
    }

    private function getStartAndEndDate($week, $year): array
    {
        $dto = new DateTime();
        $dto->setISODate($year, $week);
        $ret['week_start'] = $dto->format('Y-m-d');
        $dto->modify('+6 days');
        $ret['week_end'] = $dto->format('Y-m-d');
        return $ret;
    }

    /**
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    private function getHoraire ($data): mixed
    {
        $horaires = json_decode($data);
        $isPlanifier = true;

        if ($horaires->debut_journee === 'OFF' || empty($horaires->debut_journee)) {
            $isPlanifier = false;
        }
        if ($isPlanifier) {
            return $horaires;
        } else {
            return false;
        }
    }

    /**
     * @param $data
     * @return string
     */
    private function formatDateFr($data): string
    {
        $format = date('l d F', strtotime($data));
        $date = explode(' ', $format);
        $days = [
            'Monday' => 'Lundi',
            'Tuesday' => 'Mardi',
            'Wednesday' => 'Mercredi',
            'Thursday' => 'Jeudi',
            'Friday' => 'Vendredi',
            'Saturday' => 'Samedi',
            'Sunday' => 'Dimanche'
        ];
        $months = [
            'Janvier' => 'January',
            'Février' => 'February',
            'Mars' => 'March',
            'Avril' => 'April',
            'Mai' => 'May',
            'Juin' => 'June',
            'Juillet' => 'July',
            'Aout' => 'August',
            'Septembre' => 'September',
            'Octobre' => 'October',
            'Novembre' => 'November',
            'Décembre' => 'December'
        ];
        foreach ($days as $key => $value) {
            if ($date[0] === $key) {
                $date[0] = $value;
            }
        }
        foreach ($months as $key => $value) {
            if ($date[2] === $value) {
                $date[2] = $key;
            }
        }
        return implode(' ', $date);
    }

    /**
     * @return int
     */
    private function getLundi (): int
    {
        $today = date('l', strtotime('now'));

        $return_value = match ($today) {
            'Monday' => 1,
            'Tuesday' => 2,
            'Wednesday' => 3,
            'Thursday' => 4,
            'Friday' => 5,
            'Saturday' => 6,
            'Sunday' => 7
        };

        return $return_value;
    }

    private function getType($data, $horaires): ?string
    {
        $value = json_decode($data);

        if ($value->type === 'Iti1' || $value->type === 'Iti2' || $value->type === 'Iti3' || $value->type === 'TER') {
            if (!$horaires) {
                return null;
            } else {
                $value->type = 'Iti';
            }
        }

        return $value->type;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePlanning (Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Gate::allows('update-planning',  Hub::find(Auth::user()->hub_id))) {
            abort(401);
        }

        $type = null;
        if ($request->planification === null) {
            return response()->json(false, 400);
        }
        if ($request->planification === '3') {
            $type = 'CP';
        }
        if ($request->planification === '4') {
            $type = 'FOR';
        }
        if ($request->planification === '5') {
            $type = 'RJF';
        }
        if ($request->planification === '6') {
            $type = 'F';
        }
        if ($request->planification === '7') {
            $type = 'AM';
        }

        if ($request->isTech) {
            $type = 'Iti1';
        }

        $horaires = [
            'debut_journee' => $request->debut_journee,
            'debut_pause' => $request->debut_pause,
            'fin_pause' => $request->fin_pause,
            'fin_journee' => $request->fin_journee,
            'teletravail' => $request->teletravail,
            'type' => $type,
        ];

        if ($request->ferie) {
            $time = date('Y-m-d', strtotime($request->selected['date']));
            $date = Date::firstOrCreate([
                'date' => $time
            ]);

            $collaborateurDate = CollaborateurDate::firstOrNew([
                'collaborateur_id' => $request->user['id'],
                'date_id' => $date->id
                ],
                [
                'horaire' => $horaires,
                'hub_id' => Auth::user()->hub_id
                ]);

            $collaborateurDate->horaire = json_encode($horaires);
            $collaborateurDate->save();

            $joursFerie = JoursFerie::where('date', $time)
                ->where('hub_id', Auth::user()->hub_id)
                ->first();

            if ($type === 'F') {
                $joursFerie->collaborateurs()->detach($request->user['id']);
            } else {
                $joursFerie->collaborateurs()->attach($request->user['id']);
            }
        } else {
            foreach ($request->selected as $selected) {
                $date = Date::find($selected['date_id']);

                $joursFerie = JoursFerie::where('date', $date->date)
                    ->where('hub_id', Auth::user()->hub_id)
                    ->first();

                if ($joursFerie) {
                    $collaborateurDate = CollaborateurDate::firstOrCreate(
                            ['collaborateur_id' => $request->user['id'],
                            'date_id' => $date->id
                            ],
                            [
                            'horaire' => $horaires,
                            'collaborateur_id' => $request->user['id'],
                            'date_id' => $date->id,
                            'hub_id' => Auth::user()->hub_id
                        ]);

                    $collaborateurDate->horaire = json_encode($horaires);
                    $collaborateurDate->save();

                    if ($type === 'F') {
                        $joursFerie->collaborateurs()->detach($request->user['id']);
                    } else {
                        $joursFerie->collaborateurs()->attach($request->user['id']);
                    }
                } else {
                    $collaborateurDate = CollaborateurDate::find($selected['horaire_id']);
                    $collaborateurDate->horaire = json_encode($horaires);
                    $collaborateurDate->save();
                }
            }
        }

        $data = $request;

        if ($request->sendMail) {
            $this->sendMailPlanningUpdate($data, $type);
        }
        if ($request->ferie) {
            $annees = JoursFerie::with('collaborateurs')
                ->where('hub_id', Auth::user()->hub_id)
                ->get();

            $annees = $annees->groupBy('annee');

            return response()->json($annees->toArray());
        } else {
            return response()->json(true);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRotationPlanning (Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Gate::allows('update-planning',  Hub::find(Auth::user()->hub_id))) {
            abort(401);
        }

        foreach ($request->selected as $selected) {
            $day = strtolower(explode(' ', $selected['date'])[0]);
            foreach ($request->rotation['rotations'] as $rotation) {
                if ($rotation['day'] == $day) {
                    $rotation = json_decode($rotation['horaire']);

                    if ($rotation->technicien || $request->technicien) {
                        $type = 'Iti1';
                    } else {
                        $type = null;
                    }

                    $horaires = [
                        'debut_journee' => $rotation->debut_journee,
                        'debut_pause' => $rotation->debut_pause,
                        'fin_pause' => $rotation->fin_pause,
                        'fin_journee' => $rotation->fin_journee,
                        'teletravail' => false,
                        'rotation' => $request->rotation['type'],
                        'type' => $type
                    ];
                }
            }

            $collaborateurDate = CollaborateurDate::find($selected['horaire_id']);

            $collaborateurDate->horaire = json_encode($horaires);
            $collaborateurDate->save();
        }

        return response()->json(true);
    }

    /**
     * @param $data
     * @param $type
     */
    protected function sendMailPlanningUpdate ($data, $type)
    {
        $collect = collect();

        foreach ($data->selected as $item) {

            $collect->push([
                'date_id' => $item['date_id'],
                'date' => $item['date'],
                'debut_journee' => $data->debut_journee,
                'debut_pause' => $data->debut_pause,
                'fin_pause' => $data->fin_pause,
                'fin_journee' => $data->fin_journee,
                'teletravail' => $data->teletravail,
                'type' => $type
            ]);
        }

        $user = User::where('name', $data->user['name'])
        ->where('hub_id', $data->user['hub_id'])
        ->first();

        $collect = $collect->sortBy('date_id');

        Mail::to($user->email)
            ->send(new ModificationHoraire($collect->toArray()));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTeletravail (Request $request): \Illuminate\Http\JsonResponse
    {
        if (!Gate::allows('update-planning',  Hub::find(Auth::user()->hub_id))) {
            abort(401);
        }

        $collaborateurDate = CollaborateurDate::find($request->date['horaire_id']);
        $horaires =  json_decode($collaborateurDate->horaire);
        $horaires->teletravail = !$request->home;
        $collaborateurDate->horaire = json_encode($horaires);

        $update = $collaborateurDate->save();

        return ControllerResponse::update($update);
    }
}
