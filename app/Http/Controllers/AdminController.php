<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Mail\NouveauHub;
use App\Mail\NouveauUtilisateur;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Administrateur']);
    }

    /**
     * @return \Inertia\Response
     */
    public function show (): \Inertia\Response
    {
        $hubs = Hub::with('members')->get();

        return Inertia::render('Admin/Dashboard', [
            'hubs' => $hubs,
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createHub (Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'ville' => 'required', 'string'
        ]);

        $create = Hub::create([
            'ville' => $request->ville,
        ]);

        if ($create) {
            return response()->json(Hub::with('members')->find($create->id));
        } else {
            return response()->json('Erreur', 422);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        $hub = Hub::find($request->hub);
        $url = URL::signedRoute('register', [
            'name' => Crypt::encrypt($request->name),
            'email' => Crypt::encrypt($request->email),
            'status' => Crypt::encrypt($request->status),
            'hub' => Crypt::encrypt($hub->id),
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'hub' => $hub->ville,
            'url' => $url
        ];

        if ($request->status) {
            Mail::to($request->email)->send(new NouveauHub($data));
        } else {
            Mail::to($request->email)->send(new NouveauUtilisateur($data));
        }

        return response()->json(true);
    }
}
