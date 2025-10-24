<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
$user = current_user();
$role = $user['role'];
$uid = $user['id'];

// Hanya Super Admin & Project Manager boleh menambah tugas
require_role(['Super Admin','Project Manager']);

$project_id_prefill = isset($_GET['project_id']) ? (int)$_GET['project_id'] : null;
$errors = $success = '';

// Ambil proyek yang boleh dipilih
$projects = [];
if ($role === 'Super Admin') {
    $q = $conn->query("SELECT id, nama_proyek FROM projects ORDER BY nama_proyek");
    while ($r = $q->fetch_assoc()) $projects[] = $r;
} else {
    $stmt = $conn->prepare("SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $projects[] = $r;
}

// Ambil team members yang terkait (Super Admin bisa lihat semua Team Member; PM lihat yang project_manager_id = dirinya)
$members = [];
if ($role === 'Super Admin') {
    $q = $conn->query("SELECT id, username FROM users WHERE role = 'Team Member' ORDER BY username");
    while ($r = $q->fetch_assoc()) $members[] = $r;
} else {
    $stmt = $conn->prepare("SELECT id, username FROM users WHERE role = 'Team Member' AND project_manager_id = ? ORDER BY username");
    $stmt->bind_param('i', $uid);
    $stmt->execute();
    $res = $stmt->get_result();
    while ($r = $res->fetch_assoc()) $members[] = $r;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_tugas'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $status = $_POST['status'] ?? 'belum';
    $project_id = (int)($_POST['project_id'] ?? 0);
    $assigned_to = (int)($_POST['assigned_to'] ?? 0);

    if ($nama === '') $errors = "Nama tugas wajib diisi.";
    if ($project_id <= 0) $errors = "Pilih proyek.";

    if (!$errors) {
        $stmt = $conn->prepare("INSERT INTO tasks (nama_tugas, deskripsi, status, project_id, assigned_to) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('sssii', $nama, $deskripsi, $status, $project_id, $assigned_to);
        if ($stmt->execute()) {
            $success = "Tugas berhasil ditambahkan.";
        } else {
            $errors = "Gagal menyimpan tugas: " . $conn->error;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah Tugas</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <a href="tasks.php<?= $project_id_prefill ? '?project_id='.$project_id_prefill : '' ?>">← Kembali ke Tugas</a>
    <h2>Tambah Tugas</h2>

    <?php if ($success): ?><div class="alert success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <?php if ($errors): ?><div class="alert error"><?=htmlspecialchars($errors)?></div><?php endif; ?>

    <form method="post">
      <label>Nama Tugas</label><br>
      <input type="text" name="nama_tugas" required><br><br>

      <label>Deskripsi</label><br>
      <textarea name="deskripsi"></textarea><br><br>

      <label>Status</label><br>
      <select name="status">
        <option value="belum">belum</option>
        <option value="proses">proses</option>
        <option value="selesai">selesai</option>
      </select><br><br>

      <label>Proyek</label><br>
      <select name="project_id" required>
        <option value="">-- Pilih Proyek --</option>
        <?php foreach ($projects as $p): ?>
          <option value="<?= $p['id'] ?>" <?= $project_id_prefill && $project_id_prefill == $p['id'] ? 'selected' : '' ?>><?=htmlspecialchars($p['nama_proyek'])?></option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Assign ke (Team Member)</label><br>
      <select name="assigned_to">
        <option value="">-- Tidak ada --</option>
        <?php foreach ($members as $m): ?>
          <option value="<?= $m['id'] ?>"><?=htmlspecialchars($m['username'])?></option>
        <?php endforeach; ?>
      </select><br><br>

      <button type="submit">Simpan</button>
    </form>
  </div>
</body>
</html>
