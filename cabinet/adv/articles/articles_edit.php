<?php
if(!DEFINED("ARTICLES_EDIT")) {die ("Hacking attempt!");}
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование статьи № '.$id.'</h5>';

?>

<script type="text/javascript" language="JavaScript">

function SaveAds(id, type, op) {
	var title = $.trim($("#title").val());
	var url = $.trim($("#url").val());
	var desc_min = $.trim($("#desc_min").val());
	var desc_big = $.trim($("#desc_big").val());

	$.ajax({
		type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), 
		data: {'op':op, 'type':type, 'id': id, 'title':title, 'url':url, 'desc_min':desc_min, 'desc_big':desc_big }, 
		dataType: 'json',
		error: function() { $("#loading").slideToggle(); alert("Ошибка обработки данных AJAX!"); return false; }, 
		beforeSend: function() { $("#loading").slideToggle(); }, 
		success: function(data) {
			$("#loading").slideToggle();
			var result = data.result ? data.result : data;
			var message = data.message ? data.message : data;

			if(result == "OK") {
				alert("Изменения успешно сохранены!");
				setTimeout(function() {
					$("#info-msg-addmoney").hide();
					document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
				}, 1000);
				return false;
			} else {
				alert(message);
				return false;
			}
		}
	});
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		gebi("Save").click();
	}
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("Осталось символов: " +(count_s-elem.value.length));
}

$(document).ready(function(){
	descchange(1, desc_min, 1000);
	descchange(2, desc_big, 5000);
});
</script>


<?php

$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_assoc($sql);

	if($row["status"]==2) {
		echo '<span class="msg-error">Статья находится на модерации. Редактирование не доступно!</span>';
		include(DOC_ROOT."/footer.php");
		exit();
	}

}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
	include(DOC_ROOT."/footer.php");
	exit();
}

echo '<div id="newform" onkeypress="CtrlEnter(event);">';
	echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
	echo '<thead><tr><th align="center" colspan="2">Форма редактирования статьи</th></thead></tr>';
	echo '<tr>';
		echo '<td align="left" width="220"><b>Заголовок статьи</b></td>';
		echo '<td align="left"><input type="text" id="title" maxlength="100" value="'.$row["title"].'" class="ok"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><b>URL сайта</b> (включая http://)</td>';
		echo '<td align="left"><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="center" colspan="3"><b>Краткое описание статьи</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" colspan="2">';
			echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'desc_min\'); return false;">Ж</span>';
			echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'desc_min\'); return false;">К</span>';
			echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'desc_min\'); return false;">Ч</span>';
			echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'desc_min\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'desc_min\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'desc_min\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'desc_min\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'desc_min\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'desc_min\'); return false;">URL</span>';
			echo '<span class="bbc-url" style="float:left;" title="Добавить изображение" onClick="javascript:InsertTags(\'[img]\',\'[/img]\', \'desc_min\'); return false;">IMG</span>';
			echo '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:8px;">Осталось символов: 1000</span>';
			echo '<br>';
			echo '<div style="display: block; clear:both; padding-top:4px">';
				echo '<textarea id="desc_min" class="ok" style="height:120px; width:99%;" onKeyup="descchange(\'1\', this, \'1000\');" onKeydown="descchange(\'1\', this, \'1000\');" onClick="descchange(\'1\', this, \'1000\');">'.$row["desc_min"].'</textarea>';
			echo '</div>';
		echo '</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="center" colspan="2"><b>Описание статьи</b></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" colspan="2">';
			echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'desc_big\'); return false;">Ж</span>';
			echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'desc_big\'); return false;">К</span>';
			echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'desc_big\'); return false;">Ч</span>';
			echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'desc_big\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'desc_big\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'desc_big\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'desc_big\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'desc_big\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'desc_big\'); return false;">URL</span>';
			echo '<span class="bbc-url" style="float:left;" title="Добавить изображение" onClick="javascript:InsertTags(\'[img]\',\'[/img]\', \'desc_big\'); return false;">IMG</span>';
			echo '<span id="count2" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:8px;">Осталось символов: 5000</span>';
			echo '<br>';
			echo '<div style="display: block; clear:both; padding-top:4px">';
				echo '<textarea id="desc_big" class="ok" style="height:200px; width:99%;" onKeyup="descchange(\'2\', this, \'5000\');" onKeydown="descchange(\'2\', this, \'5000\');" onClick="descchange(\'2\', this, \'5000\');">'.$row["desc_big"].'</textarea>';
			echo '</div>';
		echo '</td>';
	echo '</tr>';
	echo '</table>';
echo '</div>';

echo '<div align="center" style="padding-top:10px;"><span id="Save" onClick="SaveAds(\''.$id.'\', \'articles\', \'Save\');" class="proc-btn" style="float:none; width:160px;">Сохранить изменения</span></div>';

?>