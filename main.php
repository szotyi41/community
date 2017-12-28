<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="Content-Security-Policy" content="default-src * 'unsafe-inline' 'unsafe-eval' data: blob:; ">

	<link rel="stylesheet" type="text/css" href="style/style.css">
	<link rel="stylesheet" type="text/css" href="style/font-awesome.css">

	<script src="javascript/jquery-3.2.1.min.js"></script>

	<script type="text/javascript">



	$(document).ready(function() 
	{
			$(document).on('click', '.comment-like', function() {

					var id = $(this).attr('id');
					var like = $(this).attr('value');

			    $.ajax( {
			    	url:'like.php',
			    	type: 'get',
			    	data: 'id='+id+'&like='+like,
			    	success:function(result) {
			      	$('.comments').load(document.URL +  ' .comments');
			    	}
			    });
			});

			$(document).on('click', '.comment-remove', function() {

					var id = $(this).attr('id');

			    $.ajax( {
			    	url:'remove.php',
			    	type: 'get',
			    	data: 'id='+id,
			    	success:function(result) {
			      	$('.comments').load(document.URL +  ' .comments');
			    	}
			    });
			});

		  $('#post-button').click(function() 
		  {

		  		var data = $("#post-form").serializeArray();

					$.ajax( {
							url: 'submit.php',
					    type: 'POST',
					    data: data,
					    dataType: 'json',
					    success: function() {
					    	$('.comments').load(document.URL +  ' .comments');
					    },
	    				error: function(error) {
	    					$('.comments').load(document.URL +  ' .comments');
	    				}
					});
		  });

	});
	</script> 

</head>

<body>

<?php

	session_save_path('/tmp');
	session_start();

	include('system.php');
	include('get-comments.php');
?>

<div class="container">

	<div class="comments">

		<div class="comment-number">
			<?php 
				if($comment_count > 1) {
					echo $comment_count." Comments";
				} else {
					echo $comment_count." Comment";
				}
			?>
		</div>
		
		<?php 
			for ($i = 0; $i < $comment_count; $i++) { 
			$id = $comment[$i]['id'];
		?>

			<div class="comment">
				<div class="comment-profile">
					<img onError="this.src='images/profile.png'" src=<?php echo "'images/profiles/user_".$comment[$i]['userid'].".jpg'"; ?>>
				</div>

				<div class="comment-content">

					<?php 
						if(($_SESSION['admin'] == true) or ($_SESSION['userid'] == $comment[$i]['userid'])) 
						{ 
							echo "<div class='comment-remove' id='$id'>Remove</div>";
						} 
					?>

					<div class="comment-name"><?php if(!empty($comment[$i]['fullname'])) echo $comment[$i]['fullname']; else echo 'Removed User'; ?></div>
					<div class="comment-date"><?php echo $comment[$i]['date']; ?></div>
					<div class="comment-text"><?php echo $comment[$i]['text']; ?></div>
					

						<?php
							if($comment[$i]['youliked']) {
								echo "
									<div class='comment-like' id='$id' value='1'>
										<i class='fa fa-heart fa-1x' aria-hidden='true'></i>".$comment[$i]['likes'].
								 "</div>";
							} else {
							echo "
									<div class='comment-like' id='$id' value='0'>
										<i class='fa fa-heart-o fa-1x' aria-hidden='true'></i>".$comment[$i]['likes'].
								 "</div>";
							}
						?>

				</div>

			</div>

		<?php 
		} 
		?>

		</div>		

		<div class="post">
			<h3>Write a comment</h3>
			<form id="post-form" class="post-form" method="POST">
					<img onerror="defaultProfile()" src=<?php echo '"images/profiles/user_'.$_SESSION['userid'].'.jpg"'; ?>>
					<textarea name="post-text" id="post-text" alt="Comment"></textarea>
			</form>
			<input type="button" name="post-button" id="post-button" value="Post">
		</div>
	
</div>

</body>
</html>
