<?php
session_start();
require_once 'data.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    $_SESSION['error'] = 'Username dan password harus diisi!';
    header('Location: login.php');
    exit;
}

$userFound = null;
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $userFound = $user;
        break;
    }
}

if ($userFound === null) {
    $_SESSION['error'] = 'Username atau password salah!';
    header('Location: login.php');
    exit;
}

if (password_verify($password, $userFound['password'])) {
    $_SESSION['user'] = $userFound;
    header('Location: dashboard.php');
    exit;
} else {
    $_SESSION['error'] = 'Username atau password salah!';
    header('Location: login.php');
    exit;
}
?>