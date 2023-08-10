<?php

//open database connection
$dbConn = mysqli_connect('localhost', 'SkyNetCloud', 'SkyNetCloud#', 'cloudnanny') or die(print_r(mysqli_error($dbConn)));
mysqli_select_db($dbConn,"cloudnanny") or die(print_r(mysqli_error($dbConn)));

?>