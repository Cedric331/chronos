<?php

namespace App\Http\Controllers;

use App\Models\Collaborateur;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class CollaborateurController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Coordinateur|Responsable|Administrateur']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): \Inertia\Response
    {
        $collaborateurs = Collaborateur::where('hub_id', Auth::user()->hub_id)->get();

        return Inertia::render('GestionCollaborateur', [
            'collaborateurs' => $collaborateurs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => [
                'required',
                'max:30',
                Rule::unique('collaborateurs')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('hub_id', Auth::user()->hub_id);
                })
            ]
        ]);

        $collaborateur = Collaborateur::create([
            'name' => $request->name,
            'hub_id' => Auth::user()->hub_id
        ]);

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a ajouté un conseiller');

        return response()->json($collaborateur);
    }

    /**
     * @param Request $request
     * @param Collaborateur $collaborateur
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Collaborateur $collaborateur): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => [
                'required',
                'max:30',
                Rule::unique('collaborateurs')->where(function ($query) use ($request) {
                    return $query->where('name', $request->name)->where('hub_id', Auth::user()->hub_id);
                })
            ]
        ]);

        $collaborateur->update([
            'name' => $request->name
        ]);

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a modifié un conseiller');

        return response()->json($collaborateur);
    }

    /**
     * @param Collaborateur $collaborateur
     */
    public function delete(Collaborateur $collaborateur): \Illuminate\Http\JsonResponse
    {
        $users = User::where('collaborateur_id', $collaborateur->id)->get();

        foreach ($users as $user) {
            $user->update([
                'collaborateur_id' => null
            ]);
        }
        $collaborateur->dates()->detach();
        $collaborateur->joursFerie()->detach();
        $delete = $collaborateur->delete();

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a supprimé un conseiller');

       return response()->json($delete);
    }
}
