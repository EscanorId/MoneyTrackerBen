<?php
require_once 'connection.php';

// 2. Ambil data dari tabel 'moneytracker'
$query = "SELECT * FROM money_tracker";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Money Tracker</title>
    <style>
        
    </style>
    <link rel="stylesheet" href="css/view_transaction.css">
</head>
<body>
    <h2>Daftar Transaksi Money Tracker</h2>
    <a href="form_transaction.php">Tambah Transaksi Baru</a>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Jumlah (Rp)</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id_transaction']; ?></td>
                        <td>Rp <?= number_format($row['amount'], 0, ',', '.'); ?></td>
                        <td class="<?= $row['type']; ?>"><?= ucfirst($row['type']); ?></td>
                        <td><?= $row['category']; ?></td>
                        <td><?= date('d-m-Y', strtotime($row['date'])); ?></td>
                        <td><a href="form_transaction.php?id=<?= $row['id_transaction']; ?>">Edit</a></td>
                        <td><a href="delete_transaction.php?id=<?= $row['id_transaction']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">Hapus</a></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Belum ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>
