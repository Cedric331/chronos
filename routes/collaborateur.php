<?php

use App\Http\Controllers\CollaborateurController;
use Illuminate\Support\Facades\Route;

Route::get('/collaborateur', [CollaborateurController::class, 'index'])
    ->middleware('auth')
    ->name('collaborateur');

Route::post('/collaborateur', [CollaborateurController::class, 'store'])
    ->middleware('auth')
    ->name('collaborateur.post');

Route::delete('/collaborateur/{collaborateur}', [CollaborateurController::class, 'delete'])
    ->middleware('auth')
    ->name('collaborateur.delete');
