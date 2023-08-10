<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = '';
$DATABASE_NAME = 'phplogin';

//open database connection
$dbConn = mysqli_connect('localhost', 'SkyNetCloud', 'SkyNetCloud#', 'cloudnanny') or die(print_r(mysqli_error($dbConn)));
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
mysqli_select_db($dbConn,"cloudnanny") or die(print_r(mysqli_error($dbConn)));

?>