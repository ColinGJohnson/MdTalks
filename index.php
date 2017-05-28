<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Home Page Layout Test</title>
        <link rel="stylesheet" href="style.css">
		<link href='https://fonts.googleapis.com/css?family=Raleway:400,300' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
        <script src="script.js"></script>
    </head>
	<body>
		<div id="outer">
			<div id="inner">
				<div id="homeBanner">
					<div id="menuBar">
						<a href="index.php"> Home </a> &nbsp&nbsp/&nbsp&nbsp <a href=""> Topics </a> &nbsp&nbsp/&nbsp&nbsp 
						<a href="register.php"> Login </a> &nbsp&nbsp/&nbsp&nbsp <a href=""> About </a>
					</div>
					<div id="bannerContent">					
						<span class="title">
							<b>MD</b> <i>Talks</i>
						</span>
						<br>
						<span class="MDinfoText">
							- The "official" Mount Doug forum! -
						</span>						
						<br>
					</div>
				</div>
				
				<div class="extraInfo">
					<h3> Some Kind Of Intro Here</h3>
					<p class="MDinfoText"> 
						Batman is a fuc boi <-- thanks david appearing in American comic books published by DC Comics. 
						The character was created by artist Bob Kane and writer Bill Finger, and first appeared 
						in Detective Comics #27 (May 1939). Originally named "the Bat-Man," the character is also 
						referred to by such epithets as the "Caped Crusader", the "Dark Knight", and the 
						"World's Greatest Detective".
					</p>
				</div>
				
				<div class='mainTopic'>
					<div class='mainTopicInner' onclick="window.location='groups/bestgroup/index.php';">
						<img class='topicIcon' src='resources/testIcon.jpg' alt='group Icon'>
					</div>
				</div>
				
				<div class='mainTopic'>
					<div class='mainTopicInner'>
						<img class='topicIcon' src='resources/testIcon.jpg' alt='group Icon'>
					</div>
				</div>
				
				<div class='mainTopic'>
					<div class='mainTopicInner'>					
						<img class='topicIcon' src='resources/testIcon.jpg' alt='group Icon'>
					</div>
				</div>
				
				<div class='mainTopic'>
					<div class='mainTopicInner'>
						<img class='topicIcon' src='resources/testIcon.jpg' alt='group Icon'>
					</div>
				</div>
				
				<div id='footer'>
					<div id='footerContent'>
						&copy MD talks, 2015. Contact: info@mdtalks.com
					</div>
				</div>
			</div>
		</div>
	</body>
</html>