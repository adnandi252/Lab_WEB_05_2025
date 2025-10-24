<?php
require 'koneksi.php';

$id = $_GET['id'];
$id = mysqli_real_escape_string($conn, $id);
$sql = "DELETE FROM mahasiswa WHERE id='$id'";

if (mysqli_query($conn, $sql)) {
    header("Location: index.php");
} else {
    echo "Error: " . mysqli_error($conn);
}

mysqli_close($conn);
?>