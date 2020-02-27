var LoadCabInfo = false;

function gebi(id){
	return document.getElementById(id)
}

function str_replace(search, replace, subject) {
	return subject.split(search).join(replace);
}

function focus_bg(id){
	gebi(id).style.background = "#FFDBDB";
	gebi(id).focus();
	return false;
}


function Infotack(id) {
	if(!LoadCabInfo) {
		$.ajax({
			type: "GET", cache: false, url: "/view_task/task_add_bl.php?rid="+id, dataType: "html", 
			beforeSend: function() {LoadCabInfo = true; $("#loading").slideToggle(); $("input, textarea, select").blur(); }, 
			statusCode: {404: function() {LoadCabInfo = false; $("#loading").slideToggle(); ModalStart("Ошибка 404", '<span class="msg-error" style="margin:0;">Возможно эта страница была удалена, переименована, или она временно недоступна.</span>', 600, false, false, 5);}},
			success: function(data) { 
				LoadCabInfo = false; $("#loading").slideToggle();
				ModalStart("Добавить пользователя в Black List", data, 550, true, true, 0);
			}
		});
	}
	return false;
}

function pass_oper(id) {
	if(!LoadCabInfo) {
		$.ajax({
			type: "GET", cache: false, url: "/pass_oper.php?id="+id, dataType: "html", 
			beforeSend: function() {LoadCabInfo = true; $("#loading").slideToggle(); $("input, textarea, select").blur(); }, 
			statusCode: {404: function() {LoadCabInfo = false; $("#loading").slideToggle(); ModalStart("Ошибка 404", '<span class="msg-error" style="margin:0;">Возможно эта страница была удалена, переименована, или она временно недоступна.</span>', 600, false, false, 5);}},
			success: function(data) { 
				LoadCabInfo = false; $("#loading").slideToggle();
				ModalStart("Получение пароля для операций", data, 550, true, true, 0);
			}
		});
	}
	return false;
}

function info_bon() {
	if(!LoadCabInfo) {
		$.ajax({
			type: "GET", cache: false, url: "/ref_bonus_info.php", dataType: "html", 
			beforeSend: function() {LoadCabInfo = true; $("#loading").slideToggle(); $("input, textarea, select").blur(); }, 
			statusCode: {404: function() {LoadCabInfo = false; $("#loading").slideToggle(); ModalStart("Ошибка 404", '<span class="msg-error" style="margin:0;">Возможно эта страница была удалена, переименована, или она временно недоступна.</span>', 600, false, false, 5);}},
			success: function(data) { 
				LoadCabInfo = false; $("#loading").slideToggle();
				ModalStart("Бонус за привлечение новых рефералов", data, 550, true, true, 0);
			}
		});
	}
	return false;
}
