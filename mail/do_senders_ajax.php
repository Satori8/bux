<?
ini_set('display_errors',0);
header('Content-Type: text/html; charset=windows-1251');
if (isset($_POST['curuser'])) {
$curuser = trim($_POST['curuser']);
require_once("../config.php");

$result='';

$senders=array();
$senders1=array();

if (strtolower($curuser)=="admin") {$dop_usl=" and `r_flag`='0'";} else {$dop_usl="";}

$prml_q=mysql_query("select nameout, 
(select count(id) from tb_mail_in tm1 where namein='$curuser' and nameout=tbm.nameout and status='0') as countid0
from tb_mail_in tbm where namein='$curuser'".$dop_usl." group by nameout");

while ($prml_f=mysql_fetch_array($prml_q)) {
$sender=$prml_f["nameout"];
$countid='0';
$countid0=$prml_f["countid0"];
if ($countid0=='') {$countid0='0';}

$last_message='0';

if ($countid0=='0') {$minstatus='1';} else {$minstatus='0';}

if ($minstatus==0) { $prml_mt=""; $prml_tm="Новых"; $countid=$countid0; }
else { $prml_mt="_n"; $prml_tm="Всего"; }

$senders1[]=$sender;
$senders[]=array('sender'=>$sender,'prm_tm'=>$prml_tm,'prm_mt'=>$prml_mt,'countid'=>$countid,'last_message'=>$last_message,'minstatus'=>$minstatus,'uid'=>'0','ava'=>'no.png');
}
$prml_q=mysql_query("select namein from tb_mail_in tbm where nameout='$curuser'".$dop_usl." group by namein");

while ($prml_f=mysql_fetch_array($prml_q)) {
$sender=$prml_f["namein"];

if (!in_array($sender,$senders1)) {
$countid='0';

$last_message='0';

$prml_mt="_n"; $prml_tm="Всего";

$senders1[]=$sender;
$senders[]=array('sender'=>$sender,'prm_tm'=>$prml_tm,'prm_mt'=>$prml_mt,'countid'=>$countid,'last_message'=>$last_message,'minstatus'=>'1','uid'=>'0','ava'=>'no.png');
}

}

$orderBy=array('minstatus'=>'asc','sender'=>'asc');

function cmp($a, $b) {
  global $orderBy;
  $result= 0;
  foreach( $orderBy as $key => $value ) {
    if( strtolower($a[$key]) == strtolower($b[$key]) ) continue;
    $result= (strtolower($a[$key]) < strtolower($b[$key]))? -1 : 1;
    if( $value=='desc' ) $result= -$result;
    break;
    }
  return $result;
  }

usort($senders, 'cmp');


$usonl=array();
$prmluo_q=mysql_query("select username FROM `tb_online` WHERE `username`!=''");
while ($prmluo_f=mysql_fetch_array($prmluo_q)) {
$usonl[]=$prmluo_f["username"];
}

$prmlu_q=mysql_query("select id,avatar,username from tb_users");
while ($prmlu_f=mysql_fetch_array($prmlu_q)) {
for ($i=0;$i<count($senders);$i++) {
if ($senders[$i]["sender"]=="Система") {
$senders[$i]["ava"]="system.gif";
$senders[$i]["uid"]='0';
} else {
if (strtolower($senders[$i]["sender"])==strtolower($prmlu_f["username"])) {
$senders[$i]["ava"]=$prmlu_f["avatar"];
$senders[$i]["uid"]=$prmlu_f["id"];
}
}
}
}

for ($i=0;$i<count($senders);$i++) {

$sender=$senders[$i]['sender'];
$prml_tm=$senders[$i]['prm_tm'];
$prml_mt=$senders[$i]['prm_mt'];
$countid=$senders[$i]['countid'];
$uava=$senders[$i]['ava'];
$uid=$senders[$i]['uid'];
$prml_adm="";

if ($sender=="Система") {
$sender_name='&equiv;&nbsp;SYSTEM&nbsp;&equiv;';
$sender='su';
$prml_adm=" prml_adm";
$prmlu_mnr=1;
} else {
if ($uid!='' && $uid>'0') {$prmlu_mnr=1;} else {$prmlu_mnr=0;}
if ($prmlu_mnr>0) {
$sender_name=$sender;
}

}

if (strtolower($sender)=="admin") {
$prml_adm=" prml_adm";
}

if ($prmlu_mnr>0) {


if (in_array($sender,$usonl)) {$onoff='online';} else {$onoff='offline';}

$result.='
	<form id="form_user_'.$uid.'" name="form_user_'.$uid.'" style="display:none;">
	<input type="hidden" name="uid" value="'.$uid.'"/>
	<input type="hidden" name="sname" value="'.$sender.'"/>
	<input type="hidden" name="rname" value="'.$curuser.'"/>
	<input type="hidden" name="onoff" value="'.$onoff.'"/>
	<input type="hidden" name="ava" value="'.$uava.'"/>
	</form>
	<div class="item_users'.$prml_adm.' w210" id="user_'.$uid.'" onclick="CheckCurrent (\'user_'.$uid.'\',\'0\')">
		<div class="svc_avatar" style="margin-right:5px;">
			<img src="https://'.$_SERVER["HTTP_HOST"].'/avatar/'.$uava.'">
			<div class="'.$onoff.'" title="'.$onoff.'"></div>
		</div>
		<div class="svc_login">
		<nobr>'.$sender_name.'</nobr>
		</div>';
if ($countid!='0') {		
$result.='<div class="svc_count'.$prml_mt.'" id="count_'.$uid.'" title="'.$prml_tm.' сообщений">'.$countid.'</div>';
}
$result.='</div>';
}
}

echo $result;
}
?>