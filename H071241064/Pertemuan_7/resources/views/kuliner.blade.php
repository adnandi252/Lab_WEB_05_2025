@extends('layouts.master')

@section('title', 'Kuliner Khas - Eksplor Pariwisata Kendari')

@section('content')
<section class="relative bg-slate-900 text-white overflow-hidden py-32">
    <div class="absolute inset-0">
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>
     
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
                Cita Rasa <span class="text-amber-400">Kendari</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 leading-relaxed">
                Nikmati kelezatan kuliner tradisional dengan bumbu rempah yang khas dan cita rasa yang menggugah selera.
            </p>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <x-card
                title="Sinonggi"
                image="images/sinonggi.webp"
                description="Sinonggi terbuat dari sagu. Makanan ini memiliki tekstur kenyal seperti lem dan rasanya hambar, sehingga biasanya disantap dengan berbagai lauk pendamping seperti ikan kuah, sayur, dan sambal untuk menambah rasa."
            />

            <x-card
                title="Sate Pokea"
                image="images/satepokea.jpeg"
                description="Sate Gogos Pokea terdiri dari sate kerang air tawar (pokea) yang disajikan dengan 'gogos' (ketan bakar) dan bumbu kacang. Memiliki rasa gurih dan nikmat dengan tekstur sate kerang yang kenyal dan lembut dari ketan bakar,"
            />

            <x-card
                title="Kasuami"
                image="images/Kasuami.jpg"
                description="Terbuat dari singkong atau ubi kayu yang diparut, dikeringkan serta dikukus, lalu dicetak dengan bentuk seperti tumpeng. Biasa dinikmati dengan lauk pauk, salah satunya ikan."
            />

            <x-card
                title="Kabuto"
                image="images/kabuto.webp"
                description="Terbuat dari singkong atau ubi. Ubi atau singkong dibiarkan mengering dan berjamur, lalu diberi tambahan parutan kelapa diatasnya. Umumnya dinikmati dengan lauk seperti ikan asin goreng."
            />

            <x-card
                title="Kacang Mete"
                image="images/kacangmete.jpg"
                description="Kacang mete adalah oleh-oleh khas dari Sulawesi Tenggara yang terkenal dengan kualitas super dan rasanya yang gurih. Produk ini tersedia dalam berbagai olahan, mulai dari yang mentah hingga yang siap santap dengan beragam bumbu."
            />

            <x-card
                title="Lapa-Lapa"
                image="images/lapalapa.jpeg"
                description="Terbuat dari beras pulut yang dimasak dengan santan. Dibungkus dengan daun kelapa, lalu direbus hingga matang. Teksturnya yang lembut dan gurih membuatnya cocok disantap dengan lauk seperti ikan bakar atau coto Makassar."
            />
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">
            Kekayaan Rempah <span class="text-amber-600">Nusantara</span>
        </h2>
            <p class="text-lg text-slate-600 leading-relaxed mb-6">
                Kuliner Kendari kaya akan penggunaan rempah-rempah dan bahan-bahan lokal yang segar. Pengaruh budaya Buton dan Sulawesi Tenggara memberikan keunikan tersendiri pada setiap hidangan.
            </p>
            <p class="text-lg text-slate-600 leading-relaxed mb-6">
                Ikan laut segar menjadi bahan utama dalam banyak masakan khas, diolah dengan bumbu rempah tradisional yang telah diwariskan turun-temurun.
            </p>
            <p class="text-lg text-slate-600 leading-relaxed">
                Jangan lewatkan kesempatan untuk mencicipi langsung di pasar tradisional atau warung-warung lokal yang tersebar di seluruh kota Kendari.
            </p>
    </div>
</section>
@endsection