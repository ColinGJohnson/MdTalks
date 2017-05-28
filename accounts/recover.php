<?php session_start(); 

	//get the user id (more useful) from the user's email
	function idFromEmail ($email) {
		
		//reading in the user list of the website
		$users = json_decode(file_get_contents("userIndex.json"), true);
		
		//returning the user's id (if they exist)
		if (array_key_exists($email, $users)) {
			return $users[$email];
		} else {
			return null;
		}//else
	
	}//userIdFromEmail
	
	//getting the user's data from their id (if they exist)
	function userFromID ($id) {
		
		$users = json_decode(file_get_contents("userIndex.json"), true);
		
		if (array_search($id, $users) !== false) {
			return json_decode(file_get_contents("users/user_".$id.".json"), true);
		} else {
			return null;
		}//else
	
	}//userFromID
// Sends a verification email using the 'phpmailer' class
	function email($email) {
	
		$resetCode = uniqid();
		
		$randomResetCodes = json_decode(file_get_contents("resetPending.json"), true);
		
		$randomResetCodes[$email] = $resetCode;
		
		file_put_contents("resetPending.json", json_encode($randomResetCodes));
	
		$user = userFromId(idFromEmail($email));
		require("mail_lib/class.phpmailer.php");
		require("mail_lib/class.smtp.php");

		$mail = new PHPMailer(); // create a new object
		$mail->IsSMTP(); // enable SMTP
		$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth = true; // authentication enabled
		$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 465; // or 587
		$mail->IsHTML(true);
		$mail->Username = "mountdougtalks@gmail.com";
		$mail->Password = "mdtalks123";
		$mail->SetFrom("mountdougtalks@gmail.com", "Mount Doug");
		$mail->Subject = "Password Reset";
		$mail->Body = "Hello ".$user["firstname"]." ".$user["lastname"].",<br /><br />Please go to this link to verify your account!"
		."<br /><br />http://142.31.53.22/~mdtalks/accounts/reset.php?id=".$user["id"]."&random=".$resetCode;
		$mail->AddAddress($email);

		// display error if the email failed to send, else forward to the home page
		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			header("Location:../index.php");
		}
		
	}
if(!empty($_POST)){
	email($_POST['email']);
	header("Location: ../");
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDTalks Recover</title>
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
				<form method='post' action = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
					<span> Type your email, we'll send you an email to your to enter a new password</span><br />
					<input type='email' name='email' />
					<button type='submit' name='submit' value='Submit'>Submit</button>
				</form>
			</div>			
			<?php include('../footer.inc'); ?>
		</div>
	</body>
</html>