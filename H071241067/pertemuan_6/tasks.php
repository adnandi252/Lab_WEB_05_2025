<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
$user = current_user();
$role = $user['role'];
$uid = $user['id'];

$project_id = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;

// ubah status (Team Member boleh ubah tugasnya sendiri)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $task_id = (int)($_POST['task_id'] ?? 0);
    $new_status = $_POST['status'] ?? 'belum';
    if ($role === 'Team Member') {
        // hanya jika assigned_to = uid
        $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ? AND assigned_to = ?");
        $stmt->bind_param('sii', $new_status, $task_id, $uid);
        $stmt->execute();
    } elseif ($role === 'Project Manager' || $role === 'Super Admin') {
        // PM/SA boleh update semua tugas di proyek yang dikelola / semua tugas (SA)
        if ($role === 'Project Manager') {
            $stmt = $conn->prepare("UPDATE tasks t JOIN projects p ON t.project_id = p.id SET t.status = ? WHERE t.id = ? AND p.manager_id = ?");
            $stmt->bind_param('sii', $new_status, $task_id, $uid);
        } else {
            $stmt = $conn->prepare("UPDATE tasks SET status = ? WHERE id = ?");
            $stmt->bind_param('si', $new_status, $task_id);
        }
        $stmt->execute();
    }
    header("Location: tasks.php" . ($project_id ? "?project_id=".$project_id : ""));
    exit;
}

// Hapus tugas (Project Manager untuk proyeknya; Super Admin semuanya)
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $tid = (int)$_GET['id'];
    if ($role === 'Super Admin') {
        $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
        $stmt->bind_param('i', $tid);
        $stmt->execute();
    } elseif ($role === 'Project Manager') {
        $stmt = $conn->prepare("DELETE t FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.id = ? AND p.manager_id = ?");
        $stmt->bind_param('ii', $tid, $uid);
        $stmt->execute();
    }
    header("Location: tasks.php" . ($project_id ? "?project_id=".$project_id : ""));
    exit;
}

// Ambil daftar tugas sesuai role & project filter
if ($role === 'Super Admin') {
    if ($project_id) {
        $stmt = $conn->prepare("SELECT t.*, p.nama_proyek, u.username AS assigned_name FROM tasks t LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id WHERE t.project_id = ? ORDER BY t.id DESC");
        $stmt->bind_param('i', $project_id);
    } else {
        $stmt = $conn->prepare("SELECT t.*, p.nama_proyek, u.username AS assigned_name FROM tasks t LEFT JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id ORDER BY t.id DESC");
    }
    if (isset($stmt)) { $stmt->execute(); $res = $stmt->get_result(); }
} elseif ($role === 'Project Manager') {
    if ($project_id) {
        $stmt = $conn->prepare("SELECT t.*, p.nama_proyek, u.username AS assigned_name FROM tasks t JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id WHERE p.manager_id = ? AND t.project_id = ? ORDER BY t.id DESC");
        $stmt->bind_param('ii', $uid, $project_id);
    } else {
        $stmt = $conn->prepare("SELECT t.*, p.nama_proyek, u.username AS assigned_name FROM tasks t JOIN projects p ON t.project_id = p.id LEFT JOIN users u ON t.assigned_to = u.id WHERE p.manager_id = ? ORDER BY t.id DESC");
        $stmt->bind_param('i', $uid);
    }
    $stmt->execute(); $res = $stmt->get_result();
} else { // Team Member
    if ($project_id) {
        $stmt = $conn->prepare("SELECT t.*, p.nama_proyek FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.assigned_to = ? AND t.project_id = ? ORDER BY t.id DESC");
        $stmt->bind_param('ii', $uid, $project_id);
    } else {
        $stmt = $conn->prepare("SELECT t.*, p.nama_proyek FROM tasks t JOIN projects p ON t.project_id = p.id WHERE t.assigned_to = ? ORDER BY t.id DESC");
        $stmt->bind_param('i', $uid);
    }
    $stmt->execute(); $res = $stmt->get_result();
}

// untuk form add task: ambil proyek yang boleh di-assign (PM untuk proyeknya; SA semua projects)
$projects_for_assign = [];
if ($role === 'Super Admin') {
    $q = $conn->query("SELECT id, nama_proyek FROM projects ORDER BY nama_proyek");
    while ($r = $q->fetch_assoc()) $projects_for_assign[] = $r;
} elseif ($role === 'Project Manager') {
    $stmt2 = $conn->prepare("SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek");
    $stmt2->bind_param('i', $uid);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($r = $res2->fetch_assoc()) $projects_for_assign[] = $r;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tugas - Manajemen Proyek</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <div class="topnav">
      <a href="dashboard.php">Dashboard</a> | <a href="projects.php">Proyek</a> | <a href="tasks.php">Tugas</a> | <a href="../auth/logout.php">Logout</a>
      <?php if ($user['role'] === 'Super Admin'): ?> | <a href="../users/manage_users.php">Kelola User</a><?php endif; ?>
    </div>

    <h2>Daftar Tugas <?= $project_id ? " (Project ID: $project_id)" : "" ?></h2>

    <?php if ($role === 'Project Manager' || $role === 'Super Admin'): ?>
      <p><a href="add_task.php<?= $project_id ? '?project_id='.$project_id : '' ?>">+ Tambah Tugas</a></p>
    <?php endif; ?>

    <table class="table">
      <thead><tr><th>ID</th><th>Nama Tugas</th><th>Project</th><th>Assigned To</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php while ($t = $res->fetch_assoc()): ?>
        <tr>
          <td><?= $t['id'] ?></td>
          <td><?= htmlspecialchars($t['nama_tugas']) ?></td>
          <td><?= htmlspecialchars($t['nama_proyek'] ?? '-') ?></td>
          <td><?= htmlspecialchars($t['assigned_name'] ?? '-') ?></td>
          <td><?= htmlspecialchars($t['status']) ?></td>
          <td>
            <?php if ($role === 'Super Admin' || ($role === 'Project Manager')): ?>
              <a href="edit_task.php?id=<?= $t['id'] ?>">Edit</a> |
              <a href="tasks.php?action=delete&id=<?= $t['id'] ?><?= $project_id ? '&project_id='.$project_id : '' ?>" onclick="return confirm('Hapus tugas?')">Hapus</a> |
            <?php endif; ?>

            <?php if ($role === 'Team Member' && $t['assigned_to'] == $uid): ?>
              <!-- Team member: ubah status -->
              <form style="display:inline" method="post">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="task_id" value="<?= $t['id'] ?>">
                <select name="status" onchange="this.form.submit()">
                  <option value="belum" <?= $t['status']=='belum' ? 'selected':'' ?>>belum</option>
                  <option value="proses" <?= $t['status']=='proses' ? 'selected':'' ?>>proses</option>
                  <option value="selesai" <?= $t['status']=='selesai' ? 'selected':'' ?>>selesai</option>
                </select>
              </form>
            <?php elseif ($role === 'Project Manager' || $role === 'Super Admin'): ?>
              <!-- PM/SA: ubah status inline -->
              <form style="display:inline" method="post">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="task_id" value="<?= $t['id'] ?>">
                <select name="status" onchange="this.form.submit()">
                  <option value="belum" <?= $t['status']=='belum' ? 'selected':'' ?>>belum</option>
                  <option value="proses" <?= $t['status']=='proses' ? 'selected':'' ?>>proses</option>
                  <option value="selesai" <?= $t['status']=='selesai' ? 'selected':'' ?>>selesai</option>
                </select>
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>

  </div>
</body>
</html>
