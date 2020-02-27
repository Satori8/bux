<?php
error_reporting (E_ALL); // Установите в error_reporting (0);

include('captcha-gen.php');

if(isset($_REQUEST[session_name()])){
	session_start();
}

$captcha = (isset($_SESSION['captcha_keystring'])) ? $_SESSION['captcha_keystring'] : false;

$captcha = new DMTcaptcha($captcha);
	return $captcha;

?>
