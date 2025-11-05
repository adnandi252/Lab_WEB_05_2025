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

// PM/super_admin: CREATE TASK
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
    
    // Validasi
    if (empty($nama)) {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Nama tugas wajib diisi"));
        exit;
    }
    
    // Validasi status
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        $status = 'belum';
    }
    
    $assignee = ($assignee_raw === "") ? NULL : (int)$assignee_raw;
    
    // Validasi assignee jika ada
    if ($assignee !== NULL) {
        $check = mysqli_prepare($connect, "SELECT role, project_manager_id FROM users WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $assignee);
        mysqli_stmt_execute($check);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        
        if (!$user || $user['role'] !== 'team_member') {
            header("Location: tasks.php?project={$pid}&error=" . urlencode("Hanya bisa assign ke Team Member"));
            exit;
        }
        
        // Validasi TM berada di bawah PM yang sama (jika PM yang assign)
        if ($role === 'project_manager') {
            if ((int)$user['project_manager_id'] !== $uid) {
                header("Location: tasks.php?project={$pid}&error=" . urlencode("Team Member tidak berada di bawah PM Anda"));
                exit;
            }
        }
    }
    
    $stmt = mysqli_prepare($connect, "INSERT INTO tasks (nama_tugas, deskripsi, status, project_id, assigned_to)
                                      VALUES (?,?,?,?,?)");
    mysqli_stmt_bind_param($stmt, "sssii", $nama, $desk, $status, $pid, $assignee);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: tasks.php?project={$pid}&success=" . urlencode("Task berhasil ditambahkan"));
        exit;
    } else {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Gagal menambahkan task"));
        exit;
    }
}

// PM/super_admin: UPDATE/EDIT TASK
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "edit") {
    if (!in_array($role, ["project_manager","super_admin"], true)) {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $tid = (int)($_POST["task_id"] ?? 0);
    
    // Get project_id
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
    
    // Validasi
    if (empty($nama)) {
        header("Location: tasks.php?project={$pid}&error=" . urlencode("Nama tugas wajib diisi"));
        exit;
    }
    
    // Validasi status
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        $status = 'belum';
    }
    
    $assignee = ($assignee_raw === "") ? NULL : (int)$assignee_raw;
    
    // Validasi assignee jika ada
    if ($assignee !== NULL) {
        $check = mysqli_prepare($connect, "SELECT role, project_manager_id FROM users WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $assignee);
        mysqli_stmt_execute($check);
        $user = mysqli_fetch_assoc(mysqli_stmt_get_result($check));
        
        if (!$user || $user['role'] !== 'team_member') {
            header("Location: tasks.php?project={$pid}&error=" . urlencode("Hanya bisa assign ke Team Member"));
            exit;
        }
        
        // Validasi TM berada di bawah PM yang sama (jika PM yang assign)
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

// PM/super_admin: DELETE TASK
if (isset($_GET["del"])) {
    $tid = (int)$_GET["del"];
    
    // Get project_id
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

// team_member: UPDATE STATUS
if ($_SERVER["REQUEST_METHOD"] === "POST" && ($_POST["action"] ?? "") === "update_status_member") {
    if ($role !== "team_member") {
        http_response_code(403);
        exit("Akses ditolak");
    }
    
    $tid = (int)($_POST["task_id"] ?? 0);
    $status = $_POST["status"] ?? "belum";
    
    // Validasi status
    if (!in_array($status, ['belum', 'proses', 'selesai'], true)) {
        header("Location: tasks.php?view=my&error=" . urlencode("Status tidak valid"));
        exit;
    }
    
    if (!can_update_task_status($connect, $tid, $uid)) {
        http_response_code(403);
        exit("Task bukan milikmu");
    }
    
    $u = mysqli_prepare($connect, "UPDATE tasks SET status=? WHERE id=?");
    if (!$u) {
        die("Prepare failed: " . mysqli_error($connect));
    }
    
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

// Get task untuk edit (PM/SA)
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
                    <?php if ($role === "project_manager" || $role === "super_admin"): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="projects.php">Projects</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="tasks.php">Tasks</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link active" href="tasks.php?view=my">My Tasks</a>
                        </li>
                    <?php endif; ?>
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
        <h2 class="mb-4"><i class="bi bi-list-task"></i> Tasks</h2>

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

        <?php
        // ==================== MY TASKS (TEAM MEMBER) ====================
        if ($role === "team_member" && $view === "my") :
        ?>
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-list-check"></i> Tugas Saya</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Tugas</th>
                                    <th>Proyek</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.deskripsi, t.status, p.nama_proyek
                                                                  FROM tasks t 
                                                                  JOIN projects p ON p.id=t.project_id
                                                                  WHERE t.assigned_to=? 
                                                                  ORDER BY t.id DESC");
                                mysqli_stmt_bind_param($stmt, "i", $uid);
                                mysqli_stmt_execute($stmt);
                                $res = mysqli_stmt_get_result($stmt);
                                
                                while ($t = mysqli_fetch_assoc($res)):
                                    $badge_class = $t['status'] === 'selesai' ? 'success' : ($t['status'] === 'proses' ? 'info' : 'warning');
                                ?>
                                <tr>
                                    <td><?= $t['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($t['nama_tugas']) ?></strong></td>
                                    <td><?= htmlspecialchars($t['nama_proyek']) ?></td>
                                    <td><?= htmlspecialchars($t['deskripsi'] ?: '-') ?></td>
                                    <td><span class="badge bg-<?= $badge_class ?>"><?= $t['status'] ?></span></td>
                                    <td>
                                        <a href="tasks.php?edit_status=<?= $t['id'] ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i> Update Status
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php
        // ==================== FORM UPDATE STATUS (TEAM MEMBER) ====================
        elseif ($role === "team_member" && $edit_status_id > 0) :
            if (!can_update_task_status($connect, $edit_status_id, $uid)):
        ?>
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i> Akses ditolak - Task ini bukan milik Anda.
                </div>
                <a href="tasks.php?view=my" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke My Tasks
                </a>
            <?php
            else:
                $g = mysqli_prepare($connect, "SELECT t.nama_tugas, t.deskripsi, t.status, p.nama_proyek 
                                                FROM tasks t JOIN projects p ON p.id=t.project_id 
                                                WHERE t.id=?");
                mysqli_stmt_bind_param($g, "i", $edit_status_id);
                mysqli_stmt_execute($g);
                $rs = mysqli_stmt_get_result($g);
                $tt = mysqli_fetch_assoc($rs);
                
                if (!$tt):
            ?>
                <div class="alert alert-danger">
                    <i class="bi bi-x-circle"></i> Task tidak ditemukan.
                </div>
                <a href="tasks.php?view=my" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke My Tasks
                </a>
            <?php
                else:
            ?>
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-pencil"></i> Update Status Task</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <strong>Nama Task:</strong> <?= htmlspecialchars($tt["nama_tugas"]) ?><br>
                            <strong>Proyek:</strong> <?= htmlspecialchars($tt["nama_proyek"]) ?><br>
                            <strong>Deskripsi:</strong> <?= htmlspecialchars($tt["deskripsi"] ?: '-') ?>
                        </div>
                        
                        <form method="post">
                            <input type="hidden" name="action" value="update_status_member">
                            <input type="hidden" name="task_id" value="<?= $edit_status_id ?>">
                            
                            <div class="mb-3">
                                <label class="form-label"><strong>Status Saat Ini:</strong> 
                                    <span class="badge bg-<?= $tt['status'] === 'selesai' ? 'success' : ($tt['status'] === 'proses' ? 'info' : 'warning') ?>">
                                        <?= $tt['status'] ?>
                                    </span>
                                </label>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Ubah Status Ke: <span class="text-danger">*</span></label>
                                <select name="status" class="form-select form-select-lg" required>
                                    <option value="belum" <?= $tt['status']==='belum' ? 'selected' : '' ?>>🔴 Belum Dikerjakan</option>
                                    <option value="proses" <?= $tt['status']==='proses' ? 'selected' : '' ?>>🟡 Sedang Proses</option>
                                    <option value="selesai" <?= $tt['status']==='selesai' ? 'selected' : '' ?>>🟢 Selesai</option>
                                </select>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="bi bi-save"></i> Simpan Status
                                </button>
                                <a href="tasks.php?view=my" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            <?php
                endif;
            endif;
        endif;

        // ==================== TASKS PER PROJECT (PM/SA) ====================
        if ($project_id > 0 && ($role === "project_manager" || $role === "super_admin")) :
            if (!can_manage_project($connect, $project_id, $uid, $role) && $role !== "super_admin"):
        ?>
                <div class="alert alert-danger">Akses ditolak.</div>
            <?php
            else:
                $p = mysqli_prepare($connect, "SELECT nama_proyek FROM projects WHERE id=?");
                mysqli_stmt_bind_param($p, "i", $project_id);
                mysqli_stmt_execute($p);
                $r = mysqli_stmt_get_result($p);
                $proj = mysqli_fetch_assoc($r);
            ?>
                <div class="mb-3">
                    <a href="projects.php" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Projects
                    </a>
                </div>

                <div class="row">
                    <!-- Form Create/Edit Task -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="bi bi-<?= $edit_task ? 'pencil' : 'plus-circle' ?>"></i> 
                                    <?= $edit_task ? 'Edit Task' : 'Buat Task Baru' ?>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <strong>Proyek:</strong> <?= htmlspecialchars($proj["nama_proyek"] ?? "Unknown") ?>
                                </div>
                                
                                <form method="post">
                                    <input type="hidden" name="action" value="<?= $edit_task ? 'edit' : 'create' ?>">
                                    <input type="hidden" name="project_id" value="<?= $project_id ?>">
                                    <?php if ($edit_task): ?>
                                        <input type="hidden" name="task_id" value="<?= $edit_task['id'] ?>">
                                    <?php endif; ?>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Nama Task <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_tugas" 
                                               value="<?= $edit_task ? htmlspecialchars($edit_task['nama_tugas']) : '' ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" rows="3"><?= $edit_task ? htmlspecialchars($edit_task['deskripsi']) : '' ?></textarea>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            <option value="belum" <?= ($edit_task && $edit_task['status']==='belum') ? 'selected' : '' ?>>Belum</option>
                                            <option value="proses" <?= ($edit_task && $edit_task['status']==='proses') ? 'selected' : '' ?>>Proses</option>
                                            <option value="selesai" <?= ($edit_task && $edit_task['status']==='selesai') ? 'selected' : '' ?>>Selesai</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Assign ke</label>
                                        <select name="assigned_to" class="form-select">
                                            <option value="">- Tidak ada -</option>
                                            <?php
                                            // Jika PM, hanya tampilkan TM yang berada di bawahnya
                                            if ($role === 'project_manager') {
                                                $us = mysqli_prepare($connect, "SELECT id, username FROM users WHERE role='team_member' AND project_manager_id=? ORDER BY username");
                                                mysqli_stmt_bind_param($us, "i", $uid);
                                                mysqli_stmt_execute($us);
                                                $us_res = mysqli_stmt_get_result($us);
                                            } else {
                                                // SA bisa assign ke semua TM
                                                $us_res = mysqli_query($connect, "SELECT id, username FROM users WHERE role='team_member' ORDER BY username");
                                            }
                                            
                                            while ($u = mysqli_fetch_assoc($us_res)):
                                            ?>
                                                <option value="<?= $u['id'] ?>" <?= ($edit_task && $edit_task['assigned_to']==$u['id']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($u['username']) ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                        <small class="text-muted">Opsional - hanya Team Member</small>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-save"></i> <?= $edit_task ? 'Update' : 'Simpan' ?>
                                    </button>
                                    
                                    <?php if ($edit_task): ?>
                                        <a href="tasks.php?project=<?= $project_id ?>" class="btn btn-secondary w-100 mt-2">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </a>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Tasks -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header bg-white">
                                <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Tasks</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nama Task</th>
                                                <th>Status</th>
                                                <th>Assignee</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $stmt = mysqli_prepare($connect, "SELECT t.id, t.nama_tugas, t.status, u.username AS assignee
                                                                              FROM tasks t 
                                                                              LEFT JOIN users u ON u.id=t.assigned_to
                                                                              WHERE t.project_id=? 
                                                                              ORDER BY t.id DESC");
                                            mysqli_stmt_bind_param($stmt, "i", $project_id);
                                            mysqli_stmt_execute($stmt);
                                            $res = mysqli_stmt_get_result($stmt);
                                            
                                            while ($t = mysqli_fetch_assoc($res)):
                                                $badge_class = $t['status'] === 'selesai' ? 'success' : ($t['status'] === 'proses' ? 'info' : 'warning');
                                            ?>
                                            <tr>
                                                <td><?= $t['id'] ?></td>
                                                <td><strong><?= htmlspecialchars($t['nama_tugas']) ?></strong></td>
                                                <td><span class="badge bg-<?= $badge_class ?>"><?= $t['status'] ?></span></td>
                                                <td>
                                                    <?php if ($t['assignee']): ?>
                                                        <span class="badge bg-secondary"><?= htmlspecialchars($t['assignee']) ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="tasks.php?project=<?= $project_id ?>&edit=<?= $t['id'] ?>" class="btn btn-sm btn-warning">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <a href="tasks.php?del=<?= $t['id'] ?>" 
                                                       class="btn btn-sm btn-danger" 
                                                       onclick="return confirm('Yakin hapus task ini?')">
                                                        <i class="bi bi-trash"></i> Hapus
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
                </div>
            <?php
            endif;
        endif;

        // ==================== DEFAULT VIEW ====================
        if ($role === "team_member" && !$view && !$edit_status_id) :
        ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Buka <a href="tasks.php?view=my" class="alert-link">My Tasks</a> untuk melihat tugasmu.
            </div>
        <?php
        endif;

        if (($role === "project_manager" || $role === "super_admin") && !$project_id && !$edit_task_id) :
        ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle"></i> 
                Pilih proyek dari halaman <a href="projects.php" class="alert-link">Projects</a> untuk mengelola tasks.
            </div>
        <?php
        endif;
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>