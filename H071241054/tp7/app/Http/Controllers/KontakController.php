<?php

namespace App\Http\Controllers;

class KontakController extends Controller
{
    public function kontak()
    {
        return view('kontak', [
            'title' => 'Kontak Kami'
        ]);
    }
}