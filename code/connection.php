<?php

//open database connection
$dbConn = mysql_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#')
	or die(print_r(mysql_error()));
mysql_select_db('cloudnanny') or die(print_r(mysql_error()));

if ( ! $dbConn ) {
	die( 'Could not connect: ' . mysqli_error($dbConn) );
 } else {
	echo 'Connection established';
 }
?>