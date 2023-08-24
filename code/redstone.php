<?php

$version = 2;

require_once('connection.php');

$token = mysqli_real_escape_string($mysqli,$_POST['token']);
$id = mysqli_real_escape_string($mysqli,$_POST['id']);
$top_input = mysqli_real_escape_string($mysqli,$_POST['top_input']);
$bottom_input = mysqli_real_escape_string($mysqli,$_POST['bottom_input']);
$front_input = mysqli_real_escape_string($mysqli,$_POST['front_input']);
$back_input = mysqli_real_escape_string($mysqli,$_POST['back_input']);
$left_input = mysqli_real_escape_string($mysqli,$_POST['left_input']);
$right_input = mysqli_real_escape_string($mysqli,$_POST['right_input']);

$top_input = htmlspecialchars($top_input);
$bottom_input = htmlspecialchars($bottom_input);
$front_input = htmlspecialchars($front_input);
$back_input = htmlspecialchars($back_input);
$left_input = htmlspecialchars($left_input);
$right_input = htmlspecialchars($right_input);

$query2 = "UPDATE redstone_controls SET top_input = '".$top_input."', bottom_input = '".$bottom_input."', front_input = '".$front_input."', back_input = '".$back_input."', left_input = '".$left_input."', right_input = '".$right_input."' WHERE token = '".$token."'";

$result = mysqli_query($mysqli,$query2);

checkEvents($mysqli,$token);
getRsOutputs($mysqli,$token, $id, $version);


function getRsOutputs($mysqli,$token, $id, $version) {
	$query = "UPDATE tokens SET last_seen = NOW() WHERE token = '".$token."' AND computer_id = '".$id."'";
	$result = mysqli_query($mysqli,$query);
	
	if ($result) {
		$query2 = "SELECT * from redstone_controls WHERE token = '".$token."'";
		$result2 = mysqli_query($mysqli,$query2);
		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
	
		$returnString = $version.", ".$row2['top'].", ".$row2['bottom'].", ".$row2['back'].", ".$row2['front'].", ".$row2['left_side'].", ".$row2['right_side'];
		echo $returnString;
	} else {
		echo 'error: token update query failed.';
	}
}

function checkEvents($mysqli,$token) {
	$query = "SELECT * from redstone_events WHERE redstone_token = '".$token."'";
	$result = mysqli_query($mysqli,$query);
	
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$query2 = "SELECT * FROM tanks WHERE token = '".$row['storage_token']."'";
		$result2 = mysqli_query($mysqli,$query2);
		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
		
		$side = '';
		if ($row['side'] == 'top_side') {
			$side = 'top';
		}
		if ($row['side'] == 'bottom_side') {
			$side = 'bottom';
		}
		if ($row['side'] == 'front_side') {
			$side = 'front';
		}
		if ($row['side'] == 'back_side') {
			$side = 'back';
		}
		
		if ($row['event_type'] == '1') {
			if (intval($row2['percent']) > intval($row['trigger_value'])) {
				$query3 = "UPDATE redstone_controls SET ".$side." = ".$row['output'];
				$result3 = mysqli_query($mysqli,$query3);
			}
		}
		if ($row['event_type'] == '2') {
			if (intval($row2['percent']) < intval($row['trigger_value'])) {
				$query3 = "UPDATE redstone_controls SET ".$side." = ".$row['output'];
				$result3 = mysqli_query($mysqli,$query3);
			}
		}
		
		$query2 = "SELECT * FROM energy_storage WHERE token = '".$row['storage_token']."'";
		$result2 = mysqli_query($mysqli,$query2);
		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
		
		$side = '';
		if ($row['side'] == 'top_side') {
			$side = 'top';
		}
		if ($row['side'] == 'bottom_side') {
			$side = 'bottom';
		}
		if ($row['side'] == 'front_side') {
			$side = 'front';
		}
		if ($row['side'] == 'back_side') {
			$side = 'back';
		}
		
		if ($row['event_type'] == '1') {
			if (intval($row2['percent']) > intval($row['trigger_value'])) {
				$query3 = "UPDATE redstone_controls SET ".$side." = ".$row['output'];
				$result3 = mysqli_query($mysqli,$query3);
			}
		}
		if ($row['event_type'] == '2') {
			if (intval($row2['percent']) < intval($row['trigger_value'])) {
				$query3 = "UPDATE redstone_controls SET ".$side." = ".$row['output'];
				$result3 = mysqli_query($mysqli,$query3);
			}
		}
	}
}


?>