<?php

        if (!empty($_POST) && $_POST["type"] === "anno") {
                $groupid = $_POST["groupid"];

                $data = json_decode(file_get_contents("../grouplist/".$groupid."/announcements.json"), true);
                unset($data[$_POST["index"]]);
                $data = array_values($data); // 'reindex' array

                file_put_contents("../grouplist/".$groupid."/announcements.json", json_encode($data));
               
        for ($i = sizeof($data) - 1; $i >= 0; $i--) {
            echo "<div class='announcement'><span class='announcetitle'>".$data[$i]["title"].":</span> ".$data[$i]["desc"]."<span onclick='deleteAnno(".$i.", \"".$groupid."\")' class='deletetextanno' style='display: block;'>Delete</span></div>";
        }

        } else if (!empty($_POST) && $_POST["type"] === "thre") {

                $groupid = $_POST["groupid"];

                $data = json_decode(file_get_contents("../grouplist/".$groupid."/threads.json"), true);
                unset($data[$_POST["index"]]);
                $data = array_values($data); // 'reindex' array
    
        deleteDirectory("../grouplist/".$groupid."/threads/".$_POST["id"]);

                file_put_contents("../grouplist/".$groupid."/threads.json", json_encode($data));

                for($i = sizeof($data) - 1; $i >= 0; $i--) {

            if (strlen($data[$i]["desc"]) > 600) {
                $data[$i]["desc"] = substr($data[$i]["desc"], 0, 597)."...";
            }

            echo "<div class='thread' onclick='goToThreadPage(\"".$data[$i]["id"]."\", \"".$groupid."/\")' ><span class='threadtitle'>".$data[$i]["subject"].":</span><br / >".$data[$i]["desc"]."<span onclick='deleteThread(".$i.", \"".$data[$i]["id"]."\", \"".$groupid."\")' class='deletetextthread' style='display: block;' >Delete</span></div>";
                }

        } else {
			header("Location: http://142.31.53.22/~mdtalks/404.php");
		}

        function deleteDirectory($path) {

                $files = glob($path."/*");

                foreach ($files as $file) {
                        unlink($file);
                }

                rmdir($path);

        }

?>
