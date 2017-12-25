<?php 

	if((isset($_POST['username'])) and (!empty($_POST['username']))) 
	{
	    include('connect.php');

	    $sql = "SELECT id, username FROM users";

	    $result = mysqli_query($connection, $sql);

	    $i = 0;

	    $userid = 0;

	    while($row = mysqli_fetch_array($result)) 
	    {
	        if($_POST['username'] === $row['username']) 
	        {
	            $userid = $row['id'];
	            $advert = array('userid' => $userid);
	            echo json_encode($advert);
	        }

	        $i++;
	    }

	    mysqli_free_result($result);
	}
	
?>

