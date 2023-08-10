<?php

//require_once('connection.php');

$mysqli = mysqli_connect('192.168.0.62', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));



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
	$result = $mysqli->query($query);
	if ($result) {
		echo 'sucess';
	} else {
		echo 'error';
	}
}

function validateToken($token, $id) {
	$query = "select user_id from tokens where token = '".dbEsc($token). "' AND computer_id = ".dbEsc($id). ";";
	$result = $mysqli->query($query);
	$row = $result->fetch_array(MYSQLI_ASSOC);
	return $row['user_id'];
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
