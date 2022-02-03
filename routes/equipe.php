<?php

use App\Http\Controllers\EquipeController;
use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

Route::get('/equipe', [EquipeController::class, 'show'])
    ->middleware('auth')
    ->middleware('role:Coordinateur')
    ->name('equipe');

Route::post('/equipe', [EquipeController::class, 'store'])
    ->middleware('auth')
    ->middleware('role:Coordinateur')
    ->name('equipe.post');
