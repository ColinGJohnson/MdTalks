<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Home Page Layout Test</title>
        <link rel="stylesheet" href="style.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
    </head>
	<body>
		<div id="outer">
			<div id="inner">
				<div id="pageBanner">
					<div id="menuBar">
						<a href=""> Home </a> &nbsp&nbsp/&nbsp&nbsp <a href=""> Topics </a> &nbsp&nbsp/&nbsp&nbsp 
						<a href="register.php"> Register </a> &nbsp&nbsp/&nbsp&nbsp <a href=""> About </a>
					</div>
				</div>
			
				<?php 
				
						$firstErr = "";
						$lastErr = "";
						$passErr = "";
						
					if (!empty($_POST)) {
						$firstName = $_POST['firstname'];
						$lastName = $_POST['lastname'];
						$password = $_POST['password'];
						$okay = true;
						
						if (empty($firstName)) {
							$firstErr = "Please enter a first name";
							$okay = false;
						}
						
						if (empty($lastName)) {
							$lastErr = "Please enter a last name";
							$okay = false;
						}
						
						if (empty($password)) {
							$passErr = "Please enter a password";
							$okay = false;
						}
						
						if (ctype_alnum($password) !== true) {
							$passErr = "Password must only have letters and numbers.";
							$okay = false;
						}
						
						$string = file_get_contents("users.json");
						$string .= "\n".json_encode($_POST);
						file_put_contents("users.json", $string);
						
						if ($okay !== false) {
							header("Location: http://142.31.53.22/~mdtalks/");
						}
						
					}
					
				?>
				
				<form id="registerform" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
					<input name="firstname" />
					<?php echo $firstErr; ?>
					<input name="lastname" />
					<?php echo $lastErr; ?>
					<input name="password" />
					<?php echo $passErr; ?>
					<input type="submit" />
				</form>
			</div>
		</div>
    </body>
</html>