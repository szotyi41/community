
<?php

	session_save_path('/tmp');
	session_start();

	include('connect.php');

	$wrong = array();
	$error = false;
	
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

	if(isset($_POST['register']) and ($_POST['register'])) 
	{
		$_POST['register'] = false;

		if(isset($_POST['fullname'])) 
		{

			if (empty($_POST['fullname'])) 
			{
				$wrong['fullname'] = "Type your full name";
				$error = true;
			}
			else 
			{
				if (strlen($_POST['fullname']) < 4) {
				  $wrong['fullname'] = "Really? Your full name is less than 4 characters?";
				  $error = true;
				}	
			}

		}
		else 
		{
			$error = true;
		}

		if(isset($_POST['email'])) 
		{
			$email = testInput($_POST["email"]);

			if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			  $wrong['email'] = "Invalid email format";
			  $error = true;
			}
		}
		else 
		{
			$error = true;
		}

		if(isset($_POST['username'])) 
		{

			if (empty($_POST['username'])) {
			  $wrong['username'] = "Type your username";
			  $error = true;
			} 
			else 
			{
				if (strlen($_POST['username']) < 4) {
				  $wrong['username'] = "Username length must be more than 3 characters";
				  $error = true;
				}
			}

		}
		else 
		{
			$error = true;
		}

		if((isset($_POST['password1'])) and (isset($_POST['password2']))) 
		{

			if ((strlen($_POST['password1']) < 6) or (strlen($_POST['password2'])) < 6) {
			  $wrong['password'] = "Password length must be more than 5 characters";
			  $error = true;
			}

			if ($_POST['password1'] !== $_POST['password2']) {
				$wrong['password'] = "Passwords must be same";
				$error = true;
			}

		}
		else 
		{
			$error = true;
		}

		if(!$error) {

			$fullname = mysqli_real_escape_string($connection, $_POST['fullname']);
			$username = mysqli_real_escape_string($connection, $_POST['username']);
			$password = mysqli_real_escape_string($connection, sha1($_POST['password1']));
			$email = mysqli_real_escape_string($connection, $_POST['email']);

			$sql = "SELECT username FROM users WHERE username = '$username'";

			$result = mysqli_query($connection, $sql);

			if(mysqli_num_rows($result) > 0) {
				$wrong['username'] = 'This username already registered';
				$error = true;
			}

			mysqli_free_result($result);

		}

		if(!$error) {

			$sql = "SELECT email FROM users WHERE email = '$email'";

			$result = mysqli_query($connection, $sql);

			if(mysqli_num_rows($result) > 0) {
				$wrong['email'] = 'This email already registered';
				$error = true;
			}

			mysqli_free_result($result);

		}

		if(!$error) {

			$sql = "
				INSERT INTO users (username, password, email, fullname, admin) 
				VALUES ('$username', '$password', '$email', '$fullname', 0);";

			$result = mysqli_query($connection, $sql);

			if($result) {
				$regid = mysqli_insert_id($connection);
				$profiles = 'images/profiles/';
				$root = $_SERVER['DOCUMENT_ROOT'].'/community/'.$profiles;
				rename($root.$_SESSION['file'], $root.'user_'.$regid.'.jpg');

				$_SESSION['registered'] = true;
				$_SESSION['registered_name'] = $fullname;
				header("Location: index.php");

			} else {
				echo 'Something wrong: '.mysqli_error($connection);
			}

			mysqli_free_result($result);

		}

	}

	function testInput($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; ">

	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">

	<script src="javascript/jquery-3.2.1.min.js"></script>

<script>

	$(document).ready(function() 
	{
		$('#profile-upload').change(function(event) {
			var tmppath = URL.createObjectURL(event.target.files[0]);
			$('#login-profile-image').attr('src',tmppath);
		});
	});

</script>


</head>

<body>


<div class="container">
	<div class="login">

		<div class="login-profile">
			<img id="login-profile-image" src=
			<?php 
				if(isset($_SESSION['file']) and (is_file('images/profiles/'.$_SESSION['file'])))
					echo 'images/profiles/'.$_SESSION['file'];
				else
					echo 'images/profile.png'; 
			?>>
		</div>

		<form id="register" method="POST" enctype="multipart/form-data">

			<input type="file" name="profile-upload" id="profile-upload" accept="image/*">
			<div class="register-error"><?php if(isset($wrong['profile-upload'])) echo $wrong['profile-upload']; ?></div>

			<input type="textbox" name="fullname" id="fullname" class="textbox" placeholder="full name" 
			value=<?php if(isset($_POST['fullname'])) echo '"'.$_POST['fullname'].'"'; ?>
			>
			<div class="register-error"><?php if(isset($wrong['fullname'])) echo $wrong['fullname']; ?></div>
			
			<input type="email" name="email" id="email" placeholder="email" 
				value=<?php if(isset($_POST['email'])) echo '"'.$_POST['email'].'"'; ?>
			>
			<div class="register-error"><?php if(isset($wrong['email'])) echo $wrong['email']; ?></div>
			
			<input type="textbox" name="username" id="username" class="textbox" placeholder="username" value=
				<?php if(isset($_POST['username'])) echo '"'.$_POST['username'].'"'; ?>
			>
			<div class="register-error"><?php if(isset($wrong['username'])) echo $wrong['username']; ?></div>
			
			<input type="password" name="password1" id="password1" value="" placeholder="password">
			<input type="password" name="password2" id="password2" value="" placeholder="password again">
			<div class="register-error"><?php if(isset($wrong['password'])) echo $wrong['password']; ?></div>

			<input type="submit" name="register" value="Register">

			<p>I have an account already, and want to <a href="index.php">log in.</a></p>

		</form>
	</div>



</body>
</html>
