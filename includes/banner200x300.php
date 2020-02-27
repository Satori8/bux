<?php
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
$banner_type = "200x300";
$timer_refresh = mt_rand(10,15)*1000;
$banners_file = ROOT_DIR."/cache/banners".$banner_type.".inc";

if(!isset($banners_array) && is_file($banners_file) ) {
	$wh = explode("x", array_shift(explode("_", $banner_type)));
	$width = isset($wh["0"]) ? $wh["0"] : false;
	$height = isset($wh["1"]) ? $wh["1"] : false;

	$banners_array = @unserialize(file_get_contents($banners_file));
	$banners_count = count($banners_array);

	if(is_array($banners_array) && $banners_count>0) {
		if($banners_count>1) shuffle($banners_array);

		for($i=0; $i<$banners_count; $i++) {
			$UrlBan_arr[] = "/redir_banners.php?id=".$banners_array[$i]["id_b"];
			$ImgBan_arr[] = $banners_array[$i]["urlbanner"];
		}
	}

	$UrlBan_arr = (isset($UrlBan_arr) && is_array($UrlBan_arr) && count($UrlBan_arr)>0) ? implode("', '", $UrlBan_arr) : "/advertise.php?ads=banners";
	$ImgBan_arr = (isset($ImgBan_arr) && is_array($ImgBan_arr) && count($ImgBan_arr)>0) ? implode("', '", $ImgBan_arr) : "/img/banner".$width."x".$height."_free.png";

	?><script type="text/javascript">
		UrlArr_<?php echo $banner_type;?> = new Array ('<?php echo $UrlBan_arr;?>');
		ImgArr_<?php echo $banner_type;?> = new Array ('<?php echo $ImgBan_arr;?>');
		var FL_<?php echo $banner_type;?> = false;

		function RotBan_<?php echo $banner_type;?>() {
			var i = Math.floor(Math.random()*UrlArr_<?php echo $banner_type;?>.length);
			var ImgBan = '<a href="'+UrlArr_<?php echo $banner_type;?>[i]+'" title="" target="_blank"><img src="'+ImgArr_<?php echo $banner_type;?>[i]+'" alt="" title="" border="0" width="<?php echo $width;?>" height="<?php echo $height;?>" style="margin:0 auto; padding:0px;" /></a>';

			if(FL_<?php echo $banner_type;?> == false || FL_<?php echo $banner_type;?> == i) {
				FL_<?php echo $banner_type;?> = i;
				$("#ImgBan_<?php echo $banner_type;?>").html(ImgBan);
			}else{
				$("#ImgBan_<?php echo $banner_type;?>").fadeOut(500, function() {$("#ImgBan_<?php echo $banner_type;?>").html(ImgBan).fadeIn(500);});
			}
		}
	</script><?php

	if($banners_count>0) {
		echo '<div align="center" style="padding-bottom:8px;">Баннеров в ротаторе: '.$banners_count.'</div>';
	}

	echo '<div align="center" style="display:inline-block; width:100%; height:'.$height.'px; margin:0 auto;"><div id="ImgBan_'.$banner_type.'"></div></div>';

	if($banners_count>0) {
		echo '<div align="center" style="padding-top:8px; font-weight:normal;"><a href="/banners_'.$width.'x'.$height.'.php">Смотреть все</a>&nbsp;|&nbsp;<a href="/advertise.php?ads=banners">Разместить баннер</a></div>';
	}else{
		echo '<div align="center" style="padding-top:8px; font-weight:normal;"><a href="/advertise.php?ads=banners">Разместить баннер</a></div>';
	}

	echo '<script type="text/javascript">RotBan_'.$banner_type.'(); setInterval("RotBan_'.$banner_type.'()", '.$timer_refresh.');</script>';

}

unset($banners_array, $banners_file, $banners_count, $UrlBan_arr, $ImgBan_arr);

?>