<?php
if (!DEFINED("ADVERTISE")) {die ("Hacking attempt!");}
?>

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

<?php
if(!isset($testdrive_status)) {
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_youtube_status' AND `howmany`='1'");
	$testdrive_status = mysql_result($sql,0,0);
}

if($testdrive_status==0) {
	echo '<span class="msg-error">������� Test Drive �������� �� ��������!</span>';
	include('footer.php');
	exit();
}

if(!isset($testdrive_count)) {
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_youtube_count' AND `howmany`='1'");
	$testdrive_count = mysql_result($sql,0,0);
}

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='testdrive_youtube_timer' AND `howmany`='1'");
$testdrive_timer = mysql_result($sql,0,0);

$plan = $testdrive_count;
$timer = $testdrive_timer;
$type_serf = -1;

function limpiarez($mensaje){
	$mensaje = htmlspecialchars(trim($mensaje));
	$mensaje = str_replace("?","&#063;",$mensaje);
	$mensaje = str_replace(">","&#062;",$mensaje);
	$mensaje = str_replace("<","&#060;",$mensaje);
	$mensaje = str_replace("'","&#039;",$mensaje);
	$mensaje = str_replace("`","&#096;",$mensaje);
	$mensaje = str_replace("$","&#036;",$mensaje);
	$mensaje = str_replace('"',"&#034;",$mensaje);
	$mensaje = str_replace("  "," ",$mensaje);
	$mensaje = str_replace("&amp amp ","&",$mensaje);
	$mensaje = str_replace("&amp;amp;","&",$mensaje);
	$mensaje = str_replace("&&","&",$mensaje);
	$mensaje = str_replace("http://http://","http://",$mensaje);
	$mensaje = str_replace("https://https://","https://",$mensaje);
	$mensaje = str_replace("&#063;","?",$mensaje);
	return $mensaje;
}

$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;

if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	$sql_user = mysql_query("SELECT `wmid`,`wm_purse`,`money_rb`,`test_drive_youtube`,`reiting` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_row($sql_user);
		$wmid_user = $row_user["0"];
		$wmr_user = $row_user["1"];
		$money_user = $row_user["2"];
		$test_drive_youtube = $row_user["3"];

		if($test_drive_youtube==1) {
			echo '<span class="msg-error">�� ��� ������������ ������� TEST DRIVE!</span>';
			include('footer.php');
			exit();
		}elseif($reiting<300) {       
                echo '<span class="msg-error">��� ������������ ������� TEST DRIVE � ��� ������ ���� ������ <b>�������</b> ��� ����!</span>';
                        include('footer.php');
                        exit();
                }

		$sql_b = mysql_query("SELECT * FROM `tb_black_users` WHERE `name`='$username' ORDER BY `id` DESC");
		if(mysql_num_rows($sql_b)>0) {
			$row_b = mysql_fetch_assoc($sql_b);
			$prichina = $row_b["why"];
			$kogda = $row_b["date"];

			echo '<span class="msg-error">��� ������� ������������! �� �� ������ ������������ ������� TEST DRIVE!<br><u>������� ����������</u>: '.$row_b["why"].'<br><u>���� ����������</u>: '.$row_b["date"].'</span>';
			include('footer.php');
			exit();
		}

	}else{
		echo '<span class="msg-error">��� ������������� ������� TEST DRIVE ���������� ������������������!</span>';
		include('footer.php');
		exit();
	}

}else{
	echo '<span class="msg-error">��� ������������� ������� TEST DRIVE ���������� ������������������!</span>';
	include('footer.php');
	exit();
}

if(count($_POST)>0 && isset($_POST["id_pay"])) {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$id_pay = (isset($_POST["id_pay"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pay"]))) ? intval(limpiar(trim($_POST["id_pay"]))) : false;

		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_youtube` WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_id)>0) {

			mysql_query("UPDATE `tb_users` SET `test_drive_youtube`='1' WHERE `username`='$username'") or die(mysql_error());
			mysql_query("UPDATE `tb_ads_youtube` SET `status`='1', `date`='".time()."', `wmid`='$wmid_user' WHERE `id`='$id_pay' AND `status`='0' AND `username`='$username'  ORDER BY `id` DESC LIMIT 1") or die(mysql_error());

			ads_date();

			echo '<span class="msg-ok">������� ������� ���������!<br>�������, ��� ����������� �������� ������ �������!</span>';
			include('footer.php');
			exit();
		}else{
			echo '<span class="msg-error">������� �� �������!</span>';
			include('footer.php');
			exit();
		}
	}else{
		echo '<span class="msg-error">��� ������������� ������� TEST DRIVE ���������� ������������������!</span>';
		include('footer.php');
		exit();
	}
}



if(count($_POST)>0) {
	$title = (isset($_POST["title"])) ? limitatexto(limpiarez($_POST["title"]),60) : false;
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]),80) : false;
	$url = (isset($_POST["url"])) ? limpiarez($_POST["url"]) : false;
	$content = (isset($_POST["content"]) && (intval($_POST["content"])==0 | intval($_POST["content"])==1)) ? intval($_POST["content"]) : "0";
	$color = 0;
	$active = 0;
	$country = false;
	$revisit = 0;
	$nolimitdate = 0;
	$limit_d = $plan;
	$limit_h = $plan;
	$method_pay = "-2";
	$laip = getRealIP();
	$black_url = @getHost($url);
	$p = explode("youtu.be/", $_POST["url"]);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");
	if(mysql_num_rows($sql_bl)>0 && $black_url!=false) {
		$row = mysql_fetch_array($sql_bl);
		echo '<span class="msg-error">���� <a href="http://'.$row["domen"].'/" target="_blank" style="color:#0000FF">'.$row["domen"].'</a> ������������ � ������� � ������ ������ ������� '.strtoupper($_SERVER["HTTP_HOST"]).' !<br>�������: '.$row["cause"].'</span>';
	}elseif($url==false | $url=="http://" | $url=="https://") {
		echo '<span class="msg-error">�� ������� ������ �� ����!</span>';
	}elseif((substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		echo '<span class="msg-error">�� ����� ������� ������ �� ����!</span>';
	}elseif(is_url($url)!="true") {
		echo is_url($url);
	}elseif($title==false) {
		echo '<span class="msg-error">�� �� ������� ��������� ������.</span><br>';
	/*}elseif($description==false) {
		echo '<span class="msg-error">�� �� ������� ������� �������� ������.</span><br>';*/
	}elseif(count($p)<='1') {
		echo '<span class="msg-error">�� ����� ������� ������ youtub!</span>';
	}elseif(@getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		echo '<span class="msg-error">'.SFB_YANDEX($url).'</span>';
	}else{
		$color_to[0]="���";
		$color_to[1]="��";

		$content_to[0]="���";
		$content_to[1]="��";

		$active_to[0]="���";
		$active_to[1]="��";

		$revisit_to[0] = "������ 24 ����";
		$revisit_to[1] = "������ 48 �����";
		$revisit_to[2] = "1 ���";

		$timer_to = "$timer";
		
		$img_youtube='https://img.youtube.com/vi/'.$p[1].'/1.jpg';
	    $ytflag=1;

		$sql_tranid = mysql_query("SELECT `merch_tran_id` FROM `tb_statistics` WHERE `id`='1'");
		$merch_tran_id = mysql_result($sql_tranid,0,0);
		mysql_query("UPDATE `tb_statistics` SET `merch_tran_id`=`merch_tran_id`+'1' WHERE `id`='1'") or die(mysql_error());
		mysql_query("DELETE FROM `tb_ads_youtube` WHERE `status`='0' AND `date`<'".(time()-(24*60*60))."'") or die(mysql_error());

		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_youtube` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_youtube` SET `merch_tran_id`='$merch_tran_id',`method_pay`='$method_pay',`type_serf`='$type_serf',`date`='".time()."',`wmid`='$wmid_user',`username`='$username',`geo_targ`='$country',`content`='$content',`active`='$active',`revisit`='$revisit',`color`='$color',`timer`='$timer',`nolimit`='0',`limit_d`='$limit_d',`limit_h`='$limit_h',`limit_d_now`='$limit_d',`limit_h_now`='$limit_h',`url`='$url',`title`='$title',`description`='$description',`plan`='$plan',`totals`='$plan',`ip`='$laip',`money`='0' WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1") or die(mysql_error());
		}else{
		    mysql_query("INSERT INTO `tb_ads_youtube` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`type_serf`,`date`,`wmid`,`username`,`geo_targ`,`content`,`active`,`revisit`,`color`,`timer`,`nolimit`,`limit_d`,`limit_h`,`limit_d_now`,`limit_h_now`,`url`,`title`,`description`,`plan`,`totals`,`ip`,`money`, `ytflag`, `img_youtube`) 
            VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$type_serf','".time()."','$wmid_user','$username','$country','$content','$active','$revisit','$color','$timer','$nolimitdate','$limit_d','$limit_h','$limit_d','$limit_h','$url','$title','$description','$plan','$plan','$laip','0','$ytflag', '$img_youtube')") or die(mysql_error());
			//mysql_query("INSERT INTO `tb_ads_youtube` (`status`,`session_ident`,`merch_tran_id`,`method_pay`,`type_serf`,`date`,`wmid`,`username`,`geo_targ`,`content`,`active`,`revisit`,`color`,`timer`,`nolimit`,`limit_d`,`limit_h`,`limit_d_now`,`limit_h_now`,`url`,`title`,`description`,`plan`,`totals`,`ip`,`money, `ytflag`, `img_youtube`) 
			//VALUES('0','".session_id()."','$merch_tran_id','$method_pay','$type_serf','".time()."','$wmid_user','$username','$country','$content','$active','$revisit','$color','$timer','$nolimitdate','$limit_d','$limit_h','$limit_d','$limit_h','$url','$title','$description','$plan','$plan','$laip','$ytflag','$img_youtube')") or die(mysql_error());
		}
		$sql_id = mysql_query("SELECT `id` FROM `tb_ads_youtube` WHERE `status`='0' AND `session_ident`='".session_id()."' ORDER BY `id` DESC LIMIT 1");
		$id_zakaz = mysql_result($sql_id,0,0);
	
		echo '<br><span class="msg-ok" style="margin-bottom:0px;">��������� ������!</span>';
		echo '<table class="tables">';
			echo '<tr><td width="200"><b>���� �:</b></td><td>'.$merch_tran_id.'</td></tr>';
			echo '<tr><td><b>��������� �����������:</b></td><td><a href="'.$url.'" target="_blank">'.$title.'</a></td></tr>';
			//echo '<tr><td><b>������� �������� ������:</b></td><td><a href="'.$url.'" target="_blank">'.$description.'</a></td></tr>';
			echo '<tr><td><b>URL �����:</b></td><td><a href="'.$url.'" target="_blank">'.$url.'</a></td></tr>';
			echo '<tr><td><b>���������� �������:</b></td><td>'.$plan.'</td></tr>';
			echo '<tr><td><b>������, ���.:</b></td><td>'.$timer_to.'</td></tr>';
			echo '<tr><td><b>��������� ������:</b></td><td>'.$color_to[$color].'</td></tr>';
			echo '<tr><td><b>�������� ����:</b></td><td>'.$active_to[$active].'</td></tr>';
			echo '<tr><td><b>�������� ��� ���������:</b></td><td>'.$revisit_to[$revisit].'</td></tr>';
			echo '<tr><td><b>������� "18+":</b></td><td>'.$content_to[$content].'</td></tr>';
			echo '<tr><td><b>������������:</b></td><td>��� ������</td></tr>';
		echo '</table>';

		echo '<table class="tables"><tr align="center"><td>';
			echo '<form action="" method="post">';
				echo '<input type="hidden" name="id_pay" value="'.$id_zakaz.'">';
				echo '<input type="hidden" name="method_pay" value="'.$method_pay.'">';
				echo '<input type="submit" value="���������� ������" class="sub-blue160" style="float:none;">';
			echo '</form>';
		echo '</td></tr></table>';
	}
}else{
	?>

	<script type="text/javascript" language="JavaScript"> 

	function gebi(id){
		return document.getElementById(id)
	}

	function SbmFormB() {
		arrayElem = document.forms["formzakaz"];
		var col=0;

		for (var i=0;i<arrayElem.length;i++){
			if ((document.forms["formzakaz"].url.value == '')|(document.forms["formzakaz"].url.value == 'http://')|(document.forms["formzakaz"].url.value == 'https://')) {
				alert('�� �� ������� URL-����� �����');
				arrayElem[i+0].style.background = "#FFDBDB";
				arrayElem[i+0].focus();
				return false;
			}else{
				arrayElem[i+0].style.background = "#FFFFFF";
			}
			if ((document.forms["formzakaz"].title.value == '')) {
				alert('�� �� ������� ��������� ������');
				arrayElem[i+1].style.background = "#FFDBDB";
				arrayElem[i+1].focus();
				return false;
			}else{
				arrayElem[i+1].style.background = "#FFFFFF";
			}
			/*if ((document.forms["formzakaz"].description.value == '')) {
				alert('�� �� ������� ������� �������� ������');
				arrayElem[i+2].style.background = "#FFDBDB";
				arrayElem[i+2].focus();
				return false;*/
			}else{
				arrayElem[i+2].style.background = "#FFFFFF";
			}
		}

		document.forms["formzakaz"].submit();
		return true;
	}

	</script>

	<?php

	echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
		echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">YouTube ������� ����-���� - ��� ���?</span>';
		echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
		echo '��� <span style="color:#FF0000;">Test-Drive YouTube</span> - '.$plan.' ���������� ������ ����������� ���������<br>';
		echo '���� ���������� ����� �������� �� �������� ��������� ������������ �������������, � ����� ����� ������ ����� ���� ���������� �������, ������� ���� �������� ���� (��� ������� ���������� ������*).<br>';
		echo '������� <span style="color:#FF0000;">Test-Drive YouTube</span> �������� ������������������ ������������� ������ 1 ���!!!<br>';
	echo '</div>';
	echo '<br><br>';

	echo '<form method="post" action="" name="formzakaz" onsubmit="return SbmFormB(); return false;" id="newform">';
	echo '<table class="tables">';
		echo '<thead><th colspan="2" class="top">����� ���������� �������� �������</th></thead>';
		echo '<tbody>';
		echo '<tr>';
			echo '<td width="180" align="left"><b>URL ����� (������):</b></td>';
			echo '<td align="left"><input type="text" name="url" maxlength="160" value="https://" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������� �����������:</b></td>';
			echo '<td align="left"><input type="text" name="title" maxlength="60" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';
		/*echo '<tr>';
			echo '<td align="left"><b>������� �������� ������:</b></td>';
			echo '<td align="left"><input type="text" name="description" maxlength="60" value="" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
		echo '</tr>';*/
		echo '<tr>';
			echo '<td align="left"><b>���������� ����������:</b></td>';
			echo '<td align="left"><b>'.$plan.'</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>����� ���������:</b></td>';
			echo '<td align="left"><b>'.$timer.'</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>��������� ������:</b></td>';
			echo '<td align="left"><b>���</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� ����:</b></td>';
			echo '<td align="left"><b>���</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>�������� ��� ���������:</b></td>';
			echo '<td align="left"><b>������ 24 ����</b></td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left"><b>������� 18+:</b></td>';
			echo '<td align="left"><input type="checkbox" name="content" value="1"> - �� ���� ����� ������������ ��������� ��� ��������</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td colspan="2" align="center"><input type="submit" value="�������� �����" class="proc-btn" style="float:none;" /></td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</form>';
}

?>

<script language="JavaScript"> obsch(); </script>