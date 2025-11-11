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

if ($_SESSION["role"] !== "super_admin") {
    http_response_code(403);
    exit("Akses ditolak - Hanya Super Admin");
}

require "connect.php";

$username = htmlspecialchars($_SESSION["username"]);

// CREATE USER
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    $new_username = trim($_POST["username"] ?? "");
    $new_password = $_POST["password"] ?? "";
    $new_role = $_POST["role"] ?? "";
    $pm_id = $_POST["project_manager_id"] ?? "";
    
    if (empty($new_username) || empty($new_password) || empty($new_role)) {
        header("Location: users.php?error=" . urlencode("Semua field wajib diisi"));
        exit;
    }
    
    if (!in_array($new_role, ["project_manager", "team_member"], true)) {
        header("Location: users.php?error=" . urlencode("Role tidak valid"));
        exit;
    }
    
    $check = mysqli_prepare($connect, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $new_username);
    mysqli_stmt_execute($check);
    if (mysqli_fetch_row(mysqli_stmt_get_result($check))) {
        header("Location: users.php?error=" . urlencode("Username sudah digunakan"));
        exit;
    }
    
    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    
    if ($new_role === "team_member") {
        $pm_id_int = (int)$pm_id;
        if ($pm_id_int <= 0) {
            header("Location: users.php?error=" . urlencode("Team Member harus memiliki Project Manager"));
            exit;
        }
        
        $check_pm = mysqli_prepare($connect, "SELECT id FROM users WHERE id = ? AND role = 'project_manager'");
        mysqli_stmt_bind_param($check_pm, "i", $pm_id_int);
        mysqli_stmt_execute($check_pm);
        if (!mysqli_fetch_row(mysqli_stmt_get_result($check_pm))) {
            header("Location: users.php?error=" . urlencode("Project Manager tidak valid"));
            exit;
        }
        
        $stmt = mysqli_prepare($connect, "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sssi", $new_username, $hashed, $new_role, $pm_id_int);
    } else {
        $stmt = mysqli_prepare($connect, "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, NULL)");
        mysqli_stmt_bind_param($stmt, "sss", $new_username, $hashed, $new_role);
    }
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: users.php?success=" . urlencode("User berhasil ditambahkan"));
        exit;
    } else {
        header("Location: users.php?error=" . urlencode("Gagal menambahkan user"));
        exit;
    }
}

// DELETE USER
if (isset($_GET["del"])) {
    $del_id = (int)$_GET["del"];
    
    if ($del_id === (int)$_SESSION["user_id"]) {
        header("Location: users.php?error=" . urlencode("Tidak bisa menghapus akun sendiri"));
        exit;
    }
    
    $check = mysqli_prepare($connect, "SELECT role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $del_id);
    mysqli_stmt_execute($check);
    $res = mysqli_stmt_get_result($check);
    $user = mysqli_fetch_assoc($res);
    
    if (!$user) {
        header("Location: users.php?error=" . urlencode("User tidak ditemukan"));
        exit;
    }
    
    if ($user["role"] === "super_admin") {
        header("Location: users.php?error=" . urlencode("Tidak bisa menghapus Super Admin"));
        exit;
    }
    
    if ($user["role"] === "project_manager") {
        $check_proj = mysqli_prepare($connect, "SELECT COUNT(*) as cnt FROM projects WHERE manager_id = ?");
        mysqli_stmt_bind_param($check_proj, "i", $del_id);
        mysqli_stmt_execute($check_proj);
        $cnt = mysqli_fetch_assoc(mysqli_stmt_get_result($check_proj));
        if ($cnt["cnt"] > 0) {
            header("Location: users.php?error=" . urlencode("Tidak bisa hapus PM yang masih memiliki proyek aktif"));
            exit;
        }
    }
    
    $del_stmt = mysqli_prepare($connect, "DELETE FROM users WHERE id = ?");
    mysqli_stmt_bind_param($del_stmt, "i", $del_id);
    
    if (mysqli_stmt_execute($del_stmt)) {
        header("Location: users.php?success=" . urlencode("User berhasil dihapus"));
        exit;
    } else {
        header("Location: users.php?error=" . urlencode("Gagal menghapus user"));
        exit;
    }
}

// EDIT USER
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    $edit_id = (int)($_POST["user_id"] ?? 0);
    $new_pm_id = (int)($_POST["project_manager_id"] ?? 0);
    
    $check = mysqli_prepare($connect, "SELECT role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $edit_id);
    mysqli_stmt_execute($check);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
    
    if (!$user || $user["role"] !== "team_member") {
        header("Location: users.php?error=" . urlencode("Hanya bisa edit Team Member"));
        exit;
    }
    
    $check_pm = mysqli_prepare($connect, "SELECT id FROM users WHERE id = ? AND role = 'project_manager'");
    mysqli_stmt_bind_param($check_pm, "i", $new_pm_id);
    mysqli_stmt_execute($check_pm);
    if (!mysqli_fetch_row(mysqli_stmt_get_result($check_pm))) {
        header("Location: users.php?error=" . urlencode("Project Manager tidak valid"));
        exit;
    }
    
    $upd = mysqli_prepare($connect, "UPDATE users SET project_manager_id = ? WHERE id = ?");
    mysqli_stmt_bind_param($upd, "ii", $new_pm_id, $edit_id);
    
    if (mysqli_stmt_execute($upd)) {
        header("Location: users.php?success=" . urlencode("Project Manager berhasil diupdate"));
        exit;
    } else {
        header("Location: users.php?error=" . urlencode("Gagal update user"));
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Users - Manajemen Proyek</title>
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
                    <a href="users.php" class="text-red-500 hover:text-red-400 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium bg-gray-800/50">
                        <i class="fas fa-users-cog mr-1"></i>Kelola Users
                    </a>
                    <a href="projects.php" class="text-gray-300 hover:text-red-500 hover:bg-gray-800 px-3 py-2 rounded-lg transition font-medium">
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
                        <span class="px-2 py-1 bg-red-600/20 text-red-400 text-xs font-bold rounded border border-red-500/50">super_admin</span>
                    </div>
                    <a href="logout.php" class="text-gray-300 hover:text-red-500 px-3 py-2 rounded-lg transition border border-red-600/50">
                        <i class="fas fa-sign-out-alt mr-1"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-4xl font-bold text-white mb-8"><i class="fas fa-users text-red-500"></i> Kelola Users</h1>

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
            <!-- Form Tambah User -->
            <div class="card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-user-plus mr-2"></i>Tambah User Baru</h2>
                </div>
                <div class="p-6">
                    <form method="post">
                        <input type="hidden" name="action" value="create">
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-user text-red-500 mr-2"></i>Username <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="username" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-lock text-red-500 mr-2"></i>Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" name="password" required minlength="6" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                            <small class="text-gray-500">Min. 6 karakter</small>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-shield-alt text-red-500 mr-2"></i>Role <span class="text-red-500">*</span>
                            </label>
                            <select name="role" id="roleSelect" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                                <option value="">-- Pilih Role --</option>
                                <option value="project_manager">Project Manager</option>
                                <option value="team_member">Team Member</option>
                            </select>
                        </div>
                        
                        <div class="mb-6" id="pmSelectDiv" style="display:none;">
                            <label class="block text-sm font-semibold text-gray-300 mb-2">
                                <i class="fas fa-briefcase text-red-500 mr-2"></i>Project Manager <span class="text-red-500">*</span>
                            </label>
                            <select name="project_manager_id" id="pmSelect" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20 hover:border-gray-600">
                                <option value="">-- Pilih PM --</option>
                                <?php
                                $pm_list = mysqli_query($connect, "SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
                                while ($pm = mysqli_fetch_assoc($pm_list)):
                                ?>
                                    <option value="<?= $pm['id'] ?>"><?= htmlspecialchars($pm['username']) ?></option>
                                <?php endwhile; ?>
                            </select>
                            <small class="text-gray-500">Wajib untuk Team Member</small>
                        </div>
                        
                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg btn-hover transition">
                            <i class="fas fa-save mr-2"></i>Simpan User
                        </button>
                    </form>
                </div>
            </div>

            <!-- Daftar Users -->
            <div class="lg:col-span-2 card-dark rounded-xl overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 border-b border-red-600/50 px-6 py-4">
                    <h2 class="text-lg font-bold text-white"><i class="fas fa-list mr-2 text-red-500"></i>Daftar Users</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-800/50 border-b border-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">ID</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Username</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Role</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Project Manager</th>
                                <th class="px-6 py-3 text-left text-red-400 font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <?php
                            $users = mysqli_query($connect, "SELECT u.id, u.username, u.role, u.project_manager_id, pm.username AS pm_name
                                                            FROM users u
                                                            LEFT JOIN users pm ON pm.id = u.project_manager_id
                                                            ORDER BY 
                                                                CASE u.role
                                                                    WHEN 'super_admin' THEN 1
                                                                    WHEN 'project_manager' THEN 2
                                                                    WHEN 'team_member' THEN 3
                                                                END, u.username");
                            while ($u = mysqli_fetch_assoc($users)):
                                $badge_color = $u['role'] === 'super_admin' ? 'red' : ($u['role'] === 'project_manager' ? 'blue' : 'green');
                            ?>
                            <tr class="hover:bg-gray-800/50 transition">
                                <td class="px-6 py-4 text-gray-300 font-mono"><?= $u['id'] ?></td>
                                <td class="px-6 py-4 text-white font-semibold"><?= htmlspecialchars($u['username']) ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 bg-<?= $badge_color ?>-600/20 text-<?= $badge_color ?>-400 text-xs font-bold rounded-full border border-<?= $badge_color ?>-500/50">
                                        <?= htmlspecialchars($u['role']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($u['role'] === 'team_member'): ?>
                                        <?php if ($u['pm_name']): ?>
                                            <span class="px-2 py-1 bg-blue-600/20 text-blue-400 text-xs font-bold rounded border border-blue-500/50"><?= htmlspecialchars($u['pm_name']) ?></span>
                                            <button onclick="document.getElementById('editModal<?= $u['id'] ?>').classList.remove('hidden')" class="ml-2 px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php else: ?>
                                            <span class="text-red-400 text-sm">Belum ada</span>
                                            <button onclick="document.getElementById('editModal<?= $u['id'] ?>').classList.remove('hidden')" class="ml-2 px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-bold rounded transition">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-gray-500">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if ($u['role'] !== 'super_admin'): ?>
                                        <a href="users.php?del=<?= $u['id'] ?>" onclick="return confirm('Yakin hapus user <?= htmlspecialchars($u['username']) ?>?')" class="inline-flex items-center space-x-1 px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-sm font-bold rounded transition btn-hover">
                                            <i class="fas fa-trash"></i> <span>Hapus</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-600/20 text-gray-400 text-xs font-bold rounded border border-gray-500/50">Protected</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Modal Edit PM -->
                            <?php if ($u['role'] === 'team_member'): ?>
                            <div id="editModal<?= $u['id'] ?>" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
                                <div class="card-dark rounded-xl p-6 max-w-md w-full mx-4">
                                    <h3 class="text-xl font-bold text-white mb-4">Edit PM: <?= htmlspecialchars($u['username']) ?></h3>
                                    <form method="post">
                                        <input type="hidden" name="action" value="edit">
                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                        <div class="mb-4">
                                            <label class="block text-sm font-semibold text-gray-300 mb-2">Project Manager Baru</label>
                                            <select name="project_manager_id" required class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-red-500 focus:ring-2 focus:ring-red-500/20">
                                                <?php
                                                $pm_list2 = mysqli_query($connect, "SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
                                                while ($pm2 = mysqli_fetch_assoc($pm_list2)):
                                                ?>
                                                    <option value="<?= $pm2['id'] ?>" <?= $pm2['id'] == $u['project_manager_id'] ? 'selected' : '' ?>>
                                                        <?= htmlspecialchars($pm2['username']) ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="flex space-x-3">
                                            <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold rounded-lg transition">Simpan</button>
                                            <button type="button" onclick="document.getElementById('editModal<?= $u['id'] ?>').classList.add('hidden')" class="flex-1 px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-bold rounded-lg transition">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('roleSelect').addEventListener('change', function() {
            const pmDiv = document.getElementById('pmSelectDiv');
            const pmSelect = document.getElementById('pmSelect');
            
            if (this.value === 'team_member') {
                pmDiv.style.display = 'block';
                pmSelect.required = true;
            } else {
                pmDiv.style.display = 'none';
                pmSelect.required = false;
                pmSelect.value = '';
            }
        });
    </script>
</body>
</html>