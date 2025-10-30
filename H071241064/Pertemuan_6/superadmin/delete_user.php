<?php
session_start(); 

require_once '../config/database.php';
include '../includes/header.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: ../auth/login.php');
    exit();
}

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($user_id === $_SESSION['user_id']) {
    $_SESSION['error'] = 'Anda tidak dapat menghapus akun Anda sendiri!';
    header('Location: users.php');
    exit();
}

$stmt = $conn->prepare("SELECT role FROM users WHERE id = ? AND role != 'Super Admin'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'User tidak ditemukan atau tidak dapat dihapus!';
    header('Location: users.php');
    exit();
}

$delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$delete_stmt->bind_param("i", $user_id);

if ($delete_stmt->execute()) {
    $_SESSION['success'] = 'User berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus user: ' . $conn->error;
}

header('Location: users.php');
exit();
?>