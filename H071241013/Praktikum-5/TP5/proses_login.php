<?php
session_start();
require 'data.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

$found = false;

foreach ($users as $user) {
    if ($user['username'] === $username && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $found = true;
        ($found) {
            header("Location: dashboard.php");
            exit;
        }
    }
}

(!$found) {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit;
}
?>
