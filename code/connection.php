<?php

$servername =  "192.168.0.62"
$username = "cloudnanny"
$password = "-Ke[RG(XqO1q8qIe"
$databaseName = "cloudnanny" 


//open database connection
$dbConn = mysqli_connect($servername, $username, $password, $databaseName)
	or die(print_r(mysql_error()));
mysql_select_db('') or die(print_r(mysql_error()));

?>