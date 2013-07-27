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
	$email = clean($_POST['email']);
	$login = clean($_POST['login']);
	$password = clean($_POST['password']);
	$cpassword = clean($_POST['cpassword']);
	$number = clean($_POST['number']);
	$carrier = $_POST['carrier'];
	$contacts = serialize(array());
	
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
	
	
	//Save vals for putting back in form
	/*$_SESSION['TEMP_SESS_EMAIL'] = $email;
	$_SESSION['TEMP_SESS_FIRST_NAME'] = $fname;
	$_SESSION['TEMP_SESS_LAST_NAME'] = $lname;
	$_SESSION['TEMP_SESS_BIRTHYEAR'] = $birthyear;
	$_SESSION['TEMP_SESS_GENDER'] = $gender;
	if($asknum != '') { $_SESSION['TEMP_SESS_ASKNUM'] = $asknum; }
	if($askperiod != '') {$_SESSION['TEMP_SESS_ASKPERIOD'] = $askperiod; }
	if($askstart_hour != '') {$_SESSION['TEMP_SESS_ASKSTART_HOUR'] = $askstart_hour; }
	if($askend_hour != '') {$_SESSION['TEMP_SESS_ASKEND_HOUR'] = $askend_hour; }
	$_SESSION['TEMP_SESS_PRIVACY'] = $privacy; 
	$_SESSION['TEMP_SESS_USERNAME'] = $login;		*/
	
	
	
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
	$qry = "INSERT INTO users(email, login, password, number) VALUES('$email', '$login','".md5($_POST['password'])."','$number')";
	$result = mysql_query($qry);
	
	//Check whether the query was successful or not
	if($result) {
	
		session_regenerate_id();
		$_SESSION['EMAIL'] = $email;
		$_SESSION['LOGIN'] = $login;
		$_SESSION['NUMBER'] = $number;	
		$_SESSION['GAMEID'] = 1;			
		
		//Unset temp vals
		/*unset($_SESSION['TEMP_SESS_EMAIL']);
		unset($_SESSION['TEMP_SESS_FIRST_NAME']);
		unset($_SESSION['TEMP_SESS_LAST_NAME']);
		unset($_SESSION['TEMP_SESS_BIRTHYEAR']);
		unset($_SESSION['TEMP_SESS_GENDER']);
		unset($_SESSION['TEMP_SESS_ASKNUM']);
		unset($_SESSION['TEMP_SESS_ASKPERIOD']);
		unset($_SESSION['TEMP_SESS_ASKSTART_HOUR']);
		unset($_SESSION['TEMP_SESS_ASKEND_HOUR']);
		unset($_SESSION['TEMP_SESS_PRIVACY']);
		unset($_SESSION['TEMP_SESS_USERNAME']);	*/	
				
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
		$errmsg_arr[] = 'Oops. Something went wrong, please try submitting registration again.';
		header("location: enter.php");
		exit();
	}
?>