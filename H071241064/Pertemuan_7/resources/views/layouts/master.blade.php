<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eksplor Pariwisata Kendari')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50">
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20 gap-4">
                <div class="flex items-center">
                    <a class="flex items-center space-x-2">
                        <img src="images/lambang_kota.png" alt="Logo Kendari" class="w-10 h-10 object-cover">
                        <div class="hidden sm:block">
                            <h1 class="text-xl font-bold text-slate-900">Kendari</h1>
                            <p class="text-xs text-slate-500">Eksplor Pariwisata</p>
                        </div>
                    </a>
                </div>

                <nav class="flex space-x-1 text-sm lg:text-base">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        Home
                    </x-nav-link>
                    <x-nav-link href="{{ route('destinasi') }}" :active="request()->routeIs('destinasi')">
                        Destinasi
                    </x-nav-link>
                    <x-nav-link href="{{ route('kuliner') }}" :active="request()->routeIs('kuliner')">
                        Kuliner
                    </x-nav-link>
                    <x-nav-link href="{{ route('galeri') }}" :active="request()->routeIs('galeri')">
                        Galeri
                    </x-nav-link>
                    <x-nav-link href="{{ route('kontak') }}" :active="request()->routeIs('kontak')">
                        Kontak
                    </x-nav-link>
                </nav>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="bg-slate-900 text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="border-t border-slate-800 pt-8 text-center text-sm text-slate-400">
                <p>&copy; 2025 Eksplor Pariwisata Kendari. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>