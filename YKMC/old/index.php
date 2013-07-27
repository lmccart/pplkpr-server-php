<?php 

	session_start();
	include("functions.php");
	
	
	// get user if specified
	$uri = $_GET['u'];
	
	if (!empty($_GET['u'])) {
	
		
		//Include database connection details
		require_once('config.php');
		
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
		$login = clean($_GET['u']);


		//Create query
		$qry="SELECT * FROM users WHERE login='$login'";
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
				$_SESSION['GAMEID'] = 1;	
				
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
				header("location: enter.php");
				exit();
			}
		}
	}
	
	else if (empty($_GET['u']) && ((!isset($_SESSION['EMAIL'])) || (!isset($_SESSION['LOGIN'])) || (!isset($_SESSION['NUMBER'])))) {
		unsetUserRedirect();
	}

	
	//Include database connection details
	require_once('config.php');
	
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

?>

<?php include("header.php"); ?>

<body>

<div class="wrapper">

    <div id="text">
        <div id="text-inner">
        	
        	<h1>YKMC</h1>
        	
        	<h2>welcome, <?php echo $_SESSION['LOGIN']; ?>!</strong></h2>
        	
			<?php //Create query
				$qry="SELECT * FROM turns WHERE game_id='$_SESSION[GAMEID]' ORDER BY time";
				$result=mysql_query($qry);
				
				//Check whether the query was successful or not
				if($result) {
					while($temp = mysql_fetch_assoc($result)) {
						echo "<strong>".$temp['user'].":</strong> ".$temp['response']." <span class='time'>".$temp['time']."</span><br><br>";
					}
				} 
			?>
        	
        	<?php 
        	
        	if ($_SESSION['TURN'] === $_SESSION['LOGIN']) {
        		echo '<p>*waiting for your partner to respond*
	              <br><br><a href="./enter.php">logout</a></p>';
        	} else {
        	
        	
	           if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
	                echo '<ul class="err">';
	                foreach($_SESSION['ERRMSG_ARR'] as $msg) {
	                    echo '<li>',$msg,'</li>'; 
	                }
	                echo '</ul>';
	                unset($_SESSION['ERRMSG_ARR']);
	           }
	           
        	 echo '<form id="loginForm" name="loginForm" method="post" action="post-exec.php">
	          <table border="0" align="center" cellpadding="2" cellspacing="0">
	            <tr>
	              <td>answer:<br><textarea name="answer" type="text" class="textfield" rows="10" cols="40" id="answer" /></textarea></td>
	            </tr>
	            
	            <tr>
	            <td><br>next question:<br><textarea name="question" type="text" class="textfield" rows="10" cols="40" id="question" /></textarea></td>
	            </tr>
	            <tr><td>&nbsp;</td></tr>
	            <tr>
	              <td><input type="submit" name="Submit" value="submit" /></td>
	            </tr>
	            <tr><td>&nbsp;</td></tr>
	            <tr>
	              <td><a href="./enter.php">logout</a></td>
	            </tr>
	          </table>
	        </form>';
	        }
	        ?>
	      
            
        </div>
    </div>
    <div class="push"></div>

</div>

<?php include("footer.php"); ?>

</body>
</html>

