<?php
	if($_SESSION['login_role'] == 'visitor')
	{
		header("location:index.php");
	}
	else
	{
		logout();
	}	
?>