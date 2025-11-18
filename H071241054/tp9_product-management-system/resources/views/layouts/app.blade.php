<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.5">
    <title>Product Management System</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind custom config -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'mint-light': '#bbf7d0'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navigation Bar -->
    <header class="absolute top-0 left-0 right-0 z-50 bg-transparent shadow-none">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-4">
            <div class="flex items-center h-16 justify-between">
                <div class="flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                        <i class="fas fa-boxes text-yellow-400 text-2xl transition-transform duration-300 group-hover:scale-110"></i>
                        <span class="text-lg font-bold text-white drop-shadow-md transition-transform duration-300 group-hover:scale-105">
                            Product Management System
                        </span>
                    </a>
                </div>
                <nav>
                    <ul class="flex space-x-8">
                        <li>
                            <a href="{{ route('categories.index') }}"
                               class="relative inline-block font-semibold text-white py-2 px-3 rounded-lg transition-all duration-300 transform 
                               hover:scale-105 hover:text-mint-light 
                               after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full 
                               after:h-[2px] after:bg-mint-light after:scale-x-0 after:origin-left after:transition-transform after:duration-300 
                               hover:after:scale-x-100 {{ request()->routeIs('categories.*') ? 'text-mint-light border-b-2 border-mint-light' : '' }}">
                                Categories
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('warehouses.index') }}"
                               class="relative inline-block font-semibold text-white py-2 px-3 rounded-lg transition-all duration-300 transform 
                               hover:scale-105 hover:text-mint-light 
                               after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full 
                               after:h-[2px] after:bg-mint-light after:scale-x-0 after:origin-left after:transition-transform after:duration-300 
                               hover:after:scale-x-100 {{ request()->routeIs('warehouses.*') ? 'text-mint-light border-b-2 border-mint-light' : '' }}">
                                Warehouses
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}"
                               class="relative inline-block font-semibold text-white py-2 px-3 rounded-lg transition-all duration-300 transform 
                               hover:scale-105 hover:text-mint-light 
                               after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full 
                               after:h-[2px] after:bg-mint-light after:scale-x-0 after:origin-left after:transition-transform after:duration-300 
                               hover:after:scale-x-100 {{ request()->routeIs('products.*') ? 'text-mint-light border-b-2 border-mint-light' : '' }}">
                                Products
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('stocks.index') }}"
                               class="relative inline-block font-semibold text-white py-2 px-3 rounded-lg transition-all duration-300 transform 
                               hover:scale-105 hover:text-mint-light 
                               after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-full 
                               after:h-[2px] after:bg-mint-light after:scale-x-0 after:origin-left after:transition-transform after:duration-300 
                               hover:after:scale-x-100 {{ request()->routeIs('stocks.*') ? 'text-mint-light border-b-2 border-mint-light' : '' }}">
                                Stocks
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="grow relative">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-blue-950 py-4 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <p class="text-gray-300 text-sm drop-shadow-md">&copy; 2025 Product Management System. All rights reserved.</p>
        </div>
    </footer>

    @yield('scripts')
</body>
</html>
