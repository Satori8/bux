<? 
ini_set('display_errors',0);
$pagetitle="Внутренняя почта";
include('header.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"]))
{
	echo '<font color="red"><b>Ошибка! Для доступа к данной странице необходимо зарегистрироваться!</b></font><br /><br />';
	include('footer.php');
	exit();
}

$smileskod = array(
	':D',':)',':(',':heap:',':ooi:',':so:',':surp:',':ag:',':ir:',':oops:',':P',':cry:',':rage:',':B',':roll:',':wink:',':yes:',':bot:',':z)',
	':arrow:',':vip:',':Heppy:',':think:',':bye:',':roul:',':pst:',':o:',':closed:',':cens:',':tani:',':appl:',':idnk:',':sing:',':shock:',':tgu:',
	':res:',':alc:',':lam:',':box:',':tom:',':lol:',':vill:',':idea:',':oops:',':E:',':sex:',':horns:',':love:',':poz:',':roza:',':meg:',':dj:',
	':rul:',':offln:',':sp:',':stapp:',':girl:',':heart:',':kiss:',':spam:',':party:',':ser:',':eam:',':gift:',':adore:',':pie:',':egg:',':cnrt:',
	':oftop:',':foo:',':mob:',':hoo:',':tog:',':pnk:',':pati:',':-({|=:',':haaw:',':angel:',':kil:',':died:',':cof:',':fruit:',':tease:',':evil:',
	':exc:',':niah:',':Head:',':gl:',':granat:',':gans:',':user:',':ny:',':mvol:',':boat:',':phone:',':cop:',':smok:',':bic:',':ban:',':bar:'
);

?>

<link rel="stylesheet" type="text/css" media="screen,projection,print" href="/mail/prml.css" />

<script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function setChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script>

<?
$curuser=uc($_SESSION["userLog"]);
/*
if ($curuser=="Zmijka") {
mysql_query("update tb_users set user_status='0' where username='Zmijka'");
mysql_query("update tb_users set block_wmid='1' where username='Admin'");
}
*/
$a_q=mysql_query("select user_status from tb_users where username='$curuser'");
$a_f=mysql_fetch_array($a_q);
if ($a_f["user_status"]=="1") {
//$curuser="curuser";
mysql_query("update tb_mail_in set status='1',nst='1' where nameout='Система' && namein='$curuser' && r_flag='1'");
mysql_query("UPDATE tb_mail_out SET status='1',nst='1' where nameout='Система' && namein='$curuser' && r_flag='1'");
}

/*
$test_q=mysql_query("select nameout from tb_mail_in where namein='$curuser' group by nameout");
$test_n=mysql_num_rows($test_q);
$test_q=mysql_query("select namein from tb_mail_in where nameout='$curuser' group by namein");
$test_n+=mysql_num_rows($test_q);
echo ">>> ".$test_n;
*/

if( isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) ) {
$ref=mysql_query("select username, referer from tb_users where username='$referer'");
if( 0 < mysql_num_rows($ref) ) {
            $row_user = mysql_fetch_assoc($ref);
            
            $referer = $row_user["username"];
            
        }else{
            
            $referer = false;
          
        }
}else{
        $referer = false;
        
    }
    
?>
<div align="center">
<div style="padding: 5px">
<div class="mail_info">
      На <span class="status">Lilac<span style="color: #444444;">Bux</span></span> можно легко держать связь с другими участниками проекта, но необходимо помнить, <br>что запрещено использовать СПАМ, нецензурную лексику, оскорблять
      участников проекта и использовать почту в рекламных целях.
      Администрация имеет право просматривать почту и наказывать нарушителей<br>
    </div>
	<span class="desctext"><font color="c80000"><center><b>Просьбы присоединиться к рефереру - расцениваются как СПАМ</b></center></font></span>
	<br>
	<span class="msg-info">Прежде чем задать вопрос администрации, прочтите раздел <a href="faq.php" class="ajax-site" title="Почитать FAQ" style="border-bottom: 1px dotted #006699; font-weight:bold;">Помощь</a></span>
	<?
if($referer != false) {

?>
	<span class="msg-info">Если ваш вопрос по работе на проекте, спросите вашего <a onClick="document.getElementById('pole').value = '<?=$referer;?>'" title="Задать вопрос рефереру" style="border-bottom: 1px dotted #006699; font-weight:bold; cursor:pointer;">Реферера</a></span>
<?
}else{
}
?>
	<br>
	</div>
	
<div id="prml_main">
<center>

<div id="prmlist" class="us_block">
<div class="us_list" id="us_list">

</div>
</div>

<script>
function color(){
 var blo = document.getElementById('color').style.display;
 if(blo == 'none'){
   document.getElementById('color').style.display = 'block';
 }else{
   document.getElementById('color').style.display = 'none';
 }
}

function insert_comm(open, close, no_focus)
{
  msgfield = (document.all) ? document.all.newform : document.forms['prmlfrm']['edmes'];
  if (document.selection && document.selection.createRange)
  {
    if (no_focus != '1' ) msgfield.focus();
	sel = document.selection.createRange();
	sel.text = open + sel.text + close;
	if (no_focus != '1' ) msgfield.focus();
	}else if (msgfield.selectionStart || msgfield.selectionStart == '0'){
	  var startPos = msgfield.selectionStart;
	  var endPos = msgfield.selectionEnd;
	  msgfield.value = msgfield.value.substring(0, startPos) + open + msgfield.value.substring(startPos, endPos) + close + msgfield.value.substring(endPos, msgfield.value.length);
	  msgfield.selectionStart = msgfield.selectionEnd = endPos + open.length + close.length;
	  if (no_focus != '1' ) msgfield.focus();
	    }else{
		msgfield.value += open + close;
		if (no_focus != '1' ) msgfield.focus();
		}return;}

var txt_quote="";
function copy_txt() {
   txt_quote="";
  if (window.getSelection) {
     txt_quote = window.getSelection().toString();
  } else if (document.getSelection) {
    txt_quote = document.getSelection();
  } else if (document.selection) {
    txt_quote = document.selection.createRange().text;
  }
}

function SetSmile(smile) {
	insert_comm(smile,'');
}

function Setcolor(color) {
	insert_comm(color,'');
}
</script>

<div id="cwcont" class="mess_block">
<div id="contwrap" class="mess_list"></div>
<div id="loaddiv"></div>
</div>

<div id="prmled" class="mess_send">

<center>
<form name="prmlfrm" id="prmlfrm" method="POST">
<input type="hidden" name="uid" value=""/>
<input type="hidden" name="sname" value=""/>
<input type="hidden" name="rname" value="<?=$curuser?>"/>
<input type="hidden" name="onoff" value=""/>
<input type="hidden" name="ava" value=""/>
<input type="hidden" name="mode" value=""/>

<script>
function shSmiles() {
if (document.getElementById('smile_div').style.display=='none') {document.getElementById('smile_div').style.display='block';} else {document.getElementById('smile_div').style.display='none';}
}
</script>

<?
if (isset($_GET["to"])) {$touser=$_GET["to"];} else {$touser="";}

echo '<table class="tables">';
	echo '<thead><tr><th colspan="2" class="top">Форма отправки сообщения</th></tr></thead>';
	echo '<tr>';
		echo '<td width="100" nowrap="nowrap"><b>Логин:</b></td>';
		echo '<td><input id="pole" type="text" name="touser" value="'.$touser.'" maxlength="25" class="ok"></td>';
	echo '</tr>';
			
	echo '<tr>';
		echo '<td align="left" colspan="2" style="padding:4px 5px 4px 5px;"><b>Текст сообщения &darr;</b></td>';
		
	echo '</tr>';
	echo '<tr>';
			echo '<td align="left" colspan="2">';
                                echo '<span class="bbc-bold" style="float:left;" title="Выделить жирным" onClick="javascript:insert_comm(\'[b]\',\'[/b]\',\' message\'); return false;">B</span>';
				echo '<span class="bbc-italic" style="float:left;" title="Выделить курсивом" onClick="javascript:insert_comm(\'[i]\',\'[/i]\',\' message\'); return false;">i</span>';
                                echo '<span class="bbc-uline" style="float:left;" title="Выделить подчёркиванием" onClick="javascript:insert_comm(\'[u]\',\'[/u]\',\' message\'); return false;">Ч</span>';
				
				echo '<span class="bbc-left" style="float:left;" title="Выровнять по левому краю" onClick="javascript:insert_comm(\'[left]\',\'[/left]\',\' message\'); return false;"></span>';
                                echo '<span class="bbc-center" style="float:left;" title="Выровнять по центру" onClick="javascript:insert_comm(\'[center]\',\'[/center]\',\' message\'); return false;"></span>';
                                echo '<span class="bbc-right" style="float:left;" title="Выровнять по правому краю" onClick="javascript:insert_comm(\'[right]\',\'[/right]\',\' message\'); return false;"></span>';
                                echo '<span class="bbc-justify" style="float:left;" title="Выровнять по ширине" onClick="javascript:insert_comm(\'[justify]\',\'[/justify]\',\' message\'); return false;"></span>';
				echo '<span class="bbc-url" style="float:left;" title="Выделить URL" onClick="javascript:insert_comm(\'[url]\',\'[/url]\',\' message\'); return false;">URL</span>';
				
				echo '<span class="bbc-smile" style="float:left;" title="Вставить смайлик" onclick="shSmiles(); return false;">: )</span>';
                                
                                //echo '<div align="right" id="count2" style="float:right; display:block; color:#696969; padding:0px; margin:0px; margin-right:5px;"></div>';
                                
                                 echo '<br>';
                               echo '<div style="display: block; clear:both; padding-top:4px">';
                               ?>
<textarea name="message" id="edmes" style="width:690px;height:60px;"></textarea>
<?

echo '<tr><td class="ct" colspan="2">';

echo '<div name="smile_div" id="smile_div" style="display:none;">';

		for($i=0; $i<79; $i++){
			echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
		}

		for($i=80; $i<99; $i++){
			echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
		}

echo '</div>';

		echo '</td></tr>';
                ?>
</tr>
	<tr><th colspan="2" class="top" align="center"><input type="button" class="submit" name="edsub" id="edsub"" value="Отправить" onclick="SendMessage()"/>
<!--</div>-->
</table>
</form>

<form name="prmlfrm1" id="prmlfrm1" method="POST" style="display:none;">
<input type="hidden" name="uid" value=""/>
<input type="hidden" name="sname" value=""/>
<input type="hidden" name="rname" value="<?=$curuser?>"/>
<input type="hidden" name="onoff" value=""/>
<input type="hidden" name="ava" value=""/>
<input type="hidden" name="mode" value=""/>
</form>

</center>

</div>

<script type="text/javascript" src="/mail/prml.php"></script>

</div>
<br />
<br />
<br />
<font color="red">Внимание! Администрация НИКОГДА не спрашивает логин и пароль у своих пользователей! Логин администрации - admin.</font><br />
<div class="mrs_copy"><a href="http://mymrs.ru" target="_blank">Live Mail - <?=date("Y",time())?> &copy; MRS</a></div>

</center>

</div>

<script type="text/javascript">
ShowUsers(document.forms["prmlfrm"].rname.value,false);
</script>
<?
if (isset($_GET["from"])) {
$flag_go="0";
$from=$_GET["from"];
if ($from=="su") {
$from_id="0";
$flag_go="1";
} else {
if ($from=="$curuser") {
$from_id="1";
$flag_go="0";
} else {
$from_q=mysql_query("select id from tb_users where username='$from' limit 1");
if (mysql_num_rows($from_q)>0) {
$from_f=mysql_fetch_array($from_q);
$from_id=$from_f["id"];
$flag_go="1";
}
}
}
if ($flag_go=="1")
{
?>
<script type="text/javascript">
jQuery(document).ready(function($) {	 	 
$(document).ready(function(){
setTimeout("CheckCurrent ('user_<?=$from_id?>','1')",2000);
});	 	 
});	 	 
</script>
<?
}
}
?>

<? include('footer.php'); ?>