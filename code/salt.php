<?php

$username = $_POST['user'];

$mysqli = mysqli_connect('192.168.0.62', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));


$username = htmlspecialchars($username);

//require_once('connection.php');
$salt = '';
$query = "select salt from users where username = '".dbEsc($username). "';";	
$result = $mysqli->query($query);



if ($result) {
	$row = $result->fetch_array(MYSQLI_ASSOC);
	$salt = $row['salt'];
	echo $salt;
} else {
	echo 'error';
}

function dbEsc($theString) {
	$theString = $mysqli->real_escape_string($theString);
	return $theString;
}

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>