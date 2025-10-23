<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'member') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Ambil catatan milik user
$result = $conn->query("SELECT * FROM catatan WHERE id=$id AND user_id=" . intval($user['id']));
if ($result->num_rows == 0) {
    die("Catatan tidak ditemukan.");
}
$data = $result->fetch_assoc();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_note'])) {
    $judul = $conn->real_escape_string($_POST['judul']);
    $isi = $conn->real_escape_string($_POST['isi']);
    $conn->query("UPDATE catatan SET judul='$judul', isi='$isi' WHERE id=$id AND user_id=" . intval($user['id']));
    $msg = "Catatan diperbarui.";
    // Ambil ulang data
    $result = $conn->query("SELECT * FROM catatan WHERE id=$id AND user_id=" . intval($user['id']));
    $data = $result->fetch_assoc();
}
?>
<!doctype html>
<html>

<head>
 <meta charset="utf-8">
 <title>Edit Catatan</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Edit Catatan</h1>
   </div>
   <div class="nav"><a href="dashboard_member.php">Kembali</a></div>
  </div>

  <?php if($msg): ?><div class="card small"><?= htmlspecialchars($msg) ?></div><?php endif; ?>

  <div class="card">
   <form method="post">
    <div class="group"><label>Judul</label>
     <input name="judul" class="input" value="<?= htmlspecialchars($data['judul']) ?>" required>
    </div>
    <div class="group"><label>Isi</label>
     <textarea name="isi" rows="6" class="input" required><?= htmlspecialchars($data['isi']) ?></textarea>
    </div>
    <div>
     <button class="btn" name="update_note" type="submit">Simpan Perubahan</button>
    </div>
   </form>
  </div>

  <div class="footer">Karang Taruna</div>
 </div>
</body>

</html>

<?php $conn->close(); ?>