function goToUrl(path) {

        window.location = "http://142.31.53.22/~mdtalks/groups/bestgroup/" + path;

}

function changeDesc() {

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
					data: {"groupdesc":$("#groupdesc").val()},
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
        document.getElementById("announcecontain").style.height = "45%";

        form.style.bottom = "5%";
        form.style.display = "block";

}

function cancelAddAnnounce() {

        document.getElementById("addannounce").style.display = "none";
        document.getElementById("addannounce").style.bottom = "5%";

        document.getElementById("announce").style.display = "block";
        document.getElementById("deleteannounce").style.display = "block";
        document.getElementById("announcecontain").style.height = "60%";

}

function allowAnnounceDelete() {

	if ($("#deleteannounce").html() !== "Cancel Deletion") {
		$("#deleteannounce").html("Cancel Deletion");

		$(".deletetextanno").each(function(index, element) {
			$(element).css("display", "block");
		});
	} else {
		$("#deleteannounce").html("Delete Announcement");

		$(".deletetextanno").each(function(index, element) {
			$(element).css("display", "none");
		});
	}
	
}

function deleteAnno(index) {

	$.ajax({
		type:"POST",
		url:"delete.php",
		data: {"index": index, "type":"anno"},
		success : function(response) {
			document.getElementById("announcecontain").innerHTML = response;
		}});

}

function showAddThreadForm() {

        var form = document.getElementById("addthread");

        document.getElementById("buttonaddthread").style.display = "none";
        document.getElementById("threadcontain").style.height = "45%";

        form.style.bottom = "5%";
        form.style.display = "block";

}

function cancelAddThread() {

        document.getElementById("addthread").style.display = "none";
        document.getElementById("addthread").style.bottom = "5%";

        document.getElementById("buttonaddthread").style.display = "block";
        document.getElementById("threadcontain").style.height = "60%";

}

function allowThreadDelete() {

	
	
	if ($("#deletethread").html() !== "Cancel Deletion") {
		$("#deletethread").html("Cancel Deletion");

		$(".deletetextthread").each(function(index, element) {
		$(element).css("display", "block");
	});
	} else {
		$("#deletethread").html("Delete Thread");

		$(".deletetextthread").each(function(index, element) {
		$(element).css("display", "none");
	});
	}
	
}

function deleteThread(index, id) {
	$.ajax({
		type:"POST",
		url:"delete.php",
		data: {"index": index, "id":id, "type":"thre"},
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

function goToThreadPage(idstring, groupname) {

	$(".deletetextthread").each(function(index, element) {
	
		if ($(this).css("display") === "none") {
			window.location = "http://142.31.53.22/~mdtalks/groups/bestgroup/threads/" + idstring;
		}
	});
}
