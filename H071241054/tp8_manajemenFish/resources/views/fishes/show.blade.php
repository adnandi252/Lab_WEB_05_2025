@extends('layouts.app')

@section('title', 'Detail Ikan')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Detail Ikan</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="mb-4">
                <a href="{{ route('fishes.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Kembali
                </a>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">ID</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->id }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Nama</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->name }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Kelangkaan</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($fish->rarity == 'Common') bg-gray-200 text-gray-800
                                @elseif($fish->rarity == 'Uncommon') bg-green-200 text-green-800
                                @elseif($fish->rarity == 'Rare') bg-blue-200 text-blue-800
                                @elseif($fish->rarity == 'Epic') bg-yellow-200 text-yellow-800
                                @elseif($fish->rarity == 'Legendary') bg-red-200 text-red-800
                                @elseif($fish->rarity == 'Mythic') bg-gray-800 text-white
                                @elseif($fish->rarity == 'Secret') bg-white border border-gray-300 text-gray-800
                                @endif">
                                {{ $fish->rarity }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rentang Berat</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->weight_range }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Harga per kg</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->formatted_price }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Peluang Tangkap</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->catch_probability }}%</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Deskripsi</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->description ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Dibuat Pada</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->created_at->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Diupdate Pada</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $fish->updated_at->format('d M Y H:i') }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-6 flex space-x-2">
                <a href="{{ route('fishes.edit', $fish) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Edit
                </a>
                <form method="POST" action="{{ route('fishes.destroy', $fish) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" 
                        onclick="return confirm('Apakah Anda yakin?')">
                        Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection