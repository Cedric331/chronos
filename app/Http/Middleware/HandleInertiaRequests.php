<?php

namespace App\Http\Middleware;

use App\Models\Hub;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'auth.user' => fn () => $request->user()
                ? $request->user()->only(
                    'id',
                    'name',
                    'email',
                    'collaborateur_id',
                    'status',
                    'check_update',
                    'color_travaille',
                    'color_conge',
                    'color_repos',
                    'color_technicien',
                    'color_texte',
                    'hub_id')
                : null,
            'auth.user.coordinateur' => fn () => $request->user()
                ? $request->user()->isCoordinateur() || $request->user()->isAdmin()
                : null,
            'auth.user.admin' => fn () => $request->user()
                ? $request->user()->isAdmin()
                : null,
            'hub' => $request->user() ? Hub::find($request->user()->hub_id) : null,
            'hub.horodatage' => $request->user() ? Hub::find($request->user()->hub_id)->horodatage() : null,
            'hubs' => Hub::all()
        ]);
    }
}
