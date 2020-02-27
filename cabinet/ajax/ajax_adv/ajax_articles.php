<?php
error_reporting(E_ALL);
if(!DEFINED("ARTICLES_AJAX")) {die ("Hacking attempt!");}
if($type_ads!="articles") {die ("Hacking attempt!");}
require_once(DOC_ROOT."/bbcode/bbcode.lib.php");

if(function_exists('desc_bb')===false) {
	function desc_bb($desc) {
		$desc = new bbcode($desc);
		$desc = $desc->get_html();
		$desc = str_replace("&amp;", "&", $desc);
		return $desc;
	}
}

function count_text_art($count, $text1, $text2, $text3) {
	if($count>=0) {
		if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
			return "$count $text3";
		}else{
			switch(substr($count, -1, 1)){
				case 1: return "$count $text1"; break;
				case 2: case 3: case 4: return "$count $text2"; break;
				case 5: case 6: case 7: case 8: case 9: case 0: return "$count $text3"; break;
			}
		}
	}
}

function myErrorHandler($errno, $errstr, $errfile, $errline, $json_result) {
	switch ($errno) {
		case(1): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Fatal error[$errno]: $errstr in line $errline")); break;
		case(2): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Warning[$errno]: $errstr in line $errline")); break;
		case(8): $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "Notice[$errno]: $errstr in line $errline")); break;
		default: $json_result = array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", "[$errno] $errstr in line $errline")); break;
	}
	exit(json_encode_cp1251($json_result));
}
$set_error_handler = set_error_handler('myErrorHandler', E_ALL);

if($option == "delete") {
	$sql = mysql_query("SELECT `id`,`status` FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		mysql_query("DELETE FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");

		if($status==1) cache_articles();

		$json_result["result"] = "OK";
		exit(json_encode_cp1251($json_result));
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "get_up") {
	$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status==1) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_articles_up' AND `howmany`='1'");
			$cena_articles_up = number_format(mysql_result($sql,0,0), 2, ".", "");

			$message_text = "";
			$message_text.= '<div align="justify" style="margin:auto 10px;">';
				$message_text.= 'Вы можете разместить свою статью выше остальных в каталоге статей. Статья будет сохранять свою позицию до тех пор, пока другой пользователь не поднимет свою статью. ';
				$message_text.= 'В таком случае ваша статья будет опускаться ниже. В связи с этим, невозможно однозначно сказать, как долго ваша статья продержится на первых позициях.<br>';
				$message_text.= 'Стоимость поднятия составляет <b>'.number_format($cena_articles_up,2,".","").'</b> руб. Эта сумма будет снята с вашего рекламного счета.';
			$message_text.= '</div><br>';
			$message_text.= '<div align="center"><span onClick="pay_adv_up('.$row["id"].', \'articles\');" class="sub-red" style="float:none;" title="Поднять">Поднять</span></div>';

			$json_result["result"] = "OK";
			$json_result["message"] = iconv("CP1251", "UTF-8", $message_text);
			exit(json_encode_cp1251($json_result));
		}else{
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Статья не активна!");
			exit(json_encode_cp1251($json_result));
		}

	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}


}elseif($option == "pay_adv_up") {
	$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status==1) {
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_articles_up' AND `howmany`='1'");
			$cena_articles_up = number_format(mysql_result($sql,0,0), 2, ".", "");

			if($row["up_list"]>0) {
				$position = mysql_result(mysql_query("SELECT COUNT(*) FROM `tb_ads_articles` WHERE `status`='1' AND `up_list`>(SELECT `up_list` FROM `tb_ads_articles` WHERE `id`='$id')" ),0,0)+1;
			}else{
				$position = 0;
			}

			if($money_user_rb >= $cena_articles_up){
				if($position>1 | $position==0) {
					mysql_query("UPDATE `tb_ads_articles` SET `up_list`='".time()."' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());
					mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$cena_articles_up', `money_rek`=`money_rek`+'$cena_articles_up' WHERE `username`='$username'") or die(mysql_error());
					mysql_query("INSERT INTO `tb_history` (`user`,`date`,`amount`,`method`,`status`,`tipo`) 
					VALUES('$username','".DATE("d.m.Yг. H:i")."','$cena_articles_up','Поднятие статьи в списке каталога статей ID:$id','Списано','rashod')") or die(mysql_error());

					cache_articles();

					$json_result["result"] = "OK";
					exit(json_encode_cp1251($json_result));
				}else{
					$json_result["result"] = "ERROR";
					$json_result["message"] = iconv("CP1251", "UTF-8", "Ошибка! Статья уже на первой позиции!");
					exit(json_encode_cp1251($json_result));
				}
			}else{
				$json_result["result"] = "ERROR";
				$json_result["message"] = iconv("CP1251", "UTF-8", "На вашем счету недостаточно средств для поднятия статьи в списке!");
				exit(json_encode_cp1251($json_result));
			}
		}else{
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Статья не активна!");
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "clear_stat") {
	$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$views = $row["views"];

		if($views == 0) {
			exit("Счётчик этой площадки уже равен 0");
		}else{
			mysql_query("UPDATE `tb_ads_articles` SET `views`='0', `date_edit`='".time()."', `ip`='$laip'  WHERE `id`='$id' AND `username`='$username'");

			if($status==1) cache_articles();

			exit("OK");
		}
	}else{
		exit("У Вас нет рекламной площадки с ID - $id");
	}

}elseif($option == "get_info") {
       	$sql_id = mysql_query("SELECT `id`,`title`,`url`,`desc_min`,`desc_big`,`date`,`views` FROM `tb_ads_articles` WHERE `id`='$id'");
	if(mysql_num_rows($sql_id)>0) {
		$row_id = mysql_fetch_assoc($sql_id);
	        $art_id = $row_id["id"];
	        $art_title = $row_id["title"];
	        $art_url = $row_id["url"];
	        $art_date = $row_id["date"];
	        $art_views = $row_id["views"];
	        $art_desc_min = desc_bb($row_id["desc_min"]);
		$art_desc_big = desc_bb($row_id["desc_big"]);

		$message_text = "";
		$message_text.= '<div id="PreView" style="display:block; margin:5px; color: #333333; font-size:12px;">';
			$message_text.= '<div style="margin:0 auto 20px auto; padding: 8px 1px 0px 1px; text-align:justify; background-color:#F9F9F9; border-radius:5px 5px 10px 10px; box-shadow: 0 0 0 1px rgb(194, 192, 184) inset, 0 5px 0 -4px rgb(255, 255, 255), 0 5px 0 -3px rgb(194, 192, 184), 0 11px 0 -8px rgb(255, 255, 255), 0 11px 0 -7px rgb(194, 192, 184), 0 17px 0 -12px rgb(255, 255, 255), 0 17px 0 -11px rgb(194, 192, 184);">';
				$message_text.= '<div class="test-blank-title" style="width:100%; margin:0 auto; border-radius:0px; text-shadow: 1px 1px 1px #000;">'.$art_title.'</div>';
				$message_text.= '<div align="center" style="margin:7px auto 0px auto; font-size:13px; color:#828282; text-shadow: 1px 1px 2px #FFF;">Краткое содержание статьи</div>';
				$message_text.= '<div style="padding:8px 10px 15px 10px;">'.$art_desc_min.'</div>';
				$message_text.= '<div style="padding:0px 10px 15px 10px;">Ссылка на сайт: <a href="'.$art_url.'" target="_blank" class="golinktest">'.$art_url.'</a></div>';

				$message_text.= '<div style="padding:0px 10px 30px 10px;">';
					$message_text.= '<span class="art-calendar">дата создания '.DATE("d.m.Y в H:i", $art_date).'</span>';
					$message_text.= '<span class="art-eye">'.count_text_art(number_format($art_views, 0, ".", "`"), "просмотр", "просмотра", "просмотров").'</span>';
				$message_text.= '</div>';
			$message_text.= '</div>';

			$message_text.= '<div style="margin:0 auto 20px auto; padding: 8px 1px 0px 1px; text-align:justify; background-color:#F9F9F9; border-radius:5px 5px 10px 10px; box-shadow: 0 0 0 1px rgb(194, 192, 184) inset, 0 5px 0 -4px rgb(255, 255, 255), 0 5px 0 -3px rgb(194, 192, 184), 0 11px 0 -8px rgb(255, 255, 255), 0 11px 0 -7px rgb(194, 192, 184), 0 17px 0 -12px rgb(255, 255, 255), 0 17px 0 -11px rgb(194, 192, 184);">';
				$message_text.= '<div class="test-blank-title" style="width:100%; margin:0 auto; border-radius:0px; text-shadow: 1px 1px 1px #000;">'.$art_title.'</div>';
				$message_text.= '<div style="padding:8px 10px 15px 10px;">'.$art_desc_big.'</div>';
				$message_text.= '<div style="padding:0px 10px 15px 10px;">Ссылка на сайт: <a href="'.$art_url.'" target="_blank" class="golinktest">'.$art_url.'</a></div>';

				$message_text.= '<div style="padding:0px 10px 30px 10px;">';
					$message_text.= '<span class="art-calendar">дата создания '.DATE("d.m.Y в H:i", $art_date).'</span>';
					$message_text.= '<span class="art-eye">'.count_text_art(number_format($art_views, 0, ".", "`"), "просмотр", "просмотра", "просмотров").'</span>';
				$message_text.= '</div>';
			$message_text.= '</div>';

		$message_text.= '</div>';

		$json_result["result"] = "OK";
		$json_result["message"] = iconv("CP1251", "UTF-8", $message_text);
		exit(json_encode_cp1251($json_result));

	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "go_edit") {
	$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];

		if($status == 2) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Статья находится на модерации. Редактирование не доступно!");
			exit(json_encode_cp1251($json_result));
		}else{
			$json_result["result"] = "OK";
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}

}elseif($option == "Save") {
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]), 100) : false;
	$desc_min = isset($_POST["desc_min"]) ? limitatexto(limpiarez($_POST["desc_min"]), 1000) : false;
	$desc_min = get_magic_quotes_gpc() ? stripslashes($desc_min) : $desc_min;
	$desc_big = isset($_POST["desc_big"]) ? limitatexto(limpiarez($_POST["desc_big"]), 5000) : false;
	$desc_big = get_magic_quotes_gpc() ? stripslashes($desc_big) : $desc_big;
	$black_url = getHost($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

	if($title == false) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали заголовок статьи!");
		exit(json_encode_cp1251($json_result));

	}elseif(mysql_num_rows($sql_bl) > 0 && $black_url != false) {
		$row_bl = mysql_fetch_assoc($sql_bl);

		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Сайт ".$row_bl["domen"]." заблокирован и занесен в черный список проекта ".strtoupper($_SERVER["HTTP_HOST"])." Причина: ".$row_bl["cause"]."");
		exit(json_encode_cp1251($json_result));

	}elseif($url == false | $url == "http://" | $url == "https://") {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали URL-адрес сайта!");
		exit(json_encode_cp1251($json_result));

	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Вы неверно указали URL-адрес сайта!");
		exit(json_encode_cp1251($json_result));

	}elseif($desc_min == false) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали краткое описание статьи!");
		exit(json_encode_cp1251($json_result));

	}elseif(strlen($desc_min) < 50) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "В кратком описание статьи должно быть не менее 50 символов!");
		exit(json_encode_cp1251($json_result));

	}elseif($desc_big == false) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "Вы не указали описание статьи!");
		exit(json_encode_cp1251($json_result));

	}elseif(strlen($desc_big) < 100) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "В описание статьи должно быть не менее 100 символов!");
		exit(json_encode_cp1251($json_result));

	}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", SFB_YANDEX($url));
		exit(json_encode_cp1251($json_result));

	}else{
		$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id'") or die(mysql_error());
		if(mysql_num_rows($sql)>0) {
			$row = mysql_fetch_assoc($sql);
			$status = $row["status"];

			if($status == 2) {
				$json_result["result"] = "ERROR";
				$json_result["message"] = iconv("CP1251", "UTF-8", "Статья находится на модерации. Редактирование не доступно!");
				exit(json_encode_cp1251($json_result));

			}elseif($status == 0) {
				mysql_query("UPDATE `tb_ads_articles` SET `date_edit`='".time()."',`title`='$title',`url`='$url',`desc_min`='$desc_min',`desc_big`='$desc_big' WHERE `id`='$id'") or die(mysql_error());

				$json_result["result"] = "OK";
				exit(json_encode_cp1251($json_result));

			}elseif($status == 1 | $status == 4) {
				mysql_query("UPDATE `tb_ads_articles` SET `status`='2',`date_edit`='".time()."',`title_h`='".$row["title"]."',`url_h`='".$row["url"]."',`desc_min_h`='".$row["desc_min"]."',`desc_big_h`='".$row["desc_big"]."' WHERE `id`='$id' AND (`title`!='$title' OR `url`!='$url' OR `desc_min`!='$desc_min' OR `desc_big`!='$desc_big')") or die(mysql_error());

				mysql_query("UPDATE `tb_ads_articles` SET `title`='$title',`url`='$url',`desc_min`='$desc_min',`desc_big`='$desc_big' WHERE `id`='$id'") or die(mysql_error());

				cache_articles();

				$json_result["result"] = "OK";
				exit(json_encode_cp1251($json_result));
			}else{
				$json_result["result"] = "ERROR";
				$json_result["message"] = iconv("CP1251", "UTF-8", "Статья находится на модерации. Редактирование не доступно!");
				exit(json_encode_cp1251($json_result));
			}
		}else{
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
			exit(json_encode_cp1251($json_result));
		}
	}

}elseif($option == "get_add_money") {
	$sql = mysql_query("SELECT * FROM `tb_ads_articles` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);
		$status = $row["status"];
		$merch_tran_id = $row["merch_tran_id"];

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_articles' AND `howmany`='1'");
		$cena_articles = number_format(mysql_result($sql,0,0), 2, ".", "");

		$money_pay = number_format(($cena_articles * (100-$cab_skidka)/100), 2, ".", "");

		if($money_user_rb < $money_pay) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "На Вашем рекламном счету недостаточно средств!");
			exit(json_encode_cp1251($json_result));

		}elseif($status != 0) {
			$json_result["result"] = "ERROR";
			$json_result["message"] = iconv("CP1251", "UTF-8", "Рекламная площадка уже была оплачены!");
			exit(json_encode_cp1251($json_result));

		}else{
			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
			$reit_rek = mysql_result($sql,0,0);
			$reit_add_1 = floor(bcdiv($money_pay, 10)) * $reit_rek;

			$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
			$reit_ref_rek = mysql_result($sql,0,0);
			$reit_add_2 = floor(bcdiv($money_pay, 10)) * $reit_ref_rek;

			if($my_referer_1!=false) {mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_2' WHERE `username`='$my_referer_1'");}

			mysql_query("UPDATE `tb_users` SET `reiting`=`reiting`+'$reit_add_1', `money_rb`=`money_rb`-'$money_pay', `money_rek`=`money_rek`+'$money_pay'  WHERE `username`='$username'");
			mysql_query("UPDATE `tb_ads_articles` SET `status`='2', `date_edit`='".time()."', `ip`='$laip' WHERE `id`='$id' AND `username`='$username'") or die(mysql_error());

			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`wmr`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$username','$user_id','$wmr_user','".DATE("d.m.Y H:i")."','".time()."','$money_pay','Оплата рекламы (Каталог статей, ID:$id)', 'Оплачено', 'reklama')") or die(json_encode_cp1251(array("result" => "ERROR", "message" => iconv("CP1251", "UTF-8", mysql_error()))));

			stat_pay("articles", $money_pay);
			ads_wmid($wmid_user, $wmr_user, $username, $money_pay);
			konkurs_ads_new($wmid_user, $username, $money_pay);
			invest_stat($money_pay, 4);
			ActionRef(number_format($money_pay,2,".",""), $username);

			$json_result["result"] = "OK";
			exit(json_encode_cp1251($json_result));
		}
	}else{
		$json_result["result"] = "ERROR";
		$json_result["message"] = iconv("CP1251", "UTF-8", "У Вас нет рекламной площадки с ID - $id");
		exit(json_encode_cp1251($json_result));
	}


}else{
	$json_result["result"] = "ERROR";
	$json_result["message"] = iconv("CP1251", "UTF-8", "ERROR! NO OPTION!");
	exit(json_encode_cp1251($json_result));
}

?>