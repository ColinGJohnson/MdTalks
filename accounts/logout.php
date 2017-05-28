<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDtalks</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="support/style.css">
		<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
	<body>
		<div id="outer">
		
			<!-- header bar -->
			<?php 
			
			// clear the sesson variable
			if (array_key_exists("user", $_SESSION)) {
				session_destroy();
			}
			
			include('../header.inc'); ?>
			
			<div id="inner">
			
				<!-- logout notification -->
				<br /><span id='logouttext'>You have been logged out. Click <a style='color:black;' id='logbackin' href='login.php'>here</a> to log back in.</span><br /><br />
			</div>
			
			<!-- footer bar -->
			<?php include('../footer.inc');?>	
		</div>
	</body>
</html>