<?php require 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>UNN SUG e-Voting</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<script defer src="assets/js/app.js"></script>
</head>
<body>
  <div class="header">
    <h1>UNN SUG e-Voting Portal</h1>
    <div class="sub">Secure • Fast • Transparent</div>
  </div>

  <div class="container-narrow">
    <div class="card">
      <div class="section-title">Voter Verification</div>
      <form method="post">
        <input class="input" type="text" name="reg_number" placeholder="Enter your Registration Number" required>
        <div class="note">If not found, you will see: “Student not registered”.</div>
        <button class="btn" type="submit" name="check">Check</button>
      </form>

      <?php
      if (isset($_POST['check'])) {
        $reg = trim($_POST['reg_number']);
        $stmt = $conn->prepare("SELECT * FROM students WHERE reg_number=? LIMIT 1");
        $stmt->bind_param("s", $reg);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows === 0) {
          echo "<div class='alert alert-danger'>Student not registered.</div>";
        } else {
          $s = $res->fetch_assoc();
          $_SESSION['student_id'] = $s['id'];
          $_SESSION['reg_number'] = $s['reg_number'];

          echo "<div class='badge' style='margin:10px 0;'><span>Verified</span></div>";
          echo "<div style='margin-top:8px;'>
                  <div><b>Name:</b> ".htmlspecialchars($s['first_name']." ".$s['last_name'])."</div>
                  <div><b>Faculty:</b> ".htmlspecialchars($s['faculty'])."</div>
                  <div><b>Department:</b> ".htmlspecialchars($s['department'])."</div>
                  <div><b>Level:</b> ".intval($s['level'])."</div>
                </div>";

          // Show "already voted" here BEFORE OTP stage
          if (intval($s['has_voted']) === 1) {
            echo "<div class='alert alert-info' style='margin-top:12px;'>You have already voted. Thank you.</div>";
            echo "<div style='margin-top:10px;'>
                    <a class='btn' href='results.php' target='_blank'>View Live Results</a>
                  </div>";
          } else {
            echo "<form method='post' action='generate_otp.php' style='margin-top:14px;'>
                    <button class='btn' type='submit'>Request OTP</button>
                  </form>";
          }
        }
      }
      ?>
    </div>

    <div class="footer">© "
      <?php echo date('Y'); ?>
      " University of Nigeria, Nsukka • SUG ICT</div>
  </div>
</body>
</html>
