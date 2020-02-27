<?php

if($username!=false && ($moder_post==1 | $del_post==1)) {

	if($moder_post==1) {$moder_post2=0;}else{$moder_post2=1;}

	mysql_query("DELETE FROM `tb_forum_p` WHERE `id`='$id_p' AND `status`='0' AND `moder`='$moder_post' AND (`username`='$username' OR $moder_post=1) AND `ident_t`='$id_t'") or die(mysql_error());

	if(mysql_affected_rows() > 0) {
		$sql_p = mysql_query("SELECT `id`,`username`,`ident_pr`,`date` FROM `tb_forum_p` WHERE `ident_t`='$id_t' ORDER BY `date` DESC LIMIT 1");
		if(mysql_num_rows($sql_p)>0) {
			$row_p = mysql_fetch_array($sql_p);

			$count_otv = mysql_num_rows(mysql_query("SELECT `id`,`username`,`date` FROM `tb_forum_p` WHERE `ident_t`='$id_t'")) - 1;

			if($count_otv<0) $count_otv=0;

			mysql_query("UPDATE `tb_forum_t` SET `moder`=`moder`-'$moder_post2',`count_otv`='$count_otv', `lpost_user`='".$row_p["username"]."', `lpost_date`='".$row_p["date"]."', `lpost_p_id`='".$row_p["id"]."' WHERE `id`='$id_t'") or die(mysql_error());

			$to_post = "#post-".$row_p["id"];

			$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `ident_pr`='".$row_p["ident_pr"]."' ORDER BY `date` DESC LIMIT 1");
			if(mysql_num_rows($sql_p)>0) {
				$row_p = mysql_fetch_array($sql_p);

				mysql_query("UPDATE `tb_forum_pr` SET `moder`=`moder`-'$moder_post2',`lpost_t`='".$row_p["title"]."', `lpost_t_id`='".$row_p["ident_t"]."', `lpost_user`='".$row_p["username"]."', `lpost_date`='".$row_p["date"]."', `lpost_p_id`='".$row_p["id"]."' WHERE `id`='".$row_p["ident_pr"]."'") or die(mysql_error());
			}
		}else{
			$to_post = "";
		}
	}else{
		$to_post = "";
	}

	echo '<script type="text/javascript">parent.location = "'.$_SERVER["PHP_SELF"].'?th='.$id_t.'&p='.$page.'";</script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?th='.$id_t.'&p='.$page.'"></noscript>';
}
?>