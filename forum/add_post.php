<?php

$p_id_r = (isset($_POST["id_r"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_r"]))) ? intval(limpiar(trim($_POST["id_r"]))) : false;
$p_id_pr = (isset($_POST["id_pr"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pr"]))) ? intval(limpiar(trim($_POST["id_pr"]))) : false;
$p_id_t = (isset($_POST["id_t"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_t"]))) ? intval(limpiar(trim($_POST["id_t"]))) : false;

$p_hs = (isset($_POST["hs"]) && preg_match("|^[a-zA-Z0-9]{32,48}$|", trim($_POST["hs"]))) ? htmlspecialchars(trim($_POST["hs"])) : false;
$check_hs1 = preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $p_hs);
$check_hs2 = md5($p_id_r+$p_id_pr+$p_id_t+2205);

//echo "$p_hs<br>$check_hs<br>$check_hs2<br>";

$post = (isset($_POST["post"])) ? (trim($_POST["post"])) : false;
$post = str_replace("'", '"', $post);
$post = str_replace("`", '"', $post);
$post = str_replace("\\", "", $post);
if (get_magic_quotes_gpc()) {$post = stripslashes($post);}

if($check_hs1==1 && $p_hs==$check_hs2 && $username!=false && $add_post==1) {

	$sql_t = mysql_query("SELECT * FROM `tb_forum_t` WHERE `id`='$p_id_t' AND `status`='0' AND `ident_r`='$p_id_r' AND `ident_pr`='$p_id_pr'");
	if(mysql_num_rows($sql_t)>0) {
		$row_t = mysql_fetch_array($sql_t);
		$title_t = $row_t["title"];

$rowl = mysql_query("SELECT price FROM tb_config WHERE item='rait_forum'") or die(mysql_error());
$rowl = mysql_fetch_row($rowl); 
$rr_price=$rowl[0];


		mysql_query("UPDATE `tb_users` SET `forum_mess`=`forum_mess`+'1', `reiting`=`reiting`+'$rr_price' WHERE `username`='$username'") or die(mysql_error());
		mysql_query("INSERT INTO `tb_forum_p` (`moder`,`username`,`ident_r`,`ident_pr`,`ident_t`,`title`,`text`,`date`) VALUES('$moder_post','$username','$p_id_r','$p_id_pr','$p_id_t','$title_t','$post','".time()."')") or die(mysql_error());

		$sql_p_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_forum_p`");
		$last_p_id = mysql_result($sql_p_id,0,0);

		if($moder_post==1) {$moder_post2=0;}else{$moder_post2=1;}
		mysql_query("UPDATE `tb_forum_pr` SET `moder`=`moder`+'$moder_post2',`lpost_t`='$title_t', `lpost_t_id`='$p_id_t', `lpost_user`='$username', `lpost_date`='".time()."', `lpost_p_id`='$last_p_id' WHERE `id`='$p_id_pr' AND `ident_r`='$p_id_r'") or die(mysql_error());
		mysql_query("UPDATE `tb_forum_t` SET `moder`=`moder`+'$moder_post2',`count_otv`=`count_otv`+'1', `lpost_user`='$username', `lpost_date`='".time()."', `lpost_p_id`='$last_p_id' WHERE `id`='$p_id_t' AND `ident_r`='$p_id_r'") or die(mysql_error());


		echo '<script type="text/javascript"> parent.location = "'.$_SERVER["PHP_SELF"].'?th='.$p_id_t.'&p=-1&last#post-'.$last_p_id.'";</script> ';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?th='.$p_id_t.'&p=-1&last#post-'.$last_p_id.'"></noscript>';
	}
}

?>
