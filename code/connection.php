<?php

//open database connection
$dbConn = mysql_connect('localhost', 'cloudpc', '-Ke[RG(XqO1q8qIe', 'cloudnanny') or die(print_r(mysql_error()));
mysql_select_db("cloudnanny", $dbConn) or die(print_r(mysql_error()));

?>