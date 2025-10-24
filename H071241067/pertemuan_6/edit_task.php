<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
$user = current_user();
$role = $user['role'];
$uid = $user['id'];

if (!isset($_GET['id'])) { header("Location: tasks.php"); exit; }
$tid = (int)$_GET['id'];

// Ambil tugas & cek akses
if ($role === 'Super Admin') {
    $stmt = $conn->prepare("SELECT t.*, p.manager_id FROM tasks t LEFT JOIN projects p ON t.project_id = p.id WHERE t.id = ?");
    $stmt->bind_param('i', $tid);
} else {
    // Project Manager: hanya tugas di proyeknya
    $stmt = $conn->prepare("SELECT t.*, p.manager_id FROM tasks t LEFT JOIN projects p ON t.project_id = p.id WHERE t.id = ? AND p.manager_id = ?");
    $stmt->bind_param('ii', $tid, $uid);
}
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) { echo "Tugas tidak ditemukan atau akses ditolak."; exit; }
$task = $res->fetch_assoc();

// Ambil daftar project & members untuk PM/SA
$projects = [];
if ($role === 'Super Admin') {
    $q = $conn->query("SELECT id, nama_proyek FROM projects ORDER BY nama_proyek");
    while ($r = $q->fetch_assoc()) $projects[] = $r;
} else {
    $stmt2 = $conn->prepare("SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek");
    $stmt2->bind_param('i', $uid);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    while ($r = $res2->fetch_assoc()) $projects[] = $r;
}

// Members
$members = [];
if ($role === 'Super Admin') {
    $q = $conn->query("SELECT id, username FROM users WHERE role = 'Team Member' ORDER BY username");
    while ($r = $q->fetch_assoc()) $members[] = $r;
} else {
    $stmt3 = $conn->prepare("SELECT id, username FROM users WHERE role = 'Team Member' AND project_manager_id = ? ORDER BY username");
    $stmt3->bind_param('i', $uid);
    $stmt3->execute();
    $res3 = $stmt3->get_result();
    while ($r = $res3->fetch_assoc()) $members[] = $r;
}

$errors = $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_tugas'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'belum';
    $project_id = (int)($_POST['project_id'] ?? 0);
    $assigned_to = (int)($_POST['assigned_to'] ?? 0);

    if ($nama === '') $errors = "Nama tugas wajib diisi.";
    if ($project_id <= 0) $errors = "Pilih proyek.";

    if (!$errors) {
        $stmt = $conn->prepare("UPDATE tasks SET nama_tugas = ?, deskripsi = ?, status = ?, project_id = ?, assigned_to = ? WHERE id = ?");
        $stmt->bind_param('sssiii', $nama, $deskripsi, $status, $project_id, $assigned_to, $tid);
        if ($stmt->execute()) {
            $success = "Perubahan disimpan.";
        } else {
            $errors = "Gagal update: " . $conn->error;
        }
    }
    // refresh data
    $stmt4 = $conn->prepare("SELECT t.*, p.manager_id FROM tasks t LEFT JOIN projects p ON t.project_id = p.id WHERE t.id = ?");
    $stmt4->bind_param('i', $tid);
    $stmt4->execute();
    $task = $stmt4->get_result()->fetch_assoc();
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Edit Tugas</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <a href="tasks.php?project_id=<?= $task['project_id'] ?>">← Kembali ke Tugas</a>
    <h2>Edit Tugas</h2>

    <?php if ($success): ?><div class="alert success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert error"><?=htmlspecialchars($errors)?></div><?php endif; ?>

    <form method="post">
      <label>Nama Tugas</label><br>
      <input type="text" name="nama_tugas" value="<?=htmlspecialchars($task['nama_tugas'])?>" required><br><br>

      <label>Deskripsi</label><br>
      <textarea name="deskripsi"><?=htmlspecialchars($task['deskripsi'])?></textarea><br><br>

      <label>Status</label><br>
      <select name="status">
        <option value="belum" <?= $task['status']=='belum' ? 'selected':'' ?>>belum</option>
        <option value="proses" <?= $task['status']=='proses' ? 'selected':'' ?>>proses</option>
        <option value="selesai" <?= $task['status']=='selesai' ? 'selected':'' ?>>selesai</option>
      </select><br><br>

      <label>Proyek</label><br>
      <select name="project_id" required>
        <option value="">-- Pilih Proyek --</option>
        <?php foreach ($projects as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $p['id']==$task['project_id'] ? 'selected':'' ?>><?=htmlspecialchars($p['nama_proyek'])?></option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Assign ke (Team Member)</label><br>
      <select name="assigned_to">
        <option value="">-- Tidak ada --</option>
        <?php foreach ($members as $m): ?>
          <option value="<?= $m['id'] ?>" <?= $m['id']==$task['assigned_to'] ? 'selected':'' ?>><?=htmlspecialchars($m['username'])?></option>
        <?php endforeach; ?>
      </select><br><br>

      <button type="submit">Simpan</button>
    </form>
  </div>
</body>
</html>
