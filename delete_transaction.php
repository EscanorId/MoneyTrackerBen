<?php
// 1. Panggil koneksi database
require_once "connection.php";

// 2. Ambil ID dari URL
$id = $_GET['id'] ?? null;

// 3. Validasi apakah ID ada
if ($id) {
    // Amankan ID dari SQL Injection
    $id = mysqli_real_escape_string($conn, $id);

    // 4. Buat query DELETE (PENTING: Selalu gunakan WHERE agar tidak terhapus semua!)
    $query = "DELETE FROM money_tracker WHERE id_transaction = '$id'";
    $result = mysqli_query($conn, $query);

    // 5. Cek apakah query berhasil
    if ($result) {
        // Redirect kembali ke index.php setelah berhasil hapus
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
} else {
    // Jika tidak ada ID di URL, kembalikan ke index.php
    header("Location: index.php");
    exit();
}
?>