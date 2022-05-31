<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InformationEquipeController extends Controller
{

    /**
     * @return \Inertia\Response
     */
    public function show (): \Inertia\Response
    {
        $users = User::where('hub_id', Auth::user()->hub_id)
            ->get();

        foreach ($users as $user) {
            $user->anniversaire_en = $user->anniversaire;
            $user->anniversaire = $user->anniversaire ? date('d-m-Y', strtotime($user->anniversaire)) : null;
        }

        return Inertia::render('InformationEquipe', [
            'users' => $users
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request, User $user): \Illuminate\Http\JsonResponse
    {

        if ($user->id === Auth::id() || Auth::user()->isCoordinateur()) {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'phone' => 'nullable|integer|digits_between:9,10',
                'anniversaire' => 'nullable|date'
            ]);

            $update = $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'anniversaire' => $request->anniversaire,
            ]);

            return ControllerResponse::update($update);
        }
        else {
            return response()->json(['error' => 'Action non autorisée'], 401);
        }
    }

}
