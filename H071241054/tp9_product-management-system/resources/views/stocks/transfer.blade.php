@extends('layouts.app')

@section('title', 'Transfer Stock')

@section('content')
<div class="min-h-screen bg-blue-950 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">

        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-white">Transfer Stock</h1>
            <p class="mt-2 text-sm text-white">Add or reduce stock in warehouses</p>
        </div>

        <!-- Flash Messages -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(request()->has('warehouse_id') && request()->has('product_id'))
            @php
                $selectedWarehouse = \App\Models\Warehouse::find(request('warehouse_id'));
                $selectedProduct = \App\Models\Product::find(request('product_id'));
                $currentStock = request('current_stock', 0);
            @endphp
            @if($selectedWarehouse && $selectedProduct)
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                    <div class="flex-1">
                        <p class="font-medium text-blue-800">Managing Stock for:</p>
                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                            <div class="flex items-center">
                                <i class="fas fa-warehouse text-blue-600 mr-2 text-xs"></i>
                                <span class="text-blue-700">Warehouse: <strong>{{ $selectedWarehouse->name }}</strong></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-box text-blue-600 mr-2 text-xs"></i>
                                <span class="text-blue-700">Product: <strong>{{ $selectedProduct->name }}</strong></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-cubes text-blue-600 mr-2 text-xs"></i>
                                <span class="text-blue-700">Current Stock: <strong>{{ $currentStock }} units</strong></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-tag text-blue-600 mr-2 text-xs"></i>
                                <span class="text-blue-700">Price: <strong>Rp {{ number_format($selectedProduct->price, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @endif

        <div class="bg-gray-300 rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('stocks.transfer.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    <!-- Warehouse Selection -->
                    <div>
                        <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Choose Warehouse *
                        </label>
                        <select name="warehouse_id" id="warehouse_id" 
                                class="block w-full border rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('warehouse_id') border-red-500 @enderror"
                                required>
                            <option value="">Choose Warehouse</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}" 
                                    {{ old('warehouse_id', request('warehouse_id')) == $warehouse->id ? 'selected' : '' }}>
                                    {{ $warehouse->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('warehouse_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Product Selection -->
                    <div>
                        <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Choose Product *
                        </label>
                        <select name="product_id" id="product_id" 
                                class="block w-full border rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('product_id') border-red-500 @enderror"
                                required>
                            <option value="">Choose Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" 
                                    {{ old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}
                                    data-price="{{ $product->price }}">
                                    {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                        @error('product_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Stock Info (Readonly) -->
                    @if(request()->has('current_stock'))
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Current Stock
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   value="{{ request('current_stock') }} units"
                                   class="block w-full border border-gray-300 bg-gray-100 rounded-lg shadow-sm py-3 px-4 text-gray-600"
                                   readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">readonly</span>
                            </div>
                        </div>
                        <p class="mt-2 text-xs text-gray-500">
                            Current stock in the selected warehouse
                        </p>
                    </div>
                    @endif

                    <!-- Quantity Input -->
                    <div>
                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                            Quantity *
                        </label>
                        <div class="relative">
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   value="{{ old('quantity') }}"
                                   class="block w-full border rounded-lg shadow-sm py-3 px-4 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('quantity') border-red-500 @enderror"
                                   placeholder="Enter positive number to add, negative to reduce"
                                   required>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 text-sm">units</span>
                            </div>
                        </div>
                        @error('quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Positive number adds stock, negative number reduces stock
                        </p>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="3"
                                  class="block w-full border rounded-lg shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('notes') border-red-500 @enderror"
                                  placeholder="Add any notes about this stock transfer">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('stocks.index') }}" 
                       class="inline-flex items-center px-6 py-3 bg-gray-400 hover:bg-gray-500 text-black font-medium rounded-lg transition duration-150 w-full sm:w-auto justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Stocks
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-150 w-full sm:w-auto justify-center">
                        <i class="fas fa-exchange-alt mr-2"></i>
                        Process Transfer
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="mt-6 bg-blue-900 border border-blue-700 rounded-lg p-4">
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-300 mt-1 mr-3"></i>
                <div class="text-blue-100 text-sm">
                    <p class="font-semibold">Stock Transfer Rules:</p>
                    <ul class="mt-2 list-disc list-inside space-y-1">
                        <li>Positive numbers add stock to warehouse</li>
                        <li>Negative numbers reduce stock from warehouse</li>
                        <li>Cannot reduce stock below zero</li>
                        <li>Cannot reduce stock if product doesn't exist in warehouse</li>
                        <li>Ensure sufficient stock before reducing</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const warehouseId = urlParams.get('warehouse_id');
    const productId = urlParams.get('product_id');
    
    if (warehouseId) {
        document.getElementById('warehouse_id').value = warehouseId;
    }
    
    if (productId) {
        document.getElementById('product_id').value = productId;
    }
});
</script>
@endsection