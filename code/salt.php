<?php
global $mysqli;

$username = mysqli_real_escape_string($mysqli,$_POST['user']);

$username = htmlspecialchars($username);

require_once('connection.php');

$salt = '';
$query = "select salt from users where username = '".$username. "';";	
$result = mysqli_query($mysqli,$query);



if ($result) {
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$salt = $row['salt'];
	echo $salt;
} else {
	echo 'error';
}

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>