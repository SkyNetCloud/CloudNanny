<?php

$username = $_POST['user'];

$username = htmlspecialchars($username);

require_once('connection.php');
$salt = '';
$query = "select salt from users where username = '".dbEsc($username). "';";	
$result = mysqli_query($dbConn, $query);



if ($result) {
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$salt = $row['salt'];
	echo $salt;
} else {
	echo 'error';
}

// function dbEsc($theString) {
// 	$theString = $dbConn -> real_escape_string($theString);;
// 	return $theString;
// }

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>