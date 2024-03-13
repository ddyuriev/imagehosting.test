<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImageController;

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


Route::get('/', [ImageController::class, 'index'])->name('image.index');
Route::get('/upload', [ImageController::class, 'upload'])->name('image.upload');
Route::post('/', [ImageController::class, 'store'])->name('image.store');
Route::get('/download/{name}', [ImageController::class, 'downloadZip'])->name('image.download');

