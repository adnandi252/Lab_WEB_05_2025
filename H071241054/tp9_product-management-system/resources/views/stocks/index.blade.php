@extends('layouts.app')

@section('title', 'Stock Management')

@section('content')
<div class="min-h-screen bg-blue-950 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">

        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-white">Stock Management</h1>
                    <p class="mt-2 text-sm text-white">Manage product stock across warehouses</p>
                </div>
                <a href="{{ route('stocks.transfer.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-400 hover:bg-green-200 text-black font-medium rounded-lg transition duration-150">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    Transfer Stock
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

        <!-- Filter Section -->
        <div class="bg-gray-800 rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-white mb-4">Filter Warehouse</h3>
            <form method="GET" action="{{ route('stocks.index') }}" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-1">
                    <select name="warehouse_id" id="warehouse_id" 
                            class="block w-full border border-gray-600 bg-gray-700 text-white rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            onchange="this.form.submit()">
                        <option value="" class="bg-gray-700">All Warehouses</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" 
                                {{ $selectedWarehouseId == $warehouse->id ? 'selected' : '' }}
                                class="bg-gray-700">
                                {{ $warehouse->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end">
                    <a href="{{ route('stocks.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white font-medium rounded-lg transition duration-150">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>

        <!-- Stocks Table -->
        <div class="bg-gray-300 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Warehouse
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current Stock
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($stocks as $stock)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-box text-blue-600 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $stock->product->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $stock->product->detail->size ?? 'No size' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="h-8 w-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-warehouse text-orange-600 text-xs"></i>
                                    </div>
                                    <div class="text-sm text-gray-900">{{ $stock->warehouse->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                Rp {{ number_format($stock->product->price, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-gray-900">{{ $stock->quantity }}</div>
                                <div class="text-xs text-gray-500">units</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($stock->quantity > 20)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Good
                                    </span>
                                @elseif($stock->quantity > 0)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Low
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Out of Stock
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <a href="{{ route('stocks.transfer.create') }}?warehouse_id={{ $stock->warehouse_id }}&product_id={{ $stock->product_id }}&current_stock={{ $stock->quantity }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-lg transition duration-150">
                                    <i class="fas fa-edit mr-1"></i>
                                    Manage
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-boxes text-4xl mb-3 text-gray-300"></i>
                                    <p class="text-lg font-medium mb-2">No stock data found</p>
                                    <p class="text-sm mb-4">Add stock by transferring products to warehouses</p>
                                    <a href="{{ route('stocks.transfer.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150">
                                        <i class="fas fa-exchange-alt mr-2"></i>
                                        Transfer Stock
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
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