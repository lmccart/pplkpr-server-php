<?php 

	session_start();
	require_once("functions.php");
	
	connect();
	
	//responding to invitation, set sess var
	if (!empty($_GET['i'])) {
		$_SESSION['INVITEDBY'] = $_GET['i'];
	}
	
	//redirect to enter page for login
	if (empty($_GET['u']) && ((!isset($_SESSION['EMAIL'])) || (!isset($_SESSION['LOGIN'])) || (!isset($_SESSION['NUMBER'])))) {
		unsetUserRedirect();
	}
	
	//accept/decline invite
	else if (!empty($_GET['g']) && isset($_SESSION['INVITEDBY'])) {
		if ($_GET['g'] === 'accept') {
			acceptInvite();
		} else {
			declineInvite();
		}
		
		header("location: ./");
		exit();
	}

	//redirect to invite page
	else if (empty($_GET['g']) && isset($_SESSION['INVITEDBY'])) {
		header("location: ./accept.php");
		exit();
	}
	
	else if ($_SESSION['GAMEID'] < 0) {
		header("location: ./invite.php");
		exit();
	}
	
	

	//responding to turn
	else if (!empty($_GET['u'])) {

		
		//Sanitize the POST values
		$login = clean($_GET['u']);


		//Create query
		$qry="SELECT * FROM users WHERE login='$login'";
		$result=mysql_query($qry);
		
		//Check whether the user is registered (should be)
		if($result) {
			if(mysql_num_rows($result) == 1) {
				//Login Successful
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
						$_SESSION['INVITED'] = $g_member['invited'];		
					}
				}

				header("location: ./");
				exit();
				
			}else {
				header("location: enter.php");
				exit();
			}
		}
	}

?>

<?php include("header.php"); ?>

<body>

<div class="wrapper">

    <div id="text">
        <div id="text-inner">
        	
        	<h1>YKMC</h1>
        	
        	<h2>welcome, <?php echo $_SESSION['LOGIN']; ?>!</strong></h2>
        	
			<?php //get all turns
			
				$qry="SELECT * FROM turns WHERE game_id='$_SESSION[GAMEID]' ORDER BY time";
				$result=mysql_query($qry);
				
				//Check whether the query was successful or not
				if($result) {
					echo '<p>';
					while($temp = mysql_fetch_assoc($result)) {
						echo "<strong>".$temp['user'].":</strong> ".$temp['response']." <span class='time'>".$temp['time']."</span><br><br>";
					}
					echo '</p>';
				} 
			?>
        	
        	<?php 
        	
        	if ($_SESSION['INVITED'] != '') {
        		echo '<p>*waiting for '.$_SESSION['INVITED'].' to accept your invitation to play*
	              <br><br><a href="./enter.php">logout</a></p>';
	      	} else if ($_SESSION['TURN'] === $_SESSION['LOGIN']) {
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

