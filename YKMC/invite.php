<?php 
	require_once('functions.php');
	include("header.php"); 
	
	connect();
?>

<body>

<div class="wrapper">

    <div id="text">
        <div id="text-inner">
        
	        
        	<h1>YKMC</h1>
        	
        	
			<?php
	            if( isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) >0 ) {
	                echo '<ul class="err">';
	                foreach($_SESSION['ERRMSG_ARR'] as $msg) {
	                    echo '<li>',$msg,'</li>'; 
	                }
	                echo '</ul>';
	                unset($_SESSION['ERRMSG_ARR']);
	            }
	        ?>
        
        	
		
		<form id="inviteForm" name="inviteForm" method="post" action="invite-exec.php">
          <table border="0" align="center" cellpadding="2" cellspacing="0">
            <tr>
              <td>Enter the email address of the person you would like to play with:<br><input name="email" type="text" class="textfield" id="email" /></td>
            </tr>
             <tr>
              <td>Or, choose the person you would like to play with:<br>
              <select name="person">
              <option value=""></option>
              <?php
                $vals = getFreeUsers();
                for($i = 0; $i < count($vals); $i++){
					echo '<option value="'.$vals[$i].'">'.$vals[$i].'</option>';
                }
              ?>
              </select>
              </td> 
            </tr>
            <tr>
              <td><br>Ask your first question to begin the game:<br><textarea name="question" type="text" class="textfield" rows="10" cols="40" id="question" /></textarea></td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
              <td><input type="submit" name="Submit" value="invite" /></td>
            </tr>
          </table>
        </form>

            
        </div>
    </div>
    <div class="push"></div>

</div>

<?php include("footer.php"); ?>

</body>
</html>
