<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
require_role('Super Admin');

$action = $_GET['action'] ?? '';
if ($action === 'delete' && isset($_GET['id'])) {
    $uid_del = (int)$_GET['id'];
    // Jangan hapus diri sendiri
    if ($uid_del != $_SESSION['user_id']) {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param('i', $uid_del);
        $stmt->execute();
    }
    header("Location: manage_users.php");
    exit;
}

// Ambil semua user
$res = $conn->query("SELECT id, username, role, project_manager_id FROM users ORDER BY id ASC");

// Ambil daftar project managers untuk assign saat tambah member (di add_user.php)
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Kelola User</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <div class="topnav">
      <a href="../pages/dashboard.php">Dashboard</a> | <a href="../pages/projects.php">Proyek</a> | <a href="../pages/tasks.php">Tugas</a> | <a href="manage_users.php">Kelola User</a> | <a href="../auth/logout.php">Logout</a>
    </div>

    <h2>Kelola User (Super Admin)</h2>
    <p><a href="add_user.php">+ Tambah User</a></p>

    <table class="table">
      <thead><tr><th>ID</th><th>Username</th><th>Role</th><th>Project Manager</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php while ($u = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= htmlspecialchars($u['username']) ?></td>
          <td><?= htmlspecialchars($u['role']) ?></td>
          <td>
            <?php
            if ($u['project_manager_id']) {
                $q = $conn->prepare("SELECT username FROM users WHERE id = ?");
                $q->bind_param('i', $u['project_manager_id']);
                $q->execute();
                $nm = $q->get_result()->fetch_assoc();
                echo htmlspecialchars($nm['username'] ?? '-');
            } else {
                echo '-';
            }
            ?>
          </td>
          <td>
            <a href="edit_user.php?id=<?= $u['id'] ?>">Edit</a>
            <?php if ($u['id'] != $_SESSION['user_id']): ?>
              | <a href="manage_users.php?action=delete&id=<?= $u['id'] ?>" onclick="return confirm('Hapus user?')">Hapus</a>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>
</body>
</html>
