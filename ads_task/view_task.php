<?php

require_once ("bbcode/bbcode.lib.php");
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">��� ������������ �������</h5>';		
echo '<a href="'.$_SERVER["PHP_SELF"].'?option=add_task" title="������� ����� �������"><span class="proc-btn"><center><img style="margin-top: -8px;" src="img/add.png" border="0" alt="" align="middle" title="������� ����� �������" />������� ����� �������</center></span></a>';

?>

<link rel="stylesheet" type="text/css" href="/style/task.css?v=1.02" />
<div id="load" style="display: none;"></div>
<script src="/js/js_task.js?v=1.021"></script>
  
  <script type="text/javascript" language="JavaScript">
var LoadBlock = false;
var s_h = false;
var new_id = false;

function LoadInfo(id, type, op, token) {
	if(s_h==(id + op)) {
		s_h = false; $("#task_info"+id).hide(); $("#mess_info"+id).html("");
	} else if(!LoadBlock){
		if(s_h && new_id && id!=new_id) {$("#task_info"+new_id).hide(); $("#mess_info"+new_id).html("");}

		$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_task_up.php", dataType: 'json', data: { 'id':id, 'type':type, 'op':op, 'token':token }, 
			error: function(request, status, errortext) {
				LoadBlock = false; $("#loading").slideToggle(); alert("������ Ajax! \n"+status+"\n"+errortext); 
				console.log(status, errortext, request);
			},
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) { 
				LoadBlock = false; $("#loading").slideToggle();
				var result = (data.result!=undefined) ? data.result : data;
				var message = (data.message!=undefined) ? data.message : data;
				new_id = id; s_h = id + op;

				if(result == "OK") {
					$("#task_info"+id).css("display", "");
					$("#mess_info"+id).html(message);
				} else if(result == "ERROR") {
					$("#task_info"+id).css("display", "");
					$("#mess_info"+id).html('<span class="msg-error" style="margin:0 5px;">'+message+'</span>');
				} else {
					$("#task_info"+id).css("display", "");
					$("#mess_info"+id).html('<span class="msg-w" style="margin:0 5px;">�� ������� ���������� ������!<br>'+message+'</span>');
				}
			}
		});
	}

	return false;
}

function FuncAdv(id, type, op, token, title_win, close_win) {
	if(!LoadBlock){
		$.ajax({
			type: "POST", cache: false, url: "/ajax/ajax_task_up.php", dataType: 'json', 
			data: { 'id':id, 'type':type, 'op':op, 'token':token, 'plan_add':$.trim($("#plan_add"+id).val()), 'int_autoup':$.trim($("#int_autoup"+id).val()), 'cnt_autoup':$.trim($("#cnt_autoup"+id).val()) }, 
			error: function(request, status, errortext) { LoadBlock = false; $("#loading").slideToggle(); alert("������ Ajax! \n"+status+"\n"+errortext); console.log(status, errortext, request); },
			beforeSend: function() { LoadBlock = true; $("#loading").slideToggle(); }, 
			success: function(data) {
				LoadBlock = false; $("#loading").slideToggle();
				var result = data.result!=undefined ? data.result : data;
				var message = data.message!=undefined ? data.message : data;
				var status = data.status!=undefined ? data.status : data;
				title_win = (!title_win | result!="OK") ? "������" : title_win;

				if (result == "OK") { 
					if(status) $("#status-"+id).html(status);
					if($("div").is(".box-modal") && message) {
						$(".box-modal-title").html(title_win);
						$(".box-modal-content").html(StatusMsg(result, message));
					} else if(message) {
						ModalStart(title_win, (title_win=="������" ? StatusMsg('ERROR', message) : message), 550, true, false, false);
					}
				} else { 
					if($("div").is(".box-modal") && message) {
						$(".box-modal-title").html(title_win);
						$(".box-modal-content").html(StatusMsg(result, message));
					} else if(message) {
						ModalStart(title_win, StatusMsg(result, message), 550, true, false, false);
					}
				}
			}
		});
	}

	return false;
}

</script>

<?
//$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_vip'");
//$task_cena_vip = number_format(mysql_result($sql,0,0), 2, ".", "");

	$sql_n = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
	$nacenka_task = mysql_result($sql_n,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='limit_min_task' AND `howmany`='1'");
    $limit_min_task = mysql_result($sql,0,0);
	
$s_key_task = "TUlnli^&*@if%Yl957kj630n(*7p0UFn#*hglkj?t";

$sql = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' ORDER BY `id` ASC");
if(mysql_num_rows($sql)>0) {
	

	while ($row = mysql_fetch_array($sql)) {
	    
	//$sql_num = mysql_fetch_array(mysql_query("select  num from (SELECT @num:=@num+1 AS num,id,date_up,status FROM (SELECT @num:= 0) v,tb_ads_task ORDER BY date_up DESC) zz where zz.id='".$row['id']."' "));
	$us_v = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='wait' or `status`='dorab') &&`ident`='".$row['id']."'"));
	$sql_wait_z=mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='wait' &&`ident`='".$row['id']."'");
	$us_wait_z = mysql_num_rows($sql_wait_z);
		$token_form_up = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."FormUp".$s_key_task));
		
		
	if($row["status"]=="pay" && $row["date_up"]>0) {
			$position = mysql_result(mysql_query( "SELECT COUNT(*) FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='".$row["id"]."')" ),0,0)+1;
		}else{
			unset($position);
		}
?>

<table id="task-block<?=$row["id"];?>" class="adv-serf" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tbody><tr>
      <td class="normal" valign="middle" width="15px">
	 <? if($row['status']=='pay'){?>
        <span id="pause<?=$row["id"];?>" class="serfcontrol-play" title="���������� �������" onclick="js_post(this, '../ajax/ajax_task.php', 'func=z_pause&id=<?=$row["id"];?>');return false;"></span>
	<?}elseif($row['status']=='pause' or $row['status']=='wait'){?>
		<span id="pause<?=$row["id"];?>" class="serfcontrol-pause" title="��������� �������" onclick="js_post(this, '../ajax/ajax_task.php', 'func=z_play&id=<?=$row["id"];?>');return false;"></span>
	 <?}?>
	 </td>
      <td class="normal" valign="top" style="position: relative;">      
       
        <?
		echo '<a href="'.$_SERVER["PHP_SELF"].'?option=task_stat&rid='.$row["id"].'" style="">'.$row["zdname"].'</a><br>';
		$zd_price_n=$row["zdprice"]+($row["zdprice"]/100*$nacenka_task);
        echo'<span class="serfinfotext">
          � '.$row["id"].'&nbsp;&nbsp;
          ����: '.$zd_price_n.' ���.&nbsp;&nbsp;
          ������ �����������: '.$row["zdprice"].' ���.
        </span><br>';
        
        echo '<span class="desctext">���������:&nbsp;';
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

		echo '&nbsp;&nbsp;����� �� ����������:&nbsp;';
		if($row['time']=='1'){
			echo '1 ���';
		}elseif($row['time']=='2'){
			echo '3 ����';
		}elseif($row['time']=='3'){
			echo '12 �����';
		}elseif($row['time']=='4'){
			echo '24 ����';
		}elseif($row['time']=='5'){
			echo '3 ���';
		}elseif($row['time']=='6'){
			echo '7 ����';
		}elseif($row['time']=='7'){
			echo '14 ����';
		}elseif($row['time']=='8'){
			echo '30 ����';
		}
		
		echo'<br><span class="serfinfotext">';
			if($row['zdre']=='0'){
		echo'���� ������������ � ���� ����������</span>';
			}else{
		echo'�����������&nbsp;';
			
		if($row['zdre']=='3'){
			echo '(1 ��� � 3 ����)';
		}elseif($row['zdre']=='6'){
			echo '(1 ��� � 6 �����)';
		}elseif($row['zdre']=='12'){
			echo '(1 ��� � 12 �����)';
		}elseif($row['zdre']=='24'){
			echo '(1 ��� � 24 ����)';
		}elseif($row['zdre']=='48'){
			echo '(1 ��� � 48 �����)';
		}elseif($row['zdre']=='72'){
			echo '(1 ��� � 72 ����)';
		}
				
		echo'</span>';
			}
		echo '&nbsp;&nbsp;</span><br>';
		$user = mysql_fetch_array(mysql_query("SELECT id, username FROM `tb_users` WHERE `username`='".$username."'"));
		
		$us_play = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='' or `status`='wait' or `status`='dorab') &&`ident`='".$row['id']."'"));
		$us_ok = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE (`status`='good' or `status`='good_auto') && `ident`='".$row['id']."'"));
		$us_no = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `status`='bad' && `ident`='".$row['id']."'"));
		$us_izb = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='favorite' && `rek_id`='".$user['id']."'"));
		$us_bl = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_fav` WHERE `type`='BL' && `rek_id`='".$user['id']."'"));
		$us_otz = mysql_num_rows(mysql_query("SELECT * FROM `tb_ads_task_pay` WHERE `coment`!='' && `ident`='".$row['id']."'"));
		$task_pay=$row['totals'];
		$sql_d = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
		$nacenka_task = mysql_result($sql_d,0,0);
		$bal_z=($row['zdprice']+(($row['zdprice']/100)*$nacenka_task))*$row['totals'];
		$bal_mno=($row['zdprice']+(($row['zdprice']/100)*$nacenka_task));
		
		?>
		<a class="workstatus-yes" href="#" title="����� ��������" onclick="popup_w('������������, ������� �������� ���������� �� ��� �����!', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=good', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_ok;?></font></a>
		<a class="workstatus-wait" href="#" title="������ �����������" onclick="popup_w('������ ���������, ���� ������ �����!', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=wait', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_play;?></font></a>
		<a class="workstatus-no" href="#" title="����� ���������" onclick="popup_w('������������, ������� �������� � ������ �� ��� �����!', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=bad', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_no;?></font></a>		
		<a class="workstatus-otziv" href="#" title="������� � �������" onclick="popup_w('������ � �������', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=otziv', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_otz;?></font></a>
		<a class="workstatus-pay" href="#" title="���������� �������������, ������� ����� ��������� ������ �������" onclick="popup_w('���������� �������������, ������� ����� ��������� ������ �������', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=pay', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$task_pay;?></font></a>
		<a class="workstatus-izbr" href="#" title="�������� � ���������" onclick="popup_w('�������� � ���������', false, 650, 'func=stata-task&id=<?=$user["id"];?>&mode=izbr', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_izb;?></font></a>
		<a class="workstatus-bl" href="#" title="�������� � BL" onclick="popup_w('�������� � BL', false, 650, 'func=stata-task&id=<?=$user["id"];?>&mode=bl', '../ajax/ajax_task.php');return false;"><font color="#ffffff"><?=$us_bl;?></font></a>
		<a class="workstatus-stat" href="#" title="C��������� �������" onclick="popup_w('���������� �������', false, 650, 'func=stata-task&id=<?=$row["id"];?>&mode=stat', '../ajax/ajax_task.php');return false;"><font color="#ffffff">&#10026;</font></a>

		<?
		//if($us_wait_z=='0')
		//if($us_wait_z=='0') {
		//if($row["wait"]>0) 
		if($us_wait_z=='0') {
			echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod-no" title="���������� ���" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">���������� ���</span>';
					//echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod" title="��������� ����������!" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">���������:&nbsp;<b>['.$row["wait"].']</b></span>';
				}elseif($us_wait_z>='1') {
					echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod" title="��������� ����������!" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">���������:&nbsp;<b>['.$row["wait"].']</b></span>';
					//echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod-no" title="���������� ���" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">���������� ���</span>';
				}
		
		if($row['status']=='pay'){?>
		<a class="scon-backmoney" title="������� �������� �� ��������� ���� � ������� ���" style="display: none; float:right;" onclick="popup_w('�������������!!', false, 500, 'func=z_vozvrat&l=../ajax/ajax_task.php&sum=<? echo $row['totals']*$row['zdprice'];?>&f=z_vozvrat&id=<?=$row["id"];?>&hash=4afe32e7ed14644e2ed39271d6c05d09', '../ajax/ajax_task.php');return false;"></a>
		
		<a style="display: none;" class="scon-perevod" onclick="show_window('#perevod-<?=$row["id"];?>');return false;" title="��������� �������� �� ������ ��������"></a>
		
        <!--<a class="scon-edit" style="display: none;" title="������������� �������" onclick="popup_w('�������������� �������� � <?=$row["id"];?>', false, 600, 'func=edit-task&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;"></a>-->
		<a class="scon-edit" style="display: none;" href="/ads_task.php?option=edit_task&amp;rid=<?=$row["id"];?>" title="������������� �������"></a>
		
		<? }elseif($row['status']=='pause' or $row['status']=='wait'){?>
		<a class="scon-backmoney" title="������� �������� �� ��������� ���� � ������� ���" style="float:right;" onclick="popup_w('�������������!!', false, 500, 'func=z_vozvrat&l=../ajax/ajax_task.php&sum=<?echo $row['totals']*$row['zdprice'];?>&f=z_vozvrat&id=<?=$row["id"];?>&hash=4afe32e7ed14644e2ed39271d6c05d09', '../ajax/ajax_task.php');return false;"></a>

		 <a class="scon-perevod" onclick="show_window('#perevod-<?=$row["id"];?>');return false;" title="��������� �������� �� ������ ��������"></a>
		
        <!--<a class="scon-edit" title="������������� �������" onclick="popup_w('�������������� �������� � <?=$row["id"];?>', false, 600, 'func=edit-task&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;"></a>-->
		<a class="scon-edit" href="/ads_task.php?option=edit_task&amp;rid=<?=$row["id"];?>" title="������������� �������"></a>
		<?}?>
		
		<?php
		//if(isset($position) && $position>0 && $position<100) {
					echo '<span id="task_up'.$row["id"].'" class="adv-down" title="������� ������� � ����� ������ ������: '.$position.'" onClick="LoadInfo('.$row["id"].', \'task\', \'form_up\', \''.$token_form_up.'\');">'.$position.'</span>';
				//}else{
					//echo '<span id="task_up'.$row["id"].'" class="adv-up" title="������� ������� � ������" onClick="LoadInfo('.$row["id"].', \'task\', \'form_up\', \''.$token_form_up.'\');">&uarr;</span>';
				//}
				?>
		

       <!--<span class="scon-up tipi" onclick="show_window('#taskup-<?=$row["id"];?>');return false;" title="������� ������� � ������"></span>-->
	<!-- <span class="scon-up tipi" id="task_up'.$row["id"].'" onClick="LoadInfo(<?=$row["id"];?>, 'task', 'form_up', '<?=$token_form_up;?>');"></span>-->

        <span class="scon-vip tipi" onclick="show_window('#taskvip-<?=$row["id"];?>');return false;" title="���������� � VIP-�����"></span>
        <span class="scon-color tipi" onclick="show_window('#taskcolor-<?=$row["id"];?>');return false;" title="�������� ������"></span>
        <a class="scon-view" onclick="show_window('#view-<?=$row["id"];?>');return false;" title="��������� ����������"></a>
        <a class="scon-url tipi" onclick="show_window('#tasurl-<?=$row["id"];?>');return false;" title="������ �� �������"></a>
               
      </font></td>
      <td class="budget" style="width:65px;">
     
         <a class="add-budget" onclick="show_window('#moneyadd-<?=$row["id"];?>');return false;" title="��������� ������"></a>
         <span id="money<?=$row["id"];?>" class="desctext">
         <font style="color:#267F00;"><?echo $bal_z;?></font>
         </span>
       
      </td>
    </tr>

	<?php
		echo '<tr id="task_info'.$row["id"].'" style="display: none">';
			echo '<td align="center" colspan="4" class="ext-text"><div id="mess_info'.$row["id"].'"></div></td>';
		echo '</tr>';
	?>

    <tr id="view-<?=$row["id"];?>" style="display: none;">
      <td class="ext-viptask" colspan="3" style="text-align: left; padding: 2px 10px; background: #f9f9f9;">
        <span class="letter-subtitle">�������� �������:</span>
		<font color="#545454"><?=$row['zdtext'];?></font>
		<span class="letter-subtitle">��� ����� ������� ��� ���������� �������:</span>
		<font color="#C80000">
		<? if($row['zdquest']==null){
		echo $row['zotch'];
		}else{
		echo '����������� ������: '.$row['zdquest'];
		}
		?>
		</font>
		<span class="letter-subtitle">������ ��� ��������:</span>
		<font color="#545454"><?=$row['zdurl'];?></font>  
      </td>
	</tr>
	
	<tr id="comp<?=$row["id"];?>" style="display: none;"><td class="ext-comp" colspan="3"></td></tr>
		<script language="JavaScript">
			function makePrice<?=$row["id"];?>()
			{
			var count = document.getElementById("plan_<?=$row["id"];?>").value;
			var price = count * <?=$bal_mno;?>;
			document.getElementById("taskprice_<?=$row["id"];?>").value = price;
			}
		</script>
	<tr id="moneyadd-<?=$row["id"];?>" style="display: none">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;" id="prob<?=$row["id"];?>">
	    ���������� ���������� (���. 3):
		
		<div class="sum"><input type="text" class="summ" name="plan" id="plan_<?=$row["id"];?>" maxlength="6" size="4" value="1" autocomplete="off" oninput="makePrice<?=$row["id"];?>();"></div>
		
		<input name="mess-text" id="mess-text-<?=$row["id"];?>" type="hidden" value="�� ������� ��� ������ ��������� ������ �������� ID: <?=$row["id"];?>?">
		<input name="system" id="inp-system-<?=$row["id"];?>" type="hidden" value="0">
		<input id="money_kl_<?=$row["id"];?>" type="hidden" value="1" ><br>
		C���� ����������: <input id="taskprice_<?=$row["id"];?>" type="number" align='center' value='<?=$bal_mno;?>' style="width: 5em;  border-radius: 3px;" disabled oninput="makeGoods();"> ���.
		<br><br>

		<span class="btn-line"><input type="submit" class="proc-btn" value="�������� � ����. �����" onclick="funcjs['go_balans'](0,<?=$row["id"];?>,'4afe32e7ed14644e2ed39271d6c05d09','../ajax/ajax_task.php');"></span>
		<span class="btn-line"><input type="submit" class="proc-btn" value="�������� � ��������� �����" onclick="funcjs['go_balans'](1,<?=$row["id"];?>,'4afe32e7ed14644e2ed39271d6c05d09','../ajax/ajax_task.php');"></span>	
		<br>
	  </td>
	</tr>
	
	
	<tr id="perevod-<?=$row["id"];?>" style="display: none">
<script>
 funcjs['go_perevod'] = function(id, url) {
	        
	popup_w(
	  '�������������!!', 
	  false, 
	  500, 
	  'func=z_perevod&l='+url+'&id='+id+'&plan='+$('#planz_'+id).val()+'&z_popoln='+$('#z_popoln_'+id).val(), 
	  '../ajax/ajax_task.php'
	);
	        
	return false;
	          	
  }	
 
</script>	

	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;" id="prob<?=$row["id"];?>">
	  	<?
		
		echo '<select name="z_popoln_'.$row["id"].'" id="z_popoln_'.$row["id"].'" class="ok">
				<option value="0">�� �������</option>';
			
			$sql_p = mysql_query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' and id!='".$row['id']."' ORDER BY `id` ASC");
					while ($row_p = mysql_fetch_array($sql_p)) {
							//echo'<option value="'.$row_p['id'].'">ID:'.$row_p['id'].'->'.$row_p['zdname'].'->'.$row_p['zdprice'].' ���./1 ����������</option>';
							$z_cen=$row_p['zdprice']+$row_p['zdprice']/100*$nacenka_task;
							echo'<option value="'.$row_p['id'].'">ID:'.$row_p['id'].'->'.$z_cen.' ���./1 ����������</option>';
					}
			
		echo '</select><br>';
?>

	    ��������� �������� ������� �� ����� ���������� ������� (���. 1):<br>�������� ��������, ������� �������, ����� �������� �� ��������� ����, �� ������� �������� 20%.
		<div class="sum"><input type="text" class="summ" name="planz" id="planz_<?=$row["id"];?>" maxlength="6" size="4" value="1" autocomplete="off" oninput="makePrice<?=$row["id"];?>();"></div>
		<!--<div class="sum"><input type="text" class="summ" name="plan" id="plan_<?=$row["id"];?>" maxlength="6" size="4" value="1"></div>-->
		
		<input name="mess-text" id="mess-text-<?=$row["id"];?>" type="hidden" value="�� ������� ��� ������ ��������� ������ �������� ID: <?=$row["id"];?>?">
		<input id="money_kl_<?=$row["id"];?>" type="hidden" value="1" ><br>
		<!--C���� ����������: <input id="taskprice_<?=$row["id"];?>" type="number" align='center' value='<?=$bal_mno;?>' style="width: 5em;  border-radius: 3px;" disabled oninput="makeGoods();"> ���.
		<br><br>-->
		
		<span class="btn-line"><input type="submit" class="proc-btn" value="���������" onclick="funcjs['go_perevod'](<?=$row["id"];?>,'../ajax/ajax_task.php');"></span>
			
		<br>
	  </td>
	</tr>
	
	
	<tr id="taskup-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    ������� ����� ������� �� ������ ������� � ����� ��������� � � ����� ������<br>��������� �������� ���������� 1<!--?=$ask_taskupam?--> ���.<br>
		<span class="proc-btn" onclick="popup_w('�������������!!', false, 500, 'func=z_up&l=../ajax/ajax_task.php&d=�� ������� ��� ������ ������� ������� ID: <?=$row["id"];?>?&f=verx&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">������� ������� �������</span>
		<!--<hr>-->
		
	  </td>
	</tr>
	
	<tr id="taskvip-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    ������� ����� ��������� � VIP �����, � ����� ����� �������� � ����� ������ ������� ������ ������� VIP<br>��������� ��������� ������� VIP � ����������  � ��������� ����� ���������� 3 ���.<br>
		<span class="proc-btn" onclick="popup_w('�������������!!', false, 500, 'func=z_vip&l=../ajax/ajax_task.php&d=�� ������� ��� ������ �������� ������� VIP � ��� �� ���������� � VIP �����, �������� ID: <?=$row["id"];?>?&f=vip&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">�������� ������� VIP</span>
	  </td>
	</tr>
	
	<tr id="taskperevod-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	   ��������� �������� � ����� ������� �� ������. ��� ����� ������� ����� �������� � �������� ���� �� ������� � ������ �������:<br> 
		<span class="proc-btn" onclick="popup_w('�������������!!', false, 500, 'func=z_vip&l=../ajax/ajax_task.php&d=�� ������� ��� ������ �������� ������� VIP � ��� �� ���������� � VIP �����, �������� ID: <?=$row["id"];?>?&f=vip&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">�������� ������� VIP</span>
	  </td>
	</tr>
	
	<tr id="taskcolor-<?=$row["id"];?>" style="display: none;">
	  <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    ������� ����� �������� ������� ������<br>��������� ��������� ������ ���������� 1 ���. �� 1 ����<br>
	    <span class="proc-btn" onclick="popup_w('�������������!!', false, 500, 'func=z_color&l=../ajax/ajax_task.php&d=�� ������� ��� ������ �������� ������, �������� ID: <?=$row["id"];?>?&f=color&id=<?=$row["id"];?>', '../ajax/ajax_task.php');return false;">�������� ������</span>
	  </td>
	</tr>
	
	<tr id="tasurl-<?=$row["id"];?>" style="display: none;">
      <td class="ext-viptask" colspan="3" style="text-align: center; padding: 2px 10px;">
	    ����� ���������������� ������� � <?=$row["id"];?> � �������� - ���������� ������ ��� ������!<br>
		<div style="border: 1px solid #cccccc; padding:5px; margin-top: 5px; margin-bottom:5px;">
		  <a href="/view_task.php?page=task&rid=<?=$row["id"];?>" target="_blank" title="������ �� ���� �������">https://<?=$_SERVER["SERVER_NAME"];?>/view_task.php?page=task&rid=<?=$row["id"];?></a>
		</div>
      </td>
    </tr>
	
	<tr>
      <td class="taskmsg" colspan="3" style="display: none; ">
	  </td>
    </tr>

	<tr id="otchet-<?=$row["id"];?>" class="otchet-new" style="display: none;">
	  <td class="text-otchet" id="text-otchet-<?=$row["id"];?>" colspan="3">

</td>
	</tr>

  </tbody></table>
<?
}
}else{
echo '<br><span class="msg-warning">� ��� ���� �������!</span>';
}
//}

?>