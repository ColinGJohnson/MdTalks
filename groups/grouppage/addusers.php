<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDtalks</title>
        <link rel="stylesheet" href="../../style.css">
        <link rel="stylesheet" href="style.css">
		<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
	
	<body >
	<?php
		include('../../header.inc');
		$data = json_decode(file_get_contents("../grouplist/".$_GET['groupid']."/pending.json"), true);
		
		if(sizeof($data) == 0){
			echo "<span id='emptyuserlist'>There are no pending approvals</span>";
		} else {
			for($i = 0; $i < sizeof($data); $i++){
				$user = json_decode(file_get_contents("../../accounts/users/user_".$data[$i].".json"), true);
				$filepath = "../../accounts/profilePics/profilePic_".$user['id'].".jpg";
				
				if (!file_exists($filepath)) {
					$filepath = "../../resources/imageMissing.png";
				}
				
				echo "<div class='user' onClick='approveMember(\"".$user['id']."\",\"".$_GET['groupid']."\",\"".$i."\")'><div class='thumbnail' style='background-image: url(\"".$filepath."\")'></div><span class='pendingusername'>".$user['firstname']." ".$user['lastname']."</span></div>";
			}
		}
		include('../../footer.inc');
	?>
	</body>
</html>