<?php
if(!DEFINED("ADMINKA") | !DEFINED("NOTIF_NOT_ACTIVE_USERS")) exit(my_json_encode("ERROR", "Access denied!"));

if($option == "notif-config-save") {
	$notif_status = (isset($_POST["notif_status"]) && preg_match("|^[0-1]{1}$|", trim($_POST["notif_status"]))) ? intval(trim($_POST["notif_status"])) : 0;
	$notif_days_not_active = ( isset($_POST["days_not_active"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["days_not_active"])) && intval(trim($_POST["days_not_active"]))>=5 && intval(trim($_POST["days_not_active"]))<=1000 ) ? abs(intval(trim($_POST["days_not_active"]))) : 15;
	$notif_days_resending = ( isset($_POST["days_resending"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["days_resending"])) && intval(trim($_POST["days_resending"]))>=3 && intval(trim($_POST["days_resending"]))<=1000 ) ? abs(intval(trim($_POST["days_resending"]))) : 7;
	$notif_text_message = (isset($_POST["text_message"])) ? limpiarez($_POST["text_message"]) : false;
	$notif_text_message = get_magic_quotes_gpc() ? stripslashes($notif_text_message) : $notif_text_message;

	if($notif_text_message == false) {
		$result_text = "ERROR";
		$message_text = "Укажите текст сообщения!";
		exit(my_json_encode($result_text, $message_text));

	}else{
		$mysqli->query("UPDATE `tb_notif_conf` SET `status`='".escape($notif_status)."', `days_not_active`='".escape($notif_days_not_active)."', `days_resending`='".escape($notif_days_resending)."', `text_message`='".escape($notif_text_message)."' WHERE `id`='1'") or die(my_json_encode("ERROR", $mysqli->error));
	
		$result_text = "OK";
		$message_text = '<span class="msg-ok">Изменения успешно сохранены!</span>';
		exit(my_json_encode($result_text, $message_text));
	}

}else{
	$result_text = "ERROR";
	$message_text = "Option [$option] not found...";
	exit(my_json_encode($result_text, $message_text));
}

?>