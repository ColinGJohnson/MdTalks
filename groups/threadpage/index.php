<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDtalks</title>
        <link rel="stylesheet" href="../../style.css">
        <link rel="stylesheet" href="../../headerStyle.css">
        <link rel="stylesheet" href="style.css">
		<link rel="shortcut icon" type="image/ico" href="favicon.ico" />
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="script.js"></script>
    </head>
	<body>
		<?php
		
		include('../groupfunctions.php');
		$groupid = $_GET["groupid"];
		$threadid = $_GET["threadid"];	
		$data = json_decode(file_get_contents("../grouplist/".$groupid."/threads/".$threadid."/pageInfo.json"), true);
		
					
		?>
		<div id="outer">
			<div id="inner">
				<?php include('../../header.inc');  
				function getUserFromID ($id) {
		
					$users = json_decode(file_get_contents("../../accounts/userIndex.json"), true);
					
					if (array_search($id, $users) !== false) {
						return json_decode(file_get_contents("../../accounts/users/user_".$id.".json"), true);
					} else {
						return null;
					}
				}?>	
				
				<div id="firstpost">
					<?php echo "<span id='threadtitle'>".$data["subject"]."</span><br /><br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$data["desc"]."<span class='firstusername'>".$data["user"]."</span>"?>
				</div>
				
				<div id="postscontain">
				<?php
					$data = json_decode(file_get_contents("../grouplist/".$groupid."/threads/".$threadid."/posts.json"), true);
					
					foreach ($data as $post) {
						$user = getUserFromId($post['id']);
						$imagePath = '../../accounts/profilePics/profilePic_'.$post["id"].'.jpg';
						
						if (file_exists($imagePath) == false) {
							$imagePath = "../../resources/imageMissing.png";
						}
						
						echo "<div class='post'><div class='posttext'>".$post["text"]."</div><br /><a class='username' href='../../accounts/publicProfile.php?targetId=".$post['id']."'><span class='nametext'>".$user['firstname']." ".$user['lastname']."</span><img class='thumbnail' src='".$imagePath."' /></a></div>";
					}
				
				?>
				</div>
				
				<?php if($_SESSION['user'] != null){
					echo "<br />";
					include('form.inc');
				} else {
					echo "<span class='error'> You may not post without logging in. <br /> <a class='error' href='http://142.31.53.22/~mdtalks/accounts/login.php'> Click here to log in.</a></span>";
				}
				
				 include('../../footer.inc');?>	
			</div>
		</div>
	</body>
</html>