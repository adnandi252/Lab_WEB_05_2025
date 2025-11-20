<?php
session_start();
require_once '../config/database.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Team Member') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Tugas Saya';
$member_id = intval($_SESSION['user_id']); 

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$status_filter = isset($_GET['status']) ? clean_input($_GET['status']) : '';

if ($status_filter) {
    $status_safe = mysqli_real_escape_string($conn, $status_filter);
    $query = "
        SELECT t.*, p.nama_proyek, u.username AS manager_name
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        JOIN users u ON p.manager_id = u.id
        WHERE t.assigned_to = '$member_id' AND t.status = '$status_safe'
        ORDER BY t.id DESC
    ";
} else {
    $query = "
        SELECT t.*, p.nama_proyek, u.username AS manager_name
        FROM tasks t
        JOIN projects p ON t.project_id = p.id
        JOIN users u ON p.manager_id = u.id
        WHERE t.assigned_to = '$member_id'
        ORDER BY t.id DESC
    ";
}

$result = mysqli_query($conn, $query);

include '../includes/header.php'; 
?>

<div class="max-w-7xl mx-auto">
    <div class="mb-4">
        <a href="dashboard.php" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Dashboard
        </a>
    </div>

    <h1 class="text-3xl font-bold text-gray-800 mb-8">
        <i class="fas fa-tasks"></i> Tugas Saya
    </h1>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle"></i> 
            <?php echo clean_input($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> 
            <?php echo clean_input($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex items-center gap-4 flex-wrap">
            <span class="text-gray-700 font-semibold">
                <i class="fas fa-filter"></i> Filter Status:
            </span>
            <a href="tasks.php" 
               class="px-4 py-2 rounded-lg <?= $status_filter == '' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                Semua
            </a>
            <a href="tasks.php?status=belum" 
               class="px-4 py-2 rounded-lg <?= $status_filter == 'belum' ? 'bg-gray-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                Belum Dikerjakan
            </a>
            <a href="tasks.php?status=proses" 
               class="px-4 py-2 rounded-lg <?= $status_filter == 'proses' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                Dalam Proses
            </a>
            <a href="tasks.php?status=selesai" 
               class="px-4 py-2 rounded-lg <?= $status_filter == 'selesai' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'; ?>">
                Selesai
            </a>
        </div>
    </div>
    
    <?php if (mysqli_num_rows($result) == 0): ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-600">
                <?= $status_filter ? "Tidak ada tugas dengan status '" . clean_input($status_filter) . "'." : "Belum ada tugas yang ditugaskan kepada Anda."; ?>
            </p>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ($task = mysqli_fetch_assoc($result)): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                    <div class="bg-black p-4">
                        <h3 class="text-white font-bold text-lg mb-1">
                            <?= htmlspecialchars($task['nama_tugas']); ?>
                        </h3>
                        <p class="text-blue-100 text-sm">
                            <i class="fas fa-folder"></i> <?= htmlspecialchars($task['nama_proyek']); ?>
                        </p>
                    </div>
                    
                    <div class="p-4">
                        <p class="text-gray-600 text-sm mb-4"><?= htmlspecialchars($task['deskripsi']); ?></p>
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-user-tie"></i> Project Manager: 
                            <span class="font-semibold"><?= htmlspecialchars($task['manager_name']); ?></span>
                        </p>

                        <?php
                        $status_class = '';
                        $status_icon = '';
                        switch ($task['status']) {
                            case 'belum':
                                $status_class = 'bg-gray-100 text-gray-800 border-gray-300';
                                $status_icon = 'fa-clock';
                                break;
                            case 'proses':
                                $status_class = 'bg-blue-100 text-blue-800 border-blue-300';
                                $status_icon = 'fa-spinner';
                                break;
                            case 'selesai':
                                $status_class = 'bg-green-100 text-green-800 border-green-300';
                                $status_icon = 'fa-check-circle';
                                break;
                        }
                        ?>
                        <span class="px-3 py-2 inline-flex text-sm font-semibold rounded-lg border-2 <?= $status_class; ?>">
                            <i class="fas <?= $status_icon; ?> mr-2"></i> Status: <?= ucfirst($task['status']); ?>
                        </span>

                        <form method="POST" action="update_status.php" class="space-y-3 mt-4">
                            <input type="hidden" name="task_id" value="<?= $task['id']; ?>">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-sync-alt"></i> Ubah Status:
                            </label>
                            <select name="new_status" required
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 text-sm">
                                <option value="belum" <?= $task['status'] == 'belum' ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                                <option value="proses" <?= $task['status'] == 'proses' ? 'selected' : ''; ?>>Dalam Proses</option>
                                <option value="selesai" <?= $task['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                            <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition text-sm font-semibold">
                                <i class="fas fa-save"></i> Update Status
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
</div>