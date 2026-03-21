<?php
require '../db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $u = trim($_POST['username']??'');
  $p = trim($_POST['password']??'');
  $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
  $stmt->bind_param("s",$u);
  $stmt->execute();
  $admin = $stmt->get_result()->fetch_assoc();
  if ($admin && password_verify($p, $admin['password_hash'])) {
    $_SESSION['admin_id']=$admin['id'];
    $_SESSION['admin_user']=$admin['username'];
    header("Location: dashboard.php"); exit;
  } else { $err = "Invalid credentials"; }
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8"><title>Admin Login</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
  <div class="header"><h1>Admin Login</h1></div>
  <div class="container-narrow">
    <div class="card" style="max-width:460px;margin:auto;">
      <?php if(!empty($err)) echo "<div class='alert alert-danger'>{$err}</div>"; ?>
      <form method="post">
        <input class="input" name="username" placeholder="Username" required>
        <input class="input" style="margin-top:8px" type="password" name="password" placeholder="Password" required>
        <button class="btn" style="margin-top:10px" type="submit">Login</button>
      </form>
    </div>
  </div>
</body></html>
