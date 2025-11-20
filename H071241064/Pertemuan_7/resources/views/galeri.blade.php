@extends('layouts.master')

@section('title', 'Galeri Foto - Eksplor Pariwisata Kendari')

@section('content')
<section class="bg-violet-300 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mt-3 mb-4">
                Keindahan Kendari dalam Gambar
            </h1>
            <p class="text-lg leading-relaxed">
                Koleksi foto-foto memukau yang menampilkan pesona dan keindahan Kota Kendari dari berbagai sudut pandang.
            </p>
        </div>
    </div>
</section>

<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-3 gap-8">
            <x-card
                title="Jembatan Bahteramas"
                image="images/jembatan_bahteramas.jpeg"
                description="Ikon kota Kendari yang menghubungkan wilayah dan menawarkan pemandangan spektakuler Teluk Kendari."
            />

            <x-card
                title="Kebun Raya"
                image="images/kebunraya.jpg"
                description="Destinasi wisata alam yang menggabungkan konservasi, edukasi, dan rekreasi hijau dalam satu kawasan."
            />

            <x-card
                title="Anjungan Teluk Kendari"
                image="images/anjungan.jpg"
                description="Menyediakan berbagai wahana seru. Daya tarik utama adalah jembatan kaca, yang memungkinkan wisatawan untuk menikmati keindahan Teluk Kendari dari sudut pandang yang unik."
            />

            <x-card
                title="Masjid Agung Al-Kautsar Kendari"
                image="images/masjid_agung.webp"
                description="Bangunan megah dengan arsitektur modern yang menjadi landmark religius dan budaya kota."
            />

            <x-card
                title="Kali Biru"
                image="images/kalibiru.jpg"
                description="Tempat alami yang tenang dengan suasana asri, cocok untuk piknik keluarga dan fotografi alam."
            />

            <x-card
                title="Sunset Kendari"
                image="images/sunset.jpg"
                description="Momen golden hour yang menakjubkan di tepi pantai dengan gradasi warna langit yang dramatis."
            />
        </div>
    </div>
</section>

<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto text-center">
            <h2 class="text-3xl font-bold text-slate-900 mb-6">
                Abadikan Momen Anda di Kendari
            </h2>
            <p class="text-slate-600 leading-relaxed mb-8">
                Setiap sudut Kota Kendari menyimpan keindahan yang layak diabadikan. Dari landmark ikonik seperti Jembatan Bahteramas hingga keindahan alam Teluk Kendari, semuanya menawarkan latar foto yang sempurna untuk koleksi perjalanan Anda.
            </p>
        </div>
    </div>
</section>
@endsection