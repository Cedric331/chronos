<?php

use App\Http\Controllers\ParametreController;
use Illuminate\Support\Facades\Route;

Route::get('/parametre', [ParametreController::class, 'show'])
    ->middleware('auth')
    ->name('parametre');

Route::patch('/parametre/update', [ParametreController::class, 'update'])
    ->middleware('auth')
    ->name('parametre.update');

Route::patch('/parametre/reset', [ParametreController::class, 'reset'])
    ->middleware('auth')
    ->name('parametre.reset');
