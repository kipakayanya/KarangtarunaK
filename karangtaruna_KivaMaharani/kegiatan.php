<?php
// kegiatan.php - CRUD kegiatan sederhana
session_start();
require 'db.php';

// Cek login dan role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = '';

// Tambah dan Hapus Kegiatan
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Tambah
    if (isset($_POST['add'])) {
        $nama = $conn->real_escape_string($_POST['nama']);
        $mulai = $conn->real_escape_string($_POST['mulai']);
        $selesai = $conn->real_escape_string($_POST['selesai']);
        $tempat = $conn->real_escape_string($_POST['tempat']);
        $des = $conn->real_escape_string($_POST['des']);
        $conn->query("INSERT INTO kegiatan (nama_kegiatan, tanggal_mulai, tanggal_selesai, tempat, deskripsi) 
                      VALUES ('$nama', '$mulai', '$selesai', '$tempat', '$des')");
        $msg = "Kegiatan ditambahkan.";
    }

    // Hapus
    if (isset($_POST['delete_ang'])) {
        $id = intval($_POST['id']);
        $conn->query("DELETE FROM kegiatan WHERE id = $id");
        $msg = "Kegiatan berhasil dihapus.";
    }
}

// Ambil semua kegiatan
$all = $conn->query("SELECT * FROM kegiatan ORDER BY tanggal_mulai DESC");
?>

<!doctype html>
<html lang="id">

<head>
 <meta charset="utf-8">
 <title>Kelola Kegiatan</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>

 <div class="container">

  <!-- Header -->
  <div class="header">
   <div class="brand">
    <h1>Kelola Kegiatan</h1>
   </div>
   <div class="nav">
    <a href="dashboard_admin.php">Kembali</a>
   </div>
  </div>

  <!-- Pesan -->
  <?php if ($msg): ?>
  <div class="card small"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <!-- Form Tambah Kegiatan -->
  <div class="card">
   <h3>Tambah Kegiatan</h3>
   <form method="post">
    <div class="group">
     <label>Nama Kegiatan</label>
     <input name="nama" class="input" required>
    </div>
    <div class="group">
     <label>Tanggal Mulai</label>
     <input name="mulai" type="date" class="input">
    </div>
    <div class="group">
     <label>Tanggal Selesai</label>
     <input name="selesai" type="date" class="input">
    </div>
    <div class="group">
     <label>Tempat</label>
     <input name="tempat" class="input">
    </div>
    <div class="group">
     <label>Deskripsi</label>
     <textarea name="des" class="input" rows="4"></textarea>
    </div>
    <div>
     <button class="btn" name="add" type="submit">Tambah Kegiatan</button>
    </div>
   </form>
  </div>

  <!-- Tabel Daftar Kegiatan -->
  <div class="card">
   <h3>Daftar Kegiatan</h3>
   <table class="table">
    <thead>
     <tr>
      <th>Nama</th>
      <th>Mulai</th>
      <th>Selesai</th>
      <th>Tempat</th>
      <th>Deskripsi</th>
      <th>Aksi</th>
     </tr>
    </thead>
    <tbody>
     <?php while ($r = $all->fetch_assoc()): ?>
     <tr>
      <td><?= htmlspecialchars($r['nama_kegiatan']) ?></td>
      <td class="small"><?= htmlspecialchars($r['tanggal_mulai']) ?></td>
      <td class="small"><?= htmlspecialchars($r['tanggal_selesai']) ?></td>
      <td><?= htmlspecialchars($r['tempat']) ?></td>
      <td><?= htmlspecialchars($r['deskripsi']) ?></td>
      <td>
       <!-- Tombol Hapus -->
       <form method="post" style="display:inline" onsubmit="return confirm('Yakin ingin menghapus kegiatan ini?')">
        <input type="hidden" name="id" value="<?= intval($r['id']) ?>">
        <button class="btn btn-danger" name="delete_ang" type="submit">Hapus</button>
       </form>

       <!-- Tombol Edit -->
       <a class="btn" href="edit_kegiatan.php?id=<?= intval($r['id']) ?>">Edit</a>
      </td>
     </tr>
     <?php endwhile; ?>
    </tbody>
   </table>
  </div>

  <!-- Footer -->
  <div class="footer">Karang Taruna</div>

 </div>
</body>

</html>

<?php $conn->close(); ?>