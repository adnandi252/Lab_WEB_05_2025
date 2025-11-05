@extends('layouts.master')

@section('content')
<h2 style="color: #8B4513; text-align: center; margin-bottom: 30px; font-size: 2em;">Kuliner Khas Toraja</h2>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
    
    <x-card 
        image="/images/pa'piong.jpg"
        title="Pa'piong"
        description="Pa'piong adalah makanan khas Toraja yang dimasak di dalam bambu. 
                     Biasanya berisi ayam atau ikan yang dibumbui dengan rempah-rempah tradisional
                    kemudian dibakar dalam bambu. Proses memasak ini memberikan aroma dan rasa yang khas pada makanan."
    />

    <x-card 
        image="/images/pamarrasan.jpg"
        title="Pamarrasan"
        description="Pamarrasan adalah makanan tradisional Toraja yang terbuat dari daging
                      kerbau,babi, dan ikan yang dimasak dengan bumbu khas yaitu bumbu pamarrasan yang kaya rempah.
                        Hidangan ini biasanya disajikan dalam acara-acara adat dan upacara tradisional di Toraja."
    />

    <x-card 
        image="/images/piong bo'bo.webp"
        title="piong bo'bo"
        description="Piong Bo’bo bukan sekadar makanan, melainkan warisan kuliner budaya Toraja 
                    yang memadukan cita rasa tradisional, aroma alami bambu, serta nilai kebersamaan masyarakatnya. 
                    Hidangan ini menjadi bukti bagaimana masyarakat Toraja menjaga tradisi dan keunikan dalam 
                    setiap sajian mereka."
    />

    <x-card 
        image="/images/dangke.webp"
        title="Dangke"
        description="Dangke adalah keju tradisional khas Toraja yang terbuat dari susu kerbau atau sapi. 
                    Teksturnya yang lembut dan rasanya yang unik membuat dangke menjadi oleh-oleh favorit dari Toraja. 
                    Dangke biasanya dimakan langsung atau dijadikan campuran masakan."
    />

    <x-card 
        image="/images/deppa tori.webp"
        title="deppa tori'"
        description="Kudapan manis yang juga terbuat dari tepung beras ketan dan gula merah, 
                    tapi digoreng sehingga teksturnya renyah di luar dan lembut di dalam. 
                    Biasanya menjadi oleh-oleh khas Toraja."
    />

     <x-card 
        image="/images/pokon.webp"
        title="pokon"
        description="Nasi ketan yang dimasak dengan santan dan dibungkus daun pisang berbentuk segitiga. 
                     Biasanya disajikan sebagai makanan pendamping lauk pauk di Toraja."

    
    />

    <x-card 
        image="/images/pa'lawa.jpg"
        title="pa'lawa"
        description="Pa’lawa adalah masakan tradisional Toraja yang terbuat dari daging babi atau kerbau 
                    yang dimasak dengan bumbu khas Toraja seperti bawang merah, bawang putih, cabai, jahe, dan lengkuas. 
                    Masakan ini biasanya dimasak dalam bambu (pa’piong) atau direbus hingga bumbunya meresap sempurna. 
                    Pa’lawa sering disajikan pada upacara adat seperti rambu solo’ (upacara kematian) atau rambu tuka’ (upacara syukuran), 
                    melambangkan kebersamaan dan rasa syukur masyarakat Toraja."

    
    />


    <x-card 
        image="/images/dangkot ayam.webp"
        title="dangkot ayam"
        description="Dangkot ayam (singkatan dari dada’ danging manuk to’kko) adalah kuliner pedas khas Toraja 
                    yang berbahan dasar ayam kampung. Daging ayam dipotong kecil, kemudian ditumis dengan rempah-rempah 
                    seperti cabai, serai, kemiri, dan daun jeruk hingga kering dan meresap. Rasanya gurih pedas dan sangat 
                    nikmat disantap bersama nasi hangat. Dangkot ayam sering menjadi hidangan utama dalam acara keluarga maupun pesta adat."

    
    />
</div>
@endsection