<?php
$pagetitle="Входящие сообщения";
include('header.php');
require_once('.zsecurity.php');
require_once ("bbcode/bbcode.lib.php");
?>

<script type="text/javascript">
function setChecked(obj){
	var check = document.getElementsByName("chkb[]");
	for (var i=0; i<check.length; i++) {check[i].checked = obj.checked;}
}
</script>

<?php

function GetAvatar($nameout, $system=false) {
			$GetAvatar = "avatar/no.png";
			if($system!=false && $system==1) {
				$GetAvatar = "avatar/SG.gif";
			}elseif($system!=false && $system==2) {
				$GetAvatar = "avatar/SG.gif";
			}else{
				$sql_i = mysql_query("SELECT `avatar` FROM `tb_users` WHERE `username`='$nameout'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
				if(mysql_num_rows($sql_i)>0) {
					$row_i = mysql_fetch_assoc($sql_i);
					$GetAvatar = "avatar/".$row_i["avatar"];
				}
			}
			return $GetAvatar;
		}
		
if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"]))
{
	echo '<font color="red"><b>Ошибка! Для доступа к данной странице необходимо зарегистрироваться!</b></font><br /><br />';
	include('footer.php');
	exit();
}else{
	require('config.php');

	//echo '<a href="'.$_SERVER["PHP_SELF"].'" class="block1"><b>Входящие сообщения</b></a> | <a href="/outbox.php"><b>Исходящие сообщения</b></a> | <a href="/newmsg.php"><b>Новое сообщение</b></a><br /><br />';

        echo '<div align="center">';
        echo '<a class="filterlineactive" href="/inbox.php">Входящие сообщения</a>'; 
   echo '<a class="filterline" href="/outbox.php">Исходящие сообщения</a>';
  echo '<a class="filterline" href="/newmsg.php">Новое сообщение</a>';
echo '</div>';
  echo '<br/>'; 

	$username = uc($_SESSION["userLog"]);

	$KOL_ZP = "20";
	if (isset($_GET["page"]))
		{
			if(preg_match("|^[\d]*$|",$_GET['page']))
				{
					$page = (int)limpiar($_GET["page"]);
					if($page>0) {$start = abs(($page - 1) * $KOL_ZP);}else{$page = "1"; $start = "0";}
				}else{
					$page = "1";
					$start = "0";
				}
		}else{
			$start = "0";
			$page = "1";
		}

if(isset($_GET["option"]))
{
	$option = limpiar($_GET["option"]);

	if($option=="del")
		{
			if(isset($_POST['chkb']))
				{
					$chkb = $_POST['chkb'];
					$chkb = array_map('intval',$chkb);
					$chkb = array_map('mysql_real_escape_string',$chkb);
					$chkb = implode("','",$chkb);

					mysql_query("DELETE FROM `tb_mail_in` WHERE (`id` IN ('$chkb') AND `namein`='$username')") or die(mysql_error());
				}
		}
}

if(isset($_GET["view"]))
{
	if(preg_match("|^[\d]*$|",$_GET['view']))
		{
			$view = (int)limpiar($_GET["view"]);

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

			if($view > 0)
			{
				$sql = mysql_query("SELECT * FROM tb_mail_in WHERE id='$view' AND namein='$username'");
				if(mysql_num_rows($sql)>0)
					{
						$row = mysql_fetch_assoc($sql);

						$id = $row["id"];
						$nameout = $row["nameout"];
						$subject = $row["subject"];
						$message = readBR($row["message"]);
						$status = $row["status"];

						$message = new bbcode($message);
						$message = $message->get_html();

						if($status=="0") {
							mysql_query("UPDATE tb_mail_in SET status='1' WHERE id='$id'") or die(mysql_error());
							mysql_query("UPDATE tb_mail_out SET status='1' WHERE ident='$id'") or die(mysql_error());
						}

						echo "<b>От кого:</b>&nbsp;$nameout<br />";
						echo "<b>Тема:</b>&nbsp;$subject<br />";
						echo "<b>Сообщение:</b>&nbsp;<br />$message<br /><br />";
						echo '<font color="red">Внимание! Администрация НИКОГДА не спрашивает логин и пароль у своих пользователей! Логин администрации - admin.</font><br /><br />';
						echo '<form action="newmsg.php?re='.$id.'" method="get"><input type="hidden" name="re" value="'.$id.'"><input type="submit" value="Ответить на сообщение" /></form>';

						include('footer.php');
						exit();
					}else{
						echo '<font color="red"><b>Ошибка! Такого сообщения нет!</b></font>';
						include('footer.php');
						exit();
					}
			}else{
				echo '<font color="red"><b>Ошибка! Такого сообщения нет!</b></font>';
				include('footer.php');
				exit();
			}
		}else{
			echo '<font color="red"><b>Ошибка! Такого сообщения нет!</b></font>';
			include('footer.php');
			exit();
		}
}



$sql_page = mysql_query("SELECT id FROM tb_mail_in WHERE namein='$username'");
$kol_page = mysql_num_rows($sql_page);

?><form action="<?php echo $_SERVER['PHP_SELF'];?>?option=del" method="post">
<table class="tables">
	<thead><tr>
	<!--<th class="top">Дата</th>-->
	<th class="top" colspan="2">Отправитель</th>
	<th class="top">Тема</th>
	<th class="top">Просмотр</th>
	<th class="top"><input type="checkbox" name="set" onclick="setChecked(this)" /></th>
	</tr></thead><?

$sql = mysql_query("SELECT * FROM tb_mail_in WHERE namein='$username' ORDER BY id DESC limit $start,$KOL_ZP");
if(mysql_num_rows($sql)>0)
	{
		while ($row = mysql_fetch_assoc($sql))
			{
				$id = $row["id"];
				$nameout = $row["nameout"];
				$subject = $row["subject"];
				$date = DATE("d.m.Yг. в H:i",$row["date"]);

				$img = ($row["status"]==0) ? "messnoread.ico" : "messread.ico";
				$title = ($row["status"]==0) ? "Сообщение не прочитано" : "Сообщение прочитано";

				?><tr>
					<!--<td align="center"><?php echo $date;?></td>-->
					<?php
					
		
					$sql_igr_us=mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$nameout."'"));	
					$sql_where = mysql_query("SELECT `pagetitle` FROM `online` WHERE `username`='".$nameout."'");
  if(mysql_num_rows($sql_where)>0) {
     $where_user = '<b style="color:black;">[</b><b style="color:#0dbd0d;">OnLine</b><b style="color:black;">]</b>';
  }else{
   $where_user = '<b style="color:black;">[OffLine]</b>';
  }
  $notif_avatar = GetAvatar($nameout, ($nameout=="Система" ? 1 : false));
  $_adm_online = mysql_num_rows(mysql_query("SELECT `id` FROM `tb_online` WHERE `username`='".$nameout."'"));
					echo '<td style="text-align:center; width:70px; border-right:none; padding:2px 5px;"><a href="/wall?uid='.$sql_igr_us["id"].'" target="_blank]"><img src="/'.$notif_avatar.'" class="avatar" style="width:50px; height:50px" border="0" alt="avatar" title="Перейти на стену пользователя '.$nameout.'"></a></td>';
                           echo ' <td style="text-align:left; border-left:none; padding-left:0px;width: 150px;">';
                            echo '<div class="rw_login" style="text-align:left;"><span style="color:#696969;">Логин:</span> '.$nameout.'</div>';
							echo "<div class=\"rw_status\" style=\"text-align:left; margin-top:1px; font-weight:bold;\"><span style=\"color:#696969;\">Дата:</span> " . $date . "</div>";
							//echo '<span style="color:#696969;">'.$_adm_online.'</span>';
							?>
							<span style="color:#000;">[ <?php echo ($_adm_online==1 ? '<span style="color:green;"><b>OnLine</b></span>' : '<span style="color:red;"><b>OffLine</b></span>');?> ]</span>
							</td>
							
					<!--<td align="center"><?php echo $nameout;?></td>-->
					<td align="center"><?php echo $subject;?></td>
					<td align="center"><img src="img/<?=$img?>" width="16" height="16" border="0" align="absmiddle" alt="" title="<?=$title?>" />&nbsp;<a href="<?=$_SERVER['PHP_SELF']?>?view=<?=$id?>">Просмотр</a></td>
					<td align="center"><input type="checkbox" name="chkb[]" value="<?php echo $id;?>" /></td>
				</tr><?
			}
	}else{
		echo '<tr align="center"><td colspan="5"><b>Сообщений нет</b></td></tr>';
	}

echo "</table>";
if($kol_page>0) {echo '<br><input type="submit" class="proc-btn" style="float:right; display:block;" value="Удалить отмеченные" /><br>';}
echo "</form>";

	if($kol_page>$KOL_ZP){
		echo '<br /><b>Страницы:</b>&nbsp;';
		$num_pages=ceil($kol_page/$KOL_ZP);
		for($i=1;$i<=$num_pages;$i++)
		{
			if($i == $page)
			{
				echo '<font color="red"><b>'.$i.'</b></font>&nbsp;';
			}else{
				echo '<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'"><b>'.$i.'</b></a>&nbsp;';
			}
		}
	}

}
?>

<?php include('footer.php');?>