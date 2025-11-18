<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;

// Home Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Categories Routes
Route::resource('categories', CategoryController::class);

// Warehouses Routes  
Route::resource('warehouses', WarehouseController::class);

// Products Routes
Route::resource('products', ProductController::class);

// Stocks Routes
Route::prefix('stocks')->name('stocks.')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('index');
    Route::get('/transfer', [StockController::class, 'createTransfer'])->name('transfer.create');
    Route::post('/transfer', [StockController::class, 'storeTransfer'])->name('transfer.store');
});