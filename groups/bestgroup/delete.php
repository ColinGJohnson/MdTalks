<?php

	if (!empty($_POST) && $_POST["type"] === "anno") {
		echo "anno";
		
		$data = json_decode(file_get_contents("announcements.json"), true);
		unset($data[$_POST["index"]]);
		$data = array_values($data); // 'reindex' array
		
		
		file_put_contents("announcements.json", json_encode($data));
    
		for($i = sizeof($data) - 1; $i >= 0; $i--) {
			
			echo "<div class='announcement'>".$data[$i]["title"].": ".$data[$i]["desc"]."<span onclick='deleteAnno(".$i.")' class='deletetextanno'>Delete?</span></div>";
							
		}
		
	}

?>
