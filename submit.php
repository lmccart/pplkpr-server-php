<?php	

	require_once("functions.php");

	$type = clean($_POST['type']);
	$user = clean($_POST['user']);
	$name = clean($_POST['name']);
	$moments = clean($_POST['moments']);
	$rating = clean($_POST['rating']);

	
	
	if ($type == "interaction") {
		submit($type, $name, $moments, $rating);
	}
	
	//submit("testuser", "testname", "testmoments", 0);
?>