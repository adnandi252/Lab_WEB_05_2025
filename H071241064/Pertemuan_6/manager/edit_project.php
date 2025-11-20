<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Edit Proyek';
$error = '';
$project_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$manager_id = intval($_SESSION['user_id']);

$stmt = $conn->prepare("SELECT * FROM projects WHERE id = ? AND manager_id = ?");
$stmt->bind_param("ii", $project_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'Proyek tidak ditemukan!';
    header('Location: projects.php');
    exit();
}

$project = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_proyek = trim($_POST['nama_proyek']);
    $deskripsi = trim($_POST['deskripsi']);
    $tanggal_mulai = trim($_POST['tanggal_mulai']);
    $tanggal_selesai = trim($_POST['tanggal_selesai']);

    if (empty($nama_proyek) || empty($deskripsi) || empty($tanggal_mulai) || empty($tanggal_selesai)) {
        $error = 'Semua field harus diisi!';
    } elseif ($tanggal_selesai < $tanggal_mulai) {
        $error = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!';
    } else {
        $stmt = $conn->prepare("UPDATE projects SET nama_proyek = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ? WHERE id = ? AND manager_id = ?");
        $stmt->bind_param("ssssii", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $project_id, $manager_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Proyek berhasil diupdate!';
            $stmt->close();
            header('Location: projects.php');
            exit();
        } else {
            $error = 'Gagal mengupdate proyek: ' . $stmt->error;
            $stmt->close();
        }
    }
}

include '../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
        <a href="projects.php" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Kembali ke Proyek
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Edit Proyek
        </h1>

        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Nama Proyek
                </label>
                <input type="text" name="nama_proyek" required
                    value="<?= htmlspecialchars($project['nama_proyek']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan nama proyek">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Deskripsi
                </label>
                <textarea name="deskripsi" required rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan deskripsi proyek"><?= htmlspecialchars($project['deskripsi']); ?></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" required
                        value="<?= $project['tanggal_mulai']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" required
                        value="<?= $project['tanggal_selesai']; ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>

            <div class="flex gap-4">
                <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Update Proyek
                </button>
                <a href="projects.php"
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
