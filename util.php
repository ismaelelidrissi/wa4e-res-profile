<?php
function flash($msg) {
    echo '<p style="color:green">' . htmlentities($msg) . '</p>';
}
function error($msg) {
    echo '<p style="color:red">' . htmlentities($msg) . '</p>';
}
?>
