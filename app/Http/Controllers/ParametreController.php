<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ParametreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Inertia\Response
     */
    public function show (): \Inertia\Response
    {
        return Inertia::render('Parametre');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        $update = $user->update([
                'color_travaille' => $request->jourTravaille !== '#000000' ? $request->jourTravaille : null,
                'color_conge' => $request->congePaye !== '#000000' ? $request->congePaye : null,
                'color_repos' => $request->repos !== '#000000' ? $request->repos : null,
                'color_technicien' => $request->technicien !== '#000000' ? $request->technicien : null,
                'color_texte' => $request->texte !== '#000000' ? $request->texte : null
            ]);

        return ControllerResponse::update($update);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset (Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        $update = $user->update([
            'color_travaille' => null,
            'color_conge' => null,
            'color_repos' => null,
            'color_technicien' => null,
            'color_texte' => null
        ]);

        return ControllerResponse::update($update);
    }
}
