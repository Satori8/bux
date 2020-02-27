<?header('Content-Type: text/html; charset=windows-1251');?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
</head>
<body style="background:#37474F;padding-top:40px; color:#FFFFFF; font-size:36px;">
<center>
<?
define('CHARSET', 'cp1251');
define('REPLACE_FLAGS', ENT_COMPAT);

function mhtmlspecialchars($string) { return htmlspecialchars($string, REPLACE_FLAGS, CHARSET); }

if (isset($_GET["v"])) {
$video=trim(mhtmlspecialchars($_GET["v"]));
if ($video!="") {
?>
<style> .youtub{
    
    height:512px;
    background:url(../img/ramka/ramka_serf.gif) no-repeat center;
    margin:0;
    padding:0px;
    
    padding-left:0;
}
    </style>
<center class="youtub" style="display: block;"><iframe id="ytplayer"
        width="845" height="467"
        src="https://www.youtube.com/embed/<?=$video?>?enablejsapi=1&autoplay=1"
        frameborder="0"
        allowfullscreen
        style="padding-top: 23px;padding-left:2px;"
></iframe></center>
<?
} else {
echo "Недействительная ссылка";
}
} else {
echo "Недействительная ссылка";
}

$video=explode("/", $_GET["v"]);
$url="https://www.youtube.com/watch?v=".$video[1];
echo"<script>
//header('Location: '.$url); 
var redirect_to='".$url."';
    if(window==window.top){
        top.location.replace(redirect_to);
    }</script>
	";

?>
</center>
</body>
</html>