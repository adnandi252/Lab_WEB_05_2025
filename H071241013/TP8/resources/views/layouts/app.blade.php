<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Fishes Simulator')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .ocean-bg {
            background: linear-gradient(180deg, #0a4d68 0%, #05364d 50%, #041c32 100%);
            min-height: 100vh;
        }
        .navbar-glass {
            background: rgba(10, 77, 104, 0.8);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="ocean-bg">
    <!-- Navbar -->
    <nav class="navbar-glass border-b border-cyan-700/50 sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-8">
                    <h1 class="text-2xl font-bold text-cyan-300">🐟 Fishes Simulator</h1>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('fishes.index') }}" class="px-6 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg transition duration-300">
                        Lihat Ikan
                    </a>
                    <a href="{{ route('fishes.create') }}" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition duration-300">
                        + Tambah Ikan
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-600/80 backdrop-blur-sm text-white rounded-lg shadow-lg border border-green-500">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-600/80 backdrop-blur-sm text-white rounded-lg shadow-lg border border-red-500">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-12 py-6 text-center text-cyan-300/60 text-sm">
        <p>"Setiap baris kode yang kamu tulis adalah langkah menuju secret. Teruslah memancing, karena waktu adalah secret." 🎣</p>
    </footer>
</body>
</html>