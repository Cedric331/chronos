<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class UserController extends Controller
{

    /**
     * @return \Inertia\Response
     */
    public function show (): \Inertia\Response
    {
        return Inertia::render('Auth/UpdatePassword');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'new_password' => ['required', 'min:8', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', 'min:8'],
        ]);

        $request->request->add(['email' => Auth::user()->email]);
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $password = Hash::make($request->new_password);
            $update = User::find(Auth::id())->update([
                'password' => $password
            ]);

            return ControllerResponse::update($update);
        }
        return response()->json('Erreur d\'authentification', 401);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateFavori (Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        $collaborateur = null;
        if ($user->collaborateur_id !== $request->collaborateur) {
            $collaborateur = $request->collaborateur;
        }

        $update = $user->update([
                    'collaborateur_id' => $collaborateur
                ]);

        return ControllerResponse::update($update);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUpdate (): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        $user->update([
            'check_update' => true
        ]);

        return response()->json(true);
    }
}
