<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_manajemen_proyek";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>