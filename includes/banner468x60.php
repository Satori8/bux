<?php

if(!defined("banners468x60_file")) {
	define("banners468x60_file", $_SERVER["DOCUMENT_ROOT"].'/cache/banners468x60.inc');
}

if(!isset($banners468x60_array) && is_file(banners468x60_file) ) {
	$banners468x60_array = @unserialize(file_get_contents(banners468x60_file));
	$cnt = count($banners468x60_array);


$baner_q=array();
$img_q=array();
for ($i=0;$i<$cnt;$i++)
{
 $baner_q[]='"redir_banners.php?id='.$banners468x60_array[$i]["id_b"].'"';  
 $img_q[]='"'.$banners468x60_array[$i]["urlbanner"].'"';  
}

if($cnt>0) {

		echo '<div align="center">';
?>
 <script type="text/javascript" language="JavaScript">
hArrayy = new Array (<?echo implode (",",$baner_q);?>);
iArrayy = new Array (<?echo implode (",",$img_q);?>);
function bannerss(a468) {
sekk = 14500;
document.getElementById('banner468x60').innerHTML = '<a href="'+hArrayy[a468]+'" target="_blank"><img src="'+iArrayy[a468]+'" width="468" height="60" border="0" alt="" /></a>';
a468 = a468 + 1;
if (a468 == iArrayy.length) { 
a468 = 0;
}
sl = a468;
timerID = setTimeout("bannerss(sl)",sekk);
}
</script>

<div id="baner-serf"><span id="banner468x60" class="banner_468x60" style="display: block;"></span></div>
<script type="text/javascript">
bannerss(0);
</script>
<?	
			echo '<div style="color:#f7f7f7; font-size:90%">Баннеров в ротаторе:&nbsp;'.$cnt.'&nbsp;|&nbsp;<a href="/banners_468x60.php"><span style="color:#ab0606">Смотреть все</span></a>&nbsp;|&nbsp;<a href="/advertise.php?ads=banners" target="_blank"><span style="color:#ab0606">Разместить баннер</span></a></div>';
		echo '</div>';
	}else{
		echo '<div align="center">';
			echo '<a href="/advertise.php?ads=banners" target="_blank" title=""><img src="/img/banner468_free.gif" alt="" title="" border="0" width="468" height="60" style="margin:0px; padding:0; border:3px solid #CCC" /></a>';
		echo '</div>';
	}
}else{
	echo '<div align="center">';
		echo '<a href="/advertise.php?ads=banners" target="_blank" title=""><img src="/img/banner468_free.gif" alt="" title="" border="0" width="468" height="60" style="margin:0px; padding:0; border:3px solid #CCC" /></a>';
	echo '</div>';
}
unset($banners468x60_array, $cnt);
?>