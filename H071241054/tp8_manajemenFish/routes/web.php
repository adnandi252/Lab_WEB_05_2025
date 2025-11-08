<?php

use App\Http\Controllers\FishController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('fishes.index');
});

Route::resource('fishes', FishController::class);