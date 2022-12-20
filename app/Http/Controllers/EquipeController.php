<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Mail\NouveauUtilisateur;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EquipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Coordinateur|Administrateur|Responsable');
    }

    /**
     * @return \Inertia\Response
     */
    public function show (): \Inertia\Response
    {
        $users = User::where('hub_id', Auth::user()->hub_id)
            ->where('status', 'Conseiller')
            ->get();

        return Inertia::render('GestionEquipe', [
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users'
        ]);

        $url = URL::temporarySignedRoute('register', now()->addHours(24), [
            'name' => Crypt::encrypt($request->name),
            'email' => Crypt::encrypt($request->email),
            'hub' => Crypt::encrypt(Auth::user()->hub_id),
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'url' => $url
        ];

        Mail::to($request->email)
            ->send(new NouveauUtilisateur($data));

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a envoyé une invitation un utilisateur');

        return response()->json(true);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        if ($user->isAdmin()) {
            return response()->json('Action non autorisée', 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'hub' => 'required'
        ]);

        $update = $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'hub_id' => $request->hub,
        ]);

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a modifié un utilisateur');

        return ControllerResponse::update($update);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete (User $user): \Illuminate\Http\JsonResponse
    {
        if ($user->isAdmin()) {
            return response()->json('Action non autorisée', 401);
        }

       $delete = $user->delete();

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a supprimé un utilisateur');

       return ControllerResponse::delete($delete);
    }
}
