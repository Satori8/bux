function gebi(id){
	return document.getElementById(id)
}

function saveform() {
	if (gebi("forum_b_p").value == ''){
		alert("Ошибка! Укажите причину блокировки!");
		return false;
	}
	if (gebi("forum_b_e").value == ''){
		alert("Ошибка! Укажите длительность блокировки!");
		return false;
	}
	if (gebi("forum_b_e").value < 1){
		alert("Ошибка! Длительность блокировки не может быть меньше 1 дня!");
		return false;
	}

	if (!confirm("Вы уверены, что хотите забанить этого пользователя на "+gebi("forum_b_e").value+" суток?")) return false;

	return true;
}

function showmod(n) {
	gebi('modpost'+n).style.display = 'none';
}


function add_ban(idt,idu) {
	$.jqpooop({
		Id:"addban",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"/forum/form_add_ban.php?th="+idt+"&add_ban="+idu,	
		Headmsg:"Блокировка пользователя"
	});
}

function del_ban(id) {
	if (confirm("Вы уверены, что хотите снять блокировку с этого пользователя?"))

	$.jqpooop({
		Id:"delban",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"/forum/del_ban.php?add_ban="+id,
		Headmsg:""
	});
}

function close_theme(id) {
	if (confirm("Вы уверены, что хотите закрыть тему?"))

	$.jqpooop({
		Id:"closetheme",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"/forum/close_theme.php?th="+id,
		Headmsg:""
	});
}

function open_theme(id) {
	if (confirm("Вы уверены, что хотите открыть тему?"))

	$.jqpooop({
		Id:"opentheme",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"/forum/open_theme.php?th="+id,
		Headmsg:""
	});
}

function del_theme(id) {
	if (confirm("Вы уверены, что хотите удалить тему?"))

	$.jqpooop({
		Id:"deltheme",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"/forum/del_theme.php?th="+id,
		Headmsg:""
	});
}


function moder_post(id) {

	$.jqpooop({
		Id:"moderpost",
		Position:"absolute",
		Width:'400',
		Height:'450',
		Ajax:"/forum/moder_post.php?post="+id,
		Headmsg:""
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
		 Headmsg:""
	},

$.extend({
	'load':function(urlajax){
		
		$.ajax({
			'url':urlajax,
			'cache': false,
			'dataType': 'php',
			'type':'GET',
			success: function(msg)
			{
				if(opc.Headmsg!=false) {
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
				}else{
					$(opc.Id + msg).appendTo(document.body);
				}
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