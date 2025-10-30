<?php
session_start(); 

require_once __DIR__ . '/../config/database.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Tambah User';
$error = '';
$success = '';

if (!function_exists('clean_input')) {
    function clean_input($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }
}

$query_managers = "SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username";
$managers = mysqli_query($conn, $query_managers);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    $role = clean_input($_POST['role']);
    $project_manager_id = isset($_POST['project_manager_id']) ? intval($_POST['project_manager_id']) : null;
    
    if (empty($username) || empty($password) || empty($role)) {
        $error = 'Semua field harus diisi!';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            if ($role !== 'Team Member') {
                $project_manager_id = null;
            }

            $stmt_insert = $conn->prepare(
                "INSERT INTO users (username, password, role, project_manager_id) VALUES (?, ?, ?, ?)"
            );
            $stmt_insert->bind_param("sssi", $username, $hashed_password, $role, $project_manager_id);
            
            if ($stmt_insert->execute()) {
                $_SESSION['success'] = 'User berhasil ditambahkan!';
                header('Location: users.php');
                exit();
            } else {
                $error = 'Gagal menambahkan user: ' . $conn->error;
            }

            $stmt_insert->close();
        }
        $stmt->close();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex gap-4">
        <a href="dashboard.php" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
            <i class="fas fa-home mr-2"></i> Dashboard
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-user-plus"></i> Tambah User Baru
        </h1>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> <?= $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-user"></i> Username *
                </label>
                <input type="text" name="username" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan username">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-lock"></i> Password *
                </label>
                <input type="password" name="password" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Masukkan password">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-user-tag"></i> Role *
                </label>
                <select name="role" id="role" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Role</option>
                    <option value="Project Manager">Project Manager</option>
                    <option value="Team Member">Team Member</option>
                </select>
            </div>
            
            <div class="mb-6" id="manager_field" style="display: none;">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    <i class="fas fa-user-tie"></i> Project Manager *
                </label>
                <select name="project_manager_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Project Manager</option>
                    <?php while ($manager = mysqli_fetch_assoc($managers)): ?>
                    <option value="<?= $manager['id']; ?>">
                        <?= htmlspecialchars($manager['username']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    <i class="fas fa-save"></i> Simpan User
                </button>
                <a href="users.php" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('role').addEventListener('change', function() {
    const managerField = document.getElementById('manager_field');
    if (this.value === 'Team Member') {
        managerField.style.display = 'block';
        managerField.querySelector('select').required = true;
    } else {
        managerField.style.display = 'none';
        managerField.querySelector('select').required = false;
    }
});
</script>