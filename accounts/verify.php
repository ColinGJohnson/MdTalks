<?php


	session_start();
	$id = $_GET["id"];
	$userdata = json_decode(file_get_contents("users/user_".$_GET["id"].".json"), true);
	
	if ($userdata['verified'] == 'false') {
		$userdata["verified"] = 'true';
		file_put_contents("users/user_".$_GET["id"].".json", json_encode($userdata));
		$_SESSION["user"] = $userdata;
		header("Location: profile.php");
	} else {
		header("Location: ../404.php");
	}

?>