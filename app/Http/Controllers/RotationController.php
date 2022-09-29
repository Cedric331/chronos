<?php

namespace App\Http\Controllers;

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'type' => 'required',
            'jours' => 'required'
        ]);

        date_default_timezone_set('Europe/Paris');
        $minutes = null;
        foreach ($request->jours as $key => $value) {

            if (!$value['is_off'] && $value['debut_journee'] && $value['fin_journee']) {

                $origin = new DateTimeImmutable(Str::of($value['debut_journee'])->replace('h', ':'));
                $target = new DateTimeImmutable(Str::of($value['fin_journee'])->replace('h', ':'));
                $interval = $origin->diff($target);
                $timeJournee = $interval->format('%H:%I');

                if ($value['debut_pause'] && $value['fin_pause']) {

                    $origin = new DateTimeImmutable(Str::of($value['debut_pause'])->replace('h', ':'));
                    $target = new DateTimeImmutable(Str::of($value['fin_pause'])->replace('h', ':'));
                    $interval = $origin->diff($target);
                    $timepause = $interval->format('%H:%I');

                    $origin = new DateTimeImmutable($timepause);
                    $target = new DateTimeImmutable($timeJournee);
                    $interval = $origin->diff($target);
                    $hours = $interval->format('%H:%I');

                } else {
                    $hours = $timeJournee;
                }

                $t = explode(':', $hours);
                $minutes += $t[0] * 60 + $t[1];

            }
        }

        if ($minutes) {
            $hrs = $minutes / 60;
            $mins = $minutes % 60;

            $heures = ((int)$hrs . "h" . ((int)$mins === 0 ? '00' : (int)$mins));
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
}
