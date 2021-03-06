<?php

class ymAPI {
	const URI_YM_API = 'https://money.yandex.ru/api';
	const URI_YM_AUTH = 'https://sp-money.yandex.ru/oauth/authorize';
	const URI_YM_TOKEN = 'https://sp-money.yandex.ru/oauth/token';
	const CHARSET = 'UTF-8';
	public $client_id = '';
	public $redirect_url = '';
	public $sertificate = '/var/www/lilac-www/data/www/scorpionbux.info/merchant/yandexmoney/ca-certificate.crt';

	private $scopeArray = Array(' account-info',' operation-history', ' operation-details',' payment', ' payment-shop', ' payment-p2p',' money-source("wallet","card")');

	public function __construct($client_id, $redirect_url) {
		$this->client_id = $client_id;
		$this->redirect_url = $redirect_url;
	}
  
	public function accountInfo($accessToken) {
		$curl = $this->initCurl(self::URI_YM_API . '/account-info', $this->sertificate, NULL, $accessToken);
		$response = $this->execCurl($curl);
		return $response;
	}
  
	public function operationHistory($accessToken, $type = NULL, $records = NULL, $startRecord = NULL) {
		$paramArray = Array();
		if ($type != NULL) $paramArray['type'] = $type;
		if ($startRecord != NULL) $paramArray['start_record'] = $startRecord;
		if ($records != NULL) $paramArray['records'] = $records;
		if (count($paramArray) > 0) $params = http_build_query($paramArray); else $params = '';
		$curl = $this->initCurl(self::URI_YM_API . '/operation-history', $this->sertificate, $params, $accessToken);
		$response = $this->execCurl($curl);
		return $response;
	}
  
	public function operationDetail($accessToken, $operationId) {
		$paramArray['operation_id'] = $operationId;
		$params = http_build_query($paramArray);
		$curl = $this->initCurl(self::URI_YM_API . '/operation-details',
		$this->sertificate, $params, $accessToken);
		$response = $this->execCurl($curl);
		return $response;
	}

	public function checkScope($scope = array()) {
		$count = count($this->scopeArray);
		$res = '';
    
		for ($n = 0; $n < $count; $n++) {          
			$key = trim($this->scopeArray[$n]);
			if (array_key_exists($key, $scope)) {      
				$res = $res . '' . $scope[$key];
			}
		}

		return $res;
	}
  
  	public function authorizeUri($scope = NULL) {    
		$scope = trim($scope);
		$url = self::URI_YM_AUTH . "?client_id=$this->client_id" . "&response_type=code&scope=$scope&redirect_uri=$this->redirect_url";
		return $url;
	}
  
	public function getOAuthToken($code) {
		$paramArray['grant_type'] = 'authorization_code';
		$paramArray['client_id'] = $this->client_id;
		$paramArray['code'] = $code;
		$paramArray['redirect_uri'] = $this->redirect_url;
		$params = http_build_query($paramArray);
		$curl = $this->initCurl(self::URI_YM_TOKEN, $this->sertificate, $params);
		$response = $this->execCurl($curl);
    
		if (isset($response['error'])) {
			return 100;
		}  
            
		return $response['access_token'];
	}
    

	private function initCurl($uri, $certificateChain, $postParams, $accessToken = NULL) {
		$curl = curl_init($uri);
		$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=' . self::CHARSET;
		if (isset($accessToken)) $headers[] = 'Authorization: Bearer ' . $accessToken;
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($curl, CURLOPT_FORBID_REUSE, TRUE);
		curl_setopt($curl, CURLOPT_POST, TRUE);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postParams);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, TRUE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($curl, CURLOPT_CAINFO, $certificateChain);
		return $curl;
	}

	private function execCurl($curl) {
		$response = json_decode(curl_exec($curl), TRUE);
		$error = curl_error($curl);

		if ($error != NULL)
		$error = 'cURL error: ' . $error . '; ';
		curl_close($curl);
    
		if ($response == NULL) {
			echo 'error 103';
			exit();
		}
    
		return $response;
	}
  
	public function requestPaymentP2P($accessToken, $to, $amount, $comment, $message) {
		$paramArray['pattern_id'] = 'p2p';
		$paramArray['to'] = $to;
		$paramArray['amount_due'] = $amount;
		$paramArray['comment'] = $comment;
		$paramArray['message'] = $message;
		$params = http_build_query($paramArray);
		$curl = $this->initCurl(self::URI_YM_API . '/request-payment', $this->sertificate, $params, $accessToken);
		$response = $this->execCurl($curl);
		return $response;
	}

	public function processPayment($accessToken, $requestId, $moneySource = 'wallet', $csc = NULL) {
		$cc = '';

		if ($moneySource === 'card') {
			$ms = 'card';

			if ($csc == NULL) {
				echo 'error 1008';
				die();
			}
		}else{
			$ms = 'wallet';
			$cc = '';
		}

		$paramArray['request_id'] = $requestId;
		$paramArray['money_source'] = $ms;
    
		if ($cc !== '')
		$paramArray['csc'] = $cc;
		$params = http_build_query($paramArray);
		$curl = $this->initCurl(self::URI_YM_API . '/process-payment', $this->sertificate, $params, $accessToken);
		$response = $this->execCurl($curl);
		return $response;
	}
}

?>