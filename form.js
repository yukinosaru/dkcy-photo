function validate(){
	if (document.admin.edit){
		if (document.admin.edit.value=="collection"){
			if(document.admin.ec_name.value.length<1){
				alert("Please enter a collection name.");
				return false;
			}
			if(document.admin.ec_intro.value.length < 1){
				alert("Please enter an introduction.");
				return false;
			}
		}
		if (document.admin.edit.value=="image"){
			if(document.admin.ei_colid.value.length < 1){
				alert("Please select a collection to add this image to.");
				return false;
			}
			if(document.admin.ei_imgname.value.length < 1){
				alert("Please enter an image name.");
				return false;
			}
			if(document.admin.ei_desc.value.length < 1){
				alert("Please enter an image description.");
				return false;
			}
		}
	} else {
		if(document.getElementById("coll").checked){
			if(document.admin.colname.value.length < 1){
				alert("Please enter a collection name.");
				return false;
			}
			if(document.admin.intro.value.length < 1){
				alert("Please enter an introduction.");
				return false;
			}
		}
		if(document.getElementById("img").checked){
			if(document.admin.colid.value.length < 1){
				alert("Please select a collection to add this image to.");
				return false;
			}
			if(document.admin.imgname.value.length < 1){
				alert("Please enter an image name.");
				return false;
			}
			if(document.admin.desc.value.length < 1){
				alert("Please enter an image description.");
				return false;
			}
			if(document.admin.image.value.length < 1){
				alert("Please enter the location of the image file.");
				return false;
			}
		}
	}
	return true;
}

function verify(name){
	message = "Are you sure you want to delete '" + name + "' ?";
	var response = window.confirm(message);
	return response;
}

function showHidden(){
	var img=document.getElementById("img").checked;
	var coll=document.getElementById("coll").checked;

	var addImage=document.getElementById("addImage").style;
	var addColl=document.getElementById("addColl").style;

	if(img != ""){
		addImage.display = "block";
		addColl.display = "none";
	}

	else if(coll != ""){
		addColl.display = "block";
		addImage.display = "none";
	}
}