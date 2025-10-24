<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
require_role('Super Admin');

if (!isset($_GET['id'])) { header("Location: manage_users.php"); exit; }
$uid = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT id, username, role, project_manager_id FROM users WHERE id = ?");
$stmt->bind_param('i', $uid);
$stmt->execute();
$userRow = $stmt->get_result()->fetch_assoc();
if (!$userRow) { echo "User tidak ditemukan."; exit; }

$errors = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $project_manager_id = $_POST['project_manager_id'] ? (int)$_POST['project_manager_id'] : null;

    if ($username === '' || $role === '') $errors = "Username & role wajib diisi.";
    if (!in_array($role, ['Super Admin','Project Manager','Team Member'])) $errors = "Role tidak valid.";

    if (!$errors) {
        if ($password !== '') {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt2 = $conn->prepare("UPDATE users SET username = ?, password = ?, role = ?, project_manager_id = ? WHERE id = ?");
            $stmt2->bind_param('sssii', $username, $hash, $role, $project_manager_id, $uid);
        } else {
            $stmt2 = $conn->prepare("UPDATE users SET username = ?, role = ?, project_manager_id = ? WHERE id = ?");
            $stmt2->bind_param('ssii', $username, $role, $project_manager_id, $uid);
        }
        if ($stmt2->execute()) {
            $success = "Data user diperbarui.";
            $stmt->execute();
            $userRow = $stmt->get_result()->fetch_assoc();
        } else {
            $errors = "Gagal update: " . $conn->error;
        }
    }
}

// managers list
$managers = [];
$q = $conn->query("SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username");
while ($r = $q->fetch_assoc()) $managers[] = $r;
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit User</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <a href="manage_users.php">← Kembali</a>
    <h2>Edit User</h2>

    <?php if ($success): ?><div class="alert success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert error"><?=htmlspecialchars($errors)?></div><?php endif; ?>

    <form method="post">
      <label>Username</label><br>
      <input type="text" name="username" value="<?=htmlspecialchars($userRow['username'])?>" required><br><br>

      <label>Password (kosongkan jika tidak ingin mengganti)</label><br>
      <input type="password" name="password"><br><br>

      <label>Role</label><br>
      <select name="role" id="role_select" onchange="togglePmSelect()">
        <option value="Super Admin" <?= $userRow['role']=='Super Admin' ? 'selected':'' ?>>Super Admin</option>
        <option value="Project Manager" <?= $userRow['role']=='Project Manager' ? 'selected':'' ?>>Project Manager</option>
        <option value="Team Member" <?= $userRow['role']=='Team Member' ? 'selected':'' ?>>Team Member</option>
      </select><br><br>

      <div id="pm_select" style="display:none;">
        <label>Project Manager untuk Team Member</label><br>
        <select name="project_manager_id">
          <option value="">-- Pilih --</option>
          <?php foreach ($managers as $m): ?>
            <option value="<?= $m['id'] ?>" <?= $m['id']==$userRow['project_manager_id'] ? 'selected':'' ?>><?=htmlspecialchars($m['username'])?></option>
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
