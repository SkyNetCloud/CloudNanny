<?php
// disable error display
error_reporting(E_ALL ^ E_DEPRECATED);
error_reporting(E_ALL | E_NOTICE | E_WARNING);
ini_set('log_errors', 'Off'); 

require_once('main_functions.php');

$mysqli = mysqli_connect('127.0.0.1', 'SkyNetCloud', 'SkyNetCloud#','cloudnanny') or die(print_r(mysqli_error($mysqli)));




// toggle document type xml/html
$usexml = 1;
$debugMode = true;

if (!isset($usexml)) {
    $usexml = 0;
}

if ($usexml == 1) {
    if (isset($_SERVER['HTTP_ACCEPT'])) {
		if (!strpos($_SERVER['HTTP_ACCEPT'], "application/xhtml+xml")) {
			$usexml = 1;
		}
	} else {
		$usexml = 0;
	}
}

// var setup
if ($_REQUEST) {
	$actionArray = explode(',', $_REQUEST['a']);
} else {
	$phpScript = $_SERVER['PHP_SELF'];

	$dieMessage = <<<EOD
No action specified. Exiting.<br>
<a href="$phpScript?a=options">Options</a>
EOD;
	
	die('Die: ' . $dieMessage);
}

// XML document for output
$xmlDoc = new DomDocument('1.0', 'UTF-8');
$xmlDoc->preserveWhiteSpace = false;
$xmlDoc->formatOutput = true;

$xmlRoot = $xmlDoc->createElement('xml_root');
$xmlDoc->appendChild($xmlRoot);

//open database connection
//require_once('connection.php');

$query = '';

// compose XML

if ($debugMode) {
	$debugInfo = $xmlDoc->createElement('debug_info');
	$xmlRoot->appendChild($debugInfo);
	
	$requestOptions = $xmlDoc->createElement('options');	
	$debugInfo->appendChild($requestOptions);
	$textNode = $xmlDoc->createTextNode($_REQUEST['a']);
	$requestOptions->appendChild($textNode);
}

foreach ($actionArray as $action) {
	switch ($action) {
		case "setuprecord":
			$xmlRoot->appendChild(setUpRecord($mysqli, $ldapConfig, $xmlDoc, $_REQUEST['username']));
			
			break;
		case "getPosts":
			$xmlRoot->appendChild(getPosts($mysqli, $xmlDoc, $_REQUEST['page'], $_REQUEST['sub'], $_REQUEST['user'], $_REQUEST['comments']));
			
			break;
		case "email":
			phpMailer($xmlDoc, $_REQUEST['name'], $_REQUEST['email'], $_REQUEST['subject'], $_REQUEST['msg']);
			
			break;
		case "doesUserExist":
			$xmlRoot->appendChild(doesUserExist($mysqli, $xmlDoc, $_REQUEST['id'], $_REQUEST['user_type']));
			
			break;
		case "addGoogleUser":
			$xmlRoot->appendChild(addGoogleUser($mysqli, $xmlDoc, $_REQUEST['id'], $_REQUEST['name'], $_REQUEST['email'], $_REQUEST['url']));
			
			break;
		case "addPost":
			$xmlRoot->appendChild(addPost($mysqli, $xmlDoc, $_REQUEST['id'], $_REQUEST['title'], $_REQUEST['text'], $_REQUEST['url'], $_REQUEST['sub']));
			
			break;
		case "userInfo":
			$xmlRoot->appendChild(userInfo($mysqli, $xmlDoc, $_REQUEST['id']));
			
			break;
		case "editUsername":
			$xmlRoot->appendChild(editUsername($mysqli, $xmlDoc, $_REQUEST['id'], $_REQUEST['username']));
			
			break;
		case "castVote":
			$xmlRoot->appendChild(castVote($mysqli, $xmlDoc, $_REQUEST['user_id'], $_REQUEST['post_id'], $_REQUEST['vote']));
			
			break;
		case "tallyVotes":
			$xmlRoot->appendChild(tallyVotes($mysqli, $xmlDoc, $_REQUEST['post_id']));
			
			break;
		case "checkForUserVote":
			$xmlRoot->appendChild(checkForUserVote($mysqli, $xmlDoc, $_REQUEST['post_id'], $_REQUEST['user_id']));
			
			break;
		case "addComment":
			$xmlRoot->appendChild(addComment($mysqli, $xmlDoc, $_REQUEST['user_id'], $_REQUEST['post_id'], $_REQUEST['comment']));
			
			break;
		case "getComments":
			$xmlRoot->appendChild(getComments($mysqli, $xmlDoc, $_REQUEST['post_id']));
			
			break;
		case "addNewUser":
			$xmlRoot->appendChild(addNewUser($mysqli, $xmlDoc, $_REQUEST['username'], $_REQUEST['password'], $_REQUEST['email']));
			
			break;
		case "signIn":
			$xmlRoot->appendChild(signIn($mysqli, $xmlDoc, $_REQUEST['username'], $_REQUEST['password']));
			
			break;
		case "getConnections":
			$xmlRoot->appendChild(getConnections($mysqli, $xmlDoc, $_REQUEST['user_id'], $_REQUEST['module_type']));
			
			break;
		case "logs":
			$xmlRoot->appendChild(getLogs($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "getPlayerData":
			$xmlRoot->appendChild(getPlayerData($mysqli, $xmlDoc, $_REQUEST['ign'], $_REQUEST['token']));
			
			break;
		case "getUser":
			$xmlRoot->appendChild(getUser($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "load_redstone_controls":
			$xmlRoot->appendChild(loadRedstoneControls($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "setRedstoneOutput":
			$xmlRoot->appendChild(setRedstoneOutput($mysqli, $xmlDoc, $_REQUEST['token'], $_REQUEST['side'], $_REQUEST['value'], $_REQUEST['val_type']));
			
			break;
		case "load_fluid_modules":
			$xmlRoot->appendChild(getFluidLevels($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "load_energy_modules":
			$xmlRoot->appendChild(getEnergyLevels($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "remove_module":
			$xmlRoot->appendChild(removeModule($mysqli, $xmlDoc, $_REQUEST['token']));
			
			break;
		case "redstone_event_dropdowns":
			$xmlRoot->appendChild(redstoneEventDropdowns($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "get_redstone_sides":
			$xmlRoot->appendChild(getRedstoneSides($mysqli, $xmlDoc, $_REQUEST['token']));
			
			break;
		case "create_redstone_event":
			$xmlRoot->appendChild(createRedstoneEvent($mysqli, $xmlDoc, $_REQUEST['storage_token'], $_REQUEST['redstone_token'], $_REQUEST['trigger_value'], $_REQUEST['side'], $_REQUEST['output_value'], $_REQUEST['event_type'], $_REQUEST['user_id']));
			
			break;
		case "load_redstone_events":
			$xmlRoot->appendChild(loadRedstoneEvents($mysqli, $xmlDoc, $_REQUEST['user_id']));
			
			break;
		case "remove_event":
			$xmlRoot->appendChild(removeEvent($mysqli, $xmlDoc, $_REQUEST['event_id']));
			
			break;
		default:

			break; 
	}

}

function sendxhtmlheader($usexml) {
    if ($usexml == 1) {
		//header("Content-Type: application/xhtml+xml; charset=utf-8");
		header("Content-type: text/xml; charset=utf-8");
    } else {
		header("Content-type: text/html; charset=utf-8");
    }
}

function sendpage($page, $usexml) {
    //$xhtmldtd="\n<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\" \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n";
    //$bar=preg_replace('/\n/',$xhtmldtd,$page,1);
    sendxhtmlheader($usexml);
    //print($bar);
	if ($usexml == 0) {
		print('<html><body><pre>' . htmlentities($page) . '</pre></body></html>');
	} else {
		print(trim($page));
	}
}

// close database connection
$mysqli->close();

// save/send the XML document
$xmlString = $xmlDoc->saveXML();

sendpage(trim($xmlString), $usexml);
?>