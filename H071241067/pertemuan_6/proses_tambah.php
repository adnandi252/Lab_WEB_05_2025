<?php
require 'koneksi.php';

$nama = $_POST['nama'];
$nim = $_POST['nim'];
$jurusan = $_POST['jurusan'];

// 1. Prepare: Buat template query dengan penanda tanya (?)
$sql = "INSERT INTO mahasiswa (nama, nim, jurusan) VALUES (?, ?, ?)";
$stmt = mysqli_prepare($conn, $sql);

// 2. Bind: Ikat variabel PHP ke penanda '?' dan tentukan tipe datanya
// "sss" berarti ketiga variabel adalah string (s)
mysqli_stmt_bind_param($stmt, "sss", $nama, $nim, $jurusan);

// 3. Execute: Jalankan query yang sudah aman
if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

// Tutup statement dan koneksi
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>