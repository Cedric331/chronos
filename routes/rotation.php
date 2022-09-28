<?php

use App\Http\Controllers\RotationController;
use Illuminate\Support\Facades\Route;

Route::get('/rotation', [RotationController::class, 'index'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable')
    ->name('rotation');

