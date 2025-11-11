@extends('layouts.app')

@section('title', 'Halaman Daftar Ikan')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-5xl font-bold text-white mb-3">Halaman Daftar Ikan</h1>
        <p class="text-cyan-300/80">Kelola koleksi ikan dalam Fish It Roblox</p>
    </div>

    <!-- Filter & Search -->
    <div class="bg-cyan-900/30 backdrop-blur-sm rounded-xl p-6 mb-6 border border-cyan-700/50">
        <form method="GET" action="{{ route('fishes.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">🔍 Cari Ikan</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Cari nama ikan..." 
                    class="w-full px-4 py-2 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white placeholder-cyan-500/50 focus:outline-none focus:border-cyan-500">
            </div>

            <!-- Filter Rarity -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">⭐ Filter Rarity</label>
                <select name="rarity" class="w-full px-4 py-2 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white focus:outline-none focus:border-cyan-500">
                    <option value="">Semua Rarity</option>
                    @foreach($rarities as $rarity)
                        <option value="{{ $rarity }}" {{ request('rarity') == $rarity ? 'selected' : '' }}>
                            {{ $rarity }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-cyan-300 text-sm font-semibold mb-2">📊 Urutkan</label>
                <div class="flex gap-2">
                    <select name="sort" class="flex-1 px-4 py-2 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white focus:outline-none focus:border-cyan-500">
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama</option>
                        <option value="sell_price_per_kg" {{ request('sort') == 'sell_price_per_kg' ? 'selected' : '' }}>Harga</option>
                        <option value="catch_probability" {{ request('sort') == 'catch_probability' ? 'selected' : '' }}>Probabilitas</option>
                    </select>
                    <select name="order" class="px-4 py-2 bg-cyan-950/50 border border-cyan-700 rounded-lg text-white focus:outline-none focus:border-cyan-500">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>↑</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>↓</option>
                    </select>
                </div>
            </div>

            <div class="md:col-span-3 flex justify-end gap-2">
                <a href="{{ route('fishes.index') }}" class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                    🔄 Reset
                </a>
                <button type="submit" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition">
                    ✓ Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-cyan-900/30 backdrop-blur-sm rounded-xl overflow-hidden border border-cyan-700/50">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-cyan-800/50 border-b border-cyan-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-200 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-200 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-200 uppercase tracking-wider">Rarity</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-200 uppercase tracking-wider">Berat (kg)</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-200 uppercase tracking-wider">Harga/kg</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-cyan-200 uppercase tracking-wider">Probabilitas</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-cyan-200 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-cyan-800/30">
                    @forelse($fishes as $fish)
                        <tr class="hover:bg-cyan-800/20 transition">
                            <td class="px-6 py-4 text-white">{{ $fish->id }}</td>
                            <td class="px-6 py-4 text-white font-medium">{{ $fish->name }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold text-white rounded-full {{ $fish->rarity_color }}">
                                    {{ $fish->rarity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-cyan-300">{{ $fish->formatted_weight_range }}</td>
                            <td class="px-6 py-4 text-yellow-400 font-semibold">{{ $fish->formatted_price }}</td>
                            <td class="px-6 py-4 text-cyan-300">{{ $fish->catch_probability }}%</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('fishes.show', $fish) }}" 
                                        class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition">
                                        👁️ Lihat
                                    </a>
                                    <a href="{{ route('fishes.edit', $fish) }}" 
                                        class="px-3 py-1 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded transition">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('fishes.destroy', $fish) }}" method="POST" 
                                        onsubmit="return confirm('Yakin ingin menghapus ikan {{ $fish->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-cyan-300/60">
                                <div class="text-6xl mb-4">🐟</div>
                                <p class="text-xl mb-2">Tidak ada ikan ditemukan</p>
                                <p class="text-sm text-cyan-400/60 mb-4">Silakan tambahkan ikan baru untuk memulai</p>
                                <a href="{{ route('fishes.create') }}" class="inline-block mt-4 px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition">
                                    + Tambah Ikan Baru
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($fishes->hasPages())
            <div class="px-6 py-4 border-t border-cyan-800/30">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-cyan-300">
                        Menampilkan {{ $fishes->firstItem() }} - {{ $fishes->lastItem() }} dari {{ $fishes->total() }} ikan
                    </div>
                    <div class="flex gap-2">
                        {{-- Previous Page Link --}}
                        @if ($fishes->onFirstPage())
                            <span class="px-3 py-2 text-gray-500 bg-gray-700 rounded cursor-not-allowed">
                                ← Previous
                            </span>
                        @else
                            <a href="{{ $fishes->previousPageUrl() }}" class="px-3 py-2 bg-cyan-700 hover:bg-cyan-600 text-white rounded transition">
                                ← Previous
                            </a>
                        @endif

                        {{-- Next Page Link --}}
                        @if ($fishes->hasMorePages())
                            <a href="{{ $fishes->nextPageUrl() }}" class="px-3 py-2 bg-cyan-700 hover:bg-cyan-600 text-white rounded transition">
                                Next →
                            </a>
                        @else
                            <span class="px-3 py-2 text-gray-500 bg-gray-700 rounded cursor-not-allowed">
                                Next →
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection