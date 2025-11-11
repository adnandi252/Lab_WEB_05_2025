<?php
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "connect.php";

$role = $_SESSION["role"];
$uid  = (int)$_SESSION["user_id"];
$username = htmlspecialchars($_SESSION["username"]);

function can_manage_project(mysqli $db, int $pid, int $uid, string $role): bool {
    if ($role === "super_admin") return true;
    if ($role !== "project_manager") return false;
    $s = mysqli_prepare($db, "SELECT 1 FROM projects WHERE id=? AND manager_id=?");
    mysqli_stmt_bind_param($s, "ii", $pid, $uid);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    return (bool)mysqli_fetch_row($r);
}

function can_update_task_status(mysqli $db, int $tid, int $uid): bool {
    $s = mysqli_prepare($db, "SELECT 1 FROM tasks WHERE id=? AND assigned_to=?");
    mysqli_stmt_bind_param($s, "ii", $tid, $uid);
    mysqli_stmt_execute($s);
    $r = mysqli_stmt_get_result($s);
    return (bool)mysqli_fetch_row($r);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    if (!in_array($role, ["project_manager","super_admin"], true)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $pid = (int)($_POST["project_id"] ?? 0);
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $nama = trim($_POST["nama_tugas"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $status = $_POST["status"] ?? "belum";
    $assignee_raw = $_POST["assigned_to"] ?? "";
    
    if (empty($nama)) {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Nama tugas wajib diisi"));
        exit;
    }
    
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        $status = 'belum';
    }
    
    $assignee = ($assignee_raw === "") ? NULL : (int)$assignee_raw;
    
    if ($assignee !== NULL) {
        $check = mysqli_prepare($connect, "SELECT role, project_manager_id FROM users WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $assignee);
        mysqli_stmt_execute($check);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        
        if (!$user || $user['role'] !== 'team_member') {
            header("Location: tasks.php?project={$pid}&error=" . urlencode("Hanya bisa assign ke Team Member"));
            exit;
        }
        
        if ($role === 'project_manager') {
            if ((int)$user['project_manager_id'] !== $uid) {
                header("Location: tasks.php?project={$pid}&error=" . urlencode("Team Member tidak berada di bawah PM Anda"));
                exit;
            }
        }
    }
    
    $stmt = mysqli_prepare($connect, "INSERT INTO tasks (nama_tugas, deskripsi, status, project_id, assigned_to) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "sssii", $nama, $desk, $status, $pid, $assignee);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil ditambahkan"));
        exit;
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal menambahkan task"));
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    if (!in_array($role, ["project_manager","super_admin"], true)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $tid = (int)($_POST["task_id"] ?? 0);
    
    $q = mysqli_prepare($connect, "SELECT project_id FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($q, "i", $tid);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    $task = mysqli_fetch_assoc($res);
    
    if (!$task) {
        header("Location: tasks.php?error=" . urlencode("Task tidak ditemukan"));
        exit;
    }
    
    $pid = (int)$task["project_id"];
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $nama = trim($_POST["nama_tugas"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $status = $_POST["status"] ?? "belum";
    $assignee_raw = $_POST["assigned_to"] ?? "";
    
    if (empty($nama)) {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Nama tugas wajib diisi"));
        exit;
    }
    
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        $status = 'belum';
    }
    
    $assignee = ($assignee_raw === "") ? NULL : (int)$assignee_raw;
    
    if ($assignee !== NULL) {
        $check = mysqli_prepare($connect, "SELECT role, project_manager_id FROM users WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $assignee);
        mysqli_stmt_execute($check);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        
        if (!$user || $user['role'] !== 'team_member') {
            header("Location: tasks.php?project={$pid}&error=" . urlencode("Hanya bisa assign ke Team Member"));
            exit;
        }
        
        if ($role === 'project_manager') {
            if ((int)$user['project_manager_id'] !== $uid) {
                header("Location: tasks.php?project={$pid}&error=" . urlencode("Team Member tidak berada di bawah PM Anda"));
                exit;
            }
        }
    }
    
    $stmt = mysqli_prepare($connect, "UPDATE tasks SET nama_tugas=?, deskripsi=?, status=?, assigned_to=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssii", $nama, $desk, $status, $assignee, $tid);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil diupdate"));
        exit;
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal mengupdate task"));
        exit;
    }
}

if (isset($_GET["del"])) {
    $tid = (int)$_GET["del"];
    
    $q = mysqli_prepare($connect, "SELECT project_id FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($q, "i", $tid);
    mysqli_stmt_execute($q);
    $res = mysqli_stmt_get_result($q);
    $row = mysqli_fetch_assoc($res);
    
    if (!$row) {
        header("Location: tasks.php?error=" . urlencode("Task tidak ditemukan"));
        exit;
    }
    
    $pid = (int)$row["project_id"];
    
    if (!in_array($role, ["project_manager","super_admin"], true)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $d = mysqli_prepare($connect, "DELETE FROM tasks WHERE id=?");
    mysqli_stmt_bind_param($d, "i", $tid);
    
    if (mysqli_stmt_execute($d)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil dihapus"));
        exit;
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal menghapus task"));
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_status_member") {
    if ($role !== "team_member") {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $tid = (int)($_POST["task_id"] ?? 0);
    $status = $_POST["status"] ?? "belum";
    
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        header("Location: tasks.php?view=my&error=" . urlencode("Status tidak valid"));
        exit;
    }
    
    if (!can_update_task_status($connect, $tid, $uid)) {
        http_response_code(403);
        exit("Task bukan milikmu");
    }
    
    $u = mysqli_prepare($connect, "UPDATE tasks SET status=? WHERE id=?");
    mysqli_stmt_bind_param($u, "si", $status, $tid);
    
    if (mysqli_stmt_execute($u)) {
        header("Location: tasks.php?view=my&success=" . urlencode("Status berhasil diupdate"));
        exit;
    } else {
        die("Execute failed: " . mysqli_stmt_error($u));
    }
}

$view = $_GET["view"] ?? "";
$project_id = isset($_GET["project"]) ? (int)$_GET["project"] : 0;
$edit_status_id = isset($_GET["edit_status"]) ? (int)$_GET["edit_status"] : 0;
$edit_task_id = isset($_GET["edit"]) ? (int)$_GET["edit"] : 0;

$edit_task = null;
if ($edit_task_id > 0 && in_array($role, ["project_manager","super_admin"], true)) {
    $q = mysqli_prepare($connect, "SELECT t.*, p.id as project_id FROM tasks t JOIN projects p ON p.id=t.project_id WHERE t.id=?");
    mysqli_stmt_bind_param($q, "i", $edit_task_id);
    mysqli_stmt_execute($q);
    $edit_task = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
    
    if ($edit_task && !can_manage_project($connect, (int)$edit_task['project_id'], $uid, $role)) {
        $edit_task = null;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { transition: all 0.3s ease; }
        .btn-hover { transform: translateY(-2px); box-shadow: 0 8px 16px rgba(139, 0, 0, 0.3); }
        .card-dark { background: linear-gradient(135deg, #1F2937 0%, #111827 100%); border: 1px solid rgba(139, 0, 0, 0.3); }
        .glow-red { box-shadow: 0 0 20px rgba(220, 38, 38, 0.3); }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-950 via-black to-gray-900">
    <nav class="bg-gradient-to-r from-gray-900 via-black to-gray-900 border-b-2 border-red-600 shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <a href="halaman_utama.php" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-red-800 rounded-lg flex items-center justify-center glow-red">
                        <i class="fas fa-rocket text-white text-lg"></i>
                    </div>
                    <span class="text-white font-bold text-xl hidden sm:inline">Manajemen Proyek</span>
                </a>

                <button class="md:hidden text-white" onclick="document.getElementById('navbar-menu').classList.toggle('hidden')">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <div id="navbar-menu" class="hidden md:flex md:items-center space-x-1">
                    <a href="halaman_utama.php" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
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
                        <a href="tasks.php" class="text-red-500 hover:text-red-400 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium bg-gray-800/50">
                            <i class="fas fa-tasks mr-1"></i>Tasks
                        </a>
                    <?php else: ?>
                        <a href="tasks.php?view=my" class="text-red-500 hover:text-red-400 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium bg-gray-800/50">
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
                    <a href="logout.php" class="text-gray-300 hover:text-red-500 px-3 py-2 rounded-lg transition border border-red-600/50">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-white mb-8"><i class="fas fa-list-check text-red-500"></i> Tasks</h1>

        <?php if (isset($_GET['error'])): ?>
            <div class="mb-6 p-4 bg-red-600/20 border-l-4 border-red-600 rounded-lg flex items-center space-x-3">
                <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                <span class="text-red-300"><?= htmlspecialchars($_GET['error']) ?></span>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-red-500 hover:text-red-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="mb-6 p-4 bg-green-600/20 border-l-4 border-green-600 rounded-lg flex items-center space-x-3">
                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                <span class="text-green-300"><?= htmlspecialchars($_GET['success']) ?></span>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-500 hover:text-green-400">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>

        <?php if ($role === "team_member" && $view === "my"): ?>
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
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Deskripsi</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Status</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php
                            $stmt = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.deskripsi, t.status, p.nama_proyek FROM tasks t JOIN projects p ON p.id=t.project_id WHERE t.assigned_to=? ORDER BY t.id DESC");
                            mysqli_stmt_bind_param($stmt, "i", $uid);
                            mysqli_stmt_execute($stmt);
                            $res = mysqli_stmt_get_result($stmt);
                            
                            while ($t = mysqli_fetch_assoc($res)):
                                $status_color = $t['status'] === 'selesai' ? 'green' : ($t['status'] === 'proses' ? 'blue' : 'yellow');
                            ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-300 font-mono"><?= $t['id'] ?></td>
                                <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                <td class="px-6 py-4 text-gray-400"><?= htmlspecialchars($t['nama_proyek']) ?></td>
                                <td class="px-6 py-4 text-gray-400 text-sm"><?= htmlspecialchars(substr($t['deskripsi'] ?? '-', 0, 40)) ?>...</td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 bg-<?= $status_color ?>-600/20 text-<?= $status_color ?>-400 text-xs font-bold rounded-full border border-<?= $status_color ?>-500/50">
                                        <?= $t['status'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="tasks.php?edit_status=<?= $t['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded transition btn-hover">
                                        <i class="fas fa-edit"></i> <span>Update</span>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php elseif ($role === "team_member" && $edit_status_id > 0): ?>
            <?php if (!can_update_task_status($connect, $edit_status_id, $uid)): ?>
                <div class="p-4 bg-red-600/20 border-l-4 border-red-600 rounded-lg text-red-300 mb-6">
                    <i class="fas fa-lock mr-2"></i>Akses ditolak - Task ini bukan milik Anda
                </div>
                <a href="tasks.php?view=my" class="inline-flex items-center space-x-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg transition">
                    <i class="fas fa-arrow-left"></i>Kembali
                </a>
            <?php else: ?>
                <?php
                $g = mysqli_prepare($connect, "SELECT t.nama_tugas, t.deskripsi, t.status, p.nama_proyek FROM tasks t JOIN projects p ON p.id=t.project_id WHERE t.id=?");
                mysqli_stmt_bind_param($g, "i", $edit_status_id);
                mysqli_stmt_execute($g);
                $rs = mysqli_stmt_get_result($g);
                $tt = mysqli_fetch_assoc($rs);
                
                if (!$tt):
                ?>
                    <div class="p-4 bg-red-600/20 border-l-4 border-red-600 rounded-lg text-red-300 mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>Task tidak ditemukan
                    </div>
                    <a href="tasks.php?view=my" class="inline-flex items-center space-x-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg transition">
                        <i class="fas fa-arrow-left"></i>Kembali
                    </a>
                <?php else: ?>
                    <div class="max-w-2xl">
                        <div class="card-dark rounded-xl overflow-hidden mb-6">
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                                <h2 class="text-lg font-bold text-white"><i class="fas fa-edit mr-2"></i>Update Status Task</h2>
                            </div>
                            <div class="p-6">
                                <div class="mb-6 p-4 bg-gray-800 border border-red-600/30 rounded-lg">
                                    <p class="text-gray-400 text-sm mb-2"><strong class="text-red-400">Nama Task:</strong></p>
                                    <p class="text-white font-semibold mb-3"><?= htmlspecialchars($tt["nama_tugas"]) ?></p>
                                    
                                    <p class="text-gray-400 text-sm mb-2"><strong class="text-red-400">Proyek:</strong></p>
                                    <p class="text-white font-semibold mb-3"><?= htmlspecialchars($tt["nama_proyek"]) ?></p>
                                    
                                    <p class="text-gray-400 text-sm mb-2"><strong class="text-red-400">Deskripsi:</strong></p>
                                    <p class="text-gray-300"><?= htmlspecialchars($tt["deskripsi"] ?: '-') ?></p>
                                </div>
                                
                                <form method="post" class="space-y-4">
                                    <input type="hidden" name="action" value="update_status_member">
                                    <input type="hidden" name="task_id" value="<?= $edit_status_id ?>">
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2">Status Saat Ini:</label>
                                        <?php $current_status_color = $tt['status'] === 'selesai' ? 'green' : ($tt['status'] === 'proses' ? 'blue' : 'yellow'); ?>
                                        <span class="inline-block px-3 py-1 bg-<?= $current_status_color ?>-600/20 text-<?= $current_status_color ?>-400 text-xs font-bold rounded-full border border-<?= $current_status_color ?>-500/50">
                                            <?= $tt['status'] ?>
                                        </span>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2">
                                            <i class="fas fa-tasks text-red-500 mr-2"></i>Ubah Status Ke: <span class="text-red-500">*</span>
                                        </label>
                                        <select name="status" required class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600 text-lg">
                                            <option value="belum" <?= $tt['status']==='belum' ? 'selected' : '' ?>>📍 Belum Dikerjakan</option>
                                            <option value="proses" <?= $tt['status']==='proses' ? 'selected' : '' ?>>🟡 Sedang Proses</option>
                                            <option value="selesai" <?= $tt['status']==='selesai' ? 'selected' : '' ?>>✓ Selesai</option>
                                        </select>
                                    </div>
                                    
                                    <div class="flex gap-3 pt-4">
                                        <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg btn-hover transition">
                                            <i class="fas fa-save mr-2"></i>Simpan Status
                                        </button>
                                        <a href="tasks.php?view=my" class="flex-1 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition text-center">
                                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

        <?php elseif ($project_id > 0 && ($role === "project_manager" || $role === "super_admin")): ?>
            <?php if (!can_manage_project($connect, $project_id, $uid, $role) && $role !== "super_admin"): ?>
                <div class="p-4 bg-red-600/20 border-l-4 border-red-600 rounded-lg text-red-300 mb-6">
                    Akses ditolak
                </div>
            <?php else: ?>
                <?php
                $p = mysqli_prepare($connect, "SELECT nama_proyek FROM projects WHERE id=?");
                mysqli_stmt_bind_param($p, "i", $project_id);
                mysqli_stmt_execute($p);
                $r = mysqli_stmt_get_result($p);
                $proj = mysqli_fetch_assoc($r);
                ?>
                <div class="mb-6">
                    <a href="projects.php" class="inline-flex items-center space-x-2 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-bold rounded-lg transition">
                        <i class="fas fa-arrow-left"></i>Kembali ke Projects
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="col-span-1">
                        <div class="card-dark rounded-xl overflow-hidden">
                            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                                <h5 class="text-lg font-bold text-white">
                                    <i class="fas fa-<?= $edit_task ? 'pencil-alt' : 'plus-circle' ?> mr-2"></i> 
                                    <?= $edit_task ? 'Edit Task' : 'Buat Task Baru' ?>
                                </h5>
                            </div>
                            <div class="p-6">
                                <div class="mb-4 p-3 bg-gray-800 border border-red-600/30 rounded-lg text-gray-300 font-medium">
                                    <strong class="text-red-400">Proyek:</strong> <?= htmlspecialchars($proj["nama_proyek"] ?? "Unknown") ?>
                                </div>
                                
                                <form method="post" class="space-y-4">
                                    <input type="hidden" name="action" value="<?= $edit_task ? 'edit' : 'create' ?>">
                                    <input type="hidden" name="project_id" value="<?= $project_id ?>">
                                    <?php if ($edit_task): ?>
                                        <input type="hidden" name="task_id" value="<?= $edit_task['id'] ?>">
                                    <?php endif; ?>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2">Nama Task <span class="text-red-500">*</span></label>
                                        <input type="text" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600" name="nama_tugas" value="<?= $edit_task ? htmlspecialchars($edit_task['nama_tugas']) : '' ?>" required>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2">Deskripsi</label>
                                        <textarea class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600" name="deskripsi" rows="3"><?= $edit_task ? htmlspecialchars($edit_task['deskripsi']) : '' ?></textarea>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2">Status <span class="text-red-500">*</span></label>
                                        <select name="status" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600" required>
                                            <option value="belum" <?= ($edit_task && $edit_task['status']==='belum') ? 'selected' : '' ?>>Belum</option>
                                            <option value="proses" <?= ($edit_task && $edit_task['status']==='proses') ? 'selected' : '' ?>>Proses</option>
                                            <option value="selesai" <?= ($edit_task && $edit_task['status']==='selesai') ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-300 mb-2">Assign ke</label>
                                        <select name="assigned_to" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                                            <option value="">- Tidak ada -</option>
                                            <?php
                                            if ($role === 'project_manager') {
                                                $us = mysqli_prepare($connect, "SELECT id, username FROM users WHERE role='team_member' AND project_manager_id=? ORDER BY username");
                                                mysqli_stmt_bind_param($us, "i", $uid);
                                                mysqli_stmt_execute($us);
                                                $us_res = mysqli_stmt_get_result($us);
                                            } else {
                                                $us_res = mysqli_query($connect, "SELECT id, username FROM users WHERE role='team_member' ORDER BY username");
                                            }
                                            
                                            while ($u = mysqli_fetch_assoc($us_res)):
                                            ?>
                                                <option value="<?= $u['id'] ?>" <?= ($edit_task && $edit_task['assigned_to']==$u['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($u['username']) ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                        <small class="text-gray-500">Opsional - hanya Team Member</small>
                                    </div>
                                    
                                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg btn-hover transition">
                                        <i class="fas fa-save mr-2"></i> <?= $edit_task ? 'Update Task' : 'Simpan Task' ?>
                                    </button>
                                    
                                    <?php if ($edit_task): ?>
                                        <a href="tasks.php?project=<?= $project_id ?>" class="w-full flex justify-center items-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition mt-2">
                                            <i class="fas fa-times-circle mr-2"></i> Batal Edit
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <div class="card-dark rounded-xl overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-red-600/50 px-6 py-4">
                                <h5 class="text-lg font-bold text-white"><i class="fas fa-list-ul mr-2 text-red-500"></i>Daftar Tasks Proyek</h5>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead class="bg-gray-800/50 border-b border-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-red-400 font-semibold">ID</th>
                                            <th class="px-6 py-3 text-left text-red-400 font-semibold">Nama Task</th>
                                            <th class="px-6 py-3 text-left text-red-400 font-semibold">Status</th>
                                            <th class="px-6 py-3 text-left text-red-400 font-semibold">Assignee</th>
                                            <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        <?php
                                        $stmt = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.status, u.username AS assignee FROM tasks t LEFT JOIN users u ON u.id=t.assigned_to WHERE t.project_id=? ORDER BY t.id DESC");
                                        mysqli_stmt_bind_param($stmt, "i", $project_id);
                                        mysqli_stmt_execute($stmt);
                                        $res = mysqli_stmt_get_result($stmt);
                                        
                                        while ($t = mysqli_fetch_assoc($res)):
                                            $status_color = $t['status'] === 'selesai' ? 'green' : ($t['status'] === 'proses' ? 'blue' : 'yellow');
                                        ?>
                                        <tr class="hover:bg-gray-800/50 transition">
                                            <td class="px-6 py-4 text-gray-300 font-mono"><?= $t['id'] ?></td>
                                            <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($t['nama_tugas']) ?></td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 bg-<?= $status_color ?>-600/20 text-<?= $status_color ?>-400 text-xs font-bold rounded-full border border-<?= $status_color ?>-500/50">
                                                    <?= $t['status'] ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <?php if ($t['assignee']): ?>
                                                    <span class="px-2 py-1 bg-gray-600 text-gray-300 text-xs font-bold rounded-full border border-gray-500/50"><?= htmlspecialchars($t['assignee']) ?></span>
                                                <?php else: ?>
                                                    <span class="text-gray-500 italic">- Unassigned -</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 min-w-[150px]">
                                                <a href="tasks.php?project=<?= $project_id ?>&edit=<?= $t['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded transition btn-hover">
                                                    <i class="fas fa-pencil-alt"></i> <span>Edit</span>
                                                </a>
                                                <a href="tasks.php?del=<?= $t['id'] ?>" 
                                                   class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded transition btn-hover ml-1" 
                                                   onclick="return confirm('Yakin hapus task ini?')">
                                                    <i class="fas fa-trash"></i> <span>Hapus</span>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($role === "team_member" && !$view && !$edit_status_id): ?>
            <div class="p-4 bg-gray-800 border-l-4 border-red-600 rounded-lg text-gray-300 mb-6">
                <i class="fas fa-info-circle mr-2 text-red-500"></i>
                Buka <a href="tasks.php?view=my" class="text-red-400 hover:text-red-300 font-semibold underline">My Tasks</a> untuk melihat tugasmu.
            </div>
        <?php endif; ?>

        <?php if (($role === "project_manager" || $role === "super_admin") && !$project_id && !$edit_task_id): ?>
            <div class="p-4 bg-gray-800 border-l-4 border-red-600 rounded-lg text-gray-300 mb-6">
                <i class="fas fa-info-circle mr-2 text-red-500"></i>
                Pilih proyek dari halaman <a href="projects.php" class="text-red-400 hover:text-red-300 font-semibold underline">Projects</a> untuk mengelola tasks.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>