@props(['image', 'title'])

<div class="bg-gray-700 rounded-xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:transform hover:-translate-y-2 h-full">
    @if($image)
    <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-64 object-cover">
    @endif
    <div class="p-6">
        <h3 class="text-xl font-bold text-white mb-4">{{ $title }}</h3>
        <div class="text-white mb-6">
            {{ $slot }}
        </div>
    </div>
</div>