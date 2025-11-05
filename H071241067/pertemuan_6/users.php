<?php
session_start();

// Security
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
}

if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
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
    
    // Validasi
    if (empty($new_username) || empty($new_password) || empty($new_role)) {
        header("Location: users.php?error=" . urlencode("Semua field wajib diisi"));
        exit;
    }
    
    if (!in_array($new_role, ["project_manager", "team_member"], true)) {
        header("Location: users.php?error=" . urlencode("Role tidak valid"));
        exit;
    }
    
    // Validasi username unique
    $check = mysqli_prepare($connect, "SELECT id FROM users WHERE username = ?");
    mysqli_stmt_bind_param($check, "s", $new_username);
    mysqli_stmt_execute($check);
    if (mysqli_fetch_row(mysqli_stmt_get_result($check))) {
        header("Location: users.php?error=" . urlencode("Username sudah digunakan"));
        exit;
    }
    
    // Hash password
    $hashed = password_hash($new_password, PASSWORD_BCRYPT);
    
    // Jika team_member, wajib ada PM
    if ($new_role === "team_member") {
        $pm_id_int = (int)$pm_id;
        if ($pm_id_int <= 0) {
            header("Location: users.php?error=" . urlencode("Team Member harus memiliki Project Manager"));
            exit;
        }
        
        // Validasi PM exist
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
        // Project Manager tidak perlu PM
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
    
    // Tidak bisa hapus diri sendiri
    if ($del_id === (int)$_SESSION["user_id"]) {
        header("Location: users.php?error=" . urlencode("Tidak bisa menghapus akun sendiri"));
        exit;
    }
    
    // Cek apakah user ada dan bukan super_admin
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
    
    // Cek apakah PM masih punya proyek
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
    
    // Hapus user
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

// EDIT USER (Update PM untuk TM)
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    $edit_id = (int)($_POST["user_id"] ?? 0);
    $new_pm_id = (int)($_POST["project_manager_id"] ?? 0);
    
    // Validasi user adalah team_member
    $check = mysqli_prepare($connect, "SELECT role FROM users WHERE id = ?");
    mysqli_stmt_bind_param($check, "i", $edit_id);
    mysqli_stmt_execute($check);
    $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
    
    if (!$user || $user["role"] !== "team_member") {
        header("Location: users.php?error=" . urlencode("Hanya bisa edit Team Member"));
        exit;
    }
    
    // Validasi PM
    $check_pm = mysqli_prepare($connect, "SELECT id FROM users WHERE id = ? AND role = 'project_manager'");
    mysqli_stmt_bind_param($check_pm, "i", $new_pm_id);
    mysqli_stmt_execute($check_pm);
    if (!mysqli_fetch_row(mysqli_stmt_get_result($check_pm))) {
        header("Location: users.php?error=" . urlencode("Project Manager tidak valid"));
        exit;
    }
    
    // Update
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="halaman_utama.php">🚀 Manajemen Proyek</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="halaman_utama.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="users.php">Kelola Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tasks.php">Tasks</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        <i class="bi bi-person-circle"></i> <strong><?= $username ?></strong>
                        <span class="badge bg-light text-dark ms-2">super_admin</span>
                    </span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4"><i class="bi bi-people-fill"></i> Kelola Users</h2>

        <!-- Alert Messages -->
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($_GET['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bi bi-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Form Tambah User -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-person-plus"></i> Tambah User Baru</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" id="formTambah">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="mb-3">
                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" name="password" required minlength="6">
                                <small class="text-muted">Min. 6 karakter</small>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select class="form-select" name="role" id="roleSelect" required>
                                    <option value="">-- Pilih Role --</option>
                                    <option value="project_manager">Project Manager</option>
                                    <option value="team_member">Team Member</option>
                                </select>
                            </div>
                            
                            <div class="mb-3" id="pmSelectDiv" style="display:none;">
                                <label class="form-label">Project Manager <span class="text-danger">*</span></label>
                                <select class="form-select" name="project_manager_id" id="pmSelect">
                                    <option value="">-- Pilih PM --</option>
                                    <?php
                                    $pm_list = mysqli_query($connect, "SELECT id, username FROM users WHERE role='project_manager' ORDER BY username");
                                    while ($pm = mysqli_fetch_assoc($pm_list)):
                                    ?>
                                        <option value="<?= $pm['id'] ?>"><?= htmlspecialchars($pm['username']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                                <small class="text-muted">Wajib untuk Team Member</small>
                            </div>
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save"></i> Simpan User
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Daftar Users -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Users</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Project Manager</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
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
                                        $badge_color = $u['role'] === 'super_admin' ? 'danger' : ($u['role'] === 'project_manager' ? 'primary' : 'success');
                                    ?>
                                    <tr>
                                        <td><?= $u['id'] ?></td>
                                        <td><strong><?= htmlspecialchars($u['username']) ?></strong></td>
                                        <td><span class="badge bg-<?= $badge_color ?>"><?= htmlspecialchars($u['role']) ?></span></td>
                                        <td>
                                            <?php if ($u['role'] === 'team_member'): ?>
                                                <?php if ($u['pm_name']): ?>
                                                    <span class="badge bg-info"><?= htmlspecialchars($u['pm_name']) ?></span>
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $u['id'] ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                <?php else: ?>
                                                    <span class="text-danger">Belum ada</span>
                                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= $u['id'] ?>">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($u['role'] !== 'super_admin'): ?>
                                                <a href="users.php?del=<?= $u['id'] ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Yakin hapus user <?= htmlspecialchars($u['username']) ?>?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">Protected</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit PM untuk Team Member -->
                                    <?php if ($u['role'] === 'team_member'): ?>
                                    <div class="modal fade" id="editModal<?= $u['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit PM: <?= htmlspecialchars($u['username']) ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="post">
                                                    <div class="modal-body">
                                                        <input type="hidden" name="action" value="edit">
                                                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                        <div class="mb-3">
                                                            <label class="form-label">Project Manager Baru</label>
                                                            <select class="form-select" name="project_manager_id" required>
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
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                                    </div>
                                                </form>
                                            </div>
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Show/hide PM select based on role
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