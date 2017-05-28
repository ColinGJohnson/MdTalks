<?php 
	session_start();
	
	//This returns an array containing user data from a the user's id
	function userFromID ($id) {
		$users = json_decode(file_get_contents("../../accounts/userIndex.json"), true);
		
		if (array_search($id, $users) !== false) {
			return json_decode(file_get_contents("../../accounts/users/user_".$id.".json"), true);
		} else {
			return null;
		}//else
	}//userFromId
	
	if (array_key_exists('groupid', $_POST) !== false) {
	
		if($_SESSION["user"] != null){
		
			$write = [];
			$write['id'] = $_SESSION["user"]["id"];
			$write['text'] = htmlspecialchars($_POST["text"]);
		
			$totaldata = file_get_contents("../grouplist/".$_POST["groupid"]."/threads/".$_POST["threadId"]."/posts.json");
			$totaldata = str_replace("]", "", $totaldata);

			if (strlen($totaldata) === 1) {
				
				$totaldata .= json_encode($write)."]";
				
			} else {
				
				$totaldata .= ",".json_encode($write)."]";
				
			}//else
			
			file_put_contents("../grouplist/".$_POST["groupid"]."/threads/".$_POST["threadId"]."/posts.json", $totaldata);
		
		}//if
		
		$data = json_decode(file_get_contents("../grouplist/".$_POST["groupid"]."/threads/".$_POST["threadId"]."/posts.json"), true);
	
		foreach ($data as $post) {
			$user = userFromID($post['id']);
			$imagePath = '../../accounts/profilePics/profilePic_'.$post["id"].'.jpg';
						
			if (file_exists($imagePath) == false) {
				$imagePath = "../../resources/imageMissing.png";
			}
						
			echo "<div class='post'><div class='posttext'>".$post["text"]."</div><br /><a class='username' href='../../accounts/publicProfile.php?targetId=".$post['id']."'><span class='nametext'>".$user['firstname']." ".$user['lastname']."</span><img class='thumbnail' src='".$imagePath."' /></a></div>";
		}//foreach
		
	} else {
		header("Location: http://142.31.53.22/~mdtalks/404.php");
	}//else
	
?>