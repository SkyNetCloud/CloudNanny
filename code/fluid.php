<?php

$version = 1;

require_once('connection.php');

$token = mysqli_real_escape_string($mysqli,$_POST['token']);
$id = mysqli_real_escape_string($mysqli,$_POST['id']);
$tank_name = mysqli_real_escape_string($mysqli,$_POST['tank_name']);
$fluid_type = mysqli_real_escape_string($mysqli,$_POST['fluid_type']);
$percent = mysqli_real_escape_string($mysqli,$_POST['percent']);

$fluid_type = htmlspecialchars($fluid_type);
$tank_name = htmlspecialchars($tank_name);
$percent = htmlspecialchars($percent);

$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '".$token."' AND computer_id = ".$id;
$result = mysqli_query($mysqli,$query);

if ($result) {
	$query2 = "UPDATE tanks SET tank_name = '".$tank_name."', fluid_type = '".$fluid_type."', percent = '".$percent."' WHERE token = '".$token."'";
	$result2 = mysqli_query($mysqli,$query2);

	echo $version;
} else {
	echo 'error: token update query failed.';
}



?>