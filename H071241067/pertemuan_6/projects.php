<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
$user = current_user();
$role = $user['role'];
$uid = $user['id'];

// Hapus proyek (Super Admin atau Project Manager pemilik)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $pid = (int)$_GET['id'];
    if ($role === 'Super Admin') {
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
        $stmt->bind_param('i', $pid);
        $stmt->execute();
    } elseif ($role === 'Project Manager') {
        // hanya jika manager milik sendiri
        $stmt = $conn->prepare("DELETE FROM projects WHERE id = ? AND manager_id = ?");
        $stmt->bind_param('ii', $pid, $uid);
        $stmt->execute();
    }
    header("Location: projects.php");
    exit;
}

// Ambil daftar proyek sesuai role
if ($role === 'Super Admin') {
    $res = $conn->query("SELECT p.*, u.username AS manager_name FROM projects p LEFT JOIN users u ON p.manager_id = u.id ORDER BY p.id DESC");
} elseif ($role === 'Project Manager') {
    $stmt = $conn->prepare("SELECT p.*, u.username AS manager_name FROM projects p LEFT JOIN users u ON p.manager_id = u.id WHERE p.manager_id = ? ORDER BY p.id DESC");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
} else {
    // Team Member hanya lihat proyek yang ditugaskan kepadanya
    $stmt = $conn->prepare("SELECT DISTINCT p.*, u.username AS manager_name FROM projects p JOIN tasks t ON t.project_id = p.id LEFT JOIN users u ON p.manager_id = u.id WHERE t.assigned_to = ? ORDER BY p.id DESC");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Proyek - Manajemen Proyek</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <div class="topnav">
      <a href="dashboard.php">Dashboard</a> | <a href="projects.php">Proyek</a> | <a href="tasks.php">Tugas</a> | <a href="../auth/logout.php">Logout</a>
      <?php if ($role === 'Super Admin'): ?> | <a href="../users/manage_users.php">Kelola User</a><?php endif; ?>
    </div>

    <h2>Daftar Proyek</h2>

    <?php if ($role === 'Super Admin' || $role === 'Project Manager'): ?>
      <p><a href="add_project.php">+ Tambah Proyek</a></p>
    <?php endif; ?>

    <table class="table">
      <thead><tr><th>ID</th><th>Nama</th><th>Manager</th><th>Mulai</th><th>Selesai</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php while ($row = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['nama_proyek']) ?></td>
          <td><?= htmlspecialchars($row['manager_name'] ?? '-') ?></td>
          <td><?= $row['tanggal_mulai'] ?></td>
          <td><?= $row['tanggal_selesai'] ?></td>
          <td>
            <a href="tasks.php?project_id=<?= $row['id'] ?>">Tugas</a>
            <?php if ($role === 'Super Admin' || ($role === 'Project Manager' && $row['manager_id'] == $uid)): ?>
              | <a href="edit_project.php?id=<?= $row['id'] ?>">Edit</a>
              | <a href="projects.php?action=delete&id=<?= $row['id'] ?>" onclick="return confirm('Hapus proyek ini?')">Hapus</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>
</body>
</html>
