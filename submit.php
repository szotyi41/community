<?php 

	session_save_path('/tmp');	
	session_start();

	if(isset($_POST['post-text']) && (!empty($_POST['post-text']))) 
	{
		include("connect.php");

		$userid = $_SESSION['userid'];
		$text = strip_tags($_POST['post-text']);

		$url = '~https?://\S+\.(?:jpe?g|gif|png)(?:\?\S*)?(?=\s|$|\pP)~i';
		if(preg_match($url, $text)) {
			$text = preg_replace($url, '<a href="$0" target="_blank" title="$0"><img src="$0" title="$0" style="max-height: 500px; float: left; display: inline; right-margin: 20px;"></img></a>', $text);
		}
		else 
		{ 
			$url = '~(?:(https?)://([^\s<]+)|(www\.[^\s<]+?\.[^\s<]+))(?<![\.,:])~i';
			$text = preg_replace($url,'<a href="$0" target="_blank" title="$0">$0</a>', $text);
		}

		
		$text = mysqli_real_escape_string($connection, $text);

		if(!empty($text)) 
		{
			$sql = "INSERT INTO comments (userid, text) VALUES ($userid, '$text');";
			$result = mysqli_query($connection, $sql);
			mysqli_free_result($result);
		}


	}

?>

