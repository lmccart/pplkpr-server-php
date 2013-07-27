<?php
	
	require_once('functions.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	connect();
	
		
	//Sanitize the POST values
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	
	//Input Validations
	if($login == '') {
		$errmsg_arr[] = 'Please enter login.';
		$errflag = true;
	}
	if($password == '') {
		$errmsg_arr[] = 'Please enter password.';
		$errflag = true;
	}
	
	//If there are input validations, redirect back to the login form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: login.php");
		exit();
	}
	
	//Create query
	$qry="SELECT * FROM users WHERE login='$login' AND password='".md5($_POST['password'])."'";
	$result=mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
		if(mysql_num_rows($result) == 1) {
			//Login Successful
			session_regenerate_id();
			$member = mysql_fetch_assoc($result);
			$_SESSION['EMAIL'] = $member['email'];
			$_SESSION['LOGIN'] = $member['login'];
			$_SESSION['NUMBER'] = $member['number'];	
			$_SESSION['GAMEID'] = $member['game_id'];	
			
			//Lookup game info
			$g_qry="SELECT * FROM games WHERE id='$_SESSION[GAMEID]'";
			$g_result=mysql_query($g_qry);
			
			//Check whether the query was successful or not
			if($g_result) {
				if(mysql_num_rows($g_result) == 1) {
					
					$g_member = mysql_fetch_assoc($g_result);
					$_SESSION['TURN'] = $g_member['turn'];			
					
				}
			}
			session_write_close();
			header("location: ./");
			exit();
		}else {
			//Login failed
			$errmsg_arr[] = 'Login ID and password don\'t match.';
			$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
			session_write_close();
			header("location: login.php");
			exit();
		}
	}else {
		die("Query failed");
	}
?>