<?php
	
	require_once('functions.php');
	
	//Connect to mysql server
	connect();
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;	
	
	$email = clean($_POST['email']);
	if ($email == '') $person = $_POST['person'];
	$question = clean($_POST['question']);
	
	//Input Validations
	if($email == '' && $person == '') {
		$errmsg_arr[] = 'Please choose a person you would like to play with.';
		$errflag = true;
	}
	
	if ($question == '') {
		$errmsg_arr[] = 'Please enter your first question to start the game.';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the invite form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		header("location: ./invite.php");
		exit();
	}
	
	//create new game
	if ($email != '') {
		createNewGame($email, false);
	} else {
		createNewGame($person, true);
	}
	
	//do first turn
	doTurn($question, '', true);
?>