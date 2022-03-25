<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HubsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Coordinateur|Administrateur']);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index (): \Illuminate\Http\JsonResponse
    {
        return response()->json(Hub::all());
    }

    /**
     * @param Hub $hub
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser (Hub $hub): \Illuminate\Http\JsonResponse
    {
        $user = User::find(Auth::id());

        $update = $user->update([
                'hub_id' => $hub->id
            ]);

        return ControllerResponse::update($update);
    }
}
