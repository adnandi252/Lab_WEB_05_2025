<?php
session_start();
require_once '../config/database.php';

if (isset($_SESSION['user_id'])) {
    switch ($_SESSION['role']) {
        case 'Super Admin':
            header('Location: ../superadmin/dashboard.php');
            break;
        case 'Project Manager':
            header('Location: ../manager/dashboard.php');
            break;
        default:
            header('Location: ../member/dashboard.php');
            break;
    }
    exit;
}

function clean_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['project_manager_id'] = $user['project_manager_id'];

        switch ($user['role']) {
            case 'Super Admin':
                header('Location: ../superadmin/dashboard.php');
                break;
            case 'Project Manager':
                header('Location: ../manager/dashboard.php');
                break;
            default:
                header('Location: ../member/dashboard.php');
                break;
        }
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Proyek</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-600 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <div class="bg-white rounded-lg overflow-hidden">
            <div class="px-8 py-10">
                <h3 class="text-2xl font-semibold text-center mb-6">Login Sistem</h3>

                <?php if ($error): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2" for="username">Username</label>
                        <input type="text" name="username" id="username" required 
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2" for="password">Password</label>
                        <input type="password" name="password" id="password" required
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                        Login
                    </button>
                </form>

                <hr class="my-6 border-gray-300">

                <small class="text-gray-500 block text-center">
                    <strong>Demo Account:</strong><br>
                    Super Admin: superadmin / admin123<br>
                    Manager: manager1 / manager123<br>
                    Member: member1 / member123
                </small>
            </div>
        </div>
    </div>
</body>
</html>