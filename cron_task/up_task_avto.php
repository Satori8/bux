<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../') );
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/merchant/func_mysql.php");
require(ROOT_DIR."/funciones.php");

$domen = "lilacbux.com";

$up_task_pr = 1;
$sql_w = mysql_query("SELECT * FROM `tb_ads_task` ");
if(mysql_num_rows($sql_w) > 0) {

while($row_w = mysql_fetch_array($sql_w)) {

		$id = $row_w["id"];
		//$ident = $row_w["ident"];
		$user_name = $row_w["username"];
		$up = $row_w["up"];
		
		
	if($up == "1") {	
$sql = mysql_query("SELECT `id`,`date_up` FROM `tb_ads_task` WHERE `id`='$id' AND `username`='$user_name'");
$sql_b = mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='$user_name'"));
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];

		if($sql_b['money_rb'] >= $up_task_pr) {
			mysql_query("UPDATE `tb_ads_task` SET `date_up`='".time()."' WHERE `id`='$id' AND `username`='$user_name'") or die(mysql_error());
			mysql_query("UPDATE `tb_users` SET `money_rb`=`money_rb`-'$up_task_pr' WHERE `username`='$user_name'") or die(mysql_error());
			
			#### ИНВЕСТ ПРОЕКТ ###
			stat_pay("task_up", $up_task_pr);
			invest_stat($up_task_pr, 4);
			#### ИНВЕСТ ПРОЕКТ ###
			
			mysql_query("INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) 
			VALUES('1','$user_name','0','".DATE("d.m.Y H:i")."','".time()."','$up_task_pr','Авто-поднятие задания в списке [ID:".$id."]','Списано','rashod')") or die(mysql_error());
			
		}else{
			$sql_mes = mysql_query("SELECT `id`,`email` FROM `tb_users` WHERE `username`='$user_name'");
			if(mysql_num_rows($sql_mes)>0) {
			$row_mes = mysql_fetch_array($sql_mes);

			$email_tab = $row_mes["email"];
			
			//$to = "$email_tab";
			$subj = "Уведомление о не хватки средств на $domen";
		$msg = "<html><head><meta http-equiv='Content-Type' content='text/html; windows-1251'>
        
		        <style type='text/css'>
                        html, tbody {
                        display: table-row-group;
                        vertical-align: middle;
                        border-color: inherit;
	                }
                        </style>
                        </head>
                        <table align='center' border='0' cellpadding='5' cellspacing='0' style='width:100%;background-color:#e5e5e5;'>
                        <tbody>
                        <tr><td align='center'>
                        <table align='center' cellpadding='0' cellspacing='0' style='border:1px solid #DDD;width:100%;background-color:#751bdc;'>
                        <tbody>        
                        <tr><td align='left' style='background:url(https://lilacbux.com/img/logo/logo.png) no-repeat bottom left;padding:46px;'></td></tr>
                        <tr><td align='center'>
                        <table align='center' cellpadding='0' cellspacing='0' style='border:1px solid #DDD;width:100%;background-color:#FFF;'>
                        
                         <tr><td style='background-color:#790af7;font-size:16px;line-height:16px;text-align:center;padding:15px;color:#FFF;font-weight:normal;'>Уведомление о не хватки средств на <a style='text-decoration:none;color:#FFF;' class='daria-goto-anchor' target='_blank' rel='noopener noreferrer'><b>".$domen."</b></a></td></tr>
                        <tr><td align='left' style='font-size:12px;font-family:Arial,Helvetica,sans-serif;line-height:20px;padding:20px;'>
                        <u>На вашем рекламном счете не достаточно средств для автоматического поднятия задания <b>ID: ".$id."</b></u><br><br>
                        
                        
                        Перейдите в ваш аккаунт для пополнения баланса рекламной площадке <b>ID: ".$id."</b>! <a href=http://".$_SERVER["HTTP_HOST"]." style='color:#009E58;'><b>".$domen."</b></a>
                        </td></tr>
                        <tr><td align='left' style='border-top:1px solid #DDD;font-size:12px;padding:10px 20px;'>С уважением, автоматическая служба уведомлений <a href=http://".$_SERVER["HTTP_HOST"]." style='color:#009E58;'><b>".$domen."</b></a></td></tr>
                        </tbody>
                        </table>
                        </td></tr>
                        </tbody>
                        </table>
                        </html>";

                $headers = "MIME-Version: 1.0\r\n";
		$headers.= "Content-Type: text/html; charset=windows-1251\r\n";
		$headers.= "From: $domen <$domen@$domen>\r\n";
		$headers.= "FromName: $domen\r\n";
                $headers.= "X-Mailer: PHP/".phpversion()."\r\n";
                
                if(mail($email_tab, $subj, $msg, $headers)) {

			$user_name = false;
			$subject = false;
			$message = false;
		}
		}
		}
	
}
}
}
}
?>