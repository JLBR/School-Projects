<?php

$host = "";
$port = "";

$connection = $host . ":" . $port;

$user = "";
$password = "";

$db = mysql_connect($connection, $user, $password);

if (!$db) {
	die("Could not connect: " . mysql_error());
}

mysql_select_db('');

?>
