<?php
require_once($_SERVER['DOCUMENT_ROOT']."/merchant/payment_config.php");
require_once(dirname(__FILE__) . '/lib/YandexMoney.php');

$code = $_GET['code'];
if (!isset($code)) { //�������� �������� �� �������� ������������� ��������� ������ �����������
    $scope = 
        "account-info " .
        "payment-p2p " .
        "payment-shop";

    $authUri = YandexMoney::authorizeUri(YM_CLIENT_ID, YM_REDIRECT_URL, $scope);
    header('Location: ' . $authUri);
    exit();
}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>��������� ������ Yandex.Money</title>
</head>
<body>
<div>
    <h3>��������� ������ Yandex.Money</h3>

    <?php

        $ym = new YandexMoney(YM_CLIENT_ID, './ym.log'); //�������� ���������� ������ YandexMoney ��� ������ � API
        $receiveTokenResp = $ym->receiveOAuthToken($code, YM_REDIRECT_URL, YM_CLIENT_SECRET);

        print "<p>";
        if ($receiveTokenResp->isSuccess()) {
            $token = $receiveTokenResp->getAccessToken();
            print "Received token: " . $token; // �����: ������
        } else {
            print "Error: " . $receiveTokenResp->getError();
            die();
        }
        print "</p>";

        $resp = $ym->accountInfo($token);

        print "<p>";
            echo 'Identified: '; if($resp->getIdentified()){echo 'Yes';}else{echo 'No';}; echo '</br>'; // �����: �������������
            echo 'Account: '.$resp->getAccount().'</br>'; // �����: ������ �����
            echo 'Balance(RUB): '.$resp->getBalance(); // �����: �������
        print "</p>";
    ?>
</div>
</body>
</html>