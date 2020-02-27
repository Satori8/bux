<?php
require_once('.zsecurity.php');
$pagetitle="Блокнот (Ваши заметки)";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	?><script type="text/javascript" language="JavaScript"> 
		function gebi(id){
			return document.getElementById(id)
		}

		function descchange() {
			var desc = gebi('desc').value;
			if(desc.length > 1000) {
				gebi('desc').value = desc.substr(0,1000);
			}
			gebi('count').innerHTML = 'Осталось <b>'+(1000-desc.length)+'</b> символов';
		}

		function addtag(text1, text2) {
			if ((document.selection)){
				document.forumform.desc.focus();
				document.forumform.document.selection.createRange().text = text1+document.forumform.document.selection.createRange().text+text2;
			} else if(document.forumform.desc.selectionStart != undefined) {
				var element = document.forumform.desc;
				var str = element.value;
				var start = element.selectionStart;
				var length = element.selectionEnd - element.selectionStart;
				element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
			} else document.forumform.desc.value += text1+text2;
		}
	</script><?php

	function limpiarez($mensaje){
		$mensaje = htmlspecialchars(trim($mensaje), NULL, "cp1251");
		$mensaje = str_replace("?","&#063;",$mensaje);
		$mensaje = str_replace(">","&#062;",$mensaje);
		$mensaje = str_replace("<","&#060;",$mensaje);
		$mensaje = str_replace("'","&#039;",$mensaje);
		$mensaje = str_replace("`","&#096;",$mensaje);
		$mensaje = str_replace("$","&#036;",$mensaje);
		$mensaje = str_replace('"',"&#034;",$mensaje);
		$mensaje = str_replace("  "," ",$mensaje);
		$mensaje = str_replace("&amp amp ","&",$mensaje);
		$mensaje = str_replace("&amp;amp;","&amp;",$mensaje);
		$mensaje = str_replace("&&","&",$mensaje);
		$mensaje = str_replace("http://http://","http://",$mensaje);
		$mensaje = str_replace("https://https://","https://",$mensaje);
		return $mensaje;
	}

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Zа-яА-Я0-9\-_-]{3,255}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

	if($username == false) {
		echo '<span class="msg-error">Логин пользователя не определен!</span>';
	}else{
		$sql_c = mysql_query("SELECT `id` FROM `tb_notebook_z` WHERE `username`='$username'");
		if(mysql_num_rows($sql_c)==0) {
			for($i=1; $i<=10; $i++) {
				mysql_query("INSERT INTO `tb_notebook_z` (`ident`,`sort`,`username`,`title`,`date`,`count`) 
				VALUES ('$i', '".($i-1)."', '$username', '', '".time()."', '0')") or die(mysql_error());
			}
		}

		if(!isset($_GET["op"])) {

			echo '<table align="center">';
				echo '<tr>';
					echo '<td><form action="'.$_SERVER["PHP_SELF"].'" method="GET" id="newform"><input type="hidden" name="op" value="add" /><input type="submit" value="Создать новую заметку" class="proc-btn" /></form></td>';
					echo '<td><form action="'.$_SERVER["PHP_SELF"].'" method="GET" id="newform"><input type="hidden" name="op" value="config" /><input type="submit" value="Настройки блокнота" class="proc-btn" /></form></td>';
				echo '<tr>';
			echo '</table><br>';

		}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="add") {

			if(count($_POST)>0) {
				$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),50) : false;
				$description = (isset($_POST["description"])) ? limitatexto(trim($_POST["description"]),1000) : false;
				$book_mark = ( isset($_POST["book_mark"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["book_mark"])) ) ? intval(limpiarez(trim($_POST["book_mark"]))) : false;
				if (get_magic_quotes_gpc()) {$description = stripslashes($description);}

				$sql_check = mysql_query("SELECT `id` FROM `tb_notebook` WHERE `username`='$username' AND `title`='$title' AND `description`='$description'");

				if($title==false) {
					echo '<span class="msg-error">Ошибка! Не заполнено поле Название закладки.</span><br>';
				}elseif($description==false) {
					echo '<span class="msg-error">Ошибка! Не заполнено поле текст закладки.</span><br>';
				}elseif(mysql_num_rows($sql_check)>0) {
					echo '<span class="msg-error">Ошибка! Заметка с таким названием и содержанием уже создана.</span><br>';
				}else{
					mysql_query("INSERT INTO `tb_notebook` (`ident_z`,`username`,`title`,`description`,`date`) 
					VALUES ('$book_mark','$username','$title','$description','".time()."')") or die(mysql_error());

					mysql_query("UPDATE `tb_notebook_z` SET  `count`=`count`+'1' WHERE `id`='$book_mark' AND `username`='$username'") or die(mysql_error());

					echo '<span class="msg-ok">Заметка успешно создана.</span><br>';

					echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="POST" id="newform"><input type="submit" name="Submit" value="Перейти к списку заметок" class="proc-btn" /></form></div><br>';

					include('footer.php');
					exit();
				}
			}

			echo '<form id="newform" action="" method="POST" name="forumform">';
			echo '<table class="tables">';
			echo '<thead><tr><th class="top" colspan="2">Ваша заметка</th></tr></thead>';
			echo '<tbody>';
				echo '<tr><td width="100"><b>Название:</b></td><td><input type="text" name="title" maxlength="50" value="" class="ok" /></td></tr>';
				echo '<tr>';
					echo '<td><b>Закладка:</b></td>';
					echo '<td>';
						$sql_z = mysql_query("SELECT * FROM `tb_notebook_z` WHERE `username`='$username' AND `title`!='' ORDER BY `sort` ASC");
						if(mysql_num_rows($sql_z)>0) {
							echo '<select name="book_mark">';
								while ($row_z = mysql_fetch_array($sql_z)) {
									echo '<option value="'.$row_z["id"].'">'.$row_z["title"].' ('.$row_z["count"].')</option>';
								}
							echo '</select>';
						}
					echo '</td>';
				echo '</tr>';
				echo '<tr><td colspan="2">';
					echo '<b>Содержание закладки &darr;</b>';
					echo '<span class="bbc-url" style="float:right" title="Выделить URL" onClick="javascript:addtag(\'[url]\',\'[/url]\'); return false;">URL</span>';
					echo '<span class="bbc-uline" style="float:right" title="Выделить подчёркиванием" onClick="javascript:addtag(\'[u]\',\'[/u]\'); return false;">U</span>';
					echo '<span class="bbc-italic" style="float:right" title="Выделить курсивом" onClick="javascript:addtag(\'[i]\',\'[/i]\'); return false;">i</span>';
					echo '<span class="bbc-bold" style="float:right" title="Выделить жирным" onClick="javascript:addtag(\'[b]\',\'[/b]\'); return false;">B</span>';
					echo '<textarea name="description" id="desc" style="width:99%; height:200px;" onChange="descchange();" onKeyUp="descchange();"></textarea>';
					echo '<div align="right" id="count" style="color:#696969;"></div>';
				echo '</td></tr>';
				echo '<tr><td colspan="2" align="center"><input type="submit" value="Сохранить" class="proc-btn" style="float:none;" /></td></tr>';
			echo '</tbody>';
			echo '</table>';
			echo '</form>';

			?><script language="JavaScript">descchange();</script><?php
			include('footer.php');
			exit();

		}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="config") {
			echo 'Вы можете создать до 10 закладок для своих заметок на сайте.<br><br>';

			if(count($_POST)>0) {
				for($i=1; $i<=10; $i++) {
					$idz[$i] = ( isset($_POST["idz_$i"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["idz_$i"])) ) ? intval(limpiarez(trim($_POST["idz_$i"]))) : false;
					$sort[$i] = ( isset($_POST["sort_$i"]) && preg_match("|^[\d]{1,2}$|", trim($_POST["sort_$i"])) ) ? intval(limpiarez(trim($_POST["sort_$i"]))) : "0";
					$title[$i] = (isset($_POST["title_$i"])) ? limitatexto(limpiarez($_POST["title_$i"]),20) : false;

					if($idz[$i]!=false) mysql_query("UPDATE `tb_notebook_z` SET  `sort`='$sort[$i]',`title`='$title[$i]' WHERE `ident`='$idz[$i]' AND `username`='$username'") or die(mysql_error());
				}
				echo '<span class="msg-ok">Настройки блокнота успешно сохранены!</span>';

				echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="POST"><input type="submit" name="Submit" value="Перейти к списку заметок" class="proc-btn" /></form></div><br>';
			}

			echo '<form id="newform" action="" method="POST">';
			echo '<table class="tables">';
			echo '<thead><tr><th class="top" width="80">Сортировка</th><th class="top">Название закладки</th></tr></thead>';
	
				$sql = mysql_query("SELECT * FROM `tb_notebook_z` WHERE `username`='$username' ORDER BY `sort` ASC");
				if(mysql_num_rows($sql)>0) {
					while ($row = mysql_fetch_array($sql)) {
						echo '<tr>';
							echo '<td align="center">';
								echo '<input type="hidden" name="idz_'.$row["ident"].'" value="'.$row["ident"].'" />';
								echo '<input type="text" name="sort_'.$row["ident"].'" maxlength="2" value="'.$row["sort"].'" class="ok" style="text-align:center" />';
							echo '</td>';
							echo '<td align="left"><input type="text" name="title_'.$row["ident"].'" maxlength="20" value="'.$row["title"].'" class="ok" /></td>';
						echo '<tr>';
					}
					echo '<tr>';
						echo '<td colspan="2" align="center"><input type="submit" value="Сохранить" class="proc-btn" style="float:none;" /></td>';
					echo '</tr>';
				}

			echo '</table>';
			echo '</form>';

			include('footer.php');
			exit();

		}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="dell") {
			$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiarez(trim($_GET["id"]))) : false;

			$sql = mysql_query("SELECT `ident_z` FROM `tb_notebook` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_row($sql);

				mysql_query("DELETE FROM `tb_notebook` WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

				mysql_query("UPDATE `tb_notebook_z` SET `count`=`count`-'1' WHERE `id`='".$row["0"]."' AND `username`='$username'") or die(mysql_error());

				echo '<span class="msg-ok">Заметка успешно удалена!</span>';
			}else{
				echo '<span class="msg-error">Ошибка! У вас нет такой заметки.</span>';
			}

			echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="POST"><input type="submit" name="Submit" value="Перейти к списку заметок" class="proc-btn" /></form></div><br>';

			include('footer.php');
			exit();

		}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="edit") {
			$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiarez(trim($_GET["id"]))) : false;

			if(count($_POST)>0) {
				$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),50) : false;
				$description = (isset($_POST["description"])) ? limitatexto(trim($_POST["description"]),1000) : false;
				$book_mark = ( isset($_POST["book_mark"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["book_mark"])) ) ? intval(limpiarez(trim($_POST["book_mark"]))) : false;
				if (get_magic_quotes_gpc()) {$description = stripslashes($description);}

				if($title==false) {
					echo '<span class="msg-error">Ошибка! Не заполнено поле Название закладки.</span><br>';
				}elseif($description==false) {
					echo '<span class="msg-error">Ошибка! Не заполнено поле текст закладки.</span><br>';
				}else{
					$sql = mysql_query("SELECT * FROM `tb_notebook` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC");
					if(mysql_num_rows($sql)>0) {
						$row = mysql_fetch_array($sql);

						if($book_mark!=$row["ident_z"]) {
							mysql_query("UPDATE `tb_notebook_z` SET  `count`=`count`-'1' WHERE `id`='".$row["ident_z"]."' AND `username`='$username'") or die(mysql_error());
							mysql_query("UPDATE `tb_notebook_z` SET  `count`=`count`+'1' WHERE `id`='$book_mark' AND `username`='$username'") or die(mysql_error());
						}

						mysql_query("UPDATE `tb_notebook` SET `title`='$title',`description`='$description',`ident_z`='$book_mark' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

						echo '<span class="msg-ok">Заметка успешно изменена.</span><br>';
					}else{
						echo '<span class="msg-error">Ошибка! У вас нет такой заметки.</span>';
					}

				}

				echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="POST"><input type="submit" name="Submit" value="Перейти к списку заметок" class="proc-btn" /></form></div><br>';
				?><script language="JavaScript">descchange();</script><?php
				include('footer.php');
				exit();
			}


			$sql = mysql_query("SELECT * FROM `tb_notebook` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);

				echo '<form id="newform" action="" method="POST" name="forumform">';
				echo '<table class="tables">';
				echo '<thead><tr><th class="top" colspan="2">Ваша заметка</th></tr></thead>';
				echo '<tbody>';
					echo '<tr><td width="100"><b>Название:</b></td><td><input type="text" name="title" maxlength="50" value="'.$row["title"].'" class="ok" /></td></tr>';
					echo '<tr>';
						echo '<td><b>Закладка:</b></td>';
						echo '<td>';
							$sql_z = mysql_query("SELECT * FROM `tb_notebook_z` WHERE `username`='$username' AND `title`!='' ORDER BY `sort` ASC");
							if(mysql_num_rows($sql_z)>0) {
								echo '<select name="book_mark">';
									while ($row_z = mysql_fetch_array($sql_z)) {
										echo '<option value="'.$row_z["id"].'" '.($row["ident_z"] == $row_z["id"] ? 'selected="selected"' : false).'>'.$row_z["title"].' ('.$row_z["count"].')</option>';
									}
								echo '</select>';
							}
						echo '</td>';
					echo '</tr>';
					echo '<tr><td colspan="2">';
						echo '<b>Содержание закладки &darr;</b>';
						echo '<span class="bbc-url" style="float:right" title="Выделить URL" onClick="javascript:addtag(\'[url]\',\'[/url]\'); return false;">URL</span>';
						echo '<span class="bbc-uline" style="float:right" title="Выделить подчёркиванием" onClick="javascript:addtag(\'[u]\',\'[/u]\'); return false;">U</span>';
						echo '<span class="bbc-italic" style="float:right" title="Выделить курсивом" onClick="javascript:addtag(\'[i]\',\'[/i]\'); return false;">i</span>';
						echo '<span class="bbc-bold" style="float:right" title="Выделить жирным" onClick="javascript:addtag(\'[b]\',\'[/b]\'); return false;">B</span>';
						echo '<textarea name="description" id="desc" style="width:99%; height:200px;" onChange="descchange();" onKeyUp="descchange();">'.$row["description"].'</textarea>';
						echo '<div align="right" id="count" style="color:#696969;"></div>';
					echo '</td></tr>';
					echo '<tr><td colspan="2" align="center"><input type="submit" value="Сохранить" class="proc-btn" style="float:none;" /></td></tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</form>';

			}else{
				echo '<span class="msg-error">Ошибка! У вас нет такой заметки.</span>';
				echo '<div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="POST"><input type="submit" name="Submit" value="Перейти к списку заметок" class="proc-btn" /></form></div><br>';
			}

			?><script language="JavaScript">descchange();</script><?php
			include('footer.php');
			exit();

		}elseif(isset($_GET["op"]) && limpiarez($_GET["op"])=="read") {
			require_once("bbcode/bbcode.lib.php");

			$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"])) ) ? intval(limpiarez(trim($_GET["id"]))) : false;

			$sql = mysql_query("SELECT * FROM `tb_notebook` WHERE `id`='$id' AND `username`='$username' ORDER BY `id` DESC");
			if(mysql_num_rows($sql)>0) {
				$row = mysql_fetch_array($sql);

				echo '<form id="newform" action="" method="POST" name="forumform">';
				echo '<table class="tables">';
				echo '<thead><tr><th class="top" colspan="2">Ваша заметка</th></tr></thead>';
				echo '<tbody>';
					echo '<tr><td width="100"><b>Название:</b></td><td>'.$row["title"].'</td></tr>';
					echo '<tr>';
						echo '<td><b>Закладка:</b></td>';
						echo '<td>';
							$sql_z = mysql_query("SELECT * FROM `tb_notebook_z` WHERE `id`='".$row["ident_z"]."' AND `username`='$username' AND `title`!='' ORDER BY `sort` ASC");
							if(mysql_num_rows($sql_z)>0) {
								$row_z = mysql_fetch_array($sql_z);
								echo $row_z["title"];
							}
						echo '</td>';
					echo '</tr>';
					echo '<tr><td colspan="2">';
						echo '<b>Содержание закладки &darr;</b><br>';
						$description = new bbcode($row["description"]);
						$description = $description->get_html();
						$description = str_replace("&amp;", "&", $description);
						echo "$description";
					echo '</td></tr>';
				echo '</tbody>';
				echo '</table>';
				echo '</form>';

			}else{
				echo '<span class="msg-error">Ошибка! У вас нет такой заметки.</span>';
			}

			echo '<br><div align="center"><form action="'.$_SERVER["PHP_SELF"].'" method="POST"><input type="submit" name="Submit" value="Перейти к списку заметок" class="proc-btn" /></form></div><br>';

			include('footer.php');
			exit();

		}else{
		}

		$sql_all = mysql_query("SELECT `id` FROM `tb_notebook` WHERE `username`='$username'");
		$all_z = mysql_num_rows($sql_all);

		$sql_z = mysql_query("SELECT * FROM `tb_notebook_z` WHERE `username`='$username' AND `title`!='' ORDER BY `sort` ASC");
		if(mysql_num_rows($sql_z)>0) {
			$bm = ( isset($_GET["bm"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["bm"])) ) ? intval(limpiarez(trim($_GET["bm"]))) : false;

			$y=0;
			while ($row_z = mysql_fetch_array($sql_z)) {
				$y++; 
				if($bm==$row_z["id"]) {
					echo ' <a href="'.$_SERVER["PHP_SELF"].'?bm='.$row_z["id"].'" class="blocks2">'.str_replace(" ", "&nbsp;", $row_z["title"]).'(<b>'.$row_z["count"].'</b>)</a>';
				}else{
					echo ' <a href="'.$_SERVER["PHP_SELF"].'?bm='.$row_z["id"].'">'.str_replace(" ", "&nbsp;", $row_z["title"]).'(<b>'.$row_z["count"].'</b>)</a>';
				}
				if($y>0) echo ' <b>/</b>';
				//echo '<option value="'.$row_z["id"].'" '.($row["ident_z"] == $row_z["id"] ? 'selected="selected"' : false).'>'.$row_z["title"].' ('.$row_z["count"].')</option>';
			}
			if($bm!=false) {
				$WHERE_TAB = "`ident_z`='$bm' AND ";
				echo ' <a href="'.$_SERVER["PHP_SELF"].'">Все(<b>'.$all_z.'</b>)</a>';
			}else{ 
				$WHERE_TAB = "";
				echo ' <a href="'.$_SERVER["PHP_SELF"].'" class="blocks2">Все(<b>'.$all_z.'</b>)</a>';
			}

		}else{
			$WHERE_TAB = "";
		}

		echo '<table class="tables">';
		echo '<thead><tr>';
			echo '<th class="top" width="20">#</th>';
			echo '<th class="top" width="115">Дата</th>';
			echo '<th class="top">Название</th>';
			echo '<th class="top" colspan="2">Действие</th>';
		echo '</tr></thead>';
	
		$sql = mysql_query("SELECT * FROM `tb_notebook` WHERE $WHERE_TAB `username`='$username' ORDER BY `id` DESC");
		if(mysql_num_rows($sql)>0) {
			while ($row = mysql_fetch_array($sql)) {
				echo '<tr>';
					echo '<td align="center" nowrap="nowrap">'.$row["id"].'</td>';
					echo '<td align="center" nowrap="nowrap">'.DATE("d.m.Y H:i",$row["date"]).'</td>';
					echo '<td align="left"><a href="'.$_SERVER["PHP_SELF"].'?op=read&id='.$row["id"].'">'.$row["title"].'</a></td>';

					echo '<td align="center" width="20">';
						echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'">';
							echo '<input type="hidden" name="op" value="edit">';
							echo '<input type="hidden" name="id" value="'.$row["id"].'">';
							echo '<input type="image" src="forum/style/img/f-posts.jpg" title="Редактировать">';
						echo '</form>';
					echo '</td>';

					echo '<td align="center" width="20">';
						echo '<form method="GET" action="'.$_SERVER["PHP_SELF"].'" onClick=\'if(!confirm("Вы уверены что хотите удалить заметку #'.$row["id"].' ?")) return false;\'>';
							echo '<input type="hidden" name="op" value="dell">';
							echo '<input type="hidden" name="id" value="'.$row["id"].'">';
							echo '<input type="image" src="img/close.png" title="Удалить">';
						echo '</form>';
					echo '</td>';
				echo '<tr>';
			}
		}else{
			echo '<tr><td align="center" colspan="5">Список заметок пуст</td></tr>';
		}
		echo '</table>';

	}
}

?><script language="JavaScript">descchange();</script><?php

include('footer.php');?>