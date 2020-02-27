<?php
header("Content-type: image/png");

$testdrive_count =  ( isset($_GET["count"])  && preg_match("|^[\d]{1,11}$|", intval(htmlspecialchars(trim($_GET["count"])))) ) ? intval(htmlspecialchars(trim($_GET["count"]))) : false;

if(isset($testdrive_count) && $testdrive_count!=false) {
	$img = ImageCreateFromPng("test_drive.png");
	imagecolorallocate($img, 255, 255, 255);
	$color = imagecolorallocate($img, 8, 118, 254);
	imagettftext($img, 20, 0, 49, 58, $color, "rotos_8.ttf", $testdrive_count);
	imagepng($img);
	imagedestroy($img);
}
?>