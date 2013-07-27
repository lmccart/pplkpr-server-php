<?php
	//Start session
	session_start();
	
	//Include database connection details
	require_once('config.php');
	
	//Array to store validation errors
	$errmsg_arr = array();
	
	//Validation error flag
	$errflag = false;
	
	//Connect to mysql server
	$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
	if(!$link) {
		die('Failed to connect to server: ' . mysql_error());
	}
	
	//Select database
	$db = mysql_select_db(DB_DATABASE);
	if(!$db) {
		die("Unable to select database");
	}
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
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
	
	//update vals
	$qry0 = "INSERT INTO turns(game_id, user, response) 
	VALUES('$_SESSION[GAMEID]', '$_SESSION[LOGIN]', '$answer')";
	$result0=mysql_query($qry0);

	$qry1 = "INSERT INTO turns(game_id, user, response) 
	VALUES('$_SESSION[GAMEID]', '$_SESSION[LOGIN]', '$question')";
	$result1=mysql_query($qry1);
	
	
	$g_qry="UPDATE games SET turn='$_SESSION[LOGIN]' WHERE id='$_SESSION[GAMEID]'";
	$g_result=mysql_query($g_qry);
	
	
	//get player names
	$i_qry="SELECT * FROM games WHERE id='$_SESSION[GAMEID]'";
	$i_result=mysql_query($i_qry);
	
	if($i_result) {
		if(mysql_num_rows($i_result) == 1) {
			$g = mysql_fetch_assoc($i_result);
			if ($_SESSION['LOGIN'] === $g['player0']) {
				$me = $g['player0'];
				$you = $g['player1'];
			} else {
				$me = $g['player1'];
				$you = $g['player0'];
			}
		}
	}
	
	//get number
	$m_qry="SELECT * FROM users WHERE login='$you'";
	$m_result=mysql_query($m_qry);
	$num = 0;
	
	if($m_result) {
		if(mysql_num_rows($m_result) == 1) {
			$m = mysql_fetch_assoc($m_result);
			$num = $m['number'];
		}
	}
	
	//Check whether the query was successful or not
	if($num != 0) {
		//send text
		$a_msg = $me.': '.$answer;
		mail($num, '', $a_msg, 'From: '.$me.'@YKMC' ) or die( 'Cant mail' );
		
		sleep(1);
		
		$q_msg = 'next question: '.$question;
		mail($num, '', $q_msg.' http://lauren-mccarthy.com/YKMC/?u='.$you, 'From: '.$me.'@YKMC' ) or die( 'Cant mail' );
		
	} 
	
	
	session_regenerate_id();	
	$_SESSION['TURN'] = $_SESSION['LOGIN'];	
		
	session_write_close();
	header("location: ./");
	exit();
?>