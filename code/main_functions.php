<?php

require_once('connection.php');

function dbError(&$xmlDoc, &$xmlNode, $theMessage) {
	$errorNode = $xmlDoc->createElement('mysqlError', $theMessage);
	$xmlNode->appendChild($errorNode);
}

function doesUserExist($mysqli, $xmlDoc, $id, $type) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	if($type == 'google') {
		$query = "select * from google_users where google_id = " . $id;
	} else if ($type == 'main') {
		$query = "select * from users where username = '" . $id . "';";
	}


	$result = mysqli_query($mysqli,$query);

	if (!($result)) {
		$statusNode = $xmlDoc->createElement('status', $query);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('status', 'success');
	}

	$counter = 0;
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$counter = $counter + 1;
	}
	$statusNode = $xmlDoc->createElement('records', $counter);


	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

function addGoogleUser($mysqli, $xmlDoc, $google_id, $name, $email, $image_url) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "INSERT INTO google_users (google_id, username, name, email, img_url) " .
				"VALUES ('".$google_id."', '" . $name ."', '" . $name . "', '" . $email . "', '" . $image_url . "')";

	$result = mysqli_query($mysqli,$query);

	if (!($result)) {
		$statusNode = $xmlDoc->createElement('status', $query);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('status', $google_id);
	}

	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

function signIn($mysqli, $xmlDoc, $username, $password) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$username = htmlspecialchars($username);
	$password = htmlspecialchars($password);

	$salt = '';
	$query = "SELECT salt FROM users WHERE username = '".$username. "';";
	$result = mysqli_query($mysqli,$query);
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$salt = $row['salt'];

	$hash = sha1($salt.$password);

	$query2 = "SELECT user_id FROM users WHERE username = '" . $username . "' AND password = '" . $hash . "';";

	$result2 = mysqli_query($mysqli,$query2);

	if (!($result2)) {
		$statusNode = $xmlDoc->createElement('status', $query2);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('status', '');
	}

	$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
	$statusNode = $xmlDoc->createElement('token', $row2['user_id']);

	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

function addNewUser($mysqli, $xmlDoc, $username, $password, $email) {
	$recordDataNode = $xmlDoc->createElement('userdata');

	$username = htmlspecialchars($username);
	$password = htmlspecialchars($password);
	$email = htmlspecialchars($email);

	$salt = rand().rand().rand().rand();
	$hash = sha1($salt.$password);

	$user_id = rand().rand().rand().rand();

	$query = "INSERT INTO users (user_id, username, password, salt, email) " .
				"VALUES ('".$user_id."', '" . $username ."', '" . $hash . "', '" . $salt . "', '".$email."')";

	$result = mysqli_query($mysqli,$query);

	if (!($result)) {
		$statusNode = $xmlDoc->createElement('status', $query);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('token', $user_id);
	}

	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

function getConnections($mysqli, $xmlDoc, $user_id, $type) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

  $query = "SELECT * FROM tokens WHERE user_id = '".$user_id."' AND module_type = '".$type."'";

	$result = mysqli_query($mysqli,$query);

	if (!($result)) {
		$statusNode = $xmlDoc->createElement('status', $query);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('status', 'success');
	}


  while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
	$theChildNode = $xmlDoc->createElement('connection');
	$theChildNode->setAttribute('name', $row['computer_name']);
	$theChildNode->setAttribute('token', $row['token']);

	$datetime1 = strtotime($row['last_seen']);
	$datetime2 = time();
	$diff = $datetime2-$datetime1;
	if ($diff > 200) {
		$theChildNode->setAttribute('active', false);
	} else {
		$theChildNode->setAttribute('active', true);
	}

	$recordDataNode->appendChild($theChildNode);
  }
  	$recordDataNode->appendChild($statusNode);
	return $recordDataNode;
}

function getLogs($mysqli, $xmlDoc, $user_id) {
	//main XML element to return
	$recordDataNode = $xmlDoc->createElement('recorddata');

	//get users tokens and scanner names
	$query = "SELECT * from tokens where user_id = '".$user_id."' AND module_type = '1'";
	$result = mysqli_query($mysqli,$query);
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$theScannerNode = $xmlDoc->createElement('scanner');
			$nameNode = $xmlDoc->createElement('name');
			$nameNode->setAttribute('name', $row['computer_name']);
			$nameNode->setAttribute('token', $row['token']);
			$datetime1 = strtotime($row['last_seen']);
			$datetime2 = time();
			$diff = $datetime2-$datetime1;
			if ($diff > 200) {
				$nameNode->setAttribute('active', false);
			} else {
				$nameNode->setAttribute('active', true);
			}
		$theScannerNode->appendChild($nameNode);
		//for each scanncer, get last 10 visitors
		$query2 = "SELECT DISTINCT(ign) AS ign from logs where token = '".$row['token']."' ORDER BY timestamp DESC LIMIT 10";
		$result2 = mysqli_query($mysqli,$query2);
		while ($row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC)) {
			$VistorNode = $xmlDoc->createElement('visitor');
			$VistorNode->setAttribute('ign', $row2['ign']);
			$VistorNode->setAttribute('token', $row['token']);
			
			$query3 = "SELECT timestamp FROM logs WHERE token = '".$row['token']."' AND ign = '".$row2['ign']."' ORDER BY timestamp DESC LIMIT 1";
			$result3 = mysqli_query($mysqli,$query3);
			$row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
			$VistorNode->setAttribute('last_seen', $row3['timestamp']);
			$theScannerNode->appendChild($VistorNode);

		}
		$recordDataNode->appendChild($theScannerNode);
	}

	return $recordDataNode;
}

function getPlayerData($mysqli, $xmlDoc, $ign, $token) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from logs where token = '".$token."' AND ign = '".$ign."' ORDER BY timestamp DESC LIMIT 50";
	$result = mysqli_query($mysqli,$query);
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$VistorNode = $xmlDoc->createElement('record');
		$VistorNode->setAttribute('ign', $row['ign']);
		$VistorNode->setAttribute('event', $row['event']);
		$VistorNode->setAttribute('time', $row['timestamp']);
		$VistorNode->setAttribute('discription', $row['discription']);
		$recordDataNode->appendChild($VistorNode);
	}
	return $recordDataNode;
}

function getUser($mysqli, $xmlDoc, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query2 = "SELECT username from users where user_id = '".$user_id."'";
	$result2 = mysqli_query($mysqli,$query2);
	$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
	$userNode = $xmlDoc->createElement('user');
	$userNode->setAttribute('username', $row2['username']);

	$recordDataNode->appendChild($userNode);

	$query3 = "UPDATE users SET last_seen = NOW() WHERE user_id = '".$user_id."'";
	$result3 = mysqli_query($mysqli,$query3);

	return $recordDataNode;
}

function loadRedstoneControls($mysqli, $xmlDoc, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from tokens where user_id = '".$user_id."' AND module_type = '4'";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('controls');
		$controlNode->setAttribute('name', $row['computer_name']);
		$controlNode->setAttribute('token', $row['token']);

		$datetime1 = strtotime($row['last_seen']);
		$datetime2 = time();
		$diff = $datetime2-$datetime1;
		if ($diff > 200) {
			$controlNode->setAttribute('active', false);
		} else {
			$controlNode->setAttribute('active', true);
		}

		$query2 = "SELECT * from redstone_controls where token = '".$row['token']."'";
		$result2 = mysqli_query($mysqli,$query2);

		if (!($result2)) {
			$statusNode = $xmlDoc->createElement('status', $query);

			dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
		} else {
			$statusNode = $xmlDoc->createElement('status', 'success');
		}

		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

		$controlNode->setAttribute('top', $row2['top']);
		$controlNode->setAttribute('bottom', $row2['bottom']);
		$controlNode->setAttribute('front', $row2['front']);
		$controlNode->setAttribute('back', $row2['back']);
		$controlNode->setAttribute('left', $row2['left_side']);
		$controlNode->setAttribute('right', $row2['right_side']);

		$controlNode->setAttribute('top_name', $row2['top_name']);
		$controlNode->setAttribute('bottom_name', $row2['bottom_name']);
		$controlNode->setAttribute('front_name', $row2['front_name']);
		$controlNode->setAttribute('back_name', $row2['back_name']);
		$controlNode->setAttribute('left_name', $row2['left_name']);
		$controlNode->setAttribute('right_name', $row2['right_name']);

		$controlNode->setAttribute('top_input', $row2['top_input']);
		$controlNode->setAttribute('bottom_input', $row2['bottom_input']);
		$controlNode->setAttribute('front_input', $row2['front_input']);
		$controlNode->setAttribute('back_input', $row2['back_input']);
		$controlNode->setAttribute('left_input', $row2['left_input']);
		$controlNode->setAttribute('right_input', $row2['right_input']);

		$recordDataNode->appendChild($controlNode);

	}
	$recordDataNode->appendChild($statusNode);
	return $recordDataNode;
}

function setRedstoneOutput($mysqli, $xmlDoc, $token, $side, $value, $type) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	if ($type == 'string') {
		$value = htmlspecialchars($value);
		$query = "UPDATE redstone_controls SET ".$side." = '".$value."' WHERE token = '".$token."'";
	} else {
		$query = "UPDATE redstone_controls SET ".$side." = ".$value." WHERE token = '".$token."'";
	}

	$result = mysqli_query($mysqli,$query);

	if (!($result)) {
		$statusNode = $xmlDoc->createElement('status', $query);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('status', 'success');
	}
	$recordDataNode->appendChild($statusNode);
	return $recordDataNode;
}

function getFluidLevels($mysqli, $xmlDoc, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from tokens where user_id = '".$user_id."' AND module_type = '3'";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('modules');
		$controlNode->setAttribute('name', $row['computer_name']);
		$controlNode->setAttribute('token', $row['token']);

		$datetime1 = strtotime($row['last_seen']);
		$datetime2 = time();
		$diff = $datetime2-$datetime1;
		if ($diff > 200) {
			$controlNode->setAttribute('active', false);
		} else {
			$controlNode->setAttribute('active', true);
		}

		$query2 = "SELECT * from tanks where token = '".$row['token']."'";
		$result2 = mysqli_query($mysqli,$query2);

		if (!($result2)) {
			$statusNode = $xmlDoc->createElement('status', $query);

			dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
		} else {
			$statusNode = $xmlDoc->createElement('status', 'success');
		}

		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

		$controlNode->setAttribute('tank_name', $row2['tank_name']);
		$controlNode->setAttribute('fluid_type', $row2['fluid_type']);
		$controlNode->setAttribute('percent', $row2['percent']);

		$recordDataNode->appendChild($controlNode);

	}
	$recordDataNode->appendChild($statusNode);
	return $recordDataNode;
}


function getEnergyLevels($mysqli, $xmlDoc, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from tokens where user_id = '".$user_id."' AND module_type = '2'";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('modules');
		$controlNode->setAttribute('name', $row['computer_name']);
		$controlNode->setAttribute('token', $row['token']);

		$datetime1 = strtotime($row['last_seen']);
		$datetime2 = time();
		$diff = $datetime2-$datetime1;
		if ($diff > 200) {
			$controlNode->setAttribute('active', false);
		} else {
			$controlNode->setAttribute('active', true);
		}

		$query2 = "SELECT * from energy_storage where token = '".$row['token']."'";
		$result2 = mysqli_query($mysqli,$query2);

		if (!($result2)) {
			$statusNode = $xmlDoc->createElement('status', $query);

			dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
		} else {
			$statusNode = $xmlDoc->createElement('status', 'success');
		}

		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

		$controlNode->setAttribute('bat_name', $row2['bat_name']);
		$controlNode->setAttribute('energy_type', $row2['energy_type']);
		$controlNode->setAttribute('percent', $row2['percent']);

		$recordDataNode->appendChild($controlNode);

	}
	$recordDataNode->appendChild($statusNode);
	return $recordDataNode;
}

function removeModule($mysqli, $xmlDoc, $token) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query2 = "DELETE FROM tokens WHERE token = '".$token."'";
	$result2 = mysqli_query($mysqli,$query2);

	if (!($result2)) {
			$statusNode = $xmlDoc->createElement('status', $query2);

			dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
		} else {
			$statusNode = $xmlDoc->createElement('status', 'success');
		}

	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

function redstoneEventDropdowns($mysqli, $xmlDoc, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from tokens where user_id = '".$user_id."' AND (module_type = '2' OR module_type = '3')";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('storage_modules');
		$controlNode->setAttribute('name', $row['computer_name']);
		$controlNode->setAttribute('token', $row['token']);
		$recordDataNode->appendChild($controlNode);
	}

	$query = "SELECT * from tokens where user_id = '".$user_id."' AND module_type = '4'";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('redstone_modules');
		$controlNode->setAttribute('name', $row['computer_name']);
		$controlNode->setAttribute('token', $row['token']);
		$recordDataNode->appendChild($controlNode);
	}

	return $recordDataNode;
}

function getRedstoneSides($mysqli, $xmlDoc, $token) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from redstone_controls where token = '".$token."'";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('modules');
		$controlNode->setAttribute('top_name', $row['top_name']);
		$controlNode->setAttribute('bottom_name', $row['bottom_name']);
		$controlNode->setAttribute('front_name', $row['front_name']);
		$controlNode->setAttribute('back_name', $row['back_name']);
		$controlNode->setAttribute('left_name', $row['left_name']);
		$controlNode->setAttribute('right_name', $row['right_name']);
		$recordDataNode->appendChild($controlNode);
	}

	return $recordDataNode;
}

function createRedstoneEvent($mysqli, $xmlDoc, $storageToken, $redstoneToken, $triggerValue, $side, $outputValue, $eventType, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "INSERT INTO redstone_events (redstone_token, storage_token, event_type, trigger_value, side, output, user_id) VALUES " .
				"('".$redstoneToken."', '".$storageToken."', ".$eventType.", ".$triggerValue.", '".$side."', ".$outputValue.", '".$user_id."')";
	$result = mysqli_query($mysqli,$query);

	if (!($result)) {
		$statusNode = $xmlDoc->createElement('status', $query);

		dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
	} else {
		$statusNode = $xmlDoc->createElement('status', 'success');
	}

	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

function loadRedstoneEvents($mysqli, $xmlDoc, $user_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query = "SELECT * from redstone_events where user_id = '".$user_id."'";
	$result = mysqli_query($mysqli,$query);

	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		$controlNode = $xmlDoc->createElement('events');

		$query2 = "SELECT computer_name, last_seen FROM tokens WHERE token = '".$row['redstone_token']."'";
		$result2 = mysqli_query($mysqli,$query2);
		$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);
		$controlNode->setAttribute('redstone_module', $row2['computer_name']);
		$datetime1 = strtotime($row2['last_seen']);
		$datetime2 = time();
		$diff = $datetime2-$datetime1;
		if ($diff > 200) {
			$controlNode->setAttribute('redstone_active', false);
		} else {
			$controlNode->setAttribute('redstone_active', true);
		}

		$query3 = "SELECT computer_name, last_seen FROM tokens WHERE token = '".$row['storage_token']."'";
		$result3 = mysqli_query($mysqli,$query3);
		$row3 = mysqli_fetch_array($result3, MYSQLI_ASSOC);
		$controlNode->setAttribute('storage_module', $row3['computer_name']);
		$datetime1 = strtotime($row3['last_seen']);
		$datetime2 = time();
		$diff = $datetime2-$datetime1;
		if ($diff > 200) {
			$controlNode->setAttribute('storage_active', false);
		} else {
			$controlNode->setAttribute('storage_active', true);
		}

		$controlNode->setAttribute('event_type', $row['event_type']);
		$controlNode->setAttribute('trigger_value', $row['trigger_value']);
		$controlNode->setAttribute('side', $row['side']);
		$controlNode->setAttribute('output', $row['output']);
		$controlNode->setAttribute('event_id', $row['event_id']);
		$recordDataNode->appendChild($controlNode);
	}

	return $recordDataNode;
}

function removeEvent($mysqli, $xmlDoc, $event_id) {
	$recordDataNode = $xmlDoc->createElement('recorddata');

	$query2 = "DELETE FROM redstone_events WHERE event_id = '".$event_id."'";
	$result2 = mysqli_query($mysqli,$query2);

	if (!($result2)) {
			$statusNode = $xmlDoc->createElement('status', $query2);

			dbError($xmlDoc, $recordDataNode, mysqli_error($mysqli));
		} else {
			$statusNode = $xmlDoc->createElement('status', 'success');
		}

	$recordDataNode->appendChild($statusNode);

	return $recordDataNode;
}

?>
