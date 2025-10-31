<?php
session_start();
require 'data.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 min-h-screen">
  <div class="max-w-4xl mx-auto mt-10 bg-white p-8 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-800">
        <?php if ($user['username'] === 'adminxxx'): ?>
          Selamat Datang, Admin!
        <?php else: ?>
          Selamat Datang, <?= htmlspecialchars($user['name']) ?>!
        <?php endif; ?>
      </h1>
      <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </div>

    <?php if ($user['username'] === 'adminxxx'): ?>
      <h2 class="text-lg font-semibold mb-4">Data Semua Pengguna</h2>
      <table class="w-full border border-gray-300 text-left">
        <thead class="bg-gray-100">
          <tr>
            <th class="p-2 border">No</th>
            <th class="p-2 border">Nama</th>
            <th class="p-2 border">Username</th>
            <th class="p-2 border">Email</th>
            <th class="p-2 border">Gender</th>
            <th class="p-2 border">Fakultas</th>
            <th class="p-2 border">Angkatan</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $i => $u): ?>
            <tr class="hover:bg-gray-50">
              <td class="p-2 border"><?= $i + 1 ?></td>
              <td class="p-2 border"><?= htmlspecialchars($u['name']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($u['username']) ?></td>
              <td class="p-2 border"><?= htmlspecialchars($u['email']) ?></td>
              <td class="p-2 border"><?= $u['gender'] ?? '-' ?></td>
              <td class="p-2 border"><?= $u['faculty'] ?? '-' ?></td>
              <td class="p-2 border"><?= $u['batch'] ?? '-' ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="space-y-2">
        <p><strong>Nama:</strong> <?= htmlspecialchars($user['name']) ?></p>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Gender:</strong> <?= $user['gender'] ?? '-' ?></p>
        <p><strong>Fakultas:</strong> <?= $user['faculty'] ?? '-' ?></p>
        <p><strong>Angkatan:</strong> <?= $user['batch'] ?? '-' ?></p>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
