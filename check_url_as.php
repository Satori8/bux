<?php
@session_start();
error_reporting (E_ALL);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<meta name="Author" content="supreme-garden.ru">
	<link rel="stylesheet" type="text/css" href="style/frame.css">
	<title><?php $host=$_SERVER["HTTP_HOST"]; echo strtoupper(str_replace("www."," ",$host));?> | Проверка ссылки</title>

	<?php

	require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
	require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");

	$url = (isset($_SESSION["checkurl"])) ? limpiar($_SESSION["checkurl"]) : false;
	if(count($_POST)<1) {$_SESSION["endtmr"] = (20 + time());}

	?><script type="text/javascript" language="JavaScript">
	var counter=1+parseInt(20);
	var flag=0;                                

	function do_count(){
		if(typeof flag == "undefined") {
			return flag;
		}
		if(flag==0) {
			counter--;
			document.getElementById('begin').innerHTML='';
			document.getElementById('load').innerHTML='';
		}
		if(flag==1) {
			document.getElementById('begin').innerHTML='<br>&nbsp;[Если остановился таймер, нажмите ЗДЕСЬ]';
		}
		if(counter>=0) {
			document.getElementById('tmr').innerHTML='&nbsp;<span style="color:#555555;">Пожалуйста ждите</span>&nbsp;&nbsp;<span style="color:#289639; font-size: 17px;">'+counter+'</span>&nbsp;&nbsp;<span style="color:#555555;">секунд</span>&nbsp;...';
			document.getElementById('load').innerHTML='';
			setTimeout("do_count()",1000);
		}
		if(counter<0){
			document.getElementById('tmr').innerHTML='';
			document.getElementById('load').innerHTML='';
			document.getElementById('begin').innerHTML='';
			document.getElementById("capcha").style.display='block';
		}
	}
	window.onblur = new Function('this.flag = 0;');
	window.onfocus = new Function('this.flag = 0;');
	window.parent.document.title="<?php echo strtoupper(str_replace("www."," ",$host));?> | Проверка ссылки";
	</script>
</head>

<body onselectstart="return false" oncontextmenu="return false" onLoad="return do_count();  return false;">

<?php
if(isset($_POST["answer"]) && isset($_POST["num"]) && isset($_SESSION["captcha_keystring"]) && 
	md5(strtoupper(limpiar($_POST["answer"])))==md5(strtoupper($_SESSION["captcha_keystring"])) && 
	strtoupper(limpiar($_POST["num"]))==strtoupper(md5($_SESSION["codes"]))
) {
	if(isset($_SESSION["endtmr"]) && intval($_SESSION["endtmr"]) <= time()) {

		unset($_SESSION["captcha_keystring"]);
		unset($_SESSION["hash1"]);
		unset($_SESSION["hash2"]);
		unset($_SESSION["codes"]);
		unset($_SESSION["endtmr"]);

		$_SESSION["checkurl_ok"] = 1;

		echo '<script type="text/javascript"> top.document.location.href = "/advertise.php?ads=auto_serf"; </script>';
	}else{
		echo '<b style="color: #FF0000;">Ошибка! Нарушен порядок проверки!</b>';
		exit();
	}
}else{
	if(count($_POST)>0){
		unset($_SESSION["captcha_keystring"]);
		unset($_SESSION["hash1"]);
		unset($_SESSION["hash2"]);
		unset($_SESSION["codes"]);
		unset($_SESSION["endtmr"]);

		echo '<b style="color: #FF0000;">Ошибка! Проверка ссылки не пройдена, будьте внимательней при выборе!</b>';
		exit();
	}
}



if(!isset($_SESSION["checkurl_ok"]) && isset($_SESSION["checkurl"])) {
	function get_codes1($num,$len) {
		for($y=0;$y<$num;$y++) {
			$codes[]=substr(strtoupper(md5(mt_rand(1000,1000000000))),0,$len);
		}
		return $codes;
	}
	function get_codes2($num) {
		for($y=0;$y<$num;$y++) {
			$codes[]=mt_rand(123456789,999999999999999999);
		}
		return $codes;
	}
	$codes1 = get_codes1(5,3);
	$codes2 = get_codes2(5);
	$rnd_nomer = mt_rand(0,count($codes1)-1);

	unset($_SESSION["captcha_keystring"]);
	unset($_SESSION["codes"]);
	$_SESSION["captcha_keystring"] = $codes1[$rnd_nomer];
	$_SESSION["codes"] = $codes2[$rnd_nomer];
	$endtmr = (isset($_SESSION["endtmr"])) ? $_SESSION["endtmr"] : false;

	echo '<table width="100%" height="100%" cellspacing="0" cellpadding="0" style="margin: 0 auto; padding:0 auto;">';
	echo '<tr>';
		echo '<td width="460" height="50" style="background: #F9F9F9; padding-left: 10px;">';
			echo '<span id="tmr" style="font-weight: normal; font-family: Verdana; font-size: 14px;"></span><span id="load" style="color: #444; font-weight: normal;">Загрузка сайта, ждите&nbsp;...</span><span id="begin" style="color: #C80000;"></span>';

			echo '<table border="0" cellpadding="0" cellspacing="0" id="capcha" style="background: #F9F9F9; display: none;">';
			echo '<tr><td rowspan="2" style="padding-right: 15px;"><img src="/cap/?'.session_name().'='.session_id().'" id="captcha-image" align="middle" alt="" />&nbsp;<a href="javascript:void(0);" onclick="document.getElementById(\'captcha-image\').src = \'/cap/?'.session_name().'='.session_id().')?rid=\' + Math.random();"><img src="/img/refresh.png" alt="" border="0" align="middle" title="Обновить изображение" /></a><td colspan="5"><span style="color: #444; font-family: Verdana; font-size: 13px;">Выберите правильный вариант:</span></td></tr>';
			echo '<tr>';
				$k_num=0;
				foreach($codes1 as $c):
					echo '<td>';
					echo '<form action="" method="POST" target="_self">';
					echo '<input type="hidden" name="cap" value="0">';
					echo '<input type="hidden" name="answer" value="'.$c.'">';
					echo '<input type="hidden" name="num" value="'.md5($codes2["$k_num"]).'">';
					echo '<input type="submit" value="'.$c.'" class="submit">';
					echo '</form>';
					echo '</td>';
					$k_num++;
				endforeach;
			echo '</tr></table>';
		echo '</td>';
		echo '<td height="50" style="background: #F9F9F9;"><b style="color: #444; font-family: Verdana; font-size: 13px;">Проверка сайта, дождитесь окончания таймера.</b></td>';
	echo '</tr>';
	echo '<tr><td colspan="2" height="99%" style="border-top:2px solid #CCC;"><iframe src="'.$url.'" height="100%" width="100%" hspace="0" vspace="0" frameborder="3" scrolling="auto"></iframe></td></tr>';
	echo '</table>';
}
?>

</body>
</html>