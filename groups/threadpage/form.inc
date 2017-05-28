<div id="addpostform">
	<div id="addposttitle">Add To Discussion:</div>
	<textarea id="addpostarea"></textarea>
	<button id="addpostbutton" onclick="addPost(<?php echo "'".$groupid."', '".$threadid."'"; ?>)">Add Post</button>
	<input style="display:none" id="usernameinput" value="<?php echo $_SESSION["user"]["firstname"]; ?>" />
</div>