<?php
require '../db.php';
if (empty($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['confirm'])) {
  $conn->query("TRUNCATE TABLE votes");
  $conn->query("UPDATE students SET has_voted=0");
  $msg = "Election reset completed.";
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8"><title>Reset Election</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
  <div class="header"><h1>Reset Election</h1></div>
  <div class="container-narrow">
    <div class="card" style="text-align:center;max-width:520px;margin:auto;">
      <?php if(!empty($msg)) echo "<div class='alert alert-success'>{$msg}</div>"; ?>
      <p>This clears all votes and marks all students as not voted.</p>
      <form method="post">
        <button class="btn" name="confirm" value="1" type="submit">Confirm Reset</button>
        <a class="btn" href="dashboard.php" style="margin-left:8px;">Cancel</a>
      </form>
    </div>
  </div>
</body></html>
