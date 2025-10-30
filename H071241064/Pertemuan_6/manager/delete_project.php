<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$manager_id = (int)$_SESSION['user_id'];
$project_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT id FROM projects WHERE id = ? AND manager_id = ?");
$stmt->bind_param("ii", $project_id, $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['error'] = 'Proyek tidak ditemukan atau bukan milik Anda!';
    header('Location: projects.php');
    exit();
}

$stmt = $conn->prepare("DELETE FROM tasks WHERE project_id = ?");
$stmt->bind_param("i", $project_id);
$stmt->execute();

$stmt = $conn->prepare("DELETE FROM projects WHERE id = ?");
$stmt->bind_param("i", $project_id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Proyek dan semua tugasnya berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus proyek: ' . $stmt->error;
}

$stmt->close();
$conn->close();

header('Location: projects.php');
exit();
?>
