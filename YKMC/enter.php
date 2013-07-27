<?php 
	require_once("functions.php");
	unsetUser();
	include("header.php"); 
?>

<body>

<div class="wrapper">

    <div id="text">
        <div id="text-inner">
        	
        	<h1>YKMC</h1>
        
        	<?php 
        		if (isset($_SESSION['INVITEDBY'])) {
        			echo '<p>'.$_SESSION['INVITEDBY'].' has invited you to play!<br><br>';
        		} else echo '<p>';
        	?>
            Would you like to <a href="http://lauren-mccarthy.com/YKMC/login.php">login</a>?<br><br>
            Or do you need to <a href="http://lauren-mccarthy.com/YKMC/signup.php">sign up</a>?</p>
            
            
        </div>
    </div>
    <div class="push"></div>

</div>

<?php include("footer.php"); ?>

</body>
</html>
