<?php

	function test_input($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
	
  

	function newPost($array){
		return "<div class='post'><div class='subject'>".test_input($array["subject"])."</div><div class='text'>".test_input($array["message"])."</div><div class='name'>~".test_input($array["user"])."</div></div>";
	}
	
	if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST)) {
		
		
		$posts = json_decode(file_get_contents("posts.json"), true);
		if(!empty($_POST['message'])){
			array_push($posts, $_POST);
			file_put_contents("posts.json", json_encode($posts));
		}
		
		$size = sizeof($posts);
		for($i = 0; $i < $size; $i++){
			echo newPost($posts[$i]);
		}
	}
?>