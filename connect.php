<?php 

	error_reporting(E_ALL^E_NOTICE);

	$host 		 = "localhost";
	$username  = "root";
	$password  = "";
	$database  = "comments";
	$connected = false;

	$connection = mysqli_connect($host, $username, $password, $database) 
		or die("Failed to connect to MySQL: ".mysqli_connect_error());

	mysqli_set_charset($connection, 'utf8');

	if (!mysqli_connect_errno())
	{
		$connected = true;
	}

?>