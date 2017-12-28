<?php


	if(isset($_SESSION['registered']) and ($_SESSION['registered'])) {
		echo 'Thank you for register '.$_SESSION['registered_name'].'. You can log in now.';
		$_SESSION['registered'] = false;
		$_SESSION['registered_name'] = '';
	}

	function formatDateTime($posttime)
	{
		$datetime = new DateTime(date("Y-m-d", $posttime));
		$datetime->setTime(date("G", $posttime), date("i", $posttime));

		$result = 'Once';
    $now = new DateTime("now");
    $diff = date_diff($now, $datetime);
    
		if($diff->m > 0) {
    	$result = $datetime->format("Y m d").' at '.$datetime->format("G:i");
    }
    else if($diff->d > 0) {
    	if($diff->d == 1) {
    		$result = 'Yesterday at '.$datetime->format("G:i");
    	} else {
    		$result = ($diff->d .' day'. ($diff->d > 1?"s":'')).' ago';
    	}
    }
    else if($diff->h > 0) {
    	$result = $diff->h .' hour'.($diff->h > 1 ? "s":'').' ago';
    }
    else if($diff->i > 0) {
    	$result = $diff->i .' minute'. ($diff->i > 1?"s":'').' ago';
    }
    else if($diff->s > 0) {
    	$result = 'Now';
    }

		return $result;
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