<?php
if(!DEFINED("MAILS_EDIT")) {die ("Hacking attempt!");}
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">�������������� ���������� ������ � '.$id.'</h5>';

$sql = mysql_query("SELECT * FROM `tb_ads_mails` WHERE `id`='$id' AND `username`='$username'");
if(mysql_num_rows($sql)>0) {
	$row = mysql_fetch_array($sql);
	$geo_targ = (isset($row["geo_targ"]) && trim($row["geo_targ"])!=false) ? explode(", ", $row["geo_targ"]) : array();
	$wm_check = $row["wm_check"];

	?><script type="text/javascript" language="JavaScript">
	function save_ads(id, type) {
		$('#info-msg-cab').hide(); 
		var nolimit = $.trim($('#nolimit').val());
		var title = $.trim($('#title').val());
		var description = $.trim($('#description').val());
		var question = $.trim($('#question').val());
		var answer_t = $.trim($('#answer_t').val());
		var answer_f_1 = $.trim($('#answer_f_1').val());
		var answer_f_2 = $.trim($('#answer_f_2').val());
		var url = $.trim($('#url').val());
		var timer = $.trim($('#timer').val());
		var revisit = $.trim($('#revisit').val());
		var color = $.trim($('#color').val());
		var active = $.trim($('#active').val());
		var gotosite = $.trim($('#gotosite').val());
		var unic_ip = $.trim($('#unic_ip').val());
		var new_users = $.trim($('#new_users').val());
		var no_ref = $.trim($('#no_ref').val());
		var sex_adv = $.trim($('#sex_adv').val());
		var to_ref = $.trim($('#to_ref').val());
		var country = $('input[id="country[]"]:checked').map(function(){return $(this).val();}).get();
		var wm_check = $.trim($("#wm_check").val());

		if ((url == '') | (url == 'http://') | (url == 'https://')) {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� URL-����� �����!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("url");
		} else if (title == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ��������� ������!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("title");
		} else if (description == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ���������� ������!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("description");
		} else if (question == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ����������� ������!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("question");
		} else if (answer_t == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������� ������ (����������)!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("answer_t");
		} else if (answer_f_1 == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������� ������ (������)!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("answer_f_1");
		} else if (answer_f_2 == '') {
			gebi("info-msg-cab").innerHTML = '<span class="msg-error">�� �� ������� ������� ������ (������)!</span>';
			gebi("info-msg-cab").style.display = "";
			setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			focus_bg("answer_f_2");
		} else {
			$.ajax({
				type: "POST", url: "/cabinet/ajax/ajax_adv.php", 
				data: {
					'op':'save', 
					'type':type, 
					'id':id, 
					'nolimit':nolimit, 
					'title':title, 
					'description':description, 
					'question':question, 
					'answer_t':answer_t, 
					'answer_f_1':answer_f_1, 
					'answer_f_2':answer_f_2, 
					'url':url, 
					'timer':timer, 
					'revisit':revisit, 
					'color':color, 
					'active':active, 
					'gotosite':gotosite, 
					'unic_ip':unic_ip, 
					'new_users':new_users, 
					'no_ref':no_ref, 
					'sex_adv':sex_adv, 
					'to_ref':to_ref,
					'country[]':country, 
					'wm_check':wm_check
				}, 
				beforeSend: function() { $('#loading').show(); }, 
				success: function(data) { 
					$('#loading').hide(); 
					if (data == "OK") {
						document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>#goto";
					} else {
						gebi("info-msg-cab").innerHTML = '<span class="msg-error">' + data + '</span>';
						gebi("info-msg-cab").style.display = "";
						setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 5000); clearTimeout();
						return false;
					}
				}
			});
		}
	}

	function PlanChange(){
		var nolimit = $.trim($("#nolimit").val()) ? $.trim($("#nolimit").val()) : 0;
		var timer = $.trim($("#timer").val());
		var plan = $.trim($("#plan").val());
		var color = $.trim($("#color").val());
		var active = $.trim($("#active").val());
		var revisit = $.trim($("#revisit").val());
		var unic_ip = $.trim($("#unic_ip").val());
		var gotosite = $.trim($("#gotosite").val());
		var rprice = <?=$mails_cena_hit;?>;

		if(timer < <?=$mails_timer_ot;?>) timer = <?=$mails_timer_ot;?>;
		if(timer > <?=$mails_timer_do;?>) timer = <?=$mails_timer_do;?>;
		if(timer > <?=$mails_timer_ot;?>) rprice += (timer - <?=$mails_timer_ot;?>) * <?=$mails_cena_timer;?>;

		if (color == 1) rprice += <?=$mails_cena_color;?>;
		if (active == 1) rprice += <?=$mails_cena_active;?>;
		if (revisit == 1) {
			rprice += <?=$mails_cena_revisit[1];?>;
		} else if (revisit == 2) {
			rprice += <?=$mails_cena_revisit[2];?>;
		}
		if (unic_ip == 1) {
			rprice += <?=$mails_cena_unic_ip[1];?>;
		} else if (unic_ip == 2) {
			rprice += <?=$mails_cena_unic_ip[2];?>;
		}
		if (gotosite == 1) rprice += <?=$mails_cena_gotosite;?>;

		if(nolimit>0) {
			$("#bl_plan_t").html("<b>�� ����������</b>");
			$("#bl_plan_i").attr("style", "display:none;");

			$("#bl_timer_t").html("<b><?=$mails_nolimit_timer;?></b> ������");
			$("#bl_timer_i").attr("style", "display:none;");

			$("#bl_color_t").html("<b>��</b>");
			$("#bl_color_i").attr("style", "display:none;");

			$("#bl_active_t").html("<b>���</b>");
			$("#bl_active_i").attr("style", "display:none;");

			$("#bl_gotosite_t").html("<b>��</b>");
			$("#bl_gotosite_i").attr("style", "display:none;");

			$("#bl_revisit_t").html("<b>������������ ���� 1 ��� � �����</b>");
			$("#bl_revisit_i").attr("style", "display:none;");

			$("#bl_unic_ip_t").html("<b>���</b>");
			$("#bl_unic_ip_i").attr("style", "display:none;");

			$("#bl_new_users_t").html("<b>��� ������������ �������</b>");
			$("#bl_new_users_i").attr("style", "display:none;");

			$("#bl_no_ref_t").html("<b>��� ������������ �������</b>");
			$("#bl_no_ref_i").attr("style", "display:none;");

			$("#bl_sex_adv_t").html("<b>��� ������������ �������</b>");
			$("#bl_sex_adv_i").attr("style", "display:none;");

			$("#bl_to_ref_t").html("<b>��� ������������ �������</b>");
			$("#bl_to_ref_i").attr("style", "display:none;");

			$("#price_one_t").html("");
			$("#price_one_s").html("");
			$("#price_one_tr").attr("style", "display:none;");

			var rprice_sum = <?=$mails_nolimit_cena;?>;

			$("#price_t").html("��������� ������");
			$("#price_s").html('<span style="color:#FF0000;">' + number_format(rprice_sum, 2, '.', ' ') + ' ���.</span> (��� ����� ����� ������������� ������)');
		}else{
			$("#bl_plan_t").html("");
			$("#bl_plan_i").attr("style", "");

			$("#bl_timer_t").html("");
			$("#bl_timer_i").attr("style", "");

			$("#bl_color_t").html("");
			$("#bl_color_i").attr("style", "");

			$("#bl_gotosite_t").html("");
			$("#bl_gotosite_i").attr("style", "");

			$("#bl_active_t").html("");
			$("#bl_active_i").attr("style", "");

			$("#bl_revisit_t").html("");
			$("#bl_revisit_i").attr("style", "");

			$("#bl_unic_ip_t").html("");
			$("#bl_unic_ip_i").attr("style", "");

			$("#bl_new_users_t").html("");
			$("#bl_new_users_i").attr("style", "");

			$("#bl_no_ref_t").html("");
			$("#bl_no_ref_i").attr("style", "");

			$("#bl_sex_adv_t").html("");
			$("#bl_sex_adv_i").attr("style", "");

			$("#bl_to_ref_t").html("");
			$("#bl_to_ref_i").attr("style", "");

			var rprice_sum = (rprice * plan);

			$("#price_t").html("��������� ������ ���������");
			$("#price_s").html('<span style="color:#228B22;">' + number_format(rprice, 4, '.', ' ') + ' ���.</span> (��� ����� ����� ������������� ������)');
		}
	}

	function descchange(id, elem, count_s) {
		if (elem.value.length > count_s) { elem.value = elem.value.substr(0,count_s); }
		$("#count"+id).html("�������� ��������: " +(count_s-elem.value.length));
	}

	function addtag(text1, text2) {
		if ((document.selection)){
			gebi('description').focus();
			gebi('description').document.selection.createRange().text = text1+gebi('description').document.selection.createRange().text+text2;
		} else if(gebi('description').selectionStart != undefined) {
			var element = gebi('description');
			var str = element.value;
			var start = element.selectionStart;
			var length = element.selectionEnd - element.selectionStart;
			element.value = str.substr(0, start) + text1 + str.substr(start, length) + text2 + str.substr(start + length);
		} else gebi('description').value += text1+text2;
	}
	</script><?php

	echo '<div id="newform">';
	echo '<table class="tables">';
	echo '<thead><tr>';
		echo '<th class="top" width="250">��������</a>';
		echo '<th class="top">��������</a>';
	echo '</thead></tr>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" width="240">����������� �������</td>';
			echo '<td>';
				if($row["status"]==0 | $row["status"]==3) {
					echo '<select id="nolimit" onChange="PlanChange();" onClick="PlanChange();">';
						echo '<option value="0" '.($row["nolimit"] == 0 ? 'selected="selected"' : false).'>���</option>';
						echo '<option value="1" '.($row["nolimit"] > 0 ? 'selected="selected"' : false).'>��, �� 30 ���� &mdash; '.number_format($cena_mails_nolimit,2,".","`").' ���.</option>';
					echo '</select>';
				}else{
					if($row["nolimit"]!=0) {
						echo '<b>��</b>, �� '.DATE("d.m.Y�. H:i", $row["nolimit"]);
						echo '<input type="hidden" id="nolimit" value="1" />';
					}else{
						echo '<b>���</b>';
						echo '<input type="hidden" id="nolimit" value="0" />';
					}
				}
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>��������� ������</b></td>';
			echo '<td><input type="text" id="title" maxlength="255" value="'.$row["title"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" style="border-bottom:none;"><b>���������� ������ &darr;</b></td>';
			echo '<td align="left" style="border-bottom:none;">';
				echo '<span class="bbc-bold" style="float:left;" title="�������� ������" onClick="javascript:InsertTags(\'[b]\',\'[/b]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-italic" style="float:left;" title="�������� ��������" onClick="javascript:InsertTags(\'[i]\',\'[/i]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-uline" style="float:left;" title="�������� ��������������" onClick="javascript:InsertTags(\'[u]\',\'[/u]\', \'description\'); return false;">�</span>';
				echo '<span class="bbc-tline" style="float:left;" title="������������� �����" onClick="javascript:InsertTags(\'[s]\',\'[/s]\', \'description\'); return false;">ST</span>';
				echo '<span class="bbc-left" style="float:left;" title="��������� �� ������ ����" onClick="javascript:InsertTags(\'[left]\',\'[/left]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-center" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[center]\',\'[/center]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-right" style="float:left;" title="��������� �� ������� ����" onClick="javascript:InsertTags(\'[right]\',\'[/right]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-justify" style="float:left;" title="��������� �� ������" onClick="javascript:InsertTags(\'[justify]\',\'[/justify]\', \'description\'); return false;"></span>';
				echo '<span class="bbc-url" style="float:left;" title="�������� URL" onClick="javascript:InsertTags(\'[url]\',\'[/url]\', \'description\'); return false;">URL</span>';
				echo '<span id="count1" style="display: block; float:right; font-size:11px; color:#696969; margin-top:3px; margin-right:3px;">�������� ��������: 5000</span>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" colspan="2" style="border-top:none;">';
				echo '<textarea id="description" name="description" class="ok" style="height:120px; width:98.5%;" onKeyup="descchange(\'1\', this, \'5000\');" onKeydown="descchange(\'1\', this, \'5000\'); this.style.background=\'#FFFFFF\';" onClick="descchange(\'1\', this, \'5000\');">'.$row["description"].'</textarea>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>����������� ������ � ������</td>';
			echo '<td><input type="text" id="question" maxlength="255" value="'.$row["question"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>������� ������ <span style="color: #009125;">(����������)</span></td>';
			echo '<td><input type="text" id="answer_t" maxlength="255" value="'.$row["answer_t"].'" class="ok" style="color: #009125;" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td><input type="text" id="answer_f_1" maxlength="255" value="'.$row["answer_f_1"].'" class="ok" style="color: #FF0000;" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td>������� ������ <span style="color: #FF0000;">(������)</span></td>';
			echo '<td><input type="text" id="answer_f_2" maxlength="255" value="'.$row["answer_f_2"].'" class="ok" style="color: #FF0000;" onkeydown="this.style.background=\'#FFFFFF\';" /></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td><b>URL �����</b></td>';
			echo '<td><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="31"><b>����� �������</b></td>';
			echo '<td>';
				echo '<select id="wm_check" name="wm_check">';
					echo '<option value="0" '.($wm_check==0 ? 'selected="selected"' : false).'>���� ������������� �������</option>';
					echo '<option value="1" '.($wm_check==1 ? 'selected="selected"' : false).'>������ ������������� � ������������� WMID</option>';
				echo '</select>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">����� ���������</td>';
			echo '<td>';
				echo '<div id="bl_timer_i">';
					if($row["status"]==0 | $row["status"]==3) {
						echo '<input type="text" id="timer" maxlength="3" value="20" class="ok12" style="text-align:right;" onChange="PlanChange();" onKeyUp="PlanChange();" onkeydown="this.style.background=\'#FFFFFF\';">&nbsp;&nbsp;&nbsp;(�� '.$mails_timer_ot.' �� '.$mails_timer_do.' ���.)';
					}else{
						echo '<b>'.$row["timer"].' ������</b>';
						echo '<input type="hidden" id="timer" value="'.$row["timer"].'" />';
					}
				echo '</div>';
				echo '<div id="bl_timer_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';

	echo '<span id="adv-title1" class="adv-title-'.(($row["revisit"]>0 | $row["color"]>0 | $row["active"]>0 | $row["gotosite"]>0 | $row["unic_ip"]>0 | $row["new_users"]>0 | $row["no_ref"]>0 | $row["sex_adv"]>0 | $row["to_ref"]>0) ? "open" : "close").'" onclick="ShowHideBlock(1);">�������������� ���������</span>';
	echo '<div id="adv-block1" style="'.(($row["revisit"]>0 | $row["color"]>0 | $row["active"]>0 | $row["gotosite"]>0 | $row["unic_ip"]>0 | $row["new_users"]>0 | $row["no_ref"]>0 | $row["sex_adv"]>0 | $row["to_ref"]>0) ? "display:;" : "display:none;").'">';
	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" width="240" height="25">���������� �������� ������</td>';
			echo '<td align="left">';
				echo '<div id="bl_revisit_i"><select id="revisit" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["revisit"]==0 ? 'selected="selected"' : false).'>������������ ���� ������ 24 ����</option>';
					echo '<option value="1" '.($row["revisit"]==1 ? 'selected="selected"' : false).'>������������ ���� ������ 48 ����� '.($mails_cena_revisit[1]>0 ? "(+ ".p_floor($mails_cena_revisit[1], 4)." ���.)" : false).'</option>';
					echo '<option value="2" '.($row["revisit"]==2 ? 'selected="selected"' : false).'>������������ ���� 1 ��� � ����� '.($mails_cena_revisit[2]>0 ? "(+ ".p_floor($mails_cena_revisit[2], 4)." ���.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_revisit_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�������� ������</td>';
			echo '<td align="left">';
				echo '<div id="bl_color_i"><select id="color" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["color"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["color"]==1 ? 'selected="selected"' : false).'>�� '.($mails_cena_color>0 ? "(+ ".p_floor($mails_cena_color, 4)." ���.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_color_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�������� ����</td>';
			echo '<td align="left">';
				echo '<div id="bl_active_i"><select id="active" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["active"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["active"]==1 ? 'selected="selected"' : false).'>�� '.($mails_cena_active>0 ? "(+ ".p_floor($mails_cena_active, 4)." ���.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_active_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">����������� ������� �� ����</td>';
			echo '<td align="left">';
				echo '<div id="bl_gotosite_i"><select id="gotosite" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["gotosite"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["gotosite"]==1 ? 'selected="selected"' : false).'>�� '.($mails_cena_gotosite>0 ? "(+ ".p_floor($mails_cena_gotosite, 4)." ���.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_gotosite_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� IP</td>';
			echo '<td align="left">';
				echo '<div id="bl_unic_ip_i"><select id="unic_ip" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["unic_ip"]==0 ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.($row["unic_ip"]==1 ? 'selected="selected"' : false).'>��, 100% ���������� '.($mails_cena_unic_ip[1]>0 ? "&mdash; (+ ".p_floor($mails_cena_unic_ip[1], 4)." ���.)" : false).'</option>';
					echo '<option value="2" '.($row["unic_ip"]==2 ? 'selected="selected"' : false).'>��������� �� ����� �� 2 ����� (255.255.X.X)  '.($mails_cena_unic_ip[2]>0 ? "&mdash; (+ ".p_floor($mails_cena_unic_ip[2], 4)." ���.)" : false).'</option>';
				echo '</select></div>';
				echo '<div id="bl_unic_ip_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� ������ ��������</td>';
			echo '<td align="left">';
				echo '<div id="bl_new_users_i"><select id="new_users" class="ok" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["new_users"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["new_users"]==1 ? 'selected="selected"' : false).'>��, �� 7 ���� � ������� �����������</option>';
				echo '</select></div>';
				echo '<div id="bl_new_users_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�� ������� ��������</td>';
			echo '<td align="left">';
				echo '<div id="bl_no_ref_i"><select id="no_ref" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["no_ref"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["no_ref"]==1 ? 'selected="selected"' : false).'>������������� ��� �������� �� '.$_SERVER["HTTP_HOST"].'</option>';
				echo '</select></div>';
				echo '<div id="bl_no_ref_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">�� �������� ��������</td>';
			echo '<td align="left">';
				echo '<div id="bl_sex_adv_i"><select id="sex_adv" class="ok" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["sex_adv"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["sex_adv"]==1 ? 'selected="selected"' : false).'>������ �������</option>';
					echo '<option value="2" '.($row["sex_adv"]==2 ? 'selected="selected"' : false).'>������ �������</option>';
				echo '</select></div>';
				echo '<div id="bl_sex_adv_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" height="25">���������� ������ ���������</td>';
			echo '<td align="left">';
				echo '<div id="bl_to_ref_i"><select id="to_ref" class="ok" onChange="PlanChange();" onClick="PlanChange();" '.(($row["status"]==1 | $row["status"]==2) ? 'disabled="disabled" style="color:#CCC"' : false).'>';
					echo '<option value="0" '.($row["to_ref"]==0 ? 'selected="selected"' : false).'>��� ������������ �������</option>';
					echo '<option value="1" '.($row["to_ref"]==1 ? 'selected="selected"' : false).'>��������� 1-�� ������</option>';
					echo '<option value="2" '.($row["to_ref"]==2 ? 'selected="selected"' : false).'>��������� ���� �������</option>';
				echo '</select></div>';
				echo '<div id="bl_to_ref_t" style="text-align:left;"></div>';
			echo '</td>';
		echo '</tr>';
		echo '</tbody>';
		echo '</table>';
	echo '</div>';

	echo '<span id="adv-title2" class="adv-title-'.(count($geo_targ)>0 ? "open" : "close").'" onclick="ShowHideBlock(2);">��������� �������������</span>';
	echo '<div id="adv-block2" style="'.(count($geo_targ)>0 ? "display:;" : "display:none;").'">';
	echo '<table class="tables" style="margin:0; padding:0;">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td colspan="2" align="center" style="border-right:none;"><a onclick="SetChecked(\'paste\');" style="width:100%; color:#008B00; font-weight:bold; cursor:pointer;"><center>�������� ���</center></a></td>';
		echo '<td colspan="2" align="center" style="border-left:none;"><a onclick="SetChecked();" style="width:100%; color:#FF0000; font-weight:bold; cursor:pointer;"><center>����� ���</center></a></td>';
	echo '</tr>';
	include(DOC_ROOT."/advertise/func_geotarg_edit.php");
	echo '</tbody>';
	echo '</table>';
	echo '</div>';

	echo '<span id="adv-title3" class="adv-title-open" onclick="ShowHideBlock(3);">����������</span>';
	echo '<div id="adv-block3" style="display:block;">';
	echo '<table class="tables">';
	echo '<tbody>';
		echo '<tr>';
			if($row["status"]==0 | $row["status"]==3) {
				echo '<td id="price_t" align="left" width="240" height="25"></td>';
				echo '<td id="price_s"></td>';
			}else{
				if($row["nolimit"]!=0) {
					echo '<td align="left" width="240" height="25">��������� ������</td>';
					echo '<td><b style="color:#228B22;">'.number_format($row["money"], 2, ".", " ").' ���.</b></td>';
				}else{
					echo '<td id="price_t" align="left" width="240" height="25"></td>';
					echo '<td id="price_s"></td>';
				}
			}
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</div><br>';

	echo '<div id="info-msg-cab"></div>';

	echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'mails\');" class="proc-btn" style="float:none; width:160px;">���������</span></div>';

	?><script language="JavaScript">PlanChange(); descchange(1, description, 5000);</script><?php
}else{
	echo '<span class="msg-error">��������� �������� � '.$id.' � ��� �� �������</span>';
}
?>