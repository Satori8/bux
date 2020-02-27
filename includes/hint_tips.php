<?php
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$hints_file = ROOT_DIR."/cache/cache_hints.inc";
$hints_arr = is_file($hints_file) ? @unserialize(file_get_contents($hints_file)) : false;

if(is_array($hints_arr)) {
	$count_hints = count($hints_arr);
	if($count_hints>1) shuffle($hints_arr);

	if($count_hints>0) {
		echo '<table class="tables" style="padding:0; margin:0 auto; margin-left:0px; width:100%; table-layout: fixed;">';
			echo '<tr><td><div style="text-wrap:normal; word-wrap: break-word; font-size:13px;">';
				echo '<div style="color:#ab0606; font-weight:bold;">'.$hints_arr[0]["title_hint"].'</div>';
				echo '<div>'.$hints_arr[0]["desc_hint"].'</div>';
			echo '</div></td></tr>';
		echo '</table>';
	}else{
		echo '<div align="center" style="margin:5px auto; font-weight:bold;">Подсказок нет</div>';
	}
}else{
	echo '<div align="center" style="margin:5px auto; font-weight:bold;">Подсказок нет</div>';
}

unset($hints_file, $hints_arr, $count_hints);

?>
