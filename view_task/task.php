<script type="text/javascript" src="../scripts/jqpooop.js"></script>
<script type="text/javascript">
function ShowHide(id) {
	if(document.getElementById(id).style.display == 'none') {
		document.getElementById(id).style.display = 'block';
	} else {
		document.getElementById(id).style.display = 'none';
	}
	return false;
}

function ShowHideInfo(id) {
	if($("#"+id).css("display") == "none") {
		$("#"+id).css("display", "");
	} else {
		$("#"+id).css("display", "none");
	}
}

function CheckVirus(id, tid, op) { 
	$.ajax({  
		type: "POST", url: "/ajax/test_virus_task.php", data: { 'op' : $.trim(op), 'id' : $.trim(id) }, 
		beforeSend: function(){	$('#loading').show(); }, success: function(data) { $('#loading').hide(); $('#id_vir'+tid).hide(); alert(data); }  
	});
}

</script>
<?php
error_reporting (E_ALL);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="errorp">Ошибка! Для доступа к этой странице необходимо авторизоваться!</fieldset>';
}else{

	function limpiarez($mensaje){
		$mensaje = htmlspecialchars(trim($mensaje));
		$mensaje = str_replace("'","",$mensaje);
		$mensaje = str_replace(";","",$mensaje);
		$mensaje = str_replace("$","&#036;",$mensaje);
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

	if(count($_POST)>0 && isset($_POST["bl"])) {
		$task_id = (isset($_POST["rid"]) && intval($_POST["rid"])>0) ? intval(limpiarez($_POST["rid"])) : false;
		$bl = (isset($_POST["bl"])) ? limpiarez($_POST["bl"]) : false;

		$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$task_id' AND `status`='pay' AND `totals`>'0'");
		if(mysql_num_rows($sql)>0) {
       			$row = mysql_fetch_array($sql);
			$rek_id = $row["user_id"];

			$sql_p = mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `user_id`='$partnerid' AND `rek_id`='$rek_id'");
			if(mysql_num_rows($sql_p)>0) {
				mysql_query("UPDATE `tb_ads_task_fav` SET `type`='BL' WHERE `user_id`='$partnerid' AND `rek_id`='$rek_id'") or die(mysql_error());
			}else{
				mysql_query("INSERT INTO `tb_ads_task_fav` (`type`,`user_id`,`rek_id`,`date_add`,`ip`) VALUES('BL','$partnerid','$rek_id','".time()."','$ip')") or die(mysql_error());
			}
		}
	}

	if(count($_POST)>0 && isset($_POST["rid"]) && isset($_POST["ocenka"]) && isset($_POST["coment"])) {
		$task_id = (isset($_POST["rid"]) && intval($_POST["rid"])>0) ? intval(limpiarez($_POST["rid"])) : false;
		$ocenka = (isset($_POST["ocenka"]) && intval($_POST["ocenka"])>0) ? intval(limpiarez($_POST["ocenka"])) : false;
		$coment = (isset($_POST["coment"])) ? limpiarez($_POST["coment"]) : false;
		$coment = limitatexto($coment, 255);

		$reiting_add = ($ocenka/100);

		$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$task_id'");
		if(mysql_num_rows($sql)>0) {
       			$row = mysql_fetch_array($sql);
			$reiting_now = $row["reiting"];
			$rek_name = $row["username"];
			$all_coments_now = $row["all_coments"];

			$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$task_id' AND `type`='task' ORDER by `id` DESC LIMIT 1");
			if(mysql_num_rows($sql_p)>0) {
				$row_p = mysql_fetch_array($sql_p);
				if($row_p["status"]!="") {
					if($row_p["coment"]=="" OR $row_p["ocenka"]<1) {
						$reiting = (($reiting_now * $all_coments_now) + $ocenka) / ($all_coments_now + 1);
						$reiting = round($reiting, 2);

						mysql_query("UPDATE `tb_ads_task` SET `reiting`='$reiting', `all_coments`=`all_coments`+'1', `date_act`='".time()."' WHERE `id`='$task_id' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
						mysql_query("UPDATE `tb_ads_task_pay` SET `coment`='$coment', `ocenka`='$ocenka', `date_com`='".time()."' WHERE `user_name`='$username' AND `ident`='$task_id' AND `type`='task' ORDER by `id` DESC LIMIT 1") or die(mysql_error());
						mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reiting_add' WHERE `username`='$rek_name'") or die(mysql_error());
					}
				}
			}
		}
	}


	if(isset($_GET["rid"]) && isset($_GET["favorite"]) && intval($_GET["rid"])>0 && limpiarez($_GET["favorite"])=="add" ) {
		include('task_add_favorite.php');
	}

	if(isset($_GET["rid"]) && isset($_GET["favorite"]) && intval($_GET["rid"])>0 && limpiarez($_GET["favorite"])=="del" ) {
		include('task_del_favorite.php');
	}

	if(isset($_GET["bid"]) && isset($_GET["bl"]) && intval($_GET["bid"])>0 && limpiarez($_GET["bl"])=="del" ) {
		include('task_del_bl.php');
	}

	if(isset($_GET["rid"]) && isset($_GET["op"]) && intval($_GET["rid"])>0 && limpiarez($_GET["op"])=="gotask" ) {
		include('task_gotask.php');
	}

	if(isset($_GET["rid"]) && isset($_GET["op"]) && intval($_GET["rid"])>0 && limpiarez($_GET["op"])=="ctask" ) {
		include('task_ctask.php');
	}

	if(isset($_GET["rid"]) && intval($_GET["rid"])>0) {
		include('task_view.php');
	}

	function universal_link_bar($page, $count, $pages_count, $show_link, $sort, $sort_z, $type, $task_search, $task_name, $task_auto, $task_price) {
		if (isset($_GET["op"])) {
			$op='&op='.limpiarez($_GET["op"]).'';
		}else{
			$op="";
		}
		if (!isset($_GET["op"]) | (isset($_GET["op"]) && limpiar($_GET["op"])!="stat") ) {
			$sort_link = '&sort='.$sort.'&sort_z='.$sort_z.'&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'';
		}else{
			$sort_link = "";
		}
		if ($pages_count == 1) return false;
			$sperator = ' &nbsp;';
			$style = 'style="font-weight: bold;"';
			$begin = $page - intval($show_link / 2);
			unset($show_dots);
			if ($pages_count <= $show_link + 1) $show_dots = 'no';
			if (($begin > 2) && !isset($show_dots) && ($pages_count - $show_link > 2)) {
				echo '<a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s=1> 1 </a> ';
			}
			for ($j = 0; $j < $page; $j++) {
				if (($begin + $show_link - $j > $pages_count) && ($pages_count-$show_link + $j > 0)) {
					$page_link = $pages_count - $show_link + $j;
					if (!isset($show_dots) && ($pages_count-$show_link > 1)) {
						echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s='.($page_link - 1).'><b>...</b></a> ';
						$show_dots = "no";
					}
					echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s='.$page_link.'>'.$page_link.'</a> '.$sperator;
				} else continue;
			}
			for ($j = 0; $j <= $show_link; $j++) {
				$i = $begin + $j;
				if ($i < 1) { $show_link++; continue;}

				if (!isset($show_dots) && $begin > 1) {
					echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s='.($i-1).'><b>...</b></a> ';
					$show_dots = "no";
				}
				if ($i > $pages_count) break;
				if ($i == $page) {
					echo ' <a '.$style.' ><b style="color:#FF0000; text-decoration:underline;">'.$i.'</b></a> ';
				}else{
					echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s='.$i.'>'.$i.'</a> ';
				}
				if (($i != $pages_count) && ($j != $show_link)) echo $sperator;
				if (($j == $show_link) && ($i < $pages_count)) {
					echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s='.($i+1).'><b>...</b></a> ';
			}
		}
		if ($begin + $show_link + 1 < $pages_count) {
			echo ' <a '.$style.' href='.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).''.$op.''.$sort_link.'&s='.$pages_count.'> '.$pages_count.' </a>';
		}
		return true;
	}

	function GET_DOMEN_T($url) {
		$parts = parse_url(trim($url));
		$host = $parts["host"];
		return $host;
	}

	function format_table($id, $v, $country_targ, $text, $name, $url, $reiting, $type, $zid, $zname, $otv, $cena, $re, $good, $bad, $wait, $date, $time_color, $date_vip){
	$wait_new=mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' &&`ident`='".$id."'"));
	
		echo '<tr>';
		if($date_vip > (time()-24*60*60)) 
		{echo '<td align="center"><img title="Можете выполнить это VIP задание" src="images/otyn/ad-vip.png" alt=""><span title="VIP задание" style="float:none; margin:0; padding:0;"></span></td>';}
		else
		{echo '<td align="center"><img title="Можете выполнить это задание" src="img/icon-p.png" alt=""></td>';}
		
		echo '<td align="left">'; 
		if(($time_color+24*60*60)>=time()){
		echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiarez($_GET["page"]).'&amp;rid='.$id.'" target="_blank" class="tip">';
		echo '<span style="color:#006699;" class="classic">'.$text.'</span><font style="color:#C80000;">'.$name.'</font></a><br>';}
		
		else
			{
		echo '<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiarez($_GET["page"]).'&amp;rid='.$id.'" target="_blank" class="tip">';
		echo '<span style="color:#006699;" class="classic">'.$text.'</span><font style="color:#006699;">'.$name.'</font></a><br>';}
	
		echo '<font style="color:#89A688;">'.substr($url, 0, 65).'</font></a><br>';
		echo '№'.$id.'&nbsp;-&nbsp;';
			if($type=='1'){echo 'Клики';}
		elseif($type=='2'){echo 'Регистрация без активности';}
		elseif($type=='3'){echo 'Регистрация с активностью';}
		elseif($type=='4'){echo 'Постинг в форумы/блоги';}
		elseif($type=='5'){echo 'Бонусы';}
		elseif($type=='6'){echo 'Оставить отзыв или проголосовать';}
		elseif($type=='7'){echo 'Загрузка файлов';}
		elseif($type=='8'){echo 'Прочее';}
		elseif($type=='9'){echo 'YouTube';}
		elseif($type=='10'){echo 'Социальные сети';}
		elseif($type=='11'){echo 'Написать статью';}
		elseif($type=='13'){echo 'Играть в игры';}
		elseif($type=='14'){echo 'Инвестировать';}
		elseif($type=='15'){echo 'Стать моим рефералом на '.$_SERVER["HTTP_HOST"].'';}
		elseif($type=='16'){echo 'Перевод кредитов/баллов';}
		elseif($type=='18'){echo 'Forex';}
		elseif($type=='19'){echo 'Мобильные устройства';}
		elseif($type=='20'){echo 'Работа с капчей';}
		elseif($type=='21'){echo 'Работа с криптовалютами';}
		elseif($type=='22'){echo 'Экономические Игры/Фермы';}
		elseif($type=='23'){echo 'Зарубежные сайты';}
		
		echo '<i><a style="color:#89A688; border-bottom: 1px dotted;" href="/wall?uid='.$zid.'" title="Стена автора задания" target="_blank">&nbsp;[ '.$zname.' ]</a></i>';
				$zname = mysql_num_rows(mysql_query("SELECT * FROM tb_online WHERE username='$zname'"));
				if($zname==1) echo '<i><span title="Автор задания онлайн" style="cursor: help;color: #03c03c; font-size: 11px;"> On-line</span> </i>';
				else{ echo '';}
		if($date > (time()-24*60*60)) {echo '<span class="desctext2" style="color: rgb(255, 0, 0); text-decoration: none;">&nbsp;&nbsp;… Новое!</span>';}

				
		if($otv) echo '<span style="cursor: help; color: #de5d83; position: relative; float: right; margin: 0 3px 0 3px;" title="Задание с автоматической проверкой">[Авто-Проверка]</span>';
			else{ echo '';}
			echo '<span id="id_vir'.$id.'" class="workvir"  onClick="CheckVirus(\''.$id.'\', \''.$id.'\', \'task\'); return false;" title="Проверить ссылку на вирусы"></span>';
			echo '</td>';
		
		echo '<td align="center">';
			echo "$cena&nbsp;руб.<br>";
			if($re>0){
				echo '<span style="cursor: help; color: #9966cc; position: relative; float: right; margin: 0 3px 0 3px;" title="Задание многоразовое">[Многоразовое] <span style="color: #21abcd;">(1 раз в '.$re.'ч.)</span></span>';}else{echo "";
			}
			if($country_targ==0)
				echo '';
			elseif($country_targ==1)
				echo '&nbsp;<img src="img/flags/ru.gif" border="0" width="16" height="12" alt="" align="middle" title="Выполнение задания доступно только России" />';
			elseif($country_targ==2)
				echo '&nbsp;<img src="img/flags/ua.gif" border="0" width="16" height="12" alt="" align="middle" title="Выполнение задания доступно только Украины" />';
			else{
				echo '';
			}						
		echo '</td>';
		echo '<td align="center"><span style="color:green;">'.$good.'</span> - <span style="color:red;">'.$bad.'</span> - <span style="color:#1E90FF;">'.$wait_new.'</span><br>';
		
			?>
		<span style="color:green;margin-top:7px;"><span class='rating<?=intval($reiting); ?>' title='Рейтинг'></span></td>
		<?php
	}

	$sql = mysql_query("SELECT `ident` FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `status`!='' AND `type`='task'");
	if(mysql_num_rows($sql) > 0) {
		while($row = mysql_fetch_assoc($sql)) {
			$visited[($row['ident'])] = true;
		}
	}else{
		$visited = array();
	}

	$perpage = 30;
	if (empty($_GET["s"]) || ($_GET["s"] <= 0)) {
		$page = 1;
	}else{
		$page = intval($_GET["s"]);
	}


	$WHERE = "";
	$WHERE_F = "";
	$ORDER = "`date_up` DESC";
	$ORDER_Z = "";
	$op = (isset($_GET["op"])) ? limpiarez($_GET["op"]) : false;
	$type = (isset($_GET["type"])) ? limpiarez($_GET["type"]) : false;
	$sort = (isset($_GET["sort"]) && intval(limpiarez($_GET["sort"]))>0 ) ? intval(limpiarez($_GET["sort"])) : "1";
	$sort_z = (isset($_GET["sort_z"]) && intval(limpiarez($_GET["sort_z"]))>0 && intval(limpiarez($_GET["sort_z"]))<6 ) ? intval(limpiarez($_GET["sort_z"])) : false;
	$task_search = (isset($_GET["task_search"]) && intval(limpiarez($_GET["task_search"]))>0 && intval(limpiarez($_GET["task_search"]))<5) ? intval(limpiarez($_GET["task_search"])) : false;
	$task_name = (isset($_GET["task_name"])) ? limpiarez($_GET["task_name"]) : false;
	$task_auto = (isset($_GET["task_auto"]) && intval(limpiarez($_GET["task_auto"]))==2 ) ? intval(limpiarez($_GET["task_auto"])) : "1";
	$task_price = (isset($_GET["task_price"])) ? abs(round(floatval(str_replace(",",".",trim($_GET["task_price"]))), 2)) : false;
	
	if($task_search==false) {
		if($op==false) $WHERE = " `user_id` NOT IN (SELECT `rek_id` FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$partnerid') AND ";
	}else{
		if($task_search==1) {
			$task_name = intval($task_name);
			if($task_name>0) {
				$WHERE = " `id`='$task_name' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
			}else{
				$WHERE = " `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
			}
		}elseif($task_search==2) {
			$WHERE = " `username` LIKE '%$task_name%' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}elseif($task_search==3) {
			$WHERE = " `zdurl` LIKE '%$task_name%' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}elseif($task_search==4) {
			$task_name = intval($task_name);
			$WHERE = " `user_id`='$task_name' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		//}elseif($task_search==5) {
			//$WHERE = " `username` LIKE '%$task_name%' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}else{
			$WHERE = "";
		}
	}

/*
	if($task_search==false) {
		if($op==false) $WHERE = " `user_id` NOT IN (SELECT `rek_id` FROM `tb_ads_task_fav` WHERE `type`='BL' AND `user_id`='$partnerid') AND ";
	}else{
		if($task_search==1) {
			$task_name = intval($task_name);
			if($task_name>0) {
				$WHERE = " `id`='$task_name' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
			}else{
				$WHERE = " `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
			}
		}elseif($task_search==2) {
			$WHERE = " `zdname` LIKE '%$task_name%' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}elseif($task_search==3) {
			$WHERE = " `zdurl` LIKE '%$task_name%' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}elseif($task_search==4) {
			$task_name = intval($task_name);
			$WHERE = " `user_id`='$task_name' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}elseif($task_search==5) {
			$WHERE = " `username` LIKE '%$task_name%' AND `zdcheck`>='$task_auto' AND `zdprice`>='$task_price' AND ";
		}else{
			$WHERE = "";
		}
	}
*/
	if($type==false) {$type_tab="";}else{$type_tab="`zdtype`='$type' AND ";}

	if($sort_z==false) {
		$ORDER_Z = "";
		$zpt = "";
	}elseif($sort_z==1){
		$WHERE = "$WHERE";
		$ORDER_Z = "`zdprice` DESC";
		$zpt = ",";
	}elseif($sort_z==2){
		$WHERE = "$WHERE";
		$ORDER_Z = "`zdprice` ASC";
		$zpt = ",";
	}elseif($sort_z==3){
		$WHERE = "$WHERE";
		$ORDER_Z = "`goods` DESC";
		$zpt = ",";
	}elseif($sort_z==4){
		$WHERE = "$WHERE";
		$ORDER_Z = "`bads` DESC";
		$zpt = ",";
	}elseif($sort_z==5){
		$WHERE = "$WHERE";
		$ORDER_Z = "`wait` DESC";
		$zpt = ",";
	}else{
		$ORDER_Z = "";
		$zpt = "";
	}


	if($sort==1) {
		$WHERE = "$WHERE";
		$ORDER = "$ORDER_Z $zpt `date_up` DESC";
	}elseif($sort==2){
		$WHERE = "$WHERE `date_add`>='".(time()-24*60*60)."' AND ";
		$ORDER = "$ORDER_Z $zpt `date_up` DESC";
	}elseif($sort==3){
		$WHERE = "$WHERE `reiting`>='4.5' AND `goods`>='1' AND ";
		$ORDER = "$ORDER_Z $zpt `date_up` DESC";
	}elseif($sort==4){
		$WHERE = "$WHERE";
		$WHERE_F = " AND `id` IN (SELECT `rek_id` FROM `tb_ads_task_fav` WHERE `type`='favorite' AND `user_id`='$partnerid') ";
		$ORDER = "$ORDER_Z $zpt `date_up` DESC";
	}elseif($sort==5){
		if($sort_z==false) {
			$WHERE = "$WHERE `reiting`>='3' AND ";
			$ORDER = "`reiting` DESC, `date_up` DESC";
		}else{
			$WHERE = "$WHERE `reiting`>='3' AND ";
			$ORDER = "`reiting` DESC $zpt $ORDER_Z $zpt `date_up` DESC";
		}
	}else{
		$WHERE = "$WHERE";
		$ORDER = "$ORDER";
	}

	echo '<table class="tables">';
	echo '<tr>';
		echo '<td align="left">';
			if ($op==false | ($op!=false && $op!="stat" && $op!="bl") ) {
				$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_task' AND `howmany`='1'");
				$cena_task = mysql_result($sql,0,0);

				echo '<b>Поиск оплачиваемых заданий:</b><br><br>';
				echo '<form action="'.$_SERVER['PHP_SELF'].'" method="GET" id="newform">';
					echo '<input type="hidden" name="page" value="task">';
					echo '<input type="hidden" name="op" value="'.$op.'">';
					echo '<input type="hidden" name="sort" value="'.$sort.'">';
					echo '<input type="hidden" name="sort_z" value="'.$sort_z.'">';
					echo '<input type="hidden" name="type" value="'.$type.'">';
					echo '<table border="0" width="400px">';
						echo '<tr>';
							echo '<td><select name="task_search" class="ok">';
							echo '<option value="1"'; if($task_search==1){echo ' selected="selected"';} echo '>id задания</option>';
							echo '<option value="2"'; if($task_search==2){echo ' selected="selected"';} echo '>Логин рекламодателя</option>';
							//echo '<option value="2"'; if($task_search==2){echo ' selected="selected"';} echo '>Название задания</option>';
							echo '<option value="3"'; if($task_search==3){echo ' selected="selected"';} echo '>По URL сайта</option>';
							echo '<option value="4"'; if($task_search==4){echo ' selected="selected"';} echo '>id рекламодателя</option>';
							//echo '<option value="5"'; if($task_search==5){echo ' selected="selected"';} echo '>Логин рекламодателя</option>';
							echo '</select></td>';
							echo '<td><input type="text" name="task_name" class="ok" value=""></td>';
						echo '</tr>';
					echo '</table>';
					echo '<table border="0" width="400px">';
						if ($op==false) {echo '<tr><td colspan="2"><input type="checkbox" name="task_auto" value="2"'; if($task_auto==2){echo ' checked="checked"';} echo '> - отображать задания <b>только</b> с автоподтверждением</td></tr>';}
						echo '<tr><td>Стоимость задания больше: <input type="text" name="task_price" value="'.number_format($cena_task,2,".","").'" class="ok" style="width:65px; text-align:right;">';
						echo '<input type="hidden" name="s" value="'.$page.'"></td>';
						echo '<td align="right"><input type="submit" class="proc-btn-t" value="&nbsp;Найти&nbsp;"></td></tr>';
					echo '</table>';
				echo '</form></div>';

			}
			if(isset($_GET["op"]) && limpiarez($_GET["op"])=="stat") {echo '<b>Статистика по выполнению заданий пользователя '.$username.' (#'.$partnerid.')</b><br><br>';}
			if(isset($_GET["op"]) && limpiarez($_GET["op"])=="bl") {echo '<b>Черный список рекламодателей (Black List)</b><br><br>В черном списке находятся рекламодатели, задания которых Вам не понравились. Задания этих рекламодателей не будут отображаться в общем списке заданий. Занести рекламодателя в черный список можно нажав на кнопку [<b>BL</b>], которая находится рядом с логином рекламодателя, при просмотре задания.';}
		echo '</td>';
		echo '<td align="right" width="300">';
			echo '<fieldset class="task">';
			if($op!=false) {echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'">Оплачиваемые задания</a><br>';}else{echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'" class="b">Оплачиваемые задания</a><br>';}
			if($op=="dorab") {echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=dorab" class="b">Задания на доработке</a><br>';}else{echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=dorab">Задания на доработке</a><br>';}
			if($op=="wait") {echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=wait" class="b">Незавершенные задания</a><br>';}else{echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=wait">Незавершенные задания</a><br>';}
			if($op=="pay") {echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=pay" class="b">Выполненные задания</a><br>';}else{echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=pay">Выполненные задания</a><br>';}
			if($op=="stat") {echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=stat" class="b">Статистика</a><br>';}else{echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=stat">Статистика</a><br>';}
			if($op=="bl") {echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=bl" class="b">Черный список (Black List)</a><br>';}else{echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op=bl">Черный список (Black List)</a><br>';}
			echo '<a href="/ads_task.php?page=task">Добавить задание</a><br>';
			echo '</fieldset>';
		echo '</td>';
	echo '</tr>';
	echo '</table>';

//	echo "<br>"; include_once("includes/vip_task_new.php");
	
	if(isset($_GET["op"]) && limpiarez($_GET["op"])=="wait") {
		$count = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`='' AND `type`='task' AND `user_name`='$username') $WHERE_F"));
		$pages_count = ceil($count / $perpage);

		if ($page > $pages_count) $page = $pages_count;
		$start_pos = ($page - 1) * $perpage;
		if($start_pos<0) $start_pos=0;

		$tabla = mysql_query("SELECT * FROM `tb_ads_task` WHERE $WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`='' AND `type`='task' AND `user_name`='$username') $WHERE_F ORDER BY $ORDER LIMIT $start_pos,$perpage");

		$no_task_text = "Незавершенные задания отсутствуют!";

	}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="dorab") {
		mysql_query("UPDATE `tb_ads_task_notif` SET `status`='1' WHERE `status`='0' AND `type`='dorab' AND `user_name`='$username'") or die(mysql_error());

		$count = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`='dorab' AND `type`='task' AND `user_name`='$username') $WHERE_F"));
		$pages_count = ceil($count / $perpage);

		if ($page > $pages_count) $page = $pages_count;
		$start_pos = ($page - 1) * $perpage;
		if($start_pos<0) $start_pos=0;

		$tabla = mysql_query("SELECT * FROM `tb_ads_task` WHERE $WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`='dorab' AND `type`='task' AND `user_name`='$username') $WHERE_F ORDER BY $ORDER LIMIT $start_pos,$perpage");

		$no_task_text = "Заданий на доработке нет!";

	}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="pay") {
		mysql_query("UPDATE `tb_ads_task_notif` SET `status`='1' WHERE `status`='0' AND `type` IN ('good', 'good_auto') AND `user_name`='$username'") or die(mysql_error());

		$count = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task` WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`!='' AND `type`='task' AND `user_name`='$username') $WHERE_F"));
		$pages_count = ceil($count / $perpage);

		if ($page > $pages_count) $page = $pages_count;
		$start_pos = ($page - 1) * $perpage;
		if($start_pos<0) $start_pos=0;

		$tabla = mysql_query("SELECT * FROM `tb_ads_task` WHERE $WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`!='' AND `type`='task' AND `user_name`='$username') $WHERE_F ORDER BY $ORDER LIMIT $start_pos,$perpage");

		$no_task_text = "Выполненные задания отсутствуют!";

	}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="stat") {
		mysql_query("UPDATE `tb_ads_task_notif` SET `status`='1' WHERE `status`='0' AND `type` IN ('good', 'good_auto', 'bad') AND `user_name`='$username'") or die(mysql_error());

		require('task_stat.php');

		include('footer.php');
		exit();

	}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="bl") {
		require('task_bl.php');

		include('footer.php');
		exit();
	}else{
		$count = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_ads_task` WHERE $WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`!='' AND `type`='task' AND `re`='0' AND `user_name`='$username') 
		$WHERE_F
		
		
		
		
		"));
		$pages_count = ceil($count / $perpage);

		if ($page > $pages_count) $page = $pages_count;
		$start_pos = ($page - 1) * $perpage;
		if($start_pos<0) $start_pos=0;

		$tabla = mysql_query("SELECT * FROM `tb_ads_task` WHERE $WHERE `status`='pay' AND $type_tab `totals`>'0' AND `id` NOT IN (SELECT `ident` FROM `tb_ads_task_pay` WHERE `status`!='' AND `type`='task' AND `re`='0' AND `user_name`='$username') 
		$WHERE_F 
		
		
		
		ORDER BY $ORDER LIMIT $start_pos,$perpage");

		$no_task_text = "Доступных заданий нет!";
	}

	$all_task = mysql_num_rows($tabla);

	echo '<table class="tables">';
	echo '<thead>';
		echo '<tr>';
			echo '<td colspan="4">Найдено записей <b>'.$count.'</b>, показано <b>'.$all_task.'</b><br>';
			if($count>$perpage) {echo '<div align="left"><b>Страницы:</b> '; universal_link_bar($page, $count, $pages_count, 8, $sort, $sort_z, $type, $task_search, $task_name, $task_auto, $task_price); echo '</div>';}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
		echo '<th class="top" align="center" width="30">#</th>';
		echo '<th class="top" align="left" style="border-bottom: 1px solid #893f45; border-left: 1px solid #893f45;">
			Название ';
			if($op==false) {
				echo '
					<a href="/view_task.php?page=task"'; if($sort<2){echo ' class="b"';} echo '>Все</a> | 
					<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort=2&sort_z='.$sort_z.'&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($sort==2){echo ' class="b"';} echo '>Новые</a> | 
					<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort=3&sort_z='.$sort_z.'&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($sort==3){echo ' class="b"';} echo '>Лучшие</a> | 
					<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort=4&sort_z='.$sort_z.'&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($sort==4){echo ' class="b"';} echo '>Избранное</a> | 
					<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort=5&sort_z='.$sort_z.'&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($sort==5){echo ' class="b"';} echo '>По рейтингу</a>';}
		echo '</th>';
		echo '<th class="top" align="center" style="border-bottom: 1px solid #893f45;">Стоимость</th>';
		echo '<th class="top" align="center" style="border-bottom: 1px solid #893f45;">Статистика</th>';
		echo '</tr>';
		echo '<tr>';
		echo '<th class="top" align="center" width="30"></th>';
		echo '<th class="top" align="left" style="border-left: 1px solid #893f45;">
		Тип заданий: 
				<a href="/view_task.php?page=task"'; if($type<1){echo ' class="b"';} echo '>Все</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=1&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==1){echo ' class="b"';} echo '>Клики</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=2&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==2){echo ' class="b"';} echo '>Рег. без активности</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=3&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==3){echo ' class="b"';} echo '>Рег. с активностью</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=4&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==4){echo ' class="b"';} echo '>Постинг в форумы/блоги</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=5&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==5){echo ' class="b"';} echo '>Бонусы</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=6&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==6){echo ' class="b"';} echo '>Оставить отзыв или проголосовать</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=7&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==7){echo ' class="b"';} echo '>Загрузка файлов</a> | 
				
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=9&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==9){echo ' class="b"';} echo '>YouTube</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=10&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==10){echo ' class="b"';} echo '>Социальные сети</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=11&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==11){echo ' class="b"';} echo '>Написать статью</a> | 
	
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=13&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==13){echo ' class="b"';} echo '>Играть в игры</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=14&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==14){echo ' class="b"';} echo '>Инвестировать</a> | 
				
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=15&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==15){echo ' class="b"';} echo '>Стать моим рефералом на '.$_SERVER["HTTP_HOST"].'</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=16&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==16){echo ' class="b"';} echo '>Перевод кредитов/баллов</a> | 
				
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=18&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==18){echo ' class="b"';} echo '>Forex</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=19&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==19){echo ' class="b"';} echo '>Мобильные устройства</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=20&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==20){echo ' class="b"';} echo '>Работа с капчей</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=21&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==21){echo ' class="b"';} echo '>Работа с криптовалютами</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=22&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==22){echo ' class="b"';} echo '>Экономические Игры/Фермы</a> | 
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=23&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==23){echo ' class="b"';} echo '>Зарубежные сайты</a> | 
				
				<a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z='.$sort_z.'&type=8&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"'; if($type==8){echo ' class="b"';} echo '>Прочее</a>
		</th>';

		echo '<th class="top" align="center" style="width:auto;">
			<table class="tables"><thead>
			<tr align="center">
			<th><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z=1&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"><img src="img/down.png" border="0" alt="" align="middle" title="Сортировка по уменьшению стоимости" /></a></th>
			<th><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z=2&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"><img src="img/up.png" border="0" alt="" align="middle" title="Сортировка по увеличению стоимости" /></a></th>
			</tr></thead></table>
		</th>';
		echo '<th class="top" align="center">
			<table class="tables"><thead>
			<tr align="center">
			<th><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z=3&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"><img src="img/good.png" border="0" alt="" align="middle" title="Выполнено и оплачено" /></a></th>
			<th><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z=4&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"><img src="img/bad.png" border="0" alt="" align="middle" title="Отказано в оплате" /></a></th>
			<th><a href="'.$_SERVER['PHP_SELF'].'?page='.limpiarez($_GET["page"]).'&op='.$op.'&sort='.$sort.'&sort_z=5&type='.$type.'&task_search='.$task_search.'&task_name='.$task_name.'&task_auto='.$task_auto.'&task_price='.$task_price.'&s='.$page.'"><img src="img/wait.png" border="0" alt="" align="middle" title="Непроверенных заявок" /></a></th>
			</tr></thead></table>
		</th>';
		echo '</tr>';
	echo '<thead>';

	if($all_task>0) {
		while($links_row = mysql_fetch_assoc($tabla)) {
		
		$idz=$links_row["id"];
		$time_dist=time()-$links_row['distrib'];
		$sql_distrib = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `date_start`>'$time_dist' && ident='$idz'");
		if($links_row['distrib']>0 && mysql_num_rows($sql_distrib)>0){
		
		}else{
		
			if(isset($visited[($links_row['id'])])) $is_visited = true;
			else $is_visited = false;

			format_table(
				$links_row["id"],
				$is_visited,
				$links_row["country_targ"],
				$links_row["zdtext"],
				$links_row["zdname"],
				$links_row["zdurl"],
				$links_row["reiting"],
				$links_row["zdtype"],
				$links_row["user_id"],
				$links_row["username"],
				$links_row["zdotv"],
				$links_row["zdprice"],
				$links_row["zdre"],
				$links_row["goods"],
				$links_row["bads"],
				$links_row["wait"],
				$links_row["date_add"],
				$links_row["time_color"],
				$links_row["vip"]
			);
			}
		}
		echo '<tr>';
			echo '<td colspan="4">Найдено записей <b>'.$count.'</b>, показано <b>'.$all_task.'</b><br>';
			if($count>$perpage) {echo '<div align="left"><b>Страницы:</b> '; universal_link_bar($page, $count, $pages_count, 8, $sort, $sort_z, $type, $task_search, $task_name, $task_auto, $task_price); echo '</div>';}
			echo '</td>';
		echo '</tr>';
	}else{
		echo '<tr><td align="center" colspan="4">'.$no_task_text.'</td></tr>';
	}



	echo "</table>";




}
?>