<?php
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("frm_links_file")) DEFINE("frm_links_file", ROOT_DIR."/cache/frm_links.inc");
if(!DEFINED("TIMER_REFRESH")) DEFINE("TIMER_REFRESH", "7000");

if(!isset($frm_links_arr) && is_file(frm_links_file) ) {
	$frm_links_arr = @unserialize(file_get_contents(frm_links_file));
	$frm_links_count = count($frm_links_arr);

	if(is_array($frm_links_arr) && $frm_links_count>0) {
		$frm_links_new_arr = array();
		if($frm_links_count>1) shuffle($frm_links_arr);

		for($i=0; $i<$frm_links_count; $i++) {
			$frm_links_arr[$i]["text"] = str_replace(array("\r","\n"), " ", $frm_links_arr[$i]["text"]);
			$frm_links_arr[$i]["text"] = str_replace("  ", " ", $frm_links_arr[$i]["text"]);

			$frm_links_new_arr[] = "{link:\"".$frm_links_arr[$i]["link"]."\", text:\"".str_replace("\n", "<br>", $frm_links_arr[$i]["text"])."\"}";
		}
	}
}

$frm_links_new_arr = (isset($frm_links_new_arr) && is_array($frm_links_new_arr) && count($frm_links_new_arr)>0) ? implode(", ", $frm_links_new_arr) : "{link:'/advertise.php?ads=frm_links', text:'Рекламное место свободно!'}";

echo '<script type="text/javascript">'."\r\n";
	echo 'var frm_link_arr = ['.$frm_links_new_arr.'];'."\r\n";
	echo 'var firstLoad = false;'."\r\n";
echo '</script>'."\r\n";

unset($frm_links_arr, $frm_links_new_arr);

?>