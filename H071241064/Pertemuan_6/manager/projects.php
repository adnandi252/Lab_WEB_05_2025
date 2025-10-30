<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Proyek Saya';
$manager_id = intval($_SESSION['user_id']);

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

$query = "SELECT p.*, 
          (SELECT COUNT(*) FROM tasks WHERE project_id = p.id) as total_tasks,
          (SELECT COUNT(*) FROM tasks WHERE project_id = p.id AND status = 'selesai') as completed_tasks
          FROM projects p
          WHERE p.manager_id = $manager_id
          ORDER BY p.tanggal_mulai DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Query gagal: ' . mysqli_error($conn));
}

include '../includes/header.php';
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-6">
        <a href="dashboard.php" class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-home"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            Proyek Saya
        </h1>
        <a href="add_project.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">
            + Buat Proyek Baru
        </a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>
    
    <?php if (mysqli_num_rows($result) == 0): ?>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-600 mb-4">Anda belum memiliki proyek.</p>
        <a href="add_project.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-block">
            <i class="fas fa-plus"></i> Buat Proyek Pertama
        </a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php while ($project = mysqli_fetch_assoc($result)): 
            $progress = $project['total_tasks'] > 0 ? round(($project['completed_tasks'] / $project['total_tasks']) * 100) : 0;
        ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
            <div class="bg-black p-4">
                <h3 class="text-white font-bold text-lg mb-1">
                    <?php echo htmlspecialchars($project['nama_proyek']); ?>
                </h3>
            </div>
            
            <div class="p-4">
                <p class="text-gray-600 text-sm mb-4">
                    <?php echo htmlspecialchars(substr($project['deskripsi'], 0, 100)) . (strlen($project['deskripsi']) > 100 ? '...' : ''); ?>
                </p>
                
                <div class="flex items-center text-sm text-gray-500 mb-2">
                    <i class="fas fa-calendar-alt mr-2"></i>
                    <span><?php echo date('d/m/Y', strtotime($project['tanggal_mulai'])); ?></span>
                    <span class="mx-2">-</span>
                    <span><?php echo date('d/m/Y', strtotime($project['tanggal_selesai'])); ?></span>
                </div>
                
                <div class="flex items-center text-sm text-gray-500 mb-3">
                    <i class="fas fa-tasks mr-2"></i>
                    <span><?php echo $project['completed_tasks']; ?> / <?php echo $project['total_tasks']; ?> Tugas Selesai</span>
                </div>
                
                <div class="mb-4">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Progress</span>
                        <span><?php echo $progress; ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <a href="edit_project.php?id=<?php echo $project['id']; ?>" 
                        class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 rounded text-sm text-center transition">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="tasks.php?project_id=<?php echo $project['id']; ?>" 
                        class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded text-sm text-center transition">
                        <i class="fas fa-tasks"></i> Tugas
                    </a>
                    <button onclick="if(confirm('Yakin ingin menghapus proyek ini dan semua tugasnya?')) window.location.href='delete_project.php?id=<?php echo $project['id']; ?>'" 
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