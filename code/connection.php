<?php

$servername =  "localhost"
$username = "cloudnanny"
$password = "-Ke[RG(XqO1q8qIe"
$dbname = "cloudnanny"
//open database connection
$dbConn = mysql_connect($servername, $dbname, $username, $password)
	or die(print_r(mysql_error()));
mysql_select_db('') or die(print_r(mysql_error()));

?>