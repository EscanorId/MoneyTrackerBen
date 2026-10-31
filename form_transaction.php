<?php
require_once "connection.php";

// 1. Cek apakah ini mode Edit (ada ID di URL) atau Mode Tambah
$id = $_GET['id'] ?? null;

// Inisialisasi variabel default (untuk mode Tambah Baru)
$data = [
    'id_transaction' => '',
    'amount'         => '',
    'type'           => '',
    'category'       => '',
    'date'           => date('Y-m-d')
];

$title_page = "Tambah Transaksi";

// 2. Jika ada ID, ambil data lama (mode Edit)
if ($id) {
    $id = mysqli_real_escape_string($conn, $id);
    $query  = "SELECT * FROM money_tracker WHERE id_transaction = '$id'";
    $result = mysqli_query($conn, $query);
    $fetched_data = mysqli_fetch_assoc($result);

    if ($fetched_data) {
        $data = $fetched_data; // Timpa nilai default dengan data dari database
        $title_page = "Edit Transaksi";
    } else {
        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title_page; ?> - Money Tracker</title>
    <link rel="stylesheet" href="css/form_transaction.css">
</head>
<body>

<div class="container">
    <h2><?= $title_page; ?></h2>

    <form action="process.php" method="POST">
        <!-- Input ID tersembunyi (berisi ID jika Edit, kosong jika Tambah) -->
        <input type="hidden" name="id_transaction" value="<?= $data['id_transaction']; ?>">

        <div class="form-group">
            <label for="amount">Jumlah (Rp)</label>
            <input type="number" id="amount" name="amount" placeholder="Contoh: 50000" value="<?= $data['amount']; ?>" required>
        </div>

        <div class="form-group">
            <label for="type">Tipe Transaksi</label>
            <select id="type" name="type" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="income" <?= $data['type'] == 'income' ? 'selected' : ''; ?>>Pemasukan (Income)</option>
                <option value="expense" <?= $data['type'] == 'expense' ? 'selected' : ''; ?>>Pengeluaran (Expense)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="category">Kategori</label>
            <input type="text" id="category" name="category" placeholder="Contoh: Makanan, Gaji, Transport" value="<?= $data['category']; ?>" required>
        </div>

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" id="date" name="date" value="<?= $data['date']; ?>" required>
        </div>

        <button type="submit" name="submit" class="btn-submit">
            <?= $id ? 'Simpan Perubahan' : 'Tambah Transaksi'; ?>
        </button>
        <a href="index.php" class="btn-back">← Kembali ke Daftar Transaksi</a>
    </form>
</div>

</body>
</html>