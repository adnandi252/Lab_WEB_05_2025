@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-blue-950  py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Create New Category</h1>
            <p class="mt-2 text-sm text-white">Add a new product category to your system</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('categories.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Category Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Category Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('name') border-red-500 @enderror"
                           placeholder="e.g., Electronics, Fashion, Food"
                           required>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea name="description" 
                              id="description" 
                              rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('description') border-red-500 @enderror"
                              placeholder="Enter category description (optional)">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Provide a brief description of this category</p>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('categories.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-green-500 hover:bg-green-400 text-black font-medium rounded-lg transition duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Save Category
                    </button>
                </div>
            </form>
        </div>

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