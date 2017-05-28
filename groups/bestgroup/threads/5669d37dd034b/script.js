function addToThread(id){
	$.ajax({
		type: "POST",
		url: "add.php",
		data: $(id).serialize(),
		success: function(response){
			document.getElementById('postContainer').innerHTML = response;
			$("#postContainer").animate({ scrollBottom: $('#postContainer')[0].scrollHeight}, 1000);
		}
	});
}
