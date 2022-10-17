<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\Collaborateur;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class HubsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Coordinateur|Administrateur']);
    }

    /**
     * @return \Inertia\Response
     */
    public function getGestionHub (): \Inertia\Response
    {
        $collaborateurs = Collaborateur::where('hub_id', Auth::user()->hub_id)->get();

        $users = User::where('hub_id', Auth::user()->hub_id)
            ->where('status', 'Conseiller')
            ->get();

        return Inertia::render('GestionHub', [
            'collaborateurs' => $collaborateurs,
            'users' => $users
        ]);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (): \Illuminate\Http\JsonResponse
    {
        return response()->json(Hub::all());
    }

    /**
     * @param Hub $hub
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser (Hub $hub): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        $update = $user->update([
                'hub_id' => $hub->id
            ]);

        return ControllerResponse::update($update);
    }

    /**
     * @param Request $request
     * @param Hub $hub
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAuthorize (Request $request, Hub $hub): \Illuminate\Http\JsonResponse
    {
        $update = $hub->update([
            'droit_update' => $request->isAuthorize
        ]);

        return ControllerResponse::update($update);
    }
}
