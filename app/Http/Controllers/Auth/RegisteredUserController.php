<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules;
use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        $url = URL::temporarySignedRoute('register.post', now()->addMinutes(15));

        return Inertia::render('Auth/Register', [
            'name' => $request->name,
            'email' => $request->email,
            'hub_id' => $request->hub,
            'status' => $request->status,
            'signature' => $url,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request['name'] = Crypt::decrypt($request->name);
        $request['email'] = Crypt::decrypt($request->email);
        $request['hub'] = Crypt::decrypt($request->hub_id);
        if ($request->status) {
            $request['status'] = Crypt::decrypt($request->status);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'min:8', 'confirmed', Rules\Password::defaults()],
        ]);

       $status = $request->status ? 'Coordinateur' : 'Conseiller';

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'status' => $status,
            'hub_id' => $request->hub,
            'password' => Hash::make($request->password),
        ]);

        if ($request->status) {
            $user->assignRole('Coordinateur');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
