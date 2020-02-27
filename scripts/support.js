$("#mail").focus();

function send_tp_e()
{
	var er=0;
	if ($.trim($('#mail').val()) == ''){ var er=1; }
	if ($.trim($('#title').val()) == ''){ var er=1; }
	if ($.trim($('#text_mess').val()) == ''){ var er=1; }

	var tm;
    function hidemsg()
    {
        $('#echo_tp').fadeOut('slow');
        if (tm)
            clearTimeout(tm);
    }
	
	if(er != 1)
	{
        $.ajax(
        {
            url: 'ajax/ajax_support.php',  type: 'POST',
            data:
            {
                'sf': 'send_tp_e',
				'mail': $('#mail').val(),
				'title': $('#title').val(),
                'text': $('#text_mess').val()
            }, dataType: 'json',
            success: function (data)
            {
				if(data == 0)
				{
					$("#echo_tp_ok").html("<span class='msgbox-success'>Сообщение отправлено, вам ответят в ближайшее время</span>");
			        $('#text_mess').val(''); // очищаем поле ввода
				}
				if(data == 1){ $("#echo_tp").html("<span class='msgbox-error'>Заполнены не все поля</span>"); }
				if(data == 2){ $("#echo_tp").html("<span class='msgbox-error'>Не верно указан email</span>"); }
            }
         });
     }else{
	 
		$("#echo_tp").html("<span class='msgbox-error'>Заполнены не все поля</span>");
		$("#echo_tp").attr("style", "");
        tm = setTimeout(function() {  hidemsg()   }, 1000);
	}
}

function send_tp_a()
{
	var er=0;
    if ($.trim($('#type').val()) == ''){ var er=1; }
	if ($.trim($('#title').val()) == ''){ var er=1; }
	if ($.trim($('#text_mess').val()) == ''){ var er=1; }

	var tm;
    function hidemsg()
    {
        $('#echo_tp').fadeOut('slow');
        if (tm)
            clearTimeout(tm);
    }
	
	if(er != 1)
	{
        $.ajax(
        {
            url: 'ajax/ajax_support.php',  type: 'POST',
            data:
            {
                'sf': 'send_tp_a',
				'type': $('#type').val(),
				'title': $('#title').val(),
                'text': $('#text_mess').val()
            }, dataType: 'json',
            success: function (data)
            {
				if(data == 0)
				{
					$("#echo_tp_ok").html("<span class='msgbox-success'>Сообщение отправлено, вам ответят в ближайшее время</span>");
			        $('#text_mess').val(''); // очищаем поле ввода
				}
				if(data == 1){ $("#echo_tp").html("<span class='msgbox-error'>Заполнены не все поля</span>"); }
				if(data == 2){ $("#echo_tp").html("<span class='msgbox-error'>Не верно указан email</span>"); }
            }
         });
     }else{
	 
		$("#echo_tp").html("<span class='msgbox-error'>Заполнены не все поля</span>");
		$("#echo_tp").attr("style", "");
        tm = setTimeout(function() {  hidemsg()   }, 1000);
	}
}

function tick_send(id)
{
	if ($.trim($('#text_mess').val()) != '')
	{
		$.ajax({
			url: 'ajax/ajax_support.php',  type: 'POST',
			data:{'sf': 'tick_send', 'text_mess' : $('#text_mess').val(), 'id': id }, dataType: 'json',
			success: function (data)
			{
					if(data == 0){$("#tick").html("<span class='msgbox-success'>Ответ успешно отправлен</span>");}
			}
		});
	}
}

function clouse_tick(id)
{
    $.ajax({
		url: 'ajax/ajax_support.php',  type: 'POST',
		data:{'sf': 'clouse_tick', 'id': id }, dataType: 'json',
        success: function (data)
        {
				if(data == 0){$("#tick").html("<span class='msgbox-success'>Тикет успешно закрыт</span>");}
        }
	});
     
}

function go_answer_op() 
{	 
	$("#go_answer_ok").fadeIn("slow");
	document.getElementById('go_answer_op').innerHTML = "";
}

function ok_type(frm)
{
	if(frm.type.value > 0 ) 
	{ 
		document.getElementById('echo_type').style.display = ''; 
		document.getElementById('go_tp_ok').style.display = ''; 
		document.getElementById('go_tp_ok2').style.display = 'none';
		document.getElementById('ok_type').style.display = 'none';
	}
	if(frm.type.value >= '-1' ) 
	{ 
		document.getElementById('echo_type').style.display = ''; 
		document.getElementById('go_tp_ok').style.display = ''; 
		document.getElementById('go_tp_ok2').style.display = 'none';
		document.getElementById('ok_type').style.display = 'none';
	}
	if(frm.type.value == '-1') 
	{
		document.getElementById('echo_type').style.display = 'none'; 
		document.getElementById('go_tp_ok').style.display = 'none'; 
		
		
	
		document.getElementById('an_0').style.display = 'none';
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	
	if(frm.type.value == 0) 
	{ 
		document.getElementById('an_0').style.display = ''; 
		document.getElementById('go_tp_ok2').style.display = '';
		document.getElementById('echo_type').style.display = 'none';
		document.getElementById('go_tp_ok').style.display = 'none';
		
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';		
	}
	if(frm.type.value == 1) 
	{ 
		document.getElementById('an_1').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 2) 
	{ 
		document.getElementById('an_2').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 3) 
	{ 
		document.getElementById('an_3').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 4) 
	{ 
		document.getElementById('an_4').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 5) 
	{ 
		document.getElementById('an_5').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 6) 
	{ 
		document.getElementById('an_6').style.display = '';		
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 7) 
	{ 	
		document.getElementById('an_7').style.display = '';
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 8) 
	{ 
		document.getElementById('an_8').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 9) 
	{ 
		document.getElementById('an_9').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_10').style.display = 'none';
	}
	if(frm.type.value == 10)
	{
		document.getElementById('an_10').style.display = ''; 
		
		document.getElementById('an_0').style.display = 'none'; 
		document.getElementById('an_1').style.display = 'none';
		document.getElementById('an_2').style.display = 'none';
		document.getElementById('an_3').style.display = 'none';
		document.getElementById('an_4').style.display = 'none';
		document.getElementById('an_5').style.display = 'none';
		document.getElementById('an_6').style.display = 'none';
		document.getElementById('an_7').style.display = 'none';
		document.getElementById('an_8').style.display = 'none';
		document.getElementById('an_9').style.display = 'none';
	}
	
}
function go_tp_ok(frm)
{
    $("#title").focus();

	document.getElementById('ok_type').style.display = '';
	document.getElementById('go_tp_ok').style.display = 'none';
	document.getElementById('go_tp_ok2').style.display = 'none';
	
	document.getElementById('echo_type').style.display = 'none';
	
	document.getElementById('an_0').style.display = 'none'; 
	document.getElementById('an_1').style.display = 'none';
	document.getElementById('an_2').style.display = 'none';
	document.getElementById('an_3').style.display = 'none';
	document.getElementById('an_4').style.display = 'none';
	document.getElementById('an_5').style.display = 'none';
	document.getElementById('an_6').style.display = 'none';
	document.getElementById('an_7').style.display = 'none';
	document.getElementById('an_8').style.display = 'none';
	document.getElementById('an_9').style.display = 'none';
	document.getElementById('an_10').style.display = 'none';
}


(function($){
	$.fn.extend({limit:function(limit,element,error){
	var interval,f;
	var self=$(this);
	$(this).focus(function(){interval=window.setInterval(substring,100)});
	$(this).blur(function(){clearInterval(interval);substring()});
	substringFunction="function substring(){ var val = $(self).val();var length = val.length;if(length > limit){$(self).val($(self).val().substring(0,limit));$(error).addClass('errored');}";
	if(typeof element!='undefined')substringFunction+="if($(element).html() != limit-length){$(element).html((limit-length<=0)?'0':limit-length);}";substringFunction+="}";
	eval(substringFunction);
	substring()
	}})
})
(jQuery);
$(document).ready(function() {$('#text_mess').limit('1000','#chrLeft1','#tt1');});


//site mail

function appendcommtag_mail(smile, id)
{  
	var text2 = " ";
	var el=document.getElementById("ask_desc"+id);
	el.focus();
	if (el.selectionStart==null){
	    var rng=document.selection.createRange();
	    rng.text=smile+rng.text+text2
	}
	else{
	    el.value=el.value.substring(0,el.selectionStart)+
	    text2+smile+
	    el.value.substring(el.selectionStart,el.selectionEnd)+
	    text2+
	    el.value.substring(el.selectionEnd);
	}
}

function AppendSmile_mail(asmile, id) { appendcommtag_mail(asmile, id); }

function nuk_ref_mail(nik){ $('#mailto').val(nik); }
function bb_mail(text1, text2, id)
{
	var el=document.getElementById("ask_desc"+id);
	el.focus();
	if (el.selectionStart==null){
	    var rng=document.selection.createRange();
	    rng.text=text1+rng.text+text2
	}
	else{
	    el.value=el.value.substring(0,el.selectionStart)+
	    text1+
	    el.value.substring(el.selectionStart,el.selectionEnd)+
	    text2+
	    el.value.substring(el.selectionEnd);
	}
}
function AppendSmile(asmile, id) { bb_mail(asmile,'',id); }
function add_url(id)
{
	text_url = window.prompt("Укажите текст ссылки","");
	_url = window.prompt("Укажите ссылку","");
	if("Microsoft Internet Explorer" != navigator.appName)
	{
		var el=document.getElementById("ask_desc"+id);
		el.focus();
		if (el.selectionStart == ''){
			$('#ask_desc'+id).val('[url='+_url+']'+text_url+'[/url] ');
		}
		else{
			el.value=el.value.substring(0,el.selectionStart)+
			' [url='+_url+']'+text_url+'[/url] '+
			el.value.substring(el.selectionEnd);
		}
		
	}else{	$('#ask_desc'+id).val($('#ask_desc'+id).val()+ ' [url='+_url+']'+text_url+'[/url] ') }
}

function load_mail(id){ 
	$.ajax({ type: "POST", url: "ajax/ajax_mail.php", data: { 'sf' : 'load_mail', 'id' : id, 'fil_t' : $.trim($('#fil_t').val()) }, success: function(data){ $('#read_mail'+id).html(data); } }); 	
}

function open_m_a(id) 
{    
	if (document.getElementById('read_mail'+id).style.display == 'none') { 
		document.getElementById('read_mail'+id).style.display = '';
		load_mail(id);
		$.ajax({ type: "POST",url: "ajax/ajax_mail.php", data: { 'sf' : 'st_read', 'id_m' : id } });
		$("#style_lm"+id).removeClass('mail-sample');
		$("#style_lm"+id).addClass('mail-sample-active');
	}else{ document.getElementById('read_mail'+id).style.display = 'none'; }
}
function open_m(id) 
{    
	if (document.getElementById('read_mail'+id).style.display == 'none') { 
		document.getElementById('read_mail'+id).style.display = '';
		load_mail(id);
	}else{ document.getElementById('read_mail'+id).style.display = 'none'; }
}
function answer_m(id)
{
	$("#answer_m"+id).fadeIn("slow");
	$("#bb_mail"+id).fadeIn("slow");
	$("#but_an"+id).hide("slow");
    document.getElementById("answer_m"+id).innerHTML = 
		'<center><textarea style="width: 95%; height: 100px;" name="ask_desc'+id+'" id="ask_desc'+id+'" ></textarea></center>'+
		'<div style="height: 30px; line-height: 2.5;  text-align: center;	cursor: pointer;">'+
		'<span class="sf_button"  style="padding: 2px;" onClick="send_m('+id+');">Отправить письмо</span>'+
		'</div>'+
		'<div style="border-bottom:  1px solid #ddd;	padding: 2px;	margin: 3px 3px;	display: block;"></div>'
		;
}
function simp_mail(id) 
{ 
	$.ajax({
    type: "POST", url: "ajax/ajax_mail.php", data: { 'sf' : 'simp_mail', 'id_m' : id }, success: function(data) { $("#simp_mail"+id).fadeIn("slow"); $("#simp_mail_e"+id).html(data); }
	});
}
function send_new_mail()
{
	var er=0;
    if ($.trim($('#mailto').val()) == ''){ var er=1; }
	if ($.trim($('#title').val()) == ''){ var er=1; }
	if ($.trim($('#text_mess').val()) == ''){ var er=1; } 

	var tm;
    function slow()
    {
        $('#echo_nm').fadeOut('slow');
        if (tm)
            clearTimeout(tm);
    }

	if(er != 1)
	{	
        $.ajax({
            url: 'ajax/ajax_mail.php',  type: 'POST',
            data:{'sf': 'send_new_mail', 'mailto': $('#mailto').val(), 'title': $('#title').val(), 'text_mess': $('#text_mess').val() }, dataType: 'json',
            success: function (data)
            {
				if(data == 0){ $("#echo_nm_ok").html("<span class='msgbox-success'>Сообщение отправлено</span>"); }
				else if(data == 1){ $("#echo_nm").html("<span class='msgbox-error'>Заполнены не все поля</span>"); }
				else if(data == 2){ $("#echo_nm").html("<span class='msgbox-error'>Пользователя с именем "+$('#mailto').val()+" не существует!</span>"); }
				else if(data == 3){ $("#echo_nm").html("<span class='msgbox-error'>Нельзя отправлять сообщения самому себе!</span>"); }
				else if(data == 4){ $("#echo_nm").html("<span class='msgbox-error'>Нельзя отправлять так часто!</span>"); }
				else if(data == 5){ $("#echo_nm").html("<span class='msgbox-error'>Для отправки вам нужно минимум 5 баллов рейтинга...</span>"); }
				else if(data == 6){ $("#echo_nm").html("<span class='msgbox-error'>Вам заблокирована возможность отправлять сообщения...</span>"); }
				else{ $("#echo_nm").html("<span class='msgbox-error'>ERROR</span>"); }
            }
         });
     }else{
	 
		$("#echo_nm").html("<span class='msgbox-error'>Заполнены не все поля</span>");
		$("#echo_nm").attr("style", "");
        tm = setTimeout(function() {  slow()   }, 1000);
	}
}
			
function send_m(id)
{
	var ask_desc=$('#ask_desc'+id).val();

    var tm;
    function slow()
    {
        $('#conm'+id).fadeOut('slow');
        if (tm)clearTimeout(tm);
    }

    if (ask_desc == '') {
        $("#conm"+id).html("<span style='color:#e32636;'>Вы не ввели текст сообщения</span>");
		$("#conm"+id).attr("style", "");
        tm = setTimeout(function() {
            slow()
        }, 1000);

    }else{

		$.ajax({
			type: "POST",url: "ajax/ajax_mail.php", data: { 'sf' : 'answer_m', 'id_m' : id, 'ask_desc' : ask_desc },    
			success: function(data) {
			
				if(data == 0)
				{
					$("#conm"+id).html("<span style='color:#e32636;'>Нельзя отправлять так часто</span>");
					$("#conm"+id).attr("style", "");
					tm = setTimeout(function() { slow() }, 1000);
				}
				else if(data == 1)
				{
					$("#conm"+id).html("<span style='color:#e32636;'>Вы не ввели текст сообщения</span>");
					$("#conm"+id).attr("style", "");
					tm = setTimeout(function() { slow() }, 1000);
				}
				else if(data == 2)
				{
					$("#conm"+id).html("<span style='color:#e32636;'>Ошибка</span>");
					$("#conm"+id).attr("style", "");
					tm = setTimeout(function() { slow() }, 1000);
				}
				else if(data == 3)
				{
					$("#conm"+id).html("<span style='color:#5d8aa8;'>Сообщение отправлено</span>");
					$("#conm"+id).attr("style", "");
					$('#ask_desc'+id).val('');
				}
				else if(data == 5){ $("#conm"+id).html("<span class='msgbox-error'>Для отправки вам нужно минимум 5 баллов рейтинга...</span>"); }
				else{ $("#conm"+id).html("<span class='msgbox-error'>ERROR</span>"); }
			}
		});
				
		$("#conm").html("<span class='loading' title='Подождите пожалуйста...'></span>");
		$("#conm").attr("style", "");
		return false;
	}
}

function setCookie (name, value, expires, path, domain, secure) 
{
	document.cookie = name + "=" + escape(value) +
	((expires) ? "; expires=" + expires : "") +
	((path) ? "; path=" + path : "") +
	((domain) ? "; domain=" + domain : "") +
	((secure) ? "; secure" : "");
}
function spammail(id) 
{ 
	$.ajax({
    type: "POST",   url: "ajax/ajax_mail.php",   data: "sf=spammail&id="+id,         
    success: function(data) {  $("#load_spam"+id).html(data); }
	});
}
function maildell(id) 
{ 
	$('#maildelll'+id).hide();
	$.ajax({ type: "POST", url: "ajax/ajax_mail.php", data: { 'sf' : 'maildell', 'id' : id } });
}
function ClearForm()
{
	var stitle = "";
	document.forms['mailform'].ask_title.value = stitle;
	document.forms['mailform'].ask_desc.value = '';
	document.forms['mailform'].ask_desc.style.height = '100px';
	document.forms['mailform'].scount.value = 'Осталось 1000 символов';
}
function descchange(elem)
{
	if (elem.value.length > 1000) {	elem.value = elem.value.substr(0,1000);}
	document.forms['mailform'].scount.value = 'Осталось '+(1000-elem.value.length)+' символов';
}

function gofilter(mode)
{
	setCookie("filtermail",mode,"Mon, 01-Jan-2020 00:00:00 GMT");
	top.location = 'mail';
	return false;
}
function gokorr(mid){top.location = 'mail?new_mail&name='+mid;return false;}
