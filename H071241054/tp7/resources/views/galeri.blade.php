@extends('layouts.master')

@section('title', 'Galeri Foto')

@section('content')
<div class = "section-container py-12 bg-gray-800">
    <h1 class="text-3xl font-bold text-white mb-8 mt-16 text-center">Galeri</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
        @foreach($fotos as $foto)
        <div class="overflow-hidden rounded-lg shadow-md">
            <img src="{{ $foto }}" alt="Galeri Yogyakarta" class="w-full h-64 object-cover transition-transform duration-300 hover:scale-110">
        </div>
        @endforeach
    </div>
</div>

@endsection