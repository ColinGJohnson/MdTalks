// reset the input fields in upload form
function formReset() {
    document.getElementById("myForm").reset();
}

//change to visibility of ID
function changeVisibility(divID) {
    var element = document.getElementById(divID);
	
	// if element exists, it is considered true
	if(element) {
		element.className = (element.className == "hidden") ? "unhidden" : "hidden";
	}//if
}//changeVisibility

//change to visibility of ID
function unhideTwo(divID1, divID2) {
    changeVisibility(divID1);
	changeVisibility(divID2);
}//changeVisibility