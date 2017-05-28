<link rel="stylesheet" property='stylesheet' href="headerStyle.css" >
<link rel="shortcut icon" property='stylesheet' type="image/ico" href="favicon.ico" />
<div id="menuBar">
	
	<a href="http://142.31.53.22/~mdtalks/" id='headerMenuIcon' ><img id='headerIcon' src='http://142.31.53.22/~mdtalks/resources/mdtalkslogo.png' alt='MDtalks logo'></a>
	
	<div id='menuItems'>
	<a href='http://142.31.53.22/~mdtalks/' class='headerMenuItem'> Home </a>  
	
	<a href="http://142.31.53.22/~mdtalks/about.php" class='headerMenuItem'> About </a>
	
	<?php 
	
	if (session_status() != PHP_SESSION_ACTIVE) {
		session_start();
	}
	if ( array_key_exists("user", $_SESSION) && $_SESSION["user"] != null) {
		echo("<a href='http://142.31.53.22/~mdtalks/accounts/profile.php' class='headerMenuItem'>".$_SESSION['user']['firstname']."</a>");
	} else {
		echo("<a href='http://142.31.53.22/~mdtalks/accounts/register.php' class='headerMenuItem'> Register </a>");
	}				
	if(array_key_exists("user", $_SESSION) && $_SESSION["user"] != null) {
		echo "<a href='http://142.31.53.22/~mdtalks/accounts/logout.php' class='headerMenuItem'> Logout </a>";
		
		if($_SESSION['user']['permissions'] == 'admin'){
			echo("
				<a href='http://142.31.53.22/~mdtalks/admin/userList.php' class='headerMenuItem'> Admin Tools </a>
			");
		} 
	} else { 
		echo "<a href='http://142.31.53.22/~mdtalks/accounts/login.php' class='headerMenuItem'> Login </a>";
	} 
	?>
	</div>
</div>
<div id="fill"></div>