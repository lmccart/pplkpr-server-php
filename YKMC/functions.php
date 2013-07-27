<?php	

	require_once("config.php");
	
	//Start session
	session_start();
	
	function getRoot() {
		return 'http://lauren-mccarthy.com/YKMC/';
	}

	function unsetUser(){
		unset($_SESSION['EMAIL']); 
		unset($_SESSION['LOGIN']); 
		unset($_SESSION['NUMBER']); 
		unset($_SESSION['GAMEID']); 
		unset($_SESSION['INVITED']); 
		unset($_SESSION['TURN']); 
	}
	
	function unsetUserRedirect(){
		unsetUser();
		
		// return - redirect to normal index
		echo "<script>location.href='".getRoot()."enter.php'</script>";
	}
	
	
	//Function to sanitize values received from the form. Prevents SQL injection
	function clean($str) {
		$str = @trim($str);
		if(get_magic_quotes_gpc()) {
			$str = stripslashes($str);
		}
		return mysql_real_escape_string($str);
	}
	
	
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
	
	function createNewGame($usr, $isRegistered) {
	
		//enter new game		
		mysql_query("INSERT INTO games(turn, player0, invited)
		VALUES ('$_SESSION[LOGIN]', '$_SESSION[LOGIN]', '$usr')");
		
		//update gameid for user
		$id = mysql_insert_id();
		mysql_query("UPDATE users SET game_id='$id' WHERE login='$_SESSION[LOGIN]'");
		$_SESSION['GAMEID'] = $id;
		$_SESSION['INVITED'] = $usr;
		
		notifyPartnerOfInvite($usr, $isRegistered);
	}
	
	function removeGame($gid) {
		//update user info
		$res = mysql_query("SELECT * FROM games WHERE id='$gid'");
		$inviter = "";
		if ($res) {
			if(mysql_num_rows($res) == 1) {
				$g = mysql_fetch_assoc($res);
				$inviter = $g['player0'];
				$invitee = $g['player1'];
				mysql_query("UPDATE users SET game_id='-1' WHERE login='$inviter'");
				mysql_query("UPDATE users SET game_id='-1' WHERE login='$invitee'");
				
				$_SESSION['GAMEID'] = $id;
			}
		}
	
		//remove game
		mysql_query("DELETE FROM games WHERE id='$gid'");
		
		//notify inviter
		//get number
		$m_result=mysql_query("SELECT * FROM users WHERE login='$inviter'");
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
			$q_msg = $invitee.' has declined your invitation to play YKMC. Go to '.getRoot().' to play with someone else.';
			mail($num, '', $q_msg, 'From: info@youkeepmecompany.com' ) or die( 'Cant mail' );
		} 

		$_SESSION['GAMEID'] = -1;
	}
	
	function acceptInvite() {
		//update game w p1, remove invited
		mysql_query("UPDATE games SET player1='$_SESSION[LOGIN]', invited='' WHERE player0='$_SESSION[INVITEDBY]'");
		
		//update user w gameid
		$id_result=mysql_query("SELECT * FROM games WHERE player0='$_SESSION[INVITEDBY]'");
		if($id_result) {
			if(mysql_num_rows($id_result) == 1) {
				$g = mysql_fetch_assoc($id_result);
				$id = $g['id'];
				mysql_query("UPDATE users SET game_id='$id' WHERE login='$_SESSION[LOGIN]'");
				
				$_SESSION['GAMEID'] = $id;
			}
		}
		
		unset($_SESSION['INVITEDBY']);
	}
	
	function declineInvite() {
		$id_result=mysql_query("SELECT * FROM games WHERE player0='$_SESSION[INVITEDBY]'");
		if($id_result) {
			if(mysql_num_rows($id_result) == 1) {
				$u = mysql_fetch_assoc($id_result);
				$id = $u['id'];
				removeGame($id);
			}
		}
		unset($_SESSION['INVITEDBY']);
	}
	
	function doTurn($q, $a, $first) {
				
		//enter answer if not first turn
		if (!$first) { 
			$qry0 = "INSERT INTO turns(game_id, user, response) 
			VALUES('$_SESSION[GAMEID]', '$_SESSION[LOGIN]', '$a')";
			$result0=mysql_query($qry0);
		}
		
		//enter question
		$qry1 = "INSERT INTO turns(game_id, user, response) 
		VALUES('$_SESSION[GAMEID]', '$_SESSION[LOGIN]', '$q')";
		$result1=mysql_query($qry1);
		
		//update game
		$g_qry="UPDATE games SET turn='$_SESSION[LOGIN]' WHERE id='$_SESSION[GAMEID]'";
		$g_result=mysql_query($g_qry);
		
		if (!$first) {
			notifyPartnerOfTurn($q, $a);
		}
		
		$_SESSION['TURN'] = $_SESSION['LOGIN'];	
			
		header("location: ./");
		exit();

	}
	
	function notifyPartnerOfInvite($usr, $isRegistered) {
		if ($isRegistered) {
			//get number
			$m_qry="SELECT * FROM users WHERE login='$usr'";
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
				$q_msg = $_SESSION['LOGIN'].' has invited you to play YKMC. Go to '.getRoot().'?i='.$_SESSION['LOGIN'].' to begin.';
				mail($num, '', $q_msg, 'From: info@youkeepmecompany.com' ) or die( 'Cant mail' );
			} 
	
		} else {
			$q_msg = $_SESSION['LOGIN'].' has invited you to play YKMC. Go to '.getRoot().'?i='.$_SESSION['LOGIN'].' to begin.';
			mail($usr, $_SESSION['LOGIN'].' wants to play YKMC with you!', $q_msg, 'From: info@youkeepmecompany.com' ) or die( 'Cant mail' );
		}
	}
	
	function notifyPartnerOfTurn($q, $a) {
			
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
			$a_msg = $me.': '.$a;
			mail($num, '', $a_msg, 'From: '.$me.'@YKMC' ) or die( 'Cant mail' );
			
			sleep(1);
			
			$q_msg = 'next question: '.$q;
			mail($num, '', $q_msg.' '.getRoot().'?u='.$you, 'From: '.$me.'@YKMC' ) or die( 'Cant mail' );
			
		} 
	}
	
	function getFreeUsers() {
		$users = array();
		
		$qry="SELECT * FROM users WHERE login!='$_SESSION[LOGIN]' AND game_id<0";
		$result=mysql_query($qry);
		
		//Check whether the query was successful or not
		if($result) {
			while($temp = mysql_fetch_assoc($result)) {
				$users[] = $temp['login'];
			}
		}
		
		return $users;
	}
?>