<?php
require '../db.php';
if (empty($_SESSION['admin_id'])) { header("Location: login.php"); exit; }
$rows = $conn->query("SELECT * FROM candidates ORDER BY position, name");
?>
<!DOCTYPE html>
<html><head>
<meta charset="utf-8"><title>Admin Dashboard</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head><body>
  <div class="header"><h1>Admin Dashboard</h1><div class="sub">Manage candidates, view results, reset election</div></div>
  <div class="container-narrow">
    <div class="card">
      <a class="btn" href="add_candidate.php">Add Candidate</a>
      <a class="btn" href="../results.php" target="_blank" style="margin-left:8px">View Results</a>
      <a class="btn" href="reset.php" style="margin-left:8px">Reset Election</a>
      <a class="btn" href="logout.php" style="margin-left:8px;background:#933!important">Logout</a>
    </div>

    <div class="card">
      <div class="section-title">Current Candidates</div>
      <?php while($c = $rows->fetch_assoc()):
        $photo = ($c['photo'] && file_exists("../".$c['photo'])) ? "../".$c['photo'] : "../assets/no-photo.png"; ?>
        <div class="cand">
          <img src="<?php echo htmlspecialchars($photo); ?>" alt="photo">
          <div>
            <div><b><?php echo htmlspecialchars($c['name']); ?></b></div>
            <div class="meta"><?php echo htmlspecialchars($c['position']); ?></div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</body></html>
