<?php



$mysqli = mysqli_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny');


if ($mysqli -> connect_errno) {
	echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
	exit();
  }
?>