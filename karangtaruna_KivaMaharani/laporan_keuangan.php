<?php
// laporan_keuangan.php - ringkasan & cetak sederhana
session_start();
require 'db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){ header("Location: login.php"); exit; }
$tot_in = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='masuk'")->fetch_assoc()['total'];
$tot_out = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='keluar'")->fetch_assoc()['total'];
$saldo = $tot_in - $tot_out;
$all = $conn->query("SELECT * FROM keuangan ORDER BY tanggal DESC");
?>
<!doctype html><html><head><meta charset="utf-8"><title>Laporan Keuangan</title><link rel="stylesheet" href="css/style.css">
<style>@media print{ .nav, .btn { display:none !important; } .header{box-shadow:none;} }</style>
</head><body>
<div class="container">
  <div class="header"><div class="brand"><h1>Laporan Keuangan</h1></div><div class="nav"><a href="dashboard_admin.php">Kembali</a> <button class="btn" onclick="window.print()">Cetak</button></div></div>
  <div class="card">
    <h3>Rekap Keuangan</h3>
    <p class="small">Total Pemasukan: <strong>Rp <?=number_format($tot_in,2,',','.')?></strong></p>
    <p class="small">Total Pengeluaran: <strong>Rp <?=number_format($tot_out,2,',','.')?></strong></p>
    <p class="small">Saldo Akhir: <strong>Rp <?=number_format($saldo,2,',','.')?></strong></p>
    <hr>
    <table class="table"><thead><tr><th>Tanggal</th><th>Tipe</th><th>Jumlah</th><th>Keterangan</th></tr></thead><tbody>
    <?php while($r = $all->fetch_assoc()): ?>
      <tr><td class="small"><?=htmlspecialchars($r['tanggal'])?></td><td><?=htmlspecialchars($r['tipe'])?></td><td>Rp <?=number_format($r['jumlah'],2,',','.')?></td><td class="small"><?=htmlspecialchars($r['keterangan'])?></td></tr>
    <?php endwhile; ?>
    </tbody></table>
  </div>
  <div class="footer">Karang Taruna</div>
</div>
</body></html>
