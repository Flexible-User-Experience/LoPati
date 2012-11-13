
function newsletter(event){
	var node=document.getElementById("form_newsletter");
	

	if (node.style.display == "" || node.style.display == "none" ){
		
		node.style.display="block";
	}else{
		
		node.style.display="none";
	}
	
}



