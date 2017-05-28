<!DOCTYPE html>
<html>
  <head>
	<?php 
	$threadInfo = "";
	$threadInfo = json_decode(file_get_contents("pageInfo.json"), true);
	$threadName = $threadInfo["subject"];
	?>
    <meta charset="UTF-8">
    <title><?php echo $threadName; ?></title>
    
    <link rel="stylesheet" href="style.css">
	<script src="script.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js">	</script>
	<script src="//cdn.jsdelivr.net/jquery.color-animation/1/mainfile"></script>

    
  </head>

  <body onLoad="addToThread('#form')">
	
	<?php $threadInfo = json_decode(file_get_contents("pageInfo.json"), true);
		var_dump($threadInfo);
	?>
	<div id="banner">
		<h1 > Welcome to <?php echo $threadName; ?> </h1>
		<h3> <?php echo $threadInfo['desc']; ?></h3>
	</div>
  
	 <div id="postContainer"></div>
	<form id="form">
		<input id="subject" type="text" class='formText' placeholder="Subject" name="subject" />
		<textarea class='formText' id="message" placeholder="Enter post" name="message" ></textarea>
		<input type="text" class='formText' id="name" placeholder="Enter your name" name="user" />
		<input type="reset" class="button" id="submit" value="Enter post" onClick="addToThread('#form')"/>
	</form>
  
  </body>
</html>
