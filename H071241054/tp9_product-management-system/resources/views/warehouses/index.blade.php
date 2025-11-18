@extends('layouts.app')

@section('title', 'Warehouse Management')

@section('content')
<div class="min-h-screen bg-blue-950 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Warehouse Management</h1>
                    <p class="mt-2 text-sm text-white">Manage all warehouse locations</p>
                </div>
                <a href="{{ route('warehouses.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-400 hover:bg-green-200 text-black font-medium rounded-lg transition duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    Add New Warehouse
                </a>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <!-- Table -->
        <div class="bg-gray-300 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NO
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Warehouse Name
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Location
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Products
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Stock
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($warehouses as $warehouse)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $loop->iteration + ($warehouses->currentPage() - 1) * $warehouses->perPage() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-warehouse text-orange-600 text-sm"></i>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">{{ $warehouse->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-600">
                                    @if($warehouse->location)
                                        {{ \Illuminate\Support\Str::limit($warehouse->location, 60) }}
                                    @else
                                        <span class="text-gray-400">No location specified</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $warehouse->products_count ?? $warehouse->products->count() }} products
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $totalStock = $warehouse->products->sum('pivot.quantity');
                                @endphp
                                <div class="text-sm font-semibold {{ $totalStock > 0 ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $totalStock }} units
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('warehouses.edit', $warehouse->id) }}" 
                                       class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition duration-150">
                                        <i class="fas fa-edit mr-1"></i>
                                        Edit
                                    </a>
                                    <form action="{{ route('warehouses.destroy', $warehouse->id) }}" 
                                          method="POST" 
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded-lg transition duration-150"
                                                onclick="return confirm('Are you sure you want to delete this warehouse? All stock in this warehouse will be lost.')">
                                            <i class="fas fa-trash mr-1"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-warehouse text-4xl mb-3 text-gray-300"></i>
                                    <p class="text-lg font-medium mb-2">No warehouses found</p>
                                    <p class="text-sm mb-4">Get started by creating your first warehouse</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($warehouses->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $warehouses->firstItem() }} to {{ $warehouses->lastItem() }} of {{ $warehouses->total() }} results
                    </div>
                    <div>
                        {{ $warehouses->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ url('/') }}" 
               class="inline-flex items-center text-white hover:text-gray-300 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Home
            </a>
        </div>

    </div>
</div>
@endsection