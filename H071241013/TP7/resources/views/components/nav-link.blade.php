@props(['active' => false, 'href' => '#'])

<a href="{{ $href }}" 
   class="nav-link text-gray-700 hover:text-purple-600 font-medium transition {{ $active ? 'active text-purple-600' : '' }}">
    {{ $slot }}
</a>