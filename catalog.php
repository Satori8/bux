<?php
$pagetitle = "Каталог индексируемых ссылок на ".$_SERVER['HTTP_HOST']."";
include('header.php');
require("".$_SERVER['DOCUMENT_ROOT']."/config.php");


				
define("stat_kat_file", $_SERVER["DOCUMENT_ROOT"].'/cache/stat_kat.inc');

echo '<div id="block-text-page"><span id="info_rtpls" style="display:none;"></span>';
echo '<div id="loading" style="display:none;"></div>';
echo '<span id="info-msg" style="display:none;"></span>';
echo '<div id="LoadModal" style="display:none;"></div>';
echo '<div style="margin:0 auto;background: #f7e9d6;padding:10px;box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);text-align:justify;color:#114C5B;">
Основой продвижение вашего сайта в поисковых системах являются важные показатели <b>ИКС</b> (Яндекс). 
лучшим способом наращивания этих показателей является размещение статической ссылки вашего ресурса на сайтах, которые уже имеют солидный показатель. 
Поэтому размещение вашей статической ссылки на <br>
<b style="color:#ff7f50">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> &mdash; идеальное решение!';
echo '<div style="display: block; margin: 15px auto 3px auto; text-align:center;"><a href="https://yandex.ru/cy?base=0&amp;host='.$_SERVER["HTTP_HOST"].'" target="_blank" title="Индекс качества сайта"><img src="https://www.yandex.ru/cycounter?'.$_SERVER["HTTP_HOST"].'" width="88" height="31" alt="Индекс качества сайта" border="0" /></a></div>';
echo '</div>';

echo '<br/>';
echo '<table class="tables" style="margin:0 auto; width:100%;">';

if(!isset($stat_kat_arr) && is_file(stat_kat_file) ) {
	$stat_kat_arr = @unserialize(file_get_contents(stat_kat_file));
	$cnt = count($stat_kat_arr);

	for($i=0; $i<$cnt; $i++){
		if($stat_kat_arr[$i]["color_sl"]==1) {$style='class="slink2"';}else{$style='class="slink1"';}
		echo '<tr>';
                         echo '<td width="16" align="center" class="tdserf"><img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-right:5px;" src="//www.google.com/s2/favicons?domain='.@gethost($stat_kat_arr[$i]["url_sl"]).'" align="absmiddle" /></td>';
			
			echo '<td align="left" class="tdserf" style="padding-left:0px;"><a href="'.$stat_kat_arr[$i]["url_sl"].'" target="_blank" '.$style.'>'.$stat_kat_arr[$i]["desc_sl"].'</a><br><span class="catalog">Сайт: <b>|</b> '.$stat_kat_arr[$i]["url_sl"].'</span></td>';
                        echo '<td align="right" class="tdserf" style="padding-right:0px;"><a href="https://yandex.ru/cy/ch?base=0&amp;host='.$stat_kat_arr[$i]["url_sl"].'" target="_blank"><img src="//www.yandex.ru/cycounter?'.$stat_kat_arr[$i]["url_sl"].'" alt="Счетчик ИКС" title="Индекс качества сайта" align="absmiddle"></a></td>';
                         
		echo '</tr>';
	}
}
unset($stat_kat_arr, $cnt);



echo '<tr><td align="center" colspan="3"><span class="proc-btn" onClick="document.location.href=\'/advertise.php?ads=kat_links\'" style="float:none; width:160px;">Разместить ссылку</span></td></tr>';
echo '</table>';
echo '</div>';
?>
<?php include('footer.php');?>