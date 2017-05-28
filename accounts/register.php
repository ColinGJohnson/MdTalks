<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>MDTalks Register</title>
        <link rel="stylesheet" href="../style.css">
                <link rel="stylesheet" href="support/style.css">
                <link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
                <link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
    </head>
        <body>
                <div id="outer">

                        <!-- include the header bar -->
                        <?php include('../header.inc'); ?>
                                <?php 

                                        // define variables to store inputted data and errors
                                        $firstErr = "";
                                        $lastErr = "";
                                        $passErr = "";
                                        $emailErr = "";
                                        $termsErr = "";
                                        $firstName = '';
                                        $lastName = '';
                                        $password = '';
                                        $passwordConfirm = '';
                                        $email = '';
                                        $id = '';

                                        if (!empty($_POST)) {

                                                // read and store data in $_POST array
                                                $firstName = test_input($_POST['firstname']);
                                                $lastName = test_input($_POST['lastname']);
                                                $password = test_input($_POST['password']);
                                                $passwordConfirm = test_input($_POST['passwordConfirm']);
                                                $email = test_input($_POST['email']);
                                                $id = test_input($_POST['id']);
                                                $okay = true;

                                                // read in the user index to check for used emails
                                                $alluserdata = json_decode(file_get_contents("userIndex.json", true));

                                                // disallow already used email addresses
                                                if (array_key_exists($_POST["email"], $alluserdata)) {
                                                        $emailErr = "That email is already used!";
                                                        $okay = false;
                                                }

                                                // require all fields to be completed
                                                if (empty($firstName)) {
                                                        $firstErr = "Please enter a first name.";
                                                        $okay = false;
                                                }
                                                if (empty($lastName)) {
                                                        $lastErr = "Please enter a last name.";
                                                        $okay = false;
                                                }
                                                if (empty($password)) {
                                                        $passErr = "Please enter a password.";
                                                        $okay = false;
                                               
                                                                                                // entered passwords must match
                                                } else if ($password != $passwordConfirm) {
                                                        $passErr = "Passwords do not match.";
                                                        $okay = false;
                                                
                                                // check for illegal characters in the entered password
                                                } else if (ctype_alnum($password) !== true) {
                                                        $passErr = "Password must only have letters and numbers.";
                                                        $okay = false;
                                                }
                                                if (isset($_POST['terms']) === false) {
                                                        $termsErr = "Please accept the Mount Doug Talks Terms of Use";
                                                        $okay = false;
                                                }

                                                

                                                // ensure that the entered email is properly formatted
                                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                                        $emailErr = "That email is invalid!"; 
                                                        $okay = false;
                                                }

                                                // if no errors are found in the form, proceed with registration
                                                if ($okay) {

                                                        // add the new user to the user index
                                                        $shutup = str_replace("}", "", file_get_contents('userIndex.json'));
                                                        if (strlen($shutup) > 1) {
                                                                $shutup .=      ",\"".$email."\":\"".$id."\"}";
                                                        } else {
                                                                $shutup .=      "\"".$email."\":\"".$id."\"}";
                                                        }
                                                        file_put_contents('userIndex.json', $shutup);
                                                        $file = fopen('users/user_'.$id.'.json', 'w');
                                                        chmod('users/user_'.$id.'.json', 0777);

                                                        // create and fill array of user data to encode
                                                        $writeData = array();
                                                        $writeData['firstname'] = $firstName;
                                                        $writeData['lastname'] = $lastName;
                                                        $writeData['password'] = $password;
                                                        $writeData['email'] = $email;
                                                        $writeData['id'] = $id;
                                                        $writeData['verified'] = 'false'; 
                                                        $writeData['permissions'] = 'std'; 
                                                        $writeData['date'] = date("l jS \of F Y h:i:s A");
                                                        $writeData['groups'] = [];
                                                        $writeData['privacy'] = 1;

                                                        // is the user's account or active (-1), suspended (>1 ms) or banned (-2); 
                                                        $writeData['accountStatus'] = '-1';

                                                        // last time the user logged
                                                        $writeData['lastLogin'] = '';

                                                        // The user's Friends (if implemented)
                                                        $writeData['friends'] = [];

                                                        // number of rule violations by the user
                                                        $writeData['numViolations'] = 0;

                                                        // confirmed password only needed during registration
                                                        unset($writeData['passwordConfirm']);

                                                        // encode to json and write the new user's data in the 'users' file
                                                        fwrite($file, json_encode($writeData));
                                                        fclose($file);

                                                        // add the new user to the default 'Mount Douglas Secondary' group
                                                        addMember('569e9d51c585f', $id);

                                                        // send a verification email to the new user's email
                                                        email();
                                                }
                                        }

                                        // removes leading and lagging spaces as well as illegal character's from user input
                                        function test_input($data) {
                                          $data = trim($data);
                                          $data = stripslashes($data);
                                          $data = htmlspecialchars($data);
                                          return $data;
                                        }
                                ?>

                                <!-- Register Form-->
                                <div class='formBox'>
                                        <h3> Register </h3>
                                        <form id="registerform" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">

                                                Name:<br>
                                                <input name="firstname" placeholder="First name" value="<?php echo $firstName;?>"/><br>
                                                <input name="lastname" placeholder="Last name" value="<?php echo $lastName;?>"/><br>
                                                <span style='color:red;'><?php echo $lastErr; ?></span>
                                                <span style='color:red;'><?php echo $firstErr; ?></span>
                                                <br>

                                                Email:<br>
                                                <input name="email" placeholder="Email Address" value="<?php echo $email;?>" /><br>
                                                <span style='color:red;'><?php echo $emailErr; ?></span>
                                                <br>

                                                Password:<br>
                                                <input type="password" name="password" placeholder="Password"/ value="<?php echo $password;?>"><br>
                                                <input type="password" name="passwordConfirm" placeholder="Confirm Password"/ value="<?php echo $passwordConfirm;?>"><br>
                                                <span style='color:red;'><?php echo $passErr; ?></span>
                                                <br>

                                                <input type="checkbox" name="terms" value='decline'> 
                                                I accept the Mount Douglas Talks Terms of Use<br>
                                                <span style='color:red;'><?php echo $termsErr; ?></span>
                                                <br><br>

                                                <input style="display:none" value="<?php echo uniqid(); ?>" name="id" class="hidden"/>
                                                <input type="submit" value="Sign Up"/>
                                                <br>
                                        </form>
                                </div>

                        <!-- include the footer -->
                        <?php include('../footer.inc');?>
                </div>
    </body>
</html>

<?php
        // adds a member to a group by id
        function addMember($groupid, $member) {

                $groupdata = json_decode(file_get_contents("../groups/grouplist/".$groupid."/groupinfo.json"), true);

                        $groupdata["members"][sizeof($groupdata["members"])] = $member;
                        joinGroup($member, $groupid);

                file_put_contents("../groups/grouplist/".$groupid."/groupinfo.json", json_encode($groupdata));
        }

        // adds a group to a user's personal data file by id
        function joinGroup ($user, $groupid) {

                        // read user's data file
                        $userdata = json_decode(file_get_contents("users/user_".$user.".json"), true);

                        // add new group id to the user's group array
                        array_push($userdata["groups"], $groupid);

                        // rewrite the modified json file
                        file_put_contents("users/user_".$user.".json", json_encode($userdata));
        }

        // Sends a verification email using the 'phpmailer' class
        function email() {
                require("mail_lib/class.phpmailer.php");
                require("mail_lib/class.smtp.php");

                $mail = new PHPMailer(); // create a new object
                $mail->IsSMTP(); // enable SMTP
                $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
                $mail->SMTPAuth = true; // authentication enabled
                $mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
                $mail->Host = "smtp.gmail.com";
                $mail->Port = 465; // or 587
                $mail->IsHTML(true);
                $mail->Username = "mountdougtalks@gmail.com";
                $mail->Password = "mdtalks123";
                $mail->SetFrom("mountdougtalks@gmail.com", "Mount Doug");
                $mail->Subject = "Account Verification";
                $mail->Body = "Hello ".$_POST["firstname"]." ".$_POST["lastname"].",<br /><br />Please go to this link to verify your account!<br /><br />http://142.31.53.22/~mdtalks/accounts/verify.php?id=".$_POST["id"];
                $mail->AddAddress($_POST["email"]);

                // display error if the email failed to send, else forward to the home page
                if(!$mail->Send()) {
                        echo "Mailer Error: " . $mail->ErrorInfo;
                } else {
                        header("Location:../index.php");
                }
        }
?>
