<?php

$simbol_color='random'; //red, blue, green ��� random - ���������

$scolor=array(
'black'=>array(0,0,0),
'red'=>array(255,100,100),
'green'=>array(100,255,100),
'blue'=>array(100,100,255)
);
if ($simbol_color=='random'){
 $r=mt_rand(200,250);
 switch(mt_rand(1,3)) {
  case 1:$scolor['random']=array($r,0,0); break;
  case 3:$scolor['random']=array(0,0,$r); break;
  case 2:$scolor['random']=array(0,$r,0); break;
 }
}

$amplitude_min=10; // ����������� ��������� �����
$amplitude_max=20; // ������������ ��������� �����

$font_size_min=20;
$font_size_max=23;

$margin_left=mt_rand(3,10);// ������ �����
$margin_top=mt_rand(30,35); // ������ ������

$shum_count=20; // ���������� ����
$jpeg_quality = 90; // �������� ��������
$length = mt_rand(1,3);
//$font_ttf = mt_rand(1,3);
$font_ttf = 2;
?>