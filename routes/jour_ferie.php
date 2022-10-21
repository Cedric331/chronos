<?php

use App\Http\Controllers\JourFerieController;
use Illuminate\Support\Facades\Route;

Route::get('/jf', [JourFerieController::class, 'index'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');

Route::post('/generate/jf', [JourFerieController::class, 'store'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');

Route::delete('/jf', [JourFerieController::class, 'delete'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');
