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
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="script.js"></script>
    </head>
	<body>
		<?php
		
			// include groupfunctions for extra user and group data handling functions.
			include('../groups/groupfunctions.php');
		
			// if one of the forms has been submitted, update the user's json file and reload it
			if($_SERVER["REQUEST_METHOD"] == "POST") {
					file_put_contents('../accounts/users/user_'.$_POST['id'].'.json', $_POST['editedJson']);
					$jsonToEdit = $_POST["editedJson"];
					
			// load the currently viewed user's json file
			} else {	
				$jsonToEdit = file_get_contents('../accounts/users/user_'.$_GET['id'].'.json');
			} // else 
			
			// decode the displayed user's json file
			$jsonToEditDecoded = json_decode($jsonToEdit, true);
		?>
		
		<div id="outer">
		
			<!-- header bar -->
			<?php include('../header.inc'); ?>
			<div id="inner">

				<!-- Current User Being Edited-->
				<h2> User Info </h2>
				<?php echo("<h4>Currently editing ".$jsonToEditDecoded['email']." (id: ".$jsonToEditDecoded['id'].").</h4>"); ?>
				
				<!-- table of individual user data values -->
				<h2> Individual Values </h2>
				<h4> General </h4>
				<div id="editedInfo"> 
					<table border="1" style="width:100%">
						<tr>
							<td><b>Id</b></td>
							<td><textarea style="width:99%" name="id" rows="1" cols="14"><?php echo($jsonToEditDecoded['id']); ?></textarea><br></td>
						</tr>
						<tr>
							<td><b>Email</b></td>
							<td><textarea style="width:99%" name="email" rows="1" cols="14"><?php echo($jsonToEditDecoded['email']); ?></textarea><br></td>
						</tr>
						<tr>
							<td><b>First Name</b></td>
							<td> <textarea style="width:99%" name="firstname" rows="1" cols="14"><?php echo($jsonToEditDecoded['firstname']); ?></textarea><br> </td>
						</tr>
						<tr>
							<td><b>Last Name</b></td>
							<td> <textarea style="width:99%" name="lastname" rows="1" cols="14"><?php echo($jsonToEditDecoded['lastname']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b>Password</b></td>
							<td> <textarea style="width:99%" name="password" rows="1" cols="14"><?php echo($jsonToEditDecoded['password']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b>Date Registered</b></td>
							<td> <textarea style="width:99%" name="date" rows="1" cols="14"><?php echo($jsonToEditDecoded['date']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b>Permission</b></td>
							<td> <textarea style="width:99%" name="permissions" rows="1" cols="14"><?php echo($jsonToEditDecoded['permissions']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b>Verification</b></td>
							<td> <textarea style="width:99%" name="verification" rows="1" cols="14"><?php echo($jsonToEditDecoded['verified']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b>Account Status</b></td>
							<td>
							<input id="accountStatusText" name="accountStatus" value="<?php echo($jsonToEditDecoded['accountStatus']); ?>" />
							<button onclick='banUser()'>Ban User</button>
							<button onclick='suspendUser()'>Suspend User</button>
							<button onclick='unBanUser()'>Unban User</button>
							</td>
						</tr>
						<tr>	
							<td><b>Last Login</b></td>
							<td> <textarea style="width:99%" name="lastLogin" rows="1" cols="14"><?php echo($jsonToEditDecoded['lastLogin']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b># of Violations</b></td>
							<td> <textarea style="width:99%" name="numViolations" rows="1" cols="14"><?php echo($jsonToEditDecoded['numViolations']); ?></textarea><br> </td>
						</tr>
						<tr>	
							<td><b>Privacy</b></td>
							<td> <textarea style="width:99%" name="privacy" rows="1" cols="14"><?php echo($jsonToEditDecoded['privacy']); ?></textarea><br> </td>
						</tr>
					</table>
					
					<!-- Table of the user's group memberships and their permissions in that group -->
					<h4> Groups </h4>
					<table border="1" style="width:100%">
						<tr>
							<td><b>#</b></td>
							<td><b>Id</b></td>
							<td><b>Name</b></td>
							<td><b>Permission</b></td>
						</tr>
						<?php
							
							// loop through the array of group id's in the user's json file 
							for ($i = 0; $i < sizeof($jsonToEditDecoded['groups']); $i++) {
								if(array_key_exists($i, $jsonToEditDecoded['groups'])){
									
									// read in and decode the group information file
									$groupInfo = json_decode(file_get_contents('../groups/grouplist/'.$jsonToEditDecoded['groups'][$i].'/groupinfo.json'), true);			
									
									
									// check to see if the user is in the group's admin list
									$groupPermission = 'standard';
									foreach($groupInfo['admins'] as $adminId){
										if($adminId == $jsonToEditDecoded['id']){
											$groupPermission = 'moderator';
										} // if
									} //foreach
									
									// print table row containing gathered information
									echo("
										<tr class='groupinfo'>
											<td>".$i.".</td>									
											<td class='groupidcell'>".$jsonToEditDecoded['groups'][$i]."</td>
											<td>".nameFromId($jsonToEditDecoded['groups'][$i])."</td>
											<td>".$groupPermission."</td>
										</tr>
									");
									
								// if information stored as json is missing, display errors
								} else {
									echo("
										<tr>
											<td>".$i.".</td>									
											<td>MISSING DATA</td>
											<td>MISSING DATA</td>
											<td>MISSING DATA</td>
										</tr>
									");
								} // else
							}
						?>
					</table>
					
					<!-- submit button -->
				    <button name="submitIndividual" onclick="compileFormData('<?php
						
						// submit the json string with the id of it's owner
						if($_SERVER["REQUEST_METHOD"] == "POST") {
							echo $_POST['id'];
						} else {
							echo $_GET['id'];
						} // else 
					?>')">Submit</button>
				</div>
				
				<!-- Raw json data in editable text area-->
				<h2> Raw JSON </h2>
				<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>"> 
				
					<!-- text area with json pre-entered -->
				    <textarea style="width:100%" name="editedJson" rows="10" cols="70"><?php echo($jsonToEdit);?></textarea><br />
				    <textarea name="id" rows="1" cols="14"><?php
						
						// submit the json string with the id of it's owner
						if($_SERVER["REQUEST_METHOD"] == "POST") {
							echo $_POST['id'];
						} else {
							echo $_GET['id'];
						} // else
					?></textarea>
				    <br />
				    
				    <!-- Submit button -->
				    <input type="submit" name="submitRaw" value="Submit"> 
				</form>
			</div>
			
			<!-- footer bar -->
			<?php include('../footer.inc'); ?>
		</div>
	</body>
</html>