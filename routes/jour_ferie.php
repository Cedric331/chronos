<?php

use App\Http\Controllers\JourFerieController;
use Illuminate\Support\Facades\Route;

Route::post('/generate/jf', [JourFerieController::class, 'store'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');
