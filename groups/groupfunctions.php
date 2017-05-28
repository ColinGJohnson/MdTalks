<?php
	
	//if the session hasn't started yet, start session for access to logged in user info
	//the session will have started if this file is called by something other than javascript
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}//if
	
	//the if statements below are all for AJAX calls
	
	//all ajax calls to this file have the 'type' index
	//so we're making sure this exists
	if (array_key_exists("type", $_POST)) {
		
		//if the type of ajax call is a user asking to join a group...
		if ($_POST['type'] == "asktojoingroup") {
			
			//getting the users on the group's waiting list
			$file = json_decode(file_get_contents("grouplist/".$_POST['groupid']."/pending.json"), true);
			
			//making sure the user isn't already on the waiting list
			if (!array_search($_SESSION['user']['id'], $file)) {
			
				//adding the user's id to the waiting list
				array_push($file, $_SESSION['user']['id']);
			}//if
			
			//writing the newly edited waiting list to the group folder
			file_put_contents("grouplist/".$_POST['groupid']."/pending.json", json_encode($file));
			
			
		//for calls that create a group
		//this is actually called from a <form> submit in profile.php
		} else if ($_POST['type'] == 'creategroup') {
		
			//making sure group name isn't empty
			//if so, we redirect back to the profile page
			if (!empty($_POST['groupname'])) {
			
				//creating the group
				createGroup($_POST["groupname"], $_POST["creator"]);
			
			} else {
				
				header("Location: http://142.31.53.22/~mdtalks/accounts/profile.php");
			
			}//else
			
		//for ajax calls to add an admin to a group
		} else if ($_POST["type"] == "addingadmin") {
			
			//NOTE: admins must be a member in the group before being promoted
			
			//the ajax call will tell us the user to promote by their email
			//but we need the user's ID. So, we get the ID
			$adminid = userIdFromEmail($_POST["admintoadd"]);
			
			//if the ID isn't null, i.e. the user exists,
			//we begin to promote the user
			if ($adminid != null) {
				addAdmin($adminid, $_POST["groupidtoadd"]);
			} else {
				echo "That user doesn't exist!";
			}//else
		
		//for ajax calls to allow a member into a group
		} else if ($_POST['type'] == 'approvingmember') {
		
			//adding the user into the group
			addMember($_POST['memid'], $_POST['groupid']);
			
			//getting the group's waiting list
			$data = json_decode(file_get_contents("grouplist/".$_POST['groupid']."/pending.json"), true);
			
			//getting the index of the user on the waiting list
			$key = array_search($_POST['memid'], $data);
			
			//if the user was on the waiting list
			//we remove them from the waiting list
			//and write the newly edited list to the group folder
			if ($key !== false) {
			
				unset($data[$key]);
				
				$data = array_values($data); //making sure the waiting list is still an array
				
				file_put_contents("grouplist/".$_POST['groupid']."/pending.json", json_encode($data));
			
			}//if
		
		//for ajax calls to delete a group
		} else if ($_POST["type"] == "deletinggroup") {
			
			//deleting the group...	
			deleteGroup("grouplist/".$_POST["deletethisgroup"]);
 
		//for ajax calls to remove a user from a group
		} else if ($_POST['type'] == 'leavinggroup') {
		
			//kicking the user out of the group...
			removeUserFromGroup($_POST["grouptoleave"], $_POST["leavinguser"]);
		
		}//elseif
	
	}//if
	
	//this is to kick a user from a single group
	function removeUserFromGroup($groupid, $userid) {
		
		//getting the groupdata for editing
		$groupdata = getGroupFromID($groupid);
					
		//if the user is a member, we remove them from the member list
		if (array_search($userid, $groupdata["members"]) !== false) {
					
			unset($groupdata["members"][array_search($userid,$groupdata["members"])]);
					
		}//if
			
			
		//if the user is an admin, we remove them from the admin
		//if the user was the only admin, we promote the oldest member in the group to admin status
		//if the user was the only member, we delete the group :(
		if (array_search($userid, $groupdata["admins"]) !== false) {
				
			unset($groupdata["admins"][array_search($userid,$groupdata["admins"])]);
			
			if (empty($groupdata["admins"])) {
				if (!empty($groupdata["members"])) {
					$promotemember = $groupdata["members"][0];
					$groupdata["admins"][0] = $promotemember;
				} else {
					deleteGroup("grouplist/".$groupid);
					return;
				}
			}
			
		}
		
		//we remove the group from the user's group list
		deleteGroupFromUserJson($userid, $groupid);
		
		//making sure the group's member/admin list are still arrays
		$groupdata["members"] = array_values($groupdata["members"]);
		$groupdata["admins"] = array_values($groupdata["admins"]);
		
		//writing the newly edited group data to the group folder
		file_put_contents("grouplist/".$groupid."/groupinfo.json", json_encode($groupdata));
	}
	
	//getting all group data from a group id
	function getGroupFromId($id){
		return (json_decode(file_get_contents("grouplist/".$id."/groupinfo.json"), true));	
	}//getGroupFromId
	
	//getting the name of the group from the group id
	function nameFromId($id) {
	
		$groups = json_decode(file_get_contents("http://142.31.53.22/~mdtalks/groups/grouplist/groupIndex.json"), true);
		
		return array_search($id, $groups);
		
	}//nameFromId
	
	//removing a group from a user file
	function deleteGroupFromUserJson($userid, $groupid) {
	
		//getting the user info (to edit it)
		$userdata = userFromID($userid);
		
		//removing the group from the user's group list
		unset($userdata["groups"][array_search($groupid, $userdata["groups"])]);
		
		//making sure the group list is still an array and not an object
		$userdata["groups"] = array_values($userdata["groups"]);
		
		//writing the new user info the user file
		file_put_contents("../accounts/users/user_".$userid.".json", json_encode($userdata));
		
	}//deleteGroupFromUserJson
	
	//for creating an entirely new group
	function createGroup ($groupname, $creator) {
		
		//generating the new groupid
		$groupid = uniqid();
		
		//allowing us to make completely public files/folders
		$oldumask = umask();
		umask(0);
		
		//making the group folder (completely public)
		mkdir("grouplist/".$groupid, 0777);
		
		//making the announcements, threads, user waiting list files
		//and making sure they're completely public	
		file_put_contents("grouplist/".$groupid."/announcements.json", '[]');
		chmod("grouplist/".$groupid."/announcements.json", 0777);
		
		file_put_contents("grouplist/".$groupid."/threads.json", '[]');
		chmod("grouplist/".$groupid."/threads.json", 0777);
		
		file_put_contents("grouplist/".$groupid."/pending.json", '[]');
		chmod("grouplist/".$groupid."/pending.json", 0777);
			
		
		//inserting the new group in the website list of groups
		$groupindex = json_decode(file_get_contents("grouplist/groupIndex.json"), true);
		
		$groupindex[$groupname] = $groupid;
		
		file_put_contents("grouplist/groupIndex.json", json_encode($groupindex));
		
		
		//creating the general info object about the group
		//and inserting the default values
		$groupinfo = new stdClass();
		$groupinfo->groupdesc = "Insert group description here!";
		$groupinfo->groupname = $groupname;
		$groupinfo->groupid = $groupid;
		$groupinfo->admins = [];
		$groupinfo->members = [];		
		$groupinfo->privacy  = 'private';
		$groupinfo->dateCreated = date("M. d, Y");
		
		//storing the group info in the group folder
		//and making sure it's public
		file_put_contents("grouplist/".$groupid."/groupinfo.json", json_encode($groupinfo));
		chmod("grouplist/".$groupid."/groupinfo.json", 0777);
		
		
		//adding the creator of the group as a member and an admin
		addMember($creator, $groupid);
		addAdmin($creator, $groupid);
		
		//resetting the file permission setting
		umask($oldumask);
		
		//redirecting to the new group page!
		header("Location: grouppage/?groupid=".$groupid);
	}//createGroup
	
	
	//for deleting a group
	//$path is the path from this file to the group folder
	function deleteGroup($path) {

		//getting the member list
		$members = json_decode(file_get_contents($path."/groupinfo.json"), true)["members"];
		
		//removing the group from the currently logged in user's info
		if (array_key_exists("user", $_SESSION)) {
			$groupkey = array_search(str_replace("grouplist/", "", $path), $_SESSION["user"]["groups"]);
			unset($_SESSION["user"]["groups"][$groupkey]);
		}//if
		
		//deleting the group from all of its members' info files
		foreach($members as $member) {
			
			if (file_exists("../accounts/users/user_".$member.".json")) {
				deleteGroupFromUserJson($member, str_replace("grouplist/", "", $path));
			}//if
			
		}
		
		//removing the group from the website group list
		$grouplist = json_decode(file_get_contents($path."/../groupIndex.json"), true);
			
		unset($grouplist[nameFromId(str_replace("grouplist/", "", $path))]);
		
		file_put_contents($path."/../groupIndex.json", json_encode($grouplist));
		
		//finally, deleting the actual group folder
		deleteDirectory($path);
	
	}//deleteGroup
	
	//get the user id (more useful) from the user's email
	function userIdFromEmail ($email) {
		
		//reading in the user list of the website
		$users = json_decode(file_get_contents("../accounts/userIndex.json"), true);
		
		//returning the user's id (if they exist)
		if (array_key_exists($email, $users)) {
			return $users[$email];
		} else {
			return null;
		}//else
	
	}//userIdFromEmail
	
	//getting the user's data from their id (if they exist)
	function userFromID ($id) {
		
		$users = json_decode(file_get_contents("../accounts/userIndex.json"), true);
		
		if (array_search($id, $users) !== false) {
			return json_decode(file_get_contents("../accounts/users/user_".$id.".json"), true);
		} else {
			return null;
		}//else
	
	}//userFromID
	
	//for writing newly edited user info to their info file
	function writeUser($user){
		file_put_contents("../accounts/users/user_.".$user['id'].".json", json_encode($user));
	}
	
	//for adding an admin to a group
	function addAdmin($admin, $groupid) {
		
		//NOTE: all echoed strings are then alerted via javascript
		
		//reading the full group info
		$groupdata = getGroupFromID($groupid);
		
		//getting the website user list
		$totaluserdata = json_decode(file_get_contents("../accounts/userIndex.json"), true);
		
		//making sure the user isn't an admin already, they're a member of the group, and they exist
		if (in_array($admin, $groupdata["admins"]) == false && in_array($admin, $groupdata["members"]) !== false
		 && array_search($admin, $totaluserdata) != false) {
		
			array_push($groupdata["admins"], $admin);
			
		//if the user doesn't exist, say so
		} else if (array_search($admin, $totaluserdata) == false) {
			echo "That user doesn't exist!";
			
		//if the user isn't a member of the group, say so
		} else if (in_array($admin, $groupdata["members"]) == false) {
			echo "That user isn't in this group!";
		}//else
		
		//writing the newly edited group info to the group info file
		file_put_contents("grouplist/".$groupid."/groupinfo.json", json_encode($groupdata));
		
	}//addAdmin
	
	//adding a member to a group
	function addMember($member, $groupid) {
		
		//getting the fill group info
		$groupdata = json_decode(file_get_contents("grouplist/".$groupid."/groupinfo.json"), true);
		
		//getting the website user list
		$totaluserdata = json_decode(file_get_contents("../accounts/userIndex.json"), true);
		
		//if the user isn't already a member and they exist
		//add the user to the group and add the group to their info file
		if (in_array($member, $groupdata["members"]) == false && array_search($member, $totaluserdata) != false) {
			$groupdata["members"][sizeof($groupdata["members"])] = $member;
			joinGroup($member, $groupid);
		}//if
		
		//write the newly edited member list to the group file
		file_put_contents("grouplist/".$groupid."/groupinfo.json", json_encode($groupdata));
		
	}//addMember
	
	//adds a group to a user's group list
	function joinGroup ($user, $groupid) {
		
		//if the user is logged in,
		//add the group to their grouplist
		if ($user === $_SESSION["user"]["id"]) {
			sessionJoinGroup($groupid);
		}//if
		
		//getting the user info from the file
		$userdata = userFromID($user);
		
		//inserting the group into the user's group list
		array_push($userdata["groups"], $groupid);
			
		//writing the new group list to the user's info file
		file_put_contents("../accounts/users/user_".$user.".json", json_encode($userdata));
		
	}//joinGroup
	
	//adds a group to the currently logged in user's _SESSSION variable
	function sessionJoinGroup ($groupid) {
	
		//if there's a user logged in, and the group to add isn't already on their group list
		//we add the group to their list
		if (array_key_exists("user", $_SESSION) && in_array($groupid, $_SESSION["user"]["groups"]) === false) {
			array_push($_SESSION["user"]["groups"], $groupid);
		}//if
		
	}//sessionJoinGroup
	
	//for deleting a folder
	function deleteDirectory($path) {
		
		//gets all the files in the folder
		$files = glob($path."/*");
		
		//runs through each file
		foreach ($files as $file) {
			
			//if the file is a directory,
			//call this function on the file
			if (is_dir($file)) {
				deleteDirectory($file);
				
			//if it's just a file, delete it
			} else {	
				unlink($file);
			}//else
		}//foreach
		
		//after all the files have been deleted,
		//we delete the folder
		rmdir($path);
		
	}//deleteDirectory
	
?>