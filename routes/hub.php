<?php

use App\Http\Controllers\HubsController;
use Illuminate\Support\Facades\Route;

Route::patch('/hub/{id}', [HubsController::class, 'update'])
                ->middleware('auth')
                ->name('update.hub');

