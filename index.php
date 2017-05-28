<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDtalks</title>
        <link rel="stylesheet" property='stylesheet' href="style.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
    </head>
	<body>
		<div id="outer">
		<?php include('header.inc');?>	
			<div id="innercentered">
				<div class="extraInfo">
					<br><br><h1> Welcome to Mount Doug Talks!</h1>
					</div>
				
				<?php 
					include('groups/groupfunctions.php');

					// if a user is logged in, display all groups they have access to, else instruct them to log in
					if (session_status() == PHP_SESSION_ACTIVE && array_key_exists("user", $_SESSION) && $_SESSION["user"] != null){
						
						echo '<div id="yourgroups">
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Your Groups
						<div id="groupborder">';
								
						$groups = json_decode(file_get_contents("accounts/users/user_".$_SESSION["user"]["id"].".json"), true)["groups"];
						$_SESSION["user"]["groups"] = $groups;
						
						if (sizeof($groups) > 0) {				
							foreach($groups as $group) {				
								$groupName = nameFromId($group);
								$groupInfo = json_decode(file_get_contents("groups/grouplist/".$group."/groupinfo.json"), true);								
								
								$adminIds = $groupInfo['admins'];	
								$adminNames = array();
								foreach($adminIds as $id){
									$idInfo = json_decode(file_get_contents("accounts/users/user_".$id.".json"), true);		
									if (array_key_exists('firstname',$idInfo) && array_key_exists('firstname',$idInfo)){	
										$name = $idInfo['firstname'].' '.$idInfo['lastname'];
										array_push($adminNames, $name);
									}
								}							
								$adminNamesList = implode(', ', $adminNames);
								
								if(array_key_exists("dateCreated",$groupInfo)){
									$date = $groupInfo['dateCreated'];								
								} else {
									$date = 'Unknown';
								}
								
								// display group logo image, use imageMissing.jpg if no logo has been uploaded
								if(file_exists("groups/grouplist/".$group."/logo.jpg")){
									$iconUrl  = "groups/grouplist/".$group."/logo.jpg";
								} else { 
									$iconUrl  = "resources/imageMissing.png";
								}			
								
								echo("	
									<a href='groups/grouppage/?groupid=".$group."'>								
										<div class='mainTopic'>
											<div class='mainTopicInner'>	
												<div class='topicIcon' style='background-image: url(".$iconUrl.");background-size: cover; background-repeat: no-repeat;background-position: 50% 50%;'></div>
												<span class='topicHeading'>".$groupName."</span>
												<p class='topicInfo'>Date Created: ".$date."
												&nbsp&nbsp&nbsp&nbsp Admins: ".$adminNamesList." </p>
											</div>
										</div>
									</a>
								");
							}
						} else {
							echo "<div class='mainTopic'>
											<div class='mainTopicInner'>	
												You aren't in any groups! This is a problem. Fix it <a id='heretojoingroups' href='http://142.31.53.22/~mdtalks/groups/'>here!</a>
											</div>
										</div>";
						}
						echo '
				</div>
				</div>';
					}
				?>
				<div class="push"></div>
			</div>
			<?php include('footer.inc');?>
		</div>
	</body>
</html>