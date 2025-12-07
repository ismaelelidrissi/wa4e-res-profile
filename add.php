<?php
require_once 'db.php';
require_once 'util.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // server-side simple validation
    $fn = trim($_POST['first_name'] ?? '');
    $ln = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $headline = trim($_POST['headline'] ?? '');
    $summary = trim($_POST['summary'] ?? '');

    if($fn=='' || $ln=='' || $email=='' ) {
        $err = 'First name, last name and email are required.';
    } elseif (strpos($email,'@') === false) {
        $err = 'Email must contain @';
    }

    if(!isset($err)) {
        $pdo->beginTransaction();
        $stmt = $pdo->prepare('INSERT INTO Profile (first_name,last_name,email,headline,summary) VALUES (?,?,?,?,?)');
        $stmt->execute([$fn,$ln,$email,$headline,$summary]);
        $profile_id = $pdo->lastInsertId();

        // positions
        $rank = 1;
        for($i=1;$i<=9;$i++){
            if(isset($_POST['year'.$i]) && isset($_POST['desc'.$i])){
                $y = trim($_POST['year'.$i]);
                $d = trim($_POST['desc'.$i]);
                if($y != '' && $d != ''){
                    $stmt = $pdo->prepare('INSERT INTO Position (profile_id,rank,year,description) VALUES (?,?,?,?)');
                    $stmt->execute([$profile_id,$rank,$y,$d]);
                    $rank++;
                }
            }
        }
        $pdo->commit();
        header('Location: index.php');
        return;
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Add Profile</title>
  <script>
  function validate() {
    var fn = document.getElementById('first_name').value.trim();
    var ln = document.getElementById('last_name').value.trim();
    var email = document.getElementById('email').value.trim();
    if(fn=='' || ln=='' || email=='') { alert('First, Last and Email required'); return false; }
    if(email.indexOf('@') == -1) { alert('Email must contain @'); return false; }
    return true;
  }
  function addPosition() {
    var count = document.getElementsByClassName('pos').length;
    if(count >= 9) { alert('Maximum 9 positions'); return; }
    var div = document.createElement('div');
    div.className = 'pos';
    var idx = count+1;
    div.innerHTML = 'Year: <input name="year'+idx+'" /> Description: <input name="desc'+idx+'" /> <button type="button" onclick="this.parentNode.remove()">Remove</button>';
    document.getElementById('positions').appendChild(div);
  }
  </script>
</head>
<body>
  <h1>Add Profile</h1>
  <?php if(isset($err)) error($err); ?>
  <form method="post" onsubmit="return validate();">
    First name: <input type="text" name="first_name" id="first_name"><br/>
    Last name: <input type="text" name="last_name" id="last_name"><br/>
    Email: <input type="text" name="email" id="email"><br/>
    Headline: <input type="text" name="headline"><br/>
    Summary: <br/><textarea name="summary" rows="5" cols="40"></textarea><br/>
    <p>Positions: <button type="button" onclick="addPosition();">Add Position</button></p>
    <div id="positions"></div>
    <p><input type="submit" value="Save"> <a href="index.php">Cancel</a></p>
  </form>
</body>
</html>
