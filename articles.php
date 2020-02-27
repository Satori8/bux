<?php
$pagetitle = "Каталог статей";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");
include_once(ROOT_DIR."/navigator/navigator.php");
require_once(ROOT_DIR."/merchant/func_cache.php");

$articles_file = ROOT_DIR."/cache/cache_articles.inc";
$articles_arr = is_file($articles_file) ? @unserialize(file_get_contents($articles_file)) : false;

if(function_exists('count_text')===false) {
	function count_text($count, $text1, $text2, $text3) {
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
}

if(is_array($articles_arr) && count($articles_arr)>0 && !isset($_GET["id"])) {
	$page = ( isset($_GET["page"]) && preg_match("|^[\d]{1,11}$|", limpiar($_GET["page"])) ) ? limpiar($_GET["page"]) : 1;
	$perpage = 10;
	$count = count($articles_arr);
	$pages_count = ceil($count / $perpage);

	if($page > $pages_count) $page = $pages_count;
	if($page <= 0) $page = 1;

	$start_pos = intval(($page-1) * $perpage);
	$articles_arr = array_slice($articles_arr, $start_pos, $perpage, false);
	$count_articles = count($articles_arr);

	if($count>$perpage) universal_link_bar($count, $page, ($_SERVER["REDIRECT_URL"] ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"]), $perpage, 10, "?page=", "");

	for($i=0; $i<$count_articles && $count_articles>0; $i++) {
		echo '<div style="margin:0 auto 30px auto; padding: 8px 1px 0px 1px; background-color:#F9F9F9; border-radius:5px 5px 10px 10px; box-shadow: 0 0 0 1px rgb(194, 192, 184) inset, 0 5px 0 -4px rgb(255, 255, 255), 0 5px 0 -3px rgb(194, 192, 184), 0 11px 0 -8px rgb(255, 255, 255), 0 11px 0 -7px rgb(194, 192, 184), 0 17px 0 -12px rgb(255, 255, 255), 0 17px 0 -11px rgb(194, 192, 184);">';
			echo '<div class="test-blank-title" style="width:100%; margin:0 auto; border-radius:0px; text-shadow: 1px 1px 1px #000;">'.$articles_arr[$i]["art_title"].'</div>';
			echo '<div align="center" style="margin:7px auto 0px auto; font-size:13px; color:#828282; text-shadow: 1px 1px 2px #FFF;">Краткое содержание статьи</div>';
			echo '<div style="padding:8px 10px 15px 10px;">'.$articles_arr[$i]["art_desc_min"].'</div>';
			echo '<div style="padding:0px 10px 15px 10px;">Ссылка на сайт: <a href="'.$articles_arr[$i]["art_url"].'" target="_blank" class="golinktest">'.$articles_arr[$i]["art_url"].'</a></div>';

			echo '<div style="padding:0px 10px 20px 10px;">';
				echo '<span class="art-calendar">дата создания '.DATE("d.m.Y в H:i", $articles_arr[$i]["art_date"]).'</span>';
				echo '<span class="art-eye">'.count_text(number_format($articles_arr[$i]["art_views"], 0, ".", "`"), "просмотр", "просмотра", "просмотров").'</span>';
			echo '</div>';

			echo '<div style="padding:0px 10px 5px 10px; text-align:left;"><a href="'.($_SERVER["REDIRECT_URL"] ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"]).'?id='.$articles_arr[$i]["art_id"].'" class="articles_sub">Читать далее</a></div>';

		echo '</div>';
	}

	if($count>$perpage) universal_link_bar($count, $page, ($_SERVER["REDIRECT_URL"] ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"]), $perpage, 10, "?page=", "");

}elseif(is_array($articles_arr) && count($articles_arr)>0 && isset($_GET["id"])) {
	$id = ( isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", limpiar($_GET["id"])) ) ? intval(limpiar($_GET["id"])) : false;

	if(isset($articles_arr[$id])) {
		if(!isset($_SESSION["articles"]["$id"])) {
			$_SESSION["articles"]["$id"] = 1;

				if(mysql_query("UPDATE `tb_ads_articles` SET `views`=`views`+'1' WHERE `id`='$id'")) {
					cache_articles();
				}
		}

		echo '<div style="margin:0 auto 30px auto; padding: 8px 1px 0px 1px; background-color:#F9F9F9; border-radius:5px 5px 10px 10px; box-shadow: 0 0 0 1px rgb(194, 192, 184) inset, 0 5px 0 -4px rgb(255, 255, 255), 0 5px 0 -3px rgb(194, 192, 184), 0 11px 0 -8px rgb(255, 255, 255), 0 11px 0 -7px rgb(194, 192, 184), 0 17px 0 -12px rgb(255, 255, 255), 0 17px 0 -11px rgb(194, 192, 184);">';
			echo '<div class="test-blank-title" style="width:100%; margin:0 auto; border-radius:0px; text-shadow: 1px 1px 1px #000;">'.$articles_arr[$id]["art_title"].'</div>';
			echo '<div style="padding:8px 10px 15px 10px;">'.$articles_arr[$id]["art_desc_big"].'</div>';
			echo '<div style="padding:0px 10px 15px 10px;">Ссылка на сайт: <a href="'.$articles_arr[$id]["art_url"].'" target="_blank" class="golinktest">'.$articles_arr[$id]["art_url"].'</a></div>';

			echo '<div style="padding:0px 10px 30px 10px;">';
				echo '<span class="art-calendar">дата создания '.DATE("d.m.Y в H:i", $articles_arr[$id]["art_date"]).'</span>';
				echo '<span class="art-eye">'.count_text(number_format($articles_arr[$id]["art_views"], 0, ".", "`"), "просмотр", "просмотра", "просмотров").'</span>';
			echo '</div>';
		echo '</div>';

		echo '<div style="padding:0px 10px 5px 10px; text-align:center;"><a href="'.($_SERVER["REDIRECT_URL"] ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"]).'" class="articles_sub">Перейти к списку статей</a></div>';

	}else{
		echo '<span class="msg-error">Статья не найдена!</span>';
	}
}else{

}

echo '<div style="padding:0px 0px 15px 0px; text-align:center;"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=articles\'">Разместить статью</span></div>';

include(ROOT_DIR."/footer.php");
?>