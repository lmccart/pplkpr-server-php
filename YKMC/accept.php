

<?php
	require_once("functions.php"); 
	include("header.php"); 
	

?>

<body>

<div class="wrapper">

    <div id="text">
        <div id="text-inner">
        	
        	<h1>YKMC</h1>

        	<?php 
        		echo '<p>'.$_SESSION['INVITEDBY'].' has invited you to play!<br><br>';
        		echo 'Would you like to <a href="./index.php?g=accept">accept</a> or <a href="./index.php?g=decline">decline</a>?</p>';
        	?>
            
        </div>
    </div>
    <div class="push"></div>

</div>

<?php include("footer.php"); ?>

</body>
</html>

