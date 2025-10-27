<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectIfNotLoggedIn() {
    if (!isLoggedIn()) {
        header("Location: ../login.php");
        exit();
    }
}

function redirectIfNotAuthorized($requiredRole) {
    redirectIfNotLoggedIn();
    if ($_SESSION['role'] !== $requiredRole) {
        header("Location: ../dashboard.php");
        exit();
    }
}

?>