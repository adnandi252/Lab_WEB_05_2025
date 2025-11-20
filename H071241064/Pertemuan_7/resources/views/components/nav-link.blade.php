@props(['active' => false, 'mobile' => false])

@php
    $classes = $active 
        ? ($mobile 
            ? 'block px-4 py-3 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-lg' 
            : 'px-4 py-2 text-sm font-medium text-emerald-600 bg-emerald-50 rounded-lg')
        : ($mobile
            ? 'block px-4 py-3 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition'
            : 'px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 rounded-lg transition');
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>