<?php
require_once(ROOT_DIR."/merchant/func_cache.php");
$result_text = false;
$message_text = false;

if(!DEFINED("QUICK_MESS")) {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));

}elseif($type_ads != "quick_mess") {
	$result_text = "ERROR"; $message_text = "Hacking attempt!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

if($option == "Add") {
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 45) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$color = ( isset($_POST["color"]) && preg_match("|^[0-1]{1}$|", limpiarez($_POST["color"])) ) ? limpiarez($_POST["color"]) : 0;
	$method_pay = 0;
	$black_url = @getHost($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

	if($description==false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ����� ���������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && ($url=="http://" | $url=="https://")) {
		$result_text = "ERROR"; $message_text = "URL-����� ������ �������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && (substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		$result_text = "ERROR"; $message_text = "URL-����� ������ �������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && is_url($url)!="true") {
		$result_text = "ERROR"; $message_text = "URL-����� ����� ������ �������, �������� ������ �� ����������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && mysql_num_rows($sql_bl) > 0 && $black_url != false) {
		$row_bl = mysql_fetch_assoc($sql_bl);
		$result_text = "ERROR"; $message_text = "���� ".$row_bl["domen"]." ��������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])."";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && @getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		$result_text = "ERROR"; $message_text = SFB_YANDEX($url);
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		mysql_query("INSERT INTO `tb_ads_quick_mess` (`status`,`method_pay`,`id_us`,`username`,`date`,`description`,`url`,`color`,`money`,`ip`) 
		VALUES ('1','$method_pay','$user_id','$user_name','".time()."','$description','$url','$color','0','$my_lastiplog')") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		cache_quick_mess();

		$result_text = "OK"; $message_text = "��������� ������� ���������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}


}elseif($option == "Save") {
	$description = (isset($_POST["description"])) ? limitatexto(limpiarez($_POST["description"]), 45) : false;
	$url = (isset($_POST["url"])) ? limitatexto(limpiarez($_POST["url"]), 300) : false;
	$color = ( isset($_POST["color"]) && preg_match("|^[0-1]{1}$|", limpiarez($_POST["color"])) ) ? limpiarez($_POST["color"]) : 0;
	$black_url = @getHost($url);

	$sql_bl = mysql_query("SELECT * FROM `tb_black_sites` WHERE `domen`='$black_url'");

	if($description==false) {
		$result_text = "ERROR"; $message_text = "�� �� ������� ����� ���������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && ($url=="http://" | $url=="https://")) {
		$result_text = "ERROR"; $message_text = "URL-����� ������ �������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && (substr($url, 0, 7) != "http://" && substr($url, 0, 8) != "https://")) {
		$result_text = "ERROR"; $message_text = "URL-����� ������ �������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && is_url($url)!="true") {
		$result_text = "ERROR"; $message_text = "URL-����� ����� ������ �������, �������� ������ �� ����������";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && mysql_num_rows($sql_bl) > 0 && $black_url != false) {
		$row_bl = mysql_fetch_assoc($sql_bl);
		$result_text = "ERROR"; $message_text = "���� ".$row_bl["domen"]." ��������� � ������ ������ ������� ".strtoupper($_SERVER["HTTP_HOST"])."";
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}elseif($url!=false && @getHost($url)!=$_SERVER["HTTP_HOST"] && SFB_YANDEX($url)!=false) {
		$result_text = "ERROR"; $message_text = SFB_YANDEX($url);
		exit(my_json_encode($ajax_json, $result_text, $message_text));

	}else{
		$sql_check = mysql_query("SELECT `id` FROM `tb_ads_quick_mess` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
		if(mysql_num_rows($sql_check)>0) {
			mysql_query("UPDATE `tb_ads_quick_mess` SET `url`='$url', `description`='$description', `color`='$color' WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

			cache_quick_mess();

			$message_text.= '<img width="16" height="16" border="0" alt="" title="" style="margin:0; padding:0; padding-bottom:2px; padding-right:5px;" src="http://www.google.com/s2/favicons?domain='.($url!=false ? @gethost($url) : $_SERVER["HTTP_HOST"]).'" align="absmiddle" />';
			$message_text.= '<a class="adv" href="javascript:void(0);"><span '.($color==1 ? 'class="text-red"' : false).'>'.$description.'</span></a><br>';
			$message_text.= 'URL: '.($url!=false ? '<a href="'.$url.'" target="_blank">'.$url.'</a>' : '<span style="color:#CCC;">���</span>').'';

			$result_text = "OK";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}else{
			$result_text = "ERROR"; $message_text = "������! ��������� �������� � ID:$id �� �������!";
			exit(my_json_encode($ajax_json, $result_text, $message_text));
		}
	}


}elseif($option == "Delete") {
	$sql_check = mysql_query("SELECT `id` FROM `tb_ads_quick_mess` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql_check)>0) {
		mysql_query("DELETE FROM `tb_ads_quick_mess` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));

		cache_quick_mess();

		$result_text = "OK"; $message_text = "��������� �������� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "��������� �������� � ID:$id �� �������, �������� ��� ��� ���� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}


}elseif($option == "LoadForm") {
	$sql = mysql_query("SELECT * FROM `tb_ads_quick_mess` WHERE `id`='$id'") or die(my_json_encode($ajax_json, "ERROR", mysql_error()));
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$message_text.= '<div id="newform">';
			$message_text.= '<table class="tables" style="border:none; margin:0; padding:5px; width:100%;">';
			$message_text.= '<thead><tr><th align="center" colspan="2">�������������� ��������� ID:'.$id.'</th></thead></tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left" width="220"><b>���������</b></td>';
				$message_text.= '<td align="left"><input type="text" id="description" maxlength="45" value="'.$row["description"].'" class="ok"></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left">URL (�� �����������)</td>';
				$message_text.= '<td align="left"><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok"></td>';
			$message_text.= '</tr>';
			$message_text.= '<tr>';
				$message_text.= '<td align="left">�������� ������</td>';
				$message_text.= '<td align="left">';
					$message_text.= '<select id="color" class="ok">';
						$message_text.= '<option value="0" '.($row["color"]==0 ? 'selected="selected"' : false).'>���</option>';
						$message_text.= '<option value="1" '.($row["color"]==1 ? 'selected="selected"' : false).'>��</option>';
					$message_text.= '</select>';
				$message_text.= '</td>';
			$message_text.= '</tr>';
			$message_text.= '</table>';
		$message_text.= '</div>';

		$message_text.= '<div align="center"><span id="Save" onClick="SaveAds(\''.$id.'\', \'quick_mess\', \'Save\');" class="sub-green" style="float:none;">���������</span></div>';

		$result_text = "OK";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}else{
		$result_text = "ERROR"; $message_text = "��������� �������� � ID:$id �� �������!";
		exit(my_json_encode($ajax_json, $result_text, $message_text));
	}


}else{
	$result_text = "ERROR"; $message_text = "ERROR: NO OPTION!";
	exit(my_json_encode($ajax_json, $result_text, $message_text));
}

?>