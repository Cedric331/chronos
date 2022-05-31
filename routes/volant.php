<?php

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
