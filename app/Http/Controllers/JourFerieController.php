<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\JoursFerie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class JourFerieController extends Controller
{

    public function __construct()
    {
        $this->middleware(['role:Coordinateur|Responsable|Administrateur']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (): \Illuminate\Http\JsonResponse
    {
        $annees = JoursFerie::with(['collaborateurs', 'getDate'])
            ->where('hub_id', Auth::user()->hub_id)
            ->get();

        $annees = $annees->groupBy('annee');

        return response()->json($annees->toArray());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (Request $request): \Illuminate\Http\JsonResponse
    {
       $request->validate([
           'zone' => 'required', 'string',
           'annee' => 'required', 'string', 'min:4', 'max:4'
       ]);

        $res = Http::get('https://calendrier.api.gouv.fr/jours-feries/' . $request->zone . '/' . $request->annee . '.json');

        if ($res->collect()->isEmpty()) {
            return response()->json('Erreur Année incorrecte', 404);
        }

        foreach ($res->collect() as $date => $name) {
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

        return response()->json($annees->toArray());
    }

    /**
     * @param Request $request
     */
    public function delete (Request $request)
    {
        foreach ($request->annee as $item) {
            $ferie = JoursFerie::find($item['id']);
            $ferie->collaborateurs()->detach();
            $delete = $ferie->delete();
        }

        ControllerResponse::delete($delete);
    }
}
