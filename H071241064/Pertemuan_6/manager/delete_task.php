<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Project Manager') {
    header('Location: ../auth/login.php');
    exit();
}

$task_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$manager_id = $_SESSION['user_id'];

$check_query = "SELECT t.id FROM tasks t
                JOIN projects p ON t.project_id = p.id
                WHERE t.id = $task_id AND p.manager_id = $manager_id";
$check_result = mysqli_query($conn, $check_query);

if (mysqli_num_rows($check_result) == 0) {
    $_SESSION['error'] = 'Tugas tidak ditemukan!';
    header('Location: tasks.php');
    exit();
}

$delete_query = "DELETE FROM tasks WHERE id = $task_id";

if (mysqli_query($conn, $delete_query)) {
    $_SESSION['success'] = 'Tugas berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus tugas: ' . mysqli_error($conn);
}

header('Location: tasks.php');
exit();
?>
