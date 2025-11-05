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

// CREATE tmahbah proyek bru
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "create") {
    $nama = trim($_POST["nama_proyek"] ?? "");
    $desk = trim($_POST["deskripsi"] ?? "");
    $mulai = $_POST["tanggal_mulai"] ?? "";
    $selesai = $_POST["tanggal_selesai"] ?? "";
    
    // Validasi
    if (empty($nama) || empty($mulai)) {
        header("Location: projects.php?error=" . urlencode("Nama proyek dan tanggal mulai wajib diisi"));
        exit;
    }
    
    // Validasi tanggal
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
    
    $stmt = mysqli_prepare($connect, "INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id)
                                      VALUES (?,?,?,?,?)");
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
    
    // Validasi
    if (empty($nama) || empty($mulai)) {
        header("Location: projects.php?error=" . urlencode("Nama proyek dan tanggal mulai wajib diisi"));
        exit;
    }
    
    // Validasi tanggal
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

// Get project untuk edit
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
                    <?php if ($role === "super_admin"): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="users.php">Kelola Users</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="projects.php">Projects</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tasks.php">Tasks</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        <i class="bi bi-person-circle"></i> <strong><?= $username ?></strong>
                        <span class="badge bg-light text-dark ms-2"><?= htmlspecialchars($role) ?></span>
                    </span>
                    <a href="logout.php" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <h2 class="mb-4"><i class="bi bi-folder-fill"></i> Projects</h2>

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
            <!-- Form Create/Edit -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-<?= $edit_project ? 'pencil' : 'plus-circle' ?>"></i> 
                            <?= $edit_project ? 'Edit Proyek' : 'Buat Proyek Baru' ?>
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <input type="hidden" name="action" value="<?= $edit_project ? 'edit' : 'create' ?>">
                            <?php if ($edit_project): ?>
                                <input type="hidden" name="project_id" value="<?= $edit_project['id'] ?>">
                            <?php endif; ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Nama Proyek <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_proyek" 
                                       value="<?= $edit_project ? htmlspecialchars($edit_project['nama_proyek']) : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3"><?= $edit_project ? htmlspecialchars($edit_project['deskripsi']) : '' ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="tanggal_mulai" 
                                       value="<?= $edit_project ? $edit_project['tanggal_mulai'] : '' ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tanggal Selesai</label>
                                <input type="date" class="form-control" name="tanggal_selesai" 
                                       value="<?= $edit_project ? $edit_project['tanggal_selesai'] : '' ?>">
                                <small class="text-muted">Opsional</small>
                            </div>
                            
                            <?php if ($role === "super_admin" && !$edit_project): ?>
                                <div class="mb-3">
                                    <label class="form-label">Manager (PM) <span class="text-danger">*</span></label>
                                    <select name="manager_id" class="form-select" required>
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
                            
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-save"></i> <?= $edit_project ? 'Update' : 'Simpan' ?>
                            </button>
                            
                            <?php if ($edit_project): ?>
                                <a href="projects.php" class="btn btn-secondary w-100 mt-2">
                                    <i class="bi bi-x-circle"></i> Batal
                                </a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Daftar Projects -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Proyek</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <?php if ($role === "super_admin"): ?>
                                            <th>Manager</th>
                                        <?php endif; ?>
                                        <th>Nama Proyek</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($role === "project_manager") {
                                        $stmt = mysqli_prepare($connect, "SELECT id, nama_proyek, tanggal_mulai, COALESCE(tanggal_selesai,'-') AS tsel 
                                                                          FROM projects WHERE manager_id=? ORDER BY id DESC");
                                        mysqli_stmt_bind_param($stmt, "i", $uid);
                                        mysqli_stmt_execute($stmt);
                                        $res = mysqli_stmt_get_result($stmt);
                                    } else {
                                        $res = mysqli_query($connect, "SELECT p.id, p.nama_proyek, p.tanggal_mulai, COALESCE(p.tanggal_selesai,'-') AS tsel, u.username AS manager
                                                                       FROM projects p 
                                                                       JOIN users u ON u.id=p.manager_id 
                                                                       ORDER BY p.id DESC");
                                    }
                                    
                                    while ($p = mysqli_fetch_assoc($res)):
                                    ?>
                                    <tr>
                                        <td><?= $p['id'] ?></td>
                                        <?php if ($role === "super_admin"): ?>
                                            <td><span class="badge bg-primary"><?= htmlspecialchars($p['manager']) ?></span></td>
                                        <?php endif; ?>
                                        <td><strong><?= htmlspecialchars($p['nama_proyek']) ?></strong></td>
                                        <td><?= $p['tanggal_mulai'] ?></td>
                                        <td><?= $p['tsel'] ?></td>
                                        <td>
                                            <a href="tasks.php?project=<?= $p['id'] ?>" class="btn btn-sm btn-info text-white">
                                                <i class="bi bi-list-check"></i> Tasks
                                            </a>
                                            <?php if (can_manage_project($connect, (int)$p["id"], $uid, $role)): ?>
                                                <a href="projects.php?edit=<?= $p['id'] ?>" class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <a href="projects.php?del=<?= $p['id'] ?>" 
                                                   class="btn btn-sm btn-danger" 
                                                   onclick="return confirm('Yakin hapus proyek ini? Semua tasks akan terhapus!')">
                                                    <i class="bi bi-trash"></i> Hapus
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>