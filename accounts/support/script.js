function showPassword() {

	if ($("#showpassword").html() == "Show") {
		$.ajax({
			type:"POST",
			url:"support/functions.php",
			data: {"type":"showpassword"},
			success : function(response) {
				$("#password").html("Password: " + response);
		}});
			
		$("#showpassword").html("Hide");
	} else {
		$.ajax({
			type:"POST",
			url:"support/functions.php",
			data: {"type":"hidepassword"},
			success : function(response) {
				$("#password").html("Password: " + response);
		}});
			
		$("#showpassword").html("Show");
	
	}

}

function joinGroup() {
	
	window.location = "../groups/";

}

function uploadProfileImage(){
	
	$("#fileToUpload").change(function() {
	
		var formData = new FormData();
		formData.append("file", document.getElementById("fileToUpload").files[0]);
		formData.append("type", "uploadImage");
    	$.ajax({
    		type:"POST", 
    		url:"support/functions.php", 
    		data:formData,
    		processData: false, 
    		contentType: false, 
    		success: function(response) {
    			if (response != "wrfo") {
    				$("#profilePic").css("background-image",'url(profilePics/profilePic_' + response + '.jpg?random=' + new Date().getTime() + ')');
    			} else {
    				alert("You can only upload .png or .jpg files for your profile image!");
    			}
    		},
    		error: function(response) {
    			alert("There was an error in uploading your file.");
    		}
    	});
	});
	
	$("#fileToUpload").click();
}

function setPrivacy(privacy) {
	
	$.ajax({
		type:"POST",
		url:"support/functions.php",
		data: {"type":"setprivacy", "privacy":privacy},
		success : function(response) {
			
		}
	});

}