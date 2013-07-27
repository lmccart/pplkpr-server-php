<?php	

	require_once("functions.php");

	if (clean($_POST['type'] == "interaction") {
		$user = clean($_POST['user']);
		$name = clean($_POST['name']);
		$moments = clean($_POST['moments']);
		$rating = clean($_POST['rating']);
		
		submit($user, $name, $moments, $rating);
	}
?>