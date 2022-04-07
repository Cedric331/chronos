<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\Lien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $liens = Lien::where('hub_id', Auth::user()->hub_id)->paginate(6);

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

        $store = Lien::create([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'hub_id' => Auth::user()->hub_id,
            'user_id' => Auth::id()
        ]);

        return ControllerResponse::store($store);
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

        if ($lien->user_id === $user->id || $lien->user_id === $user->isCoordinateur() || $lien->user_id === $user->isAdmin()) {
            $update = $lien->update([
                'name' => $request->name,
                'description' => $request->description,
                'url' => $request->url,
                'hub_id' => Auth::user()->hub_id
            ]);
        } else {
            return response()->json('Action non autorisée', 401);
        }

        return ControllerResponse::update($update, $lien, true);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete (Request $request, Lien $lien): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        if ($lien->user_id === $user->id || $lien->user_id === $user->isCoordinateur() || $lien->user_id === $user->isAdmin()) {
            $delete = $lien->delete();
        } else {
            return response()->json('Action non autorisée', 401);
        }

        return ControllerResponse::delete($delete);
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
            ->paginate(6);

        return response()->json($liens);

    }
}
