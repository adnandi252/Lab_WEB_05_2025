@extends('layouts.master')

@section('title', 'Home - Eksplor Sinjai')

@section('content')
<!-- Hero Section -->
<section class="relative h-screen flex items-center justify-center overflow-hidden">
    <div class="absolute inset-0 gradient-bg opacity-90"></div>
    <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('https://4.bp.blogspot.com/-22LGvBEguvU/V2aPuRmqX2I/AAAAAAAAAP0/GrbJuyyPuPw-B4zYQOlzkZCWZjebKmfrQCLcB/s1600/1424936332336973465.jpg'); opacity: 0.2;"></div>
    
    <div class="relative z-10 text-center text-white px-4 max-w-4xl mx-auto">
        <div class="animate-fadeInUp">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">
                Selamat Datang di <span class="block mt-2">Kabupaten Sinjai</span>
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-100">
                Permata Selatan Sulawesi yang Mempesona
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('destinasi') }}" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:scale-105 transition shadow-2xl">
                    <i class="fas fa-compass mr-2"></i>Jelajahi Destinasi
                </a>
                <a href="{{ route('kuliner') }}" class="px-8 py-4 glass-effect text-white rounded-full font-semibold hover:scale-105 transition border-2 border-white">
                    <i class="fas fa-utensils mr-2"></i>Cicipi Kuliner
                </a>
            </div>
        </div>
        
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <i class="fas fa-chevron-down text-3xl"></i>
        </div>
    </div>
</section>

<!-- Welcome Section -->
<section class="py-20 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Tentang Sinjai</h2>
            <div class="w-24 h-1 gradient-bg mx-auto mb-6"></div>
            <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                Temukan keindahan tersembunyi di ujung selatan Sulawesi
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <div class="animate-on-scroll">
                <img src="https://4.bp.blogspot.com/-22LGvBEguvU/V2aPuRmqX2I/AAAAAAAAAP0/GrbJuyyPuPw-B4zYQOlzkZCWZjebKmfrQCLcB/s1600/1424936332336973465.jpg" alt="Sinjai" class="rounded-2xl shadow-2xl hover-scale">
            </div>
            
            <div class="animate-on-scroll">
                <h3 class="text-3xl font-bold text-gray-800 mb-6">Kabupaten Sinjai</h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Kabupaten Sinjai adalah salah satu kabupaten di Provinsi Sulawesi Selatan yang terletak di pesisir selatan pulau Sulawesi. Dengan luas wilayah sekitar 819,96 km², Sinjai menawarkan pesona alam yang memukau, mulai dari pantai berpasir putih hingga pegunungan hijau yang menyejukkan.
                </p>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    Sinjai dikenal dengan julukan "Bumi Panrita Kitta". BUMI Panrita Kitta adalah sebutan untuk menunjukkan Kabupaten Sinjai yang artinya tanah atau tempat para ulama. Dan, ternyata penamaan tersebut berasal dari sebuah masjid tua yang terletak di jantung Kota Sinjai tepatnya di Jalan Muhammad Tahir, Kelurahan Balangnipa, Kecamatan Sinjai Utara.
                </p>
                
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <i class="fas fa-mountain text-3xl text-purple-600 mb-2"></i>
                        <p class="font-semibold text-gray-800">9 Kecamatan</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <i class="fas fa-users text-3xl text-purple-600 mb-2"></i>
                        <p class="font-semibold text-gray-800">250K+ Jiwa</p>
                    </div>
                    <div class="text-center p-4 bg-purple-50 rounded-xl">
                        <i class="fas fa-map text-3xl text-purple-600 mb-2"></i>
                        <p class="font-semibold text-gray-800">819 km²</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-20 bg-linear-to-br from-purple-50 to-blue-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-16 animate-on-scroll">
            <h2 class="text-4xl md:text-5xl font-bold gradient-text mb-4">Kenapa Sinjai?</h2>
            <div class="w-24 h-1 gradient-bg mx-auto"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale animate-on-scroll text-center">
                <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-umbrella-beach text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Pantai Eksotis</h3>
                <p class="text-gray-600">Nikmati keindahan pantai dengan pasir putih dan air laut yang jernih di berbagai lokasi menakjubkan</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale animate-on-scroll text-center" style="animation-delay: 0.2s;">
                <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-fish text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Kuliner Laut</h3>
                <p class="text-gray-600">Rasakan kelezatan hasil laut segar yang diolah dengan bumbu khas Bugis-Makassar yang menggugah selera</p>
            </div>

            <div class="bg-white p-8 rounded-2xl shadow-lg hover-scale animate-on-scroll text-center" style="animation-delay: 0.4s;">
                <div class="w-20 h-20 gradient-bg rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-landmark text-3xl text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Budaya Kaya</h3>
                <p class="text-gray-600">Jelajahi warisan budaya Bugis-Makassar yang masih terjaga dengan tradisi dan kearifan lokal</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 px-4">
    <div class="max-w-4xl mx-auto text-center">
        <div class="gradient-bg rounded-3xl p-12 text-white animate-on-scroll">
            <h2 class="text-4xl font-bold mb-6">Siap Menjelajah Sinjai?</h2>
            <p class="text-xl mb-8 text-gray-100">
                Temukan destinasi wisata, kuliner lezat, dan keindahan alam yang menanti untuk dijelajahi
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('destinasi') }}" class="px-8 py-4 bg-white text-purple-600 rounded-full font-semibold hover:scale-105 transition">
                    Lihat Destinasi
                </a>
                <a href="{{ route('kontak') }}" class="px-8 py-4 border-2 border-white text-white rounded-full font-semibold hover:bg-white hover:text-purple-600 transition">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </div>
</section>
@endsection