@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-blue-950 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-white">{{ $product->name }}</h1>
                    <p class="mt-2 text-sm text-white">View complete product information</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('products.edit', $product->id) }}" 
                       class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition duration-150">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-150">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <!-- Product Information -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Product Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">ID</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">{{ $product->id }}</p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Name</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm font-semibold text-gray-900">{{ $product->name }}</p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Category</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">{{ $product->category->name ?? 'No Category' }}</p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Price</p>
                            </div>
                            <div>
                                <dd class="mt-1 text-sm text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</dd>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Weight</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">
                                    {{ $product->detail->weight ?? '-' }} kg
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Size</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">
                                    {{ $product->detail->size ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Description</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">
                                    {{ $product->detail->description ?? 'No description available' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Created At</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">{{ $product->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>

                        <div class="flex">
                            <div class="w-1/3">
                                <p class="text-sm font-medium text-gray-500">Last Updated</p>
                            </div>
                            <div class="w-2/3">
                                <p class="text-sm text-gray-900">{{ $product->updated_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Info -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Stock Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <div class="text-2xl font-bold text-gray-900">
                                {{ $product->warehouses->sum('pivot.quantity') }}
                            </div>
                            <div class="text-sm text-gray-500">Total Units in Stock</div>
                        </div>

                        @if($product->warehouses->count() > 0)
                            <div class="divide-y divide-gray-200">
                                @foreach($product->warehouses as $warehouse)
                                    <div class="flex justify-between py-2 text-sm">
                                        <span class="text-gray-600">{{ $warehouse->name }}</span>
                                        <span class="font-medium text-gray-900">{{ $warehouse->pivot->quantity }} units</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-sm text-gray-500 mt-4">
                                No stock information available
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-8">
            <a href="{{ route('products.index') }}" 
               class="inline-flex items-center text-white hover:text-gray-400 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Products
            </a>
        </div>
    </div>
</div>
@endsection