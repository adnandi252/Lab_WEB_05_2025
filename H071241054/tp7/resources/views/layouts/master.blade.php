<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wonderful Toraja - @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="absolute w-full z-50 bg-transparent">
        <div class="container mx-auto px-4 py-8">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <h1 class="text-2xl font-bold text-white">Wonderful Indonesia</h1>
                </div>
                <nav>
                    <ul class="flex space-x-8">
                        <li>
                            <a href="/" class="font-semibold text-white transition duration-300 hover:text-green-200 {{ request()->is('/') ? 'text-green-200 border-b-2 border-green-200' : '' }}">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="/destinasi" class="font-semibold text-white transition duration-300 hover:text-green-200 {{ request()->is('destinasi') ? 'text-green-200 border-b-2 border-green-200' : '' }}">
                                Destinasi
                            </a>
                        </li>
                        <li>
                            <a href="/kuliner" class="font-semibold text-white transition duration-300 hover:text-green-200 {{ request()->is('kuliner') ? 'text-green-200 border-b-2 border-green-200' : '' }}">
                                Kuliner
                            </a>
                        </li>
                        <li>
                            <a href="/galeri" class="font-semibold text-white transition duration-300 hover:text-green-200 {{ request()->is('galeri') ? 'text-green-200 border-b-2 border-green-200' : '' }}">
                                Galeri
                            </a>
                        </li>
                        <li>
                            <a href="/kontak" class="font-semibold text-white transition duration-300 hover:text-green-200 {{ request()->is('kontak') ? 'text-green-200 border-b-2 border-green-200' : '' }}">
                                Kontak
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="flex justify-center space-x-6 mb-4">
                <a href="#" class="hover:text-green-300 transition duration-300">
                    <i class="fab fa-facebook text-xl"></i>
                </a>
                <a href="#" class="hover:text-green-300 transition duration-300">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
                <a href="#" class="hover:text-green-300 transition duration-300">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
            </div>
            <p class="text-sm text-gray-300">&copy; 2024 Eksplore Nusantara. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>