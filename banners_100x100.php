<?php
$pagetitle = "������� 100x100";
include('header.php');
error_reporting (E_ALL);

echo '<div align="center">
	<a href="/banners_100x100.php" '.(stripos($_SERVER['PHP_SELF'], 'banners_100x100.php' ) !== false ? 'class="block1"' : false).'><b>������� 100x100</b></a> | 
	<a href="/banners_200x300.php" '.(stripos($_SERVER['PHP_SELF'], 'banners_200x300.php' ) !== false ? 'class="block1"' : false).'><b>������� 200x300</b></a> | 
	<a href="/banners_468x60.php"  '.(stripos($_SERVER['PHP_SELF'], 'banners_468x60.php' ) !== false ? 'class="block1"' : false).'><b>������� 468x60</b></a> | 
	<a href="/banners_728x90.php"  '.(stripos($_SERVER['PHP_SELF'], 'banners_728x90.php' ) !== false ? 'class="block1"' : false).'><b>������� 728x90</b></a>
</div><br><br>';

if(!defined("banners100x100_file")) {
	define("banners100x100_file", $_SERVER["DOCUMENT_ROOT"].'/cache/banners100x100.inc');
}

if(!isset($banners100x100_array) && is_file(banners100x100_file) ) {
	$banners100x100_array = @unserialize(file_get_contents(banners100x100_file));
	$banners_count = count($banners100x100_array);

	if($banners_count>0) {
		echo '<div align="center" style="padding-bottom:15px; font-weight:bold;">�������� � ��������: '.$banners_count.'</div>';

		echo '<table border="0" align="center" cellspacing="1" cellpadding="1">';
		for($i=0; $i<$banners_count; $i++){
			echo '<tr align="center">';
				echo '<td><a href="/redir_banners.php?id='.$banners100x100_array[$i]['id_b'].'" target="_blank"><img src="'.$banners100x100_array[$i]['urlbanner'].'" border="0" width="100" height="100" alt="" /></a></td>';
			echo '</tr>';
			echo '<tr align="center">';
				echo '<td>���������:&nbsp;&nbsp;'.$banners100x100_array[$i]['count_visit'].'<br>����� ������� ��: '.DATE("d.m.Y H:i:s", $banners100x100_array[$i]['datE']).'<br><br></td>';
			echo '</tr>';
		}
		echo '</table>';

		echo '<div align="center" style="padding-top:10px;margin-bottom:10px;"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">���������� ������</span></div>';
	}else{
		echo '<div align="center"><a href="/advertise.php?ads=banners"><img src="/img/banner100x100_free.png" width="100" height="100" alt="" /></a></div><br><br>';
		echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">���������� ������</span></div>';
	}
}else{
	echo '<div align="center"><a href="/advertise.php?ads=banners"><img src="/img/banner100x100_free.png" width="100" height="100" alt="" /></a></div><br><br>';
	echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=banners\'">���������� ������</span></div>';
}

unset($banners100x100_array, $banners_count);

include('footer.php');?>