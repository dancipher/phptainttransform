<?php

$x = "Hello " . $_GET['id'];
$x = $x . "!";
echo($x . "\n");

$sql = "SELECT * FROM users WHERE name = '".$_GET['name']."'";
mysql_query($sql);
include($_GET['file']);

?>
