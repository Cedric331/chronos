<?php

namespace App\Http\Controllers;

use App\Mail\nouveauUtilisateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EquipeController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:Coordinateur');
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

        $url = URL::signedRoute('register', [
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
            ->send(new nouveauUtilisateur($data));

        return response()->json(true);
    }
}
