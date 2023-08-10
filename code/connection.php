<?php

//open database connection
$dbConn = mysqli_connect('localhost', 'cloudpc', '-Ke[RG(XqO1q8qIe', 'cloudnanny') or die(print_r(mysqli_error()));
mysqli_select_db("cloudnanny", $dbConn) or die(print_r(mysqli_error()));

?>