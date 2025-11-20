@props(['title', 'description', 'image'])

<div class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 group cursor-pointer transform hover:-translate-y-2">
    <div class="relative h-64 overflow-hidden">
        <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300"></div>
    </div>
    <div class="p-6">
        <h3 class="text-2xl font-black text-slate-900 mb-3 group-hover:text-sky-600 transition-colors">
            {{ $title }}
        </h3>
        <p class="text-slate-600 leading-relaxed mb-4">
            {{ $description }}
        </p>
    </div>
</div>