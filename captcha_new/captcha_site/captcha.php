<?php
function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}


class GEN_CAPTCHA{
	function __construct($keystring=false){
		require(dirname(__FILE__).'/captcha_config.php');

		$this->keystring = "$keystring";
		$this->captcha_type = $captcha_type;

		$length = strlen($this->keystring);
		$alphabet_length = ($this->keystring!=false && $this->captcha_type!=false) ? strlen($alphabet) : false;

		if($this->keystring==false) {
			$img1 = imagecreatefromgif(dirname(__FILE__)."/error-captcha.gif");
			imagecolorallocate($img1, 255, 220, 220);

			$img2 = imagecreatetruecolor($width, $height+12);
			$foreground = imagecolorallocate($img2, 255, 0, 0);
			$background = imagecolorallocate($img2, 255, 220, 220);
			imagefilledrectangle($img2, 0, 0, $width-1, $height-1, $background);		
			imagefilledrectangle($img2, 0, $height, $width-1, $height+12, $foreground);
			$credits = "Error load captcha";
			imagestring($img2, 2, ($width/2-imagefontwidth(2)*strlen($credits)/2)+1, $height-1, $credits, $background);

			imagecopy($img2, $img1, 0, 0, -1*($width/2-24), 0, $width, $height);

			header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			header("Cache-Control: no-store, no-cache, must-revalidate");
			header("Cache-Control: post-check=0, pre-check=0", FALSE);
			header("Pragma: no-cache");
			if(function_exists("imagejpeg")){
				header("Content-Type: image/jpeg");
				imagejpeg($img2, null, $jpeg_quality);
			}else if(function_exists("imagegif")){
				header("Content-Type: image/gif");
				imagegif($img2);
			}else if(function_exists("imagepng")){
				header("Content-Type: image/x-png");
				imagepng($img2);
			}
			//exit();
		}else

		$fonts_png = array();
		$fonts_ttf = array();
		$fonts_png_dir_absolute = dirname(__FILE__).'/'.$fonts_png_dir;
		$fonts_ttf_dir_absolute = dirname(__FILE__).'/'.$fonts_ttf_dir;

		if ($handle_png = opendir($fonts_png_dir_absolute)) {
			while (false !== ($file = readdir($handle_png))) {
				if (preg_match('/\.png$/i', $file)) $fonts_png[]=$fonts_png_dir_absolute.'/'.$file;
			}
			closedir($handle_png);
		}	
		if ($handle_ttf = opendir($fonts_ttf_dir_absolute)) {
			while (false !== ($file = readdir($handle_ttf))) {
				if (preg_match('/\.ttf$/i', $file)) $fonts_ttf[]=$fonts_ttf_dir_absolute.'/'.$file;
			}
			closedir($handle_ttf);
		}	
	
		do{
			$font_file = $fonts_png[mt_rand(0, count($fonts_png)-1)];
			$font = imagecreatefrompng($font_file);
			imagealphablending($font, true);
			$fontfile_width = imagesx($font);
			$fontfile_height = imagesy($font)-1;
			$font_metrics = array();
			$symbol = 0;
			$reading_symbol = false;

			### loading font ###
			for($i=0; $i<$fontfile_width && $symbol<$alphabet_length; $i++){
				
                $transparent = (imagecolorat($font, $i, 0) >> 24) == 127;

				if(!$reading_symbol && !$transparent){
					$font_metrics[$alphabet{$symbol}] = array("start"=>$i);
					$reading_symbol = true;
					continue;
				}

				if($reading_symbol && $transparent){
					$font_metrics[$alphabet{$symbol}]["end"] = $i;
					$reading_symbol = false;
					$symbol++;
					continue;
				}
			}
			### end loading font ###

			$img = imagecreatetruecolor($width, $height);
			imagealphablending($img, true);
			$white = imagecolorallocate($img, 255, 255, 255);
			$black = imagecolorallocate($img, 0, 0, 0);
			imagefilledrectangle($img, 0, 0, $width-1, $height-1, $white);

			### draw text ###
			$x = 1;
			$odd = mt_rand(0,1);
			if($odd==0) $odd = -1;

			for($i=0;$i<$length;$i++){
				$m = $font_metrics[$this->keystring{$i}];
				//$y = mt_rand(-$fluctuation_amplitude, $fluctuation_amplitude) + ($height - $fontfile_height)/2 + 2;
				$y = (($i%2)*$fluctuation_amplitude - $fluctuation_amplitude/2)*$odd + mt_rand(-round($fluctuation_amplitude/3), round($fluctuation_amplitude/3)) + ($height-$fontfile_height)/2;

				if($no_spaces){
					$shift = 0;
					if($i>0){
						$shift = 10000;
						for($sy=7; $sy<$fontfile_height-20; $sy+=1){
							for($sx=$m['start']-1; $sx<$m['end']; $sx+=1){
					        		$rgb = imagecolorat($font, $sx, $sy);
					        		$opacity = $rgb>>24;
								if($opacity<127){
									$left = $sx-$m['start']+$x;
									$py = $sy+$y;
									if($py>$height) break;
									for($px=min($left,$width-1); $px>$left-12 && $px>=0; $px-=1){
						        		$color = imagecolorat($img, $px, $py) & 0xff;
										if($color+$opacity<190){
											if($shift>$left-$px){
												$shift = $left-$px;
											}
											break;
										}
									}
									break;
								}
							}
						}
						if($shift==10000){
							$shift = mt_rand(4,6);
						}
					}
				}else{
					$shift=1;
				}
				imagecopy($img, $font, $x-$shift, $y, $m['start'], 1, $m['end']-$m['start'], $fontfile_height);
				
                $x+=$m['end']-$m['start']-$shift;
			}
			### end draw text ###
		}while($x>=$width); // while not fit in canvas

		$center = $x/2;

		function ImageSmoothAlphaLine($image, $x1, $y1, $x2, $y2, $r, $g, $b, $alpha=0) {
			$icr = $r; $icg = $g; $icb = $b;
			$dcol = imagecolorallocatealpha($image, $icr, $icg, $icb, $alpha);
			if ($y1 == $y2 || $x1 == $x2) {
				imageline($image, $x1, $y2, $x1, $y2, $dcol);
			} else {
				$m = ($y2 - $y1) / ($x2 - $x1); $b = $y1 - $m * $x1;
				if (abs ($m) <2) {
					$x = min($x1, $x2); $endx = max($x1, $x2) + 1;
					while ($x < $endx) {
						$y = $m * $x + $b; $ya = ($y == floor($y) ? 1: $y - floor($y)); $yb = ceil($y) - $y;
						$trgb = ImageColorAt($image, $x, floor($y)); $tcr = ($trgb >> 16) & 0xFF; $tcg = ($trgb >> 8) & 0xFF; $tcb = $trgb & 0xFF;
						imagesetpixel($image, $x, floor($y), imagecolorallocatealpha($image, ($tcr * $ya + $icr * $yb), ($tcg * $ya + $icg * $yb), ($tcb * $ya + $icb * $yb), $alpha));

						$trgb = ImageColorAt($image, $x, ceil($y));  $tcr = ($trgb >> 16) & 0xFF; $tcg = ($trgb >> 8) & 0xFF; $tcb = $trgb & 0xFF;
						imagesetpixel($image, $x, ceil($y), imagecolorallocatealpha($image, ($tcr * $yb + $icr * $ya), ($tcg * $yb + $icg * $ya), ($tcb * $yb + $icb * $ya), $alpha));
						$x++;
					}
				} else {
					$y = min($y1, $y2); $endy = max($y1, $y2) + 1;
					while ($y < $endy) {
						$x = ($y - $b) / $m; $xa = ($x == floor($x) ? 1: $x - floor($x)); $xb = ceil($x) - $x;
						$trgb = ImageColorAt($image, floor($x), $y); $tcr = ($trgb >> 16) & 0xFF; $tcg = ($trgb >> 8) & 0xFF; $tcb = $trgb & 0xFF;
						imagesetpixel($image, floor($x), $y, imagecolorallocatealpha($image, ($tcr * $xa + $icr * $xb), ($tcg * $xa + $icg * $xb), ($tcb * $xa + $icb * $xb), $alpha));

						$trgb = ImageColorAt($image, ceil($x), $y);  $tcr = ($trgb >> 16) & 0xFF; $tcg = ($trgb >> 8) & 0xFF; $tcb = $trgb & 0xFF;
						imagesetpixel ($image, ceil($x), $y, imagecolorallocatealpha($image, ($tcr * $xb + $icr * $xa), ($tcg * $xb + $icg * $xa), ($tcb * $xb + $icb * $xa), $alpha));
						$y++;
					}
				}
			}
		}


		### credits ###
		$img2 = imagecreatetruecolor($width, $height+($show_credits?12:0));
		$foreground = imagecolorallocate($img2, $foreground_color[0], $foreground_color[1], $foreground_color[2]);
		$background = imagecolorallocate($img2, $background_color[0], $background_color[1], $background_color[2]);
		imagefilledrectangle($img2, 0, 0, $width-1, $height-1, $background);		
		imagefilledrectangle($img2, 0, $height, $width-1, $height+12, $foreground);
		$credits = isset($credits) && $credits!=false ? $_SERVER["HTTP_HOST"] : $credits;
		imagestring($img2, 2, $width/2-imagefontwidth(2)*strlen($credits)/2, $height-1, $credits, $background);
		### end credits ###

		### noise1 ###
		$white = imagecolorallocate($font, 255, 255, 255);
		$black = imagecolorallocate($font, 0, 0, 0);
		for($i=0; $i<(($height-30)*$x)*$white_noise_density; $i++){
			imagesetpixel($img, mt_rand(0, $x-1), mt_rand(10, $height-15), $white);
		}
		for($i=0;$i<(($height-30)*$x)*$black_noise_density;$i++){
			imagesetpixel($img, mt_rand(0, $x-1), mt_rand(10, $height-15), $black);
		}
		### end noise1 ###

		### noise2 ###
		for($i=0; ($i<30 && $noise_2==1 && isset($allowed_symbols)); $i++) {
			//$color = imagecolorallocatealpha($img2, $foreground_color[0], $foreground_color[1], $foreground_color[2], 100);
			$color = imagecolorallocatealpha($img2, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255), 100); 
			$font_ttf = $fonts_ttf[mt_rand(0, count($fonts_ttf)-1)];
			$letter = $allowed_symbols{mt_rand(0, strlen($allowed_symbols)-1)};
			$letter_size = mt_rand(12, 16);
			imagettftext($img2, $letter_size, mt_rand(0,45), mt_rand($width*0.05, $width-$width*0.05), mt_rand($height*0.2, $height), $color, $font_ttf, $letter);
		}
		### end noise2 ###

		### noise2 ###
		for ($i=0; $i<mt_rand(2,5) && $noise_2==2; $i++) {
			ImageSmoothAlphaLine($img2, mt_rand(5,$width*0.5), mt_rand(5,$height-5), mt_rand($width*0.5,$width-5), mt_rand(5,$height-5), mt_rand(0,255), mt_rand(0,255), mt_rand(0,255), mt_rand(0,100));
		}
		### end noise2 ###

		### periods ###
		$rand1 = mt_rand(750000,1200000)/10000000;
		$rand2 = mt_rand(750000,1200000)/10000000;
		$rand3 = mt_rand(750000,1200000)/10000000;
		$rand4 = mt_rand(750000,1200000)/10000000;
		### phases ###
		$rand5 = mt_rand(0,31415926)/10000000;
		$rand6 = mt_rand(0,31415926)/10000000;
		$rand7 = mt_rand(0,31415926)/10000000;
		$rand8 = mt_rand(0,31415926)/10000000;
		### amplitudes ###
		$rand9 = mt_rand(330,420)/110;
		$rand10 = mt_rand(330,450)/110;

		### wave distortion ###
		for($x=0; $x<$width; $x++){
			for($y=0; $y<$height; $y++){
				$sx = $x+(sin($x*$rand1+$rand5)+sin($y*$rand3+$rand6))*$rand9-$width/2+$center+1;
				$sy = $y+(sin($x*$rand2+$rand7)+sin($y*$rand4+$rand8))*$rand10;

				if($sx<0 || $sy<0 || $sx>=$width-1 || $sy>=$height-1){
					continue;
				}else{
					$color = imagecolorat($img, $sx, $sy) & 0xFF;
					$color_x = imagecolorat($img, $sx+1, $sy) & 0xFF;
					$color_y = imagecolorat($img, $sx, $sy+1) & 0xFF;
					$color_xy = imagecolorat($img, $sx+1, $sy+1) & 0xFF;
				}

				if($color==255 && $color_x==255 && $color_y==255 && $color_xy==255){
					continue;
				}else if($color==0 && $color_x==0 && $color_y==0 && $color_xy==0){
					$newred = $foreground_color[0];
					$newgreen = $foreground_color[1];
					$newblue = $foreground_color[2];
				}else{
					$frsx = $sx-floor($sx);
					$frsy = $sy-floor($sy);
					$frsx1 = 1-$frsx;
					$frsy1 = 1-$frsy;

					$newcolor=(
						$color*$frsx1*$frsy1+
						$color_x*$frsx*$frsy1+
						$color_y*$frsx1*$frsy+
						$color_xy*$frsx*$frsy
					);

					if($newcolor>255) $newcolor=255;
					$newcolor = $newcolor/255;
					$newcolor0 = 1-$newcolor;

					$newred = $newcolor0*$foreground_color[0]+$newcolor*$background_color[0];
					$newgreen = $newcolor0*$foreground_color[1]+$newcolor*$background_color[1];
					$newblue = $newcolor0*$foreground_color[2]+$newcolor*$background_color[2];
				}

				imagesetpixel($img2, $x, $y, imagecolorallocate($img2, $newred, $newgreen, $newblue));
			}
		}
		### end wave distortion ###

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", FALSE);
		header("Pragma: no-cache");
		if(function_exists("imagejpeg")){
			header("Content-Type: image/jpeg");
			imagejpeg($img2, null, $jpeg_quality);
		}else if(function_exists("imagegif")){
			header("Content-Type: image/gif");
			imagegif($img2);
		}else if(function_exists("imagepng")){
			header("Content-Type: image/x-png");
			imagepng($img2);
		}
	}

	function getKeyString(){
		if($this->captcha_type==1) {
			$first_symbols = $this->keystring[0];
			$sign_symbols = $this->keystring[1];
			$last_symbols = $this->keystring[2];
			if($sign_symbols=="+") {
				$this->keystring = ($first_symbols+$last_symbols);
			}elseif($sign_symbols=="-") {
				$this->keystring = ($first_symbols-$last_symbols);
			}
		}
		return $this->keystring;
	}
}

$ks = $_GET['ks'];
$ks = str_replace("p","+", $ks); 
new GEN_CAPTCHA($ks)
?>