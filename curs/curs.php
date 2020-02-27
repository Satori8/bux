<?php
$curs=new curs($_SERVER['DOCUMENT_ROOT'].'/curs/curs.txt'); //Задаем файл для хранения данных
$curs->cursNew["date"];	//Актуальая дата курса
$curs->cursNew["USD"];	//Курс евро текущий
$curs->cursNew["EUR"];	//Курс доллара текущий
$curs->cursNew["UAH"];	//Курс гривны текущий
$curs->cursOld["USD"];	//Курс евро вчерашний
$curs->cursOld["EUR"];	//Курс доллара вчерашний
$curs->cursOld["UAH"];	//Курс гривны вчерашний

class curs
{
	const REFRESH_INTERVAL=3600;
	private $dataFile;
	private $source = "http://www.cbr.ru/scripts/XML_daily.asp";
	private $refreshTime;
	public $cursNew;
	public $cursOld;

	private $error;

	public function __construct($dataFile)
	{
		$this->dataFile=$dataFile;

		if(!$f=fopen($this->dataFile, "r")) {
			$this->refresh();
			$this->error=true;
			return false;
		}
		$data=fread($f, filesize($this->dataFile));
		fclose($f);
		$data=unserialize($data);
		$this->refreshTime=$data['refresh_time'];
		$this->cursNew=$data['curs']['new'];
		$this->cursOld=$data['curs']['old'];
		$this->refresh();
	}

	public function save()
	{
		$data=array(
			"refresh_time"=>$this->refreshTime,
			"curs" => array(
				"new"=>$this->cursNew,
				"old"=>$this->cursOld
			)
		);


		if(!$f=fopen($this->dataFile, "w+")) return false;
		fwrite($f, serialize($data));
		fclose($f);
	}

	public function refresh()
	{
		if((time()-self::REFRESH_INTERVAL)<$this->refreshTime) return true;

		if(isset($this->cursNew['date']) && $this->cursNew['date']>=$this->get_timestamp(date("d.m.y"))) return true;

		$new_data=$this->readFromCbr();
		$this->refreshTime=time();
		if(is_array($new_data)){
			if($new_data['date']>$this->cursNew['date']){
				$this->cursOld=$this->cursNew;
				$this->cursNew=$new_data;
				$this->save();
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	private function readFromCbr() {
		if(!$xml=simplexml_load_file($this->source)) return false;
		$curs['date']=self::get_timestamp($xml->attributes()->Date);
		foreach($xml->Valute as $m){
			if($m->CharCode=="USD" || $m->CharCode=="EUR" || $m->CharCode=="UAH"){
				$curs[(string)$m->CharCode]=(float)str_replace(",", ".", (string)$m->Value);
				$curs[(string)$m->CharCode]=number_format($curs[(string)$m->CharCode],6,".","");
			}
		}
		return $curs;
	}

	public static function get_timestamp($date)
	{
    	list($d, $m, $y) = explode('.', $date);
    	return mktime(0, 0, 0, $m, $d, $y);
	}
}


$CURS_EUR = $curs->cursNew["EUR"]; //курс евро
$CURS_USD = $curs->cursNew["USD"]; //курс доллара
$CURS_UAH = $curs->cursNew["UAH"]/10; //курс увро

//echo "EURO => $CURS_EUR<br>";
//echo "USD => $CURS_USD<br>";
//echo "UAH => $CURS_UAH<br>";
?>