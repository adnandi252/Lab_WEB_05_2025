<?php
require 'koneksi.php';

$id = $_POST['id'];
$nama = $_POST['nama'];
$nim = $_POST['nim'];
$jurusan = $_POST['jurusan'];

// 1. Prepare
$sql = "UPDATE mahasiswa SET nama=?, nim=?, jurusan=? WHERE id=?";
$stmt = mysqli_prepare($conn, $sql);

// 2. Bind
// "sssi" berarti: string, string, string, integer (i)
mysqli_stmt_bind_param($stmt, "sssi", $nama, $nim, $jurusan, $id);

// 3. Execute
if (mysqli_stmt_execute($stmt)) {
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_stmt_error($stmt);
}

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>