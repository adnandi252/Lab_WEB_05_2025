<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../config/db.php';
ensure_logged_in();
$user = current_user();
$role = $user['role'];
$uid = $user['id'];

// Hanya Super Admin & Project Manager boleh menambah
require_role(['Super Admin','Project Manager']);

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_proyek'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    // manager_id: Super Admin bisa pilih, Project Manager otomatis dirinya
    if ($role === 'Super Admin') {
        $manager_id = (int)($_POST['manager_id'] ?? 0);
        if ($manager_id <= 0) $errors[] = "Pilih Project Manager.";
    } else {
        $manager_id = $uid;
    }

    if ($nama === '') $errors[] = "Nama proyek wajib diisi.";
    if ($tanggal_mulai === '' || $tanggal_selesai === '') $errors[] = "Tanggal mulai & selesai wajib diisi.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssi', $nama, $deskripsi, $tanggal_mulai, $tanggal_selesai, $manager_id);
        if ($stmt->execute()) {
            $success = "Proyek berhasil dibuat.";
        } else {
            $errors[] = "Gagal menyimpan proyek: " . $conn->error;
        }
    }
}

// Ambil daftar project managers (untuk Super Admin memilih)
$managers = [];
if ($role === 'Super Admin') {
    $res = $conn->query("SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username");
    while ($r = $res->fetch_assoc()) $managers[] = $r;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Tambah Proyek</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
    <a href="projects.php">← Kembali ke Proyek</a>
    <h2>Tambah Proyek</h2>

    <?php if ($success): ?><div class="alert success"><?=htmlspecialchars($success)?></div><?php endif; ?>
    <?php if ($errors): foreach ($errors as $e): ?><div class="alert error"><?=htmlspecialchars($e)?></div><?php endforeach; endif; ?>

    <form method="post">
      <label>Nama Proyek</label><br>
      <input type="text" name="nama_proyek" required><br><br>

      <label>Deskripsi</label><br>
      <textarea name="deskripsi"></textarea><br><br>

      <label>Tanggal Mulai</label><br>
      <input type="date" name="tanggal_mulai" required><br><br>

      <label>Tanggal Selesai</label><br>
      <input type="date" name="tanggal_selesai" required><br><br>

      <?php if ($role === 'Super Admin'): ?>
        <label>Project Manager</label><br>
        <select name="manager_id" required>
          <option value="">-- Pilih --</option>
          <?php foreach ($managers as $m): ?>
            <option value="<?= $m['id'] ?>"><?= htmlspecialchars($m['username']) ?></option>
          <?php endforeach; ?>
        </select><br><br>
      <?php endif; ?>

      <button type="submit">Simpan</button>
    </form>
  </div>
</body>
</html>
