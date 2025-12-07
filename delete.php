<?php
require_once 'db.php';
$id = $_GET['profile_id'] ?? false;
if(!$id) { header('Location: index.php'); return; }
$pdo->prepare('DELETE FROM Profile WHERE profile_id = ?')->execute([$id]);
header('Location: index.php');
?>
