<?php
// 1. Panggil file koneksi database
require_once 'connection.php';

// ==========================================
// KONDISI 1: PROSES DELETE (Via Method GET dari URL)
// ==========================================
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Query DELETE data
    $query = "DELETE FROM money_tracker WHERE id_transaction = '$id'";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php");
        exit();
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
        exit();
    }
}

// ==========================================
// KONDISI 2: PROSES INSERT / UPDATE (Via Method POST dari Form)
// ==========================================
if (isset($_POST['submit'])) {
    
    // Ambil dan amankan data input dari form
    $id       = mysqli_real_escape_string($conn, $_POST['id_transaction'] ?? '');
    $amount   = mysqli_real_escape_string($conn, $_POST['amount'] ?? '');
    $type     = mysqli_real_escape_string($conn, $_POST['type'] ?? '');
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    $date     = mysqli_real_escape_string($conn, $_POST['date'] ?? '');

    // Validasi field utama tidak boleh kosong
    if (!empty($amount) && !empty($type) && !empty($category) && !empty($date)) {
        
        // Pengecekan: Jika $id ada isinya -> UPDATE, jika kosong -> INSERT
        if (!empty($id)) {
            // Query UPDATE Data
            $query = "UPDATE money_tracker SET 
                        amount = '$amount', 
                        type = '$type', 
                        category = '$category', 
                        date = '$date' 
                      WHERE id_transaction = '$id'";
        } else {
            // Query INSERT Data Baru (Catatan: Perbaiki kurung tutup yang kurang di kodingan kamu sebelumnya)
            $query = "INSERT INTO money_tracker (amount, type, category, date) 
                      VALUES ('$amount', '$type', '$category', '$date')";
        }

        // Eksekusi Query
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            echo "Gagal memproses data: " . mysqli_error($conn);
        }

    } else {
        echo "Semua field wajib diisi!";
    }

} else {
    // Jika file ini diakses langsung tanpa melalui form POST atau parameter GET id
    header("Location: index.php");
    exit();
}
?>