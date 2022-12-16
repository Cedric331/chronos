<?php

namespace App\Http\Controllers;

use App\Mail\ContactAdministrateur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function send (Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'subject' => 'required|string',
            'text' => 'required|string'
        ]);

        $data = [
            'subject' => $request->subject,
            'text' => explode("\n", $request->text),
            'author' => [
                'identifiant' => Auth::id(),
                'name' => Auth::user()->name,
                'email' => Auth::user()->email
            ]
        ];

        $admin = User::role('administrateur')->first();

        Mail::to($admin->email)
            ->send(new ContactAdministrateur($data));

        return response()->json(true);
    }
}
