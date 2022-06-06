<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class VolantController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Responsable|Administrateur']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse|\Inertia\Response
     */
    public function listVolant (): \Illuminate\Http\JsonResponse|\Inertia\Response
    {
        return Inertia::render('GestionVolant', [
            'users' => User::with('roles')
                ->orderBy('name')
                ->get()
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addVolant (Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::find($request->users);

        foreach ($users as $user) {
            $user->assignRole('Volant');
        }

        $users = User::with('roles')
                    ->orderBy('name')
                    ->get();

        return response()->json($users);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteVolant (Request $request): \Illuminate\Http\JsonResponse
    {
        $users = User::find($request->users);

        foreach ($users as $user) {
            $user->removeRole('Volant');
        }

        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }

}