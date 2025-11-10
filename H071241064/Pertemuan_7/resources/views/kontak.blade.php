@extends('layouts.master')

@section('title', 'Kontak - Eksplor Pariwisata Kendari')

@section('content')
<section class="bg-slate-900 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            <span class="text-slate-400 font-semibold text-sm uppercase tracking-wider">Hubungi Kami</span>
            <h1 class="text-4xl md:text-5xl font-bold mt-3 mb-4">
                Mari Berbicara Tentang Kendari
            </h1>
            <p class="text-lg text-slate-300 leading-relaxed">
                Punya pertanyaan atau butuh informasi lebih lanjut? Kami siap membantu Anda merencanakan perjalanan sempurna ke Kendari.
            </p>
        </div>
    </div>
</section>

<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-2xl font-bold text-slate-900 mb-6">Kirim Pesan</h2>
                <form class="space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900 mb-2">Nama Lengkap</label>
                        <input 
                            type="text" 
                            id="name" 
                            name="name"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition"
                            placeholder="Masukkan nama lengkap Anda"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-900 mb-2">Email</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition"
                            placeholder="nama@email.com"
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-900 mb-2">Nomor Telepon</label>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition"
                            placeholder="08xxxxxxxxxx"
                        >
                    </div>

                    <div>
                        <label for="subject" class="block text-sm font-semibold text-slate-900 mb-2">Subjek</label>
                        <select 
                            id="subject" 
                            name="subject"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition"
                        >
                            <option value="">Pilih subjek</option>
                            <option value="informasi">Informasi Wisata</option>
                            <option value="pemesanan">Pemesanan Tour</option>
                            <option value="kerjasama">Kerjasama</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label for="message" class="block text-sm font-semibold text-slate-900 mb-2">Pesan</label>
                        <textarea 
                            id="message" 
                            name="message" 
                            rows="5"
                            class="w-full px-4 py-3 border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition resize-none"
                            placeholder="Tuliskan pesan Anda di sini..."
                        ></textarea>
                    </div>

                    <button 
                        type="submit"
                        class="w-full px-8 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition shadow-lg"
                    >
                        Kirim Pesan
                    </button>
                </form>
            </div>

            <div class="space-y-8">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-6">Informasi Kontak</h2>
                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-900 mb-1">Alamat</h3>
                                <p class="text-slate-600 text-sm">
                                    Dinas Pariwisata Kota Kendari<br>
                                    Jl. Balaikota No. 1, Kendari<br>
                                    Sulawesi Tenggara, Indonesia 93111
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-900 mb-1">Email</h3>
                                <p class="text-slate-600 text-sm">info@kendaritourism.com</p>
                                <p class="text-slate-600 text-sm">pariwisata@kendarikota.go.id</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-900 mb-1">Telepon</h3>
                                <p class="text-slate-600 text-sm">(0401) 123456</p>
                                <p class="text-slate-600 text-sm">+62 812-3456-7890</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div>
                                <h3 class="font-semibold text-slate-900 mb-1">Jam Operasional</h3>
                                <p class="text-slate-600 text-sm">Senin - Jumat: 08:00 - 17:00</p>
                                <p class="text-slate-600 text-sm">Sabtu: 08:00 - 12:00</p>
                                <p class="text-slate-600 text-sm">Minggu & Libur: Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection