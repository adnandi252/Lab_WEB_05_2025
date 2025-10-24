<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$currentUser = $_SESSION['user'];
$isAdmin = ($currentUser['username'] === 'adminxxx');

if ($isAdmin) {
    require_once 'data.php';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistem Login Sederhana</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <nav class="bg-black shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-3">
                    <h1 class="text-white text-2xl font-bold">Dashboard</h1>
                </div>
                <a href="logout.php" 
                    class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-semibold px-6 py-2 rounded-lg transition duration-200 flex items-center space-x-2">
                    <img src="assets/logout-icon.png" alt="Logout" class="w-5 h-5">
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-xl shadow-md p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">
                        Selamat Datang, <?php echo htmlspecialchars($currentUser['name']); ?>!
                    </h2>
                    <p class="text-gray-600">Anda berhasil login ke sistem</p>
                </div>
                <div>
                    <?php if ($isAdmin): ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800">
                            ADMIN
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            USER
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="p-8">
            <?php if ($isAdmin): ?>
                <div class="flex items-center mb-6 pb-4 border-b-2 border-black/50">
                    <h3 class="text-2xl font-bold text-gray-800">Data Semua Pengguna</h3>
                </div>

                <div class="overflow-x-auto -mx-8 px-8">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-black/80">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Username</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Fakultas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Angkatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($users as $index => $user): ?>
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo $index + 1; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($user['username']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700"><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo isset($user['gender']) ? htmlspecialchars($user['gender']) : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo isset($user['faculty']) ? htmlspecialchars($user['faculty']) : '-'; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                        <?php echo isset($user['batch']) ? htmlspecialchars($user['batch']) : '-'; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="flex items-center mb-6 pb-4 border-b-2 border-gray-300">
                    <h3 class="text-2xl font-bold text-gray-800">Profil Saya</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-gray-100 rounded-lg p-5 border-l-4 border-gray-500">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Nama Lengkap</p>
                        <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($currentUser['name']); ?></p>
                    </div>

                    <div class="bg-gray-100 from-purple-50 to-indigo-50 rounded-lg p-5 border-l-4 border-gray-500">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Username</p>
                        <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($currentUser['username']); ?></p>
                    </div>

                    <div class="bg-gray-100 rounded-lg p-5 border-l-4 border-gray-500">
                        <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Email</p>
                        <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($currentUser['email']); ?></p>
                    </div>

                    <?php if (isset($currentUser['gender'])): ?>
                        <div class="bg-gray-100 rounded-lg p-5 border-l-4 border-gray-500">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Jenis Kelamin</p>
                            <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($currentUser['gender']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($currentUser['faculty'])): ?>
                        <div class="bg-gray-100 rounded-lg p-5 border-l-4 border-gray-500">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Fakultas</p>
                            <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($currentUser['faculty']); ?></p>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($currentUser['batch'])): ?>
                        <div class="bg-gray-100 rounded-lg p-5 border-l-4 border-gray-500">
                            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wide mb-2">Angkatan</p>
                            <p class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($currentUser['batch']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>