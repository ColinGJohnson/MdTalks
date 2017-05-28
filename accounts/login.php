<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDTalks Login</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="support/style.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
    </head>
	<body>
		<div id="outer">
		
			<!-- page header -->
			<?php include('../header.inc'); ?>
			<div id="inner">
				<?php

					// define variables to store the user's id and returned errors
					$loginErr = '';
					$id = "";
					
					// if the login form has been submitted, attempt to validate
					if ($_SERVER["REQUEST_METHOD"] == "POST") {	

						// store entered password
						$passwordAttempt = $_POST['password'];	

						// read in and decode the user index
						$userIndex = json_decode(file_get_contents("userIndex.json"), true);
						
						// if an email has been entered that exists in the user index, get the id matching that email
						if (array_key_exists($_POST["loginEmail"], $userIndex)) {
							$id = $userIndex[$_POST["loginEmail"]];
						}
						
						// if an id can be found for the entered email, check password and account status
						if ($id != null) {
							
							// read in and decode the user's personal data file
							$userInfo = json_decode(file_get_contents('users/user_'.$id.'.json'), true);
						
							// the user must enter a matching password
							if($passwordAttempt != $userInfo['password']){
								$loginErr = 'Incorrect email or password';	
							
							// the user cannot log in using an unverified account
							} else if ($userInfo['verified'] == "false") {
								$loginErr = "You must verify your account first";
							
							// the user may not log in using a banned account
							} else if ($userInfo['accountStatus'] == -2) {
								$loginErr = "This account has been permanently banned.";
								
							// the user may not log in with a suspended account
							} else if ($userInfo['accountStatus'] > 0) {
								
								// check if the user is still suspended
								if($userInfo['accountStatus'] > time()){
									$bannedUntil = date("l\, F jS\, Y \@ g:i a", $userInfo['accountStatus']);
									$loginErr = "This account has been suspended until ".$bannedUntil.".";
								
								// user's suspension has ended, change account status to active (-1), record log in time, and log them in
								} else {
									
									$userInfo['accountStatus'] = -1;
									$userInfo['lastLogin'] = date("l jS \of F Y h:i:s A");
									file_put_contents('users/user_'.$id.'.json', json_encode($userInfo));
									$_SESSION["user"] = $userInfo;
									header("Location: ../index.php");
								}
								
							// no errors are found, store user data in session variable, record log in time, and forward to home page
							} else {
								$userInfo['lastLogin'] = date("l jS \of F Y h:i:s A");
								file_put_contents('users/user_'.$id.'.json', json_encode($userInfo));
								$_SESSION["user"] = $userInfo;
								header("Location: ../index.php");
							}
						} else {
							$loginErr = 'No user found with '.$_POST['loginEmail'].' as an email.';
						}
					}
				?>
				
				<!-- Login Form -->
				<div class='formBox'>
					<h3> Login </h3><br />
					<form id="loginform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
						<input name="loginEmail" placeholder="Email" /><br>		
						<input type="password" name="password" placeholder="Password"/><br>
						<span id="loginerr"><?php echo $loginErr; ?></span>
						<br /><br />
						<input type="submit" value="Login" />
					</form>
					<br />
					<a class="accountmanagelink message" href='register.php'>Don't have an account?</a>
					<br />
					<a class="accountmanagelink message" href='recover.php'>Forgotten password?</a>
					<br /><br />
				</div>
			</div>
			
			<!-- Footer Bar -->
			<?php include('../footer.inc');?>	
		</div>
	</body>
</html>