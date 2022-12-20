<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\Hub;
use App\Models\Lien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LienController extends Controller
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
        $liens = Lien::where('hub_id', Auth::user()->hub_id)->paginate(12);

        return Inertia::render('Lien', [
            'liens' => $liens
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store (Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string|nullable',
            'url' => 'required|url'
        ]);

        Lien::create([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'hub_id' => Auth::user()->hub_id,
            'user_id' => Auth::id()
        ]);

        $liens = Lien::where('hub_id', Auth::user()->hub_id)->paginate(12);

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a ajouté un lien');

        return response()->json($liens);
    }

    /**
     * @param Request $request
     * @param Lien $lien
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (Request $request, Lien $lien): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'string|nullable',
            'url' => 'required|url'
        ]);

        $user = User::find(Auth::id());

        if ($lien->user_id === $user->id || $user->isCoordinateur() || $user->isAdmin()) {
            $update = $lien->update([
                'name' => $request->name,
                'description' => $request->description,
                'url' => $request->url,
                'hub_id' => Auth::user()->hub_id
            ]);
        } else {
            return response()->json('Action non autorisée', 401);
        }

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a modifié un lien');

        return ControllerResponse::update($update, $lien, true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete (Request $request, Lien $lien): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        if ($lien->user_id === $user->id || $user->isCoordinateur() || $user->isAdmin()) {
            $lien->delete();
        } else {
            return response()->json('Action non autorisée', 401);
        }

        $liens = Lien::where('hub_id', Auth::user()->hub_id)->paginate(12);

        Log::info('Utilisateur '.Auth::user()->name.' de '. Hub::find(Auth::user()->hub_id)->ville.' a supprimé un lien');

        return response()->json($liens);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search (Request $request): \Illuminate\Http\JsonResponse
    {
        if ($request->filtre === 2) {
            $user = User::select('id')
                ->where('hub_id', Auth::user()->hub_id)
                ->where('status' ,'Coordinateur')
                ->first();
            $id = $user->id;
        } elseif ($request->filtre === 3) {
            $id = Auth::id();
        } else {
            $id = null;
        }

        $liens = Lien::where('hub_id', Auth::user()->hub_id)
            ->where('name', 'LIKE' , '%'.$request->search.'%')
            ->where(function($query) use ($id) {
                if ($id !== null) {
                    return $query->where('user_id', $id);
                } else {
                    return $query;
                }
            })
            ->paginate(12);

        return response()->json($liens);

    }
}
