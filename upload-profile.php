<?php
	session_save_path('/tmp');
	session_start();

	include('connect.php');

	if(isset($_FILES['profile-upload']['tmp_name']) and ($_FILES['profile-upload']['tmp_name']))
	{
		$upload = true;
		$file = $_FILES['profile-upload']['tmp_name'];
		$profiles = 'images/profiles/';
		$to = $_SERVER['DOCUMENT_ROOT'].'/community/'.$profiles;
		$destination = $to.basename($_FILES['profile-upload']['name']);


		if($file) 
		{

			if(!getimagesize($file)) {
				$wrong['profile-upload'] = 'Your file is not an image file.';
				$upload = false;
				$error = true;
			}

			if($upload) {
				$_SESSION['file'] = basename($_FILES['profile-upload']['name']);
				move_uploaded_file($file, $destination);
			}
		}
	}
?>
