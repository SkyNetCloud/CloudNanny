<?php

$version = 2;

$mysqli = mysqli_connect('192.168.0.62', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));


//require_once('connection.php');

$token = $_POST['token'];
$id = $_POST['id'];

logPing($token, $id, $version);

function logPing($token, $id, $version) {
	$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '".dbEsc($token)."' AND computer_id = ".dbEsc($id);
	$result = $mysqli->query($query);
	if ($result) {
		echo $version;
	} else {
		echo $version;
	}
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