<?php
require_once("qiwi_config.php");
require_once("qiwi_class.php");

$qiwi = new QIWI_API(QIWI_PHONE_ID, QIWI_PASSWORD, __DIR__.DIRECTORY_SEPARATOR."qiwi_cookie", false, false, 1);

//Вход в систему
if(!$qiwi->login()){
    die("Failed to login into QIWI wallet: " . $qiwi->getLastError());
}

//Загрузка баланса
$balance = $qiwi->loadBalance();
echo "BALANCE:\n";
print_r($balance);

?>