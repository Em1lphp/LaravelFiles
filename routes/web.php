<?php

use App\Http\Controllers\Files\FileController;
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::get('/file', [\App\Http\Controllers\Files\FileController::class, 'index'])->name('file.index');
Route::post('/file', [\App\Http\Controllers\Files\FileController::class, 'store'])->name('file.store');
Route::post('/file/pause', [\App\Http\Controllers\Files\FileController::class, 'pauseUpload'])->name('file.pause');
Route::post('/file/resume', [\App\Http\Controllers\Files\FileController::class, 'resumeUpload'])->name('file.resume');

