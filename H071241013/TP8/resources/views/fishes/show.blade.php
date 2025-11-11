@extends('layouts.app')

@section('title', 'Detail Ikan - ' . $fish->name)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-5xl font-bold text-white mb-3">{{ $fish->name }}</h1>
        <span class="inline-block px-4 py-2 text-sm font-bold text-white rounded-full {{ $fish->rarity_color }}">
            {{ $fish->rarity }}
        </span>
    </div>

    <!-- Card Detail -->
    <div class="bg-cyan-900/30 backdrop-blur-sm rounded-xl p-8 border border-cyan-700/50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Info Ikan -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-cyan-300 text-sm font-semibold mb-2">🆔 ID Ikan</h3>
                    <p class="text-white text-lg">{{ $fish->id }}</p>
                </div>

                <div>
                    <h3 class="text-cyan-300 text-sm font-semibold mb-2">🐟 Nama Ikan</h3>
                    <p class="text-white text-2xl font-bold">{{ $fish->name }}</p>
                </div>

                <div>
                    <h3 class="text-cyan-300 text-sm font-semibold mb-2">⭐ Tingkat Kelangkaan</h3>
                    <span class="inline-block px-4 py-2 text-sm font-bold text-white rounded-full {{ $fish->rarity_color }}">
                        {{ $fish->rarity }}
                    </span>
                </div>

                <div>
                    <h3 class="text-cyan-300 text-sm font-semibold mb-2">⚖️ Rentang Berat</h3>
                    <p class="text-white text-lg">{{ $fish->formatted_weight_range }}</p>
                    <div class="mt-2 text-sm text-cyan-400/60">
                        Min: {{ $fish->base_weight_min }} kg | Max: {{ $fish->base_weight_max }} kg
                    </div>
                </div>
            </div>

            <!-- Info Ekonomi & Catch -->
            <div class="space-y-6">
                <div class="bg-yellow-600/20 border border-yellow-600/50 rounded-lg p-4">
                    <h3 class="text-yellow-300 text-sm font-semibold mb-2">💰 Harga Jual</h3>
                    <p class="text-yellow-400 text-3xl font-bold">{{ $fish->formatted_price }}</p>
                    <p class="text-yellow-300/60 text-sm mt-1">per kilogram</p>
                </div>

                <div class="bg-purple-600/20 border border-purple-600/50 rounded-lg p-4">
                    <h3 class="text-purple-300 text-sm font-semibold mb-2">🎣 Peluang Tertangkap</h3>
                    <p class="text-purple-400 text-3xl font-bold">{{ $fish->catch_probability }}%</p>
                    <div class="w-full bg-gray-700 rounded-full h-2 mt-3">
                        <div class="bg-purple-500 h-2 rounded-full transition-all duration-500" 
                            style="width: {{ min($fish->catch_probability, 100) }}%"></div>
                    </div>
                </div>

                <div class="bg-cyan-600/20 border border-cyan-600/50 rounded-lg p-4">
                    <h3 class="text-cyan-300 text-sm font-semibold mb-2">📅 Informasi Waktu</h3>
                    <div class="space-y-2 text-sm text-cyan-200">
                        <p><span class="text-cyan-400">Dibuat:</span> {{ $fish->created_at->format('d/m/Y H:i') }}</p>
                        <p><span class="text-cyan-400">Terakhir Diupdate:</span> {{ $fish->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi -->
        @if($fish->description)
            <div class="mt-8 pt-8 border-t border-cyan-700/50">
                <h3 class="text-cyan-300 text-sm font-semibold mb-3">📝 Deskripsi</h3>
                <p class="text-white text-lg leading-relaxed">{{ $fish->description }}</p>
            </div>
        @endif

        <!-- Tombol Aksi -->
        <div class="mt-8 pt-8 border-t border-cyan-700/50">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('fishes.index') }}" 
                    class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition duration-300">
                    ← Kembali ke Daftar
                </a>
                <a href="{{ route('fishes.edit', $fish) }}" 
                    class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition duration-300">
                    ✏️ Edit Ikan
                </a>
                <form action="{{ route('fishes.destroy', $fish) }}" method="POST" class="inline"
                    onsubmit="return confirm('Yakin ingin menghapus ikan {{ $fish->name }}?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-300">
                        🗑️ Hapus Ikan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Stats Card -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-cyan-800/30 backdrop-blur-sm rounded-lg p-4 border border-cyan-700/50 text-center">
            <div class="text-cyan-400 text-sm font-semibold mb-1">💎 Potensial Maksimum</div>
            <div class="text-white text-2xl font-bold">
                {{ number_format($fish->base_weight_max * $fish->sell_price_per_kg, 0, ',', '.') }} 💰
            </div>
            <div class="text-cyan-300/60 text-xs mt-1">Coins per tangkapan</div>
        </div>

        <div class="bg-cyan-800/30 backdrop-blur-sm rounded-lg p-4 border border-cyan-700/50 text-center">
            <div class="text-cyan-400 text-sm font-semibold mb-1">📊 Rata-rata Berat</div>
            <div class="text-white text-2xl font-bold">
                {{ number_format(($fish->base_weight_min + $fish->base_weight_max) / 2, 2) }} kg
            </div>
            <div class="text-cyan-300/60 text-xs mt-1">Weight average</div>
        </div>

        <div class="bg-cyan-800/30 backdrop-blur-sm rounded-lg p-4 border border-cyan-700/50 text-center">
            <div class="text-cyan-400 text-sm font-semibold mb-1">💵 Estimasi Value</div>
            <div class="text-white text-2xl font-bold">
                {{ number_format((($fish->base_weight_min + $fish->base_weight_max) / 2) * $fish->sell_price_per_kg, 0, ',', '.') }} 💰
            </div>
            <div class="text-cyan-300/60 text-xs mt-1">Coins rata-rata</div>
        </div>
    </div>
</div>
@endsection