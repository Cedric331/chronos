<?php

use App\Http\Controllers\PlanningController;
use Illuminate\Support\Facades\Route;

Route::post('/import/hub/{hub}', [PlanningController::class, 'import'])
    ->middleware('auth')
    ->name('import');

Route::get('/planning', [PlanningController::class, 'loadPlanning'])
    ->middleware('auth')
    ->name('planning');

Route::get('/planning/date', [PlanningController::class, 'loadPlanningDate'])
    ->middleware('auth')
    ->name('planning.date');

Route::patch('/planning/update', [PlanningController::class, 'updatePlanning'])
    ->middleware('auth')
    ->name('planning.update');
