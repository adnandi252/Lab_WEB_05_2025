@extends('layouts.master')

@section('content')
<h2 style="color: #8B4513; text-align: center; margin-bottom: 30px; font-size: 2em;">Destinasi Wisata Toraja</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    
    <x-card 
        image="/images/ollon.jpg"
        title="Ollon"
        description="Ollon adalah sebuah desa wisata yang terkenal dengan pemandangan alamnya yang indah. Desa ini menawarkan hamparan sawah bertingkat yang hijau, rumah Tongkonan tradisional, dan udara pegunungan yang sejuk. Tempat yang sempurna untuk menikmati keindahan alam Toraja."
    />

    <x-card 
        image="/images/lolai.jpg"
        title="Lolai"
        description="Lolai adalah destinasi wisata alam yang menawarkan pemandangan pegunungan dan lembah yang spektakuler. Di sini pengunjung dapat menikmati udara segar pegunungan, melihat rumah-rumah adat Tongkonan, dan merasakan kehidupan masyarakat Toraja yang masih sangat kental dengan tradisi."
    />

    <x-card 
        image="/images/burake.jpg"
        title="Burake"
        description="Burake merupakan kawasan wisata yang terkenal dengan panorama alamnya yang memukau. Tempat ini menjadi spot favorit untuk menikmati sunset dan sunrise di Toraja. Pengunjung dapat melihat hamparan perbukitan hijau dan pemandangan Tongkonan yang tersebar di lembah."
    />

    <x-card 
        image="/images/ke'te kesu.jpg"
        title="Ke'te Kesu"
        description="Ke'te Kesu adalah desa adat yang paling terkenal di Toraja. Di sini terdapat deretan rumah Tongkonan yang berusia ratusan tahun, lumbung padi tradisional, dan kuburan batu (Liang Pa'). Tempat ini merupakan representasi sempurna dari budaya dan arsitektur tradisional Toraja."
    />

    <x-card 
        image="/images/kalimbuang bori'.jpg"
        title="kalimbuang bori'"
        description="Bori’ Kalimbuang adalah salah satu situs wisata budaya dan sejarah paling terkenal di Tana Toraja, 
                    Sulawesi Selatan. Tempat ini dikenal sebagai kompleks megalitikum yang menyimpan batu-batu besar 
                    berdiri (menhir) peninggalan leluhur masyarakat Toraja. Situs ini terletak di desa Bori’, Kecamatan Sesean, 
                    sekitar 6 km dari kota Rantepao, pusat wisata Toraja."
    />

    <x-card 
        image="/images/londa.jpg"
        title="Londa"
        description="Londa adalah kompleks pemakaman gua yang sangat terkenal di Toraja. Di sini terdapat gua-gua alami yang digunakan sebagai tempat pemakaman dengan patung-patung Tau-Tau (patung leluhur) yang menjaga makam. Londa memberikan pengalaman wisata budaya yang unik dan misterius."
    />

    <x-card 
        image="/images/sumalu.jpg"
        title="sumalu"
        description="Tilanga adalah sebuah kolam alami di Lemo, Toraja Utara, yang terkenal karena airnya yang sangat jernih 
                    dan dihuni oleh ikan masapi, ikan khas Toraja yang dianggap keramat. Kolam ini dikelilingi tebing batu kapur dan 
                    pepohonan rindang, menciptakan suasana tenang dan sejuk. Pengunjung dapat berenang atau menikmati pemandangan alam 
                    yang asri sambil mengenal kepercayaan dan budaya masyarakat setempat."
    />

    <x-card 
        image="/images/tilanga.jpg"
        title="tilanga"
        description="Sumalu, atau Gunung Sesean-Sumalu, merupakan destinasi wisata alam sekaligus spiritual di Toraja Utara. 
                    Dari puncaknya, pengunjung bisa menikmati panorama indah perbukitan dan kabut pagi yang menyelimuti lembah Toraja. 
                    Di kawasan ini juga terdapat situs budaya seperti kuburan batu kuno dan rumah adat Tongkonan. 
                    Tempat ini sering dijadikan lokasi trekking serta refleksi budaya dan keagamaan masyarakat Toraja."
    />
</div>
@endsection