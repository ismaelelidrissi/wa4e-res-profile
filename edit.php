<?php
require_once 'db.php';
$id = $_GET['profile_id'] ?? false;
if(!$id) { header('Location: index.php'); return; }
$stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = ?');
$stmt->execute([$id]);
$profile = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$profile) { echo 'Not found'; exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $fn = trim($_POST['first_name'] ?? '');
    $ln = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $headline = trim($_POST['headline'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    if($fn=='' || $ln=='' || $email=='') { $err = 'First, Last and Email required.'; }
    if(!isset($err)){
        $stmt = $pdo->prepare('UPDATE Profile SET first_name=?, last_name=?, email=?, headline=?, summary=? WHERE profile_id=?');
        $stmt->execute([$fn,$ln,$email,$headline,$summary,$id]);
        // delete old positions
        $pdo->prepare('DELETE FROM Position WHERE profile_id=?')->execute([$id]);
        $rank=1;
        for($i=1;$i<=9;$i++){
            if(isset($_POST['year'.$i]) && isset($_POST['desc'.$i])){
                $y = trim($_POST['year'.$i]); $d = trim($_POST['desc'.$i]);
                if($y!='' && $d!=''){
                    $pdo->prepare('INSERT INTO Position (profile_id,rank,year,description) VALUES (?,?,?,?)')->execute([$id,$rank,$y,$d]);
                    $rank++;
                }
            }
        }
        header('Location: index.php'); return;
    }
}
// fetch positions
$stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = ? ORDER BY rank');
$stmt->execute([$id]);
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Edit Profile</title>
<script>
function addPosition(){
  var count = document.getElementsByClassName('pos').length;
  if(count>=9) { alert('Max 9'); return; }
  var idx = count+1;
  var div = document.createElement('div'); div.className='pos';
  div.innerHTML = 'Year: <input name="year'+idx+'" /> Description: <input name="desc'+idx+'" /> <button type="button" onclick="this.parentNode.remove()">Remove</button>';
  document.getElementById('positions').appendChild(div);
}
</script>
</head>
<body>
  <h1>Edit Profile</h1>
  <?php if(isset($err)) echo '<p style="color:red">'.htmlentities($err).'</p>'; ?>
  <form method="post">
    First name: <input type="text" name="first_name" value="<?php echo htmlentities($profile['first_name']); ?>"><br/>
    Last name: <input type="text" name="last_name" value="<?php echo htmlentities($profile['last_name']); ?>"><br/>
    Email: <input type="text" name="email" value="<?php echo htmlentities($profile['email']); ?>"><br/>
    Headline: <input type="text" name="headline" value="<?php echo htmlentities($profile['headline']); ?>"><br/>
    Summary: <br/><textarea name="summary" rows="5" cols="40"><?php echo htmlentities($profile['summary']); ?></textarea><br/>
    <p>Positions: <button type="button" onclick="addPosition();">Add Position</button></p>
    <div id="positions">
      <?php $i=1; foreach($positions as $p){ ?>
        <div class="pos">Year: <input name="year<?php echo $i;?>" value="<?php echo htmlentities($p['year']);?>" /> Description: <input name="desc<?php echo $i;?>" value="<?php echo htmlentities($p['description']);?>" /> <button type="button" onclick="this.parentNode.remove()">Remove</button></div>
      <?php $i++; } ?>
    </div>
    <p><input type="submit" value="Save"> <a href="index.php">Cancel</a></p>
  </form>
</body>
</html>
