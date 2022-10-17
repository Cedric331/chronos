<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class JourFerieController extends Controller
{
    public function store (Request $request)
    {

        $res = Http::withOptions([
            'verify' => false,
        ])->get('https://calendrier.api.gouv.fr/jours-feries/metropole/' . $request->annee . '.json');
        dd($res->json());

        dd($jours->collect());
    }
}
