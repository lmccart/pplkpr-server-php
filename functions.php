<?php
	include('config.php');	
	
	connect();
	
	function connect() {
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

	}
	
	function submit($user, $name, $moments, $rating) {
		$qry = "INSERT INTO interactions(user, name, moments, rating) 
		VALUES('$user', '$name', '$moments', '$rating')";
		$result=mysql_query($qry);
	}	

	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
?>