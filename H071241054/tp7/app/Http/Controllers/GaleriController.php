<?php

namespace App\Http\Controllers;

class GaleriController extends Controller
{
    public function galeri()
    {
        $fotos = [
            '/images/badong.jpg',
            '/images/manene.jpg',
            '/images/musikBambu.jpeg',
            '/images/MapasilagaTedong.jpg',
            '/images/tautau2.jpeg',
            '/images/penari.jpg',
            '/images/ukir.jpeg',
            '/images/ukir2.jpeg',
            '/images/tenunToraja.jpg',
            '/images/danauAssa.jpg',
            '/images/ollon1.jpg',
            '/images/pango_pango.jpg'
        ];

        return view('galeri', [
            'title' => 'Galeri Foto',
            'fotos' => $fotos
        ]);
    }
}