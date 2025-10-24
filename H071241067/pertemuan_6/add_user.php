<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
require_role('Super Admin');

$errors = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $project_manager_id = $_POST['project_manager_id'] ? (int)$_POST['project_manager_id'] : null;

    if ($username === '' || $password === '' || $role === '') $errors = "Username, password, dan role wajib diisi.";
    else {
        // validasi role
        if (!in_array($role, ['Super Admin','Project Manager','Team Member'])) $errors = "Role tidak valid.";
    }

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('sssi', $username, $hash, $role, $project_manager_id);
        if ($stmt->execute()) {
            $success = "User berhasil dibuat.";
        } else {
            $errors = "Gagal membuat user: " . $conn->error;
        }
    }
}

// Ambil list project managers untuk select (untuk assign team member)
$managers = [];
$q = $conn->query("SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username");
while ($r = $q->fetch_assoc()) $managers[] = $r;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah User</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <a href="manage_users.php">← Kembali</a>
    <h2>Tambah User</h2>

    <?php if ($success): ?><div class="alert success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert error"><?=htmlspecialchars($errors)?></div><?php endif; ?>

    <form method="post">
      <label>Username</label><br>
      <input type="text" name="username" required><br><br>

      <label>Password</label><br>
      <input type="password" name="password" required><br><br>

      <label>Role</label><br>
      <select name="role" id="role_select" onchange="togglePmSelect()">
        <option value="Super Admin">Super Admin</option>
        <option value="Project Manager">Project Manager</option>
        <option value="Team Member">Team Member</option>
      </select><br><br>

      <div id="pm_select" style="display:none;">
        <label>Project Manager untuk Team Member</label><br>
        <select name="project_manager_id">
          <option value="">-- Pilih --</option>
          <?php foreach ($managers as $m): ?>
            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['username']) ?></option>
          <?php endforeach; ?>
        </select><br><br>
      </div>

      <button type="submit">Simpan</button>
    </form>
  </div>

<script>
function togglePmSelect(){
  var r = document.getElementById('role_select').value;
  document.getElementById('pm_select').style.display = (r === 'Team Member') ? 'block' : 'none';
}
togglePmSelect();
</script>
</body>
</html>
