<?php 

	session_save_path('/tmp');
	session_start();

	include("connect.php");

	$commentid = mysqli_real_escape_string($connection, $_GET['id']);
	$userid = mysqli_real_escape_string($connection, $_SESSION['userid']);
	$like = $_GET['like'];

	if($like == '0') 
	{
		$sql = "INSERT INTO likes (commentid, userid) VALUES ($commentid, $userid)";
	}
	else 
	{
		$sql = "DELETE FROM likes WHERE commentid = $commentid AND userid = $userid";
	}

	$result = mysqli_query($connection, $sql);

	mysqli_free_result($result);

	echo $sql;
?>

