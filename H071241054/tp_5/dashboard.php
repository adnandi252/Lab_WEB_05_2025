<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$role = $user['role'] ?? 'user';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col items-center py-10">
  <div class="bg-white shadow-xl rounded-2xl w-full max-w-3xl p-8">
    <h1 class="text-2xl font-bold mb-6 text-gray-700">
      Selamat Datang, <?= $user['nama'] ?>!
    </h1>

    <?php if ($role === 'admin'): ?>
      <h2 class="text-xl font-semibold mb-4">Data Pengguna</h2>
      <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="py-2 px-2 border">Nama</th>
            <th class="py-2 px-2 border">Username</th>
            <th class="py-2 px-2 border">Email</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($_SESSION['all_users'] as $u): ?>
            <tr class="hover:bg-gray-50">
              <td class="py-2 px-2 border"><?= htmlspecialchars($u['nama']) ?></td>
              <td class="py-2 px-2 border"><?= htmlspecialchars($u['username']) ?></td>
              <td class="py-2 px-2 border"><?= htmlspecialchars($u['email']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php else: ?>
      <h2 class="text-xl font-semibold mb-4">Data Diri Anda</h2>
      <table class="w-full border border-gray-300 rounded-lg overflow-hidden">
        <tbody class="bg-white divide-y divide-gray-200">
          <tr>
            <th class="text-left bg-gray-100 px-4 py-2 w-1/3">Nama</th>
            <td class="px-4 py-2"><?= htmlspecialchars($user['nama']) ?></td>
          </tr>
          <tr>
            <th class="text-left bg-gray-100 px-4 py-2">Username</th>
            <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
          </tr>
          <tr>
            <th class="text-left bg-gray-100 px-4 py-2">Email</th>
            <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
          </tr>
          <tr>
            <th class="text-left bg-gray-100 px-4 py-2">Gender</th>
            <td class="px-4 py-2"><?= htmlspecialchars($user['gender']) ?></td>
          </tr>
          <tr>
            <th class="text-left bg-gray-100 px-4 py-2">Fakultas</th>
            <td class="px-4 py-2"><?= htmlspecialchars($user['fakultas']) ?></td>
          </tr>
          <tr>
            <th class="text-left bg-gray-100 px-4 py-2">Angkatan</th>
            <td class="px-4 py-2"><?= htmlspecialchars($user['angkatan']) ?></td>
          </tr>
        </tbody>
      </table>
    <?php endif; ?>

    <div class="text-center mt-6">
      <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">Logout</a>
    </div>
  </div>
</body>
</html>
