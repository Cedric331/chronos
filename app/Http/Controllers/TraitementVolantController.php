<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\Hub;
use App\Models\TraitementVolant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TraitementVolantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Responsable|Administrateur|Volant']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function show (): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        return Inertia::render('TraitementVolant', [
            'traitements' => TraitementVolant::where('hub_id', Auth::user()->hub_id)->get(),
            'hubs' => Hub::all(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeHub (Request $request): \Illuminate\Http\JsonResponse
    {
        $traitements = TraitementVolant::where('hub_id', $request->hub)->get();

        return response()->json($traitements);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function store (Request $request): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        if (Auth::user()->isCoordinateur()) {
            $request->validate([
                'titre' => 'required|string',
                'description' => 'required|string'
            ]);

            $store = TraitementVolant::create([
                'titre' => $request->titre,
                'description' => $request->description,
                'hub_id' => Auth::user()->hub_id
            ]);

            return ControllerResponse::store($store);
        } else {
            return response()->json('Action non autorisée', 401);
        }
    }

    /**
     * @param Request $request
     * @param TraitementVolant $traitement
     * @return \Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function update (Request $request, TraitementVolant $traitement): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        if (Auth::user()->isCoordinateur()) {
            $request->validate([
            'titre' => 'required|string',
            'description' => 'required|string'
        ]);

        $update = $traitement->update([
            'titre' => $request->titre,
            'description' => $request->description,
        ]);

        return ControllerResponse::update($update, $traitement, true);
        } else {
            return response()->json('Action non autorisée', 401);
        }
    }

    /**
     * @param TraitementVolant $traitement
     * @return \Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function delete (TraitementVolant $traitement): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        if (Auth::user()->isCoordinateur()) {
            $delete = $traitement->delete();

            return ControllerResponse::delete($delete);
        } else {
            return response()->json('Action non autorisée', 401);
        }
    }
}
