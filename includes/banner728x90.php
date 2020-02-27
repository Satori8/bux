<?php

if(!defined("banners728x90_file")) {
	define("banners728x90_file", $_SERVER["DOCUMENT_ROOT"].'/cache/banners728x90.inc');
}

if(!isset($banners728x90_array) && is_file(banners728x90_file) ) {
	$banners728x90_array = @unserialize(file_get_contents(banners728x90_file));
	$cnt = count($banners728x90_array);


$baner_q=array();
$img_q=array();
for ($i=0;$i<$cnt;$i++)
{
 $baner_qw[]='"redir_banners.php?id='.$banners728x90_array[$i]["id_b"].'"';  
 $img_qw[]='"'.$banners728x90_array[$i]["urlbanner"].'"';  
}

if($cnt>0) {

		echo '<div align="center">';
?>
 <script type="text/javascript" language="JavaScript">
hdArrayy = new Array (<?echo implode (",",$baner_qw);?>);
idArrayy = new Array (<?echo implode (",",$img_qw);?>);
function bannerss728(a728) {
sekka = 14500;
document.getElementById('banner728x90').innerHTML = '<div align="center" style="margin-left: -3px;"><a href="'+hdArrayy[a728]+'" target="_blank"><img src="'+idArrayy[a728]+'" width="728" height="90" border="0" alt="" /></a></div>';
a728 = a728 + 1;
if (a728 == idArrayy.length) { 
a728 = 0;
}
sla = a728;
timerID = setTimeout("bannerss728(sla)",sekka);
}
</script>

<div id="baner-serf"><span id="banner728x90" style="display: block;"></span></div>
<script type="text/javascript">
bannerss728(0);
</script>
<?	
			echo '<div style="color:#dfba86; font-size:90%">Баннеров в ротаторе:&nbsp;'.$cnt.'&nbsp;|&nbsp;<a href="/banners_728x90.php"><span style="color:#ab0606">Смотреть все</span></a>&nbsp;|&nbsp;<a href="/advertise.php?ads=banners" target="_blank"><span style="color:#ab0606">Разместить баннер</span></a></div>';
			//echo '<div style="color:#0087a7; font-size:90%">Баннеров в ротаторе:&nbsp;'.$cnt.'&nbsp;|&nbsp;<a href="/banners_728x90.php"><span style="color:#0000ff">Смотреть все</span></a>&nbsp;|&nbsp;<a href="/advertise.php?ads=banners" target="_blank"><span style="color:#0000ff">Разместить баннер</span></a></div>';
		echo '</div>';
	}else{
		echo '<div align="center">';
			echo '<div align="center" style="margin-left: -3px;"><a href="/advertise.php?ads=banners" target="_blank" title=""><img src="/img/banner728x90_free.png" alt="" title="" border="0" width="728" height="90" style="margin:0px; padding:0; border:3px solid #CCC" /></a></div>';
		echo '</div>';
	}
}else{
	echo '<div align="center">';
		echo '<div align="center" style="margin-left: -3px;"><a href="/advertise.php?ads=banners" target="_blank" title=""><img src="/img/banner728x90_free.png" alt="" title="" border="0" width="728" height="90" style="margin:0px; padding:0; border:3px solid #CCC" /></a></div>';
	echo '</div>';
}
unset($banners728x90_array, $cnt);
?>