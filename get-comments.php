<?php

	include("connect.php");
	
	$sql = "SELECT * FROM comments";

	$result = mysqli_query($connection, $sql);

	$i = 0;

	while($row = mysqli_fetch_array($result)) 
	{             
    $userid = mysqli_real_escape_string($connection, $row['userid']);
    $sql_user = "SELECT fullname FROM users WHERE id = '$userid'";
    $result_user = mysqli_query($connection, $sql_user);
    $row_user = mysqli_fetch_assoc($result_user);
		mysqli_free_result($result_user);

    $date = strtotime($row['date']);

    $comment[$i]['id'] = $row['id'];
    $comment[$i]['userid'] = $row['userid'];
    $comment[$i]['fullname'] = $row_user['fullname'];
    $comment[$i]['date'] = formatDateTime($date);
    $comment[$i]['text'] = $row['text'];

    $commentid = mysqli_real_escape_string($connection, $row['id']);
    $sql_likes = "SELECT commentid 
				    			  FROM likes 
				    			  WHERE commentid = $commentid";
		$result_likes = mysqli_query($connection, $sql_likes);
    $comment[$i]['likes'] = mysqli_num_rows($result_likes);
    mysqli_free_result($result_likes);

    $userid = mysqli_real_escape_string($connection, $_SESSION['userid']);
    $commentid = mysqli_real_escape_string($connection, $row['id']);
    $sql_youlike = "SELECT commentid, userid 
				    			  FROM likes 
				    			  WHERE commentid = $commentid
				    			  AND userid = $userid";

    $result_youlike = mysqli_query($connection, $sql_youlike);
    $comment[$i]['youliked'] = (mysqli_num_rows($result_youlike)>0);
    mysqli_free_result($result_youlike);

    $i++;
	}

	mysqli_free_result($result);

	$comment_count = $i;

	getLoggedIn();
?> 