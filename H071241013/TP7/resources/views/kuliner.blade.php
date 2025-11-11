@extends('layouts.master')

@section('title', 'Kuliner Khas - Eksplor Sinjai')

@section('content')
<!-- Hero Section -->
<section class="relative h-96 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-bg opacity-90"></div>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://4.bp.blogspot.com/-22LGvBEguvU/V2aPuRmqX2I/AAAAAAAAAP0/GrbJuyyPuPw-B4zYQOlzkZCWZjebKmfrQCLcB/s1600/1424936332336973465.jpg'); opacity: 0.2;"></div>
    
    <div class="relative z-10 text-center text-white px-4 animate-fadeInUp">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Kuliner Khas Sinjai</h1>
        <p class="text-xl text-gray-100">Cita rasa autentik dari Bumi Panrita Kitta</p>
    </div>
</section>

<!-- Intro Section -->
<section class="py-16 px-4 bg-white">
    <div class="max-w-4xl mx-auto text-center animate-on-scroll">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Kelezatan Warisan Leluhur</h2>
        <p class="text-gray-600 text-lg leading-relaxed">
            Sinjai menawarkan kekayaan kuliner yang menggabungkan cita rasa laut segar dengan bumbu khas Bugis-Makassar. Setiap hidangan memiliki cerita dan tradisi yang diwariskan turun-temurun, menjadikan pengalaman kuliner Anda tak terlupakan.
        </p>
    </div>
</section>

<!-- Kuliner Grid -->
<section class="py-20 px-4 bg-gray-50">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Lawa -->
            <x-card 
                image="https://image.idntimes.com/post/20200908/untitled-design-6-cc423b0c10fdf41758b473c1bd4f5a3c.png?tr=w-1200,f-webp,q-75&width=1200&format=webp&quality=75"
                title="Lawa (laha' Bete)"
                badge="Traditional" 
                description="Lawa adalah hidangan khas Sinjai dari campuran kelapa parut dan ikan mairo. Rasanya gurih dan segar dengan tekstur lembut, cocok disantap bersama nasi hangat dalam acara tradisional.">
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500"><i class="fas fa-fire mr-1 text-orange-500"></i>Level Pedas: Rendah</span>
                    <span class="text-sm font-semibold text-purple-600">Rp 35.000</span>
                </div>
            </x-card>

            <!-- Bikang Doang -->
            <x-card 
                image="https://image.idntimes.com/post/20211120/91992455-607727849840725-1764264179948557172-n-fd58fbc81630e69ebaba2998807a3d6d.jpg?tr=w-1200,f-webp,q-75&width=1200&format=webp&quality=75"
                title="Bikang Doang"
                badge="Traditional"
                description="Bikang Doang adalah bakwan udang khas Sinjai yang gurih dan renyah. Dijual murah di pasar, camilan ini cocok dinikmati dengan kopi atau teh saat bersantai.">
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500"><i class="fas fa-leaf mr-1 text-green-500"></i>Fried Snack</span>
                    <span class="text-sm font-semibold text-purple-600">Rp 1.000-2.000</span>
                </div>
            </x-card>

            <!-- Jalangkote -->
            <x-card 
                image="https://image.idntimes.com/post/20211120/94610987-3165363433486254-3608740028789425843-n-2bf91c3e8bd129078142b45837b7cb58.jpg?tr=w-1200,f-webp,q-75&width=1200&format=webp&quality=75"
                title="Jalangkote"
                badge="Must Try"
                description="Jalangkote mirip pastel dengan kulit tipis dan isi bihun, kentang, serta sayuran. Disajikan dengan sambal cair pedas-asam yang membuat rasanya semakin nikmat.">
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500"><i class="fas fa-birthday-cake mr-1 text-pink-500"></i>Dessert</span>
                    <span class="text-sm font-semibold text-purple-600">Rp 1.000-2.000</span>
                </div>
            </x-card>

            <!-- Gogos (Gogoso) -->
            <x-card 
                image="https://image.idntimes.com/post/20211120/38080812-301870100561117-4107660808694005760-n-0e0d402e0657b02859a5005d0c81ea40.jpg?tr=w-1200,f-webp,q-75&width=1200&format=webp&quality=75"
                title="Gogos (Gogoso)"
                badge="Traditional"
                description="Gogos adalah ketan bakar khas Sinjai yang dibungkus daun pisang. Ada versi original dan isi ayam atau tuna pedas, beraroma harum dan gurih saat dibakar.">
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500"><i class="fas fa-fire mr-1 text-red-500"></i>Level Pedas: Varies</span>
                    <span class="text-sm font-semibold text-purple-600">Rp 3.500-5.000</span>
                </div>
            </x-card>

            <!-- Minas -->
            <x-card 
                image="https://image.idntimes.com/post/20211120/6044e0ee7ba03644929068-0a0cfefb8e76a86c41027a6edea3c055.jpg?tr=w-1200,f-webp,q-75&width=1200&format=webp&quality=75"
                title="Minas"
                badge="Traditional"
                description="Minas adalah minuman khas Sinjai dari campuran madu, tape, susu, air kelapa, telur, dan buah-buahan. Rasanya manis-segar dan paling nikmat diminum dalam keadaan dingin.">
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-sm text-gray-500"><i class="fas fa-coffee mr-1 text-brown-500"></i>Beverage</span>
                    <span class="text-sm font-semibold text-purple-600">Rp 20.000</span>
                </div>
            </x-card>
        </div>
    </div>
</section>

<!-- Rekomendasi Tempat Makan -->
<section class="py-20 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-4xl font-bold gradient-text mb-4">Rekomendasi Tempat Makan</h2>
            <div class="w-24 h-1 gradient-bg mx-auto mb-6"></div>
            <p class="text-gray-600 text-lg">Tempat terbaik untuk menikmati kuliner khas Sinjai</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-linear-to-br from-purple-50 to-blue-50 p-8 rounded-2xl shadow-lg hover-scale animate-on-scroll">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-store text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Rumah Makan Sinjai Jaya</h3>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Spesialis Ikan Parende dan Pallubasa dengan cita rasa autentik</p>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Jl. Jend. Sudirman No. 45
                </div>
            </div>

            <div class="bg-linear-to-br from-purple-50 to-blue-50 p-8 rounded-2xl shadow-lg hover-scale animate-on-scroll" style="animation-delay: 0.1s;">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-utensils text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Warung Kapurung Ibu Hj. Sitti</h3>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Kapurung legendaris dengan resep turun-temurun sejak 1975</p>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Pasar Sentral Sinjai
                </div>
            </div>

            <div class="bg-linear-to-br from-purple-50 to-blue-50 p-8 rounded-2xl shadow-lg hover-scale animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="flex items-center mb-4">
                    <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-birthday-cake text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Toko Kue Peca Bahari</h3>
                        <div class="flex text-yellow-400 text-sm">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="far fa-star"></i>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 mb-4">Bolu Peca terenak dengan berbagai varian rasa modern</p>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Jl. Pahlawan No. 12
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Food Festival Info -->
<section class="py-20 px-4 gradient-bg">
    <div class="max-w-4xl mx-auto text-center text-white animate-on-scroll">
        <i class="fas fa-calendar-alt text-6xl mb-6 animate-float"></i>
        <h2 class="text-4xl font-bold mb-6">Festival Kuliner Sinjai</h2>
        <p class="text-xl text-gray-100 mb-8">
            Setiap tahun di bulan Agustus, Sinjai menggelar Festival Kuliner Nusantara yang menampilkan berbagai makanan khas daerah. Jangan lewatkan kesempatan untuk mencicipi berbagai hidangan autentik dan menyaksikan demo masak dari chef lokal!
        </p>
        <a href="{{ route('kontak') }}" class="inline-block px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:scale-105 transition">
            <i class="fas fa-info-circle mr-2"></i>Info Lebih Lanjut
        </a>
    </div>
</section>
@endsection