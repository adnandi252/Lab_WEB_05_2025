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

if (!in_array($_SESSION["role"], ["project_manager","super_admin"], true)) {
    http_response_code(403);
    exit("Akses ditolak");
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

// CREATE
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    $nama = trim($_POST["nama_proyek"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $mulai = $_POST["tanggal_mulai"] ?? "";
    $selesai = $_POST["tanggal_selesai"] ?? "";
    
    if (empty($nama) || empty($mulai)) {
        header("Location: projects.php?error=" . urlencode("Nama proyek dan tanggal mulai wajib diisi"));
        exit;
    }
    
    if (!empty($selesai) && $selesai < $mulai) {
        header("Location: projects.php?error=" . urlencode("Tanggal selesai tidak boleh lebih awal dari tanggal mulai"));
        exit;
    }
    
    $mulai_val = $mulai ?: NULL;
    $selesai_val = $selesai ?: NULL;
    
    if ($role === "project_manager") {
        $manager_id = $uid;
    } else {
        $manager_id = (int)($_POST["manager_id"] ?? 0);
        $chk = mysqli_prepare($connect, "SELECT 1 FROM users WHERE id=? AND role='project_manager'");
        mysqli_stmt_bind_param($chk, "i", $manager_id);
        mysqli_stmt_execute($chk);
        $ok = mysqli_fetch_row(mysqli_stmt_get_result($chk));
        if (!$ok) {
            header("Location: projects.php?error=" . urlencode("Manager ID bukan PM yang valid"));
            exit;
        }
    }
    
    $stmt = mysqli_prepare($connect, "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $desk, $mulai_val, $selesai_val, $manager_id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: projects.php?success=" . urlencode("Proyek berhasil ditambahkan"));
        exit;
    } else {
        header("Location: projects.php?error=" . urlencode("Gagal menambahkan proyek"));
        exit;
    }
}

// UPDATE/EDIT
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    $pid = (int)($_POST["project_id"] ?? 0);
    
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $nama = trim($_POST["nama_proyek"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $mulai = $_POST["tanggal_mulai"] ?? "";
    $selesai = $_POST["tanggal_selesai"] ?? "";
    
    if (empty($nama) || empty($mulai)) {
        header("Location: projects.php?error=" . urlencode("Nama proyek dan tanggal mulai wajib diisi"));
        exit;
    }
    
    if (!empty($selesai) && $selesai < $mulai) {
        header("Location: projects.php?error=" . urlencode("Tanggal selesai tidak boleh lebih awal dari tanggal mulai"));
        exit;
    }
    
    $mulai_val = $mulai ?: NULL;
    $selesai_val = $selesai ?: NULL;
    
    $stmt = mysqli_prepare($connect, "UPDATE projects SET nama_proyek=?, deskripsi=?, tanggal_mulai=?, tanggal_selesai=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $desk, $mulai_val, $selesai_val, $pid);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: projects.php?success=" . urlencode("Proyek berhasil diupdate"));
        exit;
    } else {
        header("Location: projects.php?error=" . urlencode("Gagal mengupdate proyek"));
        exit;
    }
}

// DELETE
if (isset($_GET["del"])) {
    $pid = (int)$_GET["del"];
    if (!can_manage_project($connect, $pid, $uid, $role)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $d = mysqli_prepare($connect, "DELETE FROM projects WHERE id=?");
    mysqli_stmt_bind_param($d, "i", $pid);
    
    if (mysqli_stmt_execute($d)) {
        header("Location: projects.php?success=" . urlencode("Proyek berhasil dihapus"));
        exit;
    } else {
        header("Location: projects.php?error=" . urlencode("Gagal menghapus proyek"));
        exit;
    }
}

$edit_project = null;
if (isset($_GET["edit"])) {
    $edit_id = (int)$_GET["edit"];
    if (can_manage_project($connect, $edit_id, $uid, $role)) {
        $stmt = mysqli_prepare($connect, "SELECT * FROM projects WHERE id=?");
        mysqli_stmt_bind_param($stmt, "i", $edit_id);
        mysqli_stmt_execute($stmt);
        $edit_project = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Manajemen Proyek</title>
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
    <!-- Navbar -->
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
                    <a href="projects.php" class="text-red-500 hover:text-red-400 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium bg-gray-800/50">
                        <i class="fas fa-folder mr-1"></i>Projects
                    </a>
                    <a href="tasks.php" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
                        <i class="fas fa-tasks mr-1"></i>Tasks
                    </a>
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
        <h1 class="text-4xl font-bold text-white mb-8"><i class="fas fa-folder text-red-500"></i> Projects</h1>

        <!-- Alerts -->
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Form Create/Edit -->
            <div class="card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                    <h2 class="text-lg font-bold text-white">
                        <i class="fas fa-<?= $edit_project ? 'edit' : 'plus-circle' ?> mr-2"></i>
                        <?= $edit_project ? 'Edit Proyek' : 'Buat Proyek Baru' ?>
                    </h2>
                </div>
                <div class="p-6">
                    <form method="post">
                        <input type="hidden" name="action" value="<?= $edit_project ? 'edit' : 'create' ?>">
                        <?php if ($edit_project): ?>
                            <input type="hidden" name="project_id" value="<?= $edit_project['id'] ?>">
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-briefcase text-red-500 mr-2"></i>Nama Proyek <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_proyek" value="<?= $edit_project ? htmlspecialchars($edit_project['nama_proyek']) : '' ?>" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-align-left text-red-500 mr-2"></i>Deskripsi
                            </label>
                            <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600"><?= $edit_project ? htmlspecialchars($edit_project['deskripsi']) : '' ?></textarea>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-calendar text-red-500 mr-2"></i>Tanggal Mulai <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_mulai" value="<?= $edit_project ? $edit_project['tanggal_mulai'] : '' ?>" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-calendar-check text-red-500 mr-2"></i>Tanggal Selesai
                            </label>
                            <input type="date" name="tanggal_selesai" value="<?= $edit_project ? $edit_project['tanggal_selesai'] : '' ?>" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                            <small class="text-gray-500">Opsional</small>
                        </div>
                        
                        <?php if ($role === "super_admin" && !$edit_project): ?>
                            <div class="mb-4">
                                <label class="block text-sm font-semibold text-gray-300 mb-2">
                                    <i class="fas fa-user-tie text-red-500 mr-2"></i>Manager (PM) <span class="text-red-500">*</span>
                                </label>
                                <select name="manager_id" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                                    <option value="">-- Pilih PM --</option>
                                    <?php
                                    $pm = mysqli_query($connect, "SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
                                    while ($r = mysqli_fetch_assoc($pm)):
                                    ?>
                                        <option value="<?= $r['id'] ?>"><?= htmlspecialchars($r['username']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg btn-hover transition mb-2">
                            <i class="fas fa-save mr-2"></i><?= $edit_project ? 'Update' : 'Simpan' ?>
                        </button>
                        
                        <?php if ($edit_project): ?>
                            <a href="projects.php" class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition text-center">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Daftar Projects -->
            <div class="lg:col-span-2 card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-red-600/50 px-6 py-4">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-list mr-2 text-red-500"></i>Daftar Proyek</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50 border-b border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">ID</th>
                                <?php if ($role === "super_admin"): ?>
                                    <th class="px-6 py-3 text-left text-red-400 font-semibold">Manager</th>
                                <?php endif; ?>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Nama Proyek</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Mulai</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Selesai</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php
                            if ($role === "project_manager") {
                                $stmt = mysqli_prepare($connect, "SELECT id, nama_proyek, tanggal_mulai, COALESCE(tanggal_selesai,'-') AS tsel FROM projects WHERE manager_id=? ORDER BY id DESC");
                                mysqli_stmt_bind_param($stmt, "i", $uid);
                                mysqli_stmt_execute($stmt);
                                $res = mysqli_stmt_get_result($stmt);
                            } else {
                                $res = mysqli_query($connect, "SELECT p.id, p.nama_proyek, p.tanggal_mulai, COALESCE(p.tanggal_selesai,'-') AS tsel, u.username AS manager FROM projects p JOIN users u ON u.id=p.manager_id ORDER BY p.id DESC");
                            }
                            
                            while ($p = mysqli_fetch_assoc($res)):
                            ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-300 font-mono"><?= $p['id'] ?></td>
                                <?php if ($role === "super_admin"): ?>
                                    <td class="px-6 py-4"><span class="px-2 py-1 bg-blue-600/20 text-blue-400 text-xs font-bold rounded border border-blue-500/50"><?= htmlspecialchars($p['manager']) ?></span></td>
                                <?php endif; ?>
                                <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($p['nama_proyek']) ?></td>
                                <td class="px-6 py-4 text-gray-400 text-sm"><?= $p['tanggal_mulai'] ?></td>
                                <td class="px-6 py-4 text-gray-400 text-sm"><?= $p['tsel'] ?></td>
                                <td class="px-6 py-4 space-y-1">
                                    <a href="tasks.php?project=<?= $p['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded transition btn-hover">
                                        <i class="fas fa-list-check"></i> <span>Tasks</span>
                                    </a>
                                    <?php if (can_manage_project($connect, (int)$p["id"], $uid, $role)): ?>
                                        <a href="projects.php?edit=<?= $p['id'] ?>" class="inline-flex items-center space-x-1 px-3 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded transition btn-hover">
                                            <i class="fas fa-edit"></i> <span>Edit</span>
                                        </a>
                                        <a href="projects.php?del=<?= $p['id'] ?>" onclick="return confirm('Hapus proyek ini? Semua tasks akan terhapus!')" class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded transition btn-hover">
                                            <i class="fas fa-trash"></i> <span>Hapus</span>
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
</body>
</html>