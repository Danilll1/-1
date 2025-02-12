<?php

use App\Http\Controllers\FileController;
use App\Http\Controllers\ListController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', [ListController::class, 'index'])->name('home');
Route::get('/statuses', [ListController::class, 'statuses'])->name('statuses');
Auth::routes();

Route::post('/edit/{id}', [ListController::class, 'edit'])->name('edit');
Route::post('/create', [ListController::class, 'create'])->name('create');
Route::delete('/tasks/{id}', [ListController::class, 'destroy'])->name('delete');

Route::get('/files', [FileController::class,'index'])->name('files');
Route::post('/files-store', [FileController::class, 'store'])->name('store');

Route::post('/folders-store', [FileController::class, 'folderStore'])->name('folder.store');
Route::post('/folders-add', [FileController::class, 'folderAdd'])->name('folder.add');

Route::delete('/files/{id}', [FileController::class,'destroy'])->name('destroy.file');