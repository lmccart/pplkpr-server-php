<?php	
	function unsetUser(){
		unset($_SESSION['EMAIL']); 
		unset($_SESSION['LOGIN']); 
		unset($_SESSION['NUMBER']); 
		
	}
	
	
	function unsetUserRedirect(){
		unsetUser();
		
		// return - redirect to normal index
		echo "<script>location.href='http://lauren-mccarthy.com/YKMC/enter.php'</script>";
	}
	
?>