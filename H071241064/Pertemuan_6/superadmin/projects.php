<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Super Admin') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Semua Proyek';

if (isset($_GET['delete'])) {
    $project_id = intval($_GET['delete']);
    $delete_query = "DELETE FROM projects WHERE id = $project_id";
    if (mysqli_query($conn, $delete_query)) {
        $_SESSION['success'] = 'Proyek berhasil dihapus!';
    }
    header('Location: projects.php');
    exit();
}

$query = "SELECT p.*, u.username as manager_name, 
          (SELECT COUNT(*) FROM tasks WHERE project_id = p.id) as total_tasks
          FROM projects p
          LEFT JOIN users u ON p.manager_id = u.id
          ORDER BY p.tanggal_mulai DESC";
$result = mysqli_query($conn, $query);

include '../includes/header.php';
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="dashboard.php" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        Semua Proyek
    </h1>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>
    
    <?php if (mysqli_num_rows($result) == 0): ?>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <i class="text-gray-300 mb-4"></i>
        <p class="text-gray-600">Belum ada proyek yang dibuat.</p>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($project = mysqli_fetch_assoc($result)): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <div class="bg-black p-4">
                <h3 class="text-white font-bold text-lg mb-1">
                    <?php echo htmlspecialchars($project['nama_proyek']); ?>
                </h3>
                <p class="text-white text-sm">
                    <?php echo htmlspecialchars($project['manager_name']); ?>
                </p>
            </div>
            
            <div class="p-4">
                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                    <?php echo htmlspecialchars(substr($project['deskripsi'], 0, 100)) . (strlen($project['deskripsi']) > 100 ? '...' : ''); ?>
                </p>
                
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span><?php echo date('d/m/Y', strtotime($project['tanggal_mulai'])); ?></span>
                    <span class="mx-2">-</span>
                    <span><?php echo date('d/m/Y', strtotime($project['tanggal_selesai'])); ?></span>
                </div>
                
                <div class="flex items-center text-sm text-gray-500 mb-4">
                    <i class="fas fa-tasks mr-2"></i>
                    <span><?php echo $project['total_tasks']; ?> Tugas</span>
                </div>
                
                <div class="flex gap-2">
                    <button onclick="if(confirm('Yakin ingin menghapus proyek ini?')) window.location.href='projects.php?delete=<?php echo $project['id']; ?>'" 
                        class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 rounded text-sm transition">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>

