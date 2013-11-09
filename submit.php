<?php	

	require_once("functions.php");

	$json = json_decode(file_get_contents('php://input'),1);
 
	$func = clean($json['func']);
	$user = clean($json['user']);
	$name = clean($json['name']);
	$moments = clean($json['moments']);
	
	
	if (!$func) $func = clean($_GET['func']);
	if (!$user) $user = clean($_GET['user']);
	if (!$name) $name = clean($_GET['name']);
	if (!$moments) $moments = clean($_GET['moments']);

	
	
	if ($func == "interaction") {
		submit($user, $name, $moments);
	}
	
	if ($func == "maxs") {
		getMaxs($user, $name);
	}

?>