<?php
session_start();
include("../config.php");
?>
<style>
.title-popup{background-color:rgba(0, 154, 86, 0.9);color:#FFF;line-height: 40px;padding:0;padding-left:10px;font-size:14px;}
.closed-popup{position: absolute;top:0;right:0;font-size:14px;line-height: 42px;text-align:center;display:inline-block;width:100px;cursor:pointer;color:#FFF;}
.closed-popup:hover{opacity:0.9}
</style>
<style>
#newsmodal2 {position:absolute;top:50%;left:50%;margin-top:-250px;margin-left:-353px;width:450px;}
#newsmodal2-overlay {background-color: #000000;cursor: auto;}
#newsmodal2-container {
	background-color: #FFFFFF;
	border: 2px solid #0087a7;
	color: #545454;
	height: 500px;
	padding: 1px;
	width: 700px;
	top: 60px; 
	position: fixed; 
	display: block;
	opacity: 1;
	}
</style>

<?
if (isset($_POST['is']))
{
if ($_POST['is']){
$is=$_POST['is'];			
?>

<div id="newsmodal2-container" class="newsmodal2-container">
<span class="closed-popup" onclick="closed_popup();">Закрыть</span>
<div class="title-popup">Раздел в разработке</div>
<div style="overflow: auto; height: 450px; padding: 5px;">



<?
}
}
mysql_close();
?>