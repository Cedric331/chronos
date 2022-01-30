<?php

use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

Route::post('/import/hub/{hub}', [PlanningController::class, 'import'])
    ->middleware('auth')
    ->name('import');

Route::get('/planning', [PlanningController::class, 'loadPlanning'])
    ->middleware('auth')
    ->name('planning');
