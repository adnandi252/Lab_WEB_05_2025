@extends('layouts.master')

@section('title', 'Kuliner Khas')

@section('content')
<div class = "section-container py-12 bg-gray-800">
    <h1 class="text-3xl font-bold text-white mt-16 mb-12 text-center">Kuliner Khas Toraja</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($kuliner as $item)
        <x-card :image="$item['gambar']" :title="$item['nama']">
            <p class="text-white">{{ $item['deskripsi'] }}</p>
        </x-card>
        @endforeach
        <br>
    </div>
</div>

@endsection