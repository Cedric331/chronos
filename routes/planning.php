<?php

use App\Http\Controllers\GeneratePlanningController;
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

Route::get('/planning/week', [PlanningController::class, 'loadPlanningWeek'])
    ->middleware('auth')
    ->name('planning.week');

Route::patch('/planning/update', [PlanningController::class, 'updatePlanning'])
    ->middleware('auth')
    ->name('planning.update');

Route::patch('/planning/update/rotation', [PlanningController::class, 'updateRotationPlanning'])
    ->middleware('auth')
    ->name('planning.update.rotation');

Route::patch('/planning/update/teletravail', [PlanningController::class, 'updateTeletravail'])
    ->middleware('auth')
    ->name('planning.update.teletravail');

Route::get('/planning/generate', [GeneratePlanningController::class, 'generetePlanning'])
    ->middleware('auth')
    ->name('generate');
