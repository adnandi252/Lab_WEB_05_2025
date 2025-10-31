<?php
session_start();
require 'data.php';

$username = $_POST['username'];
$password = $_POST['password'];

$userketemu = null;
foreach ($users as $user) {
    if ($user['username'] === $username) {
        $userketemu = $user;
        break;
    }
}

if ($userketemu && password_verify($password, $userketemu['password'])) {
    $_SESSION['user'] = $userketemu;
    header("Location: dashboard.php");
    exit;
} else {
    $_SESSION['error'] = "Username atau password salah!";
    header("Location: login.php");
    exit;
}
?>
