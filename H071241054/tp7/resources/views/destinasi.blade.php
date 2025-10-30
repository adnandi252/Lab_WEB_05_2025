@extends('layouts.master')

@section('title', 'Destinasi Wisata')

@section('content')
<div class="section-container py-12 bg-gray-800">
    <h1 class="text-4xl font-bold text-white mb-12 mt-16 text-center">Destinasi Wisata di Tana Toraja</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($destinasi as $item)
        <x-card :image="$item['gambar']" :title="$item['nama']">
            <p class="text-white">{{ $item['deskripsi'] }}</p>
        </x-card>
        @endforeach
    </div>
</div>
@endsection