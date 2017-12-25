<?php


	if(isset($_SESSION['registered']) and ($_SESSION['registered'])) {
		echo 'Thank you for register '.$_SESSION['registered_name'].'. You can log in now.';
		$_SESSION['registered'] = false;
		$_SESSION['registered_name'] = '';
	}

	function formatDateTime($datetime) {

		$posttime = new DateTime(date("Y-m-d", $datetime));
		$posttime->setTime(date("G", $datetime), date("i", $datetime));

		$today = new DateTime("today");
		$yesterday = new DateTime("yesterday");

		return betweenDateTime($posttime, $yesterday, $today);
	}

	function betweenDateTime($datetime, $datetimemin, $datetimemax)
	{
		$date = 'Once';

		if($datetimemin < $datetime) 
		{
			if($datetimemax > $datetime) 
			{
				$date = 'Yesterday';
			} 
			else 
			{
				$date = 'Today';
			}
		} 
		else 
		{
			$date = strtoupper($datetime->format("Y F d"));
		}

		return $date.' at '.$datetime->format("G:i");
	}

	function getTrue($i) {
		return (isset($_POST[$i]) and ($_POST[$i]));
	}

	function logOff() {
		$_SESSION['loggedin'] = false;
		$_SESSION['userid'] = 0;
		$_SESSION['username'] = '';
		$_SESSION['fullname'] = '';
		$_SESSION['admin'] = false;
	}


	function logIn() {

		include("connect.php");

		logOff();

		$username = mysqli_real_escape_string($connection, $_POST["username"]);
		$password = mysqli_real_escape_string($connection, $_POST["password"]);
		echo '<div class="login-wrong">Wrong username or password</div>';

		$sql = "
			SELECT id, username, password, fullname, admin
			FROM users
			WHERE username = '$username'
		";

		$result = mysqli_query($connection, $sql);

		while($row = mysqli_fetch_array($result)) 
		{       


			if(($row['username'] == $username) and ($row['password'] == sha1($password)))
			{
				
				$_SESSION['loggedin'] = true;
				$_SESSION['userid'] = $row['id'];
				$_SESSION['username'] = $row['username'];
				$_SESSION['fullname'] = $row['fullname'];
				$_SESSION['admin'] = $row['admin'];

				header("Location: main.php");
			}
		}

		mysqli_free_result($result);
	}

	function getLoggedIn() 
	{
		if(isset($_SESSION['loggedin']) and ($_SESSION['loggedin'])) 
		{
			echo '<div class="container"><div class="loggedin">You are logged in as '.$_SESSION['username'];

			if($_SESSION['admin']) echo ' (admin)';

			echo '<p><a href="index.php">Log off</a></p>';
			echo '</div></div>';

		} 
	}

?>