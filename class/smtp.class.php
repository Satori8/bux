<?
final class mailPHP{
	private $host, $port, $login, $pass, $cto, $charset, $code, $smtp_conn;
	private $status = FALSE;

	public function __construct() {
	  
	  global $email_conf;
	  $this->host = $email_conf["smtp_host"];
	  $this->port = $email_conf["smtp_port"];
	  $this->login = $email_conf["smtp_log"];
	  $this->pass = $email_conf["smtp_pass"];
	  $this->cto = $email_conf["smtp_name"];
	  $this->charset = $email_conf["smtp_charset"];
	  $this->code = 'text-'.time();
	  
	}

    private function get_data($smtp_conn){
      $data="";
      while($str = fgets($smtp_conn,515)){
        $data .= $str;
        if(substr($str,3,1) == " ") { break; }
      }
      return $data;
    }

    private function socet_init() {
      $this->smtp_conn = fsockopen($this->host, $this->port,$errno, $errstr, 30);
      if(!$this->smtp_conn) { return 1; }
      $this->get_data($this->smtp_conn);

      fputs($this->smtp_conn,"EHLO ".$this->host."\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 250) { return 2; }

      fputs($this->smtp_conn,"AUTH LOGIN\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 334) { return 3; }

      fputs($this->smtp_conn,base64_encode($this->login)."\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 334) { return 4; }

      fputs($this->smtp_conn,base64_encode($this->pass)."\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 235) { return 5; }
    }

    private function mail_header($name, $email, $tema) {
      $header="Date: ".date("D, j M Y G:i:s")." +0300\r\n";
      $header.="From: =?".$this->charset."?Q?".str_replace("+","_",str_replace("%","=",urlencode($this->cto)))."?= <".$this->login.">\r\n";
      $header.="Reply-To: =?".$this->charset."?Q?".str_replace("+","_",str_replace("%","=",urlencode($this->cto)))."?= <".$this->login.">\r\n";
      $header.="Message-ID: <".time()."@".$this->host.">\r\n";
      $header.="To: =?".$this->charset."?Q?".str_replace("+","_",str_replace("%","=",urlencode($name)))."?= <".$email.">\r\n";
      $header.="Subject: =?".$this->charset."?Q?".str_replace("+","_",str_replace("%","=",urlencode($tema)))."?=\r\n";
      $header.="MIME-Version: 1.0\r\n";
      $header.="Content-Type: multipart/alternative; boundary=\"".$this->code."\"\r\n";
      $header.="Content-Transfer-Encoding: 8bit\r\n";
      return $header;
    }

    private function mail_from($size_msg) {
      fputs($this->smtp_conn,"MAIL FROM:<".$this->login."> SIZE=".$size_msg."\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 250) {return 6;}
    }

    private function mail_to($To) {
      fputs($this->smtp_conn,"RCPT TO:<".$To.">\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 250 AND substr($this->get_data($this->smtp_conn),0,3) != 251) {return 7;}
    }

    private function mail_data($header, $message) {
      fputs($this->smtp_conn,"DATA\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 354) {return 8;}

      fputs($this->smtp_conn,$header."\r\n".$message."\r\n.\r\n");
      if(substr($this->get_data($this->smtp_conn),0,3) != 250) {return 9;}
    }

    private function txt_mail($message){
      $code_html = str_replace ("\r\n","", $message);
      $code_html = str_replace ("\n","", $code_html);
      $code_html = str_replace ("\r","", $code_html);

      $find = array ("\n", "");
      $replace = array ("'\<br(\s*)?\/?\>'i", "'\<head\>(.+?)\<\/head\>'si");

      $code_html = preg_replace($replace, $find, $code_html);
      return  strip_tags($code_html);
    }

    private function mail_boundary($code_html){
      $message = "--".$this->code."\r\n";
      $message .= "Content-Type: text/plain; charset=".$this->charset."\r\n";
      $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
      $message .= $this->txt_mail($code_html)."\r\n";
      $message .= "--".$this->code."\r\n";
      $message .= "Content-Type: text/html; charset=".$this->charset."\r\n";
      $message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
      $message .= $code_html."\r\n";
      $message .= "--".$this->code."--";
      return $message;
    }

    public function send($to, $name, $tema, $text){

      $text = $this->mail_boundary($text);
      $this->status = $this->socet_init();

      if(!$this->status){
        if(is_array($to)){
          for ($i=0; $i<count($to); $i++) {
            $header = $this->mail_header($name[$i], $to[$i], $tema);
            $this->status = $this->mail_from(strlen($header."\r\n".$text));
            $this->status = $this->mail_to($to[$i]);
            $this->status = $this->mail_data($header, $text);
          }
        }else{
          $header = $this->mail_header($name, $to, $tema);
          $this->status = $this->mail_from(strlen($header."\r\n".$text));
          $this->status = $this->mail_to($to);
          $this->status = $this->mail_data($header, $text);
        }
      }
      fputs($this->smtp_conn,"QUIT\r\n");
      fclose($this->smtp_conn);

      return $this->status;
    }
}
?>
