<?php
// register.php
session_start();
require 'db.php';
$errors = [];
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $full = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = ($_POST['role'] === 'admin') ? 'admin' : 'member';

    if(!$full || !$email || !$username || !$password) $errors[] = "Lengkapi semua field penting.";
    if(empty($errors)){
        $stmt = $conn->prepare("SELECT id FROM users WHERE username=? OR email=? LIMIT 1");
        $stmt->bind_param("ss",$username,$email);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows>0){
            $errors[] = "Username atau email sudah terdaftar.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ins = $conn->prepare("INSERT INTO users (full_name,email,phone,username,password,role) VALUES (?,?,?,?,?,?)");
            $ins->bind_param("ssssss",$full,$email,$phone,$username,$hash,$role);
            if($ins->execute()){
                $_SESSION['msg'] = "Registrasi berhasil. Silakan login.";
                header("Location: login.php");
                exit;
            } else {
                $errors[] = "Gagal menyimpan: " . $conn->error;
            }
        }
    }
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Register</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<div class="container">
  <div class="card">
    <h2>Register</h2>
    <?php if($errors): foreach($errors as $e): ?>
      <div class="alert"><?=htmlspecialchars($e)?></div>
    <?php endforeach; endif; ?>
    <form method="post">
      <div class="group"><label>Nama lengkap</label><input name="full_name" class="input" required></div>
      <div class="group"><label>Email</label><input name="email" type="email" class="input" required></div>
      <div class="group"><label>Nomor HP</label><input name="phone" class="input"></div>
      <div class="group"><label>Username</label><input name="username" class="input" required></div>
      <div class="group"><label>Password</label><input name="password" type="password" class="input" required></div>
      <div class="group"><label>Daftar sebagai</label>
        <select name="role" class="input">
          <option value="member">Member</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <div><button class="btn" type="submit">Register</button> <a href="index.php" class="small">Kembali</a></div>
    </form>
  </div>
</div>
</body>
</html>
