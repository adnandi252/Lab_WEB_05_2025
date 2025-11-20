<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Edit Tugas';
$error = '';
$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$manager_id = $_SESSION['user_id'];

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

$query = "SELECT t.* FROM tasks t
          JOIN projects p ON t.project_id = p.id
          WHERE t.id = $task_id AND p.manager_id = $manager_id";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = 'Tugas tidak ditemukan!';
    header('Location: tasks.php');
    exit();
}

$task = mysqli_fetch_assoc($result);
$query_projects = "SELECT id, nama_proyek FROM projects WHERE manager_id = $manager_id ORDER BY nama_proyek";
$projects = mysqli_query($conn, $query_projects);

$query_members = "SELECT id, username FROM users 
                  WHERE role = 'Team Member' AND project_manager_id = $manager_id 
                  ORDER BY username";
$members = mysqli_query($conn, $query_members);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_tugas = clean_input($_POST['nama_tugas']);
    $deskripsi = clean_input($_POST['deskripsi']);
    $project_id = clean_input($_POST['project_id']);
    $assigned_to = isset($_POST['assigned_to']) && !empty($_POST['assigned_to']) ? clean_input($_POST['assigned_to']) : NULL;
    $status = clean_input($_POST['status']);
    
    if (empty($nama_tugas) || empty($deskripsi) || empty($project_id) || empty($status)) {
        $error = 'Semua field harus diisi!';
    } else {
        $update_query = "UPDATE tasks SET 
                        nama_tugas = '$nama_tugas',
                        deskripsi = '$deskripsi',
                        project_id = $project_id,
                        assigned_to = " . ($assigned_to ? "'$assigned_to'" : "NULL") . ",
                        status = '$status'
                        WHERE id = $task_id";
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success'] = 'Tugas berhasil diupdate!';
            header('Location: tasks.php');
            exit();
        } else {
            $error = 'Gagal mengupdate tugas: ' . mysqli_error($conn);
        }
    }
}

include '../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
        <a href="tasks.php" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tugas
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Edit Tugas
        </h1>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Nama Tugas
                </label>
                <input type="text" name="nama_tugas" required 
                    value="<?php echo htmlspecialchars($task['nama_tugas']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Deskripsi
                </label>
                <textarea name="deskripsi" required rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"><?php echo htmlspecialchars($task['deskripsi']); ?></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Proyek
                </label>
                <select name="project_id" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Proyek</option>
                    <?php while ($project = mysqli_fetch_assoc($projects)): ?>
                    <option value="<?php echo $project['id']; ?>" <?php echo $task['project_id'] == $project['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($project['nama_proyek']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Ditugaskan Ke
                </label>
                <select name="assigned_to" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Belum Ditugaskan</option>
                    <?php while ($member = mysqli_fetch_assoc($members)): ?>
                    <option value="<?php echo $member['id']; ?>" <?php echo $task['assigned_to'] == $member['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($member['username']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Status Tugas
                </label>
                <select name="status" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="belum" <?php echo $task['status'] == 'belum' ? 'selected' : ''; ?>>Belum Dikerjakan</option>
                    <option value="proses" <?php echo $task['status'] == 'proses' ? 'selected' : ''; ?>>Dalam Proses</option>
                    <option value="selesai" <?php echo $task['status'] == 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                </select>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Update Tugas
                </button>
                <a href="tasks.php" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>