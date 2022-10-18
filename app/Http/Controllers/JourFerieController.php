<?php

namespace App\Http\Controllers;

use App\Models\JoursFerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class JourFerieController extends Controller
{
    public function store (Request $request)
    {

        $res = Http::get('https://calendrier.api.gouv.fr/jours-feries/metropole/' . $request->annee . '.json');

        foreach ($res->collect() as $date => $name)
        {
            JoursFerie::create([
                'annee' => $request->annee,
                'date' => $date,
                'name' => $name,
                'hub_id' => Auth::user()->hub_id
            ]);
        }

        $annees = JoursFerie::with('collaborateurs')
            ->where('hub_id', Auth::user()->hub_id)
            ->get();
        $annees = $annees->groupBy('annee');

        return response()->json($annees);
    }
}
