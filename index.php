<?php
// 1. Aktifkan error reporting untuk debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Sertakan koneksi database
require_once 'connection.php';

// 3. Tangkap semua parameter dari URL (Metode GET)
$keyword = $_GET['keyword'] ?? '';
$filter_type = $_GET['type'] ?? '';
$sort_by = $_GET['sort'] ?? 'newest';

// Konfigurasi Pagination
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = ($page > 0) ? $page : 1; // Pastikan halaman minimal 1
$offset = ($page - 1) * $limit;

// 4. Siapkan array untuk menampung kondisi filter SQL secara dinamis
$conditions = [];

// A. Logika Pencarian (Keyword)
if (!empty($keyword)) {
    $keyword_escaped = mysqli_real_escape_string($conn, $keyword);
    $conditions[] = "(category LIKE '%$keyword_escaped%' OR type LIKE '%$keyword_escaped%' OR id_transaction LIKE '%$keyword_escaped%')";
}

// B. Logika Filter Tipe (Income / Expense)
if (!empty($filter_type)) {
    $filter_type_escaped = mysqli_real_escape_string($conn, $filter_type);
    $conditions[] = "type = '$filter_type_escaped'";
}

// 5. Susun Klausa WHERE secara dinamis jika ada kondisi
$where_clause = "";
if (count($conditions) > 0) {
    $where_clause = " WHERE " . implode(" AND ", $conditions);
}

// ==========================================
// 6. QUERY AGREGASI / RINGKASAN DATA (DASHBOARD)
// ==========================================
$query_income = "SELECT SUM(amount) AS total_income FROM money_tracker" . $where_clause . (empty($where_clause) ? " WHERE type = 'income'" : " AND type = 'income'");
$result_income = mysqli_query($conn, $query_income);
$data_income = mysqli_fetch_assoc($result_income);
$total_income = $data_income['total_income'] ?? 0;

$query_expense = "SELECT SUM(amount) AS total_expense FROM money_tracker" . $where_clause . (empty($where_clause) ? " WHERE type = 'expense'" : " AND type = 'expense'");
$result_expense = mysqli_query($conn, $query_expense);
$data_expense = mysqli_fetch_assoc($result_expense);
$total_expense = $data_expense['total_expense'] ?? 0;

$net_balance = $total_income - $total_expense;

// ==========================================
// 7. HITUNG TOTAL DATA UNTUK PAGINATION
// ==========================================
$query_count = "SELECT COUNT(*) AS total_data FROM money_tracker" . $where_clause;
$result_count = mysqli_query($conn, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total_data'];
$total_pages = ceil($total_data / $limit); // Bulatkan ke atas total halaman

// ==========================================
// 8. SUSUN QUERY UTAMA, SORTING, & LIMIT
// ==========================================
$query = "SELECT * FROM money_tracker" . $where_clause;

// Aturan Sorting (Pengurutan)
switch ($sort_by) {
    case 'oldest':
        $query .= " ORDER BY id_transaction ASC";
        break;
    case 'highest':
        $query .= " ORDER BY amount DESC";
        break;
    case 'lowest':
        $query .= " ORDER BY amount ASC";
        break;
    case 'newest':
    default:
        $query .= " ORDER BY id_transaction DESC";
        break;
}

// Tambahkan LIMIT dan OFFSET untuk pembatasan halaman
$query .= " LIMIT $limit OFFSET $offset";

// 9. Eksekusi query utama ke database
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Money Tracker - Dashboard, Filter, Sorting & Pagination</title>
    <link rel="stylesheet" href="css/view_transaction.css">
    <link rel="stylesheet" href="css/styles.css">
    <style>

    </style>
</head>

<body>
    <h2>Dashboard & Daftar Transaksi</h2>

    <a href="form_transaction.php">Tambah Transaksi Baru</a>

    <!-- TAMPILAN DASHBOARD CARDS (AGREGASI) -->
    <div class="dashboard-cards">
        <div class="card income">
            <h4>Total Pemasukan</h4>
            <p>Rp <?= number_format($total_income, 0, ',', '.'); ?></p>
        </div>
        <div class="card expense">
            <h4>Total Pengeluaran</h4>
            <p>Rp <?= number_format($total_expense, 0, ',', '.'); ?></p>
        </div>
        <div class="card balance">
            <h4>Sisa Saldo (Net Balance)</h4>
            <p>Rp <?= number_format($net_balance, 0, ',', '.'); ?></p>
        </div>
    </div>

    <!-- Form Pencarian, Filter, & Sorting -->
    <form action="index.php" method="GET" style="margin: 20px 0; display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <input type="text" name="keyword" placeholder="Cari kategori, tipe, ID..." value="<?= htmlspecialchars($keyword); ?>" style="padding: 6px; width: 200px;">

        <select name="type" style="padding: 6px;">
            <option value="">-- Semua Tipe --</option>
            <option value="income" <?= ($filter_type === 'income') ? 'selected' : ''; ?>>Pemasukan (Income)</option>
            <option value="expense" <?= ($filter_type === 'expense') ? 'selected' : ''; ?>>Pengeluaran (Expense)</option>
        </select>

        <select name="sort" style="padding: 6px;">
            <option value="newest" <?= ($sort_by === 'newest') ? 'selected' : ''; ?>>Terbaru</option>
            <option value="oldest" <?= ($sort_by === 'oldest') ? 'selected' : ''; ?>>Terlama</option>
            <option value="highest" <?= ($sort_by === 'highest') ? 'selected' : ''; ?>>Jumlah Terbesar</option>
            <option value="lowest" <?= ($sort_by === 'lowest') ? 'selected' : ''; ?>>Jumlah Terkecil</option>
        </select>

        <button type="submit" style="padding: 6px 12px;">Terapkan</button>

        <?php if (!empty($keyword) || !empty($filter_type) || $sort_by !== 'newest'): ?>
            <a href="index.php" style="color: red; text-decoration: none; margin-left: 5px;">Reset Filter</a>
        <?php endif; ?>
    </form>

    <!-- Informasi Status Aktif -->
    <?php if (!empty($keyword) || !empty($filter_type)): ?>
        <p style="font-style: italic;">
            Menampilkan data
            <?= !empty($filter_type) ? 'dengan tipe <b>' . ucfirst($filter_type) . '</b>' : ''; ?>
            <?= (!empty($keyword) && !empty($filter_type)) ? 'dan' : ''; ?>
            <?= !empty($keyword) ? 'pencarian <b>"' . htmlspecialchars($keyword) . '"</b>' : ''; ?>
            (Total ditemukan: <?= $total_data; ?> data)
        </p>
    <?php endif; ?>

    <!-- Tabel Data Transaksi -->
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; margin-top: 15px;">
        <thead>
            <tr style="background-color: #f4f4f4;">
                <th>ID</th>
                <th>Jumlah (Rp)</th>
                <th>Tipe</th>
                <th>Kategori</th>
                <th>Tanggal</th>
                <th colspan="2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (isset($result) && mysqli_num_rows($result) > 0): ?>
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
                    <td colspan="7" style="text-align: center; color: #777;">Data transaksi tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- ========================================== -->
    <!-- TAMPILAN TOMBOL PAGINATION -->
    <!-- ========================================== -->
    <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php
            // Mempertahankan parameter URL (keyword, type, sort) saat tombol halaman diklik
            $query_params = [];
            if (!empty($keyword)) $query_params['keyword'] = $keyword;
            if (!empty($filter_type)) $query_params['type'] = $filter_type;
            if (!empty($sort_by)) $query_params['sort'] = $sort_by;
            ?>

            <!-- Tombol Previous -->
            <?php if ($page > 1): ?>
                <?php $query_params['page'] = $page - 1; ?>
                <a href="index.php?<?= http_build_query($query_params); ?>">&laquo; Prev</a>
            <?php endif; ?>

            <!-- Nomor Halaman -->
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php $query_params['page'] = $i; ?>
                <?php if ($i == $page): ?>
                    <span class="active"><?= $i; ?></span>
                <?php else: ?>
                    <a href="index.php?<?= http_build_query($query_params); ?>"><?= $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Tombol Next -->
            <?php if ($page < $total_pages): ?>
                <?php $query_params['page'] = $page + 1; ?>
                <a href="index.php?<?= http_build_query($query_params); ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</body>

</html>