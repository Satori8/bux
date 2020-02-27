<?php
$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

$sql_p = mysql_fetch_array(mysql_query("SELECT `id`, `status` FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'"));

    if($sql_p['status']=='pay'){
		echo '<span class="msg-error">Ошибка! Для редактирования задания нужно остановить!</span>';
		include('footer.php'); 
		exit();
		}

//echo '<b>Редактирование оплачиваемого задания:</b><br><br>';

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("'"," ",$mensaje);
	$mensaje = str_replace(";"," ",$mensaje);
	$mensaje = str_replace("$","$",$mensaje);
	$mensaje = str_replace("<","&#60;",$mensaje);
	$mensaje = str_replace(">","&#62;",$mensaje);
	$mensaje = str_replace("\\","",$mensaje);
	$mensaje = str_replace("&amp amp ","&amp;",$mensaje);
	$mensaje = str_replace("&amp quot ","&quot;",$mensaje);
	$mensaje = str_replace("&amp gt ","&gt;",$mensaje);
	$mensaje = str_replace("&amp lt ","&lt;",$mensaje);
	$mensaje = str_replace("\r\n","<br>",$mensaje);
	return $mensaje;
}


$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
$cena_task = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
$nacenka_task = mysql_result($sql,0,0);

if(count($_POST) > 0) {
	$zdname = (isset($_POST["zdname"])) ? limpiarez($_POST["zdname"]) : false;
	$zdname = limitatexto($zdname,100);
	$zdtext = (isset($_POST["zdtext"])) ? limpiarez($_POST["zdtext"]) : false;
	$zdtext = limitatexto($zdtext,5000);
	$zdurl = (isset($_POST["zdurl"])) ? limpiarez($_POST["zdurl"]) : false;
	$zdurl = limitatexto($zdurl,160);
	$zdtype = (isset($_POST["zdtype"]) && (intval($_POST["zdtype"])==1 | intval($_POST["zdtype"])==2 | intval($_POST["zdtype"])==3 | intval($_POST["zdtype"])==4 | intval($_POST["zdtype"])==5 | intval($_POST["zdtype"])==6 | intval($_POST["zdtype"])==7 | intval($_POST["zdtype"])==8 | intval($_POST["zdtype"])==9 | intval($_POST["zdtype"])==10 | intval($_POST["zdtype"])==11 | intval($_POST["zdtype"])==12 | intval($_POST["zdtype"])==13 | intval($_POST["zdtype"])==14 | intval($_POST["zdtype"])==15 | intval($_POST["zdtype"])==16 | intval($_POST["zdtype"])==18 | intval($_POST["zdtype"])==19 | intval($_POST["zdtype"])==20 | intval($_POST["zdtype"])==21 | intval($_POST["zdtype"])==22 | intval($_POST["zdtype"])==23)) ? intval($_POST["zdtype"]) : "8";
	$time = (isset($_POST["time"]) && (intval($_POST["time"])==1 | intval($_POST["time"])==2 | intval($_POST["time"])==3 | intval($_POST["time"])==4 | intval($_POST["time"])==5 | intval($_POST["time"])==6 | intval($_POST["time"])==7 | intval($_POST["time"])==8)) ? intval($_POST["time"]) : "8";
	
	$zdre = (isset($_POST["zdre"]) && (intval($_POST["zdre"])==0 | intval($_POST["zdre"])==3 | intval($_POST["zdre"])==6 | intval($_POST["zdre"])==12 | intval($_POST["zdre"])==24 | intval($_POST["zdre"])==48 | intval($_POST["zdre"])==72)) ? intval(limpiarez($_POST["zdre"])) : "0";
	$zdcountry = (isset($_POST["zdcountry"]) && (intval($_POST["zdcountry"])==0 | intval($_POST["zdcountry"])==1 | intval($_POST["zdcountry"])==2)) ? intval(limpiarez($_POST["zdcountry"])) : "0";
	$zdcheck = (isset($_POST["zdcheck"]) && (intval($_POST["zdcheck"])==1 | intval($_POST["zdcheck"])==2)) ? intval(limpiarez($_POST["zdcheck"])) : "1";
	
	$zddistrib_ch = (isset($_POST["zddistrib_ch"]) && (intval($_POST["zddistrib_ch"])>0)) ? intval(limpiarez($_POST["zddistrib_ch"])) : "0";
	$zddistrib_min = (isset($_POST["zddistrib_min"]) && (intval($_POST["zddistrib_min"])>0)) ? intval(limpiarez($_POST["zddistrib_min"])) : "0";
	
	$distrib=$zddistrib_ch*60*60+$zddistrib_min*60;
	
	$zdquest = (isset($_POST["zdquest"])) ? limpiarez($_POST["zdquest"]) : false;
	$zdquest = limitatexto($zdquest,255);
	$zdotv = (isset($_POST["zdotv"])) ? limpiarez($_POST["zdotv"]) : false;
	$zotch = (isset($_POST["zotch"])) ? limpiarez($_POST["zotch"]) : false;
	$zdotv = limitatexto($zdotv,255);
	$zdprice = (isset($_POST["zdprice"])) ? p_floor(abs(floatval(str_replace(",",".",trim($_POST["zdprice"])))),2) : "$cena_task";
	$zdreit = (isset($_POST["zdreit"]) && (intval($_POST["zdreit"])>=0 && intval($_POST["zdreit"])<=100)) ? intval(limpiarez($_POST["zdreit"])) : "0";
	$mailwm = ( isset($_POST["mailwm"]) && (intval($_POST["mailwm"])==0 | intval($_POST["mailwm"])==1) ) ? intval($_POST["mailwm"]) : "0";

	if(strlen($zdname) < 1)
		echo '<span class="msg-error">Ошибка! Не указано название!</span>';
	elseif($zdtext==false)
		echo '<span class="msg-error">Ошибка! Не указано описание!</span>';
	elseif($zotch==false && $zdcheck=='1')
		echo '<fieldset class="errorp">Ошибка! Укажите что нужно подать в  отчете!</fieldset>';
	elseif($zdurl==false)
		echo '<span class="msg-error">Ошибка! Не указана ссылка на сайт!</span>';
	elseif(substr($zdurl, 0, 7) != "http://" && substr($zdurl, 0, 8) != "https://")
		echo '<span class="msg-error">Ошибка! Не верно указана ссылка на сайт!</span>';
	elseif($zdcheck==2 && ($zdquest==false | $zdotv==false | strlen($zdquest) < 4 | strlen($zdotv) < 2) ) {
		if(strlen($zdquest) < 4)
			echo '<span class="msg-error">Ошибка! Не указан контрольный вопрос!</span>';
		elseif(strlen($zdotv) < 2)
			echo '<span class="msg-error">Ошибка! Не указан ответ на контрольный вопрос!</span>';
		else {}
	}elseif($zdprice<$cena_task) {
			echo '<span class="msg-error">Ошибка! Минимальная стоимость за выполнение задания '.number_format($cena_task,2,"."," ").' руб.</span>';
	}elseif(@getHost($zdurl)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($zdurl)!=false) {
		echo '<span class="msg-error">'.SFB_YANDEX($zdurl).'</span>';
	}else{
		$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_array($sql);

			if($row["totals"] > 0 && $row["wait"] < 1) {
				if($zdprice >= $row["zdprice"]) {
					$money_add = ( ($zdprice - $row["zdprice"]) * $row["totals"] * (100 + $nacenka_task) / 100 );
					$money_add = round($money_add,4);

					$sql_u = mysql_query("SELECT `money_rb` FROM `tb_users` WHERE `username`='$username'");
					$row_u = mysql_fetch_row($sql_u);

					$money_users = $row_u["0"];

					if($money_add > $money_users) {
						$zdprice = $row["zdprice"];

						echo '<span class="msg-error">Ошибка! На балансе аккаунта недостаточно денег, необходимо '.$money_add.' руб.</span>';
						include('footer.php');
						exit();
					}else{
						mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$money_add', `money_rek`=`money_rek`+'$money_add' WHERE `username`='$username'") or die(mysql_error());
					}
				}else{
					$zdprice = $row["zdprice"];
					echo '<span class="msg-error">Ошибка! Стоимость задания нельзя уменьшать!</span>';
					include('footer.php');
					exit();
				}

				mysql_query("UPDATE `tb_ads_task` SET `time`='$time', `zotch`= '$zotch', `country_targ`='$zdcountry',`user_id`='$partnerid',`zdname`='$zdname',`zdtext`='$zdtext',`zdurl`='$zdurl',`zdtype`='$zdtype',`zdre`='$zdre',`zdquest`='$zdquest',`zdotv`='$zdotv',`zdprice`='$zdprice',`zdreit_us`='$zdreit',`ip`='$ip', `distrib`='$distrib' WHERE  `id`='$rid' AND `username`='$username'") or die(mysql_error());

				echo '<fieldset class="msg-ok">Изменения успешно сохранены!</span>';

				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?option=task_view");</script>';
				echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?option=task_view">';

				include('footer.php');
				exit();

			}elseif($row["wait"] > 0) {
				echo '<span class="msg-error">Ошибка! Есть не потвержденные выполнения!</span>';
				include('footer.php');
				exit();
			}else{
				mysql_query("UPDATE `tb_ads_task` SET `time`='$time', `zotch`= '$zotch', `country_targ`='$zdcountry',`user_id`='$partnerid',`zdname`='$zdname',`zdtext`='$zdtext',`zdurl`='$zdurl',`zdtype`='$zdtype',`zdre`='$zdre',`zdquest`='$zdquest',`zdotv`='$zdotv',`zdprice`='$zdprice',`zdreit_us`='$zdreit',`ip`='$ip', `distrib`='$distrib' WHERE  `id`='$rid' AND `username`='$username'") or die(mysql_error());

				echo '<fieldset class="msg-ok">Изменения успешно сохранены!</span>';

				echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?option=task_view");</script>';
				echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?option=task_view">';

				include('footer.php');
				exit();
			}
		}else{
			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?option=task_view");</script>';
			echo '<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?option=task_view">';

			include('footer.php');
			exit();
		}
	}
}else{
	$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$id = $row["id"];
		$zdname = $row["zdname"];
		$zdtext = $row["zdtext"];
		$zdurl = $row["zdurl"];
		$zdtype = $row["zdtype"];
		$zdre = $row["zdre"];
		$zdreit = $row["zdreit_us"];
		$zdcheck = $row["zdcheck"];
		$zdquest = $row["zdquest"];
		$zdotv = $row["zdotv"];
		$zotch = $row["zotch"];
		$zdprice = $row["zdprice"];
		$zdcountry = $row["country_targ"];
		$mailwm = $row["mailwm"];
		$time = $row["time"];
		$distrib = $row["distrib"];
	}else{
		echo '<span class="msg-error">У вас нет задания #'.$rid.'</span>';
		include('footer.php');
		exit();
	}
}

if($distrib>0){
$ms=$distrib/3600;
$dist_ch=floor($ms);
$dist_min=($distrib%3600)/60;
}else{
$dist_ch=0;
$dist_min=0;
}

if($zdcountry==1) {$sel_country1='selected="selected"';}else{$sel_country1="";}
if($zdcountry==2) {$sel_country2='selected="selected"';}else{$sel_country2="";}
if($zdtype==1) {$sel_type1='selected="selected"';}else{$sel_type1="";}
if($zdtype==2) {$sel_type2='selected="selected"';}else{$sel_type2="";}
if($zdtype==3) {$sel_type3='selected="selected"';}else{$sel_type3="";}
if($zdtype==4) {$sel_type4='selected="selected"';}else{$sel_type4="";}
if($zdtype==5) {$sel_type5='selected="selected"';}else{$sel_type5="";}
if($zdtype==6) {$sel_type6='selected="selected"';}else{$sel_type6="";}
if($zdtype==7) {$sel_type7='selected="selected"';}else{$sel_type7="";}
if($zdtype==8) {$sel_type8='selected="selected"';}else{$sel_type8="";}

if($zdtype==9) {$sel_type9='selected="selected"';}else{$sel_type9="";}
if($zdtype==10) {$sel_type10='selected="selected"';}else{$sel_type10="";}
if($zdtype==11) {$sel_type11='selected="selected"';}else{$sel_type11="";}
if($zdtype==12) {$sel_type12='selected="selected"';}else{$sel_type12="";}
if($zdtype==13) {$sel_type13='selected="selected"';}else{$sel_type13="";}
if($zdtype==14) {$sel_type14='selected="selected"';}else{$sel_type14="";}
if($zdtype==15) {$sel_type15='selected="selected"';}else{$sel_type15="";}
if($zdtype==16) {$sel_type16='selected="selected"';}else{$sel_type16="";}
/*if($zdtype==17) {$sel_type17='selected="selected"';}else{$sel_type17="";}*/
if($zdtype==18) {$sel_type18='selected="selected"';}else{$sel_type18="";}
if($zdtype==19) {$sel_type19='selected="selected"';}else{$sel_type19="";}
if($zdtype==20) {$sel_type20='selected="selected"';}else{$sel_type20="";}
if($zdtype==21) {$sel_type21='selected="selected"';}else{$sel_type21="";}
if($zdtype==22) {$sel_type22='selected="selected"';}else{$sel_type22="";}
if($zdtype==23) {$sel_type23='selected="selected"';}else{$sel_type23="";}



if($time==1) {$sel_type_t1='selected="selected"';}else{$sel_type_t1="";}
if($time==2) {$sel_type_t2='selected="selected"';}else{$sel_type_t2="";}
if($time==3) {$sel_type_t3='selected="selected"';}else{$sel_type_t3="";}
if($time==4) {$sel_type_t4='selected="selected"';}else{$sel_type_t4="";}
if($time==5) {$sel_type_t5='selected="selected"';}else{$sel_type_t5="";}
if($time==6) {$sel_type_t6='selected="selected"';}else{$sel_type_t6="";}
if($time==7) {$sel_type_t7='selected="selected"';}else{$sel_type_t7="";}
if($time==8) {$sel_type_t8='selected="selected"';}else{$sel_type_t8="";}
if($zdre==3)  {$sel_re3='selected="selected"';}else{$sel_re3="";}
if($zdre==6)  {$sel_re6='selected="selected"';}else{$sel_re6="";}
if($zdre==12) {$sel_re12='selected="selected"';}else{$sel_re12="";}
if($zdre==24) {$sel_re24='selected="selected"';}else{$sel_re24="";}
if($zdre==48) {$sel_re48='selected="selected"';}else{$sel_re48="";}
if($zdre==72) {$sel_re72='selected="selected"';}else{$sel_re72="";}

if($distrib==0) {$sel_dist1='selected="selected"';}else{$sel_dist1="";}
if($distrib>0) {$sel_dist2='selected="selected"';}else{$sel_dist2="";}

?>
<script>
function InsertTags(text1, text2, descId) {
	var textarea = document.getElementById(descId);
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;
		if (temp_length == 0) {
			caretPos.moveStart("character", -text2.length);
			caretPos.moveEnd("character", -text2.length);
			caretPos.select();
		} else {
			textarea.focus(caretPos);
		}
	} else if (typeof(textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;
		textarea.value = begin + text1 + selection + text2 + end;
		if (textarea.setSelectionRange) {
			if (selection.length == 0) {
				textarea.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
			} else {
				textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			}
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	} else {
		textarea.value += text1 + text2;
		textarea.focus(textarea.value.length - 1);
	}
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("Осталось символов: " +(count_s-elem.value.length));
}
</script>
<?

echo '<form id="newform" action="'.$_SERVER["PHP_SELF"].'?option='.limpiar($_GET["option"]).'&rid='.$rid.'" method="POST">';
echo '<table class="tables">';
	echo '<thead><tr align="center"><th align="center" colspan="2" class="top">Редактирование оплачиваемого задания</th></tr></thead>';
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150" align="right"><b>Название:</b></td>';
		echo '<td><input type="text" name="zdname" maxlength="100" value="'.$zdname.'" class="ok"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" align="right"><b>Описание задания:</b></td>';
		
		echo '<td align="left" colspan="2" style="">';
			echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'message\'); return false;">Ж</span>';
			echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'message\'); return false;">К</span>';
			echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'message\'); return false;">Ч</span>';
			echo '<span class="bbc-tline" style="float:left;" title="Перечеркнутый текст" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'message\'); return false;">ST</span>';
			echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'message\'); return false;"></span>';
			echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'message\'); return false;">URL</span>';
			echo '<span class="bbc-url" style="float:left;" title="Добавить изображение" onClick="javascript:InsertTags(\'[img]\',\'[/img]\', \'message\'); return false;">IMG</span>';
			echo '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:3px;">Осталось символов: 5000</span>';
			echo '<br>';
			echo '<div style="display: block; clear:both; padding-top:4px">';
					
		echo '<textarea id="message" rows="7" name="zdtext" class="ok" onKeyup="descchange(\'1\', this, \'5000\');" onKeydown="descchange(\'1\', this, \'5000\');" onClick="descchange(\'1\', this, \'5000\');">'.str_replace("<br>","\r\n", $zdtext).'</textarea>';
		
		echo '</div>';
		echo '</td>';
		
		
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" align="right"><b>Ссылка на сайт:</b></td>';
		echo '<td><input type="text" class="ok" name="zdurl" maxlength="160" value="'.$zdurl.'"></td>';
	echo '</tr>';
	if($zdcheck=='1'){
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150"  align="right"><b>Что нужно подать в отчете:</b></td>';
		echo '<td><textarea rows="7" name="zotch" maxlength="290" class="ok">'.str_replace("<br>","\r\n", $zotch).'</textarea></td>';
	echo '</tr>';
	}
		echo '<tr>';
		echo '<td nowrap="nowrap" width="150"  align="right"><b>Время на выполнение задания:</b></td>';
		echo '<td><select name="time" class="ok">
			<option value="1" '.$sel_type_t1.'>1 час</option>
			<option value="2" '.$sel_type_t2.'>3 часа</option>
			<option value="3" '.$sel_type_t3.'>12 часов</option>
			<option value="4" '.$sel_type_t4.'>24 часа</option>
			<option value="5" '.$sel_type_t5.'>3 дня</option>
			<option value="6" '.$sel_type_t6.'>7 дней</option>
			<option value="7" '.$sel_type_t7.'>14 дней</option>
			<option value="8" '.$sel_type_t8.'>30 дней</option>
		</select></td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td nowrap="nowrap" align="right"><b>Тип задания:</b></td>';
		echo '<td><select name="zdtype" class="ok">
					<option value="1" '.$sel_type1.'>Клики</option>
					 <option value="2" '.$sel_type2.'>Регистрация без активности</option>
					 <option value="3" '.$sel_type3.'>Регистрация с активностью</option>
					 <option value="4" '.$sel_type4.'>Постинг в форум</option>
					 <option value="5" '.$sel_type5.'>Постинг в блоги</option>
					 <option value="6" '.$sel_type6.'>Голосование</option>
					 <option value="7" '.$sel_type7.'>Загрузка файлов</option>
					 
					 <option value="9" '.$sel_type9.'>YouTube</option>
                     <option value="10" '.$sel_type10.'>Социальные сети</option>
                     <option value="11" '.$sel_type11.'>Написать статью</option>
                     <option value="12" '.$sel_type12.'>Оставить отзыв</option>
                     <option value="13" '.$sel_type13.'>Играть в игры</option>
                     <option value="14" '.$sel_type14.'>Инвестировать</option>
                     <option value="15" '.$sel_type15.'>Стать моим рефералом на '.$_SERVER["HTTP_HOST"].'</option>
                     <option value="16" '.$sel_type16.'>Перевод кредитов</option>

                     <option value="18" '.$sel_type18.'>Forex</option>
                     <option value="19" '.$sel_type19.'>Мобильные устройства</option>
                     <option value="20" '.$sel_type20.'>Работа с капчей</option>
                     <option value="21" '.$sel_type21.'>Работа с криптовалютами</option>
                     <option value="22" '.$sel_type22.'>Экономические Игры/Фермы</option>
                     <option value="23" '.$sel_type23.'>Зарубежные сайты</option>
					 <option value="8" '.$sel_type8.'>Прочее</option>
		</select></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150"  align="right"><b>Повтор каждые XX ч. :</b></td>';
		echo '<td><select name="zdre">
			<option value="0">Нет</option>
			<option value="3" '.$sel_re3.'>3 часа</option>
			<option value="6" '.$sel_re6.'>6 часов</option>
			<option value="12" '.$sel_re12.'>12 часов</option>
			<option value="24" '.$sel_re24.'>24 часа (1 сутки)</option>
			<option value="48" '.$sel_re48.'>48 часа (2-е суток)</option>
			<option value="72" '.$sel_re72.'>72 часа (3-е суток)</option>
		</select></td>';
	echo '</tr>';
	
	
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150" align="right"><b>Интервал последовательной раздачи:<span class="status"><font color="red" title="Укажите время которое должно пройти после начала выполнения задания одним пользователем и началом выполнения другим пользователем (таймаут между выполнениями)." style="cursor: help;">[?]</font></b></td>';
		echo '<td><select name="zddistrib" id="myselect">
			<option value="0" '.$sel_dist1.'>Без интервала</option>
			<option value="1" '.$sel_dist2.'>Фиксированный интервал</option>
		</select><div id="mydiv">'; 
		if($distrib>0){
		echo 'Часы:<input type="text" class="ok" name="zddistrib_ch" maxlength="2" value="'.$dist_ch.'"><br> Минуты:<input type="text" class="ok" name="zddistrib_min" maxlength="2" value="'.$dist_min.'">';
		echo '</div></td>';
		
		}
	echo '</tr>';
	?>

<script type="text/javascript">
    document.getElementById("myselect").addEventListener("change", function(){
	if(this.value==1){
      document.getElementById('mydiv').innerHTML = 'Часы:<input type="text" class="ok" name="zddistrib_ch" maxlength="2" value="<?=$dist_ch;?>"><br> Минуты:<input type="text" class="ok" name="zddistrib_min" maxlength="2" value="<?=$dist_min;?>">';  
	  }else{
	  document.getElementById('mydiv').innerHTML = '';  
	  }
    });
</script>
	
	<?
	echo '<thead><tr align="center"><th align="center" colspan="2" class="top">Механизм проверки задания</th></tr></thead>';
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150" align="right"><b>Механизм проверки:</b></td>';
			if($zdcheck==1) {
				echo '<td>Ручной режим<input type="hidden" name="zdcheck" value="1"></td>';
			}else{
				echo '<td>Автоматический режим<input type="hidden" name="zdcheck" value="2"></td>';
			}
	echo '</tr>';
	echo '<tr>';
		if($zdcheck==1) {
			echo '<td></td><td></td>';
		}else{
			echo '<td></td><td><b style="color:#FF0000;">Внимание! Если указание на контрольное слово будет не точным, либо контрольное слово не будет сответствовать заданию, Администрация проекта по своему усмотрению может удалить не только такое задание, но и наложить штраф на аккаунт.</b></td>';
		}
	echo '</tr>';


if($zdcheck==2) {
	echo '<thead><tr align="center"><th align="center" colspan="2" class="top">Контрольный вопрос</th></tr></thead>';
	echo '<tr>';
		echo '<td nowrap="nowrap" align="right"><b>Контрольный вопрос:</b><br>(от&nbsp;4&nbsp;до&nbsp;255&nbsp;символов)</td>';
		echo '<td><textarea rows="3" name="zdquest" class="ok">'.str_replace("<br>","\r\n", $zdquest).'</textarea></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" align="right"><b>Ответ:</b><br>(от&nbsp;2&nbsp;до&nbsp;255&nbsp;символов)</td>';
		echo '<td><input type="text" class="ok" name="zdotv" maxlength="255" value="'.$zdotv.'"></td>';
	echo '</tr>';
}

	echo '<thead><tr align="center"><th align="center" colspan="2" class="top">Настройка выполнения задания</th></tr></thead>';
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150" align="right"><b>Стоимость выполнения:</b></td>';
		echo '<td><input type="text" class="ok12" name="zdprice" maxlength="10" value="'.number_format($zdprice,2,".","").'">(минимум&nbsp;'.number_format($cena_task,2,".","").'&nbsp;руб.)</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150"  align="right"><b>Рейтинг пользователя:</b></td>';
		echo '<td><input type="text" class="ok12" name="zdreit" maxlength="3" value="'.$zdreit.'">(от&nbsp;0&nbsp;до&nbsp;100)</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" width="150"  align="right"><b>Таргетинг по странам:</b></td>';
		echo '<td><select name="zdcountry">
			<option value="0">Любые страны</option>
			<option value="1" '.$sel_country1.'>Только Россия</option>
			<option value="2" '.$sel_country2.'>Только Украина</option>
		</select></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td nowrap="nowrap" align="right"></b></td>';
		echo '<td><b></b></td>';
	echo '</tr>';
	echo '<tr align="center">';
		echo '<td colspan="2"><input type="submit" class="proc-btn" value="&nbsp;&nbsp;&nbsp;Сохранить&nbsp;&nbsp;&nbsp;"></td>';
	echo '</tr>';
echo '</table>';
echo '</form>';

?>
