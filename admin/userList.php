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
	
	<body>
		<div id="outer">
		
			<!-- include header bar -->	
			<?php include('../header.inc'); ?>
			<div id="inner">
				<?php 

					// forward to login page if not logged in
					if (!array_key_exists("user", $_SESSION)) {
						header('Location: http://142.31.53.22/~mdtalks/404.php');
					}
					
					// forward to noPermission.php if the current user has insufficient permissions
					if ($_SESSION['user']['permissions'] == 'sth') {
						header('Location: http://142.31.53.22/~mdtalks/404.php');
					}
					
					// perform delete tasks if requested (currently not included because it's dangerous,
					// we can add it back in if requested)
					if($_SERVER["REQUEST_METHOD"] == "POST"){
						if(array_key_exists('deleteId', $_POST)){
							require('../groups/groupfunctions.php');
							
							// remove user's data file
							unlink('../accounts/users/user_'.$_POST['deleteId'].'.json');
							
							// remove from user index
							$userIndex = json_decode(file_get_contents('../accounts/userIndex.json'), true);				
							if(($key = array_search($_POST['deleteId'], $userIndex)) !== false){
								unset($userIndex[$key]);
							}
							file_put_contents('../accounts/userIndex.json', json_encode($userIndex));
							
							// remove user's group memberships
							$groupIds = glob('../groups/grouplist/*');
							foreach ($groupIds as $currentId) {
								if (is_dir($currentId)) {
									$currentId = basename($currentId);
								}
							}
							
							// delete user's from all of their groups using a method from groupfunctions.php
							var_dump(deleteUserFromGroups($groupIds, $_POST['deleteId'], "../groups"));			
							$_POST['deleteId'] = '';
						} 

						// note a user report
						if (array_key_exists("notedReportId", $_POST)) {
							$notedUserData = json_decode(file_get_contents('../accounts/users/user_'.$_POST['notedUserId'].'.json'), true);
							$notedUserData['numViolations'] += 1;
							file_put_contents('../accounts/users/user_'.$_POST['notedUserId'].'.json', json_encode($notedUserData));
							unlink('userReports/report_'.$_POST['notedReportId'].'.json');
						}
								
						// delete a user report
						if (array_key_exists("deletedReportId", $_POST)) {
							unlink('userReports/report_'.$_POST['deletedReportId'].'.json');
						}						
					}
				?>	
				<br>
				
				<!-- table containing general info for each user -->
				User Info:
				<table>
			
					<!-- User info table headings -->
					<thead>
						<tr>
							<th><b>Edit</b></th>
							<th><b>id</b></th>
							<th><b>Email</b></th>
							<th><b>First Name</b></th>
							<th><b>Last Name</b></th>
							<th><b>Password</b></th>
							<th><b>Date Registered</b></th>
							<th><b>Permission</b></th>
							<th><b>Status</b></th>
						</tr>
					</thead>
					
					<!-- User info table data -->
					<?php 
						// get json file paths for all registered users
						$userDataFiles = glob('../accounts/users/user_*.json');
						
						// echo a table row for each user with basic info
						foreach($userDataFiles as $dataFile){
							$userData = json_decode(file_get_contents($dataFile), true);	
							
							// include a unique style for the word 'admin' in the permissions column
							if($userData['permissions'] != 'admin'){
								$permissionField = '<td>'.$userData['permissions'].'</td>';
							} else {
								$permissionField = '<td style=\'color:red;\'>'.$userData['permissions'].'</td>';
							}
							
							// replace account status number codes with strings
							if($userData['accountStatus'] == -2){
								$userStatus = 'Banned';
							} else if($userData['accountStatus'] > time()){
								$userStatus = 'Suspended';
							} else{
								$userStatus = 'Active';
							}
							
							// highlight the current user's account status
							if(strcmp($_SESSION['user']['id'], $userData['id']) != 0){
								$statusFieldStyle = "";
							} else {
								$statusFieldStyle = "style='color:lightgreen;'";
							} 
							
							// print the complete table row:
							//(Edit button, id, email, first name, last name, password, date registered, permission level, account status)
							echo("
								<tbody>
									<tr>	
										<td onclick='goToUserControl(\"".$userData['id']."\")'>
											<span class='edituser'>Edit</span>
										</td>".
										"<td>".$userData['id']."</td>
										<td>".$userData['email']."</td>
										<td>".$userData['firstname']."</td>
										<td>".$userData['lastname']."</td>
										<td>".$userData['password']."</td>
										<td>".$userData['date']."</td>
										".$permissionField."
										<td><span ".$statusFieldStyle.">".$userStatus."</span></td>
									</tr>
								</tbody>
							");
						}
					?>
				</table>
				<br>
				
				<!-- table containing a list of user submitted reports -->
				User Reports:
				<table>
				
					<!-- User info table headings -->
					<thead>
						<tr>
							<th><b>Edit User</b></th>
							<th><b>Delete Report</b></th>
							<th><b>Acknowledge Report</b></th>
							<th><b>Report Id</b></th>
							<th><b>Subject Id</b></th>
							<th><b># Past Violations</b></th>
							<th><b>Subject Name</b></th>
							<th><b>Issuer Id</b></th>
							<th><b>issuer Name</b></th>
							<th><b>Details</b></th>
						</tr>
					</thead>
					
					<!-- User info table data -->
					<?php 
					
						// get json file paths for all user reports
						$userReportFiles = glob('../admin/userReports/report_userReport_*.json');
						
						// echo a table row for each report
						foreach($userReportFiles as $reportFile){
							
							// load json data files for the report data, the reported user, and the issuer of the report
							$reportData = json_decode(file_get_contents($reportFile), true);	
							$reportedUserData = json_decode(file_get_contents('../accounts/users/user_'.$reportData['reportedUserId'].'.json'), true);
							$issuerData = json_decode(file_get_contents('../accounts/users/user_'.$reportData['issuerId'].'.json'), true);
							
							// print the complete table row:
							//(Options, Report Id, Subject Id, # Past Violations, Subject Name, Issuer Id, issuer Name, Details)
							echo("
								<tbody>
									<tr>	
										<td>
											<span onclick='goToUserControl(\"".$reportData['reportedUserId']."\")' class='edituser'>Edit User</span>
										</td>
										<td>
											<form id='deleteReportForm' method='post' action=".$_SERVER['PHP_SELF'].">
												<textarea style='display:none;' name='deletedReportId' rows='1' cols='40'>".$reportData['reportId']."</textarea>
												<input type='submit' value='Delete'>
											</form>
										</td>
										<td>
											<form id='noteReportForm' method='post' action=".$_SERVER['PHP_SELF'].">
												<textarea style='display:none;' name='notedReportId' rows='1' cols='40'>".$reportData['reportId']."</textarea>
												<textarea style='display:none;' name='notedUserId' rows='1' cols='40'>".$reportData['reportedUserId']."</textarea>
												<input type='submit' value='Acknowledge'>
											</form>
										</td>
										<td>".$reportData['reportId']."</td>
										<td>".$reportData['reportedUserId']."</td>v
										<td>".$reportedUserData['numViolations']."</td>
										<td>".$reportedUserData['firstname'].'&nbsp'.$reportedUserData['lastname']."</td>									
										<td>".$reportData['issuerId']."</td>
										<td>".$issuerData['firstname'].'&nbsp'.$issuerData['lastname']."</td>
										<td>".$reportData['description']."</td>
									</tr>
								</tbody>
							");
						}
					?>
				</table>
				<br>
				
				<!-- tables containing useful var_dump strings including the session data, the user index, and existing group ids -->
				Other info:
				<table>
					<thead>
						<tr>
							<th><b>Session Data</b></th>
						</tr>
					</thead>
					<tbody>
						<tr>	
							<td><?php var_dump($_SESSION); ?></td>
						</tr>
					</tbody>
				</table>
				<br />
				
				<table>
					<thead>
						<tr>
							<th><b>User Index Data</b></th>
						</tr>
					</thead>
					<tbody>
						<tr>	
							<td><?php var_dump(json_decode(file_get_contents('../accounts/userIndex.json'), true)); ?></td>
						</tr>
					</tbody>
				</table>
				<br />
				
				<table>
					<thead>
						<tr>
							<th><b>Groups</b></th>
						</tr>
					</thead>
					<tbody>
						<tr>	
							<td>
								<?php 
									$groupIds = glob('../groups/grouplist/?????????????');
									for ($i = 0; $i < count($groupIds); $i++) {
										$groupIds[$i] = basename($groupIds[$i]);
									}
									var_dump($groupIds);
								?>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<!-- add the page footer -->
			<?php include('../footer.inc');?>
		</div>
	</body>
</html>