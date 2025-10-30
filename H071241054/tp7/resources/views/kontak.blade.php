@extends('layouts.master')

@section('title', 'Kontak Kami')

@section('content')
<div class = "section-container py-12 bg-gray-800">
    <div class="max-w-4xl mx-auto mt-12">
        <h1 class="text-3xl font-bold text-white mt-16 mb-8 text-center">Hubungi Kami</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informasi Kontak -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Informasi Kontak</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-blue-600 mr-3"></i>
                        <div>
                            <p class="font-semibold">Alamat</p>
                            <p class="text-gray-600">Jl. Pongtiku No.1, Tana Toraja</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-phone text-green-600 mr-3"></i>
                        <div>
                            <p class="font-semibold">Telepon</p>
                            <p class="text-gray-600">(1234) 56789</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-envelope text-red-600 mr-3"></i>
                        <div>
                            <p class="font-semibold">Email</p>
                            <p class="text-gray-600">info@wonderfulToraja.com</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Kontak -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-semibold mb-4">Kirim Pesan</h2>
                <form>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="nama">Nama</label>
                        <input type="text" id="nama" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="email">Email</label>
                        <input type="email" id="email" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2" for="pesan">Pesan</label>
                        <textarea id="pesan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full bg-green-900 text-white py-2 px-4 rounded hover:bg-green-500 transition duration-300">
                        Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection