function goToUrl(path) {

        window.location = "http://142.31.53.22/~mdtalks/" + path;

}

function changeDesc(groupid) {

        var button = $("#changedesc");

        if (button.html() === "Change Description") {
                var textarea = $('<textarea id="groupdesc" />');

                button.html("Submit Description");

                textarea.val($('#groupdesc').html());

                $('#groupdesc').replaceWith(textarea);

                document.getElementById("groupdesc").focus();

        } else {

                var div = $('<div id="groupdesc" />');

                button.html("Change Description");
				
				$.ajax({
					type: "POST",
					url: "add.php",
					data: {"groupid":groupid, "groupdesc":$("#groupdesc").val()},
					success: function(response) {}
				});

                div.html($('#groupdesc').val());

                $('#groupdesc').replaceWith(div);
        }

}

function showAddForm() {
        var form = document.getElementById("addannounce");

        document.getElementById("announce").style.display = "none";
        document.getElementById("deleteannounce").style.display = "none";
        document.getElementById("announcements").style.height = "630px";

        form.style.bottom = "5%";
        form.style.display = "block";

}

function cancelAddAnnounce() {

        document.getElementById("addannounce").style.display = "none";
        document.getElementById("addannounce").style.bottom = "5%";

        document.getElementById("announce").style.display = "block";
        document.getElementById("deleteannounce").style.display = "block";
        document.getElementById("announcements").style.height = "430px";

}

function allowAnnounceDelete() {

	if ($("#deleteannounce").html() !== "Cancel Deletion") {
		$("#deleteannounce").html("Cancel Deletion");

		$(".deletetextanno").each(function(index, element) {
			$(element).css("display", "block");
		});
		
		injectStyles(".announcement:hover { background-color: rgba(0,0,0,0); }");
		
	} else {
		$("#deleteannounce").html("Delete Announcement");

		$(".deletetextanno").each(function(index, element) {
			$(element).css("display", "none");
		});
		
		injectStyles(".announcement:hover { background-color: rgba(200, 230, 255, 0.5); }");
	}
	
}

function deleteAnno(index, groupid) {

	$.ajax({
		type:"POST",
		url:"delete.php",
		data: {"index": index, "type":"anno", "groupid":groupid},
		success : function(response) {
			document.getElementById("announcecontain").innerHTML = response;
		}});

}

function showAddThreadForm() {

        var form = document.getElementById("addthread");

        document.getElementById("buttonaddthread").style.display = "none";
        document.getElementById("threads").style.height = "650px";
		
		var deletethread = document.getElementById("deletethread");
		if (deletethread != null) {
			deletethread.style.display = "none";
		}
        form.style.bottom = "5%";
        form.style.display = "block";

}

function cancelAddThread() {

        document.getElementById("addthread").style.display = "none";
        document.getElementById("addthread").style.bottom = "5%";

        document.getElementById("buttonaddthread").style.display = "block";
        document.getElementById("threads").style.height = "500px";
		document.getElementById("deletethread").style.display = "block";

}

function allowThreadDelete() {

	
	
	if ($("#deletethread").html() !== "Cancel Deletion") {
		$("#deletethread").html("Cancel Deletion");

		$(".deletetextthread").each(function(index, element) {
		$(element).css("display", "block");
	});
	
		injectStyles(".thread:hover { background-color: rgba(0,0,0,0); }");
		
	} else {
		$("#deletethread").html("Delete Discussion");

		$(".deletetextthread").each(function(index, element) {
		$(element).css("display", "none");
	});
		injectStyles(".thread:hover { background-color: rgba(200, 230, 255, 0.5); }");
	
	
	}
	
}

function deleteThread(index, id, groupid) {
	$.ajax({
		type:"POST",
		url:"delete.php",
		data: {"groupid":groupid, "index": index, "id":id, "type":"thre"},
		success : function(response) {
			document.getElementById("threadcontain").innerHTML = response;
		}});

}

function addToJson(id) {


    $.ajax({
    type: "POST",
    url: "add.php",
    data: $(id).serialize(),
    success: function(response) {
      if (response.substring(0, 4) === "thre") {
        document.getElementById("threadcontain").innerHTML = response.replace("thre", "");
      } else {
        document.getElementById("announcecontain").innerHTML = response.replace("anno", "");
      }
      
    }
        });

}

function prepareFile() {

        var file = document.getElementById('logoupload').files[0];
        var reader = new FileReader();
        reader.asText(file, 'UTF-8');
        reader.onload = uploadImage;

}

function goToThreadPage(idstring, groupid) {

	$(".deletetextthread").each(function(index, element) {
	
		if ($(this).css("display") === "none") {
			window.location = "http://142.31.53.22/~mdtalks/groups/threadpage/?groupid=" + groupid + "&threadid=" + idstring;
		}
	});
}

function findLogo(groupid) {
	
	$("#logoupload").change(function() {
	
		var formData = new FormData();
		formData.append("file", document.getElementById("logoupload").files[0]);
		formData.append("groupid", groupid);
    	$.ajax({
    		type:"POST", 
    		url:"add.php", 
    		data:formData,
    		processData: false, 
    		contentType: false, 
    		success: function(response) {
    			$("#logo").css("background-image",'url(../grouplist/' + groupid + '/logo.jpg?random=' + new Date().getTime() + ")");
    		}
    	});
	});
	
	$("#logoupload").click();
}

function injectStyles(rule) {
  var div = $("<div />", {
    html: '&shy;<style>' + rule + '</style>'
  }).appendTo("body");    
}

function toggleSettingsPanel() {

	if (document.getElementById('settingspanel').style.display === "none") {
		document.getElementById('settingspanel').style.display = "block";
	} else {
		document.getElementById('settingspanel').style.display = "none";
	}
	
}

function addAdmin(groupid) {

	var adminemail = prompt("Enter the new admin's email:", "");

	$.ajax({
    	type:"POST", 
    	url:"../groupfunctions.php", 
    	data:{"admintoadd":adminemail, "groupid":groupid},
    	success: function(response) {
    		
    	}	
    });

}

function addMember(groupid) {

	var memberemail = prompt("Enter the new member's email:", "");

	$.ajax({
    	type:"POST", 
    	url:"../groupfunctions.php", 
    	data:{"membertoadd":memberemail, "groupid":groupid},
    	success: function(response) {
    		alert(response);
    	}	
    });

}
