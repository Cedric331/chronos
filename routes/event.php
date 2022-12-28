<?php

use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

Route::post('/event/post', [EventController::class, 'store'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');
