<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Kelola Tugas';
$manager_id = intval($_SESSION['user_id']);

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$project_filter = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

$query_projects = "SELECT id, nama_proyek FROM projects WHERE manager_id = $manager_id ORDER BY nama_proyek";
$projects = mysqli_query($conn, $query_projects);
if (!$projects) die('Gagal mengambil data proyek: ' . mysqli_error($conn));

if ($project_filter > 0) {
    $query_tasks = "SELECT t.*, p.nama_proyek, u.username AS assigned_name
                    FROM tasks t
                    JOIN projects p ON t.project_id = p.id
                    LEFT JOIN users u ON t.assigned_to = u.id
                    WHERE p.manager_id = $manager_id AND p.id = $project_filter
                    ORDER BY t.id DESC";
} else {
    $query_tasks = "SELECT t.*, p.nama_proyek, u.username AS assigned_name
                    FROM tasks t
                    JOIN projects p ON t.project_id = p.id
                    LEFT JOIN users u ON t.assigned_to = u.id
                    WHERE p.manager_id = $manager_id
                    ORDER BY t.id DESC";
}

$result_tasks = mysqli_query($conn, $query_tasks);
if (!$result_tasks) die('Gagal mengambil data tugas: ' . mysqli_error($conn));

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
            <i class="fas fa-tasks"></i> Kelola Tugas
        </h1>
        <a href="add_task.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg shadow">
            <i class="fas fa-plus"></i> Tambah Tugas
        </a>
    </div>

    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-check-circle"></i> 
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <i class="fas fa-exclamation-circle"></i> 
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <form method="GET" action="" class="flex items-center gap-4">
            <label class="text-gray-700 font-semibold">
                <i class="fas fa-filter"></i> Filter Proyek:
            </label>
            <select name="project_id" onchange="this.form.submit()"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                <option value="0">Semua Proyek</option>
                <?php 
                mysqli_data_seek($projects, 0);
                while ($project = mysqli_fetch_assoc($projects)): 
                ?>
                <option value="<?php echo $project['id']; ?>" 
                    <?php echo $project_filter == $project['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($project['nama_proyek']); ?>
                </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <?php if (mysqli_num_rows($result_tasks) == 0): ?>
    <div class="bg-white rounded-lg shadow-md p-8 text-center">
        <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
        <p class="text-gray-600 mb-4">Belum ada tugas.</p>
        <a href="add_task.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg inline-block">
            <i class="fas fa-plus"></i> Tambah Tugas Pertama
        </a>
    </div>
    <?php else: ?>
    <div class="overflow-x-auto bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Tugas</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Proyek</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ditugaskan Ke</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($task = mysqli_fetch_assoc($result_tasks)): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">
                            <?php echo htmlspecialchars($task['nama_tugas']); ?>
                        </div>
                        <div class="text-sm text-gray-500">
                            <?php echo htmlspecialchars(substr($task['deskripsi'], 0, 50)) . '...'; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <?php echo htmlspecialchars($task['nama_proyek']); ?>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <?php echo $task['assigned_name'] 
                            ? '<i class="fas fa-user"></i> ' . htmlspecialchars($task['assigned_name']) 
                            : '-'; ?>
                    </td>
                    <td class="px-6 py-4">
                        <?php
                        $status_class = '';
                        $status_icon = '';
                        switch($task['status']) {
                            case 'belum':
                                $status_class = 'bg-gray-100 text-gray-800';
                                $status_icon = 'fa-check-circle';
                                break;
                        }
                        ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $status_class; ?>">
                            <i class="fas <?php echo $status_icon; ?> mr-1"></i> 
                            <?php echo ucfirst($task['status']); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium">
                        <a href="edit_task.php?id=<?php echo $task['id']; ?>" 
                           class="text-blue-600 hover:text-blue-900 mr-3">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete_task.php?id=<?php echo $task['id']; ?>" 
                           class="text-red-600 hover:text-red-900"
                           onclick="return confirm('Yakin ingin menghapus tugas ini?')">
                            <i class="fas fa-trash"></i> Hapus
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>