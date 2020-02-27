<?php

define("beg_stroka_file", $_SERVER["DOCUMENT_ROOT"].'/cache/beg_stroka.inc');

if(!isset($beg_stroka_arr) && is_file(beg_stroka_file) ) {
	$beg_stroka_arr = @unserialize(file_get_contents(beg_stroka_file));
	$cnt_beg = count($beg_stroka_arr);
	shuffle($beg_stroka_arr);

    echo '<table class="tables_inv" style="margin:0px auto; padding:2px 0px; width:100%;">';
	//echo '<table class="tables_inv" style="padding: 7px 0px 7px 0px"; style="margin:0 auto; width:1200px;">';
	echo '<tr>';
               
		echo '<td height="30px" style="/*border: 1px solid #acacac; background: #F7F7F7; box-shadow: 0 1px 4px rgba(0, 0, 0, .3), -23px 0 20px -23px rgba(0, 0, 0, .8), 23px 0 20px -23px rgba(0, 0, 0, .8), 0 0 40px rgba(0, 0, 0, .1) inset;*/">';
		if($cnt_beg>0) {
			echo '<marquee style="/*width:925px;*/" behavior="scroll" align="center" direction="left"  scrollamount="2" scrolldelay="30" truespeed onmouseover="this.stop()" onmouseout="this.start()">';
				for($i=0; $i<$cnt_beg; $i++){
				    echo '<img src="//www.google.com/s2/favicons?domain='.@gethost($beg_stroka_arr[$i]["url_beg"]).'" width="16" height="16" border="0" alt="" title="" style="margin:0 5px; padding:0;" align="absmiddle">';
        				echo '<a href="'.$beg_stroka_arr[$i]["url_beg"].'" target="_blank" '.($beg_stroka_arr[$i]["color_beg"]==1 ? 'style="color:#FF0000;"' : false).'><b>'.$beg_stroka_arr[$i]["desc_beg"].'</b></a>&nbsp;&nbsp;&nbsp;&nbsp;';
				}
			echo '</marquee>';
		}else{
			echo '<div align="center" style="width:950px;">МЕСТО СВОБОДНО</div>';
		}
		echo '</td>';
		echo '<td align="center" width="150" style="/*border: 1px solid #acacac; background: #F7F7F7; box-shadow: 0 1px 4px rgba(0, 0, 0, .3), -23px 0 20px -23px rgba(0, 0, 0, .8), 23px 0 20px -23px rgba(0, 0, 0, .8), 0 0 40px rgba(0, 0, 0, .1) inset;*/"><a href="/advertise.php?ads=beg_stroka" class="link_adv" title="Разместить ссылку">Добавить ссылку</a></td>';
	echo '</tr>';
	echo '</table>';
}else{
    echo '<table class="tables_inv" style="margin:0px auto; padding:2px 0px; width:100%;">';
	//echo '<table class="tables_inv" style="padding: 7px 0px 7px 0px"; style="margin:0 auto; width:1200px;">';
	echo '<tr>';
		echo '<td height="30px" style="/*border: 1px solid #acacac; background: #F7F7F7; box-shadow: 0 1px 4px rgba(0, 0, 0, .3), -23px 0 20px -23px rgba(0, 0, 0, .8), 23px 0 20px -23px rgba(0, 0, 0, .8), 0 0 40px rgba(0, 0, 0, .1) inset;*/"><div align="center">МЕСТО СВОБОДНО</div></td>';
		echo '<td align="center" width="150" style="box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);"><a href="/advertise.php?ads=beg_stroka" class="link_adv" title="Разместить ссылку">Добавить ссылку</a></td>';
	echo '</tr>';
	echo '</table>';
}

unset($beg_stroka_arr, $cnt_beg);

?>