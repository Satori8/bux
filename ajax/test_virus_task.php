<?php
header("Content-type: text/html; charset=windows-1251");

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	require_once("".$_SERVER['DOCUMENT_ROOT']."/config.php");

	$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", intval($_POST["id"]))) ? intval($_POST["id"]) : false;
	$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", trim($_POST["op"])) ) ? htmlspecialchars(trim($_POST["op"])) : false;

	if($id!=false && $option!=false) {
		if($option=="task") {
			$TABLES = "tb_ads_task";
		}else{
			exit("Ошибка!");
		}

		$sql_links = mysql_query("SELECT `zdurl` FROM `".$TABLES."` WHERE `id`='$id' AND `status`='pay'");
		if(mysql_num_rows($sql_links)>0) {
			$links_row = mysql_fetch_row($sql_links);
			$url_ban = $links_row["0"];
			$url_ban = "http://online.us.drweb.com/result/?url=$url_ban";

			$url_ban = file_get_contents("$url_ban");
			$pos = strpos($url_ban, "INFECTED");
			$pos2 = strpos($url_ban, "ERROR");

			//echo "P1-$pos, P2-$pos2";
			//if($pos2==true) echo "ZZZ";

			if ($pos === false && $pos2 === false) {
				echo "Вирусы не обнаружены";
			}elseif ($pos === false && $pos2 == true) {
				echo "Внимание!\nНе удалось проверить сайт, возможно сайт не доступен либо редирект!";
			}else{
				echo "Внимание!\nОбнаружены вирусы!";
			}
		}else{
			echo "Ошибка!\nСсылка не существует!";
		}
	}else{
		echo "Ошибка!\nСсылка не существует!";
	}
}else{
	echo "Ошибка!\n Не удалось обработать запрос!";
}

?>