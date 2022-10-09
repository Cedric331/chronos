<?php

namespace App\Http\Controllers;

use App\Models\Collaborateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CollaborateurController extends Controller
{
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
            'name' => 'required|max:30'
        ]);

        $collaborateur = Collaborateur::create([
            'name' => $request->name,
            'hub_id' => Auth::user()->hub_id
        ]);

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
        $delete = $collaborateur->delete();

       return response()->json($delete);
    }
}
