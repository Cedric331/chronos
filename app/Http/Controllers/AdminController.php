<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Mail\NouveauHub;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Administrateur']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createHub (Request $request): \Illuminate\Http\JsonResponse
    {
        $hub = Hub::create([
            'departement' => $request->departement,
            'code_postal' => $request->code_postal,
            'adresse' => $request->adresse,
            'complement_adresse' => $request->complement_adresse,
            'abonne_freebox' => $request->abonne_freebox,
            'abonne_mobile' => $request->abonne_mobile
        ]);

        return ControllerResponse::store($hub);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function creeateCoordinateur(Request $request): \Illuminate\Http\JsonResponse
    {
        $hub = Hub::find($request->hub);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => 'Coordinateur',
            'hub_id' => $hub->id,
        ]);

        $user->assignRole('Coordinateur');

        $url = URL::signedRoute('register', [
            'name' => Crypt::encrypt($request->name),
            'email' => Crypt::encrypt($request->email),
            'hub' => Crypt::encrypt($hub->id),
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'hub' => $hub->name,
            'url' => $url
        ];

        Mail::to($request->email)->send(new NouveauHub($data));

        return ControllerResponse::store($user);
    }
}
