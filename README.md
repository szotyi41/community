# Community
My own community system

How to use the documentation
======================

There is the description of all files, and the important aspects of project.
The description of classes, methods and every important (not temporary) variables (in PHP) and variants (in JS).

	0.1 Parts of documentation 
	------------------------
		1. Interface files: the user can see this results.
		2. Interface near files: this files run in background, register, login or ajax runs what is refresh the part of the site.
		3. System.php
		4. POSTS 
		5. SESSIONS
		6. Database

	0.2 Database
	-----------------------
	Database section is show all commands in mysql

		6.1. Create commands
		6.2. Connections
		6.3. Querys

Install
=====================
	1. Upload files to server
	2. Create a new database
	3. Run INSERT codes to database
	4. Test

Interface files
=====================

	1.1 index.php
	----------	
	session start
	login.php run when click the login button
	get-profile.php run when you add a character in username field
	Show profile picture
	Login form
	Register button

		JS
		-----------
		refreshProfile() 
			call ajax for profile refresh
			get-profile.php run, form details send with JSON
			get-profile.php refresh #login-prfile-image src frissítése: src='images/profiles/user_'+userid+'.jpg';

		PHP
		-----------
		$_POST['username'] = send username
		$_POST['password'] = send password in SHA1 encoding
		$_POST['login'] = login button

	1.2 register.php
	-----------
	session start
	connect.php connect mysql

	when there is a selected picture in .profile-upload input[type='file'], the file will upload to the server, then its load to $_SESSION['file'] therefore dont need to upload again, when you get register error.

	When the user click to register button:
		1. check fullname length > 4 chars
		2. check email vaidation
		3. check username length > 3 chars   
		4. check password1 length > 5
		5. check password1 and password2 identity
	
		PHP
		---------
		testInput()  
			email validation checker

		$wrong[] = array() {username, password, fullname...}
		$error = true: dont run the INSERT at register
		$upload = true: run the profile upload code
		$file = profile storage
		$profiles = path of profiles
		$to = full path of profiles
		$destination = upload profile path (with filetype)

		$_FILES['profile-upload']['tmp_name'] = uploaded profile
		$_POST['fullname'] = fullname send
		$_POST['email'] = email
		$_POST['username'] = username
		$_POST['password1'] = pass1
		$_POST['password2'] = pass2
		$_POST['register'] = register button

	1.3 main.php
	-----------
	session start
	system.php, get-comments.php

	<?php for($i = 0; $i < $comment_count; $i++)?>
		comment user show profile $comemnt[$i]['userid']
		show the user fullname of comment $comemnt[$i]['fullname']
		show comment timestamp $comemnt[$i]['date'] 
		show comment text $comemnt[$i]['text'] 
		show like number of comment $comemnt[$i]['likes'] 
		show like icon $comemnt[$i]['youlike']
		if($comemnt[$i]['youlike'] == '1') <i value='1'> 
		if($comemnt[$i]['youlike'] == '0') <i value='0'>
		The value is important for ajax works
	<?php } ?>  

	Post form

		PHP
		-----------
		$_POST['post-text'] = full post text

		$comment_count
		$comment[$i]['id'] = comment azonosítója
		$comment[$i]['userid'] = comment owner 
		$comment[$i]['fullname'] = comment owner fullname
		$comment[$i]['date'] = comment TIMESTAMP
		$comment[$i]['text'] = comment text
		$comment[$i]['likes'] = count of comment likes
		$comment[$i]['youlike'] = the logged in user like this comment or not

		JS
		------------
		$('.comment-like').on('click')
			var id = comemnt identification
			var like = comment liked by the user
			ajax -> like.php?id=id&like=like
			.comment div refresh

		$('.comment-remove').on('click')
			var id = comment identification
			ajax -> remove.php?id=id
			.comment div refresh

		$('#post-button').on('click')
			var data = (#post-form) -> convert to JSON
			ajax -> post.php
			.comment div refresh

Interface near files
====================

	2.1 connect.php
	---------
		connect to database

		$host 
		$username = sql user
		$password = sql pass
		$database = USE database
		$connected = boolean to indicate valid comment

	2.2 login.php
	---------
		Inline logIn() method, from system.php

	2.3 get-profile.php
	---------
		Query the user profile picture by username field with ajax.

		$.(ajax) GET: {username} RETURN: {userid}

		$sql = "SELECT id, username FROM users";
		<img src="images/profiles/user_<userid>.jpg">

	2.4 get-comments.php
	----------
		Query comments with ajax
		$sql = "SELECT * FROM comments";

		while($row = row values) {

			Query username based on $userid
				$sql_user = "SELECT fullname FROM users WHERE id = '$userid'";
		
			The comment owner properties storage in array()
				id, userid, fullname, date, text

			Query likes count based on $commentid
	    		$sql_likes = "SELECT commentid FROM likes WHERE commentid = $commentid";
			
			When there is a row in likes table, what values
			$row['commentid'] == the comment of identification
			$row['userid'] == the logged in user identification
			then youliked set true
	    		$sql_youlike = "SELECT commentid FROM likes WHERE commentid = $commentid AND userid = $userid";

		}

		close result, set comment_count to $i
		

		$comment_count 
		$comment[$i]['id'] = Comment identification
		$comment[$i]['userid'] = Comment owner identification
		$comment[$i]['fullname'] = Comment owner fullname
		$comment[$i]['date'] = Comment TIMESTAMP
		$comment[$i]['text'] = Comment text
		$comment[$i]['likes'] = Comment likes count
		$comment[$i]['youlike'] = When the logged in user like this comment this value set true

	2.5 submit.php
	----------
		Session start
		Send post text with ajax, POST method (for data safety)
		$sql = "INSERT INTO comments (userid, text) VALUES ($userid, '$text');";


	2.6 like.php 
	----------
		Send comment id and like value with ajax, GET method (for fast data transfer) then run the sql.

		if('.comment-like').value == 0
			$sql = "INSERT INTO likes (commentid, userid) VALUES ($commentid, $userid)";

		if('.comment-like').value == 1
			$sql = "DELETE FROM likes WHERE commentid = $commentid AND userid = $userid";

	2.7 remove.php 
	----------
		Send comment id with ajax, GET method (for fast data transfer) then run the sql.
		$sql = "DELETE FROM comments WHERE commentid = $id";

		Then DELETE all rows what identify the deleted comment, becouse prevent the data redundancy.
		$sql = "DELETE FROM likes WHERE commentid = $id";

system.php - Some scripts
====================

	3.1 system.php
	-----------
		Check, the user status is just registered.

		formatDateTime()
			Convert datetime to yesterday, tomorrow or other date format.
		betweenDateTime()
			Convert formatDateTime() to string for output.
		getTrue()
			Check POST value is TRUE with exists checking.
		logOff()
			Set all logged in SESSIONS to default value.
		logIn()
			Connect to database
			When you stay in login screen,  we get 'Wrong username or password' caption.
			$sql = "
				SELECT id, username, password, fullname, admin
				FROM users
				WHERE username = '$username'
			";

			Load rows by 'username'. You get one row.
			Then get the password SHA1 encoding, then compare with 'password' field.
			Set the user properties to SESSION.
			Redirecting to logged in screen.
			Close result.

			$_SESSION['loggedin'] = storage logged in status
			$_SESSION['userid'] = logged in user identification
			$_SESSION['username'] = logged in user username
			$_SESSION['fullname'] = logged in user fullname
			$_SESSION['admin'] = logged in user is access to administrator events or not.

		getLoggedIn()
			When your status is logged in, this indicate your username, admin access, and show the log off button.


$_POSTS
=============

	4.1 LOGIN
	------
		$_POST['username'] = username
		$_POST['password'] = password in SHA1 encoding
		$_POST['login'] = login button

	4.2 REGISTER
	-------
		$_POST['fullname'] 
		$_POST['email']  
		$_POST['username'] 
		$_POST['password1'] 
		$_POST['password2'] 
		$_POST['register'] = register button

	4.3 MAIN
	-------
		$_POST['post-text'] 


$_SESSIONS
=============

	5.1 LOGIN
	--------
		$_SESSION['loggedin'] = storage logged in status
		$_SESSION['userid'] = logged in user identification
		$_SESSION['username'] = logged in user username
		$_SESSION['fullname'] = logged in user fullname
		$_SESSION['admin'] = logged in user is access to administrator events or not.


	5.2 REGISTER
	---------
		$_SESSION['file'] = storage uploaded profile picture, dont need to upload again and again when you get error in register screen.

		$_SESSION['registered'] = your status is just registered
		$_SESSION['registered_name'] = just registered user fullname

DATABASE
=============
	
	6.1 CREATE TABLES
	-------------
		We need a table for storage comments, and users. Every comment have an owner. 
		The comments need to set TIMESTAMP and the comment text.
		Users need to storage properties and password in SHA1 encoding.
		Likes table is for storage, every user can likes every comments.

		CREATE TABLE comments (
			 id int(11) NOT NULL AUTO_INCREMENT,
			 userid int(11) NOT NULL,
			 date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			 text mediumtext NOT NULL,
			 UNIQUE KEY (userid)
			 PRIMARY KEY (id)
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

		CREATE TABLE likes (
			 userid int(11) NOT NULL,
			 commentid int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8

		REATE TABLE users (
			 id int(11) NOT NULL AUTO_INCREMENT,
			 username varchar(50) NOT NULL,
			 password char(40) NOT NULL,
			 email varchar(50) NOT NULL,
			 fullname varchar(50) NOT NULL,
			 admin tinyint(1) NOT NULL,
			 PRIMARY KEY (id)
		) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8

	6.2 CONNECTIONS
	--------------
		comments.userid = users.id
		comments.id = likes.commentid
		users.id = likes.userid

	6.3 QUERYS
	--------------
		Get all profiles:
			$sql = "SELECT id, username FROM users";	

		Get all comments:
			$sql = "SELECT * FROM comments";

		Count likes in a comment:
			$sql_likes = "SELECT commentid FROM likes WHERE commentid = $commentid";

		Like a comment:
			$sql = "INSERT INTO likes (commentid, userid) VALUES ($commentid, $userid)";

		Dislike a comment:
			$sql = "DELETE FROM likes WHERE commentid = $commentid AND userid = $userid";

		If the logged in user like the comment:
			$sql_youlike = "SELECT commentid FROM likes WHERE commentid = $commentid AND userid = $userid";

		Post a comment:
			$sql = "INSERT INTO comments (userid, text) VALUES ($userid, '$text');";

		Remove a comment:
			$sql = "DELETE FROM comments WHERE commentid = $id";
			$sql = "DELETE FROM likes WHERE commentid = $id";

		Query for login:
			$sql = "
				SELECT id, username, password, fullname, admin
				FROM users
				WHERE username = '$username'
			";

		Remove user:
			$sql = "DELETE FROM users WHERE userid=$userid"
			$sql = "DELETE FROM comments WHERE userid = $userid";
			$sql = "DELETE FROM likes WHERE userid = $userid";
