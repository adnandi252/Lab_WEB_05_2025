<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

$page_title = 'Tambah Proyek';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_proyek = clean_input($_POST['nama_proyek']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $tanggal_mulai = clean_input($_POST['tanggal_mulai']);
    $tanggal_selesai = clean_input($_POST['tanggal_selesai']);
    $manager_id = $_SESSION['user_id'];
    
    if (empty($nama_proyek) || empty($deskripsi) || empty($tanggal_mulai) || empty($tanggal_selesai)) {
        $error = 'Semua field harus diisi!';
    } elseif ($tanggal_selesai < $tanggal_mulai) {
        $error = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai!';
    } else {
        $stmt = $conn->prepare("INSERT INTO projects (nama_proyek, deskripsi, tanggal_mulai, tanggal_selesai, manager_id)
                                VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nama_proyek, $deskripsi, $tanggal_mulai, $tanggal_selesai, $manager_id);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Proyek berhasil ditambahkan!';
            header('Location: projects.php');
            exit();
        } else {
            $error = 'Gagal menambahkan proyek: ' . $stmt->error;
        }
        $stmt->close();
    }
}

include '../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex gap-4">
        <a href="dashboard.php" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-home"></i> Dashboard
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Buat Proyek Baru
        </h1>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Nama Proyek
                </label>
                <input type="text" name="nama_proyek" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan nama proyek">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Deskripsi
                </label>
                <textarea name="deskripsi" required rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan deskripsi proyek"></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tanggal Mulai
                    </label>
                    <input type="date" name="tanggal_mulai" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">
                        Tanggal Selesai
                    </label>
                    <input type="date" name="tanggal_selesai" required 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Simpan Proyek
                </button>
                <a href="projects.php" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>