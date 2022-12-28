<?php

namespace App\Http\Controllers;

use App\Models\CollaborateurDate;
use App\Models\Date;
use App\Models\JoursFerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Coordinateur|Responsable|Administrateur']);
    }

    public function store (Request $request)
    {
        // TODO Vérifier heures + longueur name evenement
        // TODO Ajout delete evenement
        $event =  [
            'name' => $request->evenement,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin
        ];
        $collect = collect();
        $collect->push($event);
        $collaborateurDate = null;
        foreach ($request->selected as $selected) {
                $collaborateurDate = CollaborateurDate::find($selected['horaire_id']);
                $events = $collaborateurDate->evenements;
                if ($events) {
                    foreach (json_decode($events) as $event) {
                        $collect->push($event);
                    }
                }
                $collaborateurDate->evenements = $collect->toJson();
                $collaborateurDate->save();
            }

        return response()->json($collaborateurDate);
    }
}
