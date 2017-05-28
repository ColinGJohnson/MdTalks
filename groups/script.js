var tooltip;
function init(){
	tooltip = document.getElementById("tooltip");
}

function addGroup(groupid, userid) {
	
	if (confirm("Are you sure you want to join this group?") == true) {
		$.ajax({
    		type:"POST", 
    		url:"groupfunctions.php", 
    		data:{"type":"asktojoingroup","groupid":groupid},
    		success: function(response) {
				alert("Your request has been sent to the group moderators.");
    		}
    	});
	}
}

function previewGroup(groupid, event){
	event.stopPropagation();

	window.location = "http://142.31.53.22/~mdtalks/groups/grouppage/index.php?groupid="+groupid;
}