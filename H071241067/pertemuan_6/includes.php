<?php
// includes/auth.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

function checkRole($roles) {
    if (!in_array($_SESSION['role'], (array)$roles)) {
        echo "<p style='color:red;'>Akses ditolak. Anda tidak memiliki izin untuk halaman ini.</p>";
        exit;
    }
}
?>
