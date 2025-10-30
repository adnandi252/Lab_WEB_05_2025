<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Super Admin') {
    header('Location: ../auth/login.php');
    exit();
}

$page_title = 'Edit User';
$error = '';

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = "SELECT * FROM users WHERE id = $user_id AND role != 'Super Admin'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = 'User tidak ditemukan!';
    header('Location: users.php');
    exit();
}

$user = mysqli_fetch_assoc($result);

$query_managers = "SELECT id, username FROM users WHERE role = 'Project Manager' ORDER BY username";
$managers = mysqli_query($conn, $query_managers);

function clean_input($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $role = clean_input($_POST['role']);
    $project_manager_id = isset($_POST['project_manager_id']) ? clean_input($_POST['project_manager_id']) : NULL;
    $new_password = $_POST['new_password'];
    
    if (empty($username) || empty($role)) {
        $error = 'Username dan role harus diisi!';
    } else {
        if ($role != 'Team Member') {
            $project_manager_id = NULL;
        }
        
        if (!empty($new_password)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE users SET username = '$username', password = '$hashed_password', 
                           role = '$role', project_manager_id = " . 
                           ($project_manager_id ? "'$project_manager_id'" : "NULL") . 
                           " WHERE id = $user_id";
        } else {
            $update_query = "UPDATE users SET username = '$username', role = '$role', 
                           project_manager_id = " . 
                           ($project_manager_id ? "'$project_manager_id'" : "NULL") . 
                           " WHERE id = $user_id";
        }
        
        if (mysqli_query($conn, $update_query)) {
            $_SESSION['success'] = 'User berhasil diupdate!';
            header('Location: users.php');
            exit();
        } else {
            $error = 'Gagal mengupdate user: ' . mysqli_error($conn);
        }
    }
}

include '../includes/header.php';
?>

<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex gap-4">
        <a href="users.php" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar User
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">
            Edit User
        </h1>
        
        <?php if ($error): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Username
                </label>
                <input type="text" name="username" required 
                    value="<?php echo htmlspecialchars($user['username']); ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Password Baru
                </label>
                <input type="password" name="new_password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                    placeholder="Kosongkan jika tidak ingin mengubah password">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Role
                </label>
                <select name="role" id="role" required 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="Project Manager" <?php echo $user['role'] == 'Project Manager' ? 'selected' : ''; ?>>
                        Project Manager
                    </option>
                    <option value="Team Member" <?php echo $user['role'] == 'Team Member' ? 'selected' : ''; ?>>
                        Team Member
                    </option>
                </select>
            </div>
            
            <div class="mb-6" id="manager_field" style="display: <?php echo $user['role'] == 'Team Member' ? 'block' : 'none'; ?>;">
                <label class="block text-gray-700 text-sm font-bold mb-2">
                    Project Manager
                </label>
                <select name="project_manager_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Project Manager</option>
                    <?php while ($manager = mysqli_fetch_assoc($managers)): ?>
                    <option value="<?php echo $manager['id']; ?>" 
                        <?php echo $user['project_manager_id'] == $manager['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($manager['username']); ?>
                    </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            <div class="flex gap-4">
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg transition">
                    Update User
                </button>
                <a href="users.php" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 rounded-lg text-center transition">
                    Batal
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