<?php

//open database connection
// $dbConn = mysql_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#')
// 	or die(print_r(mysql_error()));
// mysql_select_db('cloudnanny') or die(print_r(mysql_error()));

$mysqli=mysqli_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny');


if ($mysqli -> connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	exit();
  }
?>