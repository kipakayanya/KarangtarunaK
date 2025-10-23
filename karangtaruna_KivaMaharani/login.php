<?php
// login.php
session_start();
require 'db.php';
$err = '';
if($_SERVER['REQUEST_METHOD']==='POST'){
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if($username && $password){
        $stmt = $conn->prepare("SELECT id, full_name, username, password, role FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $res = $stmt->get_result();
        if($res && $user = $res->fetch_assoc()){
            if(password_verify($password, $user['password'])){
                unset($user['password']);
                $_SESSION['user'] = $user;
                if($user['role'] === 'admin') header("Location: dashboard_admin.php");
                else header("Location: dashboard_member.php");
                exit;
            } else $err = "Username atau password salah.";
        } else $err = "Username tidak ditemukan.";
    } else $err = "Isi username & password.";
}
$msg = $_SESSION['msg'] ?? null;
unset($_SESSION['msg']);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Login</title><link rel="stylesheet" href="css/style.css"></head>
<body>
<div class="container">
  <div class="card">
    <h2>Login</h2>
    <?php if($msg): ?><div class="alert"><?=htmlspecialchars($msg)?></div><?php endif; ?>
    <?php if($err): ?><div class="alert"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <form method="post">
      <div class="group"><label>Username</label><input name="username" class="input" required></div>
      <div class="group"><label>Password</label><input name="password" type="password" class="input" required></div>
      <div><button class="btn" type="submit">Login</button> <a href="register.php" class="small">Register</a></div>
    </form>
  </div>
</div>
</body>
</html>
