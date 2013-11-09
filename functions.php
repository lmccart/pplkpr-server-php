<?php
	include('config.php');	
	
	$emotions = array('Excited', 'Aroused', 'Angry', 'Scared', 'Anxious', 'Bored', 'Calm');
	
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
	
	function submit($user, $name, $moments) {

		$moments = explode(";", $moments);
		$n = count($moments)+1;

		global $emotions;
		foreach ($moments as $m) {
			if (in_array($m, $emotions)) {
				$qry = "INSERT INTO ".$m."(user, name, rating) 
					VALUES('$user', '$name', '$n')";
				mysql_query($qry);
			}					
		}

		getMaxs($user, $name);
	}	
	
	function getMaxs($user, $name) {

		global $emotions;

		$response = array();
		
		foreach ($emotions as $e) {
			$qry = "SELECT COUNT(*) AS `Rows`, name 
							FROM ".$e." 
							WHERE user='$user'
							GROUP BY name
							ORDER BY `Rows` DESC
							LIMIT 1";
							
			$res = mysql_query($qry);
			if ($res) {
			 	$r = mysql_fetch_assoc($res);
			 	if ($r['name']) {
				 	$response[$e] = $r['name'];
				}
			}					
		}
		echo json_encode($response);
		    //header('Content-type: application/json');
    //echo json_encode(array("hi"));
		//return json_encode($arr, JSON_FORCE_OBJECT);
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