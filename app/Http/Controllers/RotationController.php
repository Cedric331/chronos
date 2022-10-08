<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rotation\StoreRotationRequest;
use App\Http\Requests\Rotation\UpdateRotationRequest;
use App\Models\Collaborateur;
use App\Models\Date;
use App\Models\Hub;
use App\Models\Rotation;
use App\Models\TypeRotation;
use App\Models\User;
use DateTime;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class RotationController extends Controller
{

    public function __construct()
    {
        $this->middleware('role:Coordinateur|Administrateur|Responsable');
    }

    /**
     * @return \Inertia\Response
     */
    public function index (): \Inertia\Response
    {
        $rotations = TypeRotation::with('rotations')
                        ->where('hub_id', Auth::user()->hub_id)
                        ->get();

        $collaborateurs = Collaborateur::where('hub_id', Auth::user()->hub_id)->get();

        date_default_timezone_set('Europe/Paris');
        $time = strtotime(date('l d F Y', strtotime('- '.($this->getLundi() - 1).' days')));

        $dateStart = str_replace('-', '-W', date('Y-W', $time));
        $dateEnd = str_replace('-', '-W', date('Y-W', strtotime('+1 year') + strtotime($time)));

        return Inertia::render('Rotation', [
            'rotations' => $rotations,
            'collaborateurs' => $collaborateurs,
            'dateLimitStart' => $dateStart,
            'dateLimitEnd' => $dateEnd,
        ]);
    }

    /**
     * @param StoreRotationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store (StoreRotationRequest $request): \Illuminate\Http\JsonResponse
    {
        $heures = self::timeHours($request);

        if (is_array($heures)) {
            return response()->json($heures['message'], 422);
        }

        $typeRotation = TypeRotation::create([
            'type' => $request->type,
            'hours' => $heures,
            'hub_id' => Auth::user()->hub_id
        ]);

        foreach ($request->jours as $key => $value) {
            Rotation::create([
                'day' => $key,
                'horaire' => json_encode($value),
                'type_rotation_id' => $typeRotation->id
            ]);
        }


        $rotations = TypeRotation::with('rotations')
            ->where('hub_id', Auth::user()->hub_id)
            ->find($typeRotation->id);

        return response()->json($rotations);
    }

    /**
     * @param UpdateRotationRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update (UpdateRotationRequest $request): \Illuminate\Http\JsonResponse
    {
        $heures = self::timeHours($request);

        if (is_array($heures)) {
            return response()->json($heures['message'], 422);
        }

        $typeRotation = TypeRotation::find($request->id);

        $typeRotation->update([
            'type' => $request->type,
            'hours' => $heures
        ]);

        foreach ($request->jours as $key => $value) {
            $rotation = Rotation::find($request->jours[$key]['id']);

            $rotation->update([
                'day' => $key,
                'horaire' => json_encode($value),
                'type_rotation_id' => $typeRotation->id
            ]);
        }

        $rotations = TypeRotation::with('rotations')
            ->where('hub_id', Auth::user()->hub_id)
            ->find($typeRotation->id);

        return response()->json($rotations);
    }

    /**
     * @param TypeRotation $rotation
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete (TypeRotation $rotation): \Illuminate\Http\JsonResponse
    {
        $rotation->rotations()->delete();

       $delete = $rotation->delete();

        return response()->json($delete);
    }

    public function generatePlanning (Request $request)
    {
        date_default_timezone_set('Europe/Paris');

        $selectTimeStart = date('l d F Y',strtotime($request->dateStart));
        $selectTimeEnd = date('l d F Y',strtotime($request->dateEnd));
        $dateLimitedStart = date('l d F Y',strtotime('- '.($this->getLundi() - 1).' days'));
        $dateLimitedEnd = date('l d F Y',strtotime('+1 year'));

        if (strtotime($dateLimitedStart) > strtotime($selectTimeStart) ||
            strtotime($selectTimeStart) > strtotime($dateLimitedEnd) ||
            strtotime($dateLimitedEnd) < strtotime($selectTimeEnd) ||
            strtotime($selectTimeEnd) < strtotime($dateLimitedStart)) {
            return response()->json('Erreur dans la sélection des dates', 422);
        }

        $hub = Hub::find(Auth::user()->hub_id);

        if ($request->collaborateur) {
        $collaborateur = Collaborateur::find($request->collaborateur['id']);

        $users = User::where('collaborateur_id', $collaborateur->id)->get();
        $saveCollaborateurs = collect();

        $saveCollaborateurs->push([
            'name' => $collaborateur->name,
            'users' => $users
        ]);

        foreach ($users as $user) {
            $user->update([
                'collaborateur_id' => null
            ]);
        }
        $collaborateur->dates()->detach();
        $collaborateur->delete();
        }

        $i = 0;
        while ($selectTimeStart !== $selectTimeEnd) {

             $day = $this->formatDateFr($selectTimeStart);

            foreach ($request->rotations[$i]['rotations'] as $rotation) {

                if ($rotation['day'] === $day) {
                    $horaire = json_decode($rotation['horaire']);
                    $horaires = [
                        'debut_journee' => $horaire->debut_journee,
                        'debut_pause' => $horaire->debut_pause,
                        'fin_pause' => $horaire->fin_pause,
                        'fin_journee' => $horaire->fin_journee,
                        'teletravail' => false,
                        'type' => $horaire->technicien ? 'Iti1' : null,
                        'rotation' => $request->rotations[$i]['type'],
                    ];
                }
            }

                $date = Date::firstOrCreate([
                    'date' => date('Y-m-d', strtotime($selectTimeStart))
                ]);

                $collaborateur = Collaborateur::firstOrCreate([
                    'name' => $request->collaborateur['name'],
                    'hub_id' => $hub->id,
                ]);

                $hub->dates()->attach($date, [
                    'collaborateur_id' => $collaborateur->id,
                    'horaire' => json_encode($horaires)
                ]);

            $selectTimeStart = date('l d F Y',(strtotime('+1 days', strtotime($selectTimeStart))));

            $day = $this->formatDateFr($selectTimeStart);

            if ($day === 'lundi') {
                if (array_key_exists($i + 1, $request->rotations)) {
                    $i++;
                } else {
                    $i = 0;
                }
            }
        }
        return 'ok';
    }

    /**
     * @param $request
     * @return array|string|null
     * @throws \Exception
     */
    private function timeHours($request): array|string|null
    {
        date_default_timezone_set('Europe/Paris');
        $minutes = null;
        foreach ($request->jours as $key => $value) {

            if (!$value['is_off'] && $value['debut_journee'] && $value['fin_journee']) {

                $origin = new DateTimeImmutable(Str::of($value['debut_journee'])->replace('h', ':'));
                $target = new DateTimeImmutable(Str::of($value['fin_journee'])->replace('h', ':'));

                if ($this->convertMinute($origin->format('G:i')) > $this->convertMinute($target->format('G:i'))) {
                    return [
                        'message' => 'Le début de journée de '.$key.' ne peut pas être inférieur à la fin de journée'
                    ];
                }

                $interval = $origin->diff($target);
                $timeJournee = $interval->format('%H:%I');

                if ($value['debut_pause'] && $value['fin_pause']) {

                    $origin = new DateTimeImmutable(Str::of($value['debut_pause'])->replace('h', ':'));
                    $target = new DateTimeImmutable(Str::of($value['fin_pause'])->replace('h', ':'));
                    $interval = $origin->diff($target);
                    $timepause = $interval->format('%H:%I');

                    if ($this->convertMinute($origin->format('G:i')) > $this->convertMinute($target->format('G:i'))) {
                        return [
                            'message' => 'Le début de la pause de '.$key.' ne peut pas être inférieur à la fin de pause'
                        ];
                    }

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
            return null;
        }
    }

    /**
     * @param $hours
     * @return float|int|string
     */
    protected function convertMinute ($hours): float|int|string
    {
        $t = explode(':', $hours);
        return $t[0] * 60 + $t[1];
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

    /**
     * @param $data
     * @return string
     */
    private function formatDateFr($data): string
    {
        $format = date('l', strtotime($data));
        $date = null;
        $days = [
            'Monday' => 'lundi',
            'Tuesday' => 'mardi',
            'Wednesday' => 'mercredi',
            'Thursday' => 'jeudi',
            'Friday' => 'vendredi',
            'Saturday' => 'samedi',
            'Sunday' => 'dimanche'
        ];
        foreach ($days as $key => $value) {
            if ($key === $format) {
                $date = $value;
            }
        }
        return $date;
    }
}
