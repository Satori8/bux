<?php

$rid = (isset($_GET["rid"])) ? intval($_GET["rid"]) : false;

//if($username=='vladok108' or $username=='Administrator'){
if(1==1){
?>
<link rel="stylesheet" type="text/css" href="/style/task.css?v=1.02" />
<div id="load" style="display: none;"></div>
<script src="/js/js_task.js?v=1.00"></script>
<script src="/view_task/tack_info.js?v=1.00"></script>
<script type="text/javascript">
function ShowHideInfo(id) {
	if($("#"+id).css("display") == "none") {
		$("#"+id).css("display", "");
	} else {
		$("#"+id).css("display", "none");
	}
}

function AddClaims(id, type) {
	var tm;
	var claimstext = $.trim($("#claimstext"+id).val());

	function hidemsg() {
		$("#info-claims-"+id).slideToggle("slow");
		if (tm) clearTimeout(tm);
	}

	if(claimstext.length<10) {
		$("#info-claims-"+id).show().html('������� ������� 10 �������� ������ ��� ������.<br>');
		tm = setTimeout(function() {hidemsg()}, 3000);
		return false;
	} else {
		$.ajax({
			type: "POST", url: "/ajax/ajax_claims_task.php?rnd="+Math.random(), data: {'id':id, 'type':type, 'claimstext':claimstext}, 
			dataType: 'json', beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				$("#info-claims-"+id).show().html('������ ��������� ������! ���� ������ �����������, �������� ������������� �����.<br>');
				tm = setTimeout(function() {hidemsg()}, 5000);
				return false;
			}, 
			success: function(data) {
				$("#loading").slideToggle();
				$("#info-claims-"+id).html("");

				if (data.result == "OK") {
					$("#formclaims"+id).show().html(data.message);
					tm = setTimeout(function() {$("#claims"+id).click()}, 5000);
					return false;
				} else {
					if(data.message) {
						$("#info-claims-"+id).show().html(data.message);
						tm = setTimeout(function() {hidemsg()}, 3000);
						return false;
					} else {
						$("#info-claims-"+id).show().html("������ ��������� ������!");
						tm = setTimeout(function() {hidemsg()}, 3000);
						return false;
					}
				}
			}
		});
	}
}

</script>

<?
//////////////////////////////////////////////////////////////////////////////////////
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_b = mysql_query("SELECT * FROM `tb_black_users` WHERE `name`='$username' ORDER BY `id` DESC");
	if(mysql_num_rows($sql_b)>0) {
		$row_b = mysql_fetch_array($sql_b);
		$prichina = $row_b["why"];
		$kogda = $row_b["date"];
		echo '<span class="msg-error">��� ������� ������������! �� �� ������ ������������� �������!<br><u>���� ����������</u>: '.$row_b["date"].'</span>';
		include('footer.php');
		exit();
	}
}


if($my_rep_task <= -10) {
	echo '<span class="msg-error">�� �� ������ ��������� �������! ���� ��������� �����������&nbsp;&nbsp;'.$my_rep_task.'</span>';
	include('footer.php');
	exit();
}


if(isset($_GET["op"]) && isset($_GET["rid"]) && limpiar($_GET["op"])=="dell") {
	$sql_dell = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' AND (`status`='' OR `status`='dorab') ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_dell)>0) {
		$row_dell = mysql_fetch_row($sql_dell);
		mysql_query("DELETE FROM `tb_ads_task_pay`  WHERE `id`='".$row_dell["0"]."' LIMIT 1") or die(mysql_error());
		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiarez($_GET["page"]).'&rid='.$rid.'");</script>';
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'
AND `username` NOT IN (SELECT `user_name` FROM `tb_black_list` WHERE `user_bl`='$username' AND `type`='usr_adv' AND `tasks_usr`='1') 
	AND `username` NOT IN (SELECT `user_bl` FROM `tb_black_list` WHERE `user_name`='$username' AND `type`='usr_adv' AND `tasks_adv`='1') 
");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$rek_name = $row["username"];
	$country_targ = $row["country_targ"];
	$status = $row["status"];

	$check_user = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name' AND `user_name`='$username'");
	if(mysql_num_rows($check_user)) {
		echo '<span class="msg-error">�� �� ������ ��������� ������� ����� �������������, ��� ��� ������������� �������� ��� ��������� ��� �������!</span>';
		include('footer.php');
		exit();
	}elseif($country_targ==2 && strtolower($my_country)!="ua") {
		echo '<fieldset class="errorp">������!! �� �������� ���-���������� ������� �� �������� ��� ���������� � ������!&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}elseif($country_targ==1 && strtolower($my_country)!="ru") {
		echo '<fieldset class="errorp">������!!! �� �������� ���-���������� ������� �� �������� ��� ���������� � ������!&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}else{ 
	
	$sql_uss = mysql_query("SELECT avatar,joindate2,username,reiting FROM `tb_users` WHERE `id`='".$row['user_id']."'");
	$row_uss = mysql_fetch_array($sql_uss);
	$time_reg=DATE("d.m.Y�. � H:i",$row_uss["joindate2"]);
	$time_soz=DATE("d.m.Y�. � H:i",$row["date_add"]);
	
	$goog_us =mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='good_auto' or `status`='good') && `rek_name`='".$row_uss['username']."'"));
	$bad_us = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='bad' && `rek_name`='".$row_uss['username']."'"));
	$dorab_us=mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_notif` WHERE `type`='dorab' && `rek_name`='".$row_uss['username']."'"));
	
	$goog_us_s =mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='good_auto' or `status`='good') && `ident`='".$rid."'"));
	$bad_us_s = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='bad' && `ident`='".$rid."'"));
	$dorab_us_s=mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_notif` WHERE `type`='dorab' && `ident`='".$rid."'"));
	
	
	if($row_uss['reiting']<0) $row_uss['reiting'] = '<span style="color:#FF0000; text-shadow: 1px 1px #000;">'.$row_uss['reiting'].'</span>';
	$reitt=$row_uss['reiting'];
	$sql_rang_s = mysql_query("SELECT * FROM `tb_config_rang` WHERE `r_ot`<='$reitt' AND `r_do`>='".floor($reitt)."'");
	if(mysql_num_rows($sql_rang_s)>0) {
		$row_rang_s = mysql_fetch_assoc($sql_rang_s);
	}else{
		$sql_rang_s = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang_s = mysql_fetch_assoc($sql_rang_s);
	}
	$my_id_rang_s = $row_rang_s["id"];
	$my_rang_s = $row_rang_s["rang"];
	
	$us_otz = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `coment`!='' && `ident`='".$rid."'"));
	$sql_otc_wait = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && `status`='wait' && ident='$rid'");
    $sql_otc_good = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && (`status`='good' or `status`='good_auto') && ident='$rid'");
    $sql_otc_good_c = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && (`status`='wait' or`status`='good' or `status`='good_auto') && ident='$rid' ORDER BY `id` DESC LIMIT 1");
	
	$sql_otc_bad = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && `status`='bad' && ident='$rid'");
	
	$time_dist=time()-$row['distrib'];
	$sql_distrib = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `date_start`>'$time_dist' && ident='$rid'");
	$sql_pss = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1"));
	//$ffd=mysql_fetch_array($sql_distrib);
	//echo $ffd['id'];
	//echo mysql_num_rows($sql_distrib); 
	
	$sql_otc_dorab = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && `status`='dorab' && ident='$rid'");
	$sql_otc_v = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && ident='$rid'");
	$sql_otc_pro = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && `status`='' && ident='$rid'");
	$sql_otc_pro_x = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' && ident='$rid'");
	$sql_otc_pro_vv = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='dorab') && ident='$rid'");
	$num_price=$row['totals']-mysql_num_rows($sql_otc_pro_vv);
	//echo mysql_num_rows($sql_otc_pro_vv);
		require_once(ROOT_DIR."/bbcode/bbcode.lib.php");
?>
<div class="blok" align='center'>
  <table class="tables"><thead><th align="center" colspan="2">������������ ������� � <?=$rid;?>  </th></thead></table>
 <?
  $s_task_st = mysql_fetch_array(mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='".$row_uss['username']."' && id='$rid'"));
		  if($s_task_st['status']=='pause' or $s_task_st['status']=='wait'){
		  echo '<span class="msg-warning">��� ������� ������ �� �������.</span>';
				if(mysql_num_rows($sql_otc_v)==0){
					include('footer.php');
					exit();
				}
		  }
 ?>
  <div style="padding: 3px;">           
      <span style="font-size: 13px;" class="status">
	  <?=str_replace("\r\n","<br>", $row["zdname"]);?>
	  </span>
      <span class="letter-subtitle">���������� �� ������<span style="float:right;">����������� �� �������: <?=$time_reg;?> </span></span>
        
      <table style="padding:0px;" border="0" cellpadding="0" cellspacing="0">
        <tbody><tr>
          <td width="12%" valign="top" align="center" rowspan="4">
		    <a href="/wall?uid=<?=$row["user_id"];?>" title="����� ������" target="_blank">
		      <img class="avatar_wall" border="0" src="/avatar/<?=$row_uss['avatar'];?>" align="absmiddle" width="60" height="60">
		    </a>
	      </td>
          <td style="padding:2px; border-bottom: 1px solid #E0E0E0" width="100%" align="left">
		    �����: 
		    <span class="status">
		      <font color="#114C73">
		        <a style="border-bottom: 1px dotted;" href="/wall?uid=<?=$row["user_id"];?>" target="_blank" title="�����"><?=$row["username"];?></a>
		      </font>
		    </span> 
		    <span class="status"><b>[<?=$my_rang_s;?> - <?=$row_uss['reiting'];?>]</b></span> 
		    		  </td>
		  <td align="right" style="padding:0px; border-bottom: 1px solid #E0E0E0" width="30%">
		    <a href="/view_task.php?page=task&op=&sort=1&sort_z=&type=&task_search=4&task_name=<?=$row["user_id"];?>&task_price=0.20&s=1" target="_blank"><input style="border: 1px solid #006699; background: #F7F7F7; color:#006699;cursor: pointer;" value="��� ������� ������" type="submit"></a>
		  </td>
        </tr>
        <tr>
          <td colspan="2" style="padding:0px; border-bottom: 1px solid #E0E0E0" width="85%" align="left">���������� �� ���� �������� ������ �� ����� ������ �� �������</td>
	    </tr>
        <tr>
          <td colspan="2" style="padding:0px; border-bottom: 1px solid #E0E0E0" width="85%" align="left">
		    �������� <span class="status"><font color="#ab0606" title="�� ��� ����� ������ �� ������� ����� ������� ������� <?=$goog_us;?> ������������" style="cursor: help;"><?=$goog_us;?></font></span><font color="gray"> / </font>
		    ��������� <span class="status"><font color="#999999" title="�� ��� ����� ������ �� ������� ����� �������� �� ��������� ������� <?=$dorab_us;?> ������������" style="cursor: help;"><?=$dorab_us;?></font></span> /
		    ��������� <span class="status"><font color="#c80000" title="�� ��� ����� ������ �� ������� ����� ������� � ������ <?=$bad_us;?> ������������" style="cursor: help;"><?=$bad_us;?></font></span> /
		  </td>
        </tr>
      </tbody></table> 
        
      <span class="letter-subtitle">���������� � ������� � <?=$rid;?> <span style="float:right;">�������: <?=$time_soz;?></span></span>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tbody><tr> 
          <td style="padding:0px; border-bottom: 1px solid #E0E0E0" width="75%" align="left"> 
		    �������� <span class="status"><font color="#ab0606" title="��������" style="cursor: help;"><?=$goog_us_s;?></font></span> /
					      ��������� <span class="status"><font color="#999999" title="���������� �� ���������" style="cursor: help;"><?=$dorab_us_s;?></font></span> /
					    ��������� <span class="status"><font color="#c80000" title="���������" style="cursor: help;"><?=$bad_us_s;?></font></span>
					  </td>
          <td style="padding:0px;" width="25%" align="right" nowrap="nowrap">
            <span class="status"><font color="#ab0606">������: <font size="3"><?=$row["zdprice"];?></font> ���.</font></span>
		  </td>
        </tr>
        <tr>
          <td style="padding:2px; border-bottom: 1px solid #E0E0E0" width="100%" align="left">
		    ���������:
		    
		    		      <font color="#D28000">
						  <?
		if($row['zdtype']=='1'){
			echo '�����.';
		}elseif($row['zdtype']=='2'){
			echo '����������� ��� ����������.';
		}elseif($row['zdtype']=='3'){
			echo '����������� � �����������.';
		}elseif($row['zdtype']=='4'){
			echo '������� � �����.';
		}elseif($row['zdtype']=='5'){
			echo '������� � �����.';
		}elseif($row['zdtype']=='6'){
			echo '�����������.';
		}elseif($row['zdtype']=='7'){
			echo '�������� ������.';
		}elseif($row['zdtype']=='8'){
			echo '������.';
		}elseif($row['zdtype']=='9'){
			echo 'YouTube';
		}elseif($row['zdtype']=='10'){
			echo '���������� ����';
		}elseif($row['zdtype']=='11'){
			echo '�������� ������';
		}elseif($row['zdtype']=='12'){
			echo '�������� �����';
		}elseif($row['zdtype']=='13'){
			echo '������ � ����';
		}elseif($row['zdtype']=='14'){
			echo '�������������';
		}elseif($row['zdtype']=='15'){
			echo '����� ���� ��������� �� '.$_SERVER["HTTP_HOST"].'';
		}elseif($row['zdtype']=='16'){
			echo '������� ��������';
		/*}elseif($row['zdtype']=='17'){
			echo '�� ������ ��������';*/
		}elseif($row['zdtype']=='18'){
			echo 'Forex';
		}elseif($row['zdtype']=='19'){
			echo '��������� ����������';
		}elseif($row['zdtype']=='20'){
			echo '������ � ������';
		}elseif($row['zdtype']=='21'){
			echo '������ � ��������������';
		}elseif($row['zdtype']=='22'){
			echo '������������� ����/�����';
		}elseif($row['zdtype']=='23'){
			echo '���������� �����';
		}
			
			
			
					?>	  
						  </font>
		    		    
		  </td>
          <td style="padding:2px;" width="25%" align="right">
		  <?if($row['reiting']>='0' and $row['reiting']<'1'){?>
            <span class="rating0" title=""></span>
			<?}elseif($row['reiting']>='1' and $row['reiting']<'2'){?>
            <span class="rating1" title=""></span>
			<?}elseif($row['reiting']>='2' and $row['reiting']<'3'){?>
            <span class="rating2" title=""></span>
			<?}elseif($row['reiting']>='3' and $row['reiting']<'4'){?>
            <span class="rating3" title=""></span>
			<?}elseif($row['reiting']>='4' and $row['reiting']<'4,5'){?>
            <span class="rating4" title=""></span>
			<?}elseif($row['reiting']>='4,5' and $row['reiting']<='5'){?>
            <span class="rating5" title=""></span>
			<?}?>
          </td>

        </tr>
		
		<tr>
		
		<td style="padding:2px; border-bottom: 1px solid #E0E0E0" width="75%" align="left">
		    ����������� �� ��������: <font color="#D28000"><?=$row['zdreit_us'];;?> ������ � ����</font>
		  </td>
		</tr>
		<tr>
		<td style="padding:2px; border-bottom: 1px solid #E0E0E0" width="75%" align="left">
		��������:<font color="#D28000">
		<?if($row["zdcheck"]=='1') {?>
		� ������� 5-�� ����
		<?}else{?> �������������<?}?>
		</font>
		  </td>
		</tr>
		
	    <tr>

          <td style="padding:2px; border-bottom: 1px solid #E0E0E0" width="75%" align="left">
            ��������� ����������: <font color="#D28000">
			<?
			if($row["zdre"]==3){
			$zp=1;
				echo '����������� - ������ 3 ����';
			}elseif($row["zdre"]==6){
			
			$zp=1;
				echo '����������� - ������ 6 �����';
			}elseif($row["zdre"]==12){
			$zp=1;
				echo '����������� - ������ 12 �����';
			}elseif($row["zdre"]==24){
			$zp=1;
				echo '����������� - ������ 24 ���� (1 �����)';
			}elseif($row["zdre"]==48){
			$zp=1;
				echo '����������� - ������ 48 ����� (2-� �����)';
			}elseif($row["zdre"]==72){
			$zp=1;
				echo '����������� - ������ 72 ���� (3-� �����)';
			}else{
			$zp=0;
				echo '���� ������������ � ���� ����������';
			}
			$sql_o=mysql_fetch_array($sql_otc_good_c);
			//
			//echo mysql_num_rows($sql_otc_good_c);
			//echo $sql_o['date_end'];
			$sg=($sql_o['date_end']+$row["zdre"]*60*60)-time(); 
			?> 
			</font>
		  </td> 
          		<td style="padding:2px;" width="25%" align="right">
		    <?if($us_otz=='0'){?>
		    		      <span class="textgray">������� ��� </span>
						  <?}else{?>
						  <a href="#" onclick="popup_w('��� ������ � ������� �<?=$rid;?>', false, 600, 'func=all_com&id=<?=$rid;?>', '../ajax/ajax_task.php');return false;">������ (<?=$us_otz;?>)</a>
						  
						  <?}?>
		    		    
		  </td>
        </tr>
		

		        <tr>
				 <?if($row_uss['username']!=$username){?>
		  <td style="padding-top:5px;" colspan="2" align="center">
            			
				<?		
			if(mysql_num_rows($sql_otc_good)>0 or mysql_num_rows($sql_otc_bad)>0){
				//echo '<a href="#" onclick="popup_w(\'����� � ������� �'.$rid.'\', false, 500, \'func=task_comment&amp;id='.$rid.'\', \'/ajax/ajax_task.php\');return false;" class="btn blue"><font color="#fff">�������� �����</font></a>';
				echo '<a class="btn blue" href="javascript: void(0);" onclick="add_coment(\''.$rid.'\');"><font color="#fff">�������� �����</font></a>';
				}		
			
				
				
				$rek_id_z=$row["user_id"];
				$sql_f = mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `type`='favorite' AND `user_id`='$partnerid' AND `rek_id`='$rid'");
			if (mysql_num_rows($sql_f) > 0) {
				echo '<span class="scon-del"><span class="btn blue task-favorite-r" style="cursor: pointer;" onclick="js_post_task(this, \'../ajax/ajax_task.php\', \'func=del_izb_task&id='.$rid.'\');return false;"><font color="#fff">��������� �� ����������</font></span></span>';
				
				echo '<span class="scon-ok" style="display: none; "><span class="btn green task-favorite-r" style="cursor: pointer;" onclick="js_post_task(this, \'../ajax/ajax_task.php\', \'func=add_izb_task&id='.$rid.'\');return false;"><font color="#fff">�������� � ���������</font></span></span>';
			}else{				
				echo '<span class="scon-ok"><span class="btn green task-favorite-r" style="cursor: pointer;" onclick="js_post_task(this, \'../ajax/ajax_task.php\', \'func=add_izb_task&id='.$rid.'\');return false;"><font color="#fff">�������� � ���������</font></span></span>';
				
				echo '<span class="scon-del" style="display: none; "><span class="btn blue task-favorite-r" style="cursor: pointer;" onclick="js_post_task(this, \'../ajax/ajax_task.php\', \'func=del_izb_task&id='.$rid.'\');return false;"><font color="#fff">��������� �� ����������</font></span></span>';
				
			}
			
			
			echo'<span class="btn red task-favorite-r" style="cursor: pointer;">[<a href="javascript: void(0);" onclick="Infotack(\''.$rek_id_z.'\');" style="color:#fff;" title="��������� ������������ '.$row_uss['username'].' � ������ ������ (Black List)">��������� ������ � BL</a>]</span>';
			?>
		    		    <!--  <span class="btn red task-favorite-r" style="cursor: pointer;" onclick="js_post(this, 'ajax/earnings/ajax-task.php', 'func=del-task&id=270474');return false;"><font color="#fff">��������� ������  � BL</font></span>-->
			  		  <?
					  echo '<span class="btn red task-favorite-r" style="cursor: pointer;" id="claims'.$rid.'" onClick="ShowHideInfo(\'claims_'.$rid.'\');" style="cursor:pointer;padding-left:6px;"title="������������ �� �������"/><font color="#fff">������������ �� �������</font></span>';
					  ?>
					  </td>
					  <?}?>
		</tr>
      			  <tr>
      <td class="taskmsg" style="display: none; ">
	  </td>
    </tr>  
      <?
echo '<tr id="claims_'.$rid.'"  colspan="3" style="display:none;">';
		echo '<td colspan="3" align="center" class="td-serf" style="color: #FFFFFF; background-color: #FF9966; padding: 5px 0px; font: 12px Tahoma, Arial, sans-serif;">';
			echo '<div id="info-claims-'.$rid.'" style="display:none;"></div>';
			echo '<div id="formclaims'.$rid.'"><table style="width:100%; margin:0; padding:0;">';
			echo '<tr>';
				echo '<td align="center" width="100" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;">����� ������:</td>';
				echo '<td align="center" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><div id="newform"><input type="text" id="claimstext'.$rid.'" maxlength="100" value="" class="ok" style="margin:0; padding:1px 5px;" /></div></td>';
				echo '<td align="center" width="120" style="color: #FFFFFF; background-color: #FF9966; border:none; padding:0; margin:0;"><span onClick="AddClaims(\''.$rid.'\', \'task\');" class="sub-red" style="float:none;">���������</span></td>';
			echo '</tr>';
			echo '</table></div>';
		echo '</td>';
	echo '</tr>';
?>
      </tbody></table>
      
      
	  <span class="letter-subtitle">�������� �������:</span>

	  <div class="maintask" style="text-align: left;">
	  <? 
	  function desc_bb($desc) {
	$desc = new bbcode($desc);
	$desc = $desc->get_html();
	$desc = str_replace("&amp;", "&", $desc);
	return $desc;
}
	  echo desc_bb(str_replace("<br>","\r\n", $row["zdtext"]));?>
	  </div><br>
      <span class="letter-subtitle">��� ����� ������� ��� ���������� �������:</span>

      <span class="yellowbk">
	  <?if($row["zotch"]!=""){
		    echo $row["zotch"];
		    }else{
			echo '����������� �����';
			}
			?></span>
           
           <?
		   //echo $sg; 
		   
		   if(mysql_num_rows($sql_otc_wait)>0){?>
		    <div id='otc-<?=$rid;?>'>
			<span class="letter-subtitle">��� �����</span>
			<span class="yellowbk">
        <span style="cursor: pointer; border-bottom: 1px dotted #9f6000;" onclick="popup_w('����� � ������� �<?=$rid;?>', false, 700, 'func=zero_otchet&amp;id=<?=$rid;?>', '/ajax/ajax_task.php');return false;">���������� �����</span>
      </span>
	  </div>
	 <?}else{?>
	 <div id='otc-<?=$rid;?>' style="display: none; ">
			<span class="letter-subtitle">��� �����</span>
			<span class="yellowbk">
        <span style="cursor: pointer; border-bottom: 1px dotted #9f6000;" onclick="popup_w('����� � ������� �<?=$rid;?>', false, 700, 'func=zero_otchet&amp;id=<?=$rid;?>', '/ajax/ajax_task.php');return false;">���������� �����</span>
      </span>
	  </div>
	 <?}?>
	 
	  <table id="ot_task-<?=$rid;?>">	  		  
      <td class="taskmsg_zz" style="display: none; "></td>
	  </table>
      <span class="letter-subtitle">��������!</span>	    
	      
		  <?
		  if($row["time"]==1)
				$time_ad=1;
			elseif($row["time"]==2)
				$time_ad=3;
			elseif($row["time"]==3)
				$time_ad=12;
			elseif($row["time"]==4)
				$time_ad=24;
			elseif($row["time"]==5)
				$time_ad=72;
			elseif($row["time"]==6)
				$time_ad=168;
			elseif($row["time"]==7)
				$time_ad=336;
			elseif($row["time"]==8)
				$time_ad=720;
			else{
				$time_ad=1;
			}
		  
		 
			if($row_uss['username']==$username){
		  echo '<span class="msg-warning">�� �� ������ ��������� ��� �������, �.�. �� ��������� ��� �������.</span>';	
			
			}elseif($num_price<=0 and mysql_num_rows($sql_otc_pro_x)<=0){
			
			echo '<span class="msg-warning">��� ������� ��� ��������� ������ ���������� �������������!</span>';
			
			}elseif($row['distrib']>0 && mysql_num_rows($sql_distrib)>0 && $sql_pss<=0){
			
			echo '<span class="msg-warning">������� �������� ����������, ���������� �������� ����������!</span>';
			
			}elseif(mysql_num_rows($sql_otc_wait)>0 and $zp==0){
			
			?>
			<div class="blockwaittask">�������, ��� ����� ������!<br><span class="text12">���������, ���� ��� �������� �������������.<br>��� ����� �� ����� 5 ����</span></div>
			
			<?}elseif(mysql_num_rows($sql_otc_wait)>0 and $sg>0 and $zp==1){?>
		  <div class="blockwaittask">�������, ��� ����� ������!<br><span class="text12">���������, ���� ��� �������� �������������.<br>��� ����� �� ����� 5 ����</span></div>
		  <?  
		  //echo $sg; 
		  //echo $zp; echo 'f'; 
		  //echo 'ff';
		  				if($row["zdre"]>0) {   
				
						$ost = ($sql_o["date_end"] + ($row["zdre"] * 60 * 60) );  
						$ost = ( $ost - time() );
//echo $sql_o["date_end"];
						if($ost > 0){
						echo '<br>';
							$d = floor($ost/86400);
							$h = floor( ($ost - ($d * 86400)) / 3600);
							$i = floor( ($ost - ($d * 86400) - ($h * 3600)) / 60 );
							$s = floor($ost - ($d * 86400) - ($h * 3600) - ($i * 60));

							if($d==0)
								$dn="����";
							elseif($d==1)
								$dn="����";
							elseif($d==2) 
								$dn="���";
							elseif($d==3)
								$dn="���";
							else{ $dn=""; }

							if($h>=0) {$hn="�����";}else{$hn="";}
							if(($h>1 && $h<5) | $h>20) {$hn="����";}
							if($h==1 | $h==21) {$hn="���";}

							if($i>0) {$mn="������";}else{$mn="";}
							if($i==1 | $i==21 | $i==31 | $i==41 | $i==51) {$mn="������";}
							if( ($i<21 && $i>4) | $i==0 | $i===20 | $i==30 | $i==40 | $i==50 | ($i>24 && $i<30) | ($i>34 && $i<40) | ($i>44 && $i<50) | ($i>54 && $i<60)) {$mn="�����";}

							if($s>0) {$sn="�������";}else{$sn="";}
							if($s==1 | $s==21 | $s==31 | $s==41 | $s==51) {$sn="�������";}
							if( ($s<21 && $s>4) | $s==0 | $s===20 | $s==30 | $s==40 | $s==50 | ($s>24 && $s<30) | ($s>34 && $s<40) | ($s>44 && $s<50) | ($s>54 && $s<60)) {$sn="������";}

							if($s>0) {$date_next = "<b>$s</b> $sn";}
							if($i>0) {$date_next = "<b>$i</b> $mn <b>$s</b> $sn";}
							if($h>0) {$date_next = "<b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
							if($d>0) {$date_next = "<b>$d</b> $dn <b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
							if($h==0 && $i==0 && $s==0) {$date_next = "<b>$d</b> $dn";}

							echo '<td colspan="2" height="40px">��������� ���������� ����� ������� ����� �������� ����� '.$date_next.'</td>';
				
				}
				}    
		  
		    echo '<div id=\'ok_otch\' style="display: none;">';
		  echo '<div class="blockwaittask">�������, ��� ����� ������!<br><span class="text12">���������, ���� ��� �������� �������������.<br>��� ����� �� ����� 5 ����</span></div>';
		  echo '</div>';
		  
		   $z_otc_pro=mysql_fetch_array($sql_otc_pro);
		   $time_zz=time();
			
		  ?>
		   <div id='new_otch' style="display: none;">
            <div style="text-align: left;">
              <b>�� ���������� ����� ������� ���������� �� ����� <font color="#C80000">
			  <?
			  if($row["time"]==1)
				echo '1 ���a';
			elseif($row["time"]==2)
				echo '3 �����';
			elseif($row["time"]==3)
				echo '12 �����';
			elseif($row["time"]==4)
				echo '24 �����';
			elseif($row["time"]==5)
				echo '3 ����';
			elseif($row["time"]==6)
				echo '7 ����';
			elseif($row["time"]==7)
				echo '14 ����';
			elseif($row["time"]==8)
				echo '30 ����';
			else{
				echo '';
			}
			  ?>
			  </font></b>
              <br>����� ��������� �������, ������� �� ������ ������� ����������. ����������� �������������� ���������� � ���������� ������� ����� ����� ������ ����� ��.
			      </div>
			      
			      			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tbody><tr>				
				<td class="gosite">
			      <form name="goform" method="post" action="/view_task.php?rid=<?=$rid;?>&op=gotask" target="_blank">
			        <input name="id" value="<?=$rid;?>" type="hidden">
			        <input class="btn_big_blue" style="margin-top:15px;" title="���������� � ���������� �������" onclick="funcjs['task-start'](<?=$rid;?>);" value="������ ����������!" type="button">
		          </form>
			      <center><span class="desctext">����� ��������� ������� �� ����<br><?=$row["zdurl"];?></span></center>
			    </td>
				
		
			  </tr>
		  
            </tbody></table>
			</div>
			
			<div id='otch_r' style="display: none;">
		  <div style="text-align: left;">
	        		      <font color="corall">� �� ������ ��������� ��� ������� <b><? echo date("Y-m-d � H:i:s" , $time_zz);?></b></font>
			<?
			$time_endd=time()+($time_ad*60*60);
			?>
		    <br><font color="corall">� ����� � ���������� ���������� ������ �� ������� <b><? echo date("Y-m-d � H:i:s" , $time_endd);?></b></font><br><br>
		    ��������� ����� ����� � ���������� �������. ���� ����� �� ����� �����, �� ���������� ���������� �����, ������ �� ���������� ����� ������������. ���� �� ��������� ��������� �������, ��������
		    � ����� ���� ��, ��� ���������� ������� ��� ������������� � ������� ������ ���������� �����. ���� �� �� ����������� ��������� �������, ������� ������ ����������� �� �����������.
		    <br><br>		    
		    		      <span class="msg-warning">�������������� ��� ��������� ������.<br>�� ������� �������� ������� � ����� �� ������� ����� - ��� ��������.</span>
					    <center>
		      <span class="desctext">
		        		        <font color="corall">��� IP �����: <?=$ip;?></font> (����� ��������� ������������� �������������)
		      </span>
		    </center>
		          
		    <form method="post" action="../ajax/ajax_task.php" class="aj-form_u" data-infa="true,<?=$rid;?>,">
		      <input name="func" type="hidden" value="done_otch">
              <input name="id" type="hidden" value="<?=$rid;?>">
		          		      		        
		        <div style="position: relative;padding-top:20px;">
		          <span class="len-otch">�������� <span id="num-len">3000</span> ��������</span>
		          <textarea rows="5" name="ask_reply" id="coment" style="width: 98%; height: 150px; margin-top: 5px; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#coment', 3000, '#num-len')"></textarea>
		        </div>
		          		      		          
		      <input name="submit" class="btn_big_green" style="margin-top:10px;" title="��������� ����� � ����������" value="��������� ����� � ����������" type="submit">
		      
		    </form>

		    <br><center><span class="desctext">����� ��������� �������, �� ���������� �� <a href="<?=$row["zdurl"];?>" target="_blank">���� ������</a>.</span></center>

		    <span class="btn_big_red" style="margin-top:10px;width: 755px;text-align: center;" title="���������� �� ����������" onclick="popup_w('�������������!!', false, 450, 'func=otc_z&l=/ajax/ajax_task.php&id=<?=$rid;?>', '/ajax/ajax_task.php');return false;">���������� �� ����������</span>
		    
		  </div>
		  </div>
			
			
		  <?
		  
			}elseif(mysql_num_rows($sql_otc_good)>0 and $sg>0 and $zp==1){  
				echo '<div class="blockoktask"><b>�������� ������!</b><br><span class="text12"><b>������������� ������� � ������� ��� ����!</b></span></div>';
				
				
				//echo 'ky-ky';   
				if($row["zdre"]>0) { 
				
						$ost = ($sql_o["date_end"] + ($row["zdre"] * 60 * 60) );
						$ost = ( $ost - time() );

						if($ost > 0){
							$d = floor($ost/86400);
							$h = floor( ($ost - ($d * 86400)) / 3600);
							$i = floor( ($ost - ($d * 86400) - ($h * 3600)) / 60 );
							$s = floor($ost - ($d * 86400) - ($h * 3600) - ($i * 60));

							if($d==0)
								$dn="����";
							elseif($d==1)
								$dn="����";
							elseif($d==2) 
								$dn="���";
							elseif($d==3)
								$dn="���";
							else{ $dn=""; }

							if($h>=0) {$hn="�����";}else{$hn="";}
							if(($h>1 && $h<5) | $h>20) {$hn="����";}
							if($h==1 | $h==21) {$hn="���";}

							if($i>0) {$mn="������";}else{$mn="";}
							if($i==1 | $i==21 | $i==31 | $i==41 | $i==51) {$mn="������";}
							if( ($i<21 && $i>4) | $i==0 | $i===20 | $i==30 | $i==40 | $i==50 | ($i>24 && $i<30) | ($i>34 && $i<40) | ($i>44 && $i<50) | ($i>54 && $i<60)) {$mn="�����";}

							if($s>0) {$sn="�������";}else{$sn="";}
							if($s==1 | $s==21 | $s==31 | $s==41 | $s==51) {$sn="�������";}
							if( ($s<21 && $s>4) | $s==0 | $s===20 | $s==30 | $s==40 | $s==50 | ($s>24 && $s<30) | ($s>34 && $s<40) | ($s>44 && $s<50) | ($s>54 && $s<60)) {$sn="������";}

							if($s>0) {$date_next = "<b>$s</b> $sn";}
							if($i>0) {$date_next = "<b>$i</b> $mn <b>$s</b> $sn";}
							if($h>0) {$date_next = "<b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
							if($d>0) {$date_next = "<b>$d</b> $dn <b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
							if($h==0 && $i==0 && $s==0) {$date_next = "<b>$d</b> $dn";}

							echo '<td colspan="2" height="40px">��������� ���������� ����� ������� ����� �������� ����� '.$date_next.'</td>';
				
				}
				}    
			}elseif(mysql_num_rows($sql_otc_good)>0 and $zp==0){
			
				echo '<div class="blockoktask"><b>�������� ������!</b><br><span class="text12"><b>������������� ������� � ������� ��� ����!</b></span></div>';	
			
			}elseif(mysql_num_rows($sql_otc_bad)>0){
		  echo '<div class="blocknotask">������ ������� ��������� ��������������!</div>';
			}elseif(mysql_num_rows($sql_otc_pro)>0){
		  $z_otc_pro=mysql_fetch_array($sql_otc_pro);
		  
		  
		  echo '<div id=\'ok_otch\' style="display: none;">';
		  echo '<div class="blockwaittask">�������, ��� ����� ������!<br><span class="text12">���������, ���� ��� �������� �������������.<br>��� ����� �� ����� 5 ����</span></div>';
		  echo '</div>';
		  
		  
		  ?>
		  <div id='otch_r'>
		  <div style="text-align: left;"> 
	        
	        		      <font color="corall">� �� ������ ��������� ��� ������� <b><? echo date("Y-m-d � H:i:s" ,$z_otc_pro['date_start']);?></b></font>
		    		    
	
			<?
			$time_endd=$z_otc_pro['date_start']+($time_ad*60*60);
			?>
		    <br><font color="corall">� ����� � ���������� ���������� ������ �� ������� <b><? echo date("Y-m-d � H:i:s" , $time_endd);?></b></font>
		    <br><br>
		    ��������� ����� ����� � ���������� �������. ���� ����� �� ����� �����, �� ���������� ���������� �����, ������ �� ���������� ����� ������������. ���� �� ��������� ��������� �������, ��������
		    � ����� ���� ��, ��� ���������� ������� ��� ������������� � ������� ������ ���������� �����. ���� �� �� ����������� ��������� �������, ������� ������ ����������� �� �����������.
		    <br><br>
		    
		    		      <span class="msg-warning">�������������� ��� ��������� ������.<br>�� ������� �������� ������� � ����� �� ������� ����� - ��� ��������.</span>
					    <center>
		      <span class="desctext">
		        		        <font color="corall">��� IP �����: <?=$ip;?></font> (����� ��������� ������������� �������������)
		      </span>
		    </center>
		          
		    <form method="post" action="../ajax/ajax_task.php" class="aj-form_u" data-infa="true,<?=$rid;?>,">
		      <input name="func" type="hidden" value="done_otch">
              <input name="id" type="hidden" value="<?=$rid;?>">
		          		      		        
		        <div style="position: relative;padding-top:20px;">
		          <span class="len-otch">�������� <span id="num-len">3000</span> ��������</span>
		          <textarea rows="5" name="ask_reply" id="coment" style="width: 98%; height: 150px; margin-top: 5px; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#coment', 3000, '#num-len')"></textarea>
		        </div>
		          		      		          
		      <input name="submit" class="btn_big_green" style="margin-top:10px;" title="��������� ����� � ����������" value="��������� ����� � ����������" type="submit">
		      
		    </form>

		    <br><center><span class="desctext">����� ��������� �������, �� ���������� �� <a href="<?=$row["zdurl"];?>" target="_blank">���� ������</a>.</span></center>

		    <span class="btn_big_red" style="margin-top:10px;width: 755px;text-align: center;" title="���������� �� ����������" onclick="popup_w('�������������!!', false, 450, 'func=otc_z&l=/ajax/ajax_task.php&id=<?=$rid;?>', '/ajax/ajax_task.php');return false;">���������� �� ����������</span>
		    
		  </div>
		  </div>
		  
		  
		  <div id='new_otch' style="display: none;">
            <div style="text-align: left;">
              <b>�� ���������� ����� ������� ���������� �� ����� <font color="#C80000">
			  <?
			  if($row["time"]==1)
				echo '1 ���a';
			elseif($row["time"]==2)
				echo '3 �����';
			elseif($row["time"]==3)
				echo '12 �����';
			elseif($row["time"]==4)
				echo '24 �����';
			elseif($row["time"]==5)
				echo '3 ����';
			elseif($row["time"]==6)
				echo '7 ����';
			elseif($row["time"]==7)
				echo '14 ����';
			elseif($row["time"]==8)
				echo '30 ����';
			else{
				echo '';
			}
			  ?>
			  </font></b>
              <br>����� ��������� �������, ������� �� ������ ������� ����������. ����������� �������������� ���������� � ���������� ������� ����� ����� ������ ����� ��.
			      </div>
			      
			      			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tbody><tr>				
				<td class="gosite">
			      <form name="goform" method="post" action="/view_task.php?rid=<?=$rid;?>&op=gotask" target="_blank">
			        <input name="id" value="<?=$rid;?>" type="hidden">
			        <input class="btn_big_blue" style="margin-top:15px;" title="���������� � ���������� �������" onclick="funcjs['task-start'](<?=$rid;?>);" value="������ ����������!" type="button">
		          </form>
			      <center><span class="desctext">����� ��������� ������� �� ����<br><?=$row["zdurl"];?></span></center>
			    </td>
				
		
			  </tr>
		  
            </tbody></table>
			</div>
		  
  

		  
		  <?
		  
			}elseif(mysql_num_rows($sql_otc_dorab)>0){
			$z_otc_pro=mysql_fetch_array($sql_otc_dorab);
		echo '<div id=\'ok_otch\' style="display: none;">';
		  echo '<div class="blockwaittask">�������, ��� ����� ������!<br><span class="text12">���������, ���� ��� �������� �������������.<br>��� ����� �� ����� 5 ����</span></div>';
		  echo '</div>';
		  ?>

		  <div id='otch_r'>
		  <div style="text-align: left;">
	        
	        <font color="corall">� �� ������ ��������� ��� ������� <b><? echo date("Y-m-d � H:i:s" ,$z_otc_pro['date_start']);?></b></font>

			<?
				$time_endd=$z_otc_pro['date_start']+24*60*60;
			?>
		    <br><font color="corall">� ����� � ���������� ���������� ������ �� ������� <b><? echo date("Y-m-d � H:i:s" , $time_endd);?></b></font>
		    <br>
			<br><font color="red"><b>����������: <?=$z_otc_pro['why'];?></b></font>
		    <br>
			<br><font color="red"><b>��� ������� ���� ���������� ��� �� ���������!</b></font>
		    <br><br>
		    ��������� ����� ����� � ���������� �������. ���� ����� �� ����� �����, �� ���������� ���������� �����, ������ �� ���������� ����� ������������. ���� �� ��������� ��������� �������, ��������
		    � ����� ���� ��, ��� ���������� ������� ��� ������������� � ������� ������ ���������� �����. ���� �� �� ����������� ��������� �������, ������� ������ ����������� �� �����������.
		    <br><br>
		    
		    		      <span class="msg-warning">�������������� ��� ��������� ������.<br>�� ������� �������� ������� � ����� �� ������� ����� - ��� ��������.</span>
					    <center>
		      <span class="desctext">
		        		        <font color="corall">��� IP �����: <?=$ip;?></font> (����� ��������� ������������� �������������)
		      </span>
		    </center>
		          
		    <form method="post" action="../ajax/ajax_task.php" class="aj-form_u" data-infa="true,<?=$rid;?>,">
		      <input name="func" type="hidden" value="done_otch">
              <input name="id" type="hidden" value="<?=$rid;?>">
		          		      		        
		        <div style="position: relative;padding-top:20px;">
		          <span class="len-otch">�������� <span id="num-len">3000</span> ��������</span>
		          <textarea rows="5" name="ask_reply" id="coment" style="width: 98%; height: 150px; margin-top: 5px; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#coment', 3000, '#num-len')"></textarea>
		        </div>
		          		      		          
		      <input name="submit" class="btn_big_green" style="margin-top:10px;width: 715px;" title="��������� ����� � ����������" value="��������� ����� � ����������" type="submit">
		      
		    </form>

		    <br><center><span class="desctext">����� ��������� �������, �� ���������� �� <a href="<?=$row["zdurl"];?>" target="_blank">���� ������</a>.</span></center>

		    <span class="btn_big_red" style="margin-top:10px;width: 695px;text-align: center;" title="���������� �� ����������" onclick="popup_w('�������������!!', false, 450, 'func=otc_z&l=/ajax/ajax_task.php&id=<?=$rid;?>', '/ajax/ajax_task.php');return false;">���������� �� ����������</span>
		    
		  </div>
		  </div>
		  
		  
		  
		  <div id='new_otch' style="display: none;">
            <div style="text-align: left;">
              <b>�� ���������� ����� ������� ���������� �� ����� <font color="#C80000">
			  <?
			  if($row["time"]==1)
				echo '1 ���a';
			elseif($row["time"]==2)
				echo '3 �����';
			elseif($row["time"]==3)
				echo '12 �����';
			elseif($row["time"]==4)
				echo '24 �����';
			elseif($row["time"]==5)
				echo '3 ����';
			elseif($row["time"]==6)
				echo '7 ����';
			elseif($row["time"]==7)
				echo '14 ����';
			elseif($row["time"]==8)
				echo '30 ����';
			else{
				echo '';
			}
			  ?>
			  </font></b>
              <br>����� ��������� �������, ������� �� ������ ������� ����������. ����������� �������������� ���������� � ���������� ������� ����� ����� ������ ����� ��.
			      </div>
			      
			      			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tbody><tr>				
				<td class="gosite">
			      <form name="goform" method="post" action="/view_task.php?rid=<?=$rid;?>&op=gotask" target="_blank">
			        <input name="id" value="<?=$rid;?>" type="hidden">
			        <input class="btn_big_blue" style="margin-top:15px;" title="���������� � ���������� �������" onclick="funcjs['task-start'](<?=$rid;?>);" value="������ ����������!" type="button">
		          </form>
			      <center><span class="desctext">����� ��������� ������� �� ����<br><?=$row["zdurl"];?></span></center>
			    </td>
				
		
			  </tr>
		  
            </tbody></table>
			</div>
		  
		  
		  
		  
		  
		  
		  
		  
		  <?
		  }else{
			?>

			<div id='new_otch'>
            <div style="text-align: left;">
              <b>�� ���������� ����� ������� ���������� �� ����� <font color="#C80000">
			  <?
			  if($row["time"]==1)
				echo '1 ���a';
			elseif($row["time"]==2)
				echo '3 �����';
			elseif($row["time"]==3)
				echo '12 �����';
			elseif($row["time"]==4)
				echo '24 �����';
			elseif($row["time"]==5)
				echo '3 ����';
			elseif($row["time"]==6)
				echo '7 ����';
			elseif($row["time"]==7)
				echo '14 ����';
			elseif($row["time"]==8)
				echo '30 ����';
			else{
				echo '';
			}
			  ?>
			  </font></b>
              <br>����� ��������� �������, ������� �� ������ ������� ����������. ����������� �������������� ���������� � ���������� ������� ����� ����� ������ ����� ��.
			      </div>
			      
			      			
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
			  <tbody><tr>				
				<td class="gosite">
			      <form name="goform" method="post" action="/view_task.php?rid=<?=$rid;?>&op=gotask" target="_blank">
			        <input name="id" value="<?=$rid;?>" type="hidden">
			        <input class="btn_big_blue" style="margin-top:15px;" title="���������� � ���������� �������" onclick="funcjs['task-start'](<?=$rid;?>);" value="������ ����������!" type="button">
		          </form>
			      <center><span class="desctext">����� ��������� ������� �� ����<br><?=$row["zdurl"];?></span></center>
			    </td>
				
		
			  </tr>
		  
            </tbody></table>
			</div>
			
			
			<?
			 $z_otc_pro=mysql_fetch_array($sql_otc_pro);
		  
		   if($row["time"]==1)
				$time_ad=1;
			elseif($row["time"]==2)
				$time_ad=3;
			elseif($row["time"]==3)
				$time_ad=12;
			elseif($row["time"]==4)
				$time_ad=24;
			elseif($row["time"]==5)
				$time_ad=72;
			elseif($row["time"]==6)
				$time_ad=168;
			elseif($row["time"]==7)
				$time_ad=336;
			elseif($row["time"]==8)
				$time_ad=720;
			else{
				$time_ad=1;
			}
			$time_zz=time();
			
			
			echo '<div id=\'ok_otch\' style="display: none;">';
		  echo '<div class="blockwaittask">�������, ��� ����� ������!<br><span class="text12">���������, ���� ��� �������� �������������.<br>��� ����� �� ����� 5 ����</span></div>';
		  echo '</div>';
		  ?>
		  
		  
		  
		  <div id='otch_r' style="display: none;">
		  <div style="text-align: left;">
	        		      <font color="corall">� �� ������ ��������� ��� ������� <b><? echo date("Y-m-d � H:i:s" , $time_zz);?></b></font>
			<?
			$time_endd=time()+($time_ad*60*60);
			?>
		    <br><font color="corall">� ����� � ���������� ���������� ������ �� ������� <b><? echo date("Y-m-d � H:i:s" , $time_endd);?></b></font><br><br>
		    ��������� ����� ����� � ���������� �������. ���� ����� �� ����� �����, �� ���������� ���������� �����, ������ �� ���������� ����� ������������. ���� �� ��������� ��������� �������, ��������
		    � ����� ���� ��, ��� ���������� ������� ��� ������������� � ������� ������ ���������� �����. ���� �� �� ����������� ��������� �������, ������� ������ ����������� �� �����������.
		    <br><br>		    
		    		      <span class="msg-warning">�������������� ��� ��������� ������.<br>�� ������� �������� ������� � ����� �� ������� ����� - ��� ��������.</span>
					    <center>
		      <span class="desctext">
		        		        <font color="corall">��� IP �����: <?=$ip;?></font> (����� ��������� ������������� �������������)
		      </span>
		    </center>
		          
		    <form method="post" action="../ajax/ajax_task.php" class="aj-form_u" data-infa="true,<?=$rid;?>,">
		      <input name="func" type="hidden" value="done_otch">
              <input name="id" type="hidden" value="<?=$rid;?>">
		          		      		        
		        <div style="position: relative;padding-top:20px;">
		          <span class="len-otch">�������� <span id="num-len">3000</span> ��������</span>
		          <textarea rows="5" name="ask_reply" id="coment" style="width: 98%; height: 150px; margin-top: 5px; border: 3px solid #B1C3CA;" onkeyup="desk_limit('#coment', 3000, '#num-len')"></textarea>
		        </div>
		          		      		          
		      <input name="submit" class="btn_big_green" style="margin-top:10px;width: 715px;" title="��������� ����� � ����������" value="��������� ����� � ����������" type="submit">
		      
		    </form>

		    <br><center><span class="desctext">����� ��������� �������, �� ���������� �� <a href="<?=$row["zdurl"];?>" target="_blank">���� ������</a>.</span></center>

		    <span class="btn_big_red" style="margin-top:10px;width: 695px;text-align: center;" title="���������� �� ����������" onclick="popup_w('�������������!!', false, 450, 'func=otc_z&l=/ajax/ajax_task.php&id=<?=$rid;?>', '/ajax/ajax_task.php');return false;">���������� �� ����������</span>
		    
		  </div>
		  </div>
			
			
              <?
			  }
			  ?>

  
  </div>
         
</div>
<br>
<br>

<?
}
}else{
	echo '<fieldset class="errorp">������! ������� �� ������� ��� ��������!&nbsp;&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
	include('footer.php');
	exit();
}
////////////////////////////////////////////////////////
include('footer.php');
exit();
}














if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_b = mysql_query("SELECT * FROM `tb_ban_users` WHERE `name`='$username' ORDER BY `id` DESC");
	if(mysql_num_rows($sql_b)>0) {
		$row_b = mysql_fetch_array($sql_b);
		$prichina = $row_b["why"];
		$kogda = $row_b["date"];
		echo '<span class="msg-error">��� ������� ������������! �� �� ������ ������������� �������!<br><u>���� ����������</u>: '.$row_b["date"].'</span>';
		include('footer.php');
		exit();
	}
}


if($my_rep_task <= -10) {
	echo '<span class="msg-error">�� �� ������ ��������� �������! ���� ��������� �����������&nbsp;&nbsp;'.$my_rep_task.'</span>';
	include('footer.php');
	exit();
}

if(isset($_GET["op"]) && isset($_GET["rid"]) && limpiar($_GET["op"])=="dell") {
	$sql_dell = mysql_query("SELECT `id` FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' AND (`status`='' OR `status`='dorab') ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql_dell)>0) {
		$row_dell = mysql_fetch_row($sql_dell);
		mysql_query("DELETE FROM `tb_ads_task_pay`  WHERE `id`='".$row_dell["0"]."' LIMIT 1") or die(mysql_error());
		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?page='.limpiarez($_GET["page"]).'&rid='.$rid.'");</script>';
	}
}

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$rek_name = $row["username"];
	$country_targ = $row["country_targ"];

	$check_user = mysql_query("SELECT `id` FROM `tb_ads_task_bl` WHERE `rek_name`='$rek_name' AND `user_name`='$username'");
	if(mysql_num_rows($check_user)) {
		echo '<span class="msg-error">�� �� ������ ��������� ������� ����� �������������, ��� ��� ������������� �������� ��� ��������� ��� �������!</span>';
		include('footer.php');
		exit();
	
		include('footer.php');
		exit();
	}elseif($country_targ==2 && strtolower($my_country)!="ua") {
		echo '<fieldset class="errorp">������! �� �������� ���-���������� ������� �� �������� ��� ���������� � ������!&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}elseif($country_targ==1 && strtolower($my_country)!="ru") {
		echo '<fieldset class="errorp">������! �� �������� ���-���������� ������� �� �������� ��� ���������� � ������!&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
		include('footer.php');
		exit();
	}else


	echo '<table class="tables">';
		echo '<thead><tr align="center" height="30px"><th align="center" colspan="2">������������ �������</th></tr></thead>';
		echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>��������:</b></td>';
			echo '<td>&nbsp;'.str_replace("\r\n","<br>", $row["zdname"]).'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>��� �������:</b></td>';
			if($row["zdtype"]==1)
				echo '<td>&nbsp;�����</td>';
			elseif($row["zdtype"]==2)
				echo '<td>&nbsp;����������� ��� ����������</td>';
			elseif($row["zdtype"]==3)
				echo '<td>&nbsp;����������� � �����������</td>';
			elseif($row["zdtype"]==4)
				echo '<td>&nbsp;������� � �����</td>';
			elseif($row["zdtype"]==5)
				echo '<td>&nbsp;������� � �����</td>';
			elseif($row["zdtype"]==6)
				echo '<td>&nbsp;�����������</td>';
			elseif($row["zdtype"]==7)
				echo '<td>&nbsp;�������� ������</td>';
			elseif($row["zdtype"]==8)
				echo '<td>&nbsp;������</td>';
			else{
				echo '<td></td>';
			}
		echo '</tr>';

		$sql_u = mysql_query("SELECT `id`,`username`,`reiting`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `username`='".$row["username"]."'");
		if(mysql_num_rows($sql_u)>0) {
			$row_u = mysql_fetch_array($sql_u);

			$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];

			$info_user = ''.$row_u["username"].' <img src="img/reiting.png" border="0" alt="" align="middle" title="�������" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> ';
			$info_user.= '<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&task_search=4&task_name='.$row_u["id"].'"><img src="img/view.png" border="0" alt="���������� ��� ������� ����� �������������" align="middle" title="���������� ��� ������� ����� �������������" alt="���������� ��� ������� ����� �������������" style="margin:0; padding:0;" /></a>&nbsp;&nbsp;';

			$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];
			$info_user.= '<a href="/wall?uid='.$row_u["id"].'" target="_blank"><img src="img/wall20.png" title="����� ������������ '.$row_u["username"].'" width="20" border="0" align="absmiddle" />';

				if($wall_com>0) {
					$info_user.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
				}elseif($wall_com<0) {
					$info_user.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
				}else{
					$info_user.= '<b style="color:#000000;">0</b></a>';
				}

			$info_user.= '&nbsp;&nbsp;&nbsp;[<a href="javascript: void(0);" onclick="add_bl(\''.$row_u["id"].'\');" style="color:#000; font-weight: bold;" title="��������� ������������ '.$row_u["username"].' � ������ ������ (Black List)">BL</a>]';

		}else{
			$info_user = ''.$row["user_name"].' <span style="color:#FF0000;">������������� ������</span>';
		}

		echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>�������������:</b></td>';
			echo '<td><table width="100%"><tr><td width="250">'.$info_user.'</td>';

			$sql_f = mysql_query("SELECT `id` FROM `tb_ads_task_fav` WHERE `type`='favorite' AND `user_id`='$partnerid' AND `rek_id`='".$row["user_id"]."'");
			if (mysql_num_rows($sql_f) > 0) {
				echo '<td style="border-left: 1px solid #cccccc; padding-left:20px;">[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&amp;favorite=del&amp;rid='.$row["id"].'">������� �� ����������</a>]</td></tr>';
			}else{
				echo '<td style="border-left: 1px solid #cccccc; padding-left:20px;">[<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'&amp;favorite=add&amp;rid='.$row["id"].'">�������� � ���������</a>]</td></tr>';
			}
			echo '</table></td>';

		echo '</tr>';
		echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>�������:</b></td>';
			echo '<td><table width="100%"><tr><td width="250">&nbsp;'.round($row["reiting"], 2).' (������������� '.$row["all_coments"].')&nbsp;&nbsp;[<a href="javascript: void(0);" onclick="add_coment(\''.$rid.'\');">�������������</a>]</td><td style="border-left: 1px solid #cccccc; padding-left:20px;"><span style="color:green;">'.$row["goods"].'</span> - <span style="color:red;">'.$row["bads"].'</span> - <span style="color:black;">'.$row["wait"].'</span></td></tr></table></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>������ �������:</b></td>';
			if($row["zdre"]==3)
				echo '<td>&nbsp;������� ����� ��������� ������ 3 ����</td>';
			elseif($row["zdre"]==6)
				echo '<td>&nbsp;������� ����� ��������� ������ 6 �����</td>';
			elseif($row["zdre"]==12)
				echo '<td>&nbsp;������� ����� ��������� ������ 12 �����</td>';
			elseif($row["zdre"]==24)
				echo '<td>&nbsp;������� ����� ��������� ������ 24 ���� (1 �����)</td>';
			elseif($row["zdre"]==48)
				echo '<td>&nbsp;������� ����� ��������� ������ 48 ����� (2-� �����)</td>';
			elseif($row["zdre"]==72)
				echo '<td>&nbsp;������� ����� ��������� ������ 72 ���� (3-� �����)</td>';
			else{
				echo '<td>&nbsp;���</td>';
			}
		echo '</tr>';
			if($row["zotch"]!=""){
			echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>��� ����� ������ � ������:</b></td>';
		    echo '<td>&nbsp;'.$row["zotch"].'</td>';
		    }
			
		echo '<tr>';
			echo '<td width="140" align="right" height="30px"><b>����� �� ���������� �������:</b></td>';
			if($row["time"]==1)
				echo '<td>&nbsp;1 ���</td>';
			elseif($row["time"]==2)
				echo '<td>&nbsp;3 ����</td>';
			elseif($row["time"]==3)
				echo '<td>&nbsp;12 �����</td>';
			elseif($row["time"]==4)
				echo '<td>&nbsp;24 ����</td>';
			elseif($row["time"]==5)
				echo '<td>&nbsp;3 ���</td>';
			elseif($row["time"]==6)
				echo '<td>&nbsp;7 ����</td>';
			elseif($row["time"]==7)
				echo '<td>&nbsp;14 ����</td>';
			elseif($row["time"]==8)
				echo '<td>&nbsp;30 ����</td>';
			else{
				echo '<td></td>';
			}
		echo '</tr>';		
			
		echo '<tr>';	
			echo '<td width="140" align="right" height="30px"><b>�������� �������:</b></td>';
			if($row["zdcheck"]==1) {echo '<td>&nbsp;�������� � ������ ������ �������������� � ������� 5 ����</td>';}else{echo '<td>&nbsp;�������� ������������ �����</td>';}
		echo '</tr>';
		echo '<thead><tr align="center" height="30px"><th align="center" colspan="2">�������� �������</th></tr></thead>';
		echo '<tr>';
			echo '<td colspan="2">'.$row["zdtext"].'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2"><br>�� ���������� ����� ������� �� ��������: <b>'.$row["zdprice"].' ���.</b></td>';
		echo '</tr>';
		echo '<thead><tr align="center" height="30px"><th align="center" colspan="2">���������� �������</th></tr></thead>';
		echo '<tr>';

			$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `user_name`='$username' AND `ident`='$rid' AND `type`='task' ORDER by `id` DESC LIMIT 1");
			if(mysql_num_rows($sql_p)>0) {
				$row_p = mysql_fetch_array($sql_p);
				if($row_p["status"]=="") {
					echo '<td colspan="2" height="50px">�� ��� ������ ��������� ��� �������. ���� �� �����-�� �������� �� ������� ���� ��������, �� ������� ��������� ������� ��� ���, ������� �� ������:<br><a href="'.$row["zdurl"].'" target="_blank">'.$row["zdurl"].'</td>';
				}elseif($row_p["status"]=="dorab") {
					echo '<td colspan="2" height="50px"><span class="msg-error" style="margin:0px;">������� ���� ���������� �� ��������� � �� ��� ������ ��������� ��� �������.<br>���������� �������������: '.$row_p["why"].'<br>���� �� �����-�� �������� �� ������� ���� ��������, �� ������� ��������� ������� ��� ���.<br><a href="'.$row["zdurl"].'" target="_blank"><input type="submit" class="submit" value="������"></span></td>';
				}else{
					if($row["zdre"]>0) {
						$ost = ($row_p["date_end"] + ($row["zdre"] * 60 * 60) );
						$ost = ( $ost - time() );

						if($ost > 0){
							$d = floor($ost/86400);
							$h = floor( ($ost - ($d * 86400)) / 3600);
							$i = floor( ($ost - ($d * 86400) - ($h * 3600)) / 60 );
							$s = floor($ost - ($d * 86400) - ($h * 3600) - ($i * 60));

							if($d==0)
								$dn="����";
							elseif($d==1)
								$dn="����";
							elseif($d==2)
								$dn="���";
							elseif($d==3)
								$dn="���";
							else{ $dn=""; }

							if($h>=0) {$hn="�����";}else{$hn="";}
							if(($h>1 && $h<5) | $h>20) {$hn="����";}
							if($h==1 | $h==21) {$hn="���";}

							if($i>0) {$mn="������";}else{$mn="";}
							if($i==1 | $i==21 | $i==31 | $i==41 | $i==51) {$mn="������";}
							if( ($i<21 && $i>4) | $i==0 | $i===20 | $i==30 | $i==40 | $i==50 | ($i>24 && $i<30) | ($i>34 && $i<40) | ($i>44 && $i<50) | ($i>54 && $i<60)) {$mn="�����";}

							if($s>0) {$sn="�������";}else{$sn="";}
							if($s==1 | $s==21 | $s==31 | $s==41 | $s==51) {$sn="�������";}
							if( ($s<21 && $s>4) | $s==0 | $s===20 | $s==30 | $s==40 | $s==50 | ($s>24 && $s<30) | ($s>34 && $s<40) | ($s>44 && $s<50) | ($s>54 && $s<60)) {$sn="������";}

							if($s>0) {$date_next = "<b>$s</b> $sn";}
							if($i>0) {$date_next = "<b>$i</b> $mn <b>$s</b> $sn";}
							if($h>0) {$date_next = "<b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
							if($d>0) {$date_next = "<b>$d</b> $dn <b>$h</b> $hn <b>$i</b> $mn <b>$s</b> $sn";}
							if($h==0 && $i==0 && $s==0) {$date_next = "<b>$d</b> $dn";}

							echo '<td colspan="2" height="40px">�� ��� ��������� ��� �������. ��������� ���������� ����� ������� ����� �������� ����� '.$date_next.'</td>';
						}else{
							echo '<td align="center" colspan="2"><form action="'.$_SERVER["PHP_SELF"].'" method="GET" target="_blank"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="gotask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="������ ����������"></form></td>';
						}
					}else{
						echo '<td colspan="2" height="40px">�� ��� ��������� ��� �������.'; if($row_p["status"]=="wait") { echo '������� ������� �������� ��������������, � ������� 5 ����.';} echo '</td>';
					}
				}
			}else{
				echo '<td align="center" colspan="2"><form action="'.$_SERVER["PHP_SELF"].'" method="GET" target="_blank"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="gotask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" value="������ ����������"></form></td>';
			}

		echo '</tr>';
		echo '<thead><tr align="center" height="30px"><th align="center" colspan="2">�������� �������</th></tr></thead>';
		echo '<tr align="center">';
			echo '<td colspan="2" align="center">';
				if( isset($row_p["status"]) && isset($row_p["status"]) && ($row_p["status"]=="dorab" | $row_p["status"]=="") ) {echo '<div align="center" style="text-align:center; width:480px; margin:0 auto;">';}else{echo '<div align="center" style="text-align:center; width:320px; margin:0 auto;">';}
					echo '<form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="ctask"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" style="float: left;" value="������ �����"></form>';
					if( isset($row_p["status"]) && isset($row_p["status"]) && ($row_p["status"]=="dorab" | $row_p["status"]=="") ) echo '<form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="hidden" name="op" value="dell"><input type="hidden" name="rid" value="'.$rid.'"><input type="submit" class="submit" style="float: left;" value="���������� �� ����������"></form>';
					echo '<form action="'.$_SERVER["PHP_SELF"].'" method="GET"><input type="hidden" name="page" value="'.limpiar($_GET["page"]).'"><input type="submit" class="submit" style="float: left;" value="������� � ������ �������"></form>';
				echo '</div>';
			echo '</td>';
		echo '</tr>';
		echo '<thead><tr align="center" height="30px"><th align="center" colspan="2">����������� ������ �������������</th></tr></thead>';
		echo '<tr><td colspan="2">������������ �����: <b>'.$row["all_coments"].'</b>'; if($row["all_coments"]>20) echo ', �������� ��������� 20'; echo '</td></tr>';
		echo '</table>';

		$sql_p = mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `ident`='$rid' AND `type`='task' AND (`coment`!='' OR `ocenka`>0) ORDER by `id` DESC LIMIT 20");
		if(mysql_num_rows($sql_p)>0) {

			function smile($mes) {
				for($i=0; $i<=37; $i++) {
					$mes = str_ireplace("<br><br>", "<br>", $mes);
					$mes = str_ireplace(":smile-".$i.":", "<img src=\"smiles/smile-".$i.".gif\" alt=\"\" align=\"middle\" border=\"0\" style=\"padding:0; margin:0;\">", $mes);
				}
				return $mes;
			}

			echo '<table width="100%" border="0" style="border-collapse: collapse; border: 1px solid #1E90FF;">';
			while($row_p = mysql_fetch_assoc($sql_p)) {
				$sql_u = mysql_query("SELECT `id`,`username`,`reiting`,`avatar`,`wall_com_p`,`wall_com_o` FROM `tb_users` WHERE `username`='".$row_p["user_name"]."'");
				if(mysql_num_rows($sql_u)>0) {
					$row_u = mysql_fetch_array($sql_u);

					$wall_com = $row_u["wall_com_p"] - $row_u["wall_com_o"];
					$info_wall = '<a href="/wall?uid='.$row_u["id"].'" target="_blank"><img src="img/wall20.png" title="����� ������������ '.$row_u["username"].'" width="20" border="0" align="absmiddle" />';
					if($wall_com>0) {
						$info_wall.= '<b style="color:#008000;">+'.$wall_com.'</b></a>';
					}elseif($wall_com<0) {
						$info_wall.= '<b style="color:#FF0000;">'.$wall_com.'</b></a>';
					}else{
						$info_wall.= '<b style="color:#000000;">0</b></a>';
					}


					$info_user = '['.DATE("d.m.Y�. H:i", $row_p["date_com"]).'] <b>'.$row_u["username"].'</b> <img src="img/reiting.png" border="0" alt="" align="middle" title="�������" style="margin:0; padding:0;" /> <span style="color:blue;">'.round($row_u["reiting"], 2).'</span> '.$info_wall.', ������ <b>'.$row_p["ocenka"].'</b>';
					$avatar_t = '<img src="../avatar/'.$row_u["avatar"].'" border="0" alt="" align="middle" />';
				}else{
					$info_user = '['.DATE("d.m.Y�. H:i", $row_p["date_com"]).'] <b>'.$row["user_name"].'</b> <span style="color:#FF0000;">������������ ������</span>, ������ <b>'.$row_p["ocenka"].'</b>';
					$avatar_t = '<img src="/avatar/no.png" border="0" alt="" align="middle" />';
				}

				echo '<tr><td align="center" width="85" style="border: 1px solid #1E90FF;">'.$avatar_t.'</td><td valign="top" style="border: 1px solid #1E90FF; padding-left:10px;">'.$info_user.'<br><br>'.smile($row_p["coment"]).'</td></tr>';

			}
			echo '</table>';
		}

	mysql_query("UPDATE `tb_ads_task` SET `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());

	$sql_stat = mysql_query("SELECT `id` FROM `tb_ads_task_stat` WHERE `type`='view' AND `user_name`='$username' AND `ident`='$rid' AND `date`='".DATE("d.m.Y")."'");
	if(mysql_num_rows($sql_stat)<1) {
		mysql_query("INSERT INTO `tb_ads_task_stat` (`type`,`rek_name`,`user_name`,`ident`,`date`) VALUES('view','$rek_name','$username','$rid','".DATE("d.m.Y")."')") or die(mysql_error());
		mysql_query("UPDATE `tb_ads_task` SET `views`=`views`+'1', `date_act`='".time()."' WHERE `id`='$rid' AND `status`='pay' AND `totals`>'0'") or die(mysql_error());
	}

	include('footer.php');
	exit();
}else{
	echo '<fieldset class="errorp">������! ������ ������� ���, ���� ��� �� �������!&nbsp;&nbsp;&nbsp;<a href="'.$_SERVER["PHP_SELF"].'?page='.limpiar($_GET["page"]).'">&lt;&lt; ��������� �����</a></fieldset>';
	include('footer.php');
	exit();
}

?>