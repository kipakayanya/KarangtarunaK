<?php
session_start();
require 'db.php';

// Cek login dan role
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$msg = '';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil data kegiatan berdasarkan ID
$result = $conn->query("SELECT * FROM kegiatan WHERE id = $id");
if ($result->num_rows === 0) {
    die("Kegiatan tidak ditemukan.");
}
$data = $result->fetch_assoc();

// Proses update saat form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $nama = $conn->real_escape_string($_POST['nama']);
    $mulai = $conn->real_escape_string($_POST['mulai']);
    $selesai = $conn->real_escape_string($_POST['selesai']);
    $tempat = $conn->real_escape_string($_POST['tempat']);
    $des = $conn->real_escape_string($_POST['des']);

    $conn->query("UPDATE kegiatan SET 
        nama_kegiatan = '$nama', 
        tanggal_mulai = '$mulai', 
        tanggal_selesai = '$selesai', 
        tempat = '$tempat', 
        deskripsi = '$des'
        WHERE id = $id");

    $msg = "Kegiatan berhasil diperbarui.";

    // Ambil data terbaru untuk ditampilkan di form
    $result = $conn->query("SELECT * FROM kegiatan WHERE id = $id");
    $data = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
 <meta charset="UTF-8">
 <title>Edit Kegiatan</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>

 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Edit Kegiatan</h1>
   </div>
   <div class="nav"><a href="kegiatan.php">Kembali</a></div>
  </div>

  <?php if ($msg): ?>
  <div class="card small"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <div class="card">
   <h3>Form Edit Kegiatan</h3>
   <form method="post">
    <div class="group">
     <label>Nama Kegiatan</label>
     <input name="nama" class="input" value="<?= htmlspecialchars($data['nama_kegiatan']) ?>" required>
    </div>
    <div class="group">
     <label>Tanggal Mulai</label>
     <input name="mulai" type="date" class="input" value="<?= htmlspecialchars($data['tanggal_mulai']) ?>">
    </div>
    <div class="group">
     <label>Tanggal Selesai</label>
     <input name="selesai" type="date" class="input" value="<?= htmlspecialchars($data['tanggal_selesai']) ?>">
    </div>
    <div class="group">
     <label>Tempat</label>
     <input name="tempat" class="input" value="<?= htmlspecialchars($data['tempat']) ?>">
    </div>
    <div class="group">
     <label>Deskripsi</label>
     <textarea name="des" class="input" rows="4"><?= htmlspecialchars($data['deskripsi']) ?></textarea>
    </div>
    <div>
     <button class="btn" name="update" type="submit">Simpan Perubahan</button>
    </div>
   </form>
  </div>

  <div class="footer">Karang Taruna</div>
 </div>

</body>

</html>

<?php $conn->close(); ?>