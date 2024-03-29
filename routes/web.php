<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/hub.php';
require __DIR__.'/equipe.php';
require __DIR__.'/admin.php';
require __DIR__.'/planning.php';
require __DIR__.'/parametre.php';
require __DIR__.'/lien.php';
require __DIR__.'/rotation.php';
require __DIR__.'/jour_ferie.php';
require __DIR__.'/collaborateur.php';
require __DIR__.'/contact.php';
require __DIR__.'/event.php';
