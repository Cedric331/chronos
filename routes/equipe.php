<?php

use App\Http\Controllers\EquipeController;
use Illuminate\Support\Facades\Route;

Route::get('/equipe', [EquipeController::class, 'show'])
    ->middleware('auth')
    ->name('equipe');

Route::post('/equipe', [EquipeController::class, 'store'])
    ->middleware('auth')
    ->name('equipe.post');
