<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KendariController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function destinasi()
    {
        return view('destinasi');
    }

    public function kuliner()
    {
        return view('kuliner');
    }

    public function galeri()
    {
        return view('galeri');
    }

    public function kontak()
    {
        return view('kontak');
    }
}
