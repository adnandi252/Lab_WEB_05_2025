@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-blue-950 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <div class="mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-white">Category Details</h1>
                    <p class="mt-2 text-sm text-white">View complete category information</p>
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('categories.edit', $category->id) }}" 
                       class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition duration-150">
                        <i class="fas fa-edit mr-2"></i>
                        Edit
                    </a>
                    <form action="{{ route('categories.destroy', $category->id) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Are you sure you want to delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-150">
                            <i class="fas fa-trash mr-2"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Category Information</h2>
            </div>
            <div class="p-6 space-y-4">
                <!-- ID -->
                <div class="flex">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500">ID</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-sm text-gray-900">{{ $category->id }}</p>
                    </div>
                </div>

                <!-- Name -->
                <div class="flex">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500">Name</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-sm font-semibold text-gray-900">{{ $category->name }}</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="flex">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500">Description</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-sm text-gray-900">{{ $category->description ?? '-' }}</p>
                    </div>
                </div>

                <!-- Created At -->
                <div class="flex">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500">Created At</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-sm text-gray-900">{{ $category->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                <!-- Updated At -->
                <div class="flex">
                    <div class="w-1/3">
                        <p class="text-sm font-medium text-gray-500">Last Updated</p>
                    </div>
                    <div class="w-2/3">
                        <p class="text-sm text-gray-900">{{ $category->updated_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products in this Category -->
        @if($category->products && $category->products->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">
                    Products in this Category
                    <span class="ml-2 px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">
                        {{ $category->products->count() }}
                    </span>
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($category->products as $product)
                    <a href="{{ route('products.show', $product->id) }}" 
                       class="flex items-center p-4 border border-gray-200 rounded-lg hover:border-blue-300 hover:bg-blue-50 transition duration-150">
                        <div class="shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                            <p class="text-xs text-gray-500">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Products in this Category</h2>
            </div>
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                <p>No products in this category yet</p>
            </div>
        </div>
        @endif

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('categories.index') }}" 
               class="inline-flex items-center text-white hover:text-gray-500 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Categories
            </a>
        </div>

    </div>
</div>
@endsection