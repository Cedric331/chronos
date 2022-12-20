<?php

namespace App\Http\Controllers;

use App\Helpers\ControllerResponse;
use App\Mail\NouveauHub;
use App\Mail\NouveauUtilisateur;
use App\Models\Hub;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:Responsable|Administrateur']);
    }

    /**
     * @return \Inertia\Response
     */
    public function show (): \Inertia\Response
    {
        $hubs = Hub::with('members')->get();

        $logFile = file(storage_path().'/logs/laravel.log');
        $logCollection = collect();
        foreach ($logFile as $line) {
            if (str_contains($line, 'local.INFO:')) {
                $logCollection->push(str_replace(['local.INFO:', '[', ']'], '', htmlspecialchars($line)));
            }
        }

        return Inertia::render('Admin/Dashboard', [
            'hubs' => $hubs,
            'logs' => $logCollection,
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

        $hub = Hub::create([
            'ville' => $request->ville,
        ]);

        if ($request->name && $request->email) {

            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email'
            ]);

            $url = URL::signedRoute('register', [
                'name' => Crypt::encrypt($request->name),
                'email' => Crypt::encrypt($request->email),
                'status' => Crypt::encrypt('Coordinateur'),
                'hub' => Crypt::encrypt($hub->id),
            ]);

            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'hub' => $hub->ville,
                'url' => $url
            ];

            Mail::to($request->email)->send(new NouveauHub($data));
        }

        if ($hub) {
            return response()->json(Hub::with('members')->find($hub->id));
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
            'status' => 'nullable|string',
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

        if ($request->status === 'Coordinateur') {
            Mail::to($request->email)->send(new NouveauHub($data));
        } else {
            Mail::to($request->email)->send(new NouveauUtilisateur($data));
        }

        return response()->json(true);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkUpdate (): \Illuminate\Http\JsonResponse
    {
        $users = User::where('check_update', true)->get();

        foreach ($users as $user) {
            $user->update([
                'check_update' => false
            ]);
        }

        return response()->json(true);
    }

    public function updateStatus (Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($request->id);
        if ($user->isAdmin() || $user->id === Auth::id()) {
            return response()->json('Action non autorisée', 401);
        }
        $user->update([
            'status' => $request->status
        ]);

        if ($request->status !== 'Conseiller') {
            $user->assignRole($request->status);
        } else {
            $user->syncRoles([]);
        }

        return ControllerResponse::update($user);
    }
}
