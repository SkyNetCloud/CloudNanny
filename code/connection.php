<?php
session_start();

$DATABASE_HOST = '127.0.0.1';
$DATABASE_USER = 'SkyNetCloud';
$DATABASE_PASS = 'SkyNetCloud#';
$DATABASE_NAME = 'cloudnanny';

//open database connection
$mysqli = mysqli_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));
session_start();
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
mysqli_select_db($mysqli,$DATABASE_NAME ) or die(print_r(mysqli_error($mysqli)));

?>