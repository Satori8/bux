<?php

require_once("captcha-config.php");

class DMTcaptcha{

 function DMTcaptcha($keystring){
 require(dirname(__FILE__).'/captcha-config.php');
			while(true){
				if(!preg_match('/cb|rn|rm|mm|co|do|db|qp|qb|dp|ww|vw|wv/', $keystring)) break;
			}
			$im=imagecreatefrompng(dirname(__FILE__)."/fonts/back1.png");
			$width = imagesx($im);
			$height = imagesy($im);
            $font_color = imagecolorallocate($im, $scolor[$simbol_color][0], $scolor[$simbol_color][1],$scolor[$simbol_color][2]);
   			$angle=0;
			$px=$margin_left;
			mgradient_region($im,array ( mt_rand(0,200), mt_rand(0,200), mt_rand(0,200),  mt_rand(0,200), mt_rand(0,200), mt_rand(0,200) ));
			imagettftext($im, mt_rand($font_size_min,$font_size_max),$angle, $px, $margin_top, $font_color,dirname(__FILE__)."/fonts/rotos_$font_ttf.ttf",$keystring);
		$rand=mt_rand(0,1);
		if ($rand) $rand=-1; else $rand=1;
		wave_region($im,0,0,$width,$height,$rand*mt_rand($amplitude_min,$amplitude_max),mt_rand(40,50));
		header('Expires: Sat, 29 May 2008 00:00:00 GMT'); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header('Cache-Control: no-store, no-cache, must-revalidate'); 
		header('Cache-Control: post-check=0, pre-check=0', FALSE); 
		header('Pragma: no-cache');  
		if(function_exists("imagepng")){
			header("Content-Type: image/x-png");
			imagepng($im);
		}else if(function_exists("imagegif")){
			header("Content-Type: image/gif");
			imagegif($im);
		}elseif(function_exists("imagejpeg")){
			header("Content-Type: image/jpeg");
			imagejpeg($im, null, $jpeg_quality);
		}
 }
 	function getKeyString(){
		return $keystring;
	}
}


function mgradient_region($im,$array_grad){
     list ( $r1, $g1, $b1, $r2, $g2, $b2 ) = $array_grad;	
	 $h=imagesy($im)+130;
	 $w=imagesx($im);	 
	 
	 $vector[0]=mt_rand(2,10)/10;
	 $vector[1]=mt_rand(2,10)/10;

     $x1 = 0;
     $y1 = -mt_rand(10,200)*$vector[1];
	 $x2 = $w;
     $y2 = -mt_rand(10,200)*$vector[1];
	 
     $inc=-130;
	 while ($y1<$h || $y2<$h){
    	$r = ( $r2 - $r1 != 0 ) ? $r1 + ( $r2 - $r1 ) * ( $inc / $h ) : $r1;
    	$g = ( $g2 - $g1 != 0 ) ? $g1 + ( $g2 - $g1 ) * ( $inc / $h ) : $g1;
    	$b = ( $b2 - $b1 != 0 ) ? $b1 + ( $b2 - $b1 ) * ( $inc / $h ) : $b1;
    	//$color = imagecolorallocate( $im, $r, $g, $b );
		$color = imagecolorresolve( $im, $r, $g, $b );
    	imageline($im, $x1, $y1, $x2, $y2, $color );
		imageline($im, $x1-2, $y1, $x2, $y2+1, $color );
		$y1+=$vector[0];
		$y2+=$vector[1];
		$inc++;
    }
}

function wave_region($img, $x, $y, $width, $height,$amplitude = 4.5,$period = 30){
        $mult = 2;
        $img2 = imagecreatetruecolor($width * $mult, $height * $mult);
        imagecopyresampled ($img2,$img,0,0,$x,$y,$width * $mult,$height * $mult,$width, $height);
        for ($i = 0;$i < ($width * $mult);$i += 2)
           imagecopy($img2,$img2,$x + $i - 2,$y + sin($i / $period) * $amplitude,$x + $i,$y, 2,($height * $mult));
        imagecopyresampled($img,$img2,$x,$y,0,0,$width, $height,$width * $mult,$height * $mult);
        imagedestroy($img2);
 }

?>