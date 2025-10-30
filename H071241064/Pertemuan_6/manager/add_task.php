<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

$page_title = 'Tambah Tugas';
$error = '';
$manager_id = (int)$_SESSION['user_id'];

$query_projects = "SELECT id, nama_proyek FROM projects WHERE manager_id = ? ORDER BY nama_proyek";
$stmt = $conn->prepare($query_projects);
$stmt->bind_param('i', $manager_id);
$stmt->execute();
$projects = $stmt->get_result();

$query_members = "SELECT id, username FROM users WHERE role = 'Team Member' AND project_manager_id = ? ORDER BY username";
$stmt2 = $conn->prepare($query_members);
$stmt2->bind_param('i', $manager_id);
$stmt2->execute();
$members = $stmt2->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_tugas = clean_input($_POST['nama_tugas']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $project_id = (int)$_POST['project_id'];
    $assigned_to = !empty($_POST['assigned_to']) ? (int)$_POST['assigned_to'] : null;
    $status = 'belum'; 

    if (empty($nama_tugas) || empty($deskripsi) || empty($project_id)) {
        $error = 'Nama tugas, deskripsi, dan proyek harus diisi!';
    } else {
        $check_query = "SELECT id FROM projects WHERE id = ? AND manager_id = ?";
        $check_stmt = $conn->prepare($check_query);
        $check_stmt->bind_param('ii', $project_id, $manager_id);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows === 0) {
            $error = 'Proyek tidak valid!';
        } else {
            $insert_query = "INSERT INTO tasks (nama_tugas, deskripsi, status, project_id, assigned_to)
                             VALUES (?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param('sssii', $nama_tugas, $deskripsi, $status, $project_id, $assigned_to);

            if ($insert_stmt->execute()) {
                $_SESSION['success'] = 'Tugas berhasil ditambahkan!';
                header('Location: tasks.php');
                exit();
            } else {
                $error = 'Gagal menambahkan tugas: ' . $conn->error;
            }
        }
    }
}

include '../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex gap-4">
        <a href="tasks.php" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Tambah Tugas Baru
        </h1>

        <?php if (!empty($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Nama Tugas
                </label>
                <input type="text" name="nama_tugas" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan nama tugas">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Deskripsi
                </label>
                <textarea name="deskripsi" required rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan deskripsi tugas"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Proyek
                </label>
                <select name="project_id" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Proyek</option>
                    <?php while ($project = $projects->fetch_assoc()): ?>
                    <option value="<?= $project['id']; ?>">
                        <?= htmlspecialchars($project['nama_proyek']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Ditugaskan Ke
                </label>
                <select name="assigned_to" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Belum Ditugaskan</option>
                    <?php while ($member = $members->fetch_assoc()): ?>
                    <option value="<?= $member['id']; ?>">
                        <?= htmlspecialchars($member['username']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Simpan Tugas
                </button>
                <a href="tasks.php" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>