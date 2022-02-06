<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EquipeController;
use Illuminate\Support\Facades\Route;

Route::get('/administration', [AdminController::class, 'show'])
    ->middleware('auth')
    ->middleware('role:Administrateur')
    ->name('administration');

Route::post('/administration/create/hub', [AdminController::class, 'createHub'])
    ->middleware('auth')
    ->middleware('role:Administrateur')
    ->name('administration.hub');

Route::post('/administration/create/user', [AdminController::class, 'createUser'])
    ->middleware('auth')
    ->middleware('role:Administrateur')
    ->name('administration.user');
