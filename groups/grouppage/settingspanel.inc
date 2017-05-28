<div id="settingspanel" style="display:none">	
	Settings<br />
	
	<button class='settingsbutton' onclick='openUserWindow()'>User List</button>
	<?php
	if ($permission == 2) {
		
		echo "<button class='settingsbutton' onclick='findLogo(\"".$groupid."\")'>Change Logo</button>
		<button class='settingsbutton' onclick='addAdmin(\"".$groupid."\")'>Add an Admin</button>
		<button class='settingsbutton' onclick='goToUrl(\"groups/grouppage/addusers.php?groupid=".$groupid."\")'>Approve New Users</button>
		<button class='settingsbutton' id='warning' onclick='deleteGroup(\"".$groupid."\")'>Delete Group</button>";
	}
	
	if ($groupid != '569e9d51c585f') {
		echo "<button class='settingsbutton' id='warning' onclick=\"leaveGroup('".$_SESSION['user']['id']."', '".$groupid."','userchoice', event)\">Leave Group</button>";
	}
	
	?>
	<button class="settingsbutton" id='closesettings' onclick="toggleSettingsPanel()">Close Settings</button>
	
</div>

<?php
		
	echo "<div id='userlist' style='display: none'>";
	
	echo "<h2>User List</h2>";
	
	$userlist = json_decode(file_get_contents("../grouplist/".$groupid."/groupinfo.json"), true)['members'];
	$groupadmins = json_decode(file_get_contents("../grouplist/".$groupid."/groupinfo.json"), true)['admins'];
	
	foreach ($userlist as $user) {
		$userdata = json_decode(file_get_contents("../../accounts/users/user_".$user.".json"), true);
		
		echo "<a href='http://142.31.53.22/~mdtalks/accounts/publicProfile.php?targetId=".$user."'><div class='useritem'>".$userdata['firstname']." ".$userdata['lastname'];
		
		if ($permission == 2 && array_search($user, $groupadmins) === false && $groupid != '569e9d51c585f') {
			echo "<button class='kickuser' id='warning' onclick='leaveGroup(\"".$user."\", \"".$groupid."\", \"kickuser\", event)' >Kick User</button>";
		}
		
		echo "</div></a> \n";
		
	}
	
	echo "<button id='closeuserlist' onclick='closeUserWindow()'>Close List</button>";
	
	echo "</div>";
	
?>