<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDtalks</title>
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="style.css">
		<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
	
	<body onload="init()">
		<div id="outer">
			<div id="inner">
				<?php include('../header.inc'); ?>	
				
				<div id="jointitle">Join a group!</div>
				
				<div id="groupcontain">
				
				<?php
					$groups = glob("grouplist/*");
					
					include("groupfunctions.php");
					
					$count = 0;
					
					foreach ($groups as $group) {
						
						if (is_dir($group) && in_array(str_replace("grouplist/", "", $group), $_SESSION["user"]["groups"]) === false) {
							
							$groupinfo = json_decode(file_get_contents($group."/groupinfo.json"), true);
							
							$imagepath = $group."/logo.jpg";
							
							if (!file_exists($imagepath)) {
								$imagepath = "../resources/imageMissing.png";
							}
							
							$toEcho = "<div class='grouptojoin' onclick='addGroup(\"".str_replace("grouplist/", "", $group)."\", event)'>"
							."<div class='groupimage' style='background-image:url(\"".$imagepath."\")'></div>"
							."<span class='groupname'>".nameFromId(str_replace("grouplist/", "", $group))."</span>"
							."<div class='groupdesc'>".$groupinfo["groupdesc"]."</div>";
							
							
							echo "</div>";
							
							if ($groupinfo['privacy'] == 'public'){
								$toEcho .= "<button class='previewgroup' onclick='previewGroup(\"".str_replace("grouplist/", "", $group)."\", event)'>Preview Group</button>";
							}
							
							echo $toEcho;
							$count = $count + 1;
						}
					
					}	
					if ($count === 0) {
						echo "<div class='grouptojoin'>There aren't any more groups for you to join!</div>";
					}
						
				?>
				
				<span id="tooltip" class="hidden"></span>
				</div>
				
					
			</div>
			<?php include('../footer.inc');?>
		</div>
	</body>
	
</html>