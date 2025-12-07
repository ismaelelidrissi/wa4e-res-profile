<?php
require_once 'db.php';
$id = $_GET['profile_id'] ?? false;
if(!$id) { header('Location: index.php'); return; }
$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = ?');
$stmt->execute([$id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$profile) { echo 'Not found'; exit; }
$stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = ? ORDER BY rank');
$stmt->execute([$id]);
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>View Profile</title></head>
<body>
  <h1><?php echo htmlentities($profile['first_name'].' '.$profile['last_name']); ?></h1>
  <p>Email: <?php echo htmlentities($profile['email']); ?></p>
  <p>Headline: <?php echo htmlentities($profile['headline']); ?></p>
  <p>Summary: <?php echo nl2br(htmlentities($profile['summary'])); ?></p>
  <?php if(count($positions)>0) { echo '<h3>Positions</h3><ul>'; foreach($positions as $p) { echo '<li>'.htmlentities($p['year']).' : '.htmlentities($p['description']).'</li>'; } echo '</ul>'; } ?>
  <p><a href="index.php">Back</a></p>
</body>
</html>
