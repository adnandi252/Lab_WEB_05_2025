<?php
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

require "connect.php";

$role = $_SESSION["role"];
$uid  = (int)$_SESSION["user_id"];
$username = htmlspecialchars($_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { transition: all 0.3s ease; }
        .btn-hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(139, 0, 0, 0.3); }
        .card-dark { background: linear-gradient(135deg, #1F2937 0%, #111827 100%); border: 1px solid rgba(139, 0, 0, 0.3); }
        .stat-card { background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%); }
        .glow-red { box-shadow: 0 0 20px rgba(220, 38, 38, 0.3); }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-950 via-black to-gray-900">
    <!-- Navbar -->
    <nav class="bg-gradient-to-r from-gray-900 via-black to-gray-900 border-b-2 border-red-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="halaman_utama.php" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center glow-red">
                        <i class="fas fa-rocket text-white text-lg"></i>
                    </div>
                    <span class="text-white font-bold text-xl hidden sm:inline">Manajemen Proyek</span>
                </a>

                <button class="md:hidden text-white hover:text-red-500 transition" onclick="document.getElementById('navbar-menu').classList.toggle('hidden')">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div id="navbar-menu" class="hidden md:flex md:items-center space-x-1">
                    <a href="halaman_utama.php" class="text-red-500 hover:text-red-400 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium bg-gray-800/50">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <?php if ($role === "super_admin"): ?>
                        <a href="users.php" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-users-cog mr-1"></i>Kelola Users
                        </a>
                    <?php endif; ?>
                    <?php if ($role === "project_manager" || $role === "super_admin"): ?>
                        <a href="projects.php" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-folder mr-1"></i>Projects
                        </a>
                        <a href="tasks.php" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-tasks mr-1"></i>Tasks
                        </a>
                    <?php elseif ($role === "team_member"): ?>
                        <a href="tasks.php?view=my" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
                            <i class="fas fa-tasks mr-1"></i>My Tasks
                        </a>
                    <?php endif; ?>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <div class="flex items-center space-x-2 px-3 py-2 bg-gray-800 rounded-lg border border-red-900/30">
                        <i class="fas fa-user-circle text-red-500"></i>
                        <span class="text-white font-semibold text-sm"><?= $username ?></span>
                        <span class="px-2 py-1 bg-red-600/20 text-red-400 text-xs font-bold rounded border border-red-500/50"><?= htmlspecialchars($role) ?></span>
                    </div>
                    <a href="logout.php" class="text-gray-300 hover:text-red-500 hover:bg-red-600/20 px-3 py-2 rounded-lg transition border border-red-600/50 hover:border-red-500">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-white mb-2"><i class="fas fa-chart-line text-red-500"></i> Dashboard</h1>
            <p class="text-gray-400">Selamat datang kembali, <span class="text-red-400 font-semibold"><?= $username ?></span></p>
        </div>

        <?php if ($role === "super_admin"): ?>
            <!-- Super Admin Dashboard -->
            <div class="mb-6 p-4 bg-red-600/20 border-l-4 border-red-600 rounded-lg">
                <p class="text-red-300"><i class="fas fa-shield-alt mr-2"></i><strong>Super Admin</strong> - Akses penuh ke seluruh sistem</p>
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM users) AS users,
                (SELECT COUNT(*) FROM users WHERE role='project_manager') AS pm,
                (SELECT COUNT(*) FROM users WHERE role='team_member') AS tm,
                (SELECT COUNT(*) FROM projects) AS projects,
                (SELECT COUNT(*) FROM tasks) AS tasks,
                (SELECT COUNT(*) FROM tasks WHERE status='selesai') AS tasks_done");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="card-dark rounded-xl p-6 glow-red hover:glow-red">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Users</p>
                            <p class="text-3xl font-bold text-white"><?= (int)$row["users"] ?></p>
                            <p class="text-xs text-gray-500 mt-2"><i class="fas fa-briefcase mr-1 text-red-500"></i>PM: <?= (int)$row["pm"] ?> | <i class="fas fa-user mr-1 text-red-500"></i>TM: <?= (int)$row["tm"] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="card-dark rounded-xl p-6 glow-red hover:glow-red">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Projects</p>
                            <p class="text-3xl font-bold text-white"><?= (int)$row["projects"] ?></p>
                            <p class="text-xs text-gray-500 mt-2"><i class="fas fa-folder-open mr-1 text-red-500"></i>Aktif saat ini</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="card-dark rounded-xl p-6 glow-red hover:glow-red">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Tasks</p>
                            <p class="text-3xl font-bold text-white"><?= (int)$row["tasks"] ?></p>
                            <p class="text-xs text-gray-500 mt-2"><i class="fas fa-check-circle mr-1 text-red-500"></i>Selesai: <?= (int)$row["tasks_done"] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-red-600/50 px-6 py-4">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-list mr-2 text-red-500"></i>Semua Proyek</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50 border-b border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">ID</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Nama Proyek</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Manager</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php
                            $q = mysqli_query($connect, "SELECT p.id, p.nama_proyek, p.tanggal_mulai, 
                                                        COALESCE(p.tanggal_selesai,'-') AS tsel, u.username
                                                        FROM projects p 
                                                        JOIN users u ON u.id=p.manager_id 
                                                        ORDER BY p.id DESC");
                            while ($p = mysqli_fetch_assoc($q)):
                            ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-300 font-mono"><?= $p['id'] ?></td>
                                <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($p['nama_proyek']) ?></td>
                                <td class="px-6 py-4"><span class="px-2 py-1 bg-red-600/20 text-red-400 text-xs font-bold rounded border border-red-500/50"><?= htmlspecialchars($p['username']) ?></span></td>
                                <td class="px-6 py-4 text-gray-400"><?= $p['tanggal_mulai'] ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= $p['tsel'] ?></td>
                                <td class="px-6 py-4">
                                    <a href="tasks.php?project=<?= $p['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded transition btn-hover">
                                        <i class="fas fa-list-check"></i> <span>Tasks</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($role === "project_manager"): ?>
            <!-- PM Dashboard -->
            <div class="mb-6 p-4 bg-blue-600/20 border-l-4 border-blue-600 rounded-lg">
                <p class="text-blue-300"><i class="fas fa-briefcase mr-2"></i><strong>Project Manager</strong> - Kelola proyek dan tim Anda</p>
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM projects WHERE manager_id=$uid) AS my_projects,
                (SELECT COUNT(*) FROM tasks WHERE project_id IN (SELECT id FROM projects WHERE manager_id=$uid)) AS my_tasks,
                (SELECT COUNT(*) FROM tasks WHERE status='selesai' AND project_id IN (SELECT id FROM projects WHERE manager_id=$uid)) AS done_tasks");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="card-dark rounded-xl p-6 glow-red hover:glow-red">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Proyek Saya</p>
                            <p class="text-3xl font-bold text-white"><?= (int)$row["my_projects"] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="card-dark rounded-xl p-6 glow-red hover:glow-red">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Total Tasks</p>
                            <p class="text-3xl font-bold text-white"><?= (int)$row["my_tasks"] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-white text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="card-dark rounded-xl p-6 glow-red hover:glow-red">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-400 text-sm mb-1">Tasks Selesai</p>
                            <p class="text-3xl font-bold text-white"><?= (int)$row["done_tasks"] ?></p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects Table -->
            <div class="card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-red-600/50 px-6 py-4">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-list mr-2 text-red-500"></i>Proyek Saya</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50 border-b border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">ID</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Nama Proyek</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Tanggal Mulai</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Tanggal Selesai</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php
                            $q = mysqli_query($connect, "SELECT id, nama_proyek, tanggal_mulai, 
                                                        COALESCE(tanggal_selesai,'-') AS tsel
                                                        FROM projects WHERE manager_id = $uid 
                                                        ORDER BY id DESC");
                            while ($p = mysqli_fetch_assoc($q)):
                            ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-300 font-mono"><?= $p['id'] ?></td>
                                <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($p['nama_proyek']) ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= $p['tanggal_mulai'] ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= $p['tsel'] ?></td>
                                <td class="px-6 py-4">
                                    <a href="tasks.php?project=<?= $p['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded transition btn-hover">
                                        <i class="fas fa-list-check"></i> <span>Tasks</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php else: // team_member ?>
            <!-- TM Dashboard -->
            <div class="mb-6 p-4 bg-red-600/20 border-l-4 border-red-600 rounded-lg">
                <p class="text-red-300"><i class="fas fa-check-circle mr-2"></i><strong>Team Member</strong> - Kelola tugas Anda</p>
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid) AS my_tasks,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='belum') AS pending,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='proses') AS progress,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='selesai') AS done");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="card-dark rounded-xl p-4 glow-red hover:glow-red">
                    <p class="text-gray-400 text-xs mb-1">Total Tugas</p>
                    <p class="text-2xl font-bold text-white"><?= (int)$row["my_tasks"] ?></p>
                </div>
                <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-xl p-4 glow-red hover:glow-red">
                    <p class="text-gray-100 text-xs mb-1">Belum</p>
                    <p class="text-2xl font-bold text-white"><?= (int)$row["pending"] ?></p>
                </div>
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl p-4 glow-red hover:glow-red">
                    <p class="text-gray-100 text-xs mb-1">Proses</p>
                    <p class="text-2xl font-bold text-white"><?= (int)$row["progress"] ?></p>
                </div>
                <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-xl p-4 glow-red hover:glow-red">
                    <p class="text-gray-100 text-xs mb-1">Selesai</p>
                    <p class="text-2xl font-bold text-white"><?= (int)$row["done"] ?></p>
                </div>
            </div>

            <!-- Tasks Table -->
            <div class="card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-red-600/50 px-6 py-4">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-list-check mr-2 text-red-500"></i>Tugas Saya</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50 border-b border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">ID</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Nama Tugas</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Proyek</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Status</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php
                            $stm = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.status, p.nama_proyek
                                                            FROM tasks t 
                                                            JOIN projects p ON p.id=t.project_id
                                                            WHERE t.assigned_to=? 
                                                            ORDER BY t.id DESC");
                            mysqli_stmt_bind_param($stm, "i", $uid);
                            mysqli_stmt_execute($stm);
                            $res = mysqli_stmt_get_result($stm);
                            while ($t = mysqli_fetch_assoc($res)):
                                $status_color = $t['status'] === 'selesai' ? 'green' : ($t['status'] === 'proses' ? 'blue' : 'yellow');
                            ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-300 font-mono"><?= $t['id'] ?></td>
                                <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= htmlspecialchars($t['nama_proyek']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-<?= $status_color ?>-600/20 text-<?= $status_color ?>-400 text-xs font-bold rounded-full border border-<?= $status_color ?>-500/50">
                                        <?= $t['status'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="tasks.php?edit_status=<?= $t['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded transition btn-hover">
                                        <i class="fas fa-edit"></i> <span>Update</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>