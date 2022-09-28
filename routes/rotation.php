<?php

use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

Route::get('/rotation', [PlanningController::class, 'import'])
    ->middleware('auth')
    ->name('rotation');

