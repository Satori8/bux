<?php
$pagetitle="Личные данные";
include('header.php');
require_once('.zsecurity.php');
require('config.php');
require($_SERVER["DOCUMENT_ROOT"]."/api/advcash/advcash_func.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	/*$_URL_Email_Arr = array("/members.php","/newmsg.php","/contact.php","/index.php");
	if(isset($my_tl_purse) && $my_tl_purse!=0 && array_search($_SERVER["PHP_SELF"], $_URL_Email_Arr)===false) {
				echo '<span class="msg-error">Для работы на проекте Вам необходимо подтвердить ваш E-mal. <a href="/profile.php?op=mod_email">[подтвердить]</a></span>';
				include('footer.php');
			exit();
			//}
		}*/
		
		
	//echo '<script type="text/javascript" src="../scripts/jqpooop.js"></script>';
	
	echo '<script src="/view_task/tack_info.js?v=1.00"></script>';

	?><script language="JavaScript">
		function sendchkpass(){
			if (confirm("Вы собираетесь получить пароль для операций.\nСейчас будет сгенерирован новый пароль для операций, a старый будет удален из системы.\nВы хотите продолжить?")){
				pass_oper("<?php echo $partnerid;?>");
			}
		}
	</script><?php

	$domen = ucfirst($_SERVER['HTTP_HOST']);
	$domen = str_replace("wm","WM", $domen);
	$domen = str_replace("ru","RU", $domen);

	$username = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? uc($_SESSION["userLog"]) : false;
	$password = (isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) ? ($_SESSION["userPas"]) : false;

	if (count($_POST) > 0) {
		$block_wmid = (isset($_POST["block_wmid"]) && (intval($_POST["block_wmid"])==0 | intval($_POST["block_wmid"])==1)) ? intval($_POST["block_wmid"]) : "0";
		$block_agent = (isset($_POST["block_agent"]) && (intval($_POST["block_agent"])==0 | intval($_POST["block_agent"])==1)) ? intval($_POST["block_agent"]) : "0";
		$email_sent = (isset($_POST["email_sent"]) && (intval($_POST["email_sent"])==0 | intval($_POST["email_sent"])==1)) ? intval($_POST["email_sent"]) : "0";
		$fl18 = (isset($_POST["fl18"]) && (intval($_POST["fl18"])==0 | intval($_POST["fl18"])==1)) ? intval($_POST["fl18"]) : "0";
		$check_pass = (isset($_POST["pass"])) ? limpiar(trim($_POST["pass"])) : false;
		$enter_pass_oper = (isset($_POST["pass_oper"]) && preg_match("|^[a-zA-Z0-9\-_-]{6,20}$|", trim($_POST["pass_oper"]))) ? uc_p($_POST["pass_oper"]) : false;
		$db_d = (isset($_POST["db_d"]) && (intval($_POST["db_d"])>0 | intval($_POST["db_d"])<=31)) ? intval($_POST["db_d"]) : false;
		$db_m = (isset($_POST["db_m"]) && (intval($_POST["db_m"])>0 | intval($_POST["db_m"])<=12)) ? intval($_POST["db_m"]) : false;
		$db_y = (isset($_POST["db_y"]) && (intval($_POST["db_y"])>(DATE("Y")-91) | intval($_POST["db_y"])<=(DATE("Y")-6))) ? intval($_POST["db_y"]) : false;
		$db_time_ = "$db_d.$db_m.$db_y";
		$db_time = DATE("d.m.Y",strtotime($db_time_));
		$db_time = strtotime($db_time);
		$sex = (isset($_POST["sex"]) && (intval($_POST["sex"])==0 | intval($_POST["sex"])==1 | intval($_POST["sex"])==2)) ? intval($_POST["sex"]) : "0";
		$fio = (isset($_POST["fio"]) && preg_match("|^[а-яА-Яa-zA-Z\- ]{1,160}$|", trim($_POST["fio"]))) ? htmlspecialchars(trim($_POST["fio"])) : false;
		$imay = (isset($_POST["imay"]) && preg_match("|^[а-яА-Яa-zA-Z\- ]{1,160}$|", trim($_POST["imay"]))) ? htmlspecialchars(trim($_POST["imay"])) : false;

		$frm_position = ( isset($_POST["frm_pos"]) && preg_match("|^[0-1]{1}$|", trim($_POST["frm_pos"])) ) ? htmlspecialchars(trim($_POST["frm_pos"])) : "1";

		$wm_purse = (isset($_POST["wm_purse"]) && preg_match("|^[R]{1}[\d]{12}$|", htmlspecialchars(trim($_POST["wm_purse"])))) ? trim($_POST["wm_purse"]) : false;
		$ym_purse = (isset($_POST["ym_purse"]) && preg_match("|^[\d]{13,16}$|", trim($_POST["ym_purse"]))) ? trim($_POST["ym_purse"]) : false;
		$pm_purse = (isset($_POST["pm_purse"]) && preg_match("|^[U]{1}[\d]{1,20}$|", trim($_POST["pm_purse"]))) ? trim($_POST["pm_purse"]) : false;
		$py_purse = (isset($_POST["py_purse"]) && preg_match("|^[P]{1}[\d]{1,20}$|", trim($_POST["py_purse"]))) ? trim($_POST["py_purse"]) : false;
		$qw_purse = (isset($_POST["qw_purse"]) && preg_match("/^\+?([87](?!95[4-79]|99[08]|907|94[^0]|336)([348]\d|9[0-6789]|7[0247])\d{8}|[1246]\d{9,13}|68\d{7}|5[1-46-9]\d{8,12}|55[1-9]\d{9}|55[12]19\d{8}|500[56]\d{4}|5016\d{6}|5068\d{7}|502[45]\d{7}|5037\d{7}|50[4567]\d{8}|50855\d{4}|509[34]\d{7}|376\d{6}|855\d{8}|856\d{10}|85[0-4789]\d{8,10}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|17[01]|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^4568]\d{7,11}|994\d{9}|9955\d{8}|996[57]\d{8}|9989\d{8}|380[3-79]\d{8}|381\d{9}|385\d{8,9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[12359]\d{8,12}|36\d{9}|38[1679]\d{8}|382\d{8,9}|46719\d{10})$/", trim($_POST["qw_purse"]))) ? htmlspecialchars(trim($_POST["qw_purse"]), NULL, "CP1251") : false;
		//$mb_purse = (isset($_POST["mb_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["mb_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["mb_purse"]))) ? htmlspecialchars(trim($_POST["mb_purse"]), NULL, "CP1251") : false;
		$be_purse = (isset($_POST["be_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["be_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["be_purse"]))) ? htmlspecialchars(trim($_POST["be_purse"]), NULL, "CP1251") : false;
		$mt_purse = (isset($_POST["mt_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["mt_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["mt_purse"]))) ? htmlspecialchars(trim($_POST["mt_purse"]), NULL, "CP1251") : false;
		$mg_purse = (isset($_POST["mg_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["mg_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["mg_purse"]))) ? htmlspecialchars(trim($_POST["mg_purse"]), NULL, "CP1251") : false;
		$tl_purse = (isset($_POST["tl_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["tl_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["tl_purse"]))) ? htmlspecialchars(trim($_POST["tl_purse"]), NULL, "CP1251") : false;
		$me_purse = (isset($_POST["me_purse"]) && preg_match("|^[\d]{14,22}$|", trim($_POST["me_purse"]))) ? trim($_POST["me_purse"]) : false;
		$vs_purse = (isset($_POST["vs_purse"]) && preg_match("|^[\d]{14,22}$|", trim($_POST["vs_purse"]))) ? trim($_POST["vs_purse"]) : false;
		$ms_purse = (isset($_POST["ms_purse"]) && preg_match("|^[\d]{14,22}$|", trim($_POST["ms_purse"]))) ? trim($_POST["ms_purse"]) : false;
		$ac_purse = (isset($_POST["ac_purse"]) && preg_match("|^[R]{1}[\d]{12}$|", str_ireplace(" ", "", strtoupper(htmlspecialchars(trim($_POST["ac_purse"])))))) ? str_ireplace(" ", "", strtoupper(htmlspecialchars(trim($_POST["ac_purse"])))) : false;
		$ac_purse = $ac_purse!=false ? (CheckWalletIdAC($ac_purse)==$ac_purse ? $ac_purse : false) : false;

		$qw_purse = str_ireplace("+","", $qw_purse);
		$be_purse = str_ireplace("+","", $be_purse);
		$mt_purse = str_ireplace("+","", $mt_purse);
		$mg_purse = str_ireplace("+","", $mg_purse);
		$tl_purse = str_ireplace("+","", $tl_purse);
		
		$sql_ym_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `ym_purse`='$ym_purse'");
        $sql_pm_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `pm_purse`='$pm_purse'");
        $sql_py_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `py_purse`='$py_purse'");
        $sql_qw_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `qw_purse`='$qw_purse'");
        $sql_ac_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `ac_purse`='$ac_purse'");
        $sql_me_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `me_purse`='$me_purse'");
        $sql_vs_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `vs_purse`='$vs_purse'");
        $sql_ms_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `ms_purse`='$ms_purse'");
        $sql_be_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `be_purse`='$be_purse'");
        $sql_mt_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `mt_purse`='$mt_purse'");
        $sql_mg_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `mg_purse`='$mg_purse'");
        $sql_tl_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `tl_purse`='$tl_purse'");
		
		if(mysql_num_rows($sql_ym_check)>0 and $ym_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с Яндекс.Деньги кошельком <b>'.$ym_purse.'</b> уже зарегистрирован!</span>'; 
		
		}elseif(mysql_num_rows($sql_pm_check)>0 and $pm_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с PerfectMoney кошельком <b>'.$pm_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_py_check)>0 and $py_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с Payeer кошельком <b>'.$py_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_qw_check)>0 and $qw_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с Qiwi кошельком <b>'.$qw_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_be_check)>0 and $be_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером телефона <b>'.$be_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_mt_check)>0 and $mt_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с Номером телефона <b>'.$mt_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_mg_check)>0 and $mg_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером телефона <b>'.$mg_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_tl_check)>0 and $tl_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером телефона <b>'.$tl_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_me_check)>0 and $me_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером карты MAESTRO <b>'.$me_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_ms_check)>0 and $ms_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером карты VISA <b>'.$vs_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_ms_check)>0 and $ms_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером карты MASTERCARD <b>'.$ms_purse.'</b> уже зарегистрирован!</span>'; 

}elseif(mysql_num_rows($sql_ac_check)>0 and $ac_purse!='') {
echo '<span id="info-msg-profile" class="msg-error">Пользователь с номером кошелька AdvCash <b>'.$ac_purse.'</b> уже зарегистрирован!</span>';
}else{

		if($check_pass!=false) {
			if(!preg_match("|^[a-zA-Z0-9\-_-]{6,20}$|", trim($_POST["pass"]))) {
				$pass = htmlspecialchars(stripslashes(trim($_POST["pass"])));
				$pass = str_replace("'","",$pass);
				$pass = str_replace(";","",$pass);
				$pass = str_replace("$","",$pass);
				$eror_pass = "1";
			}else{
				$pass = htmlspecialchars(stripslashes(trim($_POST["pass"])));
				$pass = str_replace("'","",$pass);
				$pass = str_replace(";","",$pass);
				$pass = str_replace("$","",$pass);
				$eror_pass = "0";
			}

			if(!preg_match("|^[a-zA-Z0-9\-_-]{6,20}$|", trim($_POST["cpass"]))) {
				$cpass = htmlspecialchars(stripslashes(trim($_POST["cpass"])));
				$cpass = str_replace("'","",$cpass);
				$cpass = str_replace(";","",$cpass);
				$cpass = str_replace("$","",$cpass);
				$eror_cpass = "1";
			}else{
				$cpass = htmlspecialchars(stripslashes(trim($_POST["cpass"])));
				$cpass = str_replace("'","",$cpass);
				$cpass = str_replace(";","",$cpass);
				$cpass = str_replace("$","",$cpass);
				$eror_cpass = "0";
			}
		}else{
			$eror_pass = "0";
			$eror_cpass = "0";
			$pass = "";
			$cpass = "";
		}

		if(!preg_match("|^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{1,200}\.[a-zA-Z]{1,4}$|", trim($_POST["email"]))) {
			$email = limpiar(trim($_POST["email"]));
			$eror_email = "1";
		}else{
			$email = limpiar(trim($_POST["email"]));
			$eror_email = "0";
		}



		if($enter_pass_oper==false) {
			echo '<span id="info-msg-profile" class="msg-error">Для изменения данных необходимо указать пароль для операций! <a href="javascript: void(0);" onClick="sendchkpass();">получить пароль для операций</a></span>';

		}elseif($enter_pass_oper!=$pass_oper) {
			echo '<span id="info-msg-profile" class="msg-error">Пароль для операций введен не верно! <a href="javascript: void(0);" onClick="sendchkpass();">получить пароль для операций</a></span>';

		}elseif($eror_pass=="1") {
			echo '<span id="info-msg-profile" class="msg-error">Указан неправильный пароль! Пароль должен состоять не менее чем из 6 и не более 20 символов, и содержать только латинские символы, допускаются символы "-" и "_"</span>';

		}elseif($pass!=$cpass) {
			echo '<span id="info-msg-profile" class="msg-error">Пароли не совпадают!</span>';

		}elseif($email=="") {
			echo '<span id="info-msg-profile" class="msg-error">Не указан e-mail!</span>';

		}elseif($eror_email=="1") {
			echo '<span id="info-msg-profile" class="msg-error">E-mail введен не правильно!</span>';

		}elseif(isset($_POST["wm_purse"]) && htmlspecialchars($_POST["wm_purse"])!=false && $wm_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет WMR WebMoney введен не правильно!</span>';
			$wm_purse_tab = "";
		}elseif($wm_purse!=false && $my_wm_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет WMR WebMoney нельзя изменить!</span>';
			$wm_purse_tab = "";

		}elseif(isset($_POST["ym_purse"]) && htmlspecialchars($_POST["ym_purse"])!=false && $ym_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет в Яндекс.Деньги введен не правильно!</span>';
			$ym_purse_tab = "";
		}elseif($ym_purse!=false && $my_ym_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет в Яндекс.Деньги нельзя изменить!</span>';
			$ym_purse_tab = "";

		}elseif(isset($_POST["pm_purse"]) && htmlspecialchars($_POST["pm_purse"])!=false && $pm_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет PerfectMoney введен не правильно!</span>';
			$pm_purse_tab = "";
		}elseif($pm_purse!=false && $my_pm_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет PerfectMoney нельзя изменить!</span>';
			$pm_purse_tab = "";

		}elseif(isset($_POST["py_purse"]) && htmlspecialchars($_POST["py_purse"])!=false && $py_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет Payeer введен не правильно!</span>';
			$py_purse_tab = "";
		}elseif($py_purse!=false && $my_py_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет Payeer нельзя изменить!</span>';
			$py_purse_tab = "";

		}elseif(isset($_POST["qw_purse"]) && htmlspecialchars($_POST["qw_purse"])!=false && $qw_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет Qiwi введен не правильно!</span>';
			$qw_purse_tab = "";
		}elseif($qw_purse!=false && $my_qw_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Счет Qiwi нельзя изменить!</span>';
			$qw_purse_tab = "";

		/*}elseif(isset($_POST["mb_purse"]) && htmlspecialchars($_POST["mb_purse"])!=false && $mb_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона введен не правильно!<br>Номер телефона необходимо указывать только для абонентов России</span>';
			$mb_purse_tab = "";
		}elseif($mb_purse!=false && $my_mb_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона нельзя изменить!</span>';
			$mb_purse_tab = "";*/
			
		}elseif(isset($_POST["be_purse"]) && htmlspecialchars($_POST["be_purse"])!=false && $be_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона введен не правильно!<br>Номер телефона необходимо указывать только для абонентов России</span>';
			$be_purse_tab = "";
		}elseif($be_purse!=false && $my_be_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона нельзя изменить!</span>';
			$be_purse_tab = "";
			
		}elseif(isset($_POST["mt_purse"]) && htmlspecialchars($_POST["mt_purse"])!=false && $mt_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона введен не правильно!<br>Номер телефона необходимо указывать только для абонентов России</span>';
			$mt_purse_tab = "";
		}elseif($mt_purse!=false && $my_mt_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона нельзя изменить!</span>';
			$mt_purse_tab = "";
			
		}elseif(isset($_POST["mg_purse"]) && htmlspecialchars($_POST["mg_purse"])!=false && $mg_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона введен не правильно!<br>Номер телефона необходимо указывать только для абонентов России</span>';
			$mg_purse_tab = "";
		}elseif($mg_purse!=false && $my_mg_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона нельзя изменить!</span>';
			$mg_purse_tab = "";
			
		}elseif(isset($_POST["tl_purse"]) && htmlspecialchars($_POST["tl_purse"])!=false && $tl_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона введен не правильно!<br>Номер телефона необходимо указывать только для абонентов России</span>';
			$tl_purse_tab = "";
		}elseif($tl_purse!=false && $my_tl_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер телефона нельзя изменить!</span>';
			$tl_purse_tab = "";
			
		}elseif(isset($_POST["me_purse"]) && htmlspecialchars($_POST["me_purse"])!=false && $me_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер карты MAESTRO введен не правильно!</span>';
			$me_purse_tab = "";

		}elseif($me_purse!=false && $my_me_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер карты MAESTRO нельзя изменить!</span>';
			$me_purse_tab = "";
			
		}elseif(isset($_POST["vs_purse"]) && htmlspecialchars($_POST["vs_purse"])!=false && $vs_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер карты VISA введен не правильно!</span>';
			$vs_purse_tab = "";

		}elseif($vs_purse!=false && $my_vs_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер карты VISA нельзя изменить!</span>';
			$vs_purse_tab = "";
			
		}elseif(isset($_POST["ms_purse"]) && htmlspecialchars($_POST["ms_purse"])!=false && $ms_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер карты MASTERCARD введен не правильно!</span>';
			$ms_purse_tab = "";

		}elseif($ms_purse!=false && $my_ms_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер карты MASTERCARD нельзя изменить!</span>';
			$ms_purse_tab = "";

		}elseif(isset($_POST["ac_purse"]) && htmlspecialchars($_POST["ac_purse"])!=false && $ac_purse==false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер кошелька в системе AdvCash введен не правильно!</span>';
			$ac_purse_tab = "";
		}elseif($ac_purse!=false && $my_ac_purse!=false) {
			echo '<span id="info-msg-profile" class="msg-error">Номер кошелька AdvCash нельзя изменить!</span>';
			$ac_purse_tab = "";

		}else{
			$_ERROR = 0;

			if(isset($_POST["wm_purse"]) && $wm_purse!=false) {
				require_once("auto_pay_req/wmxml.inc.php");
				$_X8 = _WMXML8("", $wm_purse);

				if($_X8["retval"]==0) {
					echo '<span id="info-msg-profile" class="msg-error">Счет WMR не найден в системе WebMoney, проверьте правильность ввода!</span>';

					$wm_id_x8 = false;
					$wm_purse_x8 = false;
					$wm_attestat_x8 = false;
					$_ERROR++;

				}elseif($_X8["retval"]==1) {
					$wm_id_x8 = $_X8["wmid"];
					$wm_purse_x8 = $_X8["purse"];

					$sql_wmid_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `wmid`='$wm_id_x8'");
					$sql_wmr_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `wm_purse`='$wm_purse_x8'");

					if(mysql_num_rows($sql_wmid_check)>0) {
						echo '<span id="info-msg-profile" class="msg-error">Пользователь с WMID '.$wm_id_x8.' уже зарегистрирован!</span>';

						$wm_id_x8 = false;
						$wm_purse_x8 = false;
						$wm_attestat_x8 = false;
						$_ERROR++;

					}elseif(mysql_num_rows($sql_wmr_check)>0) {
						echo '<span id="info-msg-profile" class="msg-error">Пользователь с WMR кошельком '.$wm_purse_x8.' уже зарегистрирован!</span>';

						$wm_id_x8 = false;
						$wm_purse_x8 = false;
						$wm_attestat_x8 = false;
						$_ERROR++;

					}else{
						$wm_id_x8 = (isset($wm_id_x8) && preg_match("|^[\d]{12}$|", htmlspecialchars(trim($wm_id_x8)))) ? htmlspecialchars(trim($wm_id_x8)) : false;
						$wm_purse_x8 = (isset($wm_purse_x8) && preg_match("|^[R]{1}[\d]{12}$|", htmlspecialchars(trim($wm_purse_x8)))) ? htmlspecialchars(trim($wm_purse_x8)) : false;
						$wm_attestat_x8 = isset($_X8["newattst"]) ? $_X8["newattst"] : 0;
					}
				}else{
					$wm_id_x8 = false;
					$wm_purse_x8 = false;
					$wm_attestat_x8 = false;
					$_ERROR++;

					echo '<span id="info-msg-profile" class="msg-error">Ошибка обработки данных! Повторите попытку ввода WMR кошелька позже.</span>';
				}
			}


			if($_ERROR==0) {

				if(
					isset($wm_id_x8) && isset($wm_purse_x8) && isset($wm_attestat_x8) && 
					trim($wm_id_x8)!=false && trim($wm_purse_x8)!=false && trim($wm_attestat_x8)!=false 
				) {
					$wm_purse_tab = ", `wmid`='$wm_id_x8', `wm_purse`='$wm_purse', `attestat`='$wm_attestat_x8'";
				}else{
					$wm_purse_tab = "";
				}

				//if($wm_purse_tab == false) $block_wmid = 0;

				if($ym_purse!=false) {$ym_purse_tab = ", `ym_purse`='$ym_purse'";}else{$ym_purse_tab = "";}
				if($pm_purse!=false) {$pm_purse_tab = ", `pm_purse`='$pm_purse'";}else{$pm_purse_tab = "";}
				if($py_purse!=false) {$py_purse_tab = ", `py_purse`='$py_purse'";}else{$py_purse_tab = "";}
				if($qw_purse!=false) {$qw_purse_tab = ", `qw_purse`='$qw_purse'";}else{$qw_purse_tab = "";}
				//if($mb_purse!=false) {$mb_purse_tab = ", `mb_purse`='$mb_purse'";}else{$mb_purse_tab = "";}
				if($be_purse!=false) {$be_purse_tab = ", `be_purse`='$be_purse'";}else{$be_purse_tab = "";}
				if($mt_purse!=false) {$mt_purse_tab = ", `mt_purse`='$mt_purse'";}else{$mt_purse_tab = "";}
				if($mg_purse!=false) {$mg_purse_tab = ", `mg_purse`='$mg_purse'";}else{$mg_purse_tab = "";}
				if($tl_purse!=false) {$tl_purse_tab = ", `tl_purse`='$tl_purse'";}else{$tl_purse_tab = "";}
				if($ac_purse!=false) {$ac_purse_tab = ", `ac_purse`='$ac_purse'";}else{$ac_purse_tab = "";}
				if($me_purse!=false) {$me_purse_tab = ", `me_purse`='$me_purse'";}else{$me_purse_tab = "";}
				if($vs_purse!=false) {$vs_purse_tab = ", `vs_purse`='$vs_purse'";}else{$vs_purse_tab = "";}
				if($ms_purse!=false) {$ms_purse_tab = ", `ms_purse`='$ms_purse'";}else{$ms_purse_tab = "";}

				if($db_d!=false|$db_m!=false|$db_y!=false) {$db_time_tab = ", `db_time`='$db_time', `db_date`='".DATE("d.m.Y", $db_time)."'";}else{$db_time_tab = "";}
				if($sex!=false) {$sex_tab = ", `sex`='$sex'";}else{$sex_tab = "";}
				if($fio!=false) {$fio_tab = ", `fio`='$fio'";}else{$fio_tab = "";}
				if($imay!=false) {$imay_tab = ", `imay`='$imay'";}else{$imay_tab = "";}

				$sql_user = mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'");
				$row_user = mysql_fetch_array($sql_user);

				$tab_id = $row_user["id"];
				$username = $row_user["username"];
				$tab_pass = $row_user["password"];
				$tab_email = $row_user["email"];
				$tab_email_ok = $row_user["email_ok"];
				$email_kod = $row_user["email_kod"];

				if($pass=="") {
					$new_pass = $tab_pass;
				}else{
					$new_pass = $pass;
					$_SESSION["userPas"] = md5($new_pass);
				}
				if($email=="$tab_email") {
					$email_kod = md5($username.$new_pass.$tab_email);
					$new_email = $tab_email;
					$email_ok = $tab_email_ok;
					$email_kod = $email_kod;
				}else{
					$new_email = $email;
					$email_ok = 0;
					$email_kod = md5($username.$new_pass.$email);
				}

				mysql_query("UPDATE `tb_users` SET 
					`password`='$new_pass', 
					`email`='$new_email', 
					`email_ok`='$email_ok', 
					`email_sent`='$email_sent', 
					`fl18`='$fl18', 
					`block_wmid`='$block_wmid', 
					`block_agent`='$block_agent',
					`frm_pos`='$frm_position'
					$db_time_tab 
					$sex_tab 
					$fio_tab 
					$imay_tab 
					$wm_purse_tab 
					$ym_purse_tab 
					$pm_purse_tab 
					$py_purse_tab 
					$qw_purse_tab 
					$be_purse_tab
				    $mt_purse_tab
				    $mg_purse_tab
				    $tl_purse_tab 
					$ac_purse_tab 
					$me_purse_tab 
					$vs_purse_tab 
					$ms_purse_tab 
				WHERE `username`='$username'") or die(mysql_error());

				if(isset($wm_purse_tab) && $wm_purse_tab!=false && isset($wm_id_x8) && $wm_id_x8!=false) {
					$sql_bl_wmid = mysql_query("SELECT `id` FROM `tb_black_wmid` WHERE `wmid`='$wm_id_x8'");
					if(mysql_num_rows($sql_bl_wmid)>0) {
						mysql_query("INSERT INTO `tb_black_users` (`name`,`why`,`ip`,`date`,`time`) 
						VALUES('$username','Повторная регистрация на проекте. WMID $wm_id_x8 занесен в черный список проекта.','$laip','".DATE("d.m.Y H:i")."','".time()."')") or die(mysql_error());
					}
				}

				echo '<span id="info-msg-profile" class="msg-ok">Данные успешно изменены!</span>';

				echo '<script type="text/javascript">
					setTimeout(function() {window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'");}, 10);
					HideMsg("info-msg-profile", 2000);
				</script>';
			}
		}
     }

		echo '<script type="text/javascript">
			setTimeout(function() {window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'");}, 10);
			HideMsg("info-msg-profile", 5000);
		</script>';
	}


	$sql_user = mysql_query("SELECT * FROM `tb_users` WHERE `username`='$username'");
	$row_user = mysql_fetch_array($sql_user);

	$id = $row_user["id"];
	$username = $row_user["username"];
	$password = $row_user["password"];

	$wmiduser = $row_user["wmid"];
	$wmruser = $row_user["wm_purse"];
	$block_wmid = $row_user["block_wmid"];
	$block_agent = $row_user["block_agent"];
	$email_sent = $row_user["email_sent"];

	$fl18 = $row_user["fl18"];
	$imay = $row_user["imay"];
	$checked1 = ($block_wmid==1) ? 'checked="checked"' : false;
	$checked2 = ($email_sent==1) ? 'checked="checked"' : false;
	$checked3 = ($block_agent==1) ? 'checked="checked"' : false;
	$selected = ($fl18==1) ? 'selected="selected"' : false;

	$email = $row_user["email"];
	$email_ok = $row_user["email_ok"];
	$email_kod = $row_user["email_kod"];
	$kod = md5($username.$password.$email);

	if($email_ok==1) {
		$email_ok_t = '<img src="img/ok2.gif" align="absmiddle" alt="" width="16" /> Ваш E-mail проверен.';
	}else{
		$email_ok_t = '<img src="img/error2.gif" align="absmiddle" alt="" width="16" /> Ваш E-mail еще не проверен. <a href="'.$_SERVER["PHP_SELF"].'?op=mod_email">[проверить]</a>';
	}
	
	if(isset($_GET["op"]) && limpiar($_GET["op"])=="mod_email") {
		if($email_ok==0) {
			mysql_query("UPDATE `tb_users` SET `email_kod`='$kod' WHERE `username`='$username'") or die(mysql_error());

			require_once($_SERVER['DOCUMENT_ROOT'].'/class/email.conf.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/class/smtp.class.php');
			
$link= "reg_email.php?uid=$id&check=$kod";
      
      $var = array('{ID}','{LOGIN}','{CODE}');
	    $zamena = array($id, $username,$link);
	    $msgtext = str_replace($var, $zamena, $email_temp['recc_1']);

      $mail_out = new mailPHP();
      if(!$mail_error = $mail_out->send($email,$username,iconv("CP1251", "UTF-8", 'Активация аккаунта на проекте '.$_SERVER["HTTP_HOST"]), iconv("CP1251", "UTF-8", $msgtext))) {
				echo '<span class="msg-ok">Письмо для активации аккаунта отправлено на Ваш e-mail!<br>Если в течение нескольких часов письмо не дошло, попробуйте запросить письмо еще раз. Если и в этот раз письмо не пришло, попробуйте указать email с другого почтового сервера.</span><br>';
			}else{
				echo '<span class="msg-error">Не удалось отправить сообщение. Повторите попытку позже!</span><br>';
			}
		}else{
		}
	}

	echo '<table class="tables_inv">';
	echo '<thead><tr align="center"><th align="center" colspan="2">Аватар</th></tr></thead>';
	echo '<tr>';
		echo '<td align="center" width="80" height="80"><div id="uploadButton" class="button"><div id="avatar2"><img class="avatar" src="avatar/'.$avatar.'" width="80" height="80" border="0" align="middle" alt="" /></div></div></td>';
		echo '<td align="left" valign="top"><div align="justify">';
			echo '<b>Кликните по аватару, чтобы изменить его!</b><br>';
			echo 'Картинка для аватара не должна превышать 200x200 пикселей, и быть не более 100 килобайт. Минимальный размер 60x60 пикселей. Принимаются картинки следующих типов: gif, jpg, jpeg, png. Все аватары будут приведены к размерам 80х80 пикселей.';
		echo '</div></td>';
	echo '</tr>';
	echo '</table>';

	echo '<form action="'.$_SERVER["PHP_SELF"].'" method="POST" id="newform">';
	echo '<table class="tables_inv">';
	echo '<thead><tr align="center"><th align="center" colspan="3">Персональная информация</th></tr></thead>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Логин для входа</td>';
		echo '<td width="200">'.$username.'</td>';
		//if($row_user["imay"]==false) {echo '<input type="text" name="imay" maxlength="160" value="" class="ok">';}else{echo $row_user["imay"];}
		echo '<td align="left"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Имя</td>';
		//echo '<td width="200">'.$username.'</td>';
		if($row_user["imay"]==false) {echo '<td width="200"><input type="text" name="imay" maxlength="160" value="" class="ok"></td>';}else{echo '<td width="200">'.$row_user["imay"].'</td>';}
		echo '<td align="left">Если указать будет отображаться на стене и в комментариях к новостям!</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">ФИО</td>';
		echo '<td colspan="2">';
			if($row_user["fio"]==false) {echo '<input type="text" name="fio" maxlength="160" value="" class="ok">';}else{echo $row_user["fio"];}
		echo '</td>';
	echo '</tr>';

	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Пол</td>';
		echo '<td>';
			if($row_user["sex"]>0 && $row_user["sex"]==1) {
				echo 'Мужчина';
			}elseif($row_user["sex"]>0 && $row_user["sex"]==2) {
				echo 'Женщина';
			}else{
				echo '<select name="sex" style="width:auto">';
					echo '<option value="0">Выбор....</option>';
					echo '<option value="1">Мужской</option>';
					echo '<option value="2">Женский</option>';
				echo '</select>';
			}
		echo '</td>';
		echo '<td align="left"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Дата рождения:</td>';
		echo '<td nowrap="nowrap">';
			$m_arr = array("Января","Февраля","Марта","Апреля","Мая","Июня","Июля","Августа","Сентября","Октября","Ноября","Декабря");

			//if($row_user["dbdate"]!=0) {
			//	echo DATE("d",$row_user["dbdate"])." ".$m_arr[DATE("m",$row_user["dbdate"])-1]." ".DATE("Y",$row_user["dbdate"])."г.";
			//}else{
				echo '<select name="db_d" style="width:auto;">';
					echo '<option value="">Выбор....</option>';
					for($i=1; $i<=31; $i++){
						echo '<option value="'.$i.'" '.(intval(DATE("d", $row_user["db_time"]))==intval($i) ? 'selected="selected"': false).'>'.$i.'</option>';
					}
				echo '</select>&nbsp;';
				echo '<select name="db_m" style="width:auto;">';
					echo '<option value="">Выбор....</option>';
					for($i=1; $i<=12; $i++){
						echo '<option value="'.$i.'" '.(intval(DATE("m", $row_user["db_time"]))==intval($i) ? 'selected="selected"': false).'>'.$m_arr[$i-1].'</option>';
					}
				echo '</select>&nbsp;';
				echo '<select name="db_y" style="width:auto;">';
					echo '<option value="">Выбор....</option>';
					for($i=(DATE("Y")-5); $i>=(DATE("Y")-92); $i--){
						echo '<option value="'.$i.'" '.(intval(DATE("Y", $row_user["db_time"]))==intval($i) ? 'selected="selected"': false).'>'.$i.'</option>';
					}
				echo '</select>';
			//}
		echo'</td>';
		echo '<td align="left"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">E-mail:</td>';
		echo '<td><input type="text" name="email" maxlength="50" value="'.$email.'" class="ok"></td>';
		echo '<td align="left">'.$email_ok_t.'</td>';
	echo '</tr>';

	echo '<thead><tr align="center" height="15px" ><th align="center" colspan="3">Платежные реквизиты</th></tr></thead>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap"><img src="/img/wm16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />Номер <span style="color:#006699;">WebMoney</span> [WMID]</td>';
		echo '<td>'.$row_user["wmid"].'</td>';
		echo '<td align="left"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap"><img src="/img/wmr16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />Номер счета <span style="color:#006699;">WebMoney</span> [WMR]</td>';
		if($row_user["wm_purse"]==false) {
			echo '<td><input type="text" class="ok" name="wm_purse" maxlength="13" value=""></td>';
		}else{
			echo '<td>'.$row_user["wm_purse"].'</td>';
		}
		echo '<td align="left">Пример: R012345678910</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap"><img src="/img/yandexmoney16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />Номер счета <span style="color:#006699;"><span style="color:#DE1200">Я</span>ндекс.Деньги</span></td>';
		if($row_user["ym_purse"]==false) {
			echo '<td><input type="text" class="ok" name="ym_purse" maxlength="20" value=""></td>';
		}else{
			echo '<td>'.$row_user["ym_purse"].'</td>';
		}
		echo '<td align="left">Пример: 410000000000000</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap"><img src="/img/perfectmoney16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />Номер счета <span style="color:#DE1200;">PerfectMoney</span></td>';
		if($row_user["pm_purse"]==false) {
			echo '<td><input type="text" class="ok" name="pm_purse" maxlength="20" value=""></td>';
		}else{
			echo '<td>'.$row_user["pm_purse"].'</td>';
		}
		echo '<td align="left">Пример: U1234567</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap"><img src="/img/payeer16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />Номер счета <span style="color:#000;">PAY</span><span style="color:#3498DB;">EER</span></td>';
		if($row_user["py_purse"]==false) {
			echo '<td><input type="text" class="ok" name="py_purse" maxlength="20" value=""></td>';
		}else{
			echo '<td>'.$row_user["py_purse"].'</td>';
		}
		echo '<td align="left">Пример: P1234567</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/qiwi16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#FC8000;">QIWI кошелек</span></td>';
		if($row_user["qw_purse"]==false) {
			echo '<td><input type="text" class="ok" name="qw_purse" maxlength="20" value=""></td>';
		}else{
			echo '<td>'.str_replace("+","", $row_user["qw_purse"]).'</td>';
		}
		echo '<td align="left">Пример: 79201234567</td>';
	echo '</tr>';
echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/be16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">Номер телефона</span> <span style="color:#0f1010">Би</span><span style="color:#e0b806">ла</span><span style="color:#0f1010">йн</span></td>';
		if($row_user["be_purse"]==false) {
			echo '<td><input type="text" class="ok" name="be_purse" maxlength="20" value="" placeholder=" Только для абонентов России"></td>';
		}else{
			echo '<td>'.str_replace("+","", $row_user["be_purse"]).'</td>';
		}
		echo '<td align="left">Пример: 79031234567</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/mt16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">Номер телефона</span> <span style="color:#ff0000; font-weight:bold;">МТС</span></td>';
		if($row_user["mt_purse"]==false) {
			echo '<td><input type="text" class="ok" name="mt_purse" maxlength="20" value="" placeholder=" Только для абонентов России"></td>';
		}else{
			echo '<td>'.str_replace("+","", $row_user["mt_purse"]).'</td>';
		}
		echo '<td align="left">Пример: 79131234567</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/mg16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">Номер телефона</span> <span style="color:#049C64">Мега</span><span style="color:#00649e">фон</span></td>';
		if($row_user["mg_purse"]==false) {
			echo '<td><input type="text" class="ok" name="mg_purse" maxlength="20" value="" placeholder=" Только для абонентов России"></td>';
		}else{
			echo '<td>'.str_replace("+","", $row_user["mg_purse"]).'</td>';
		}
		echo '<td align="left">Пример: 79231234567</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/tl16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">Номер телефона</span> <span style="color:#0c0c0c">TELE2</span></td>';
		if($row_user["tl_purse"]==false) {
			echo '<td><input type="text" class="ok" name="tl_purse" maxlength="20" value="" placeholder=" Только для абонентов России"></td>';
		}else{
			echo '<td>'.str_replace("+","", $row_user["tl_purse"]).'</td>';
		}
		echo '<td align="left">Пример: 79511234567</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/advcash_18x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span></td>';
		if($row_user["ac_purse"]==false) {
			echo '<td><input type="text" class="ok" name="ac_purse" maxlength="20" value="" placeholder=" RUR кошелек, пример: R 1234 5678 9123"></td>';
		}else{
			$nbsp_ac = " ";
			$row_user["ac_purse"] = substr($row_user["ac_purse"], 0, 1).$nbsp_ac.substr($row_user["ac_purse"], 1, 4).$nbsp_ac.substr($row_user["ac_purse"], 5, 4).$nbsp_ac.substr($row_user["ac_purse"], 9, 4);
			echo '<td>'.$row_user["ac_purse"].'</td>';
		}
		echo '<td align="left">Пример: R 1234 5678 9123</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/me16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span></td>';
		if($row_user["me_purse"]==false) {
			echo '<td><input type="text" class="ok" name="me_purse" maxlength="22" value="" placeholder="Только карты Банка России"></td>';
		}else{
			echo '<td>'.$row_user["me_purse"].'</td>';
		}
		echo '<td align="left">Пример: 676102XXXX78551100</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/vs16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#1c0cf5">VISA</span></td>';
		if($row_user["vs_purse"]==false) {
			echo '<td><input type="text" class="ok" name="vs_purse" maxlength="22" value="" placeholder="Только карты Банка России"></td>';
		}else{
			echo '<td>'.$row_user["vs_purse"].'</td>';
		}
		echo '<td align="left">Пример: 412107XXXX785577</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td align="left" valign="bottom" nowrap="nowrap"><img src="/img/ms16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span></td>';
		if($row_user["ms_purse"]==false) {
			echo '<td><input type="text" class="ok" name="ms_purse" maxlength="22" value="" placeholder="Только карты Банка России"></td>';
		}else{
			echo '<td>'.$row_user["ms_purse"].'</td>';
		}
		echo '<td align="left">Пример: 512107XXXX785577</td>';
	echo '</tr>';

	echo '<thead><tr align="center"><th align="center" colspan="3">Настройки аккаунта</th></tr></thead>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Выбор системы фрейма</td>';
		echo '<td>';
			echo '<select name="frm_pos" class="ok">';
				echo '<option value="0" '.($row_user["frm_pos"]==0 ? 'selected="selected"' : false).'>Расположение вверху</option>';
				echo '<option value="1" '.($row_user["frm_pos"]==1 ? 'selected="selected"' : false).'>Расположение внизу</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="left"><span style="color:#FF0000;">new</span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Фильтр 18+</td>';
		echo '<td>';
			echo '<select name="fl18" class="ok">';
				echo '<option value="0" '.($row_user["fl18"]==0 ? 'selected="selected"' : false).'>Отключен</option>';
				echo '<option value="1" '.($row_user["fl18"]==1 ? 'selected="selected"' : false).'>Включен</option>';
			echo '</select>';
		echo '</td>';
		echo '<td align="left"><span>Отображать любые сайты для заработка</span></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" colspan="3"><input type="checkbox" name="block_wmid" value="1" '.$checked1.'> Входить в аккаунт только через WmLogin</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left"><input type="checkbox" name="block_agent" value="1" '.$checked3.'> дополнительная защита аккаунта</td>';
		echo '<td align="left" colspan="2">если при входе в аккаунт IP адрес или браузер будет отличаться, Вам нужно будет указать "пароль для операций" для проверки</td>';
	echo '</tr>';

	echo '<tr>';
		echo '<td align="left"><input type="checkbox" name="email_sent" value="1" '.$checked2.'> отправлять рекламные письма на e-mail</td>';
		echo '<td align="left" colspan="2">уберите галочку, и письма будут высылаться только в аккаунт</td>';
	echo '</tr>';

	echo '<thead><tr align="center" ><th align="center" colspan="3">Если нужно сменить пароль</th></tr></thead>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Пароль:</td>';
		echo '<td><input type="password" name="pass" maxlength="20" value="" class="ok"></td>';
		echo '<td align="left" rowspan="2">не указывайте ничего, если не хотите менять свой пароль</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">Подтвердите пароль:</td>';
		echo '<td><input type="password" name="cpass" maxlength="20" value="" class="ok"></td>';
	echo '</tr>';

	echo '<thead><tr align="center"><th align="center" colspan="3">Сохранение данных</th></tr></thead>';
	echo '<tr>';
		echo '<td colspan="3" align="center"><span style="color:#FF0000;">для изменения данных необходимо указать пароль для операций</span>,<br>[<a href="javascript: void(0);" onClick="sendchkpass();">получить пароль для операций</a>]</td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap"><b>Пароль для операций:</b></td>';
		echo '<td><input type="password" name="pass_oper" value="" class="ok"></td>';
		echo '<td align="center"><input type="submit" class="proc-btn" value="Сохранить" style="float:none"></td>';
	echo '</tr>';
	echo '</table>';
	echo '</form>';
}

include('footer.php');?>