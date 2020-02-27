<?PHP
define('HTTP_HOST', $_SERVER["HTTP_HOST"]);

#BTC
define('BTC_GUID', '');
define('BTC_PASS', '');

#WebMoney
define('WM_WMID', '');
define('WM_WMR', '');
define('WM_FILE_KEY', WM_WMID.'.kwm');
define('WM_FILE_PASS', '');
define('WM_SECRET_KEY', '');

#QiWi
define('QW_LOGIN', '');
define('QW_PASSWORD', '');

#YandexMoney
define ('YM_CLIENT_PURSE', '410018419705382');
define ('YM_CLIENT_ID', '4585C52F28D4219EC7BD9F26700123B963AE2ABA8F5C9633E01DE1A19886FFBF');
define ('YM_REDIRECT_URL', 'https://'.$_SERVER["HTTP_HOST"].'/merchant/yandexmoneytoken/get_token.php');
define ('YM_CLIENT_SECRET', '3996D98C39B8B6417D179A70A7BBD32CD243FE75EF33EAA0B2277F07053FD8603C76717042B5015DD7F1E7BB40009D613BBD6749EA143B2BA14184C6F85E486C');
define ('YM_SECRET_KEY', '');
define ('YM_TOKEN', YM_CLIENT_PURSE.'.');

#LiqPay
define('LP_MERCH_ID', '');//ID мерчанта (прием платежей на карту/счет). Он же public_key
define('LP_PRIVAT_KEY', '');//Подпись мерчанта
define('LP_RES_URL', 'http://'.$_SERVER["HTTP_HOST"].'/payok.php');//страница на которую вернется клиент
define('LP_SER_URL', 'http://'.$_SERVER["HTTP_HOST"].'/merchant/liqpay/lp_payresult.php');//страница на которую придет ответ от сервера
define('LP_CURR', 'RUR'); // валюта
define('LP_SENDBOX', '0'); // 1 = тестовый, 0 = робочий
define('LP_TYPE', 'buy'); //buy - покупка, donate - пожертвование, subscribe - подписка
define('LP_LANG', 'ru'); //язык

#PerfectMoney
define('PM_MEMBER_ID', '');
define('PM_PASSWORD', '');
define('PM_PHRASE_HASH', '');
define('PM_MEMBER_USD', '');
define('PM_MEMBER_EUR', '');
define('PM_PAYMENT_UNITS', 'USD');
define('PM_TIMESTAMPGMT', time());

#Payeer
define('PY_ACC_NUMBER', '');
define('PY_API_ID', '');
define('PY_API_KEY', '');
define('PY_M_SHOP', '');
define('PY_M_CURR', '');
define('PY_M_KEY', '');

#RoboKassa
define('RK_LOGIN', '');
define('RK_PASS1', '');
define('RK_PASS2', '');
define('RK_CURR', 'PCR');
define('RK_CULTURE', 'ru');

#InterKassa
define('IK_SHOP_ID', '');
define('IK_SECRET_KEY', '');
define('IK_CURR', 'RUB');
define('IK_ENC', 'cp-1251');
define('IK_LOC', 'ru');

#ZPayment
define('ZP_ID_SHOP', '16413');//ID магазина в Z-Payment
define('ZP_SECRET_KEY', '');//Merhant Key ключ магазина

#WalletOne
define('WO_LOGIN', '');
define('WO_SECRET_KEY', '');
define('WO_PTENABLED', 'WalletOneRUR');
define('WO_CURRENCY_ID', '643'); // 643 — Російські рублі  980 — Українські гривні
?>