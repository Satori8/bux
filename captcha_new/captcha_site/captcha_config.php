<?php

$captcha_type = 1;
$alphabet = "0123456789+-";
$allowed_symbols = "0123456789+-";
$fonts_png_dir = "fonts_png";
$no_spaces = false;
$fonts_ttf_dir = "fonts_ttf";

$width = 100;
$height = 40;

$fluctuation_amplitude = 2; #кратное 2

$show_credits = false;
$credits = true;

$jpeg_quality = mt_rand(75,100);

$noise_1 = mt_rand(0,1);
$white_noise_density = $noise_1 * 1/12;
$black_noise_density = $noise_1 * 1/60;

$noise_2 = mt_rand(1,2);

$foreground_color = array(mt_rand(0,140), mt_rand(0,140), mt_rand(0,140));
$background_color = array(mt_rand(200,255), mt_rand(200,255), mt_rand(200,255));

?>