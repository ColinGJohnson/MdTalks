<?php
session_start();

//if we're adding a thread
if (!empty($_POST) && array_key_exists("subject", $_POST)) {
    echo "thre";

    $_POST["id"] = uniqid();
	$groupid = $_POST["groupid"];
	
	//Make sure there is no html in the post
	$_POST["subject"] = htmlspecialchars($_POST["subject"]);
    $_POST["desc"] = htmlspecialchars($_POST["desc"]);
    $totaldata = file_get_contents("../grouplist/".$groupid."/threads.json");
    $totaldata = str_replace("]", "", $totaldata);

    if (strlen($totaldata) === 1) {
        $totaldata .= json_encode($_POST)."]";
    } else {
        $totaldata .= ",".json_encode($_POST)."]";
    }//else

	$oldmask = umask(0);
	
	//If the groups odes not yet exist, create it
	if (!file_exists("../grouplist/".$groupid."/threads")) {
		mkdir("../grouplist/".$groupid."/threads", 0777);
	}//if
	
	file_put_contents("../grouplist/".$groupid."/threads.json", $totaldata);
	chmod("../grouplist/".$groupid."/threads.json", 0777);

	mkdir("../grouplist/".$groupid."/threads/".$_POST["id"], 0777);

	file_put_contents("../grouplist/".$groupid."/threads/".$_POST["id"]."/posts.json", "[]");
	chmod("../grouplist/".$groupid."/threads/".$_POST["id"]."/posts.json", 0777);

	//Set data about the thread
	$threadinfo = new stdClass();
	$threadinfo->subject = htmlspecialchars($_POST["subject"]);
	$threadinfo->desc = htmlspecialchars($_POST["desc"]);
	$threadinfo->user = $_SESSION["user"]["firstname"]." ".$_SESSION["user"]["lastname"];

	file_put_contents("../grouplist/".$groupid."/threads/".$_POST["id"]."/pageInfo.json", json_encode($threadinfo));
	chmod("../grouplist/".$groupid."/threads/".$_POST["id"]."/pageInfo.json", 0777);

	$postdata = json_decode(file_get_contents("../grouplist/".$groupid."/../".$groupid."/threads.json"), true);
	
	for($i = sizeof($postdata) - 1; $i >= 0; $i--) {
		if (strlen($postdata[$i]["desc"]) > 600) {
			$postdata[$i]["desc"] = substr($postdata[$i]["desc"], 0, 597)."...";
		}//if
		echo "<div class='thread' onclick='goToThreadPage(\"".$postdata[$i]["id"]."\", \"".$groupid."/\")' ><span class='threadtitle'>".$postdata[$i]["subject"].":</span><br / >".$postdata[$i]["desc"]."<span onclick='deleteThread(".$i.", \"".$postdata[$i]["id"]."\", \"".$groupid."\")' class='deletetextthread'>Delete</span></div>";
	}//for


	umask($oldmask);

//if we're adding an announcement
} else if (!empty($_POST) && array_key_exists("title", $_POST)) {
	echo "anno";

	$_POST["title"] = htmlspecialchars($_POST["title"]);
	$_POST["desc"] = htmlspecialchars($_POST["desc"]);
	$groupid = $_POST["groupid"];

    $totaldata = file_get_contents("../grouplist/".$groupid."/announcements.json");
    $totaldata = str_replace("]", "", $totaldata);

    if (strlen($totaldata) === 1) {
        $totaldata .= json_encode($_POST)."]";
    } else {
        $totaldata .= ",".json_encode($_POST)."]";
    }//else

	file_put_contents("../grouplist/".$groupid."/announcements.json", $totaldata);

	$postdata = json_decode(file_get_contents("../grouplist/".$groupid."/announcements.json"), true);
	
	for ($i = sizeof($postdata) - 1; $i >= 0; $i--) {
		echo "<div class='announcement'><span class='announcetitle'>".$postdata[$i]["title"].":</span> ".$postdata[$i]["desc"]."<span onclick='deleteAnno(".$i.", \"".$groupid."\")' class='deletetextanno'>Delete</span></div>";
	}//for
    
//if we're changing the group description
} else if (!empty($_POST) && array_key_exists("groupdesc", $_POST)) {

        $groupid = $_POST["groupid"];

        $groupdata = json_decode(file_get_contents("../grouplist/".$groupid."/groupinfo.json"), true);

        $groupdata["groupdesc"] = $_POST["groupdesc"];

        file_put_contents("../grouplist/".$groupid."/groupinfo.json", json_encode($groupdata));

//if we're changing the logo
} else if (!empty($_FILES)) {
        $groupid = $_POST["groupid"];

        move_uploaded_file($_FILES["file"]["tmp_name"], "../grouplist/".$groupid."/logo.jpg");

} else {
	header("Location: http://142.31.53.22/~mdtalks/404.php");
}//else

//Copy all of the files from from one directory to another
function copyDirectory ($source, $dest) {

    $files = glob($source."/*");
    
    
    foreach($files as $file) {
        copy($file, $dest."/".basename($file));
    }
}

?>
