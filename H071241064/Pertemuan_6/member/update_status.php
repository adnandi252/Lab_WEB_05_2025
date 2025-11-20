<?php
session_start();
require_once '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Team Member') {
    header('Location: ../auth/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['task_id']) || empty($_POST['new_status'])) {
        $_SESSION['error'] = 'Data tidak lengkap!';
        header('Location: tasks.php');
        exit();
    }

    $task_id = intval($_POST['task_id']);
    $new_status = trim($_POST['new_status']);
    $member_id = intval($_SESSION['user_id']);

    $valid_status = ['belum', 'proses', 'selesai'];
    if (!in_array($new_status, $valid_status, true)) {
        $_SESSION['error'] = 'Status tidak valid!';
        header('Location: tasks.php');
        exit();
    }

    $check_stmt = $conn->prepare("SELECT id FROM tasks WHERE id = ? AND assigned_to = ?");
    $check_stmt->bind_param("ii", $task_id, $member_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows === 0) {
        $_SESSION['error'] = 'Tugas tidak ditemukan atau bukan milik Anda!';
        header('Location: tasks.php');
        exit();
    }

    $update_stmt = $conn->prepare("
        UPDATE tasks 
        SET status = ?
        WHERE id = ? AND assigned_to = ?
    ");
    $update_stmt->bind_param("sii", $new_status, $task_id, $member_id);

    if ($update_stmt->execute()) {
        $_SESSION['success'] = 'Status tugas berhasil diupdate!';
    } else {
        $_SESSION['error'] = 'Gagal mengupdate status.';
    }

    $update_stmt->close();
    $check_stmt->close();
} else {
    $_SESSION['error'] = 'Metode tidak valid!';
}

header('Location: tasks.php');
exit();
?>
