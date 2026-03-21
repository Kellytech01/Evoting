<?php
require 'db.php';
if (empty($_SESSION['student_id']) || empty($_SESSION['otp_verified'])) { header("Location: index.php"); exit; }

$student_id = intval($_SESSION['student_id']);

// Prevent double vote (again)
$chk = $conn->prepare("SELECT has_voted FROM students WHERE id=?");
$chk->bind_param("i", $student_id);
$chk->execute();
$flag = $chk->get_result()->fetch_assoc();
if (intval($flag['has_voted']) === 1) {
  die('<div class="container-narrow"><div class="card"><h3>You have already voted.</h3></div></div>');
}

$votes = $_POST['vote'] ?? [];
if (empty($votes)) {
  die('<div class="container-narrow"><div class="card"><h3>No selections.</h3></div></div>');
}

$stmt = $conn->prepare("INSERT INTO votes (student_id, candidate_id) VALUES (?, ?)");
foreach ($votes as $pos => $candidate_id) {
  $cid = intval($candidate_id);
  $stmt->bind_param("ii", $student_id, $cid);
  $stmt->execute();
}
$conn->query("UPDATE students SET has_voted=1 WHERE id={$student_id}");

unset($_SESSION['otp'], $_SESSION['otp_time'], $_SESSION['otp_verified']);

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><title>Vote Submitted</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="header"><h1>Thank You</h1></div>
  <div class="container-narrow">
    <div class="card" style="text-align:center;">
      <h3>✅ Your vote has been recorded successfully!</h3>
      <a class="btn" href="results.php" target="_blank">View Live Results</a>
      <a class="btn" style="margin-left:8px" href="index.php">Home</a>
    </div>
  </div>
</body>
</html>
