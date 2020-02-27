<?php

/**
 * QIWI_PHP
 * Класс для браузинга с поддержкой куки
 */

/*
 * Юзерагент браузера
 */
define('USERAGENT_USERAGENT', "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.116 Safari/537.36");
//define('USERAGENT_USERAGENT', $_SERVER["HTTP_USER_AGENT"]);
/**
 * Количество попыток выполнения запроса, прежде чем он будет помечен как неудавшийся.
 */
define('USERAGENT_SB_MAX_HTTP_RETRIES', 3);
define('USERAGENT_METHOD_GET', "get");
define('USERAGENT_METHOD_POST', "post");
define('USERAGENT_METHOD_OPTIONS', "options");
define('USERAGENT_METHOD_PUT', 'put');

class UserAgent2{
    private $debug;
    private $lastErrorNo;
    private $lastErrorStr;
    private $ch;
    private $lastStatus;
    private $lastHeaders;

    function __construct($cookie_file, $debug=false){
        $this->setDebug($debug);
        $this->curlSetCookie($cookie_file);
        $this->curlInit();
    }

    function clearCookies(){
        if($this->cookie_file && file_exists($this->cookie_file)){
            unlink($this->cookie_file);
        }
    }

    function getStatus(){
        return $this->lastStatus;
    }

    function getHeader($name=false){
        if($name){
            return isset($this->lastHeaders[$name]) ? $this->lastHeaders[$name] : false;
        }
        return $this->lastHeaders;
    }

    function curlSetCookie($cookie_file){
        $this->cookie_file = $cookie_file;
    }

    function getMyIP(){
        $content = $this->request(USERAGENT_METHOD_GET, "http://myip.ru/index_small.php");
        if(preg_match("#<tr><td>([0-9]+)\\.([0-9]+)\\.([0-9]+)\\.([0-9]+)</td></tr>#", $content, $m)){
            return "{$m[1]}.{$m[2]}.{$m[3]}.{$m[4]}";
        }
        return false;
    }

    public function curlInit(){
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_USERAGENT, USERAGENT_USERAGENT);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($this->ch, CURLOPT_ENCODING, '');
        curl_setopt($this->ch, CURLOPT_HEADER, 1);
    }

    function closeCurl(){
        curl_close($this->ch);
    }

    function __destruct(){
        $this->closeCurl();
    }

    /**
     * Установить прокси, для работы со сбербанком
     * @param $proxy
     * @param bool $proxyAuth
     * @param int $proxyType
     * @param bool $basicAuth
     * @param bool $proxyTunnel
     */
    function setProxy($proxy, $proxyAuth=false, $proxyType=CURLPROXY_HTTP, $basicAuth=false, $proxyTunnel=false){
        if($proxy){
            curl_setopt($this->ch, CURLOPT_PROXY, $proxy);
            curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, $proxyTunnel);
            if($proxyAuth){
                curl_setopt($this->ch, CURLOPT_PROXYTYPE, $proxyType);
                curl_setopt($this->ch, CURLOPT_PROXYUSERPWD, $proxyAuth);
            }
            if($basicAuth){
                curl_setopt($this->ch, CURLOPT_PROXY, CURLAUTH_BASIC);
            }
        }
    }

    /**
     * Вывод отладочной информации на экран
     * @param $msg
     */
    private function trace($msg){
        if($this->debug){
            echo "[SberbankAPI] " . trim($msg) . "\n";
        }
    }

    function setDebug($debug_mode){
        $this->debug = $debug_mode;
    }

    function request($method, $url, $ref=false, $data=false, $additionalHeaders=null, $verbose=false){
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_VERBOSE, $this->debug || $verbose);
        $headers = array(
            //'X-Requested-With' => 'XMLHttpRequest'
        );
        if($additionalHeaders){
            foreach($additionalHeaders as $k=>$v){
                $headers[$k] = $v;
            }
        }
        $tmp = $headers;
        $headers = array();
        foreach($tmp as $k=>$v){
            $headers[] = "$k: $v";
        }
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
        if($ref) {
            curl_setopt($this->ch, CURLOPT_REFERER, $ref);
        }else{
            curl_setopt($this->ch, CURLOPT_AUTOREFERER, 1);
        }
        if($this->cookie_file) {
            curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie_file);
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie_file);
        }else{
            curl_setopt($this->ch, CURLOPT_COOKIEFILE, false);
            curl_setopt($this->ch, CURLOPT_COOKIEJAR, false);
        }
        if($method == USERAGENT_METHOD_POST) {
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($this->ch, CURLOPT_HTTPGET, 0);
        }else if($method == USERAGENT_METHOD_GET){
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($this->ch, CURLOPT_POST, NULL);
            curl_setopt($this->ch, CURLOPT_HTTPGET, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, NULL);

        }else if($method == USERAGENT_METHOD_OPTIONS){
            curl_setopt($this->ch, CURLOPT_POST, NULL);
            curl_setopt($this->ch, CURLOPT_HTTPGET, 0);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, 0);
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "OPTIONS");
            if($data){
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
            }
        }else if($method == USERAGENT_METHOD_PUT){
            curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT");
            if($data){
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
            }else{
                curl_setopt($this->ch, CURLOPT_POSTFIELDS, false);
            }
        }

        //Выполняем запрос
        $ret = false;
        $max_retry = USERAGENT_SB_MAX_HTTP_RETRIES;
        while(true) {
            $data = curl_exec($this->ch);
            $header_size = curl_getinfo($this->ch, CURLINFO_HEADER_SIZE);
            $header = substr($data, 0, $header_size);
            $ret = substr($data, $header_size);

            $tmp = preg_split("/\n/", $header);
            $header = array();
            foreach($tmp as $line){
                if(preg_match("/^([^:]+): (.*)$/", $line, $m)){
                    $header[$m[1]] = trim($m[2]);
                }
            }

            $this->lastErrorNo = curl_errno($this->ch);
            $this->lastErrorStr = curl_error($this->ch);
            $this->lastStatus = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
            $this->lastHeaders = $header;

            if(in_array($this->lastErrorNo, array(CURLE_OPERATION_TIMEOUTED, CURLE_COULDNT_CONNECT, CURLE_RECV_ERROR)) && $max_retry > 0){
                $this->trace("HTTP ERROR: Timed out. Retry ($max_retry left)...");
                $max_retry--;
                continue;
            }
            if($this->lastErrorNo != 0){
                $this->trace("HTTP ERROR: {$this->lastErrorStr} [{$this->lastErrorNo}]");
            }
            break;
        }

        $ret = trim($ret);

        return $ret;
    }

    function setCurlOption($opt, $val){
        curl_setopt($this->ch, $opt, $val);
    }

    function postForm($url, $postData, $ref=false){
        $headers = array(
            'Content-type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        );
        return $this->request(USERAGENT_METHOD_POST, $url, $ref, $postData, $headers);
    }

    function saveFromUrl($url, $outFile, $binary=false){
        $data = $this->request(USERAGENT_METHOD_GET, $url);
        if(!($fp = fopen($outFile, "w" . ($binary ? "b" : "")))){
            $this->lastErrorStr = "Failed to create file $outFile";
            return false;
        }
        fwrite($fp, $data);
        fclose($fp);
        return true;
    }

    function downloadFile($url, $saveToFile, $method=USERAGENT_METHOD_GET, $postdata=null, $binary=false){
        $fp = fopen($saveToFile, "w" . ($binary ? "b" : "") . "+");
        if(!$fp){
            $this->lastErrorStr = "Failed to open file for writing: $saveToFile";
            return false;
        }
        $this->setCurlOption(CURLOPT_FILE, $fp);
        $this->request($method, $url, false, $postdata);
        $this->setCurlOption(CURLOPT_FILE, null);

        fclose($fp);

        return true;
    }
}
