<?php

$servername =  "192.168.0.62"
$username = "cloudnanny"
$password = "-Ke[RG(XqO1q8qIe"


//open database connection
$dbConn = mysql_connect($servername, $username, $password)
	or die(print_r(mysql_error()));
mysql_select_db('') or die(print_r(mysql_error()));

?>