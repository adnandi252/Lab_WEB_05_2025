<?php
session_start();

if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true); // untuk menjaga keamanan 
}


if (!isset($_SESSION["user_id"])) {
    header("Location: login.html");
    exit;
}

require "connect.php"; // halaman ini bisa ambil data dari database.

// ambil data daei session
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); }
        .stat-card { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .stat-card h3 { font-size: 2.5rem; font-weight: bold; }
        .badge-role { padding: 8px 15px; border-radius: 20px; }
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
                        <a class="nav-link active" href="halaman_utama.php">Dashboard</a>
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
                            <a class="nav-link" href="tasks.php">Tasks</a>
                        </li>
                    <?php elseif ($role === "team_member"): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="tasks.php?view=my">My Tasks</a>
                        </li>
                    <?php endif; ?>
                </ul>
                <div class="d-flex align-items-center">
                    <span class="text-white me-3">
                        <i class="bi bi-person-circle"></i> 
                        <strong><?= $username ?></strong>
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
        <h2 class="mb-4">Dashboard</h2>

        <?php if ($role === "super_admin"): ?>
            <!-- Dashboard Super Admin -->
            <div class="alert alert-info mb-4">
                <i class="bi bi-shield-check"></i> <strong>Super Admin</strong> - Anda memiliki akses penuh ke seluruh sistem
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

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-people-fill" style="font-size: 3rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["users"] ?></h3>
                            <p class="mb-0">Total Users</p>
                            <small>PM: <?= (int)$row["pm"] ?> | TM: <?= (int)$row["tm"] ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-folder-fill" style="font-size: 3rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["projects"] ?></h3>
                            <p class="mb-0">Total Projects</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-list-task" style="font-size: 3rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["tasks"] ?></h3>
                            <p class="mb-0">Total Tasks</p>
                            <small>Selesai: <?= (int)$row["tasks_done"] ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Semua Project -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-folder2-open"></i> Semua Proyek</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Proyek</th>
                                    <th>Manager</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($connect, "SELECT p.id, p.nama_proyek, p.tanggal_mulai, 
                                                            COALESCE(p.tanggal_selesai,'-') AS tsel, u.username
                                                            FROM projects p 
                                                            JOIN users u ON u.id=p.manager_id 
                                                            ORDER BY p.id DESC");
                                while ($p = mysqli_fetch_assoc($q)):
                                ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($p['nama_proyek']) ?></strong></td>
                                    <td><span class="badge bg-primary"><?= htmlspecialchars($p['username']) ?></span></td>
                                    <td><?= $p['tanggal_mulai'] ?></td>
                                    <td><?= $p['tsel'] ?></td>
                                    <td>
                                        <a href="tasks.php?project=<?= $p['id'] ?>" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-list-check"></i> Tasks
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php elseif ($role === "project_manager"): ?>
            <!-- Dashboard Project Manager -->
            <div class="alert alert-primary mb-4">
                <i class="bi bi-briefcase"></i> <strong>Project Manager</strong> - Kelola proyek dan tugas Anda
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM projects WHERE manager_id=$uid) AS my_projects,
                (SELECT COUNT(*) FROM tasks WHERE project_id IN (SELECT id FROM projects WHERE manager_id=$uid)) AS my_tasks,
                (SELECT COUNT(*) FROM tasks WHERE status='selesai' AND project_id IN (SELECT id FROM projects WHERE manager_id=$uid)) AS done_tasks");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-folder-fill" style="font-size: 3rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["my_projects"] ?></h3>
                            <p class="mb-0">Proyek Saya</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-list-task" style="font-size: 3rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["my_tasks"] ?></h3>
                            <p class="mb-0">Total Tasks</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle-fill" style="font-size: 3rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["done_tasks"] ?></h3>
                            <p class="mb-0">Tasks Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="bi bi-folder2-open"></i> Proyek Saya</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Proyek</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Selesai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $q = mysqli_query($connect, "SELECT id, nama_proyek, tanggal_mulai, 
                                                            COALESCE(tanggal_selesai,'-') AS tsel
                                                            FROM projects WHERE manager_id = $uid 
                                                            ORDER BY id DESC");
                                while ($p = mysqli_fetch_assoc($q)):
                                ?>
                                <tr>
                                    <td><?= $p['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($p['nama_proyek']) ?></strong></td>
                                    <td><?= $p['tanggal_mulai'] ?></td>
                                    <td><?= $p['tsel'] ?></td>
                                    <td>
                                        <a href="tasks.php?project=<?= $p['id'] ?>" class="btn btn-sm btn-info text-white">
                                            <i class="bi bi-list-check"></i> Tasks
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        <?php else: // team_member ?>
            <!-- Dashboard Team Member -->
            <div class="alert alert-success mb-4">
                <i class="bi bi-person-check"></i> <strong>Team Member</strong> - Lihat dan update status tugas Anda
            </div>

            <?php
            $stat = mysqli_query($connect, "SELECT 
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid) AS my_tasks,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='belum') AS pending,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='proses') AS progress,
                (SELECT COUNT(*) FROM tasks WHERE assigned_to=$uid AND status='selesai') AS done");
            $row = mysqli_fetch_assoc($stat);
            ?>

            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card stat-card">
                        <div class="card-body text-center">
                            <i class="bi bi-list-task" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["my_tasks"] ?></h3>
                            <p class="mb-0">Total Tugas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-hourglass-split" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["pending"] ?></h3>
                            <p class="mb-0">Belum</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["progress"] ?></h3>
                            <p class="mb-0">Proses</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <i class="bi bi-check-circle-fill" style="font-size: 2.5rem;"></i>
                            <h3 class="mt-2"><?= (int)$row["done"] ?></h3>
                            <p class="mb-0">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

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
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
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
                                    $badge_class = $t['status'] === 'selesai' ? 'success' : ($t['status'] === 'proses' ? 'info' : 'warning');
                                ?>
                                <tr>
                                    <td><?= $t['id'] ?></td>
                                    <td><strong><?= htmlspecialchars($t['nama_tugas']) ?></strong></td>
                                    <td><?= htmlspecialchars($t['nama_proyek']) ?></td>
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
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>