<?php
require_once 'db.php';
require_once 'util.php';

// fetch profiles
$stmt = $pdo->query('SELECT profile_id, first_name, last_name, headline FROM Profile');
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Profiles</title>
</head>
<body>
  <h1>Profiles</h1>
  <p><a href="add.php">Add New Profile</a></p>
  <?php if(count($rows) == 0) { echo '<p>No profiles found</p>'; } else { ?>
  <table border="1" cellpadding="5">
    <tr><th>Name</th><th>Headline</th><th>Actions</th></tr>
    <?php foreach($rows as $r) { ?>
      <tr>
        <td><?php echo htmlentities($r['first_name'].' '.$r['last_name']); ?></td>
        <td><?php echo htmlentities($r['headline']); ?></td>
        <td>
          <a href="view.php?profile_id=<?php echo $r['profile_id']; ?>">View</a> |
          <a href="edit.php?profile_id=<?php echo $r['profile_id']; ?>">Edit</a> |
          <a href="delete.php?profile_id=<?php echo $r['profile_id']; ?>" onclick="return confirm('Delete?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </table>
  <?php } ?>
</body>
</html>
