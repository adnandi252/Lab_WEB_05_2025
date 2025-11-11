<?php

use App\Http\Controllers\FishesController;
use Illuminate\Support\Facades\Route;

// Redirect homepage ke daftar ikan
Route::get('/', function () {
    return redirect()->route('fishes.index');
});

// Resource route untuk CRUD ikan
Route::resource('fishes', FishesController::class);