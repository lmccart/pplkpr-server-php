<?php

	require_once('functions.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	connect();
	

	
	//Sanitize the POST values
	$answer = clean($_POST['answer']);
	$question = clean($_POST['question']);
	
	//Input Validations
	if($answer == '') {
		$errmsg_arr[] = 'Please enter your answer.';
		$errflag = true;
	}
	if($question == '') {
		$errmsg_arr[] = 'Please enter a new question.';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: ./");
		exit();
	}
	
	doTurn($question, $answer, false);
?>