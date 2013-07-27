<?php	

	require_once("functions.php");
	
	if (true) {
		$type = clean($_POST['type']);
		$user = clean($_POST['user']);
		$name = clean($_POST['name']);
		$moments = clean($_POST['moments']);
		$rating = clean($_POST['rating']);
		
	} else { // for testing
		$type = clean($_GET['type']);
		$user = clean($_GET['user']);
		$name = clean($_GET['name']);
		$moments = clean($_GET['moments']);
		$rating = clean($_GET['rating']);
	}
	
	
	if ($type == "interaction") {
		submit($user, $name, $moments, $rating);
	}
?>