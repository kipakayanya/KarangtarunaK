<?php
// dashboard_admin.php
session_start();
require 'db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){
    header("Location: login.php");
    exit;
}

$anggota = $conn->query("SELECT * FROM anggota ORDER BY nama");
$kas_in = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='masuk'")->fetch_assoc()['total'];
$kas_out = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='keluar'")->fetch_assoc()['total'];
$saldo = $kas_in - $kas_out;
$user = $_SESSION['user'];
$msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['delete_ang'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM anggota WHERE id=$id");
        $msg = "Anggota dihapus.";
    }
}
?>

<!doctype html>
<html>

<head>
 <meta charset="utf-8">
 <title>Admin Dashboard</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Admin - Karang Taruna</h1>
    <div class="small"><?=htmlspecialchars($user['full_name'])?></div>
   </div>
   <div class="nav"><a href="index.php">Publik</a> <a href="logout.php">Logout</a></div>
  </div>

  <?php if($err): ?><div class="alert"><?=htmlspecialchars($err)?></div><?php endif; ?>
  <?php if($msg): ?><div class="card small"><?=htmlspecialchars($msg)?></div><?php endif; ?>

  <div class="grid">
   <div>
    <div class="card">
     <h3>Kelola Anggota</h3>
     <p><a class="btn" href="anggota.php">Tambah / Kelola Anggota</a></p>
     <table class="table">
      <thead>
       <tr>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>NO HP</th>
        <th>AKSI</th>
       </tr>
      </thead>
      <tbody>
       <?php while($r = $anggota->fetch_assoc()): ?>
       <tr>
        <td><?=htmlspecialchars($r['nama'])?></td>
        <td class="small"><?=htmlspecialchars($r['jabatan'])?></td>
        <td><?=htmlspecialchars($r['phone'])?></td>
        <td>
         <form method="post" style="display:inline">
          <input type="hidden" name="id" value="<?=intval($r['id'])?>">
          <button class="btn btn-danger" name="delete_ang" type="submit">Hapus</button>
         </form>
        </td>
       </tr>
       <?php endwhile; ?>
      </tbody>
     </table>
    </div>



   </div>

   <div class="card">
    <h3>Kelola Kegiatan</h3>
    <p><a class="btn" href="kegiatan.php">Tambah / Kelola Kegiatan</a></p>
   </div>



   <div>
    <div class="card">
     <h3>Uang Kas</h3>
     <div class="small">Saldo saat ini: <strong>Rp <?=number_format($saldo,2,',','.')?></strong></div>
     <p><a class="btn" href="keuangan.php">Kelola Kas</a></p>
     <p><a class="btn" href="laporan_keuangan.php">Lihat Laporan</a></p>
    </div>



    <div class="footer">Admin — Karang Taruna</div>
   </div>
</body>

</html>