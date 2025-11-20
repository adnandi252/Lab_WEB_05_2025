@extends('layouts.master')

@section('title', 'Home - Eksplor Pariwisata Kendari')

@section('content')

<section class="relative bg-slate-900 text-white overflow-hidden min-h-screen flex items-center">
    <div class="absolute inset-0">
        <img src="images/sultra.webp" alt="Kendari" class="w-full h-full object-cover opacity-50">
        <div class="absolute inset-0 bg-black opacity-40"></div>
    </div>
    

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 z-10">
        <div class="max-w-4xl">
            <h1 class="text-5xl md:text-8xl font-black mb-6 leading-tight drop-shadow-2xl">
                Selamat Datang di <span class="text-emerald-400">Kendari</span>
            </h1>
            <p class="text-xl md:text-3xl text-gray-100 mb-8 leading-relaxed font-light">
                Kota pesisir di Sulawesi Tenggara yang menyimpan keindahan alam tropis, kekayaan budaya, dan kelezatan kuliner khas yang menggugah selera.
            </p>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-16 items-center">
            <div>
                <h2 class="text-4xl md:text-5xl font-black mb-6 leading-tight">
                    Mutiara di <span class="text-emerald-400">Teluk Kendari</span>
                </h2>
                <p class="text-gray-300 text-lg leading-relaxed mb-6">
                    Kendari adalah ibu kota Provinsi Sulawesi Tenggara yang terletak di pesisir Teluk Kendari. Kota ini dikenal dengan keindahan alamnya yang memesona, mulai dari pantai berpasir putih, pulau-pulau eksotis, hingga air terjun yang menawan.
                </p>
                <p class="text-gray-300 text-lg leading-relaxed mb-6">
                    Tidak hanya kaya akan destinasi wisata alam, Kendari juga memiliki warisan budaya yang kental dengan tradisi Kesultanan Buton. Kota ini berkembang menjadi pusat ekonomi dan pariwisata di Sulawesi Tenggara.
                </p>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-emerald-500 p-8 rounded-3xl shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer group">
                    <div class="w-16 h-16 mb-4">
                        <img src="images/icon-location.png" alt="Lokasi" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                    </div>
                    <h3 class="text-2xl font-black mb-2">Lokasi Strategis</h3>
                    <p class="text-emerald-100 text-sm">Ibu kota Sulawesi Tenggara dengan akses mudah</p>
                </div>
                
                <div class="bg-sky-500 p-8 rounded-3xl shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer group">
                    <div class="w-16 h-16 mb-4">
                        <img src="images/icon-weather.png" alt="Cuaca" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                    </div>
                    <h3 class="text-2xl font-black mb-2">Iklim Tropis</h3>
                    <p class="text-sky-100 text-sm">Hangat sepanjang tahun, sempurna untuk liburan</p>
                </div>
                
                <div class="bg-amber-500 p-8 rounded-3xl shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer group">
                    <div class="w-16 h-16 mb-4">
                        <img src="images/icon-beach.png" alt="Pantai" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                    </div>
                    <h3 class="text-2xl font-black mb-2">Pantai Indah</h3>
                    <p class="text-amber-100 text-sm">Pasir putih dan air jernih yang memukau</p>
                </div>
                
                <div class="bg-rose-500 p-8 rounded-3xl shadow-2xl hover:scale-105 transition-all duration-300 cursor-pointer group">
                    <div class="w-16 h-16 mb-4">
                        <img src="images/icon-culture.png" alt="Budaya" class="w-full h-full object-contain group-hover:scale-105 transition-transform">
                    </div>
                    <h3 class="text-2xl font-black mb-2">Budaya Kaya</h3>
                    <p class="text-rose-100 text-sm">Warisan Kesultanan Buton yang legendaris</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-32 bg-emerald-600 relative overflow-hidden">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <h2 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">
            Siap Menjelajahi <span class="text-emerald-200">Kendari</span>?
        </h2>
        <p class="text-xl md:text-2xl text-emerald-50 leading-relaxed max-w-3xl mx-auto">
            Mulai petualangan Anda sekarang dan temukan keindahan tersembunyi di setiap sudut kota Kendari. Pengalaman tak terlupakan menanti Anda!
        </p>
    </div>
</section>

@endsection