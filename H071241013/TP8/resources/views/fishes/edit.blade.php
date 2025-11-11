@extends('layouts.app')

@section('title', 'Edit Ikan')

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-white mb-3">✏️ Edit Ikan</h1>
        <p class="text-cyan-300/80">Perbarui data ikan {{ $fish->name }}</p>
    </div>

    <!-- Form -->
    <div class="bg-cyan-900/30 backdrop-blur-sm rounded-xl p-8 border border-cyan-700/50">
        <form action="{{ route('fishes.update', $fish) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Nama Ikan -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">
                    Nama Ikan <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $fish->name) }}" required
                    class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500 @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Golden Tuna">
                @error('name')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rarity -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">
                    Rarity <span class="text-red-400">*</span>
                </label>
                <select name="rarity" required
                    class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white focus:outline-none focus:border-cyan-500 @error('rarity') border-red-500 @enderror">
                    <option value="">Pilih Rarity</option>
                    @foreach($rarities as $rarity)
                        <option value="{{ $rarity }}" {{ old('rarity', $fish->rarity) == $rarity ? 'selected' : '' }}>
                            {{ $rarity }}
                        </option>
                    @endforeach
                </select>
                @error('rarity')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Berat Min & Max -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-cyan-300 text-sm font-semibold mb-2">
                        Berat Minimum (kg) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="base_weight_min" value="{{ old('base_weight_min', $fish->base_weight_min) }}" 
                        step="0.01" min="0" required
                        class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500 @error('base_weight_min') border-red-500 @enderror"
                        placeholder="0.50">
                    @error('base_weight_min')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-cyan-300 text-sm font-semibold mb-2">
                        Berat Maksimum (kg) <span class="text-red-400">*</span>
                    </label>
                    <input type="number" name="base_weight_max" value="{{ old('base_weight_max', $fish->base_weight_max) }}" 
                        step="0.01" min="0" required
                        class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500 @error('base_weight_max') border-red-500 @enderror"
                        placeholder="2.50">
                    @error('base_weight_max')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Harga Jual -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">
                    Harga Jual per kg (Coins) <span class="text-red-400">*</span>
                </label>
                <input type="number" name="sell_price_per_kg" value="{{ old('sell_price_per_kg', $fish->sell_price_per_kg) }}" 
                    min="0" required
                    class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500 @error('sell_price_per_kg') border-red-500 @enderror"
                    placeholder="1000">
                @error('sell_price_per_kg')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Probabilitas -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">
                    Probabilitas Tertangkap (%) <span class="text-red-400">*</span>
                </label>
                <input type="number" name="catch_probability" value="{{ old('catch_probability', $fish->catch_probability) }}" 
                    step="0.01" min="0.01" max="100" required
                    class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500 @error('catch_probability') border-red-500 @enderror"
                    placeholder="5.50">
                <p class="mt-1 text-xs text-cyan-400/60">Masukkan nilai antara 0.01% - 100%</p>
                @error('catch_probability')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">
                    Deskripsi <span class="text-cyan-400/60">(Opsional)</span>
                </label>
                <textarea name="description" rows="4"
                    class="w-full px-4 py-3 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500 @error('description') border-red-500 @enderror"
                    placeholder="Deskripsi ikan...">{{ old('description', $fish->description) }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-4 pt-4">
                <a href="{{ route('fishes.index') }}" 
                    class="flex-1 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white text-center rounded-lg transition duration-300">
                    ← Batal
                </a>
                <button type="submit" 
                    class="flex-1 px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition duration-300">
                    💾 Update Ikan
                </button>
            </div>
        </form>

        <!-- Tombol Hapus -->
        <div class="mt-6 pt-6 border-t border-cyan-700/50">
            <form action="{{ route('fishes.destroy', $fish) }}" method="POST" 
                onsubmit="return confirm('Yakin ingin menghapus ikan {{ $fish->name }}? Tindakan ini tidak dapat dibatalkan!')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                    class="w-full px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300">
                    🗑️ Hapus Ikan Ini
                </button>
            </form>
        </div>
    </div>
</div>
@endsection