<?php

$version = 1;

require_once('connection.php');

$token = $_POST['token'];
$id = $_POST['id'];
$tank_name = $_POST['tank_name'];
$fluid_type = $_POST['fluid_type'];
$percent = $_POST['percent'];

$fluid_type = htmlspecialchars($fluid_type);
$tank_name = htmlspecialchars($tank_name);
$percent = htmlspecialchars($percent);

$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '".dbEsc($token)."' AND computer_id = ".dbEsc($id);
$result = mysqli_query($mysqli,$query);

if ($result) {
	$query2 = "UPDATE tanks SET tank_name = '".$tank_name."', fluid_type = '".dbEsc($fluid_type)."', percent = '".dbEsc($percent)."' WHERE token = '".dbEsc($token)."'";
	$result2 = mysqli_query($mysqli,$query2);

	echo $version;
} else {
	echo 'error: token update query failed.';
}



?>