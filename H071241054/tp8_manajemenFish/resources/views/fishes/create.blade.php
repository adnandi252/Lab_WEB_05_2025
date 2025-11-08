@extends('layouts.app')

@section('title', 'Tambah Ikan Baru')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Ikan Baru</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('fishes.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Ikan <span class="text-red-500">*</span></label>
                    <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="rarity" class="block text-gray-700 text-sm font-bold mb-2">Kelangkaan <span class="text-red-500">*</span></label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('rarity') border-red-500 @enderror" id="rarity" name="rarity" required>
                        <option value="">Pilih Kelangkaan</option>
                        @foreach($rarities as $rarity)
                            <option value="{{ $rarity }}" {{ old('rarity') == $rarity ? 'selected' : '' }}>{{ $rarity }}</option>
                        @endforeach
                    </select>
                    @error('rarity')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="base_weight_min" class="block text-gray-700 text-sm font-bold mb-2">Berat Minimum (kg) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('base_weight_min') border-red-500 @enderror" id="base_weight_min" name="base_weight_min" value="{{ old('base_weight_min') }}" required>
                        @error('base_weight_min')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="base_weight_max" class="block text-gray-700 text-sm font-bold mb-2">Berat Maksimum (kg) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('base_weight_max') border-red-500 @enderror" id="base_weight_max" name="base_weight_max" value="{{ old('base_weight_max') }}" required>
                        @error('base_weight_max')
                            <p class="text-red-500 text-xs italic">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="sell_price_per_kg" class="block text-gray-700 text-sm font-bold mb-2">Harga Jual per kg (Coins) <span class="text-red-500">*</span></label>
                    <input type="number" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('sell_price_per_kg') border-red-500 @enderror" id="sell_price_per_kg" name="sell_price_per_kg" value="{{ old('sell_price_per_kg') }}" required>
                    @error('sell_price_per_kg')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="catch_probability" class="block text-gray-700 text-sm font-bold mb-2">Peluang Tangkap (%) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('catch_probability') border-red-500 @enderror" id="catch_probability" name="catch_probability" value="{{ old('catch_probability') }}" required>
                    @error('catch_probability')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('description') border-red-500 @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <a href="{{ route('fishes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Kembali
                    </a>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection