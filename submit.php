<?php	

	require_once("functions.php");

	$json = json_decode(file_get_contents('php://input'),1);
 
	$func = clean($json['func']);
	$user = clean($json['user']);
	$name = clean($json['name']);
	$emotion = clean($json['emotion']);
	
	
	if (!$func) $func = clean($_GET['func']);
	if (!$user) $user = clean($_GET['user']);
	if (!$name) $name = clean($_GET['name']);
	if (!$emotion) $emotion = clean($_GET['emotion']);

	
	
	if ($func == "interaction") {
		submit($user, $name, $emotion);
	}
	
	if ($func == "maxs") {
		getMaxs($user, $name);
	}

?>