<?php

namespace App\Http\Controllers;

use App\Http\Requests\Planning\importHubRequest;
use App\Models\Collaborateur;
use App\Models\CollaborateurDate;
use App\Models\Date;
use App\Models\Hub;
use App\Models\Planning;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        $this->middleware(['role:Coordinateur|Administrateur'], ['only' => ['import', 'updatePlanning']]);
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

                if  (strtotime(date('l d F Y', $oldTime)) === strtotime(date("l d F Y", $unix_date)) ||
                    strtotime(date('l d F Y', $oldTime)) < strtotime(date("l d F Y", $unix_date))) {

//                if (strtotime(date("l d F Y", $unix_date)) === strtotime(date('l d F Y', strtotime(now()))) ||
//                    strtotime(date("l d F Y", $unix_date)) > strtotime(date('l d F Y', strtotime(now())))) {

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
                        $debutJournee = $horaire[0];
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
//    }
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
    public function loadPlanning (Request $request): \Illuminate\Http\JsonResponse|\Inertia\Response
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
                if (strtotime(date('l d F Y', strtotime($date->date))) > strtotime(date('l d F Y', strtotime('- '.$this->getLundi().' days')))) {
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

        if (!$request->loadData) {
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

//    /**
//     * @param Request $request
//     * @return \Illuminate\Http\JsonResponse
//     */
//    public function loadPlanningSemaine (Request $request): \Illuminate\Http\JsonResponse
//    {
//        $collaborateurs = Collaborateur::with(['dates' => function ($query) use ($request) {
//            $query->whereIn('dates.id', $request->date);
//        }
//        ])->where('hub_id', Auth::user()->hub_id)
//          ->get();
//
//        $getDate = Date::select('date')->find($request->date);
//        $showDate = $this->formatDateFr($getDate->date);
//
//        $collect = collect();
//        if ($collaborateurs) {
//            foreach ($collaborateurs as $collaborateur) {
//                foreach ($collaborateur->dates as $date) {
//                    $horaires = $this->getHoraire($date->pivot->horaire);
//                    $object = [
//                        'collaborateur' => $collaborateur->name,
//                        'horaires' => $horaires,
//                        'type' => $this->getType($date->pivot->horaire, $horaires)
//                    ];
//                    $collect->push($object);
//                }
//            }
//        }
//
//        $collect = $collect->unique();
//
//        return response()->json([
//            'planning' => $collect->toArray(),
//            'date' => [
//                'id' => $request->date,
//                'format' => $showDate,
//                'previous' => $request->previous,
//                'next' => $request->next,
//                'index' => $request->index
//            ]
//        ]);
//    }

    /**
     * @param $data
     * @return mixed
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

        if ($value->type === 'Iti1' || $value->type === 'Iti2' || $value->type === 'Iti3') {
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
        $type = null;
        if ($request->planification === null) {
            return response()->json(false, 400);
        }
        if ($request->planification === '3') {
            $type = 'CP';
        }
        if ($request->isTech) {
            $type = 'Iti1';
        }
        foreach ($request->selected as $selected) {
           $collaborateurDate = CollaborateurDate::find($selected['horaire_id']);
            $horaires = [
                'debut_journee' => $request->debut_journee,
                'debut_pause' => $request->debut_pause,
                'fin_pause' => $request->fin_pause,
                'fin_journee' => $request->fin_journee,
                'teletravail' => $request->teletravail,
                'type' => $type,
            ];

           $collaborateurDate->horaire = json_encode($horaires);
           $collaborateurDate->save();
        }
        return response()->json(true);
    }
}
