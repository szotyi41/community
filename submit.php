<?php 

	session_save_path('/tmp');	
	session_start();

	if(isset($_POST['post-text']) && (!empty($_POST['post-text']))) 
	{
		include("connect.php");

		$userid = $_SESSION['userid'];
		$text = mysqli_real_escape_string($connection, $_POST['post-text']);

		$sql = "INSERT INTO comments (userid, text) VALUES ($userid, '$text');";
		$result = mysqli_query($connection, $sql);

		mysqli_free_result($result);


	}

?>

