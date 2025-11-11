<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { transition: all 0.3s ease; }
        .gradient-bg {
            background: linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 50%, #8b0000 100%);
        }
        .btn-hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(139, 0, 0, 0.3);
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center p-4">
    <!-- Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 -right-40 w-80 h-80 bg-red-900/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-red-800/5 rounded-full blur-3xl"></div>
    </div>

    <div class="w-full max-w-md relative z-10">
        <!-- Card -->
        <div class="bg-gray-900 backdrop-blur-md border border-red-900/50 rounded-2xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-block mb-4 float-animation">
                    <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-full flex items-center justify-center shadow-lg shadow-red-900/50">
                        <i class="fas fa-rocket text-white text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Manajemen Proyek</h1>
                <p class="text-gray-400 text-sm">Platform manajemen proyek modern dan interaktif</p>
            </div>

            <!-- Form -->
            <form action="login_logic.php" method="post" class="space-y-5">
                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-300 mb-2">
                        <i class="fas fa-user mr-2 text-red-500"></i>Username
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 hover:border-gray-600"
                            placeholder="Masukkan username"
                        >
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-300 mb-2">
                        <i class="fas fa-lock mr-2 text-red-500"></i>Password
                    </label>
                    <div class="relative">
                        <input 
                            type="password" 
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-500/20 hover:border-gray-600"
                            placeholder="Masukkan password"
                        >
                    </div>
                </div>

                <!-- Login Button -->
                <button 
                    type="submit"
                    class="w-full mt-6 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg transition duration-200 btn-hover flex items-center justify-center space-x-2 shadow-lg"
                >
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Masuk Sekarang</span>
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-6 pt-6 border-t border-gray-700">
                <p class="text-center text-gray-400 text-xs">
                </p>
            </div>
        </div>
    </div>
</body>
</html>