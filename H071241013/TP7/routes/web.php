<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/destinasi', function () {
    return view('destinasi');
})->name('destinasi');

Route::get('/kuliner', function () {
    return view('kuliner');
})->name('kuliner');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

Route::get('/galeri', function () {
    return view('galeri');
})->name('galeri');

?>

