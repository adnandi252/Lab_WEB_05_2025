<?php 
require 'koneksi.php';

echo "<h1>Menambahkan Data Mahasiswa</h1>";

$sql = "INSERT INTO mahasiswa (nama, nim, jurusan) VALUES ('suci', 'H071241009', 'Sistem Informasi')";

if (mysqli_query($conn, $sql)) {
    echo "Data berhasil ditambahkan";
} else {
    echo "Error, data tidak berhasil ditambahkan";
}

mysqli_close($conn);
?>