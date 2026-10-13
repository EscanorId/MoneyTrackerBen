

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Transaksi - Money Tracker</title>
    <link rel="stylesheet" href="css/form_transaction.css">
</head>
<body>

<div class="container">
    <h2>Tambah Transaksi</h2>

    <?php if (!empty($pesan_error)): ?>
        <div class="alert-error"><?= $pesan_error; ?></div>
    <?php endif; ?>

    <form action="process.php" method="POST">
        <div class="form-group">
            <label for="amount">Jumlah (Rp)</label>
            <input type="number" id="amount" name="amount" placeholder="Contoh: 50000" required>
        </div>

        <div class="form-group">
            <label for="type">Tipe Transaksi</label>
            <select id="type" name="type" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="income">Pemasukan (Income)</option>
                <option value="expense">Pengeluaran (Expense)</option>
            </select>
        </div>

        <div class="form-group">
            <label for="category">Kategori</label>
            <input type="text" id="category" name="category" placeholder="Contoh: Makanan, Gaji, Transport" required>
        </div>

        <div class="form-group">
            <label for="date">Tanggal</label>
            <input type="date" id="date" name="date" value="<?= date('Y-m-d'); ?>" required>
        </div>

        <button type="submit" name="submit" class="btn-submit">Simpan Transaksi</button>
        <a href="index.php" class="btn-back">← Kembali ke Daftar Transaksi</a>
    </form>
</div>

</body>
</html>