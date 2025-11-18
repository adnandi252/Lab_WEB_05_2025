@extends('layouts.app')

@section('title', 'Edit Warehouse')

@section('content')
<div class="min-h-screen bg-blue-950 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 mt-20">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-white">Edit Warehouse</h1>
            <p class="mt-2 text-sm text-white">Update warehouse information</p>
        </div>

        <!-- Form Card -->
        <div class="bg-gray-300 rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <form action="{{ route('warehouses.update', $warehouse->id) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Warehouse Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Warehouse Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name" 
                           value="{{ old('name', $warehouse->name) }}"
                           class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('name') border-red-500 @enderror"
                           placeholder="e.g., Main Warehouse, Branch Warehouse, Storage Facility"
                           required>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">
                        Location
                    </label>
                    <textarea name="location" 
                              id="location" 
                              rows="4"
                              class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 @error('location') border-red-500 @enderror"
                              placeholder="Enter warehouse address and location details (optional)">{{ old('location', $warehouse->location) }}</textarea>
                    @error('location')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-2 text-xs text-gray-500">Provide the complete address and location details</p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium">Last Updated</p>
                            <p class="mt-1">{{ $warehouse->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200">
                    <a href="{{ route('warehouses.index') }}" 
                       class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-150">
                        <i class="fas fa-save mr-2"></i>
                        Update Warehouse
                    </button>
                </div>
            </form>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('warehouses.index') }}" 
               class="inline-flex items-center text-white hover:text-gray-300 transition duration-150">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Warehouses
            </a>
        </div>

    </div>
</div>
@endsection