@extends('layouts.master')

@section('title', 'Destinasi Wisata - Eksplor Sinjai')

@section('content')
<!-- Hero Section -->
<section class="relative h-96 flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-bg opacity-90"></div>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://4.bp.blogspot.com/-22LGvBEguvU/V2aPuRmqX2I/AAAAAAAAAP0/GrbJuyyPuPw-B4zYQOlzkZCWZjebKmfrQCLcB/s1600/1424936332336973465.jpg'); opacity: 0.2;"></div>
    
    <div class="relative z-10 text-center text-white px-4 animate-fadeInUp">
        <h1 class="text-5xl md:text-6xl font-bold mb-4">Destinasi Wisata Sinjai</h1>
        <p class="text-xl text-gray-100">Jelajahi keindahan alam dan budaya Sinjai</p>
    </div>
</section>

<!-- Destinations Grid -->
<section class="py-20 px-4">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl font-bold gradient-text mb-4">Tempat Wisata Unggulan</h2>
            <div class="w-24 h-1 gradient-bg mx-auto mb-6"></div>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                Temukan pesona wisata alam dan budaya yang menakjubkan di Kabupaten Sinjai
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Pantai Baloiya -->
            <x-card 
                image="https://i.pinimg.com/736x/a5/fb/1f/a5fb1f19055a7b91e40ee05c086879d7.jpg"
                title="Pantai Baloiya"
                badge="Favorit"
                description="Pantai Baloiya merupakan destinasi wisata pantai yang terkenal di Sinjai dengan pasir putih yang bersih dan air laut yang jernih kebiruan. Pantai ini menawarkan pemandangan sunset yang memukau dan merupakan tempat favorit untuk berkumpul bersama keluarga. Fasilitas lengkap seperti gazebo, area parkir luas, dan warung makan tersedia di lokasi ini.">
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                    <span>Kecamatan Sinjai Utara</span>
                </div>
            </x-card>

            <!-- Pulau Sembilan -->
            <x-card 
                image="https://i.pinimg.com/1200x/f4/5c/19/f45c19777a6714207a4777ea876927e0.jpg"
                title="Pulau Sembilan"
                badge="Eksotis"
                description="Pulau Sembilan adalah gugusan sembilan pulau kecil di perairan Sinjai yang menawarkan keindahan bawah laut yang luar biasa. Destinasi ini sangat cocok untuk snorkeling dan diving dengan terumbu karang yang masih alami. Air laut yang jernih dengan visibilitas tinggi membuat pengalaman eksplorasi bawah laut menjadi tak terlupakan.">
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-ship mr-2 text-purple-600"></i>
                    <span>Perjalanan 30 menit dari pantai</span>
                </div>
            </x-card>

            <!-- Air Terjun Puncak Ilalang -->
            <x-card 
                image="https://images.unsplash.com/photo-1432405972618-c60b0225b8f9?w=800"
                title="Air Terjun Puncak Ilalang"
                badge="Hidden Gem"
                description="Air terjun yang tersembunyi di tengah hutan hijau dengan ketinggian sekitar 25 meter. Suasana sejuk dan asri menjadikan tempat ini ideal untuk melepas penat dari rutinitas. Air terjun ini memiliki kolam alami di bawahnya yang aman untuk berenang, dikelilingi oleh bebatuan besar dan pepohonan rindang.">
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                    <span>Kecamatan Sinjai Barat</span>
                </div>
            </x-card>

            <!-- Pantai Mallenreng -->
            <x-card 
                image="https://asset-2.tstatic.net/makassar/foto/bank/images/Pantai-Mallenreng-di-Sinjai-yang-dulu-menjadi-primadona-wisata-kini-sepi-pengunjung.jpg"
                title="Pantai Mallenreng"
                badge="Instagramable"
                description="Pantai dengan karakteristik unik berupa batu karang raksasa yang tersebar di sepanjang bibir pantai. Ombak yang menghantam karang menciptakan pemandangan dramatis yang sempurna untuk fotografi. Sunset di pantai ini memberikan nuansa magis dengan perpaduan warna langit yang memukau, sangat disukai para fotografer.">
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-camera mr-2 text-purple-600"></i>
                    <span>Spot Foto Terbaik</span>
                </div>
            </x-card>

            <!-- Gunung Loka Bulan -->
            <x-card 
                image="https://th.bing.com/th/id/OIP.op6tuexcP_3TklKORajP7AHaE8?w=236&h=180&c=7&r=0&o=7&dpr=1.3&pid=1.7&rm=3"
                title="Gunung Bawakaraeng"
                badge="Adventure"
                description="Destinasi pendakian favorit dengan pemandangan spektakuler dari puncak. Trekking pulang-pergi memakan waktu sekitar 2-3 Hari melewati hutan tropis yang masih asri. Dari puncak, pengunjung dapat menyaksikan panorama Kabupaten Sinjai dari ketinggian, termasuk garis pantai yang memanjang dan hamparan sawah yang hijau.">
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-hiking mr-2 text-purple-600"></i>
                    <span>Level: Ekstrem</span>
                </div>
            </x-card>

            <!-- Taman Purbakala Batu Pake Gojeng -->
            <x-card 
                image="https://ainhyedelweiss.com/wp-content/uploads/2021/07/2018-07-20_compress0.jpg"
                title="Batu Pake Gojeng"
                badge="Heritage"
                description="Situs megalitikum bersejarah dengan batu besar bergambar kuno yang menjadi bukti peradaban masa lampau. Lokasi ini memiliki nilai arkeologis tinggi dengan berbagai artefak dan relief yang masih terpelihara. Tempat ini juga menawarkan suasana mistis dan spiritual yang kental, cocok untuk wisata edukasi dan sejarah.">
                <div class="flex items-center mt-4 text-sm text-gray-500">
                    <i class="fas fa-landmark mr-2 text-purple-600"></i>
                    <span>Situs Bersejarah</span>
                </div>
            </x-card>
        </div>
    </div>
</section>

<!-- Tips Section -->
<section class="py-20 bg-linear-to-br from-purple-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 animate-on-scroll">
            <h2 class="text-4xl font-bold gradient-text mb-4">Tips Berwisata</h2>
            <div class="w-24 h-1 gradient-bg mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-lg hover-scale animate-on-scroll">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-clock text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Waktu Terbaik</h3>
                <p class="text-gray-600 text-sm">Kunjungi saat musim kemarau (April-Oktober) untuk cuaca optimal</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg hover-scale animate-on-scroll" style="animation-delay: 0.1s;">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-tshirt text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Pakaian</h3>
                <p class="text-gray-600 text-sm">Bawa pakaian nyaman dan ringan, jangan lupa sunscreen</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg hover-scale animate-on-scroll" style="animation-delay: 0.2s;">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-camera text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Dokumentasi</h3>
                <p class="text-gray-600 text-sm">Jangan lupa kamera untuk abadikan momen indah Anda</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg hover-scale animate-on-scroll" style="animation-delay: 0.3s;">
                <div class="w-16 h-16 gradient-bg rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-leaf text-2xl text-white"></i>
                </div>
                <h3 class="font-bold text-lg mb-2">Jaga Lingkungan</h3>
                <p class="text-gray-600 text-sm">Selalu jaga kebersihan dan kelestarian alam sekitar</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <div class="gradient-bg rounded-3xl p-12 text-white animate-on-scroll">
            <h2 class="text-3xl font-bold mb-4">Tertarik Berkunjung?</h2>
            <p class="text-lg mb-8 text-gray-100">
                Hubungi kami untuk informasi lebih lanjut tentang paket wisata dan akomodasi
            </p>
            <a href="{{ route('kontak') }}" class="inline-block px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:scale-105 transition">
                <i class="fas fa-phone mr-2"></i>Hubungi Kami
            </a>
        </div>
    </div>
</section>
@endsection