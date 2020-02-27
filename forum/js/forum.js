function FormT() {
	if ((document.forms["forumform"].title.value == '')) {
		alert('Вы не указали название темы!');
		document.forms["forumform"].title.focus();
		return false;
	}
	if ((document.forms["forumform"].opis.value == '')) {
		alert('Вы не указали краткое описание темы!');
		document.forms["forumform"].opis.focus();
		return false;
	}
	if ((document.forms["forumform"].post.value == '')) {
		alert('Вы не указали сообщение!');
		document.forms["forumform"].post.focus();
		return false;
	}
	document.forms["forumform"].submit();
	return true;
}

function FormC() {
	if ((document.forms["forumform"].post.value == '')) {
		alert('Вы не указали комментарий!');
		document.forms["forumform"].post.focus();
		return false;
	}
	document.forms["forumform"].submit();
	return true;
}

function gebi(id){
	return document.getElementById(id)
}

function delpost(idt, page, idp) {
	if (confirm("Вы уверены, что хотите удалить это сообщение?")) parent.location = '/forum.php?th='+idt+'&p='+page+'&del='+idp;
}

function showHide() {
	document.getElementById('blockyes').style.display = 'block';
	document.getElementById('blockno').style.display = 'none';

	blockyes.scrollIntoView(1000);
	window.scrollBy(0,-200);
}

function addtag(text1, text2) {
	if ((document.selection)){
		document.forumform.post.focus();
		document.forumform.document.selection.createRange().text = text1+document.forumform.document.selection.createRange().text+text2;
	} else if(document.forumform.post.selectionStart != undefined) {
		var element = document.forumform.post;
		var str = element.value;
		var start = element.selectionStart;
		var length = element.selectionEnd - element.selectionStart;
		element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
	} else document.forumform.post.value += text1+text2;
}

function copy_quote(){
	post_txt = '';
	if (window.getSelection) post_txt = window.getSelection().toString();
	else if (document.getSelection) post_txt = document.getSelection();
	else if (document.selection) post_txt = document.selection.createRange().text;
}

function add_quote(username) {
	document.getElementById('blockyes').style.display = 'block';
	document.getElementById('blockno').style.display = 'none';
	document.forumform.post.focus();

	if (post_txt == '') {
		addtag('[b]'+username+'[/b],\n','');
	} else if (post_txt != 'undefined') {
		addtag('[quote="'+username+'"]'+post_txt+'[/quote]\n','');
	} else {
		addtag('[b]'+username+'[/b],\n','');
	}
	blockyes.scrollIntoView(1000);
	window.scrollBy(0,-200);
}

function SetSmile(smile) {
	addtag(smile,'');
}