<?php
$pagetitle = "Баннеры 728x90";
include('header.php');
error_reporting (E_ALL);

echo '<div align="center"><a href="/banners_100x100.php"><b>Баннеры 100x100</b></a> | <a href="/banners_200x300.php"><b>Баннеры 200x300</b></a>  | <a href="/banners_468x60.php"><b>Баннеры 468x60</b></a> | <a href="'.$_SERVER["PHP_SELF"].'" class="block1"><b>Баннеры 728x90</b></a></div><br /><br />';

if(!defined("banners728x90_file")) {
	define("banners728x90_file", $_SERVER["DOCUMENT_ROOT"].'/cache/banners728x90.inc');
}

if(!isset($banners728x90_array) && is_file(banners728x90_file) ) {
	$banners728x90_array = @unserialize(file_get_contents(banners728x90_file));
	$cnt = count($banners728x90_array);

	if($cnt>0) {
		echo '<table border="0" align="center" cellspacing="1" cellpadding="1">';
		for($i=0; $i<$cnt; $i++){
                        
			echo '<tr>';
				echo '<div align="center" style="margin-left: -11px;"><a href="/redir_banners.php?id='.$banners728x90_array[$i]['id_b'].'" target="_blank"><img src="'.$banners728x90_array[$i]['urlbanner'].'" border="0" width="728" height="90" alt="" /></a></div>';
			echo '</tr>';
                        
			echo '<tr>';
				echo '<div align="center">Переходов:&nbsp;&nbsp;'.$banners728x90_array[$i]['count_visit'].'<br>Показ заказан до: '.DATE("d.m.Y H:i:s", $banners728x90_array[$i]['datE']).'<br><br></div>';
				//echo '</div>';
			echo '</tr>';
		}
		echo '</table><br>';
		echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">Разместить баннер</spaan></div>';
	}else{
		echo '<div align="center" style="margin-left: -11px;"><a href="/advertise.php?ads=banners"><img src="/img/banner728x90_free.png" width="728" height="90" alt="" /></a></div><br><br>';
		echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">Разместить баннер</span></div>';
	}
}else{
	echo '<div align="center" style="margin-left: -11px;"><a href="/advertise.php?ads=banners"><img src="/img/banner728x90_free.png" width="728" height="90" alt="" /></a></div><br><br>';
	echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">Разместить баннер</spaan></div>';
}

unset($banners728x90_array, $cnt);

include('footer.php');?>