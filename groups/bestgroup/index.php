<!DOCTYPE html>
<html>

<head>

<title>Home Page</title>

<link rel="stylesheet" href="style.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script type="text/javascript" src="script.js"></script>

</head>

<body>
<?php

session_start();

$_SESSION['loggedin'] = false;

$groupname = str_replace("/index.php", "", $_SERVER['PHP_SELF']);
$groupname = str_replace("/~mdtalks/groups/", "", $groupname);
?>
<div id="banner">

<div id="bannercontent">
        <span id="bannertitle">MD Talks</span>
        <span id="navbar">
        <span onclick="goToUrl('')" >Home</span>/<span onclick="goToUrl('topics.php')" >Topics</span>/<span onclick="goToUrl('groups.php')" >Groups</span>/<span onclick="goToUrl('accountsettings.php')" >My Account</span></span>
</div>
<div id="bannercontentbg"></div>

</div>

<div id="container">
        <div id="bannerfiller"></div>
        
        <div id="logodescdiv">
        
        <img src="logo.png" id="logo" />
        
        <div id="groupdesc">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras ut nunc elit. Nullam nec nunc at turpis feugiat mattis ut ac nisi. Sed pharetra lobortis mi et facilisis. Donec rhoncus elementum quam, eu ornare nisi imperdiet quis. Aliquam erat volutpat. Cras pretium fermentum ex, a porttitor nunc maximus quis. Cras dapibus eros a arcu tempor scelerisque. Quisque a faucibus tortor, vitae ullamcorper nibh. Vivamus posuere velit id ipsum fermentum pulvinar. Nulla facilisi. Nulla pharetra suscipit arcu, eget sodales nibh aliquam et. Integer ac dolor justo. Etiam vitae ultrices felis. Proin ac metus ut dui consectetur ornare ut at dolor. Etiam posuere purus turpis, tincidunt viverra neque laoreet quis.</div>
        
        <form id="logouploadform" action="" method="get">
        
                <input type="file" name="logo" id="logoupload" />
                <input type="submit" name="submit" />
        
        </form>
        
        <button id="changedesc" onclick="changeDesc()">Change Description</button>
        
        </div>
        
        <div id="announcements">
                <div class="categorytitle">Announcements</div>
                <br /><br /><br />
                <div id="announcecontain">
                <?php

                                $postdata = json_decode(file_get_contents("announcements.json"), true);
                        for($i = sizeof($postdata) - 1; $i >= 0; $i--) {
                        echo "<div class='announcement'>".$postdata[$i]["title"].": ".$postdata[$i]["desc"]."<span onclick='deleteAnno(".$i.")' class='deletetextanno'>Delete?</span></div>";
                        }

                ?>
                </div>
                <button id="announce"  class="buttonadd" onclick="showAddForm()">Add Announcement</button>
                <button id="deleteannounce" class="buttondelete" onclick="allowAnnounceDelete()">Delete Announcement</button>
				<form id="addannounce"  class="addform" action="" method="post">
                        Announcement Title<br />
                        <input type="text" name="title"/><br /><br />
                        Announcement Details<br />
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

                $postdata = json_decode(file_get_contents("threads.json"), true);
                for($i = sizeof($postdata) - 1; $i >= 0; $i--) {

                        if (strlen($postdata[$i]["desc"]) > 600) {
                                $postdata[$i]["desc"] = substr($postdata[$i]["desc"], 0, 597)."...";
                        }

                        echo "<div class='thread' onclick='goToThreadPage(\"".$postdata[$i]["id"]."\", \"".$GLOBALS['groupname']."\")' >".$postdata[$i]["subject"].": <br / >".$postdata[$i]["desc"]."</div>";
                }

                ?>
                </div>
                <button id="buttonaddthread" class="buttonadd" onclick="showAddThreadForm()">Add Thread</button>
                <button id="deletethread" class="buttondelete" onclick="allowThreadDelete()">Delete Thread</button>
				<form id="addthread" class="addform" action="" method="post">
                        Subject<br />
                        <input type="text" name="subject"/><br /><br />
                        Description<br />
                        <textarea id="threaddesc" class="formdesc" name="desc"></textarea>
                        <input type="reset" value="Add Thread" onclick="addToJson('#addthread')" />
                        <input type="text" class="noshow" name="id" value="<?php echo uniqid(); ?>" />
                        <input type="button" onclick="cancelAddThread()" value="Cancel" />
                </form>
        </div>
</div>
<div id="footer">
                &#169; Mount Doug Talks Ltd.
</div>
</body>
</html>
