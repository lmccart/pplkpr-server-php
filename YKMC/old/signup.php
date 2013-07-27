<?php 
	session_start();
	include("header.php"); 
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
        
        	

<form id="loginForm" name="loginForm" method="post" action="signup-exec.php">
          <table border="0" align="center" cellpadding="2" cellspacing="0">
            <tr>
              <td>email address<input name="email" type="text" class="textfield" id="email" value="<?php echo $_SESSION['TEMP_SESS_EMAIL']; ?>"/></td>
            </tr>
            <tr>
              <td>username <input name="login" type="text" class="textfield" id="login" value="<?php echo $_SESSION['TEMP_SESS_USERNAME']; ?>"/></td>
            </tr>
            <tr>
              <td>password<input name="password" type="password" class="textfield" id="password" /></td>
          </tr>
          <tr>
          <td>confirm password <input name="cpassword" type="password" class="textfield" id="cpassword" /></td>
          </tr>
            
          <tr>
          <td>mobile number<input name="number" type="text" class="textfield" id="number" /></td>
          </tr>
             <tr>
              <td>carrier
              <select name="carrier">
              <option value=""></option>
              <?php
                $vals = array('at&t', 'sprint', 't-mobile', 'verizon');
                for($i = 0; $i < count($vals); $i++){
					echo '<option value="'.$vals[$i].'">'.$vals[$i].'</option>';
                }
              ?>
              </select>
              </td> 
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
              <td><input type="submit" name="Submit" value="sign up" /></td>
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
