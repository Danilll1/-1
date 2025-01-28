<?php

use App\Http\Controllers\ListController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', [ListController::class, 'index'])->name('home');
Route::get('/statuses', [ListController::class, 'statuses'])->name('statuses');


Auth::routes();

Route::post('/edit/{id}', [ListController::class, 'edit'])->name('edit');
Route::post('/create', [ListController::class, 'create'])->name('create');

Route::delete('/tasks/{id}', [ListController::class, 'destroy'])->name('delete');

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
