

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
	        <form id="loginForm" name="loginForm" method="post" action="login-exec.php">
	          <table border="0" align="center" cellpadding="2" cellspacing="0">
	            <tr>
	              <td>login&nbsp;<input name="login" type="text" class="textfield" id="login" /></td>
	            </tr>
	            <tr>
	              <td>password&nbsp;<input name="password" type="password" class="textfield" id="password" /></td>
	            </tr>
	            <tr><td>&nbsp;</td></tr>
	            <tr>
	              <td><input type="submit" name="Submit" value="login" /></td>
	            </tr>
	            <tr><td>&nbsp;</td></tr>
	            <tr>
	              <td>need to <a href="./signup.php">sign up</a>?</td>
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

