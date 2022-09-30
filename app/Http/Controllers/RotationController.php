<?php

namespace App\Http\Controllers;

use App\Http\Requests\Rotation\StoreRotationRequest;
use App\Http\Requests\Rotation\UpdateRotationRequest;
use App\Models\Rotation;
use App\Models\TypeRotation;
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

        return Inertia::render('Rotation', [
            'rotations' => $rotations
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
}
