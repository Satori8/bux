<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		require(ROOT_DIR."/config.php");
		require(ROOT_DIR."/funciones.php");

		$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? trim($_SESSION["userLog"]) : false;
		$user_pass = (isset($_SESSION["userPas"]) && preg_match("|^[0-9a-fA-F]{32}$|", trim($_SESSION["userPas"]))) ? trim($_SESSION["userPas"]) : false;
		$id_adv_post = ( isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"])) ) ? intval(trim($_POST["id"])) : false;
		$my_lastiplog = getRealIP();

		$sql_user = mysql_query("SELECT * FROM `tb_users` WHERE `username`='$user_name' AND md5(`password`)='$user_pass'");
		if(mysql_num_rows($sql_user)>0) {
			$row_user = mysql_fetch_assoc($sql_user);
			$username = $row_user["username"];
			$my_referer_1 = $row_user["referer"];
			$my_referer_2 = $row_user["referer2"];
			$my_referer_3 = $row_user["referer3"];
			$my_referer_4 = $row_user["referer4"];
			$my_referer_5 = $row_user["referer5"];
			$my_country = $row_user["country_cod"];
			$my_joindate = $row_user["joindate2"];
			$my_sex = $row_user["sex"];
			$my_fl18 = $row_user["fl18"];
			//$my_vip_serf = $row_user["vip_serf"];
			//$my_ban_serf = $row_user["ban_serf"];
			$my_youtube_serf = $row_user["youtube_serf"];
			//$my_wm_verif = $row_user["wm_verif"];

			$my_lastiplog_ex = explode(".", $my_lastiplog);
			$my_lastiplog_ex = (isset($my_lastiplog_ex[0]) && isset($my_lastiplog_ex[1])) ? $my_lastiplog_ex[0].".".$my_lastiplog_ex[1]."." : false;

			$sql_link = mysql_query("SELECT `id` FROM `tb_ads_youtube` WHERE `id`='$id_adv_post' AND `status`='1' 
				AND (`totals`>'0' OR ( `nolimit`>'0' AND `nolimit`>='".time()."') ) 
				AND ( 
					( `type_serf` IN ('-1','1','2','3','4','5','10') AND '$my_youtube_serf'>'0' ) OR 
					( `type_serf` IN ('-1','1','2','5','10') OR (`type_serf`='3' AND '$my_youtube_serf'>'0') )
				)
				AND (`limit_d`='0' OR (`limit_d`>'0' AND `limit_d_now`>'0') ) 
				AND (`limit_h`='0' OR (`limit_h`>'0' AND `limit_h_now`>'0') ) 
				AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%' OR `username`='$username') 
				AND (`sex_adv`='0' OR `sex_adv`='$my_sex' OR `username`='$username') 
				AND (`new_users`='0' OR (`new_users`='1' AND '$my_joindate'>='".(time()-7*86400)."') OR `username`='$username' ) 
				AND (`no_ref`='0' OR (`no_ref`='1' AND '$my_referer_1'='') OR `username`='$username' ) 
				AND (`content`='0' OR (`content`='1' AND '$my_fl18'='0') OR `username`='$username' ) 
				
				AND (`to_ref`='0' 
					OR (`to_ref`='1' AND `username`!='' AND (`username`='$username' OR `username`='$my_referer_1') ) 
					OR (`to_ref`='2' AND `username`!='' AND (`username`='$username' OR `username`='$my_referer_1' OR `username`='$my_referer_2' OR `username`='$my_referer_3' OR `username`='$my_referer_4' OR `username`='$my_referer_5') ) 
				) 
				AND `id` NOT IN (
					SELECT `ident` FROM `tb_ads_youtube_visits` 
					WHERE `time_next`>='".time()."' 
					AND (
						   ( `tb_ads_youtube`.`unic_ip`='0' AND `username`='$username' ) 
						OR ( `tb_ads_youtube`.`unic_ip`='1' AND (`username`='$username' OR `ip`='$my_lastiplog') ) 
						OR ( `tb_ads_youtube`.`unic_ip`='2' AND (`username`='$username' OR `ip` LIKE '%$my_lastiplog_ex%') ) 
					)
				) 
				
			");
			if(mysql_num_rows($sql_link)>0) {
				$row_link = mysql_fetch_assoc($sql_link);
				$id_adv_post = $row_link["id"];
				$token = strrev(md5(strtolower($username).$id_adv_post.$my_lastiplog)).strrev(md5(strtolower($_SERVER["HTTP_HOST"]).strtolower($_SERVER["HTTP_USER_AGENT"]).$my_lastiplog."### SPARTAK ###"));
				$url_link = "http://".$_SERVER["HTTP_HOST"]."/surfing_newv.php?id=$id_adv_post&token=$token";

				exit('{"result":"OK", "url_link":"'.$url_link.'"}');
			}else{
				exit('{"result":"ERROR"}');
			}
		}else{
			exit('{"result":"ERROR"}');
		}
	}else{
		exit('{"result":"ERROR"}');
	}
}else{
	exit('{"result":"ERROR"}');
}

?>