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
			type: "POST", cache: false, url: "/ajax/ajax_task.decoded.php", dataType: 'json', data: { 'id':id, 'type':type, 'op':op, 'token':token }, 
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
			type: "POST", cache: false, url: "/ajax/ajax_task.decoded.php", dataType: 'json', 
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

<?php
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">��� ������������ �������</h5>';

$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_cena_vip'");
$task_cena_vip = $sql->fetch_object()->price;

$sql_n = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='nacenka_task' AND `howmany`='1'");
$task_nacenka = $sql_n->fetch_object()->price;

$s_key_task = "TUlnli^&*@if%Yl957kj630n(*7p0UFn#*hglkj?t";

$task_category_arr = array(
	"1"  => "�����", 
	"2"  => "����������� ��� ����������", 
	"3"  => "����������� � �����������", 
	"4"  => "������� � �����", 
	"5"  => "������� � �����", 
	"6"  => "�����������", 
	"7"  => "�������� ������", 
	"8"  => "������", 
	"9"  => "���������� ����", 
	"10" => "YouTube", 
	"11" => "������ � ������"
);

$task_repeat_work_arr = array(
	"3" => "������� ����� ��������� ������ 3 ����", 
	"6" => "������� ����� ��������� ������ 6 �����", 
	"12" => "������� ����� ��������� ������ 12 �����", 
	"24" => "������� ����� ��������� ������ 24 ���� (1 �����)", 
	"48" => "������� ����� ��������� ������ 48 ����� (2-� �����)", 
	"72" => "������� ����� ��������� ������ 72 ���� (3-� �����)"
);


$sql = $mysqli->query("SELECT `price` FROM `tb_config` WHERE `item`='task_comis_del' AND `howmany`='1'") or die(my_json_encode($ajax_json, "ERROR", $mysqli->error));
$task_comis_del = number_format($sql->fetch_object()->price, 0, ".", "");

if($task_comis_del > 0) {
	echo '<div style="text-align:center; background:#F0F8FF; border:none; padding:10px; color:#E32636; box-shadow:0 1px 2px rgba(0, 0, 0, 0.4);">';
		echo '��� �������� ������� (�������� �������) ������� ������� '.$task_comis_del.'% �� ������������ �����.<br>����� ����� 1.00 ���. �� ������������.';
	echo '</div>';
}

$sql = $mysqli->query("SELECT * FROM `tb_ads_task` WHERE `username`='$username' ORDER BY `id` ASC");
if($sql->num_rows>0) {
	echo '<div align="center" style="margin:15px auto 5px;">';
		echo '<a class="sd_sub green big" href="'.$_SERVER["PHP_SELF"].'?option=add_task" style="width:120px;">�������� �������</a>';
	echo '</div>';

	echo '<table class="tables adv-cab">';
	echo '<tbody>';

	while ($row = $sql->fetch_assoc()) {
		$token_pause = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."PlayPause".$s_key_task));
		$token_del = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."ConfirmDel".$s_key_task));
		$token_form_up = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."FormUp".$s_key_task));
		$token_form_vip = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."FormVIP".$s_key_task));
		$token_form_bal = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."FormBalance".$s_key_task));
		$token_get_url = strtolower(md5($row["id"].strtolower($username).$_SERVER["HTTP_HOST"]."GetURL".$s_key_task));

		$cena_pay = number_format($row["zdprice"], 3, ".", "");
		$cena_pay = substr($cena_pay, -1)==0 ? number_format($cena_pay, 2, ".", "") : number_format($cena_pay, 3, ".", "");

		$cena_task = number_format($row["zdprice"]*(100+$task_nacenka)/100, 3, ".", "");
		$cena_task = substr($cena_task, -1)==0 ? number_format($cena_task, 2, ".", "") : number_format($cena_task, 3, ".", "");

		$balance_adv = number_format(($row["totals"] * $row["zdprice"]) * (100 + $task_nacenka)/100, 3, ".", "");
		$balance_adv = substr($balance_adv, -1)==0 ? number_format($balance_adv, 2, ".", " ") : number_format($balance_adv, 3, ".", " ");

		if($row["status"]=="pay" && $row["totals"]>0) {
			$res_1 = $mysqli->query("SELECT COUNT(*) FROM `tb_ads_task` WHERE `status`='pay' AND `date_up`>(SELECT `date_up` FROM `tb_ads_task` WHERE `id`='".$row["id"]."')");
			$row_q=$res_1->fetch_array();
			$position=$row_q['0']+1;
		}else{
			unset($position);
		}

		echo '<tr id="adv_dell'.$row["id"].'">';
			echo '<td style="width:30px; text-align:center; border-right:none;">';
				echo '<div id="status-'.$row["id"].'">';
					if($row["status"]=="wait") {
						echo '<span class="adv-play" title="��������� �������" onClick="FuncAdv('.$row["id"].', \'task\', \'PlayPause\', \''.$token_pause.'\');"></span>';
					}elseif($row["status"]=="pay") {
						echo '<span class="adv-pause" title="������������� �������" onClick="FuncAdv('.$row["id"].', \'task\', \'PlayPause\', \''.$token_pause.'\');"></span>';
					}elseif($row["status"]=="pause") {
						echo '<span class="adv-play" title="��������� �������" onClick="FuncAdv('.$row["id"].', \'task\', \'PlayPause\', \''.$token_pause.'\');"></span>';
					}elseif($row["status"]=="block") {
						echo '<span class="adv-block" title="������� �������������, ���������� ������ ���������" onClick="FuncAdv('.$row["id"].', \'task\', \'PlayPause\', \''.$token_pause.'\');"></span>';
					}else{
						echo '<span class="adv-play" title="��������� �������" onClick="FuncAdv('.$row["id"].', \'task\', \'PlayPause\', \''.$token_pause.'\');"></span>';
					}
				echo '</div>';
			echo '</td>';

			echo '<td align="left" style="border-left:none;">';
				echo '<a class="adv" href="'.$_SERVER["PHP_SELF"].'?option=task_stat&rid='.$row["id"].'" title="'.$row["zdurl"].'">'.$row["zdname"].'</span></a><br>';
				echo '<span class="info-text">';
					echo '�&nbsp;'.$row["id"].'&nbsp;&nbsp;���� �������:&nbsp;'.$cena_task.'&nbsp;���.&nbsp;&nbsp;��������������:&nbsp;'.$cena_pay.'&nbsp;���.';
				echo '</span><br>';
				echo '<span class="desc-text">';
					echo '���������: '.(isset($task_category_arr[$row["zdtype"]]) ? $task_category_arr[$row["zdtype"]] : "�� ����������").'&nbsp;&nbsp;';
					echo '�������: '.round($row["reiting"], 2).''.($row["all_coments"]>0 ? ", �������������: ".$row["all_coments"] : false).'';
				echo '</span><br>';

				echo '<span id="cnt_good'.$row["id"].'" class="sd_sub small green" style="float:left;" title="����� ��������" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_get&rid='.$row["id"].'\';">'.$row["goods"].'</span>';
				echo '<span id="cnt_start'.$row["id"].'" class="sd_sub small grey" style="float:left; cursor: help;" title="������ �����������">'.$row["cnt_start"].'</span>';
				echo '<span id="cnt_bad'.$row["id"].'" class="sd_sub small red" style="float:left;" title="����� ���������" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_get&rid='.$row["id"].'\';">'.$row["bads"].'</span>';
				echo '<span id="cnt_totals'.$row["id"].'" class="sd_sub small blue" style="float:left; cursor: help;" title="�������� ����������">'.$row["totals"].'</span>';

				if($row["wait"]>0) {
					echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod" title="��������� ����������!" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">���������:&nbsp;<b>['.$row["wait"].']</b></span>';
				}else{
					echo '<span id="cnt_wait'.$row["id"].'" class="taskstatus-mod-no" title="���������� ���" onClick="location.href=\''.$_SERVER["PHP_SELF"].'?option=task_mod&rid='.$row["id"].'\';">���������� ���</span>';
				}

				echo '<span class="adv-dell" title="������� �������" onClick="FuncAdv('.$row["id"].', \'task\', \'confirm_del\', \''.$token_del.'\', \'�������������\');"></span>';

				echo '<a class="adv-edit" href="'.$_SERVER["PHP_SELF"].'?option=edit_task&rid='.$row["id"].'" title="������������� �������"></a>';

				if(isset($position) && $position>0 && $position<100) {
					echo '<span id="task_up'.$row["id"].'" class="adv-down" title="������� ������� � ����� ������ ������: '.$position.'" onClick="LoadInfo('.$row["id"].', \'task\', \'form_up\', \''.$token_form_up.'\');">'.$position.'</span>';
				}else{
					echo '<span id="task_up'.$row["id"].'" class="adv-up" title="������� ������� � ������" onClick="LoadInfo('.$row["id"].', \'task\', \'form_up\', \''.$token_form_up.'\');">&uarr;</span>';
				}

				echo '<span class="adv-vip" title="���������� � VIP-�����" onClick="LoadInfo(\''.$row["id"].'\', \'task\', \'form_vip\', \''.$token_form_vip.'\');"></span>';
				echo '<span class="adv-url" title="������ �� ������� ��� ������� � ��������" onClick="LoadInfo(\''.$row["id"].'\', \'task\', \'get_url\', \''.$token_get_url.'\');"></span>';

				if(isset($task_repeat_work_arr[$row["zdre"]]) && $row["zdre"]>0) {
					echo '<span class="adv-clock" style="cursor:help;" title="'.$task_repeat_work_arr[$row["zdre"]].'"></span>';
				}
			echo '</td>';

			echo '<td style="width:80px; text-align:center;">';
				echo '<span id="bal_task'.$row["id"].'" class="add-money" title="��������� ������ �������" onClick="LoadInfo('.$row["id"].', \'task\', \'form_balance\', \''.$token_form_bal.'\');">'.(str_ireplace(" ", "", $balance_adv)<=0 ? "���������" : $balance_adv).'</span>';
			echo '</td>';
		echo '</tr>';

		echo '<tr id="task_info'.$row["id"].'" style="display: none">';
			echo '<td align="center" colspan="4" class="ext-text"><div id="mess_info'.$row["id"].'"></div></td>';
		echo '</tr>';
	}
	echo '</tbody>';
	echo '</table>';
}else{
	echo '<span class="msg-w">� ��� ��� ����� ����������� ������������ �������</span>';

	echo '<div align="center" style="margin:5px auto;">';
		echo '<a class="sd_sub green big" href="'.$_SERVER["PHP_SELF"].'?option=add_task" style="width:120px;">�������� �������</a>';
	echo '</div>';
}

?>