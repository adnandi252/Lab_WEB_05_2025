<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DestinasiController;
use App\Http\Controllers\KulinerController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\KontakController;

Route::get('/', [HomeController::class, 'home']);
Route::get('/destinasi', [DestinasiController::class, 'destinasi']);
Route::get('/kuliner', [KulinerController::class, 'kuliner']);
Route::get('/galeri', [GaleriController::class, 'galeri']);
Route::get('/kontak', [KontakController::class, 'kontak']);