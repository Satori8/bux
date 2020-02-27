<?php
$pagetitle = "Баннеры 200x300";
include('header.php');
error_reporting (E_ALL);

echo '<div align="center">
	<a href="/banners_100x100.php" '.(stripos($_SERVER['PHP_SELF'], 'banners_100x100.php' ) !== false ? 'class="block1"' : false).'><b>Баннеры 100x100</b></a> | 
	<a href="/banners_200x300.php" '.(stripos($_SERVER['PHP_SELF'], 'banners_200x300.php' ) !== false ? 'class="block1"' : false).'><b>Баннеры 200x300</b></a> | 
	<a href="/banners_468x60.php"  '.(stripos($_SERVER['PHP_SELF'], 'banners_468x60.php' ) !== false ? 'class="block1"' : false).'><b>Баннеры 468x60</b></a> | 
	<a href="/banners_728x90.php"  '.(stripos($_SERVER['PHP_SELF'], 'banners_728x90.php' ) !== false ? 'class="block1"' : false).'><b>Баннеры 728x90</b></a>
</div><br><br>';

if(!defined("banners468x60_file")) {
	define("banners468x60_file", $_SERVER["DOCUMENT_ROOT"].'/cache/banners468x60.inc');
}

if(!isset($banners468x60_array) && is_file(banners468x60_file) ) {
	$banners468x60_array = @unserialize(file_get_contents(banners468x60_file));
	$banners_count = count($banners468x60_array);

	if($banners_count>0) {
		echo '<div align="center" style="padding-bottom:15px; font-weight:bold;">Баннеров в ротаторе: '.$banners_count.'</div>';

		echo '<table border="0" align="center" cellspacing="1" cellpadding="1">';
		for($i=0; $i<$banners_count; $i++){
			echo '<tr align="center">';
				echo '<td><a href="/redir_banners.php?id='.$banners468x60_array[$i]['id_b'].'" target="_blank"><img src="'.$banners468x60_array[$i]['urlbanner'].'" border="0" width="468" height="60" alt="" /></a></td>';
			echo '</tr>';
			echo '<tr align="center">';
				echo '<td>Переходов:&nbsp;&nbsp;'.$banners468x60_array[$i]['count_visit'].'<br>Показ заказан до: '.DATE("d.m.Y H:i:s", $banners468x60_array[$i]['datE']).'<br><br></td>';
			echo '</tr>';
		}
		echo '</table>';

		echo '<div align="center" style="padding-top:10px;margin-bottom:10px;"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">Разместить баннер</span></div>';
	}else{
		echo '<div align="center"><a href="/advertise.php?ads=banners"><img src="/img/banner468x60_free.png" width="468" height="60" alt="" /></a></div><br><br>';
		echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">Разместить баннер</span></div>';
	}
}else{
	echo '<div align="center"><a href="/advertise.php?ads=banners"><img src="/img/banner468x60_free.png" width="468" height="60" alt="" /></a></div><br><br>';
	echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">Разместить баннер</span></div>';
}

unset($banners468x60_array, $banners_count);

include('footer.php');?>