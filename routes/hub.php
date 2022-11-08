<?php

use App\Http\Controllers\HubsController;
use Illuminate\Support\Facades\Route;

Route::get('/hub', [HubsController::class, 'index'])
                ->middleware('auth')
                ->name('hub');

Route::get('/hub/admin', [HubsController::class, 'indexWithMember'])
    ->middleware('auth')
    ->name('hub.admin');

Route::patch('/hub/{hub}/user', [HubsController::class, 'updateUser'])
    ->middleware('auth')
    ->name('update.hub.user');

Route::patch('/hub/{hub}/authorize', [HubsController::class, 'updateAuthorize'])
    ->middleware('auth')
    ->name('update.hub.authorize');

Route::get('/gestion/hub', [HubsController::class, 'getGestionHub'])
    ->middleware('auth')
    ->name('gestion.hub');
