@extends('layouts.master')

@section('title', 'Destinasi Wisata - Eksplor Pariwisata Kendari')

@section('content')
<section class="relative bg-slate-900 text-white overflow-hidden py-32">
    <div class="absolute inset-0">
        <img src="images/viewkendari.webp" alt="Destinasi Kendari" class="w-full h-full object-cover opacity-40">
        <div class="absolute inset-0 bg-black opacity-50"></div>
    </div>
     
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-10">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight">
                Jelajahi Keindahan <span class="text-sky-400">Kendari</span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-200 leading-relaxed">
                Temukan destinasi wisata terbaik di Kendari, dari pantai yang memesona hingga air terjun yang menawan.
            </p>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-16">
            <div class="grid md:grid-cols-2 gap-8 bg-white rounded-3xl overflow-hidden shadow-2xl transition-all duration-500 group">
                <div class="relative overflow-hidden">
                    <img src="images/toronipa.jpg" alt="Toronipa" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                </div>
                <div class="p-8 md:p-12 flex flex-col justify-center">
                    <h3 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Pantai Toronipa</h3>
                    <p class="text-lg text-slate-600 leading-relaxed mb-6">
                        Memiliki hamparan pasir putih yang luas dan air laut yang jernih serta dangkal. Pepohonan kelapa yang rindang di sepanjang bibir pantai menambah suasana teduh dan menawan.
                    </p>
                    <div class="flex items-center gap-6 text-sm text-slate-600">
                        <div class="flex items-center gap-2">
                            <img src="images/icon-location.png" alt="Location" class="w-5 h-5">
                            <span>18-25 km dari pusat kota</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <img src="images/icon-time.png" alt="Time" class="w-5 h-5">
                            <span>30-45 menit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <x-card 
                title="Pantai Batu Gong"
                image="/images/batugong.jpg"
                description="Pantai ini menawarkan keindahan dengan panorama matahari terbit di pagi hari. Wisata alam ini dikenal dengan hamparan pasir hitam yang membentang cukup panjang sejauh mata memandang."
            />

            <x-card 
                title="Pulau Bokori"
                image="/images/pulaubokori.jpeg"
                description="Pulau ini terkenal dengan pantai pasir putih, air biru toska, dan pohon-pohon pinus. Menawarkan berbagai aktivitas seperti snorkeling, memancing, berenang, dan bersantai"
            />

            <x-card 
                title="Pulau Labengki"
                image="/images/pulaulabengki.jpg"
                description="menyimpan kekayaan bawah laut yang masih alami, pantai-pantai tersembunyi, hingga budaya unik masyarakat suku Bajo. Warna airnya biru toska jernih dan dikelilingi formasi karang nan memikat."
            />

            <x-card 
                title="Air Terjun Moramo"
                image="/images/airterjunmoramo.jpg"
                description="Air Terjun Moramo terkenal karena bentuknya yang bertingkat-tingkat. Moramo memiliki puluhan tingkatan yang menciptakan kolam-kolam alami di setiap lapisannya. Airnya mengalir jernih dan  memiliki udara yang sejuk"
            />

            <x-card 
                title="Masjid Al-Alam"
                image="/images/masjid_al-alam.jpeg"
                description="Masjid Al-Alam adalah masjid terapung. Terletak di tengah Teluk Kendari, masjid ini dibangun di atas perairan dan memiliki empat menara yang terinspirasi dari Burj Al-Arab di Dubai. Keunikannya terletak pada lokasi, arsitektur, serta kubahnya yang bisa dibuka-tutup."
            />

            <x-card 
                title="Kendari beach"
                image="/images/kendaribeach.jpg"
                description="Kendari Beach memiliki pemandangan yang indah terutama pada sore hari. Pengunjung dapat menikmati laut yang tenang dengan cahaya jingga yang tampak seperti lukisan."
            />
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-4">
                Tips Berwisata di <span class="text-emerald-600">Kendari</span>
            </h2>
            <p class="text-lg text-slate-600 max-w-2xl mx-auto">
                Persiapkan diri Anda dengan tips berguna untuk pengalaman wisata yang maksimal
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-6 max-w-5xl mx-auto">
            <div class="bg-emerald-50 p-8 rounded-3xl border-2 border-emerald-100 hover:border-emerald-300 hover:shadow-xl transition-all duration-300 group cursor-pointer">
                <div class="flex items-start gap-6">
                    <div>
                        <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <img src="images/icon-calendar.png" alt="Waktu" class="w-8 h-8">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Waktu Terbaik Berkunjung</h3>
                        <p class="text-slate-700 leading-relaxed">
                            Bulan April hingga Oktober adalah waktu terbaik dengan cuaca cerah dan minim hujan. Hindari musim penghujan untuk pengalaman optimal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-sky-50 p-8 rounded-3xl border-2 border-sky-100 hover:border-sky-300 hover:shadow-xl transition-all duration-300 group cursor-pointer">
                <div class="flex items-start gap-6">
                    <div>
                        <div class="w-16 h-16 bg-sky-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <img src="images/icon-backpack.png" alt="Perlengkapan" class="w-8 h-8">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Persiapan Perlengkapan</h3>
                        <p class="text-slate-700 leading-relaxed">
                            Jangan lupa bawa sunblock, topi, kacamata hitam, dan kamera untuk mengabadikan momen. Siapkan juga pakaian ganti dan handuk.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-amber-50 p-8 rounded-3xl border-2 border-amber-100 hover:border-amber-300 hover:shadow-xl transition-all duration-300 group cursor-pointer">
                <div class="flex items-start gap-6">
                    <div>
                        <div class="w-16 h-16 bg-amber-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <img src="images/icon-transport.png" alt="Transportasi" class="w-8 h-8">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Transportasi Lokal</h3>
                        <p class="text-slate-700 leading-relaxed">
                            Gunakan ojek online atau sewa motor untuk mobilitas yang lebih fleksibel. Beberapa destinasi memerlukan perjalanan dengan perahu.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-rose-50 p-8 rounded-3xl border-2 border-rose-100 hover:border-rose-300 hover:shadow-xl transition-all duration-300 group cursor-pointer">
                <div class="flex items-start gap-6">
                    <div>
                        <div class="w-16 h-16 bg-rose-500 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <img src="images/icon-leaf.png" alt="Kebersihan" class="w-8 h-8">
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900 mb-3">Jaga Kebersihan</h3>
                        <p class="text-slate-700 leading-relaxed">
                            Selalu bawa tas untuk sampah dan jaga kelestarian alam. Mari jaga keindahan Kendari untuk generasi mendatang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection