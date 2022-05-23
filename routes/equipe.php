<?php

use App\Http\Controllers\EquipeController;
use App\Http\Controllers\InformationEquipeController;
use Illuminate\Support\Facades\Route;

Route::get('/equipe', [EquipeController::class, 'show'])
    ->middleware('auth')
    ->name('equipe');

Route::post('/equipe', [EquipeController::class, 'store'])
    ->middleware('auth')
    ->name('equipe.post');

Route::patch('/equipe/{user}', [EquipeController::class, 'update'])
    ->middleware('auth')
    ->name('equipe.patch');

Route::delete('/equipe/{user}', [EquipeController::class, 'delete'])
    ->middleware('auth')
    ->name('equipe.delete');

Route::get('/equipe/information', [InformationEquipeController::class, 'show'])
    ->middleware('auth')
    ->name('equipe.information');

Route::patch('/equipe/information/{user}', [InformationEquipeController::class, 'update'])
    ->middleware('auth')
    ->name('equipe.information.update');
