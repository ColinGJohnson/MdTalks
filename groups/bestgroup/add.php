<?php

//if we're adding a thread
if (!empty($_POST) && array_key_exists("subject", $_POST)) {
        echo "thre";

    $totaldata = file_get_contents("threads.json");
    $totaldata = str_replace("]", "", $totaldata);

    if (strlen($totaldata) === 1) {
        $totaldata .= json_encode($_POST)."]";
    } else {
        $totaldata .= ",".json_encode($_POST)."]";
    }
                        
        file_put_contents("threads.json", $totaldata);
        mkdir("threads/".$_POST["id"], 0777);
        copyDirectory("../template/thread", "threads/".$_POST["id"]);
        file_put_contents("threads/".$_POST["id"]."/pageInfo.json", "{\"subject\":\"".$_POST["subject"]."\",\"desc\":\"".$_POST["desc"]."\"}");

        $postdata = json_decode($totaldata, true);
    
    for($i = sizeof($postdata) - 1; $i >= 0; $i--) {

        if (strlen($postdata[$i]["desc"]) > 600) {
            $postdata[$i]["desc"] = substr($postdata[$i]["desc"], 0, 597)."...";
        }

        echo "<div class='thread' onclick='goToThreadPage(\"".$postdata[$i]["id"]."\")' >".$postdata[$i]["subject"].": <br / >".$postdata[$i]["desc"]."</div>";
    }

//if we're adding an announcement
} else if (!empty($_POST) && array_key_exists("title", $_POST)) {
        echo "anno";

    $totaldata = file_get_contents("announcements.json");
    $totaldata = str_replace("]", "", $totaldata);

    if (strlen($totaldata) === 1) {
        $totaldata .= json_encode($_POST)."]";
    } else {
        $totaldata .= ",".json_encode($_POST)."]";
    }

        file_put_contents("announcements.json", $totaldata);

        $postdata = json_decode($totaldata, true);
    
    for($i = sizeof($postdata) - 1; $i >= 0; $i--) {
        
        echo "<div class='announcement'>".$postdata[$i]["title"].": ".$postdata[$i]["desc"]."<span onclick='deleteAnno(".$i.")' class='deletetextanno'>Delete?</span></div>";
                        
    }
}

function copyDirectory ($source, $dest) {

        $files = glob($source."/*");

        foreach($files as $file) {
                echo $file;
                copy($file, $dest."/".basename($file));
        }

}

?>
