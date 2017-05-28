<!DOCTYPE html>
<html>
        <head>
                <title>Home Page</title>
                <link rel="stylesheet" href="../../style.css">
                <link rel="stylesheet" href="style.css">
                <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
                <script type="text/javascript" src="script.js"></script>
        </head>
        <body>
                <?php

                session_start();

                $groupid = $_GET["groupid"];

                include("../groupfunctions.php");
                $groupname = nameFromId($groupid);

                if ($groupname == null) {
                        header("Location: ../../404.php");
                }

                $admins = json_decode(file_get_contents("../grouplist/".$groupid."/groupinfo.json"), true)["admins"];
                $members = json_decode(file_get_contents("../grouplist/".$groupid."/groupinfo.json"), true)["members"];
                $permission = 0; 
                          
                if (array_key_exists("user", $_SESSION) && in_array($_SESSION["user"]["id"], $members)) {
                    $permission = 1;
                }
                          
                if (array_key_exists("user", $_SESSION) && in_array($_SESSION["user"]["id"], $admins)) {
                	$permission = 2;
                }

                ?>

                <div id="outer">
                        <div id="inner">
                                        <?php
                                             if ($permission > 0) {
                                             	include("settingspanel.inc");
                                             }
                                            
                                             include ('../../header.inc'); 
                                        
                                        ?>

                                        <div id="logodescdiv">
                                                <div id="grouptitle"><?php echo $groupname; ?></div>

                                                <?php 

                                             	if ($permission > 0) {
                                                	echo "<img id='settingstoggle' src='../../resources/settings.png' onclick='toggleSettingsPanel()' />";
												}
                                                ?>

                                                <div id="logo" style="background-image:url('../grouplist/<?php echo $groupid; ?>/logo.jpg');"></div>

                                                <div id="groupdesc"><?php echo json_decode(file_get_contents("../grouplist/".$groupid."/groupinfo.json"), true)["groupdesc"]; ?>
                                                </div>

                                                <form id="logouploadform" class="noshow" >
                                                                <input type="file" name="logo" id="logoupload" />
                                                </form>

                                                <?php

                                                if ($permission == 2) {
                                                        echo "<button id=\"changedesc\" onclick=\"changeDesc('".$groupid."')\">Change Description</button>";
                                                }
                                                           
                                                ?>  
                                        </div>

                                        <div id="announcements">
                                                <div class="categorytitle">Announcements</div>
                                                <br /><br /><br />
                                                <div id="announcecontain">
                                                        <?php
                                                                $postdata = json_decode(file_get_contents("../grouplist/".$groupid."/announcements.json"), true);

                                                                for ($i = sizeof($postdata) - 1; $i >= 0; $i--) {
                                                                        echo "<div class='announcement'><span class='announcetitle'>".$postdata[$i]["title"].":</span> ".$postdata[$i]["desc"]."<span onclick='deleteAnno(".$i.", \"".$groupid."\")' class='deletetextanno'>Delete</span></div>";
                                                                }
                                                        ?>
                                                </div>

                                                <?php
                                                        if ($permission == 2) {
                                                                echo "<button id=\"announce\"  class=\"buttonadd\" onclick=\"showAddForm()\">Add Announcement</button>
                                                                          <button id=\"deleteannounce\" class=\"buttondelete\" onclick=\"allowAnnounceDelete()\">Delete Announcement</button>";
                                                        }
                                                ?>

                                                <form id="addannounce"  class="addform" action="" method="post">
                                                                        Announcement Title<br />
                                                                        <input type="text" name="title"/><br /><br />
                                                                        Announcement Details<br />
                                                                        <input name="groupid" class="noshow" value="<?php echo $groupid ?>" />
                                                                        <textarea id="announcedesc" class="formdesc" name="desc"></textarea>
                                                                        <input type="reset" value="Add Announcement" onclick="addToJson('#addannounce')" />
                                                                        <input type="button" onclick="cancelAddAnnounce()" value="Cancel" />
                                                </form>
                                        </div>

                                        <div id="threads">
                                                <div class="categorytitle">Discussions</div>
                                                <br /><br />
                                                <div id="threadcontain">
                                                        <?php
                                                                $postdata = json_decode(file_get_contents("../grouplist/".$groupid."/../".$groupid."/threads.json"), true);
                                                                for($i = sizeof($postdata) - 1; $i >= 0; $i--) {

                                                                                if (strlen($postdata[$i]["desc"]) > 600) {
                                                                                                $postdata[$i]["desc"] = substr($postdata[$i]["desc"], 0, 597)."...";
                                                                                }

                                                                                echo "<div class='thread' onclick='goToThreadPage(\"".$postdata[$i]["id"]."\", \"".$groupid."/\")' ><span class='threadtitle'>".$postdata[$i]["subject"].":</span><br / >".$postdata[$i]["desc"]."<span onclick='deleteThread(".$i.", \"".$postdata[$i]["id"]."\", \"".$groupid."\")' class='deletetextthread'>Delete</span></div>";
                                                                }
                                                        ?>
                                                </div>

                                                <?php      
                                                        if ($permission > 0) {
                                                                echo "<button id=\"buttonaddthread\"  class=\"buttonadd\" onclick=\"showAddThreadForm()\">Add Discussion</button>";
                                                        }

                                                        if ($permission == 2) {
                                                                echo "<button id=\"deletethread\" class=\"buttondelete\" onclick=\"allowThreadDelete()\">Delete Discussion</button>";
                                                        }   
                                                ?>
                                                <form id="addthread" class="addform" action="" method="post">
                                                                        Subject<br />
                                                                        <input type="text" name="subject"/><br /><br />
                                                                        Description<br />
                                                                        <textarea id="threaddesc" class="formdesc" name="desc"></textarea><br />
                                                                        <input type="reset" value="Add Discussion" onclick="addToJson('#addthread')" />
                                                                        <input name="groupid" class="noshow" value="<?php echo $groupid ?>" />
                                                                        <input type="text" class="noshow" name="id" value="" />
                                                                        <input type="button" onclick="cancelAddThread()" value="Cancel" />
                                                </form>
                                        </div>
                                        <?php include('../../footer.inc'); ?>
                        </div>
                </div>
        </body>
</html>
