<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDtalks</title>
        <link rel="stylesheet" href="support/style.css">
        <link rel="stylesheet" href="../style.css">
                <link rel="shortcut icon" type="image/ico" href="favicon.ico" />
                <link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
                <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="support/script.js"></script>
                <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    </head>
    <body>
        <div id="outer">
            
                    <?php include('../header.inc'); ?>
            <div id="inner">
                    <?php 
					
						// check for target id in get array
						if(array_key_exists('targetId', $_GET)){
							
							// get user data
							$userInfo = json_decode(file_get_contents("users/user_".$_GET['targetId'].".json"), true);
						} else {
							header('../404.php');
						}
					
					
                        // display group logo image, use imageMissing.jpg if no logo has been uploaded
                        if(file_exists("profilePics/profilePic_".$_GET['targetId'].".jpg")){
                                $iconUrl  = "profilePics/profilePic_".$_GET['targetId'].".jpg";
                        } else { 
                                $iconUrl  = "../resources/imageMissing.png";
                        }
                    ?>
            
                        <div id='accountInfo'>
                            
                            <!-- Profile Picture -->
                            <div id='profilePic' style='background-image: url("<?php echo $iconUrl ?>");background-size: cover; background-repeat: no-repeat;background-position: 50% 50%;'></div>
							
							<?php 
								if($userInfo['privacy'] == '0'){
									echo("
										<span id='profilename'>".$userInfo['firstname']." ".$userInfo["lastname"]."</span>
										<h4 class='profileInfo'> Member since: ".$userInfo['date']     ."</h4>
										<h4 class='profileInfo'> Last Active: ".$userInfo['lastLogin']."</h4>
										<h4 class='profileInfo'> Contact:     ".$userInfo['email']    ."</h4>
									");
								} else {
									echo('<div id="profilename">'.$userInfo['firstname'].' '.$userInfo["lastname"].'</div>
									      <h4 class=\'profileInfo\' style="color:red;"> This user\'s profile is private.</h4>');
								}
							?>
							
                            <form action="reportUser.php" method="get" enctype="multipart/form-data">
								<button class='profileInfo' type="submit" value="<?php echo($userInfo['id'])?>" name="reportedUserId"> Report User </button>
							</form>
                        </div>						
            </div>
                    <?php include('../footer.inc');?>
        </div>
    </body>
</html>
