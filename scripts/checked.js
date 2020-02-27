function setChecked(obj){
	var check = document.getElementsByName("chkb[]");
	for (var i=0; i<check.length; i++) {check[i].checked = obj.checked;}
}