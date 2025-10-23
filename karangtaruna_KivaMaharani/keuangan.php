<?php
// keuangan.php - input & list transaksi kas (admin)
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = '';
$user = $_SESSION['user'];

// Proses Tambah Transaksi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $tipe = $conn->real_escape_string($_POST['tipe']);
        $jumlah = floatval($_POST['jumlah']);
        $ket = $conn->real_escape_string($_POST['keterangan']);
        $conn->query("INSERT INTO keuangan (tanggal, tipe, keterangan, jumlah, created_by) 
                      VALUES (NOW(), '$tipe', '$ket', $jumlah, " . intval($user['id']) . ")");
        $msg = "Transaksi disimpan.";
    }

    // Proses Hapus Transaksi
    if (isset($_POST['delete_trans'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM keuangan WHERE id = $id");
        $msg = "Transaksi berhasil dihapus.";
    }
}

// Ambil semua data transaksi
$all = $conn->query("SELECT keuangan.*, users.full_name 
                     FROM keuangan 
                     LEFT JOIN users ON keuangan.created_by = users.id 
                     ORDER BY tanggal DESC");

// Hitung Saldo
$tot_in = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='masuk'")->fetch_assoc()['total'];
$tot_out = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='keluar'")->fetch_assoc()['total'];
$saldo = $tot_in - $tot_out;
?>
<!doctype html>
<html>

<head>
 <meta charset="utf-8">
 <title>Keuangan</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Kelola Keuangan</h1>
   </div>
   <div class="nav"><a href="dashboard_admin.php">Kembali</a></div>
  </div>

  <?php if ($msg): ?>
  <div class="card small"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <!-- Form Tambah Transaksi -->
  <div class="card">
   <h3>Tambah Transaksi</h3>
   <form method="post">
    <div class="group">
     <label>Tipe</label>
     <select name="tipe" class="input">
      <option value="masuk">Masuk</option>
      <option value="keluar">Keluar</option>
     </select>
    </div>
    <div class="group">
     <label>Jumlah (Rupiah)</label>
     <input name="jumlah" type="number" step="0.01" class="input" required>
    </div>
    <div class="group">
     <label>Keterangan</label>
     <input name="keterangan" class="input">
    </div>
    <div>
     <button class="btn" name="add" type="submit">Simpan</button>
    </div>
   </form>
  </div>

  <!-- Tabel Riwayat Transaksi -->
  <div class="card">
   <h3>Riwayat Transaksi</h3>
   <div class="small">Saldo: <strong>Rp <?= number_format($saldo, 2, ',', '.') ?></strong></div>
   <table class="table">
    <thead>
     <tr>
      <th>Tanggal</th>
      <th>Tipe</th>
      <th>Jumlah</th>
      <th>Keterangan</th>
      <th>Petugas</th>
      <th>Aksi</th>
     </tr>
    </thead>
    <tbody>
     <?php while ($r = $all->fetch_assoc()): ?>
     <tr>
      <td class="small"><?= htmlspecialchars($r['tanggal']) ?></td>
      <td><?= htmlspecialchars($r['tipe']) ?></td>
      <td>Rp <?= number_format($r['jumlah'], 2, ',', '.') ?></td>
      <td class="small"><?= htmlspecialchars($r['keterangan']) ?></td>
      <td class="small"><?= htmlspecialchars($r['full_name']) ?></td>
      <td>
       <form method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
        <input type="hidden" name="id" value="<?= intval($r['id']) ?>">
        <button class="btn btn-danger" name="delete_trans" type="submit">Hapus</button>
       </form>
      </td>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>

  <div class="footer">Karang Taruna</div>
 </div>
</body>

</html>

<?php $conn->close(); ?>