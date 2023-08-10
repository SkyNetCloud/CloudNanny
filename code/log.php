<?php

require_once('connection.php');

$token = $_POST['token'];
$ign = $_POST['ign'];
$event = $_POST['event'];
$discription = $_POST['description'];
$id = $_POST['id'];

$user_id = validateToken($token, $id);
$event = htmlspecialchars($event);
$ign = htmlspecialchars($ign);
$discription = htmlspecialchars($discription);

if ($user_id) {
	enterRecord($ign, $event, $discription, $user_id, $token);
} else {
	echo 'error';
}

function enterRecord($ign, $event, $discription, $user_id, $token) {
	$query = "INSERT INTO logs (user_id, ign, event, discription, timestamp, token) VALUES ('".$user_id."', '".dbEsc($ign)."', ".$event.", '".dbEsc($discription)."', NOW(), '".dbEsc($token)."')";
	$result = mysqli_query($dbConn, $query);
	if ($result) {
		echo 'sucess';
	} else {
		echo 'error';
	}
}

function validateToken($token, $id) {
	$query = "select user_id from tokens where token = '".dbEsc($token). "' AND computer_id = ".dbEsc($id). ";";
	$result = mysqli_query($dbConn, $query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	return $row['user_id'];
}

function dbEsc($dbConn, $theString) {
	$theString = mysqli_real_escape_string($dbConn, $theString);
	return $theString;
}

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>
