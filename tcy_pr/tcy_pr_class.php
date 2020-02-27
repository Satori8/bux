<?php

class TCY_PR {
	private function StrToNum($Str, $Check, $Magic) {
		$Int32Unit = 4294967296;
		$length = strlen($Str);
		for ($i = 0; $i < $length; $i++) {
			$Check *= $Magic;
			if ($Check >= $Int32Unit) {
				$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
				$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
			}
			$Check += ord($Str{$i});
		}
		return $Check;
	}
 
	private function HashURL($String) {
		$Check1 = TCY_PR::StrToNum($String, 0x1505, 0x21);
		$Check2 = TCY_PR::StrToNum($String, 0, 0x1003F);
		$Check1 >>= 2;
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) << 2 ) | ($Check2 & 0xF0F );
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
		return ($T1 | $T2);
	}
 
	private function CheckHash($Hashnum) {
		$CheckByte = 0;
		$Flag = 0;
		$HashStr = sprintf('%u', $Hashnum) ;
		$length = strlen($HashStr);
		for ($i=$length-1; $i>=0; $i--) {
			$Re = $HashStr{$i};
			if (1 === ($Flag % 2)) {
				$Re += $Re;
				$Re = (int)($Re / 10) + ($Re % 10);
			}
			$CheckByte += $Re;
			$Flag ++;
		}
		$CheckByte %= 10;
		if (0 !== $CheckByte) {
			$CheckByte = 10 - $CheckByte;
			if (1 === ($Flag % 2) ) {
				if (1 === ($CheckByte % 2)) {
					$CheckByte += 9;
				}
				$CheckByte >>= 1;
			}
		}
		return '7'.$CheckByte.$HashStr;
	}
 
	public function check_pr($url) {
		$ch = curl_init("http://toolbarqueries.google.com/tbr?features=Rank&sourceid=navclient-ff&client=navclient-auto-ff&ch="."&ch=".$this->CheckHash($this->HashURL($url))."&q=info:$url");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($ch);
		if(curl_errno($ch) != 0) die("curl_errno(".curl_errno($ch)."), curl_error(".curl_error($ch).")");
		curl_close($ch);
		//return substr($result, 9);
		return "0";
	}
 
	public function check_tcy($url) {
		$ch = curl_init("http://bar-navig.yandex.ru/u?ver=2&url=$url&show=1");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		$result = curl_exec($ch);
		if(curl_errno($ch) != 0) die("curl_errno(".curl_errno($ch)."), curl_error(".curl_error($ch).")");
		curl_close($ch);
		$xml = simplexml_load_string($result);
		return intval($xml->tcy->attributes()->value);
	}
}
?>