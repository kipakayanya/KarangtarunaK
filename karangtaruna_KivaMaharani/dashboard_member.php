<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$msg = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tambah catatan
    if (isset($_POST['add_note'])) {
        $judul = $conn->real_escape_string($_POST['judul']);
        $isi = $conn->real_escape_string($_POST['isi']);
        $uid = intval($user['id']);
        $conn->query("INSERT INTO catatan (user_id, judul, isi) VALUES ($uid, '$judul', '$isi')");
        $msg = "Catatan tersimpan.";
    }

    // Hapus catatan
    if (isset($_POST['delete_note'])) {
        $id = intval($_POST['id']);
        $uid = intval($user['id']);
        $conn->query("DELETE FROM catatan WHERE id=$id AND user_id=$uid");
        $msg = "Catatan dihapus.";
    }
}

$anggota = $conn->query("SELECT nama,jabatan,phone FROM anggota ORDER BY nama");
$kas_in = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='masuk'")->fetch_assoc()['total'];
$kas_out = $conn->query("SELECT COALESCE(SUM(jumlah),0) as total FROM keuangan WHERE tipe='keluar'")->fetch_assoc()['total'];
$saldo = $kas_in - $kas_out;

$my_notes = $conn->query("SELECT * FROM catatan WHERE user_id=".intval($user['id'])." ORDER BY created_at DESC");
?>
<!doctype html>
<html>

<head>
 <meta charset="utf-8">
 <title>Member Dashboard</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Member - Karang Taruna</h1>
    <div class="small"><?= htmlspecialchars($user['full_name']) ?></div>
   </div>
   <div class="nav"><a href="index.php">Publik</a> <a href="logout.php">Logout</a></div>
  </div>

  <?php if($err): ?><div class="alert"><?= htmlspecialchars($err) ?></div><?php endif; ?>
  <?php if($msg): ?><div class="card small"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <div class="grid">
   <div>
    <div class="card">
     <h3>Tambah Catatan</h3>
     <form method="post">
      <div class="group"><label>Judul</label><input name="judul" class="input" required></div>
      <div class="group"><label>Isi</label><textarea name="isi" rows="6" class="input" required></textarea></div>
      <div><button class="btn" name="add_note" type="submit">Simpan Catatan</button></div>
     </form>
    </div>

    <div class="card">
     <h3>Catatan Saya</h3>
     <?php if ($my_notes && $my_notes->num_rows > 0): ?>
     <?php while ($n = $my_notes->fetch_assoc()): ?>
     <div style="margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:10px">
      <strong><?= htmlspecialchars($n['judul']) ?></strong>
      <div class="small"><?= htmlspecialchars($n['created_at']) ?></div>
      <div class="small"><?= nl2br(htmlspecialchars(substr($n['isi'], 0, 400))) ?></div>
      <div style="margin-top:5px;">
       <a class="btn" href="edit_catatan.php?id=<?= intval($n['id']) ?>">Edit</a>
       <form method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
        <input type="hidden" name="id" value="<?= intval($n['id']) ?>">
        <button class="btn btn-danger" name="delete_note" type="submit">Hapus</button>
       </form>
      </div>
     </div>
     <?php endwhile; ?>
     <?php else: ?>
     <div class="small">Belum ada catatan.</div>
     <?php endif; ?>
    </div>
   </div>

   <div>
    <div class="card">
     <h3>Daftar Anggota</h3>
     <table class="table">
      <thead>
       <tr>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Phone</th>
       </tr>
      </thead>
      <tbody>
       <?php while ($r = $anggota->fetch_assoc()): ?>
       <tr>
        <td><?= htmlspecialchars($r['nama']) ?></td>
        <td class="small"><?= htmlspecialchars($r['jabatan']) ?></td>
        <td><?= htmlspecialchars($r['phone']) ?></td>
       </tr>
       <?php endwhile; ?>
      </tbody>
     </table>
    </div>

    <div class="card">
     <h3>Uang Kas</h3>
     <div class="small">Saldo: <strong>Rp <?= number_format($saldo, 2, ',', '.') ?></strong></div>
    </div>
   </div>
  </div>

  <div class="footer">Member Panel — Karang Taruna</div>
 </div>
</body>

</html>

<?php $conn->close(); ?>