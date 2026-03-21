<?php
require 'db.php';
if (empty($_SESSION['student_id'])) { header("Location: index.php"); exit; }

// Double check has_voted again (in case someone tries to skip)
$stmt = $conn->prepare("SELECT first_name,last_name,faculty,department,level,has_voted FROM students WHERE id=?");
$stmt->bind_param("i", $_SESSION['student_id']);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();
if (intval($student['has_voted']) === 1) {
  header("Location: index.php"); exit;
}

$otp = rand(100000, 999999);
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>OTP Verification</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="header"><h1>OTP Verification</h1><div class="sub">Valid for 5 minutes</div></div>
  <div class="container-narrow">
    <div class="card" style="text-align:center;">
      <div style="margin-bottom:8px;"><b><?php echo htmlspecialchars($student['first_name']." ".$student['last_name']); ?></b></div>
      <div class="note">
        Faculty: <?php echo htmlspecialchars($student['faculty']); ?> •
        Dept: <?php echo htmlspecialchars($student['department']); ?> •
        Level: <?php echo intval($student['level']); ?>
      </div>
      <h2 style="margin:12px 0;color:#0a7a45;"><?php echo $otp; ?></h2>
      <form method="post" action="vote.php" style="max-width:360px;margin:0 auto;">
        <input class="input" type="text" name="otp_entered" placeholder="Enter OTP" required>
        <button class="btn" type="submit">Proceed to Ballot</button>
      </form>
    </div>
  </div>
</body>
</html>
