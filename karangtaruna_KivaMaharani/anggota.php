<?php
// anggota.php - CRUD sederhana untuk anggota
session_start();
require 'db.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin'){ header("Location: login.php"); exit; }
$msg=''; $err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['add'])){
        $nik = $conn->real_escape_string($_POST['nik']);
        $nama = $conn->real_escape_string($_POST['nama']);
        $ttl = $conn->real_escape_string($_POST['ttl']);
        $jk = $conn->real_escape_string($_POST['jk']);
        $alamat = $conn->real_escape_string($_POST['alamat']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $jabatan = $conn->real_escape_string($_POST['jabatan']);
        $conn->query("INSERT INTO anggota (nik,nama,ttl,jenis_kelamin,alamat,phone,jabatan) VALUES ('$nik','$nama','$ttl','$jk','$alamat','$phone','$jabatan')");
        $msg = "Anggota ditambahkan.";
    } elseif(isset($_POST['update'])){
        $id = intval($_POST['id']);
        $nama = $conn->real_escape_string($_POST['nama']);
        $jabatan = $conn->real_escape_string($_POST['jabatan']);
        $phone = $conn->real_escape_string($_POST['phone']);
        $conn->query("UPDATE anggota SET nama='$nama', jabatan='$jabatan', phone='$phone' WHERE id=$id");
        $msg = "Anggota diperbarui.";
    }
}
$all = $conn->query("SELECT * FROM anggota ORDER BY nama");
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
 <title>Kelola Anggota</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Kelola Anggota</h1>
   </div>
   <div class="nav"><a href="dashboard_admin.php">Kembali</a></div>
  </div>
  <?php if($msg): ?><div class="card small"><?=htmlspecialchars($msg)?></div><?php endif; ?>
  <div class="card">
   <h3>Tambah Anggota</h3>
   <form method="post">
    <div class="group"><label>NIK / Nomor Anggota</label><input name="nik" class="input" required></div>
    <div class="group"><label>Nama</label><input name="nama" class="input" required></div>
    <div class="group"><label>Tempat / Tanggal Lahir</label><input name="ttl" class="input"></div>
    <div class="group"><label>Jenis Kelamin</label><select name="jk" class="input">
      <option value='L'>L</option>
      <option value='P'>P</option>
     </select></div>
    <div class="group"><label>Alamat</label><input name="alamat" class="input"></div>
    <div class="group"><label>Nomor HP</label><input name="phone" class="input"></div>
    <div class="group"><label>Jabatan</label><select name="jabatan" class="input">
      <option value='ketua'>ketua</option>
      <option value='sekertaris'>sekertaris</option>
      <option value='bendahara'>bendahara</option>
      <option value='anggota'>anggota</option>
     </select></div>

    <div><button class="btn" name="add" type="submit">Tambah Anggota</button></div>
   </form>
  </div>

  <div class="card">
   <h3>Daftar Anggota</h3>
   <table class="table">
    <thead>
     <tr>
      <th>NIK</th>
      <th>Nama</th>
      <th>Jabatan</th>
      <th>HP</th>
      <th>Aksi</th>
     </tr>
    </thead>
    <tbody>
     <?php while($r = $all->fetch_assoc()): ?>
     <tr>
      <td><?=htmlspecialchars($r['nik'])?></td>
      <td><?=htmlspecialchars($r['nama'])?></td>
      <td class="small"><?=htmlspecialchars($r['jabatan'])?></td>
      <td><?=htmlspecialchars($r['phone'])?></td>
      <td>
       <form method="post" style="display:inline">
        <input type="hidden" name="id" value="<?=intval($r['id'])?>">
        <button class="btn btn-danger" name="delete_ang" type="submit">Hapus</button>
       </form>
       <form method="post" style="display:inline">
        <input type="hidden" name="id" value="<?=intval($r['id'])?>">
        <input type="hidden" name="nama" value="<?=htmlspecialchars($r['nama'])?>">
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