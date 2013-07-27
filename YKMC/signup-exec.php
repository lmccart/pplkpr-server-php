<?php

	require_once('functions.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	connect();
	
	
	//Sanitize the POST values
	$email = clean($_POST['email']);
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	$cpassword = clean($_POST['cpassword']);
	$number = clean($_POST['number']);
	$carrier = $_POST['carrier'];
	
	//Input Validations
	if($email == '') {
		$errmsg_arr[] = 'Please enter email address.';
		$errflag = true;
	}
	else if($login == '') {
		$errmsg_arr[] = 'Please enter login.';
		$errflag = true;
	}
	else if($password == '') {
		$errmsg_arr[] = 'Please enter password.';
		$errflag = true;
	}
	else if($cpassword == '') {
		$errmsg_arr[] = 'Please confirm password.';
		$errflag = true;
	}
	else if( strcmp($password, $cpassword) != 0 ) {
		$errmsg_arr[] = 'Passwords do not match.';
		$errflag = true;
	}
	else if($number == ""){
		$errmsg_arr[] = 'Please enter mobile number.';
		$errflag = true;
	}
	else if($carrier == '') {
		$errmsg_arr[] = 'Please select carrier.';
		$errflag = true;
	}
	


	
	//Check for duplicate login ID
	else if($login != '') {
		$qry = "SELECT * FROM users WHERE login='$login'";
		$res = mysql_query($qry);
		if($res) {
			if(mysql_num_rows($res) > 0) {
				$errmsg_arr[] = 'Login already in use';
				$errflag = true;
			}
			mysql_free_result($res);
		}
		else {
			die("Query failed");
		}
	}
	
	
	//If there are input validations, redirect back to the registration form
	if($errflag) {
		$_SESSION['ERRMSG_ARR'] = $errmsg_arr;
		session_write_close();
		header("location: ./signup.php");
		exit();
	}
	
	
	
	if ($carrier === "at&t") $number .= "@txt.att.net";
	else if ($carrier === "sprint") $number .= "@messaging.sprintpcs.com";
	else if ($carrier === "t-mobile") $number .= "@tmomail.net";
	else if ($carrier === "verizon") $number .= "@vtext.com";
	

	//Create INSERT query
	$qry = "INSERT INTO users(email, login, password, number, game_id) VALUES('$email', '$login','".md5($_POST['password'])."','$number', -1)";
	$result = mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
	
		session_regenerate_id();
		$_SESSION['EMAIL'] = $email;
		$_SESSION['LOGIN'] = $login;
		$_SESSION['NUMBER'] = $number;	
		$_SESSION['GAMEID'] = -1; //PEND TEMP, need to check for invite			
				
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
	
		header("location: index.php");
		exit();
	}else {
		//Login failed
		$_SESSION['ERRMSG_ARR'] = 'Oops. Something went wrong, please try submitting registration again.';
		header("location: enter.php");
		exit();
	}
?>