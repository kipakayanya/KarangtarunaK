<?php
// index.php - Landing page (publik)
session_start();
require 'db.php';
?>
<!doctype html>
<html>

<head>
 <meta charset="utf-8">
 <title>Karang Taruna - Landing</title>
 <link rel="stylesheet" href="css/style.css">
</head>

<body>
 <div class="container">
  <div class="header">
   <div class="brand">
    <h1>Karang Taruna</h1>
    <div class="small">Komunitas Pemuda</div>
   </div>
   <div class="nav">
    <?php if(isset($_SESSION['user'])): ?>
    <span class="small">Hi, <?=htmlspecialchars($_SESSION['user']['full_name'])?></span>
    <a href="logout.php">Logout</a>
    <?php if($_SESSION['user']['role']==='admin'): ?>
    <a class="btn" href="dashboard_admin.php">Admin</a>
    <?php else: ?>
    <a class="btn" href="dashboard_member.php">Member</a>
    <?php endif; ?>
    <?php else: ?>
    <a href="login.php">Login</a>
    <a class="btn" href="register.php">Register</a>
    <?php endif; ?>
   </div>
  </div>

  <div class="card">
   <h2>Tentang Karang Taruna</h2>
   <p>Website sederhana untuk manajemen anggota, kegiatan, dan kas Karang Taruna. Disiapkan untuk kebutuhan para pemuda
    dan pemudi</p>
  </div>

  <div class="grid">
   <div>
    <div class="card">
     <h3>Kegiatan Terbaru</h3>
     <?php
        $q = $conn->query("SELECT nama_kegiatan, tanggal_mulai, tanggal_selesai, tempat FROM kegiatan ORDER BY tanggal_mulai DESC LIMIT 3");
        if($q && $q->num_rows>0){
            echo "<ul>";
            while($r = $q->fetch_assoc()){
                echo "<li><strong>".htmlspecialchars($r['nama_kegiatan'])."</strong> — ".htmlspecialchars($r['tempat'])." (".$r['tanggal_mulai']." sampai ".$r['tanggal_selesai'].")</li>";
            }
            echo "</ul>";
        } else {
            echo "<div class='small'>Belum ada kegiatan.</div>";
        }
        ?>
    </div>

    <div class="card">
     <h3>Daftar Anggota (Preview)</h3>
     <?php
        $a = $conn->query("SELECT nama, jabatan, phone FROM anggota ORDER BY nama LIMIT 10");
        if($a && $a->num_rows>0){
            echo "<table class='table'>
            <thead>
            
            <tr>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>No handphone</th>
            </tr>
            
            </thead
            ><tbody>";
            while($row = $a->fetch_assoc()){
                echo "<tr><td>".htmlspecialchars($row['nama'])."</td><td class='small'>".htmlspecialchars($row['jabatan'])."</td><td>".htmlspecialchars($row['phone'])."</td></tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<div class='small'>Belum ada anggota.</div>";
        }
        ?>
    </div>
   </div>

   <div>
    <div class="card">
     <h3>Kontak</h3>
     <p class="small">Hubungi admin untuk informasi lebih lanjut.</p>
    </div>

    <div class="card">
     <h3>Quick Links</h3>
     <a class="btn" href="login.php">Login</a>
     <a class="btn" href="register.php">Register</a>
    </div>
   </div>
  </div>


 </div>
</body>

</html>