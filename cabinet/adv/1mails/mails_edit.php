<?php
if(!DEFINED("MAILS_EDIT")) {die ("Hacking attempt!");}

echo '<a name="goto"></a>';
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование ссылки в серфинге № '.$id.'</h5>';

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='1'");
	$cena_mails[1] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='2'");
	$cena_mails[2] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails' AND `howmany`='3'");
	$cena_mails[3] = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_color' AND `howmany`='1'");
	$cena_mails_color = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_active' AND `howmany`='1'");
	$cena_mails_active = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_mails_gotosite' AND `howmany`='1'");
	$cena_mails_gotosite = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_mails' AND `howmany`='1'");
	$nacenka_mails = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='min_mails' AND `howmany`='1'");
	$min_mails = mysql_result($sql,0,0);

	?><script type="text/javascript" language="JavaScript">

	function save_ads(id, type) {
		$('#info-msg-cab').hide(); 
		var url = $.trim($('#url').val());
		var title = $.trim($('#title').val());
		var description = $.trim($('#description').val());
		var question = $.trim($('#question').val());
		var answer_t = $.trim($('#answer_t').val());
		var answer_f_1 = $.trim($('#answer_f_1').val());
		var answer_f_2 = $.trim($('#answer_f_2').val());
		var tarif = $.trim($('#tarif').val());
		var color = $.trim($('#color').val());
		var active = $.trim($('#active').val());
		var gotosite = $.trim($('#gotosite').val());
		var mailsre = $.trim($('#mailsre').val());

		if ((url == '') | (url == 'http://') | (url == 'https://')) {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали URL-адрес сайта!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("url");
		} else if (title == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали заголовок письма!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("title");
		} else if (description == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали содержание письма!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("description");
		} else if (question == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали контрольный вопрос!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("question");
		} else if (answer_t == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали вариант ответа (правильный)!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("answer_t");
		} else if (answer_f_1 == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали вариант ответа (ложный)!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("answer_f_1");
		} else if (answer_f_2 == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали вариант ответа (ложный)!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("answer_f_2");
		} else {
			$.ajax({
				type: "POST", url: "/cabinet/ajax/ajax_adv.php", 
				data: {
					'op':'save', 
					'type':type, 
					'id':id, 
					'url':url, 
					'title':title, 
					'description':description, 
					'question':question, 
					'answer_t':answer_t, 
					'answer_f_1':answer_f_1, 
					'answer_f_2':answer_f_2, 
					'tarif':tarif, 
					'color':color, 
					'active':active, 
					'gotosite':gotosite, 
					'mailsre':mailsre
				}, 
				beforeSend: function() { $('#loading').show(); }, 
				success: function(data) { 
					$('#loading').hide(); 
					if (data == "OK") {
						document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>#goto";
					} else {
						gebi("info-msg-cab").innerHTML = '<span class="msg-error">' + data + '</span>';
						gebi("info-msg-cab").style.display = "";
						setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 5000); clearTimeout();
						return false;
					}
				}
			});
		}
	}

	function obsch() {
		var tarif = $.trim($('#tarif').val());
		var color = $.trim($('#color').val());
		var active = $.trim($('#active').val());
		var gotosite = $.trim($('#gotosite').val());
		var cenacolor = <?php echo $cena_mails_color;?>;
		var cenaactive = <?php echo $cena_mails_active;?>;
		var cenagotosite = <?php echo $cena_mails_gotosite;?>;
		var cena_nac = <?=(100+$nacenka_mails)/100;?>;
		if(tarif==1) {var price = <?php echo $cena_mails[1];?>;}
		if(tarif==2) {var price = <?php echo $cena_mails[2];?>;}
		if(tarif==3) {var price = <?php echo $cena_mails[3];?>;}

		var price = (price + (color * cenacolor) + (active * cenaactive) + (gotosite * cenagotosite)) * cena_nac;

		gebi('pricet').innerHTML = 'Стоимость одного просмотра';
		gebi('price').innerHTML = '<span style="color:#228B22;">' + number_format(price, 4, '.', ' ') + ' руб.</span>';
	}

	function descchange() {
		var description = gebi('description').value;
		if(description.length > 5000) {
			gebi('description').value = description.substr(0,5000);
		}
		gebi('count').innerHTML = 'Осталось <b>'+(5000-description.length)+'</b> символов';
	}

	function addtag(text1, text2) {
		if ((document.selection)){
			gebi('description').focus();
			gebi('description').document.selection.createRange().text = text1+gebi('description').document.selection.createRange().text+text2;
		} else if(gebi('description').selectionStart != undefined) {
			var element = gebi('description');
			var str = element.value;
			var start = element.selectionStart;
			var length = element.selectionEnd - element.selectionStart;
			element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
		} else gebi('description').value += text1+text2;
	}
	</script><?php

	echo '<div id="newform">';
	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th class="top" width="250">Параметр</a>';
		echo '<th class="top">Значение</a>';
	echo '</thead></tr>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td><b>Заголовок письма</b></td>';
			echo '<td><input type="text" id="title" maxlength="255" value="'.$row["title"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>URL сайта</b></td>';
			echo '<td><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Содержание письма &darr;</b></td>';
			echo '<td>';
				echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:addtag(\'[b]\',\'[/b]\'); return false;">Ж</span>';
				echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:addtag(\'[i]\',\'[/i]\'); return false;">К</span>';
				echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:addtag(\'[u]\',\'[/u]\'); return false;">Ч</span>';
				echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:addtag(\'[url]\',\'[/url]\'); return false;">URL</span>';
				echo '<span id="count" style="display: block; float:right; color:#696969; margin-top:3px; margin-right:3px;"></span>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2">';
				echo '<textarea id="description" class="ok" style="height:150px; width:98.5%;" onkeydown="this.style.background=\'#FFFFFF\';" onkeyup="descchange();">'.$row["description"].'</textarea>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Контрольный вопрос к письму</b></td>';
			echo '<td><input type="text" id="question" maxlength="255" value="'.$row["question"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Вариант ответа <span style="color: #009125;">(правильный)</span></td>';
			echo '<td><input type="text" id="answer_t" maxlength="255" value="'.$row["answer_t"].'" class="ok" style="color: #009125;" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Вариант ответа <span style="color: #FF0000;">(ложный)</span></td>';
			echo '<td><input type="text" id="answer_f_1" maxlength="255" value="'.$row["answer_f_1"].'" class="ok" style="color: #FF0000;" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Вариант ответа <span style="color: #FF0000;">(ложный)</span></td>';
			echo '<td><input type="text" id="answer_f_2" maxlength="255" value="'.$row["answer_f_2"].'" class="ok" style="color: #FF0000;" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Тарифный план</b></td>';
			echo '<td>';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="tarif" onChange="obsch();" onClick="obsch();">';
						echo '<option value="1" '.("1"==$row["tarif"] ? 'selected="selected"' : false).'>VIP (60 секунд): стоимость - '.round(number_format(($cena_mails[1] * ($nacenka_mails+100)/100),6,".",""),6).' руб./письмо</option>';
						echo '<option value="2" '.("2"==$row["tarif"] ? 'selected="selected"' : false).'>STANDART (40 секунд): стоимость - '.round(number_format(($cena_mails[2] * ($nacenka_mails+100)/100),6,".",""),6).' руб./письмо</option>';
						echo '<option value="3" '.("3"==$row["tarif"] ? 'selected="selected"' : false).'>LITE (20 секунд): стоимость - '.round(number_format(($cena_mails[3] * ($nacenka_mails+100)/100),6,".",""),6).' руб./письмо</option>';
					echo '</select>';
				}elseif($row["tarif"]==1) {
					echo '<b>VIP (60 секунд)</b>';
					echo '<input type="hidden" id="tarif" value="'.$row["tarif"].'" />';
				}elseif($row["tarif"]==2) {
					echo '<b>STANDART (40 секунд)</b>';
					echo '<input type="hidden" id="tarif" value="'.$row["tarif"].'" />';
				}elseif($row["tarif"]==3) {
					echo '<b>LITE (20 секунд)</b>';
					echo '<input type="hidden" id="tarif" value="'.$row["tarif"].'" />';
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Выделить цветом</td>';
			echo '<td>';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="color" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("0"==$row["color"] ? 'selected="selected"' : false).'>Нет</option>';
						echo '<option value="1" '.("1"==$row["color"] ? 'selected="selected"' : false).'>Да (+'.round(number_format(($cena_mails_color * ($nacenka_mails+100)/100),6,".",""),6).' руб./письмо)</option>';
					echo '</select>';
				}elseif($row["color"]==0) {
					echo '<b>НЕТ</b>';
					echo '<input type="hidden" id="color" value="'.$row["color"].'" />';
				}elseif($row["color"]==1) {
					echo '<b>ДА</b>';
					echo '<input type="hidden" id="color" value="'.$row["color"].'" />';
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Активное окно</td>';
			echo '<td>';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="active" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("0"==$row["active"] ? 'selected="selected"' : false).'>Нет</option>';
						echo '<option value="1" '.("1"==$row["active"] ? 'selected="selected"' : false).'>Да (+'.round(number_format(($cena_mails_active*(100+$nacenka_hits_bs)/100),6,".",""),6).' руб./письмо)</option>';
					echo '</select>';
				}elseif($row["active"]==0) {
					echo '<b>НЕТ</b>';
					echo '<input type="hidden" id="active" value="'.$row["active"].'" />';
				}elseif($row["active"]==1) {
					echo '<b>ДА</b>';
					echo '<input type="hidden" id="active" value="'.$row["active"].'" />';
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>Последующий переход на сайт</td>';
			echo '<td>';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="gotosite" onChange="obsch();" onClick="obsch();">';
						echo '<option value="0" '.("0"==$row["gotosite"] ? 'selected="selected"' : false).'>Нет</option>';
						echo '<option value="1" '.("1"==$row["gotosite"] ? 'selected="selected"' : false).'>Да (+'.round(number_format(($cena_mails_gotosite*(100+$nacenka_hits_bs)/100),6,".",""),6).' руб./письмо)</option>';
					echo '</select>';
				}elseif($row["gotosite"]==0) {
					echo '<b>НЕТ</b>';
					echo '<input type="hidden" id="gotosite" value="'.$row["gotosite"].'" />';
				}elseif($row["gotosite"]==1) {
					echo '<b>ДА</b>';
					echo '<input type="hidden" id="gotosite" value="'.$row["gotosite"].'" />';
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>Технология прочтения письма</b></td>';
			echo '<td>';
				echo '<select id="mailsre" onChange="obsch();" onClick="obsch();">';
					echo '<option value="0"'.("0"==$row["mailsre"] ? 'selected="selected"' : false).'>Доступно для прочтения только 1 раз</option>';
					echo '<option value="1"'.("1"==$row["mailsre"] ? 'selected="selected"' : false).'>Доступно для прочтения каждые 24 часа</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td id="pricet"></td>';
			echo '<td id="price"></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div><br>';

	echo '<div id="info-msg-cab"></div>';

	echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'mails\');" class="sub-blue160" style="float:none; width:160px;">Сохранить</span></div>';

	?><script language="JavaScript">obsch(); descchange();</script><?php
}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>