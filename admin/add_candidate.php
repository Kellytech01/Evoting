<?php
require '../db.php';
if (empty($_SESSION['admin_id'])) { header("Location: login.php"); exit; }

$msg = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  $name = trim($_POST['name'] ?? '');
  $position = trim($_POST['position'] ?? '');
  $photoPath = null;

  if (!empty($_FILES['photo']['name'])) {
    $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/jpg'=>'jpg'];
    $type = $_FILES['photo']['type'] ?? '';
    $size = $_FILES['photo']['size'] ?? 0;

    if (!isset($allowed[$type])) { $msg = "Only JPG/PNG allowed."; }
    elseif ($size > 2*1024*1024) { $msg = "Max 2MB file."; }
    else {
      if (!is_dir("../uploads")) mkdir("../uploads", 0777, true);
      $ext = $allowed[$type];
      $safe = preg_replace('/[^a-z0-9]+/i','-', strtolower($name));
      $fname = "uploads/".time()."_".$safe.".".$ext;
      if (move_uploaded_file($_FILES['photo']['tmp_name'], "../".$fname)) {
        $photoPath = $fname;
      } else { $msg = "Upload failed."; }
    }
  }

  if (!$msg) {
    $stmt = $conn->prepare("INSERT INTO candidates (name, position, photo) VALUES (?,?,?)");
    $stmt->bind_param("sss", $name, $position, $photoPath);
    $stmt->execute();
    $msg = "Candidate added.";
  }
}
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8"><title>Add Candidate</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
  <div class="header"><h1>Add Candidate</h1></div>
  <div class="container-narrow">
    <div class="card" style="max-width:560px;margin:auto;">
      <?php if($msg) echo "<div class='alert alert-info'>{$msg}</div>"; ?>
      <form method="post" enctype="multipart/form-data">
        <label>Name</label>
        <input class="input" name="name" required>
        <label style="margin-top:8px;">Position</label>
        <select class="input" name="position" required>
          <?php
            $pos = $conn->query("SELECT position FROM positions_ref ORDER BY position");
            while($p = $pos->fetch_assoc()) {
              echo "<option value='".htmlspecialchars($p['position'])."'>".htmlspecialchars($p['position'])."</option>";
            }
          ?>
        </select>
        <label style="margin-top:8px;">Photo (JPG/PNG ≤ 2MB)</label>
        <input class="input" type="file" name="photo" accept="image/*">
        <button class="btn" style="margin-top:12px;" type="submit">Save</button>
        <a class="btn" href="dashboard.php" style="margin-left:8px;">Back</a>
      </form>
    </div>
  </div>
</body></html>
