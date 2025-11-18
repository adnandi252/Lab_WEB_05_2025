@extends('layouts.app')

@section('content')
<div class="relative min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 z-0">
        <img src="{{ asset('images/gudang.jpg') }}" alt="Warehouse Background" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black bg-opacity-20"></div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        
        <div class="flex flex-col md:flex-row items-center justify-between gap-20 lg:gap-32">
            <div class="flex-1">
                <h1 
                    class="inline-block text-2xl md:text-3xl lg:text-4xl font-black text-white leading-tight 
                           bg-black/40 px-12 pt-2 pb-4 rounded-xl 
                           backdrop-blur-sm transition-all duration-300 
                           hover:bg-black/60 hover:scale-105 hover:shadow-lg">
                    Great Products Deserve Great Management!
                </h1>
                <p class='text-center text-white py-2'>
                    Simple tools. Strong results.
                </p>
            </div>

        </div>

    </div>
</div>
@endsection
