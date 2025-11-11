<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Eksplor Sinjai - Permata Selatan Sulawesi')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Poppins', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .animate-fadeInUp {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.8s ease-out forwards;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .hover-scale {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .card-shimmer {
            position: relative;
            overflow: hidden;
        }

        .card-shimmer::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .card-shimmer:hover::before {
            left: 100%;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg fixed w-full top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 gradient-bg rounded-lg flex items-center justify-center">
                        <i class="fas fa-mountain text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold gradient-text">Sinjai</h1>
                        <p class="text-xs text-gray-500">Permata Selatan Sulawesi</p>
                    </div>
                </div>
                
                <div class="hidden md:flex space-x-8">
                    <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        <i class="fas fa-home mr-2"></i>Home
                    </x-nav-link>
                    <x-nav-link href="{{ route('destinasi') }}" :active="request()->routeIs('destinasi')">
                        <i class="fas fa-map-marked-alt mr-2"></i>Destinasi
                    </x-nav-link>
                    <x-nav-link href="{{ route('kuliner') }}" :active="request()->routeIs('kuliner')">
                        <i class="fas fa-utensils mr-2"></i>Kuliner
                    </x-nav-link>
                    <x-nav-link href="{{ route('galeri') }}" :active="request()->routeIs('galeri')">
                        <i class="fas fa-images mr-2"></i>Galeri
                    </x-nav-link>
                    <x-nav-link href="{{ route('kontak') }}" :active="request()->routeIs('kontak')">
                        <i class="fas fa-envelope mr-2"></i>Kontak
                    </x-nav-link>
                </div>

                <button id="mobile-menu-btn" class="md:hidden text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="px-4 py-4 space-y-3">
                <a href="{{ route('home') }}" class="block py-2 px-4 rounded-lg {{ request()->routeIs('home') ? 'gradient-bg text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-home mr-2"></i>Home
                </a>
                <a href="{{ route('destinasi') }}" class="block py-2 px-4 rounded-lg {{ request()->routeIs('destinasi') ? 'gradient-bg text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-map-marked-alt mr-2"></i>Destinasi
                </a>
                <a href="{{ route('kuliner') }}" class="block py-2 px-4 rounded-lg {{ request()->routeIs('kuliner') ? 'gradient-bg text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-utensils mr-2"></i>Kuliner
                </a>
                <a href="{{ route('galeri') }}" class="block py-2 px-4 rounded-lg {{ request()->routeIs('galeri') ? 'gradient-bg text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-images mr-2"></i>Galeri
                </a>
                <a href="{{ route('kontak') }}" class="block py-2 px-4 rounded-lg {{ request()->routeIs('kontak') ? 'gradient-bg text-white' : 'text-gray-700 hover:bg-gray-100' }}">
                    <i class="fas fa-envelope mr-2"></i>Kontak
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="pt-20">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="gradient-bg text-white mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Eksplor Sinjai</h3>
                    <p class="text-gray-200">Jelajahi keindahan alam, budaya, dan kuliner khas Kabupaten Sinjai, Sulawesi Selatan.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Menu Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="hover:text-gray-300 transition">Home</a></li>
                        <li><a href="{{ route('destinasi') }}" class="hover:text-gray-300 transition">Destinasi Wisata</a></li>
                        <li><a href="{{ route('kuliner') }}" class="hover:text-gray-300 transition">Kuliner</a></li>
                        <li><a href="{{ route('galeri') }}" class="hover:text-gray-300 transition">Galeri</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Ikuti Kami</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-white text-purple-600 rounded-full flex items-center justify-center hover:scale-110 transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white text-purple-600 rounded-full flex items-center justify-center hover:scale-110 transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white text-purple-600 rounded-full flex items-center justify-center hover:scale-110 transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-white border-opacity-20 mt-8 pt-8 text-center">
                <p>&copy; 2025 Eksplor Sinjai. All rights reserved. Made with <i class="fas fa-heart text-red-400"></i> for Sinjai</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            document.getElementById('mobile-menu').classList.toggle('hidden');
        });

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(el);
        });
    </script>
</body>
</html>