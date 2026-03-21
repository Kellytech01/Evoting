<?php
require 'db.php';
if (empty($_SESSION['student_id'])) { header("Location: index.php"); exit; }

$student_id = $_SESSION['student_id'];

// If already voted, stop here
$chk = $conn->prepare("SELECT has_voted FROM students WHERE id=?");
$chk->bind_param("i", $student_id);
$chk->execute();
$flag = $chk->get_result()->fetch_assoc();
if (intval($flag['has_voted']) === 1) {
  die('<div style="padding:24px" class="card"><h3>You have already voted.</h3></div>');
}

// Validate OTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $entered = trim($_POST['otp_entered'] ?? '');
  $valid = isset($_SESSION['otp']) && $entered === strval($_SESSION['otp'])
           && (time() - ($_SESSION['otp_time'] ?? 0) <= 300);
  if (!$valid) {
    die('<div class="container-narrow"><div class="card"><h3 style="color:#b91c1c;">Invalid/Expired OTP.</h3><a class="btn" href="index.php">Try again</a></div></div>');
  }
  $_SESSION['otp_verified'] = true;
}

if (empty($_SESSION['otp_verified'])) { header("Location: index.php"); exit; }

// Load candidates grouped by position
$res = $conn->query("SELECT * FROM candidates ORDER BY position, name");
$grouped = [];
while ($row = $res->fetch_assoc()) { $grouped[$row['position']][] = $row; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ballot Paper</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="header"><h1>Ballot Paper</h1><div class="sub">Choose one candidate per position</div></div>
  <div class="container-narrow">
    <form class="card" method="post" action="submit_vote.php">
      <?php global $EV_POSITIONS; foreach ($EV_POSITIONS as $pos): if (!isset($grouped[$pos])) continue; ?>
        <div class="section-title"><?php echo htmlspecialchars($pos); ?></div>
        <div class="candidate-grid">
          <?php
            $cands = $grouped[$pos];
            if (count($cands) === 1) {
              $c = $cands[0];
              $photo = ($c['photo'] && file_exists($c['photo'])) ? $c['photo'] : 'assets/no-photo.png';
              echo "<div class='cand'>
                      <input type='hidden' name='vote[".htmlspecialchars($pos)."]' value='".intval($c['id'])."'>
                      <img src='".htmlspecialchars($photo)."' alt='photo'>
                      <div>
                        <div><b>".htmlspecialchars($c['name'])."</b></div>
                        <div class='meta'>Unopposed</div>
                      </div>
                    </div>";
            } else {
              foreach ($cands as $c) {
                $photo = ($c['photo'] && file_exists($c['photo'])) ? $c['photo'] : 'assets/no-photo.png';
                echo "<label class='cand'>
                        <input type='radio' name='vote[".htmlspecialchars($pos)."]' value='".intval($c['id'])."' required style='margin-top:6px;'>
                        <img src='".htmlspecialchars($photo)."' alt='photo'>
                        <div>
                          <div><b>".htmlspecialchars($c['name'])."</b></div>
                          <div class='meta'>".htmlspecialchars($pos)."</div>
                        </div>
                      </label>";
              }
            }
          ?>
        </div>
        <hr style="border:none;height:1px;background:#f1f5f9;margin:16px 0;">
      <?php endforeach; ?>
      <button class="btn" type="submit">Submit Vote</button>
    </form>
  </div>
</body>
</html>
