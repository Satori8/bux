<link rel="stylesheet" type="text/css" media="screen,projection,print" href="../css/mail.css" />
<style type="text/css">
h3{
    text-align: left;
    font: 14px Tahoma, Arial, sans-serif;
    color: #00649E;
    margin-top: 10px;
    margin-bottom: 5px;
    border-bottom: 1px dotted #00649E;
}

.mail-dell{
	background:url(../img/close.png) no-repeat scroll left top rgba(0, 0, 0, 0);
	border: medium none;
    cursor: pointer;
    display: block;
    height: 24px;
    width: 24px;
	margin-top: -4px;
}
</style>
<span id="lot"><h3>Исходящая почта пользователей</h3>
<a href='#top' onclick="javascript:document.getElementById('mail').style.display = '';" style="color:#006600;">Настройка</a>
<?
if($_GET["option"]=="dellmail")
{
   $date=$_POST["d"]; // дни старit которого удаляем письма
   $nowtime=time();   // Текщая дата
   $timemailout=$nowtime-86400*$date;
   
   $res = "DELETE FROM tb_mail_out WHERE status=1 and date<$timemailout";
   $res = mysql_query($res) or die(mysql_error());
   $res = mysql_affected_rows();
	 echo '<br><br><i><b><span style="color:#1fa600;" >&#9758; Писма старше <span style="color:#ff0000;" >'.$date.'</span> дней удалены! Всего сообщений удалено <span style="color:#ff0000;" >'.$res.'</span></span></b></i>';  
}

if(isset($_GET["maildeldialog"]))
{
    $maildell=$_GET["maildeldialog"];
    $res=mysql_query("select nameout,namein,subject from tb_mail_out where id='$maildell'");
	  $res=mysql_fetch_array($res);
	  $nameout=$res["nameout"];
	  $namein=$res["namein"];
	  $subject=$res["subject"];
    $qw="DELETE from tb_mail_out where subject='$subject' and (( nameout='$nameout' and namein='$namein' ) or (nameout='$namein' and namein='$nameout') )";
    $res=mysql_query($qw);
    echo '<br><br><i><b><span style="color:#1fa600;" >&#9758; Переписка между <span style="color:#ff0000;" >'.$nameout.'</span> и <span style="color:#ff0000;" >'.$namein.'</span> по теме <span style="color:#ff0000;" >'.$subject.'</span> удалена!</span></b></i>';
 
}

?>
<div  id='mail' style=' display:none; margin: 5px 0 0 0; padding:5px;' align='left'>
<table class="history" width="40%" ><thead>
  <tr>
    <th width="70%">Настройки</th>
    <th width="30%">Действие</th>
  </tr></thead><tbody>
  <tr>
   <form action="index.php?op=mail_out&option=dellmail" method="post" >
   <td align="left">Очистить письма старше <input class="inputtext" type="text" value="30" size="5" maxlength="5" name="d"> дней</td>
   <td align="left"><input type="submit" value="Очистить почту"> </td>
   </form>
  </tr>
  <tbody></table>
</div>
<h3>Диологи пользователей</h3>

<?
   $tableName="tb_mail_out";
   $qw="";
   $qw="select * from (select * from $tableName order by date  desc) as Actions group by  subject order by date  desc";
   // Блок листалки --------------------------------------------------------------
   $limit = 10; // количество выводимой информации на одной странице
   $var="&op=mail_out";
   $res=mysql_query($qw);
   $total_pages=mysql_num_rows($res);  
   include("../adminka_lilaks_tools/users/page.php");
   // Конец листалки -------------------------------------------------------------
   $res=mysql_query($qw.$qw3); // Реальный запрос
?>

<center><?=$paginate;?>
<table class='history' width='60%' border='0' cellpadding='0' cellspacing='0'><thead>
<tr>
  <th></th>
  <th align='center' nowrap='nowrap'>Дата</th>
  <th align='center' width='60%'>Тема</th>
  <th></th>
  <th align='center' nowrap='nowrap'>Диалог</th>
</tr></thead>
<tbody>

<?
  while($row=mysql_fetch_array($res))
  {
    $mail_id=$row["id"];
	$mail_sender=$row["nameout"];
	$mail_namein=$row["namein"];
	$mail_subject=$row["subject"];
	$mail_status=$row["status"];
	$mail_date=$row["date"];
	$mail_date=date("d.m.Y",$mail_date);

	if($mail_status==1||$mail_sender==$user) $strstatus='mail-empty'; 
	elseif($mail_status==0) 
	$strstatus='mail-new';
	
	$restema=mysql_query("select count(id) as kolvo from tb_mail_out where subject='$mail_subject' and (( nameout='$mail_sender' and namein='$mail_namein' ) or (nameout='$mail_namein' and namein='$mail_sender') ) ");
    $restema=mysql_fetch_array($restema);
    $kolvo=$restema["kolvo"];
	
	echo "
    <tr>
      <td class='value'><span class='$strstatus'></span></td>
      <td class='value'>$mail_date</td>";
     
      ?> <td><a href="index.php?op=mail_out&mail=<?=$mail_id?>&page=<?=$page?>"><?=$mail_subject?></a><div style="float: right; display: block; height: 16px; "><span class="mail-dell"  style="cursor: pointer;" title="Удалить всю переписку" 
      onclick="javascript:
      if (confirm('Вы уверены что хотите удалить диалог?'))
           { 
            top.location = 'index.php?op=mail_out&page=<?=$page?>&maildeldialog=<?=$mail_id?>'; 
           };">
     </span></div></td> <?
      echo "<td class='value'>$kolvo</td><td nowrap='nowrap'>$mail_sender <span class='textgray'> </span> &rarr; <b>$mail_namein</b></td>
   </tr>
	";
	}
?>
</tbody>
</table>
<?=$paginate;?>
</center>

<?
if(isset($_GET["mail"]))
{
    $mail=uc(limpiar($_GET["mail"]));
    $res=mysql_query("select subject,nameout,namein from tb_mail_out where id='$mail'");
	  $res=mysql_fetch_array($res);
	  $subject=$res["subject"];
	  $nameout=$res["nameout"];
	  $namein=$res["namein"];
	  $otkogo='';
    $komu='';
    if($nameout==$user) {$otkogo=$nameout; $komu=$namein; } else {$otkogo=$namein; $komu=$nameout;} // Определям кому отправить и откого
    $qw="select nameout,namein,message,date from tb_mail_out where subject='$subject' and (( nameout='$nameout' and namein='$namein' ) or (nameout='$namein' and namein='$nameout') )  order by date  desc ";
    $res=mysql_query($qw);
    //echo "$mail";
    echo "<h3>Переписка между <span style='color:#1fa600;' >$nameout</span> и <span style='color:#1fa600;' >$namein</span></h3>";
?>
    <center>

    <table class="letter" width="100%" border="0" cellpadding="0" cellspacing="0"><tbody>
    <tr class="top">
      <td class="empty" style="padding-top: 10px;"><?=$subject?></td>
    </tr>
    <?
    
    $i=mysql_num_rows($res)+1;
    while($row=mysql_fetch_array($res))
      {
	$mail_sender=$row["nameout"];
	$mail_namein=$row["namein"];
	$mail_message=$row["message"];
	$mail_date=$row["date"];

	$mail_date=date("d.m.Y - H:i",$mail_date);
	$i--;
	if($mail_sender!=$user) $l=1; else $l=2;

?>
      <tr>
      <td align="left" style='border:1px solid#ddd;'>
      <span class="mailtext'.$l.'"><?=$mail_message;?><br /><span class="mailtitle'.$l.'"><?=$mail_nameout;?>  (<?=$mail_date;?>)<span class="mailnum'.$l.'"><?=$i;?></span></span></span>
      </td>
      </tr><?
      }
    ?>
    <tr class="bot">
      <td class="empty"></td>
    </tr></tbody>
    </table>   
    </center>

<?
}
?>