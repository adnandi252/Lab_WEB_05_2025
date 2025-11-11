<?php 

$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_manajemen_proyek";

$connect = mysqli_connect($hostname, $username, $password, $database);

if(!$connect){
    die("Koneksi Gagal" . mysqli_connect_error());
}
mysqli_set_charset($connect, "utf8");
?>