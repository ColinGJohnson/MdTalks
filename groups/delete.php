<form action="delete.php" method="post"><input name="path" /></form>
<?php
		
		if (!empty($_POST["path"])) {		
			deleteDirectory("grouplist/".$_POST["path"]);
		}

function deleteGroup($path) {

	$members = json_decode(file_get_contents("grouplist/".$path."/groupinfo.json"), true)["members"];
	
	$groupkey = array_search($path, $_SESSION["user"]["groups"]);
	
	unset($_SESSION["user"]["groups"][$groupkey]);
	
	foreach($members as $member) {
		
		echo $member;
		$userdata = json_decode(file_get_contents("../accounts/users/user_".$member.".json"), true);
		
		$groupkey = array_search($path, $userdata["groups"]);
	
		unset($userdata["groups"][$groupkey]);
	
		file_put_contents("../accounts/users/user_".$member.".json", json_encode($userdata));
	
	}
	
	$grouplist = json_decode(file_get_contents("grouplist/groupindex.json"), true);
	
	unset($grouplist[nameFromId($path)]);
	
	file_put_contents("grouplist/groupindex.json", json_encode($grouplist));
	
	deleteDirectory("grouplist/".$path);
}

function deleteDirectory($path) {
	
	
	
	$files = glob($path."/*");
		
		foreach ($files as $file) {
			echo $file;
			if (is_dir($file)) {
				deleteDirectory($file);
			} else {
				unlink($file);
			}
		}
		
		rmdir($path);
		
		echo $path;
}

?>