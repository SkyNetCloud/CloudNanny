<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'SkyNetCloud';
$DATABASE_PASS = 'SkyNetCloud#';
$DATABASE_NAME = 'cloudnanny';

//open database connection
$dbConn = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS) or die(print_r(mysqli_error($dbConn)));
if ( mysqli_connect_errno() ) {
	// If there is an error with the connection, stop the script and display the error.
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
mysqli_select_db($dbConn,$DATABASE_NAME ) or die(print_r(mysqli_error($dbConn)));

?>