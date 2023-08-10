<?php
# Fill our vars and run on cli
# $ php -f db-connect-test.php

$dbname = 'cloudnanny';
$dbuser = 'cloudpc';
$dbpass = '-Ke[RG(XqO1q8qIe';
$dbhost = 'localhost';

$link = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)

$mysqli->select_db($dbname);
