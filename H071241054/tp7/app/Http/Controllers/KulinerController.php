<?php

namespace App\Http\Controllers;

class KulinerController extends Controller
{
    public function kuliner()
    {
        $kuliner = [
            [
                'nama' => 'Kopi Toraja',
                'gambar' => '/images/kopiToraja.jpg',
                'deskripsi' => 'Kopi Khas Toraja.'
            ],
            [
                'nama' => 'Tollo Pammarasan',
                'gambar' => '/images/pantollo.jpg',
                'deskripsi' => 'Makanan khas Toraja yang dimasak dengan keluwak. Biasanya menggunakan daging babi ataupun ikan.'
            ],
            [
                'nama' => 'Deppa Tori',
                'gambar' => '/images/deppaTori.jpg',
                'deskripsi' => 'Kue ringan khas Toraja.'
            ],
            [
                'nama' => "Pa'piong",
                'gambar' => '/images/papiong.jpeg',
                'deskripsi' => 'Makanan khas Toraja yang dimasak dengan menggunakan bambu. Biasanya menggunakan daging babi, ayam, ataupun ikan dengan daun mayana sebagai pelengkap.'
            ],
            [
                'nama' => "Piong Bo'bo'",
                'gambar' => '/images/piongNasi.jpg',
                'deskripsi' => 'Beras yang dimasak menggunakan bambu..'
            ],
            [
                'nama' => 'Tuak',
                'gambar' => '/images/tuak.jpg',
                'deskripsi' => 'Minuman khas Toraja. Tuak terbuat dari cairan pohon aren yang berkualitas, kemudian disaring hingga siap untuk dikonsumsi. Tuak dianggap sebagai minuman sakral bagi masyarakat Toraja, warnanya yang putih diidentikkan dengan warna lambang kesucian.'
            ]
        ];

        return view('kuliner', [
            'title' => 'Kuliner Khas',
            'kuliner' => $kuliner
        ]);
    }
}