<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");
sleep(0);

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(stripslashes(trim($_SESSION["userLog"]))) : false;
	$token_get = ( isset($_POST["uid"]) && preg_match("/^[a-zA-Z0-9\$\!\/]{64}$/i", htmlspecialchars(limpiar(trim($_POST["uid"]))))==1 ) ? htmlspecialchars(limpiar(trim($_POST["uid"]))) : false;
	$token_post = strrev(substr($token_get,0,16)).strrev(substr($token_get,48,16));
	$uid = strrev(substr($token_get,16,32));
	$laip = getRealIP();

	$sql_user = mysql_query("SELECT `id`,`username`,`joindate2`,`lastiplog`,`country_cod`,`sex` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_assoc($sql_user);
		$username = $row_user["username"];
		$my_joindate = $row_user["joindate2"];
		$my_country = $row_user["country_cod"];
		$my_sex = $row_user["sex"];
		$my_lastiplog = $row_user["lastiplog"];
		$my_lastiplog_ex = explode(".", $my_lastiplog);
		$my_lastiplog_ex = $my_lastiplog_ex[0].".".$my_lastiplog_ex[1].".";

		mysql_query("UPDATE `tb_ads_tests` SET `status`='3', `date_edit`='".time()."' WHERE `status`>'0' AND `status`<'4' AND `balance`<`cena_advs`");
		mysql_query("DELETE FROM `tb_ads_tests_visits` WHERE `time_next`<'".(time()-3*24*60*60)."'");

		$sql_tests = mysql_query("SELECT `id` FROM `tb_ads_tests` 
			WHERE md5(`id`)='$uid' AND `status`='1' AND `balance`>=`cena_advs` 
			AND (`geo_targ`='' OR `geo_targ` LIKE '%$my_country%') 
			AND (`sex_user`='0' OR `sex_user`='$my_sex') 
			AND (`date_reg_user`='0' 
				OR (`date_reg_user`='1' AND '$my_joindate'>='".(time()-7*86400)."') 
				OR (`date_reg_user`='2' AND '$my_joindate'<='".(time()-7*86400)."') 
				OR (`date_reg_user`='3' AND '$my_joindate'<='".(time()-30*86400)."') 
				OR (`date_reg_user`='4' AND '$my_joindate'<='".(time()-90*86400)."') 
			) 
			
			AND `username` NOT IN (SELECT `user_name` FROM `tb_black_list` WHERE `user_bl`='$username' AND `type`='usr_adv' AND `tests_usr`='1') 
			AND `username` NOT IN (SELECT `user_bl` FROM `tb_black_list` WHERE `user_name`='$username' AND `type`='usr_adv' AND `tests_adv`='1') 
		");
		if(mysql_num_rows($sql_tests)>0) {
			$row_id = mysql_fetch_assoc($sql_tests);
			$id = $row_id["id"];

			$check_token = strtolower(md5(strtolower($username).session_id().DATE("H").$_SERVER["HTTP_HOST"].$id."SecurityTests"));
			$new_token = strtolower(md5(strtolower($username).session_id().DATE("H").$_SERVER["HTTP_HOST"].$id."SecurityTestsWork"));

			if($check_token != $token_post) {
				exit("ERROR");
			}else{
				$get_url = strrev(substr(strtolower(md5($id)),0,16)).strrev(substr($token_post,0,16)).strrev(substr(strtolower(md5($id)),16,16)).strrev(substr($token_post,16,16));
				exit("tests-view?id=$id&token=$new_token");
			}
		}else{
			exit("ERROR");
		}
	}else{
		exit("ERROR");
	}
}else{
	exit("ERROR");
}

?>