@props(['image' => '', 'title' => '', 'description' => '', 'badge' => ''])

<div class="bg-white rounded-2xl shadow-lg overflow-hidden hover-scale card-shimmer animate-on-scroll">
    @if($image)
    <div class="relative h-64 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover transition duration-500 hover:scale-110">
        @if($badge)
        <span class="absolute top-4 right-4 px-4 py-2 gradient-bg text-white text-sm font-semibold rounded-full">
            {{ $badge }}
        </span>
        @endif
    </div>
    @endif
    
    <div class="p-6">
        @if($title)
        <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $title }}</h3>
        @endif
        
        @if($description)
        <p class="text-gray-600 leading-relaxed">{{ $description }}</p>
        @endif
        
        {{ $slot }}
    </div>
</div>