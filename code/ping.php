<?php

$version = 2;

require_once('connection.php');

$token = mysqli_real_escape_string($mysqli,$_POST['token']);
$id = mysqli_real_escape_string($mysqli,$_POST['id']);

logPing($token, $id, $version);


function logPing($token, $id, $version) {
	global $mysqli;
	$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '". $token ."' AND computer_id = '".$id."'";
	$result = mysqli_query($mysqli,$query);
	if ($result) {
		echo $version;
	} else {
		echo $version;
	}
}

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}

?>