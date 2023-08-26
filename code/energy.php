<?php
global $mysqli;

$version = 1;

require_once('connection.php');


$token = mysqli_real_escape_string($mysqli,$_POST['token']);
$id = mysqli_real_escape_string($mysqli,$_POST['id']);
$bat_name = mysqli_real_escape_string($mysqli,$_POST['bat_name']);
$energy_type = mysqli_real_escape_string($mysqli,$_POST['energy_type']);
$percent = mysqli_real_escape_string($mysqli,$_POST['percent']);

$energy_type = htmlspecialchars($energy_type);
$bat_name = htmlspecialchars($bat_name);
$percent = htmlspecialchars($percent);

$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '".$token."' AND computer_id = '".$id."'";
$result = mysqli_query($mysqli,$query);

if ($result) {
	$query2 = "UPDATE energy_storage SET bat_name = '".$bat_name."', energy_type = '".$energy_type."', percent = '".$percent."' WHERE token = '".$token."'";
	$result2 = mysqli_query($mysqli,$query2);

	echo $version;
} else {
	echo 'error: token update query failed.';
}


?>