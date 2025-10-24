<?php
// Parameter koneksi database
$hostname = "localhost";
$username = "root";
$password = "";
$database = "akademik_db"; // Ganti dengan nama databasemu
// Membuat koneksi
$conn = mysqli_connect($hostname, $username, $password, $database);
// Memeriksa koneksi
if (!$conn) {
 // Jika koneksi gagal, hentikan script dan tampilkan pesan error
 die("Koneksi gagal: " . mysqli_connect_error());
}
echo "koneksi berhasil"
?>


