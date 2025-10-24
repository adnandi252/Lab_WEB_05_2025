<?php
session_start();
require_once 'data.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$currentUser = $_SESSION['user']; // Ambil data user yang sedang login dari session
$isAdmin = ($currentUser['username'] === 'adminxxx');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #667eea;
        }
        h1 {
            color: #333;
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .logout-btn:hover {
            background: #c82333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        tr:hover {
            background: #f9f9f9;
        }
        .user-info {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .user-info p {
            margin: 10px 0;
            font-size: 16px;
        }
        .user-info strong {
            color: #667eea;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>
                <?php 
                if ($isAdmin) {
                    echo "Selamat Datang, Admin!";
                } else {
                    echo "Selamat Datang, " . $currentUser['name'] . "!";
                }
                ?>
            </h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <?php if ($isAdmin): ?>
            <h2>Data Semua Pengguna</h2>
            <table>
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Faculty</th>
                        <th>Batch</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['name']; ?></td>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo isset($user['gender']) ? $user['gender'] : '-'; ?></td>
                            <td><?php echo isset($user['faculty']) ? $user['faculty'] : '-'; ?></td>
                            <td><?php echo isset($user['batch']) ? $user['batch'] : '-'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h2>Data Anda</h2>
            <div class="user-info">
                <p><strong>Nama:</strong> <?php echo $currentUser['name']; ?></p>
                <p><strong>Username:</strong> <?php echo $currentUser['username']; ?></p>
                <p><strong>Email:</strong> <?php echo $currentUser['email']; ?></p>
                <p><strong>Gender:</strong> <?php echo isset($currentUser['gender']) ? $currentUser['gender'] : '-'; ?></p>
                <p><strong>Faculty:</strong> <?php echo isset($currentUser['faculty']) ? $currentUser['faculty'] : '-'; ?></p>
                <p><strong>Batch:</strong> <?php echo isset($currentUser['batch']) ? $currentUser['batch'] : '-'; ?></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>