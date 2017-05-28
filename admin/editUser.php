<?php
	// create a new user data array with submitted values
	// save new json text to user file
	file_put_contents('../accounts/users/user_'.$_POST['id'].'.json', json_encode($_POST));
?>