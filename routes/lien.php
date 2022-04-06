<?php

use App\Http\Controllers\LienController;
use Illuminate\Support\Facades\Route;

Route::get('/lien', [LienController::class, 'show'])
    ->middleware('auth')
    ->name('lien');

Route::post('/lien', [LienController::class, 'store'])
    ->middleware('auth')
    ->name('lien.post');

Route::patch('/lien/{lien}', [LienController::class, 'update'])
    ->middleware('auth')
    ->name('lien.patch');

Route::delete('/lien/{lien}', [LienController::class, 'delete'])
    ->middleware('auth')
    ->name('lien.delete');

Route::post('/lien/search', [LienController::class, 'search'])
    ->middleware('auth')
    ->name('lien.search');
