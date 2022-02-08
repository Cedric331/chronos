<?php

use App\Http\Controllers\HubsController;
use Illuminate\Support\Facades\Route;

Route::get('/hub', [HubsController::class, 'index'])
                ->middleware('auth')
                ->name('hub');

Route::patch('/hub/{id}', [HubsController::class, 'update'])
                ->middleware('auth')
                ->name('update.hub');

