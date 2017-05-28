<?php
session_start();

if ($_POST["type"] == "showpassword") {
	echo $_SESSION["user"]["password"];
} else if ($_POST["type"] == "hidepassword") {
	$passwordhide = "";

	for($i = 0; $i < strlen($_SESSION["user"]["password"]); $i++) {
		$passwordhide .= "*";
	}
	
	echo $passwordhide;
} else if ($_POST["type"] == "uploadImage"){
	$id = $_SESSION["user"]["id"];

		move_uploaded_file($_FILES["file"]["tmp_name"], "../profilePics/profilePic_".$id.".jpg");
		
		echo $id;	
	
} else if ($_POST['type'] == 'setprivacy') {
	$id = $_SESSION['user']['id'];
	
	$userdata = json_decode(file_get_contents("../users/user_".$id.".json"), true);
	
	$userdata['privacy'] = $_POST['privacy'];
	
	if ($id == $_SESSION['user']['id']) {
		$_SESSION['user']['privacy'] = $_POST['privacy'];
	}
	
	file_put_contents("../users/user_".$id.".json", json_encode($userdata));
	
	echo $id;
	
}


?>