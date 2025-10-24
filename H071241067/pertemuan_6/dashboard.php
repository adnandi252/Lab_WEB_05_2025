<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();

$user = current_user();
$role = $user['role'];
$uid = $user['id'];

// Small dashboard numbers
$total_projects = 0;
$total_tasks = 0;
$tasks_by_status = ['belum'=>0,'proses'=>0,'selesai'=>0];

if ($role === 'Super Admin') {
    $r = $conn->query("SELECT COUNT(*) AS c FROM projects")->fetch_assoc();
    $total_projects = $r['c'];
    $r = $conn->query("SELECT COUNT(*) AS c FROM tasks")->fetch_assoc();
    $total_tasks = $r['c'];
    $res = $conn->query("SELECT status, COUNT(*) AS c FROM tasks GROUP BY status");
} elseif ($role === 'Project Manager') {
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM projects WHERE manager_id = ?");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $total_projects = $stmt->get_result()->fetch_assoc()['c'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM tasks t JOIN projects p ON t.project_id = p.id WHERE p.manager_id = ?");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $total_tasks = $stmt->get_result()->fetch_assoc()['c'];

    $stmt = $conn->prepare("SELECT t.status, COUNT(*) AS c FROM tasks t JOIN projects p ON t.project_id = p.id WHERE p.manager_id = ? GROUP BY t.status");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
} else { // Team Member
    $stmt = $conn->prepare("SELECT COUNT(*) AS c FROM tasks WHERE assigned_to = ?");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $total_tasks = $stmt->get_result()->fetch_assoc()['c'];

    $stmt = $conn->prepare("SELECT status, COUNT(*) AS c FROM tasks WHERE assigned_to = ? GROUP BY status");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
}

if (isset($res)) {
    while ($row = $res->fetch_assoc()) {
        $tasks_by_status[$row['status']] = (int)$row['c'];
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Dashboard - Manajemen Proyek</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <div class="topnav">
      <span>Hai, <?=htmlspecialchars($user['username'])?> (<?=htmlspecialchars($role)?>)</span> |
      <a href="projects.php">Proyek</a> |
      <a href="tasks.php">Tugas</a>
      <?php if ($role === 'Super Admin'): ?> | <a href="../users/manage_users.php">Kelola User</a> <?php endif; ?>
      | <a href="../auth/logout.php">Logout</a>
    </div>

    <h2>Dashboard</h2>

    <div class="card-row">
      <div class="card">
        <h3>Proyek</h3>
        <p class="big"><?= $total_projects ?></p>
      </div>
      <div class="card">
        <h3>Total Tugas</h3>
        <p class="big"><?= $total_tasks ?></p>
      </div>
      <div class="card">
        <h3>Status Tugas</h3>
        <p>Belum: <?= $tasks_by_status['belum'] ?></p>
        <p>Proses: <?= $tasks_by_status['proses'] ?></p>
        <p>Selesai: <?= $tasks_by_status['selesai'] ?></p>
      </div>
    </div>

    <hr>

    <h3>Daftar Proyek (ringkasan)</h3>
    <table class="table">
      <thead><tr><th>ID</th><th>Nama Proyek</th><th>Manager</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php
        if ($role === 'Super Admin') {
            $res = $conn->query("SELECT p.*, u.username AS manager_name FROM projects p LEFT JOIN users u ON p.manager_id = u.id ORDER BY p.id DESC");
        } elseif ($role === 'Project Manager') {
            $stmt = $conn->prepare("SELECT p.*, u.username AS manager_name FROM projects p LEFT JOIN users u ON p.manager_id = u.id WHERE p.manager_id = ? ORDER BY p.id DESC");
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $res = $stmt->get_result();
        } else {
            // Team Member: show projects where they have tasks
            $stmt = $conn->prepare("SELECT DISTINCT p.*, u.username AS manager_name FROM projects p JOIN tasks t ON t.project_id = p.id LEFT JOIN users u ON p.manager_id = u.id WHERE t.assigned_to = ? ORDER BY p.id DESC");
            $stmt->bind_param('i', $uid);
            $stmt->execute();
            $res = $stmt->get_result();
        }

        while ($row = $res->fetch_assoc()):
        ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['nama_proyek']) ?></td>
          <td><?= htmlspecialchars($row['manager_name'] ?? '-') ?></td>
          <td><?= $row['tanggal_mulai'] ?></td>
          <td><?= $row['tanggal_selesai'] ?></td>
          <td>
            <a href="tasks.php?project_id=<?= $row['id'] ?>">Lihat Tugas</a>
            <?php if ($role === 'Super Admin' || ($role === 'Project Manager' && $row['manager_id'] == $uid)): ?>
              | <a href="edit_project.php?id=<?= $row['id'] ?>">Edit</a>
              | <a href="projects.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Hapus proyek?')">Hapus</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>
</body>
</html>
