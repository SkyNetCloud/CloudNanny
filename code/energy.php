<?php

$version = 1;

//require_once('connection.php');

$mysqli = mysqli_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));
session_start();

$token = $_POST['token'];
$id = $_POST['id'];
$bat_name = $_POST['bat_name'];
$energy_type = $_POST['energy_type'];
$percent = $_POST['percent'];


$tokenstring = $mysqli->real_escape_string($this->$token);
$idstring = $mysqli->real_escape_string($this->$id);
$batnamestring = $mysqli->real_escape_string($this->$bat_name);
$energytypestring = $mysqli->real_escape_string($this->$token);
$percentstring = $mysqli->real_escape_string($this->$token);



$energy_type = htmlspecialchars($energy_type);
$bat_name = htmlspecialchars($bat_name);
$percent = htmlspecialchars($percent);

$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '$tokenstring' AND computer_id = '$'";
$result = $mysqli->query($query);

if ($result) {
	$query2 = "UPDATE energy_storage SET bat_name = '$bat_name', energy_type = '$energytypestring', percent = '"$percentstring"' WHERE token = '"$tokenstring."'";
	$result2 = $mysqli->query($query2);

	echo $version;
} else {
	echo 'error: token update query failed.';
}

?>