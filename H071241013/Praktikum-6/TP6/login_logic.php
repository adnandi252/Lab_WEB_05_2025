<?php
session_start();
require "connect.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: login.php"); exit;
}

$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

$sql = "SELECT id, username, password, role FROM users WHERE username = ?";
$stmt = mysqli_prepare($connect, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($res);

$ok = false;
if ($user) {
  // cocokkan bcrypt
  if (password_verify($password, $user["password"])) {
    $ok = true;
  } else {
    // fallback praktikum kalau seed pakai plain
    if (hash_equals($user["password"], $password)) {
      $ok = true;
    }
  }
}

if ($ok) {
  $_SESSION["user_id"] = (int)$user["id"];
  $_SESSION["username"] = $user["username"];
  $_SESSION["role"] = $user["role"];
  session_regenerate_id(true);
  header("Location: halaman_utama.php");
  exit;
}

echo "Login gagal. Cek username/password. <a href='login.php'>Kembali</a>";
