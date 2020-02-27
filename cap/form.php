<?php
session_start();

if(count($_POST)>0){
	if(isset($_SESSION['captcha_keystring']) && strtoupper($_SESSION['captcha_keystring']) == strtoupper($_POST['keystring'])){

	echo '<script type="">'."\r\n";
	echo 'top.window.location.href = "'.$_SESSION["urlsite"].'";'."\r\n";
	echo '</script>'."\r\n";

	echo '<noscript type="">'."\r\n";
	echo 'top.window.location.href = "'.$_SESSION["urlsite"].'";'."\r\n";
	echo '</noscript>'."\r\n";

		//?><script type="text/javascript">location.replace("<?=$_SESSION["urlsite"]?>");</script><noscript><meta http-equiv="refresh" content="0; url=<?=$_SESSION["urlsite"]?>"></noscript><?
		//exit();
	}else{
		echo "Просмотр не защитан!"; exit();
	}
}
unset($_SESSION['captcha_keystring']);

$use_symbols = "2345679abcdefghijhkmntwpuvxyz";
$use_symbols = strtoupper($use_symbols);
$use_symbols_len=strlen($use_symbols);


for($j=0;$j<4;$j++){
	$long=3;
	$codes1[$j]='';
	for($i=0;$i<$long;$i++){
		$codes1[$j].=$use_symbols{mt_rand(0,$use_symbols_len-1)};
	}
}

$_SESSION['captcha_keystring']=$codes1[rand(0,count($codes1)-1)];
?>

<table border="1" cellpadding="0" cellspacing="0" style="float: left; padding-left: 90px; background:url(/cap/?<?php echo session_name()?>=<?php echo session_id()?>) no-repeat;">
<tr>
	<td valign="top" width="250">
		<table border="1" cellpadding="0" cellspacing="0">
			<tr><td colspan="4"><font color="#000" size="2">&nbsp;Выберите вариант из списка:</font></td></tr>
			<tr><?php for($c=0;$c<4;$c++) {?><td><form method="post" action=""><input type="submit" value="<?php echo $codes1[$c];?>" name="keystring" style="background-color:#ccc; border:none; padding:3px; margin-left:2px; color:#ffffff; font-size:10pt;" readonly="readonly" /></form></td><?php }?></tr>
		</table>
	</td>
	<td style="font-family: Tahoma; Arial; font-size: 11px; color: #000;" align="left"><b>Описание:</b> <?=$_SESSION["description"]?><br><b>Адрес сайта:</b> <a href="<?=$_SESSION["urlsite"]?>" target="_blank" title="Перейти на сайт"><?=$_SESSION["urlsite"]?></a></td>
</tr>
</table>