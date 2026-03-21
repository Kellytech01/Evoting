<?php
require 'db.php';
$q = $conn->query("
  SELECT c.position, c.id, c.name, c.photo, COUNT(v.id) AS total
  FROM candidates c
  LEFT JOIN votes v ON v.candidate_id = c.id
  GROUP BY c.id
  ORDER BY c.position, total DESC, c.name
");
$data = [];
while ($r = $q->fetch_assoc()) $data[$r['position']][] = $r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8"><title>Live Results</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
  <div class="header"><h1>Live Results</h1><div class="sub">Auto-refreshed when page reloads</div></div>
  <div class="container-narrow">
    <div class="card">
      <?php foreach ($data as $pos => $rows): ?>
        <div class="section-title"><?php echo htmlspecialchars($pos); ?></div>
        <?php foreach ($rows as $r):
          $photo = ($r['photo'] && file_exists($r['photo'])) ? $r['photo'] : 'assets/no-photo.png'; ?>
          <div class="cand">
            <img src="<?php echo htmlspecialchars($photo); ?>" alt="photo">
            <div>
              <div><b><?php echo htmlspecialchars($r['name']); ?></b></div>
              <div class="meta">Votes: <?php echo intval($r['total']); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
        <hr style="border:none;height:1px;background:#f1f5f9;margin:16px 0;">
      <?php endforeach; ?>
    </div>
  </div>
</body>
</html>
