<?php
session_start();

if (isset($_SESSION['user'])) {
    header('Location: dashboard.php');
    exit;
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black/5 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="bg-gray-400 p-8 text-center">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                <img src="assets/icon-user.png" alt="Logo" class="w-full h-full object-contain p-2">
            </div>
            <h1 class="text-3xl font-bold text-white">Selamat Datang</h1>
            <p class="text-white mt-2">Silahkan login untuk melanjutkan</p>
        </div>
        
        <div class="p-8">
            <?php if ($error): ?>
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded mb-6">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form action="proses_login.php" method="POST" class="space-y-6">
                <div>
                    <label for="username" class="block text-sm font-semibold text-gray-700 mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center">
                            <img src="assets/icon-user.png" alt="User" class="h-5 w-5">
                        </div>
                        <input type="text" id="username" name="username"
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-400" placeholder="Masukkan username Anda">
                    </div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <img src="assets/icon-lock.png" alt="Lock" class="h-5 w-5">
                        </div>
                        <input type="password" id="password" name="password"  
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition placeholder-gray-400" placeholder="Masukkan password Anda">
                    </div>
                </div>
                
                <button type="submit" 
                    class="w-full bg-black text-white font-semibold py-3 rounded-lg hover:scale-[1.02] transition duration-200 shadow-lg">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>