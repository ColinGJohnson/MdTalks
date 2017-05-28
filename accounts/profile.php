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
                        // forward to login page if not logged in
                        if (!array_key_exists("user", $_SESSION)) {
                                header('Location: http://142.31.53.22/~mdtalks/accounts/login.php');
                        }
            
                        // display group logo image, use imageMissing.jpg if no logo has been uploaded
                        if(file_exists("profilePics/profilePic_".$_SESSION['user']['id'].".jpg")){
                                $iconUrl  = "profilePics/profilePic_".$_SESSION['user']['id'].".jpg";
                        } else { 
                                $iconUrl  = "../resources/imageMissing.png";
                        }
						
						function checkIfPrivate($privacy) {
							if ($privacy == $_SESSION['user']['privacy']) {
								echo 'checked';
							}
						}
                    ?>
            
            
                    
                        <div id='accountInfo'> 
							
							<!-- Profile Picture -->
                            <div id='profilePic' style='background-image: url("<?php echo $iconUrl ?>");'></div>
                            <button id="uploadprofileimage" onclick='uploadProfileImage()'>Upload New Profile Picture</button>
							<?php 
							
							if (strlen($_SESSION["user"]["firstname"]." ".$_SESSION["user"]["lastname"]) > 15) {
								echo "<div id='profilename'>".$_SESSION['user']['firstname']."<br />".$_SESSION['user']['lastname']."</div>";
							} else {
								echo "<div id='profilename'>".$_SESSION['user']['firstname']." ".$_SESSION['user']['lastname']."</div>";
							}
							
							?>
			
                            <div id="otherinfo">
                                Email: <?php echo $_SESSION["user"]["email"]; ?>
                                <br />
                                <?php 
                                    $passwordhide = "";
            
                                    for($i = 0; $i < strlen($_SESSION["user"]["password"]); $i++) {
                                                    $passwordhide .= "*";
                                    }
                                ?>
            
								<span id="password">Password: <?php echo $passwordhide; ?></span>
								<button id="showpassword" onclick="showPassword()">Show</button>
                            </div>
                        </div>
                        <div id='preferences'>
							<span id='preferencetitle'>Preferences</span>
							
							<br /><br />Profile Visibility:<br />
							<input type="radio" name="visibility" onclick="setPrivacy(0)" value="public" <?php checkIfPrivate(0); ?>>Public  Your email will be displayed if you are public<br />
							<input type="radio" name="visibility" onclick="setPrivacy(1)" value="private" <?php checkIfPrivate(1); ?>>Private<br /><br />
								
                            <form id="creategroup" action="../groups/groupfunctions.php" method="post">
                                Create Group<br />
                                <input name="groupname" placeholder="Group Name" />
                                <input class='noshow' name='type' value='creategroup' />
                                <input class="noshow" name="creator" value="<?php echo $_SESSION['user']['id'] ?>" />
                                <input id="shorten" type="submit" value="Create Group" />
                            </form><br />
							
                            Join Group<br /><br />
                            <button id="joingroup" onclick="joinGroup()">Join a Group!</button>
							<form class="noshow" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
                                <input type="file" name="fileToUpload" id="fileToUpload">
                            </form>
                        </div>
            </div>
			<?php include('../footer.inc');?>
        </div>
    </body>
</html>
