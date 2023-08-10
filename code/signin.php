<?php

require_once('connection.php');

$mysqli = mysqli_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));
session_start();



$username = $_POST['user'];
$password = $_POST['pass'];
$name = $_POST['name'];
$id = $_POST['id'];
$module_type = $_POST['module_type'];


$usernamestring = $mysqli->real_escape_string($username);
$passwordstring = $mysqli->real_escape_string($password);
$namestring = $mysqli->real_escape_string($name);
$idstring = $mysqli->real_escape_string($id);
$moduletypestring = $mysqli->real_escape_string($module_type);



$name = htmlspecialchars($name);
$username = htmlspecialchars($username);
$module_type = htmlspecialchars($module_type);

signIn($username, $password, $name, $mysqli, $id, $module_type);

function signIn($username, $password, $name, $mysqli, $id, $module_type) {

	// never trust data coming from lua
	$username = htmlspecialchars($usernamestring);
	$password = htmlspecialchars($passwordstring);
	$name = htmlspecialchars($namestring);
	$id = htmlspecialchars($idstring);
	$module_type = htmlspecialchars($moduletypestring);
	
	// hash is created in the lua now
	
	// $salt = '';
	// $query = "select salt from users where username = '".dbEsc($username). "';";	
	// $result = $mysqli->query($query);
	// $row = $result->fetch_array(MYSQLI_ASSOC);
	// $salt = $row['salt'];
	// $hash = sha1($salt.$password);
	
	$query2 = "SELECT user_id FROM users WHERE username = '$usernamestring' AND password = '$passwordstring'";
	
	$result2 = $mysqli->query($query2);
	$row2 = $result2->fetch_array(MYSQLI_ASSOC);

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

function createToken($mysqli, $user_id, $name, $id, $username, $module_type) {
	$token = rand().rand().rand().rand();
	$query = "INSERT INTO tokens (token, user_id, computer_name, computer_id, module_type) VALUES ('$token', '$user_id', '$usernamestring', '$idstring', '$moduletype')";
	$result = $mysqli->query($query);
	if ($result) {
		return $token;
	} else {
		return 'error';
	}
}

function createRedstoneEntry($mysqli, $token, $id) {
	$query = "INSERT INTO redstone_controls (token, computer_id) VALUES ('$token', '$idstring')";
	$result = $mysqli->query($query);
}

function createTankEntry($mysqli, $token, $id) {
	$query = "INSERT INTO tanks (token) VALUES ('$token')";
	$result = $mysqli->query($query);
}

function createEnergyEntry($mysqli, $token, $id) {
	$query = "INSERT INTO energy_storage (token, computer_id) VALUES ('$token', '$idstring')";
	$result = $mysqli->query($query);
}

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}





?>