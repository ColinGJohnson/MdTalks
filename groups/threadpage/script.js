
//Uses AJAX to send data to addpost.php so that posts are added without
//reloading the page
function addPost(groupid, threadId) {

	$.ajax({
		type:"POST",
		url:"addpost.php",
		data: {"groupid": groupid, "threadId":threadId, "text":$("#addpostarea").val(), "user":$("#usernameinput").val()},
		success : function(response) {
			document.getElementById("postscontain").innerHTML = response;
		}//success
	});
		
	$("#addpostarea").val("");		
}//addPost