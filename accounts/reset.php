<?php
	$codes = json_decode(file_get_contents('resetPending.json'), true);
	if(!empty($_POST)){
		$user = userFromId($_GET['id']);
		if($codes[$user['email']] == $_GET['random']){
			$user['password'] = $_POST['pass'];
			file_put_contents("users/user_".$_GET['id'].".json", json_encode($user));
			unset($codes[$user['email']]);
			file_put_contents("resetPending.json", json_encode($codes));
		}//if
		header("Location: login.php");
	}//if
	
	//get the user id (more useful) from the user's email
	function userIdFromEmail ($email) {
		
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

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDTalks Reset</title>
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
			<div id="inner">
			
				<form method='post' action = "<?php echo $_SERVER['PHP_SELF']."?id=".$_GET['id']; ?>">
					<span> Type your new Password</span><br />
					<input type='password' name='pass' />
					<button type='submit' name='submit' >Change Password</button/>
				</form>
			</div>	
			</div>
			<?php include('../footer.inc'); ?>
		</div>
	</body>
</html>