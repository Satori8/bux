<?
ini_set('display_errors',0);
header('Content-Type: text/html; charset=windows-1251');
if (isset($_POST['sname']) && isset($_POST['rname'])) {
$sname = $_POST['sname'];
$rname = $_POST['rname'];
require_once('../.zsecurity.php');
require_once ("../bbcode/bbcode.lib.php");
require_once("../config.php");

if ($sname=="su") { $sname="Система"; }

$result="";
$sticker="";

if (isset($_POST['touser']) && isset($_POST['message'])) {

define('CHARSET', 'utf-8');
define('REPLACE_FLAGS', ENT_COMPAT);
function mhtmlspecialchars($string) { return htmlspecialchars($string, REPLACE_FLAGS, CHARSET); }

$touser = $_POST['touser'];
$message = $_POST['message'];

$touser = trim($touser);
$touser = mhtmlspecialchars($touser);
$touser = mysql_real_escape_string($touser);

$message = trim($message);
$message = mhtmlspecialchars($message);
$message = mysql_real_escape_string($message);

if ($touser!="" && $message!="" && strtoupper($touser)!=strtoupper($rname)) {
$a_q=mysql_query("select id,lastiplog from tb_users where username='$touser' limit 1");
if (mysql_num_rows($a_q)>0) {
$sname = $touser;
$a_f=mysql_fetch_array($a_q);
mysql_query("insert into tb_mail_in (`nameout`,`namein`,`subject`,`message`,`status`,`date`,`ip`) values('$rname','$sname','Сообщение от ".$rname."','".iconv('utf-8','windows-1251',$message)."','0','".time()."','".$a_f["lastiplog"]."')");

$sql_id = mysql_query("SELECT LAST_INSERT_ID() FROM `tb_mail_in`");
$last_id = mysql_result($sql_id,0,0);

mysql_query("INSERT INTO tb_mail_out (`ident`,`nameout`,`namein`,`subject`,`message`,`status`,`date`,`ip`) values('$last_id','$rname','$sname','Сообщение от ".$rname."','".iconv('utf-8','windows-1251',$message)."','0','".time()."','".$a_f["lastiplog"]."')");

$result.='<script>sav_id="user_'.$a_f["id"].'";</script>';
} else { $result.='<script>alert("Нет такого пользователя");</script>'; }
}
}
/*
$sticker.='<div class="sticker_maindiv">';
$sticker_count=0;

$srn_q=mysql_query("select * from tb_mail_in where namein='$rname' && nst='0' && status='0' order by `date` desc limit 5");
while ($srn_f=mysql_fetch_array($srn_q)) {

if ($srn_f["nameout"]=="Система") {
$usava="system.gif";
$usender='Системное сообщение';
$ulnk='su';
$utid='1';
} else {
$srnu_q=mysql_query("select id,avatar from tb_users where username='".$srn_f["nameout"]."'");
$srnu_f=mysql_fetch_array($srnu_q);
$usava=$srnu_f["avatar"];
$usender='Сообщение от '.$srn_f["nameout"];
$ulnk=$srn_f["nameout"];
$utid=$srnu_f["id"];
}

$sticker_count++;
$sticker.='<div class="sticker_infodiv" id="infodiv'.$sticker_count.'">
<div class="sticker_close" onclick="HideBlock(\'infodiv'.$sticker_count.'\','.$srn_f[id].');" title="Закрыть">x</div>
<a onclick="CheckCurrent(\'user_'.$utid.'\',\'1\');HideBlock(\'infodiv'.$sticker_count.'\','.$srn_f[id].');" title="Перейти к диалогу">
<div class="sticker_tytle">'.$usender.'</div>
<table><tr><td class="sticker_ava"><img src="http://'.$_SERVER["HTTP_HOST"].'/avatar/'.$usava.'"></td><td>
'.$srn_f["message"].'</td></tr></table>
<div class="over_block"></div>
</a>
</div>
';
}
$sticker.='</div>';
*/
			function readBR($mensaje){
				$mensaje = str_replace('"',"",$mensaje);
				$mensaje = str_replace("<","[",$mensaje);
				$mensaje = str_replace(">","]",$mensaje);
				$mensaje = str_replace("&gt;","",$mensaje);
				$mensaje = str_replace("&lt;","",$mensaje);
				$mensaje = str_replace("&#063;","?",$mensaje);
				$mensaje = str_replace("&#063;","?",$mensaje);
				$mensaje = str_replace("<br>","\n",$mensaje);
				$mensaje = str_replace("<a href=","[url=",$mensaje);
				$mensaje = str_replace("</a>","[/url]",$mensaje);
				//$mensaje = str_replace("----- Original Message -----","<br /><br />----- <i>Original Message</i> -----<br />",$mensaje);
				return $mensaje;
			}

mysql_query("update tb_mail_in set status='1', nst='1' where (nameout='$sname' && namein='$rname')");
mysql_query("UPDATE tb_mail_out SET status='1', nst='1' where (nameout='$sname' && namein='$rname')");

$sr_q=mysql_query("select * from tb_mail_in where (nameout='$sname' && namein='$rname') || (nameout='$rname' && namein='$sname') order by `date` asc limit 100");
while ($sr_f=mysql_fetch_array($sr_q)) {
//if ($sr_f["status"]=='1') { $st_ln=' svc_hide50'; } else { $st_ln=''; }
$st_ln='';
$od_ln='';
$od_ln1='';

$message = readBR($sr_f["message"]);
$message = new bbcode($message);
$message = $message->get_html();


if (trim($sr_f["subject"])!="" && $sr_f["subject"]!="Сообщение от ".$sname && $sr_f["subject"]!="Сообщение от ".$rname && $sr_f["subject"]!="Системное сообщение") {$subject="<div class=\"mess_title\">".$sr_f["subject"]."</div>";} else {$subject="";}

if ($sr_f["nameout"]==$sname) { $fw_ln=''; if ($sr_f["status"]=='0') {$od_ln=' id="com_'.$sr_f["id"].'" style="cursor:pointer!important;" title="Отметить как прочитанное" onclick="doOld(\''.$sr_f["id"].'\')"'; $od_ln1=' id="new_'.$sr_f["id"].'"';} else {$od_ln=' id="com_'.$sr_f["id"].'"';} } else { $od_ln=' id="com_'.$sr_f["id"].'"'; $fw_ln='_r'; }
if ($sr_f["status"]=='1') { $st_ln2=''; } else { $st_ln2='<new'.$od_ln1.' title="Непрочитанное"/>'; }
$result.='<div class="svc_comment'.$st_ln.'"'.$od_ln.'><div class="svc_info'.$fw_ln.'"><div class="svc_nick"><span class="svc_date">'.date("H:i d.m.Y",$sr_f["date"]).$st_ln2.'<del id="del_'.$sr_f["id"].'" title="Удалить" onclick="if (confirm(\'Удалить сообщение?\')) {doRem(\''.$sr_f["id"].'\');}"/></span>'.$sr_f["nameout"].'</div><div class="svc_msg">'.$subject.$message.'</div></div></div>';
}
$result=$result.$sticker;
echo $result;
}

?>