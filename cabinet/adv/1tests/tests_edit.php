<?php
if(!DEFINED("TESTS_EDIT")) {die ("Hacking attempt!");}
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">�������������� ����� � '.$id.'</h5>';

mysql_query("UPDATE `tb_ads_tests` SET `status`='3' WHERE `status`>'0' AND `balance`<`cena_advs`") or die(mysql_error());
mysql_query("UPDATE `tb_ads_tests` SET `status`='1' WHERE `status`='3' AND `balance`>=`cena_advs`") or die(mysql_error());

?>

<script type="text/javascript" src="js/jquery.simpletip-1.3.1.pack.js"></script>
<script type="text/javascript" language="JavaScript">

$(document).ready(function(){
	$("#hint1").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>��������� �����</b> - �������� 55 ��������.<br>��������� ������ ���� �������� � ��������. ���������� ����������. ��������� ��������� �������� �����������.<br>�� ������ �� ���������� �������, �� ������� ��������� ���������� ������ ����: !!!!!! � �.�. ����� ������� ��������� ������� ���� �������.'
	});
	$("#hint2").simpletip({
		fixed: true, position: ["-622", "-45"], focus: false,
		content: '<b>���������� ��� ������������</b> - �������� 1000 ��������.<br>��������, ��� ���������� ����������� ������ ��� ��������� ������������, �������� ����������� ��� ���� ��� ��������� ������. ���������� ����������. ��������� ��������� �������� �����������. �� ������ �� ���������� �������, �� ������� ��������� ���������� ������ ����: !!!!!! � �.�. ����� ������� ��������� ������� ���� �������. �������� ���������!'
	});
	$("#hint3").simpletip({
		fixed: true, position: ["-622", "-30"], focus: false,
		content: '<b>���� ��������������� ��� ������� � ����� ���������� ������ ������</b>.<br>������ ������� ������ ������ ���� ����������. �� �������������� ����� ����� ������������ ��� ��� �������������� �������. ������������ ���������� �������� � ������� - 300, � ������ - 30.'
	});
	$("#hint4").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� ������������</b> - ���������� <b>����</b> �������� �������������� ������������ �������������:<br><b>�������� ���� ������ 24 ����</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � �����.<br><b>�������� ���� ������ 3 ���</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � 3 ���.<br><b>�������� ���� ������ ������</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � ������.<br><b>�������� ���� ������ 2 ������</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � 2 ������.<br><b>�������� ���� ������ �����</b> - ��� ������, ��� ���� ������������ ������ ������ ��� ���� ���� ��� � �����.'
	});
	$("#hint5").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�������� ����</b> - ��� ���� ����� � ������� ����� ������ � <b style="color:red;">������� ������� ������</b> �� �������������� �����.'
	});
	$("#hint6").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���������� IP</b> - �� ������ ���������� ���������� ������ ����� ��� ������ ���������� IP ��� �� ����� �� 2 ����� (255.255.X.X)'
	});
	$("#hint7").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� ���� �����������</b> - �� ������ ���������� ����� ����� �������, ��������, �������� ���������� �� ������ ��������, ������� ������������������ �� ������� 7-�� ���� �����.'
	});
	$("#hint8").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>�� �������� ��������</b> - �� ������ ���������� ����� ����� ������� �� �������� �������� �������������, �������� ����� ����� ������� ������ ������������� �������� ���� �������� ����.'
	});
	$("#hint9").simpletip({
		fixed: true, position: ["-622", "-45"], focus: false,
		content: '<b>������������ �� �������</b> - ���������� ������ ����� �������� ������������� �� ��������� �����, ��� �� �� ������ ��������� ���� ��� ��������� �����'
	});
	$("#hint12").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>��������������</b> - ������ ������������ �� �������� ����������� �����.'
	});
	$("#hint13").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>���� ������ �����</b> - ��������� �� ���� ���������� �����.'
	});
	$("#hinturl").simpletip({
		fixed: true, position: ["-622", "23"], focus: false,
		content: '<b>URL-����� �����</b> - ������ ���������� � http:// ��� https:// � ��������� �� ����� 300 ��������.<br>�� ����������� HTML-���� � Java-�������. �� ������� ������ �������, ��������� - �������� ��������.'
	});
})

function add_quest() {
	if (gebi("block_quest4").style.display == 'none') {
		$("#block_quest4").fadeIn("fast", function(){
			gebi("quest4").value = '';
			gebi("answ41").value = '';
			gebi("answ42").value = '';
			gebi("answ43").value = '';
			if (gebi("block_quest5").style.display == '') gebi("block_add_quest").style.display = 'none';
			gebi("block_quest4").style.display = '';
			PlanChange();
		});
	} else if (gebi("block_quest5").style.display == 'none') {
		$("#block_quest5").fadeIn("fast", function(){
			gebi("quest5").value = '';
			gebi("answ51").value = '';
			gebi("answ52").value = '';
			gebi("answ53").value = '';
			if (gebi("block_quest4").style.display == '') gebi("block_add_quest").style.display = 'none';
			gebi("block_quest5").style.display = '';
			PlanChange();
		});
	}
}

function del_quest() {
	if (gebi("block_quest5").style.display == '') {
		$("#block_quest5").fadeOut("fast", function(){
			gebi("quest5").value = '';
			gebi("answ51").value = '';
			gebi("answ52").value = '';
			gebi("answ53").value = '';
			gebi("block_quest5").style.display = 'none';
			gebi("block_add_quest").style.display = '';
			PlanChange();
		});
	} else if (gebi("block_quest4").style.display == '') {
		$("#block_quest4").fadeOut("fast", function(){
			gebi("quest4").value = '';
			gebi("answ41").value = '';
			gebi("answ42").value = '';
			gebi("answ43").value = '';
			gebi("block_quest4").style.display = 'none';
			gebi("block_add_quest").style.display = '';
			PlanChange();
		});
	}
}

function PlanChange(){
	var revisit = $.trim($("#revisit").val());
	var color = $.trim($("#color").val());
	var unic_ip_user = $.trim($("#unic_ip_user").val());

	var uprice = <?=number_format(($tests_cena_hit/(1 + $tests_nacenka/100)), 4);?>;
	var rprice = <?=$tests_cena_hit;?>;

	if (gebi("block_quest4").style.display == '') {
		uprice += <?=number_format(($tests_cena_quest/(1 + $tests_nacenka/100)), 5);?>;
		rprice += <?=$tests_cena_quest;?>;
	}
	if (gebi("block_quest5").style.display == '') {
		uprice += <?=number_format(($tests_cena_quest/(1 + $tests_nacenka/100)), 5);?>;
		rprice += <?=$tests_cena_quest;?>;
	}

	if (color == 1) rprice += <?=$tests_cena_color;?>;

	if (revisit == 1) {
		rprice += <?=$tests_cena_revisit[1];?>;
	} else if (revisit == 2) {
		rprice += <?=$tests_cena_revisit[2];?>;
	} else if (revisit == 3) {
		rprice += <?=$tests_cena_revisit[3];?>;
	} else if (revisit == 4) {
		rprice += <?=$tests_cena_revisit[4];?>;
	}
	if (unic_ip_user == 1) {
		rprice += <?=$tests_cena_unic_ip[1];?>;
	} else if (unic_ip_user == 2) {
		rprice += <?=$tests_cena_unic_ip[2];?>;
	}

	gebi("price_user").innerHTML = '<span style="color:#228B22;">' + number_format(uprice, 4, ".", " ") + ' ���.</span>';
	gebi("price_one").innerHTML = '<span style="color:#FF0000;">' + number_format(rprice, 4, ".", " ") + ' ���.</span>';
}

function SaveAds(id, op, type) {
	var title = $.trim($("#title").val());
	var description = $.trim($("#description").val());
	var url = $.trim($("#url").val());
	var revisit = $.trim($("#revisit").val());
	var color = $.trim($("#color").val());
	var unic_ip_user = $.trim($("#unic_ip_user").val());
	var date_reg_user = $.trim($("#date_reg_user").val());
	var sex_user = $.trim($("#sex_user").val());
	var country = $('input[id="country[]"]:checked').map(function(){return $(this).val();}).get();

	var quest1 = $.trim($("#quest1").val());
	var answ11 = $.trim($("#answ11").val());
	var answ12 = $.trim($("#answ12").val());
	var answ13 = $.trim($("#answ13").val());

	var quest2 = $.trim($("#quest2").val());
	var answ21 = $.trim($("#answ21").val());
	var answ22 = $.trim($("#answ22").val());
	var answ23 = $.trim($("#answ23").val());

	var quest3 = $.trim($("#quest3").val());
	var answ31 = $.trim($("#answ31").val());
	var answ32 = $.trim($("#answ32").val());
	var answ33 = $.trim($("#answ33").val());

	var quest4 = $.trim($("#quest4").val());
	var answ41 = $.trim($("#answ41").val());
	var answ42 = $.trim($("#answ42").val());
	var answ43 = $.trim($("#answ43").val());

	var quest5 = $.trim($("#quest5").val());
	var answ51 = $.trim($("#answ51").val());
	var answ52 = $.trim($("#answ52").val());
	var answ53 = $.trim($("#answ53").val());

	if (title == "") {
		gebi("info-msg-cab").style.display = "block";
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� ��������� �����!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (description == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� ���������� � ���������� �����!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if ((url == '') | (url == 'http://') | (url == 'https://')) {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� URL-����� �����!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (quest1 == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� ������ ������!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (answ11 == "" | answ12 == "" | answ13 == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� �������� ������ �� ������ ������!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (quest2 == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� ������ ������!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (answ21 == "" | answ22 == "" | answ23 == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� �������� ������ �� ������ ������!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (quest3 == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� ������ ������!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else if (answ31 == "" | answ32 == "" | answ33 == "") {
		$("#info-msg-cab").show();
		$("#info-msg-cab").html('<span class="msg-error">�� �� ������� �������� ������ �� ������ ������!</span>');
		setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 3000); clearTimeout();
		return false;
	} else {
		$.ajax({
			type: "POST", url: "/cabinet/ajax/ajax_adv.php?rnd="+Math.random(), 
			data: {
				'op':op, 
				'id':id, 
				'type':type, 
				'title':title, 'description':description, 'url':url, 
				'quest1':quest1, 'answ11':answ11, 'answ12':answ12, 'answ13':answ13, 
				'quest2':quest2, 'answ21':answ21, 'answ22':answ22, 'answ23':answ23, 
				'quest3':quest3, 'answ31':answ31, 'answ32':answ32, 'answ33':answ33, 
				'quest4':quest4, 'answ41':answ41, 'answ42':answ42, 'answ43':answ43, 
				'quest5':quest5, 'answ51':answ51, 'answ52':answ52, 'answ53':answ53, 
				'revisit':revisit, 'color':color, 'unic_ip_user':unic_ip_user, 'date_reg_user':date_reg_user, 'sex_user':sex_user, 
				'country[]':country
			}, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			error: function() {
				$("#loading").slideToggle();
				$("#info-msg-cab").show();
				$("#info-msg-cab").html('<span class="msg-error">������ ��������� ������! �������� ������������� �����.</span>');
				setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 10000); clearTimeout();
				return false;
			}, 
			success: function(data) {
				$("#loading").slideToggle();
				$("#info-msg-cab").html("");

				if (data.result == "OK") {
					$("#info-msg-cab").show();
					$("#info-msg-cab").html('<span class="msg-ok">��������� �������� ������� ��������!</span>');
					setTimeout(function() {
						$("#info-msg-addmoney").hide();
						document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";
					}, 1000);
					return false;
				} else {
					if(data.message) {
						$("#info-msg-cab").show();
						$("#info-msg-cab").html('<span class="msg-error">' + data.message + '</span>');
						setTimeout(function() {$("#info-msg-cab").fadeOut("slow")}, 5000); clearTimeout();
						return false;
					} else {
						alert("������ ��������� ������!");
						return false;
					}
				}
			}
		});
	}
}

function CtrlEnter(event) {
	if((event.ctrlKey) && ((event.keyCode == 0xA)||(event.keyCode == 0xD))) {
		gebi("Save").click();
	}
}

function descchange(id, elem, count_s) {
	if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
	$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
}

</script>

<?php

$sql = mysql_query("SELECT * FROM `tb_ads_tests` WHERE `id`='$id' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$id = $row["id"];
	$status = $row["status"];
	$questions = unserialize($row["questions"]);
	$answers = unserialize($row["answers"]);
	$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();

	if($status==1) {
		echo '<span class="msg-error">����� ��������������� ���������� ������������� ��������� ��������!</span>';
		include(DOC_ROOT."/footer.php");
		exit();
	}

	echo '<div id="newform" onkeypress="CtrlEnter(event);">';
		echo '<table class="tables" style="border:none; margin:0; padding:0; width:100%;">';
		echo '<thead><tr>';
			echo '<th align="center" class="top">��������</th>';
			echo '<th align="center" class="top" colspan="2">��������</th>';
		echo '</thead></tr>';
		echo '<tr>';
			echo '<td align="left" width="220"><b>��������� �����</b></td>';
			echo '<td align="left"><input type="text" id="title" maxlength="60" value="'.$row["title"].'" class="ok"></td>';
			echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint1" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="3"><b>���������� ��� ������������ &darr;</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="2">';
				echo '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'description\'); return false;">ST</span>';
				echo '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
				echo '<span id="count1" style="display: block; float:right; color:#696969; margin-top:2px; margin-right:3px;">�������� ��������: 1000</span>';
				echo '<br>';
				echo '<div style="display: block; clear:both; padding-top:4px">';
					echo '<textarea id="description" class="ok" style="height:120px; width:99%;" onKeyup="descchange(\'1\', this, \'1000\');" onKeydown="descchange(\'1\', this, \'1000\');" onClick="descchange(\'1\', this, \'1000\');">'.$row["description"].'</textarea>';
				echo '</div>';
			echo '</td>';
			echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint2" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>URL �����</b> (������� http://)</td>';
			echo '<td align="left"><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok"></td>';
			echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hinturl" class="hint-quest"></span></td>';
		echo '</tr>';

		for($i=1; $i<=3; $i++){
			echo '<tr><td align="center" colspan="3" style="background: #DCE7EA; padding:6px 10px; color: #00649E; font:13px Tahoma, Arial, sans-serif; font-weight:bold; text-shadow:1px 1px 1px #FFF;">������ �'.$i.'</td></tr>';
			echo '<tr align="left">';
				echo '<td align="left" width="220"><b>���������� �������</b></td>';
				echo '<td align="left"><input type="text" id="quest'.$i.'" maxlength="300" value="'.$questions[$i].'" class="ok"></td>';
				if($i==1) {
					echo '<td align="center" width="16" rowspan="4" style="background: #EDEDED;"><span id="hint3" class="hint-quest"></span></td>';
				}else{
					echo '<td align="center" width="16" rowspan="4" style="background: #EDEDED;"></td>';
				}
			echo '</tr>';
			for($y=1; $y<=3; $y++){
				echo '<tr>';
					echo '<td align="left">������� ������ '.($y==1 ? '<span style="color: #009125;">(����������)</span>' : '<span style="color: #FF0000;">(������)</span>').'</td>';
					echo '<td align="left"><input type="text" id="answ'.$i.$y.'" maxlength="30" value="'.$answers[$i][$y].'" class="ok" style="color: '.($y==1 ? "#009125;" : "#FF0000;").'"></td>';
				echo '</tr>';
			}
		}
		echo '</table>';

		for($i=4; $i<=5; $i++){
			echo '<table class="tables" id="block_quest'.$i.'" style="'.( (isset($questions[$i]) && $questions[$i]!="") ? "display:;" : "display:none;" ).' margin:0;">';
			echo '<tr><td align="center" colspan="3" style="background: #DCE7EA; padding:6px 10px; color: #00649E; font:13px Tahoma, Arial, sans-serif; font-weight:bold; text-shadow:1px 1px 1px #FFF;">�������������� ������</td></tr>';
			echo '<tr align="left">';
				echo '<td align="left" width="220"><b>���������� �������</b></td>';
				echo '<td align="left"><input type="text" id="quest'.$i.'" maxlength="300" value="'.( (isset($questions[$i]) && $questions[$i]!="") ? $questions[$i] : false ).'" class="ok"></td>';
				echo '<td align="center" width="16" rowspan="4" style="background: #EDEDED;"><img src="/img/error2.gif" onClick="del_quest();" style="float: none; width:14px; cursor:pointer; margin:0; padding:0" title="������� ������"></td>';
			echo '</tr>';
			for($y=1; $y<=3; $y++){
				echo '<tr>';
					echo '<td align="left">������� ������ '.($y==1 ? '<span style="color: #009125;">(����������)</span>' : '<span style="color: #FF0000;">(������)</span>').'</td>';
					echo '<td align="left"><input type="text" id="answ'.$i.$y.'" maxlength="30" value="'.( (isset($answers[$i][$y]) && $answers[$i][$y]!="") ? $answers[$i][$y] : false ).'" class="ok" style="color: '.($y==1 ? "#009125;" : "#FF0000;").'"></td>';
				echo '</tr>';
			}
			echo '</table>';
		}

		echo '<table class="tables" id="block_add_quest" style="'.(count($questions)==5 ? "display:none; " : false).' margin:0;">';
		echo '<tr><td align="center" style="padding: 8px 0;">';
			echo '<span class="sub-click" onClick="add_quest();">�������� ��� ������ '.($tests_cena_quest>0 ? "(+ ".p_floor($tests_cena_quest, 4)." ���.)" : false).'</span>';
		echo '</td></tr>';
		echo '</table>';

		echo '<span id="adv-title1" class="adv-title-'.(($row["revisit"]>0 | $row["color"]>0 | $row["unic_ip_user"]>0 | $row["date_reg_user"]>0 | $row["sex_user"]>0) ? "open" : "close").'" onclick="ShowHideBlock(1);">�������������� ���������</span>';
		echo '<div id="adv-block1" style="'.(($row["revisit"]>0 | $row["color"]>0 | $row["unic_ip_user"]>0 | $row["date_reg_user"]>0 | $row["sex_user"]>0) ? "display:;" : "display:none;").'">';
			echo '<table class="tables">';
			echo '<tr>';
				echo '<td align="left" width="220">���������� ������������</td>';
				echo '<td align="left">';
					echo '<select id="revisit" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" '.($row["revisit"]==0 ? 'selected="selected"' : false).'>�������� ���� ������ 24 ����</option>';
						echo '<option value="1" '.($row["revisit"]==1 ? 'selected="selected"' : false).'>�������� ���� ������ 3 ��� '.($tests_cena_revisit[1]>0 ? "(+ ".p_floor($tests_cena_revisit[1], 4)." ���.)" : false).'</option>';
						echo '<option value="2" '.($row["revisit"]==2 ? 'selected="selected"' : false).'>�������� ���� ������ ������ '.($tests_cena_revisit[2]>0 ? "(+ ".p_floor($tests_cena_revisit[2], 4)." ���.)" : false).'</option>';
						echo '<option value="3" '.($row["revisit"]==3 ? 'selected="selected"' : false).'>�������� ���� ������ 2 ������ '.($tests_cena_revisit[3]>0 ? "(+ ".p_floor($tests_cena_revisit[3], 4)." ���.)" : false).'</option>';
						echo '<option value="4" '.($row["revisit"]==4 ? 'selected="selected"' : false).'>�������� ���� ������ ����� '.($tests_cena_revisit[4]>0 ? "(+ ".p_floor($tests_cena_revisit[4], 4)." ���.)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint4" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left">�������� ����</td>';
				echo '<td align="left">';
					echo '<select id="color" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" '.($row["color"]==0 ? 'selected="selected"' : false).'>���</option>';
						echo '<option value="1" '.($row["color"]==1 ? 'selected="selected"' : false).'>�� '.($tests_cena_color>0 ? "(+ ".p_floor($tests_cena_color, 4)." ���.)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint5" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left">���������� IP</td>';
				echo '<td align="left">';
					echo '<select id="unic_ip_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" '.($row["unic_ip_user"]==0 ? 'selected="selected"' : false).'>���</option>';
						echo '<option value="1" '.($row["unic_ip_user"]==1 ? 'selected="selected"' : false).'>��, 100% ���������� '.($tests_cena_unic_ip[1]>0 ? "(+ ".p_floor($tests_cena_unic_ip[1], 4)." ���.)" : false).'</option>';
						echo '<option value="2" '.($row["unic_ip_user"]==2 ? 'selected="selected"' : false).'>��������� �� ����� �� 2 ����� '.($tests_cena_unic_ip[2]>0 ? "(+ ".p_floor($tests_cena_unic_ip[2], 4)." ���.)" : false).'</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint6" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left">�� ���� �����������</td>';
				echo '<td align="left">';
					echo '<select id="date_reg_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" '.($row["date_reg_user"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
						echo '<option value="1" '.($row["date_reg_user"]==1 ? 'selected="selected"' : false).'>�� 7 ���� � ������� �����������</option>';
						echo '<option value="2" '.($row["date_reg_user"]==2 ? 'selected="selected"' : false).'>�� 7 ���� � ������� �����������</option>';
						echo '<option value="3" '.($row["date_reg_user"]==3 ? 'selected="selected"' : false).'>�� 30 ���� � ������� �����������</option>';
						echo '<option value="4" '.($row["date_reg_user"]==4 ? 'selected="selected"' : false).'>�� 90 ���� � ������� �����������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint7" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td align="left">�� �������� ��������</td>';
				echo '<td align="left">';
					echo '<select id="sex_user" class="ok" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" '.($row["sex_user"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
						echo '<option value="1" '.($row["sex_user"]==1 ? 'selected="selected"' : false).'>������ �������</option>';
						echo '<option value="2" '.($row["sex_user"]==2 ? 'selected="selected"' : false).'>������ �������</option>';
					echo '</select>';
				echo '</td>';
				echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint8" class="hint-quest"></span></td>';
			echo '</tr>';
			echo '</table>';
		echo '</div>';

		echo '<span id="adv-title2" class="adv-title-'.(count($geo_targ)>0 ? "open" : "close").'" onclick="ShowHideBlock(2);">��������� �������������</span>';
		echo '<div id="adv-block2" style="'.(count($geo_targ)>0 ? "display:;" : "display:none;").'">';
			echo '<table class="tables">';
			echo '<tr>';
				echo '<td colspan="2" align="center" style="border-right:none;"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
				echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
				echo '<td align="center" width="16" rowspan="10" style="background: #EDEDED;"><span id="hint9" class="hint-quest"></span></td>';
			echo '</tr>';
			include(DOC_ROOT."/advertise/func_geotarg_edit.php");
			echo '</table>';
		echo '</div>';

		echo '<table class="tables">';
		echo '<tr><td align="center" colspan="3" style="background: #DCE7EA; padding:6px 10px; color: #00649E; font:13px Tahoma, Arial, sans-serif; font-weight:bold; text-shadow:1px 1px 1px #FFF;">����������</td></tr>';
		echo '<tr>';
			echo '<td align="left" width="220" height="23px"><b>��������������</b></td>';
			echo '<td align="left" id="price_user"></td>';
			echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint12" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="23px"><b>���� ������ �����</b></td>';
			echo '<td align="left" id="price_one"></td>';
			echo '<td align="center" width="16" style="background: #EDEDED;"><span id="hint13" class="hint-quest"></span></td>';
		echo '</tr>';
		echo '</table>';

	echo '</div>';

	echo '<br>';
	echo '<div id="GoInfo"></div>';
	echo '<div id="info-msg-cab" style="display:none;"></div>';
	echo '<div align="center"><span id="Save" onClick="SaveAds('.$id.', \'save\', \'tests\');" class="sub-blue160" style="float:none; width:160px;">���������</span></div>';

	?><script language="JavaScript">
		PlanChange();
		ScrollTo();
		descchange(1, description, 1000);
	</script><?php

}else{
	echo '<span class="msg-error">��������� �������� � '.$id.' � ��� �� �������</span>';
}
?>