<?php

$version = 2;

require_once('connection.php');

$token = mysqli_real_escape_string($mysqli,$_POST['token']);
$id = mysqli_real_escape_string($mysqli,$_POST['id']);

global $mysqli;
logPing($mysqli,$token, $id, $version);


function logPing($mysqli,$token, $id, $version) {
	$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '". $token ."' AND computer_id = ".$id ;
	$result = $mysqli->query($query);
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