<?php

require_once('connection.php');

$username = mysqli_real_escape_string($mysqli,$_POST['user']);
$password = mysqli_real_escape_string($mysqli,$_POST['pass']);
$name = mysqli_real_escape_string($mysqli,$_POST['name']);
$id = mysqli_real_escape_string($mysqli,$_POST['id']);
$module_type = mysqli_real_escape_string($mysqli,$_POST['module_type']);

$name = htmlspecialchars($name);
$username = htmlspecialchars($username);
$module_type = htmlspecialchars($module_type);

signIn($username, $password, $name, $mysqli, $id, $module_type);

function signIn($username, $password, $name, $mysqli, $id, $module_type) {

	// never trust data coming from lua
	$username = htmlspecialchars($username);
	$password = htmlspecialchars($password);
	$name = htmlspecialchars($name);
	$id = htmlspecialchars($id);
	$module_type = htmlspecialchars($module_type);
	
	// hash is created in the lua now
	
	// $salt = '';
	// $query = "select salt from users where username = '".dbEsc($username). "';";	
	// $result = mysqli_query($mysqli,$query);
	// $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	// $salt = $row['salt'];
	// $hash = sha1($salt.$password);
	
	$query2 = "SELECT user_id FROM users WHERE username = '" . $username . "' AND password = '" . $password . "'";
	
	$result2 = mysqli_query($mysqli,$query2);
	

	while ($row2 = $result2->fetch_row()) {
	if ($row2['user_id'] != '') {
		$token = createToken($mysqli, $row2['user_id'], $name, $id, $username, $module_type);
		
		if ($module_type == '4') {
			createRedstoneEntry($mysqli, $token, $id);
		}
		if ($module_type == '3') {
			createTankEntry($mysqli, $token, $id);
		}
		if ($module_type == '2') {
			createEnergyEntry($mysqli, $token, $id);
		}
		
		echo $token;
	} else {
		echo 'error';
	}
	}
}

function createToken($mysqli, $user_id, $name, $id, $username, $module_type) {
	$token = rand().rand().rand().rand();
	$query = "INSERT INTO tokens (token, user_id, computer_name, computer_id, module_type) VALUES ('".$token."', '".$user_id."', '".$name."', '".$id."', '".$module_type."')";
	$result = mysqli_query($mysqli,$query);
	if ($result) {
		return $token;
	} else {
		return 'error';
	}
}

function createRedstoneEntry($mysqli, $token, $id) {
	$query = "INSERT INTO redstone_controls (token, computer_id) VALUES ('".$token."', ".$id.")";
	$result = mysqli_query($mysqli,$query);
}

function createTankEntry($mysqli, $token, $id) {
	$query = "INSERT INTO tanks (token) VALUES ('".$token."')";
	$result = mysqli_query($mysqli,$query);
}

function createEnergyEntry($mysqli, $token, $id) {
	$query = "INSERT INTO energy_storage (token, computer_id) VALUES ('".$token."', ".$id.")";
	$result = mysqli_query($mysqli,$query);
}


function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>