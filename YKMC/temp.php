	
	//accept/decline invite
	if (!empty($_GET['accept'])) {
	
		//Lookup game info
		$g_qry="SELECT * FROM games WHERE id='$_SESSION[GAMEID]'";
		$g_result=mysql_query($g_qry);
		
		//Check whether the query was successful or not
		if($g_result) {
			if(mysql_num_rows($g_result) == 1) {
			
				if ($_GET['accept'] === 'true') {
					//update game info
					$u_qry="UPDATE games SET player1='$_SESSION[LOGIN]', invited='' WHERE id='$_SESSION[GAMEID]'";
					$u_result=mysql_query($u_qry);
					
					$g_member = mysql_fetch_assoc($g_result);
					$_SESSION['TURN'] = $g_member['turn'];	
					unset($_SESSION['INVITEDBY']);
					unset($_SESSION['INVITED']);
				} else {
					//remove game
					removeGame($_SESSION['GAMEID']);
					unset($_SESSION['INVITEDBY']);
					unset($_SESSION['INVITED']);
					header("location: ./");
					exit();
				}
			}
		}
			
		if ($_GET['accept'] === 'true') {
				
		
		} else {
						
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
		}
	}
	