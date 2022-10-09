<?php

use App\Http\Controllers\RotationController;
use Illuminate\Support\Facades\Route;

Route::get('/rotation', [RotationController::class, 'index'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable')
    ->name('rotation');

Route::get('/rotation/show', [RotationController::class, 'show'])
    ->middleware('auth')
    ->name('rotation.show');

Route::post('/rotation', [RotationController::class, 'store'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');

Route::patch('/rotation', [RotationController::class, 'update'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');

Route::delete('/rotation/{rotation}', [RotationController::class, 'delete'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');

Route::post('/generate', [RotationController::class, 'generatePlanning'])
    ->middleware('auth')
    ->middleware('role:Coordinateur|Administrateur|Responsable');
