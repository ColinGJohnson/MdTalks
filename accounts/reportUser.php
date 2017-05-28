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
		
			<!-- header bar -->
			<?php include('../header.inc'); ?>
            <div id="inner">
					<?php 
					    
						// if the report form has already been submitted, process the data
						if(array_key_exists('submitReport', $_POST)){
							
							// store post array in accociative array
							$reportInfo = array(
								'reportId' => $_POST['reportId'], 
								'reportedUserId' => $_POST['reportedUserId'], 
								'issuerId' => $_SESSION['user']['id'], 
								'description' => $_POST['complaintDetails'], 
							); 
									
							// encode as json and store array of report info as 'admin/userReports/report_userReport_id'
							file_put_contents('../admin/userReports/report_'.$reportInfo['reportId'].'.json', json_encode($reportInfo));
							
							// notify user that their report has been successfully submitted
							echo('Report submitted.<br><br>');
						
						// if the report form has not already been submitted, display the form
						} else if(array_key_exists('reportedUserId', $_GET)){
							
							// generate and store a unique id for the user report
							$reportId = 'userReport_'.uniqid();
							
							// display the report form
							echo("
								<form action=".htmlspecialchars($_SERVER["PHP_SELF"])." method='post' enctype='multipart/form-data'>
									<h4>Submitting report for ".$_GET['reportedUserId']."</h4>
									In your report please include the following information (if known), be as specific as possible. <br> 
									1. Rule that has been broken <br>
									2. Time and date of violation <br>
									3. Location of violation, if in a post, specify the group and thread title <br>
									4. Other details.<br><br>
									
									<textarea name='complaintDetails' rows='5' cols='40' placeholder='Complaint Details'></textarea><br>
									<textarea class='noshow' name='reportedUserId' rows='1' cols='40'>".$_GET['reportedUserId']."</textarea><br>
									<textarea class='noshow' name='reportId' rows='1' cols='40'>".($reportId)."</textarea><br>
									*Submitting false reports may result in the suspension or permanent ban of your own account.<br>
									<input type='submit' value='Submit' name='submitReport'><br>
								</form>
							");
							
						// if the user should not be on this page, kick them off
						} else {
							header('../admin/404.php');
						}
					?>
            </div>
			
			<!-- footer -->
			<?php include('../footer.inc');?>	
        </div>
    </body>
</html>
