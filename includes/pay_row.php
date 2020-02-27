<?php
if(!DEFINED("pay_row_file")) DEFINE("pay_row_file", $_SERVER["DOCUMENT_ROOT"].'/cache/cache_pay_row.inc');

if(!isset($pay_row_arr) && is_file(pay_row_file) ) {
	$pay_row_arr = @unserialize(file_get_contents(pay_row_file));

	echo '<table class="tables_inv" style="margin:0px auto; padding:7px 0px 4px 0px; width:100%;">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td align="center" width="16" style="border-right:none; border-top:1px solid #DDD; padding:3px 1px;"><a href="/pay_row_info.php" title="Информация о текущей ссылке"><img src="img/help.png" width="16" border="0" height="16" alt="" /></a></td>';
		echo '<td align="left" width="100" style="border-left:none; border-top:1px solid #DDD; padding-left:0;"><a href="/pay_row_info.php" title="Информация о текущей ссылке" style="color: rgb(171, 6, 6); font-weight:bold;">Платная строка</a></td>';
		echo '<td align="center" style="border-top:1px solid #DDD; padding-left:15px;">';
			if(count($pay_row_arr)>0 && isset($pay_row_arr[0]["id_pr"])) {
				echo '<a href="/redir_pay_row.php?id='.$pay_row_arr[0]["id_pr"].'" title="Переходов: '.$pay_row_arr[0]["views_pr"].' шт." target="_blank" class="block"><b>'.$pay_row_arr[0]["desc_pr"].'</b></a>';
			}else{
				echo '<a href="/advertise.php?ads=pay_row" title="Разместить ссылку">Место свободно. Добавить ссылку</a>';
			}
		echo '</td>';
		echo '<td align="center" width="130" style="border-top:1px solid #DDD;"><a href="/advertise.php?ads=pay_row" class="link_adv" title="Добавить ссылку">Добавить ссылку</a></td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
}
?>