<?php
require_once "connection.php";

// 1. Ambil ID dari URL
$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit();
}

// 2. Ambil data lama dari database berdasarkan ID
$query  = "SELECT * FROM money_tracker WHERE id_transaction = '$id'";
$result = mysqli_query($conn, $query);
$data   = mysqli_fetch_assoc($result);
print_r($data); // Debugging: Tampilkan data yang diambil dari database
// Jika ID tidak ditemukan di database
if (!$data) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="css/form_transaction.css">
</head>
<body>
<div class="container">
    <h2>Edit Transaksi</h2>

    <form action="process.php" method="POST">
        <!-- Hidden input untuk menyimpan ID transaksi -->
        <input type="hidden" name="id_transaction" value="<?= $data['id_transaction']; ?>">

        <div>
            <label for="amount">Jumlah (Rp):</label>
            <input type="number" id="amount" name="amount" value="<?= $data['amount']; ?>" required>
        </div><br>

        <div>
            <label for="type">Tipe Transaksi:</label>
            <select id="type" name="type" required>
                <option value="income" <?= $data['type'] == 'income' ? 'selected' : ''; ?>>Pemasukan (Income)</option>
                <option value="expense" <?= $data['type'] == 'expense' ? 'selected' : ''; ?>>Pengeluaran (Expense)</option>
            </select>
        </div><br>

        <div>
            <label for="category">Kategori:</label>
            <input type="text" id="category" name="category" value="<?= $data['category']; ?>" required>
        </div><br>

        <div>
            <label for="date">Tanggal:</label>
            <input type="date" id="date" name="date" value="<?= $data['date']; ?>" required>
        </div><br>


        <button type="submit" name="submit" class="btn-submit">Simpan Perubahan</button>
        <a href="index.php" class="btn-back">Batal</a>
    </form>
</div>
</body>
</html>