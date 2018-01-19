<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; ">

	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link rel="stylesheet" type="text/css" href="style/font-awesome.min.css">

	<script src="javascript/jquery-3.2.1.min.js"></script>

</head>

<body>

<?php 

	session_save_path('/tmp');
	session_start();

	include("system.php");
	include("login.php");

?>


<div class="container">
	<div class="login">

		<div class="login-profile">
			<img id="login-profile-image" src="images/profile.png" onerror="defaultProfile()">
		</div>

		<form id="login" method="POST">
			<input type="textbox" name="username" id="username" class="textbox" placeholder="username" value=<?php if(isset($_POST['username'])) echo '"'.$_POST['username'].'"'?>>
			<div id='animated-item'>
				<input type="password" name="password" id="password" placeholder="password">
			</div>
			<input type="submit" name="login" value="LogIn">

			<p>If you don't have an account, you can <a href="register.php">register here.</a></p>

		</form>
	</div>
	
</div>

<script>

	function defaultProfile() 
	{
		document.getElementById("login-profile-image").src = 'images/profile.png';
	}

	$(document).ready(function() 
	{

		refreshProfile();

		$('#username').on('input', function() { refreshProfile(); });

		function refreshProfile() {

  		var data = $("#login").serialize();

			$.ajax( {
					url: "get-profile.php",
			    type: "POST",
			    data: data,
			    dataType: 'json',
			    success: function(result) {
			    	$('#login-profile-image').load(document.URL +  ' #login-profile-image');

			    	var userid = result['userid'];
			    	$('#login-profile-image').attr('src', 'images/profiles/user_'+userid+'.jpg');
			    	
  				},
  				error: function(error) {
  					$('#login-profile-image').attr('src', 'images/profile.png');
  				}
			});
		}
	});

</script>

</body>
</html>
