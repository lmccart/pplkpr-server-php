<?php	

	require_once("functions.php");

	$type = clean($_GET['type']);
	$user = clean($_GET['user']);
	$name = clean($_GET['name']);
	$moments = clean($_GET['moments']);
	$rating = clean($_GET['rating']);

	
	
	if ($type == "interaction") {
		submit($user, $name, $moments, $rating);
	}
	
	//submit("testuser", "testname", "testmoments", 0);
?>