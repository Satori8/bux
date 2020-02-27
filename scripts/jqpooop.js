function gebi(id){
	return document.getElementById(id)
}

function saveform(){
	if (gebi("ocenka").value == "no"){
		alert("Ошибка! Не выбрана оценка задания!");
		return false;
	}
	if (gebi("coment").value == ""){
		alert("Ошибка! Не указан комментарий!");
		return false;
	}
	return true;
}

function saveform_wall(){
	if (gebi("coment").value == ""){
		alert("Ошибка! Не указан комментарий!");
		return false;
	}
	return true;
}

function saveform_wall_ot(){
	if (gebi("otvet").value == ""){
		alert("Ошибка! Не указан комментарий!");
		return false;
	}
	return true;
}
	
function setsmile(sm){
	if (gebi("coment")){
		gebi("coment").value += ":"+sm+":";
		gebi("coment").focus();
	}
	if (gebi("otvet")){
		gebi("otvet").value += ":"+sm+":";
		gebi("otvet").focus();
	}
}

function viewaddsmile(){
	if (gebi("spanaddsmile1").style.display == "none"){
		gebi("spanaddsmile1").style.display = "";
		gebi("spanaddsmile2").style.display = "none";
		gebi("allsmile").style.display = "none";
	} else {
		gebi("spanaddsmile1").style.display = "none";
		gebi("spanaddsmile2").style.display = "";
		gebi("allsmile").style.display = "";
	}
}


function pass_oper(id){
	$.jqpooop({
		Id:"passoper",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"pass_oper.php?id="+id,	
		Headmsg:"Получение пароля для операций"
	});
}


function add_coment(id){

	$.jqpooop({
			Id:"addcoment",
			Position:"absolute",
			Width:'400',
			Height:'450',
			Ajax:"/view_task/task_add_coment.php?rid="+id,	
			Headmsg:"Добавить свой комментарий о задании"
			});

}

function add_bl(id){

	$.jqpooop({
			Id:"addbl",
			Position:"absolute",
			Width:'400',
			Height:'450',
			Ajax:"/view_task/task_add_bl.php?rid="+id,	
			Headmsg:"Добавить пользователя в Black List"
			});

}

function wall_add_coment(id){

	$.jqpooop({
			Id:"walladdcoment",
			Position:"absolute",
			Width:'500',
			Height:'450',
			Ajax:"wall_add_comment.php?uid="+id,	
			Headmsg:"Добавить комментарий о пользователе"
			});

}



function wall_add_otv(id){

	$.jqpooop({
			Id:"walladdotv",
			Position:"absolute",
			Width:'500',
			Height:'450',
			Ajax:"wall_add_otv.php?rid="+id,
			Headmsg:"Ответить на комментарий"
			});

}

function info_bon(){

	$.jqpooop({
			Id:"infobon",
			Position:"absolute",
			Width:'500',
			Height:'450',
			Ajax:"ref_bonus_info.php",	
			Headmsg:"Бонус за привлечение новых рефералов"
			});

}

function news_add_coment(id){

	$.jqpooop({
			Id:"news_add_coment",
			Position:"absolute",
			Width:'500',
			Height:'450',
			Ajax:"news_add_coment.php?id="+id,	
			Headmsg:"Добавить комментарий к новости"
			});

}

function news_add_otv(id){

	$.jqpooop({
			Id:"newsaddotv",
			Position:"absolute",
			Width:'500',
			Height:'450',
			Ajax:"news_add_otv.php?id="+id,
			Headmsg:"Ответить на комментарий"
			});

}

function reactref(id){

	$.jqpooop({
			Id:"reactref",
			Position:"absolute",
			Width:'400',
			Height:'450',
			Ajax:"reactref.php?uid="+id,	
			Headmsg:"Разбудить реферала #"+id
			});

}

function reactref_all(){

	$.jqpooop({
			Id:"reactrefall",
			Position:"absolute",
			Width:'400',
			Height:'450',
			Ajax:"reactref_all.php",	
			Headmsg:"Разбудить рефералов"
			});

}

function info_cab(){
	$.jqpooop({
			Id:"infobon",
			Position:"absolute",
			Width:'600',
			Height:'450',
			Ajax:"/cabinet/cab_info.php",	
			Headmsg:"Накопительные скидки для рекламодателей"
			});

}

function info_kopilka(){
	$.jqpooop({
			Id:"infobon",
			Position:"absolute",
			Width:'600',
			Height:'450',
			Ajax:"/kopilka/kopilka_info.php",	
			Headmsg:"Копилка нашего проекта"
			});

}


(function($) {

$.fn.jqpooop = function(){


	
    this.each( function(){
			alert(this);
    });
}

$.fn.jqpooop.options_default = {
		 Unique:true,
		 Top:'0%',
		 Left:'0',
		 Mensaje:"Ninguno",
		 Position:"absolute",
		 Ajax:" ",
		 Id:"Id",
		 Center:"true",
		 Width:'',
		 Height:'',
		 Headmsg:"Alert message"
	},

$.extend({
	'load':function(urlajax){
		
		$.ajax({
			type: "GET", 
			url: urlajax, 
			cache: false,
			success: function(msg) {
				$('<div id="'+ opc.Id +'" class="popup"><h6>'+opc.Headmsg+'</h6><button id="botonpop'+ opc.Id +'" onclick="$(this).parent().remove()"><img src="img/close.gif" align="absmiddle" alt="" title="Закрыть" style="padding:0; margin:0px;" /></button><p>'+ msg +'</p></div>').appendTo(document.body);
				var w = window.innerWidth ||document.documentElement.clientWidth || document.body.clientWidth;
				var h = window.innerHeight ||document.documentElement.clientHeight || document.body.clientHeight;
				w1 =(w/2) - (opc.Width/2); 
				h1 =(h/2) - (opc.Height/2);
				$('#'+opc.Id).css("top",h1+"px");
				$('#'+opc.Id).css("left",w1+"px");
				$('#'+opc.Id).css("display","");
				$('#'+opc.Id).css("z-index","1000");
				$('#'+opc.Id).css("position","fixed");
			}
		})
	},

'msg':function(msg){
	$('<div id="'+ opc.Id +'" class="popup"><h6>'+opc.Headmsg+'</h6><button id="botonpop'+ opc.Id +'" onclick="$(this).parent().remove()"><img src="img/close.gif" align="absmiddle" alt="" title="Закрыть" style="padding:0; margin:0px;" /></button><p>'+ msg +'</p></div>').appendTo(document.body);
	var w = window.innerWidth ||document.documentElement.clientWidth || document.body.clientWidth;
	var h = window.innerHeight ||document.documentElement.clientHeight || document.body.clientHeight;
	w1 =(w/2) - (opc.Width/2);
	h1 =(h/2) - (opc.Height/2);
	$('#'+opc.Id).css("top",h1+"px");
	$('#'+opc.Id).css("left",w1+"px");
	$('#'+opc.Id).css("display","");
	$('#'+opc.Id).css("z-index","1000");
	$('#'+opc.Id).css("position","absolute");
}	
	
	
});

$.jqpooop=function(options_user)
	{

		opc = $.extend( $.fn.jqpooop.options_default,options_user );

	
		if(opc.Unique==true && $('#'+opc.Id).length)
			{		
				return false;
			}
				
		if(opc.Ajax!=''){	
			this.load(opc.Ajax);	
				return false;
			}
		
		this.msg(opc.Mensaje);	
				return false;
	},

$.close=function(options_user)
	{
		opc = $.extend( $.fn.jqpooop.options_default,options_user );
		$('#'+opc.Id).remove();
	}

})(jQuery);