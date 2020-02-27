<?php

if(!defined("banners468x60_file")) {
	define("banners468x60_file", $_SERVER["DOCUMENT_ROOT"].'/cache/banners468x60_frm.inc');
}

if(!isset($banners468x60_array) && is_file(banners468x60_file) ) {
	$banners468x60_array = @unserialize(file_get_contents(banners468x60_file));
	$cnt = count($banners468x60_array);

	if($cnt>0) {
		@shuffle($banners468x60_array);

		echo '<a href="/redir_banners.php?id='.$banners468x60_array["0"]["id_b"].'" target="_blank" title=""><img src="'.$banners468x60_array["0"]["urlbanner"].'" alt="" title="" border="0" width="468" height="60" style="margin:0px; padding:0;" /></a>';
	}else{
		echo '<a href="/advertise.php?ads=banners" target="_blank" title=""><img src="/img/banner468_free.gif" alt="" title="" border="0" width="468" height="60" style="margin:0px; padding:0;" /></a>';
	}
}else{
	echo '<a href="/advertise.php?ads=banners" target="_blank" title=""><img src="/img/banner468_free.gif" alt="" title="" border="0" width="468" height="60" style="margin:0px; padding:0;" /></a>';
}
unset($banners468x60_array, $cnt);
?>