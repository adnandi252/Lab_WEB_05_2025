<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KendariController;

Route::get('/', [KendariController::class, 'home'])->name('home');
Route::get('/destinasi', [KendariController::class, 'destinasi'])->name('destinasi');
Route::get('/kuliner', [KendariController::class, 'kuliner'])->name('kuliner');
Route::get('/galeri', [KendariController::class, 'galeri'])->name('galeri');
Route::get('/kontak', [KendariController::class, 'kontak'])->name('kontak');
