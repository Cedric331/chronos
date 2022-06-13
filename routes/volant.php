<?php

use App\Http\Controllers\TraitementVolantController;
use App\Http\Controllers\VolantController;
use Illuminate\Support\Facades\Route;

Route::get('/volant/gestion', [VolantController::class, 'listVolant'])
    ->middleware('auth')
    ->name('volant.list');

Route::post('/volant/add', [VolantController::class, 'addVolant'])
    ->middleware('auth')
    ->name('volant.add');

Route::delete('/volant/delete', [VolantController::class, 'deleteVolant'])
    ->middleware('auth')
    ->name('volant.delete');

Route::get('/volant/traitement', [TraitementVolantController::class, 'show'])
    ->middleware('auth')
    ->name('volant.show');

Route::post('/volant/traitement', [TraitementVolantController::class, 'store'])
    ->middleware('auth')
    ->name('volant.traitement.post');

Route::patch('/volant/traitement/{traitement}', [TraitementVolantController::class, 'update'])
    ->middleware('auth')
    ->name('volant.traitement.update');

Route::delete('/volant/traitement/{traitement}', [TraitementVolantController::class, 'delete'])
    ->middleware('auth')
    ->name('volant.traitement.delete');

Route::post('/volant/traitement/hub', [TraitementVolantController::class, 'changeHub'])
    ->middleware('auth')
    ->name('volant.traitement.hub');

