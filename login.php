<?php

	if(isset($_POST['login']) and ($_POST['login']))
	{
		if(!empty("username") and (!empty("password")))
		{
			logIn();
		}
	}
	
?>