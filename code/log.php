<?php

require_once('connection.php');

$token = mysqli_real_escape_string($mysqli,$_POST['token']);
$ign = mysqli_real_escape_string($mysqli,$_POST['ign']);
$event = $_POST['event'];
$discription = mysqli_real_escape_string($mysqli,$_POST['description']);
$id = $_POST['id'];

$user_id = validateToken($mysqli,$token, $id);
$event = htmlspecialchars($event);
$ign = htmlspecialchars($ign);
$discription = htmlspecialchars($discription);

if ($user_id) {
	enterRecord($mysqli,$ign, $event, $discription, $user_id, $token);
} else {
	echo 'error';
}

function enterRecord($mysqli,$ign, $event, $discription, $user_id, $token) {
	$query = "INSERT INTO logs (user_id, ign, event, discription, timestamp, token) VALUES ('".$user_id."', '".$ign."', ".$event.", '".$discription."', NOW(), '".$token."')";
	$result = mysqli_query($mysqli,$query);
	if ($result) {
		echo 'sucess';
	} else {
		echo 'error';
	}
}

function validateToken($mysqli,$token, $id) {
	$query = "select user_id from tokens where token = '".$token. "' AND computer_id = ".$id. ";";
	$result = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	return $row['user_id'];
}


function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>
