<?php
// 1. Koneksi ke Database
$host = "localhost";
$user = "root";
$password = "";
$database = "money_tracker_ben"; // Nama database kamu

$conn = mysqli_connect($host, $user, $password, $database);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}


?>