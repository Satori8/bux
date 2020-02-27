<?php
DEFINE("ROOT_DIR", realpath(dirname(__FILE__).'/../../') );
require(ROOT_DIR."/config.php");
require_once(ROOT_DIR."/api/qiwi/qiwi_config.php");
require_once(ROOT_DIR."/api/qiwi/qiwi_class.php");

$sql = mysql_query("SELECT `price` FROM `tb_config_pay` WHERE `item`='pay_status' AND `eps`='qw'") or die(mysql_error());
$pay_status["qiwi"] = number_format(mysql_result($sql,0,0), 0, ".", "");

if($pay_status["qiwi"] > 0) {
	$qiwi = new QIWI_API(QIWI_PHONE_ID, QIWI_PASSWORD, __DIR__.DIRECTORY_SEPARATOR."qiwi_cookie", false, false, 1);

	if(!$qiwi->login()){
	    die("Failed to login into QIWI wallet: " . $qiwi->getLastError());
	}
	$balance = $qiwi->loadBalance();
}

?>