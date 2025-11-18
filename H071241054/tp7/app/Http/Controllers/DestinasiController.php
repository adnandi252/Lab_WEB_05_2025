<?php

namespace App\Http\Controllers;

class DestinasiController extends Controller
{
    public function destinasi()
    {
        $destinasi = [
            [
                'nama' => 'Buntu Burake',
                'gambar' => '/images/burake.jpg',
                'deskripsi' => 'Destinasi Wisata Religi, Patung Yesus Memberkati di Buntu Burake yang mengarah ke Kota Makale sebagai Pusat Kota di Kabupaten Tana Toraja.'
            ],
            [
                'nama' => 'Lembah Ollon',
                'gambar' => '/images/ollon1.jpg',
                'deskripsi' => 'Wisata Alam, Keindahan Lembah Ollon, dikenal juga dengan sebutan Bukit teletubbies. Lembah ini menyuguhkan panorama perbukitan hijau yang luas, padang rumput yang asri, serta udara sejuk khas pegunungan dengan beberapa kuda peliharaan warga setempat.'
            ],
            [
                'nama' => 'Sarambu Assing',
                'gambar' => '/images/sarambuAssing.jpg',
                'deskripsi' => 'Air Terjun Sarambu Assing merupakan hidden gem yang terletak di Lembang Patongloan, Kecamatan Bittuang, Kabupaten Tana Toraja, Sulawesi Selatan. Dikelilingi oleh pepohonan rindang dan suasana alam yang masih sangat asri, air terjun ini menawarkan keindahan alami yang menenangkan.'
            ],
            [
                'nama' => 'Pemakaman Batu, Lemo',
                'gambar' => '/images/lemo.jpg',
                'deskripsi' => 'Pemakaman Leluhur Suku Toraja, sejak abad ke-16. Batu Lemo berada di Kelurahan Lemo, Kecamatan Mengkendek, Kabupaten Tana Toraja, Sulawesi Selatan.'
            ],
            [
                'nama' => 'Danau Assa',
                'gambar' => '/images/danauAssa.jpg',
                'deskripsi' => 'Danau Kecil yang terbentuk secara alami. Terletak di Kalimbuang, Lembang Turunan, Kecamatan Sangalla, Kabupaten Tana Toraja.'
            ],
            [
                'nama' => 'Pango-Pango',
                'gambar' => '/images/pango_pango.jpg',
                'deskripsi' => 'Tempat terbaik untuk melihat Kota Makele dari ketinggian serta keindahan panorama sunset dan sunrise yang sangat cantik.'
            ]
        ];

        return view('destinasi', [
            'title' => 'Destinasi Wisata',
            'destinasi' => $destinasi
        ]);
    }
}