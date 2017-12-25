<?php 

	session_save_path('/tmp');
	session_start();

	include("connect.php");

	$id = mysqli_real_escape_string($connection, $_GET['id']);

	$sql = "DELETE FROM comments WHERE id = $id";
	$result = mysqli_query($connection, $sql);
	mysqli_free_result($result);

	$sql = "DELETE FROM likes WHERE commentid = $id";
	$result = mysqli_query($connection, $sql);
	mysqli_free_result($result);

	echo $sql;
?>

