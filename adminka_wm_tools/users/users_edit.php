<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}
//require(ROOT_DIR."/api/advcash/advcash_func.php");

############### ����� ������ ###############
$param_count = 2;
$_WHERE_SEARCH = array();
$_WHERE_SEARCH_SESS = array();
$_ORDER_SORT = array();
$_ORDER_SORT_SESS = array();

$param_arr = array(
	"false" 	=>  	"��� ������", 
	"id" 		=>  	"ID", 
	"username" 	=> 	"�����", 
	"password" 	=> 	"������", 
	"referer" 	=> 	"������� I ������", 
	"referer2" 	=> 	"������� II ������", 
	"referer3" 	=> 	"������� III ������", 
	
	"wmid" 		=> 	"WMID", 
	"agent" 	=> 	"�������", 
	"wm_purse" 	=> 	"WMR �������", 
	"ym_purse" 	=> 	"������ �������", 
	"py_purse" 	=> 	"Payeer �������", 
	"pm_purse" 	=> 	"PerfectMoney �������", 
	"qw_purse" 	=> 	"QIWI �������", 
	"be_purse" 	=> 	"��������� ������� ������", 
	"mt_purse" 	=> 	"��������� ������� ���",
	"mg_purse" 	=> 	"��������� ������� �������",
	"tl_purse" 	=> 	"��������� �������TELE2", 
	"ac_purse" 	=> 	"AdvCash �������", 
	"email" 	=> 	"E-mail", 
	"ip" 		=> 	"IP �����������", 
	"lastiplog" 	=> 	"IP ����������", 
	"log_ip_aut" 	=> 	"��� IP", 
	"joindate" 	=> 	"���� �����������", 
	"lastlogdate" 	=> 	"���� ����������", 
	"http_ref" 	=> 	"������ � [HTTP_REFERER]", 
	"investor" 	=> 	"��������",
	"user_status" 	=> 	"������ [0-����, 1-�����, 2-���������]",
	"country_cod" 	=> 	"������ [���, ��������: RU,UA...]"
);
$param_key_arr = array_keys($param_arr);
$param_val_arr = array_values($param_arr);

$oper_arr = array(
	"false" 	=> 	"", 
	"=" 		=> 	"= [�����, ������ ������������]", 
	"!=" 		=> 	"!= [�� �����, ������ ������������]", 
	"LIKE" 		=> 	"LIKE '%...%' [�������� ����� �� ����/��������]", 
	"NOT LIKE" 	=> 	"NOT LIKE '%...%' [�� �������� ����� �� ����/��������]",
	"= ''" 		=> 	"= '' [����� �����]", 
	"!= ''" 	=> 	"!= '' [�� ����� �����]"
);
$oper_key_arr = array_keys($oper_arr);
$oper_val_arr = array_values($oper_arr);

$and_or_arr = array("AND" => "�", "OR" => "���");
$and_or_key_arr = array_keys($and_or_arr);
$and_or_val_arr = array_values($and_or_arr);

$order_arr = array(
	"false" 	=>  	"��� ����������", 
	"id" 		=>  	"ID", 
	"username" 	=> 	"�����", 
	"joindate2" 	=> 	"���� �����������", 
	"lastlogdate2" 	=> 	"���� ����������", 
	"money" 	=> 	"������, �������� ����",
	"money_rb" 	=> 	"������, ��������� ����",
	"money_in" 	=> 	"���������� ���������� �����",
	"money_out" 	=> 	"��������� �������",
	"referals" 	=> 	"���������� ��������� I ������",
	"referals2" 	=> 	"���������� ��������� II ������",
	"referals3" 	=> 	"���������� ��������� III ������",
	
	"reiting" 	=> 	"���������� ������ ��������", 
	"visits" 	=> 	"���������� ���������� � ��������", 
	"visits_a" 	=> 	"���������� ���������� � ����-��������", 
	"visits_m" 	=> 	"���������� ��������� �����", 
	"visits_tests" 	=> 	"���������� ����������� ������", 
	"visits_t" 	=> 	"���������� ���������� �������", 

);
$order_key_arr = array_keys($order_arr);
$order_val_arr = array_values($order_arr);

$asc_desc_arr = array("ASC" => "�� �����������", "DESC" => "�� ��������");
$asc_desc_key_arr = array_keys($asc_desc_arr);
$asc_desc_val_arr = array_values($asc_desc_arr);

$_COOKIE["WHERE_USERS_ARR"] = (isset($_COOKIE["WHERE_USERS_ARR"]) && is_array(unserialize(base64_decode($_COOKIE["WHERE_USERS_ARR"]))) ) ? unserialize(base64_decode($_COOKIE["WHERE_USERS_ARR"])) : false;
$_COOKIE["SORT_USERS_ARR"] = (isset($_COOKIE["SORT_USERS_ARR"]) && is_array(unserialize(base64_decode($_COOKIE["SORT_USERS_ARR"]))) ) ? unserialize(base64_decode($_COOKIE["SORT_USERS_ARR"])) : false;

for($i=1; $i<=$param_count; $i++) {
	if(isset($_POST["param"]) && count($_POST["param"])>0) {
		$param_post[$i] = ( isset($_POST["param"][$i]) && array_search(htmlentities(trim($_POST["param"][$i])), $param_key_arr)!==false && $par_m = array_search(htmlentities(trim($_POST["param"][$i])), $param_key_arr) ) ? $param_key_arr[$par_m] : false;
		$operator_post[$i] = ( $param_post[$i]!==false && isset($_POST["operator"][$i]) && array_search(htmlentities(trim($_POST["operator"][$i])), $oper_key_arr)!==false && $oper_m = array_search(htmlentities(trim($_POST["operator"][$i])), $oper_key_arr) ) ? $oper_key_arr[$oper_m] : false;
		$search_post[$i] = ( $param_post[$i]!==false && isset($_POST["search_val"][$i]) && htmlentities(trim($_POST["search_val"][$i]))!=false ) ? ( get_magic_quotes_gpc() ? stripslashes(htmlentities(trim($_POST["search_val"][$i]))) : htmlentities(trim($_POST["search_val"][$i])) ) : false;
		$and_or_post[$i] = ( $param_post[$i]!==false && isset($_POST["and_or"][$i]) && array_search(htmlentities(trim($_POST["and_or"][$i])), $and_or_key_arr)!==false && $and_or_m = array_search(htmlentities(trim($_POST["and_or"][$i])), $and_or_key_arr) ) ? $and_or_key_arr[$and_or_m] : "AND";
	}else{
		$param_post[$i] = ( isset($_COOKIE["WHERE_USERS_ARR"][$i]["param"]) && array_search(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["param"])), $param_key_arr)!==false && $par_m = array_search(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["param"])), $param_key_arr) ) ? $param_key_arr[$par_m] : false;
		$operator_post[$i] = ( $param_post[$i]!==false && isset($_COOKIE["WHERE_USERS_ARR"][$i]["operator"]) && array_search(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["operator"])), $oper_key_arr)!==false && $oper_m = array_search(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["operator"])), $oper_key_arr) ) ? $oper_key_arr[$oper_m] : false;
		$search_post[$i] = ( $param_post[$i]!==false && isset($_COOKIE["WHERE_USERS_ARR"][$i]["search_val"]) && htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["search_val"]))!=false ) ? ( get_magic_quotes_gpc() ? stripslashes(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["search_val"]))) : htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["search_val"])) ) : false;
		$and_or_post[$i] = ( $param_post[$i]!==false && isset($_COOKIE["WHERE_USERS_ARR"][$i]["and_or"]) && array_search(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["and_or"])), $and_or_key_arr)!==false && $and_or_m = array_search(htmlentities(trim($_COOKIE["WHERE_USERS_ARR"][$i]["and_or"])), $and_or_key_arr) ) ? $and_or_key_arr[$and_or_m] : "AND";
	}
}

for($i=1; $i<=$param_count; $i++) {
	if($param_post[$i]!==false && $operator_post[$i]!==false) {

		if($param_post[$i]=="password") {$search_tab[$i] = md5($search_post[$i]);}else{$search_tab[$i]=$search_post[$i];}

		$and_or_post[$i] = ($i!=$param_count && $param_post[$i+1]!==false) ? $and_or_post[$i] : false;
		if($operator_post[$i] == "LIKE") {
			$search[$i] = "`$param_post[$i]` LIKE '%".mysql_real_escape_string($search_tab[$i])."%' $and_or_post[$i]";
		}elseif($operator_post[$i] =="NOT LIKE") {
			$search[$i] = "`$param_post[$i]` NOT LIKE '%".mysql_real_escape_string($search_tab[$i])."%' $and_or_post[$i]";
		}elseif($operator_post[$i] == "= ''" | $operator_post[$i] == "!= ''") {
			$search_post[$i] = false;
			$search[$i] = "`$param_post[$i]` $operator_post[$i] $and_or_post[$i]";
		}else{
			$search[$i] = "`$param_post[$i]` $operator_post[$i] '".mysql_real_escape_string($search_tab[$i])."' $and_or_post[$i]";
		}
		$_WHERE_SEARCH[] = "$search[$i]";
		$_WHERE_SEARCH_SESS[$i] = array("param" => $param_post[$i], "operator" => $operator_post[$i], "search_val" => $search_post[$i], "and_or" => $and_or_post[$i]);

	}
}
$_WHERE_SEARCH = (is_array($_WHERE_SEARCH) && count($_WHERE_SEARCH)>0) ? "WHERE ".implode(" ", $_WHERE_SEARCH) : false;


if(isset($_POST["sort"]) && count($_POST["sort"])>0) {
	$sort_post = ( isset($_POST["sort"]) && array_search(htmlentities(trim($_POST["sort"])), $order_key_arr)!==false && $src_m = array_search(htmlentities(trim($_POST["sort"])), $order_key_arr) ) ? $order_key_arr[$src_m] : false;
	$asc_desc_post = ( $sort_post!==false && isset($_POST["asc_desc"]) && array_search(htmlentities(trim($_POST["asc_desc"])), $asc_desc_key_arr)!==false && $asc_desc_m = array_search(htmlentities(trim($_POST["asc_desc"])), $asc_desc_key_arr) ) ? $asc_desc_key_arr[$asc_desc_m] : "ASC";
}else{
	$sort_post = ( isset($_COOKIE["SORT_USERS_ARR"]["sort"]) && array_search(htmlentities(trim($_COOKIE["SORT_USERS_ARR"]["sort"])), $order_key_arr)!==false && $src_m = array_search(htmlentities(trim($_COOKIE["SORT_USERS_ARR"]["sort"])), $order_key_arr) ) ? $order_key_arr[$src_m] : false;
	$asc_desc_post = ( $sort_post!==false && isset($_COOKIE["SORT_USERS_ARR"]["asc_desc"]) && array_search(htmlentities(trim($_COOKIE["SORT_USERS_ARR"]["asc_desc"])), $asc_desc_key_arr)!==false && $asc_desc_m = array_search(htmlentities(trim($_COOKIE["SORT_USERS_ARR"]["asc_desc"])), $asc_desc_key_arr) ) ? $asc_desc_key_arr[$asc_desc_m] : "ASC";
}
$_ORDER_SORT = (isset($sort_post) && isset($asc_desc_post) && $sort_post!=false && $asc_desc_post!=false) ? "ORDER BY `$sort_post` $asc_desc_post" : false;
$_ORDER_SORT_SESS = array("sort" => $sort_post, "asc_desc" => $asc_desc_post);
############### ����� ������ ###############

?><script type="text/javascript" language="JavaScript">
function ShowHideSearch(id) {
	if($("#adv-title-"+id).attr("class") == "adv-title-open") {
		$("#adv-title-"+id).attr("class", "adv-title-close");
		setCookie("MENU_USERS_SEARSH", "0", "", "<?php echo $_SERVER["HTTP_HOST"];?>");
	} else {
		$("#adv-title-"+id).attr("class", "adv-title-open");
		setCookie("MENU_USERS_SEARSH", "1", "", "<?php echo $_SERVER["HTTP_HOST"];?>");
	}
	$("#adv-block-"+id).slideToggle("slow");
}
<?php
if($_WHERE_SEARCH!==false) {
	echo 'setCookie("WHERE_USERS_ARR", "'.base64_encode(serialize($_WHERE_SEARCH_SESS)).'", "", "'.(isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : false).'");'."\n";
}
if(isset($_POST["param"]) && count($_POST["param"])>0) {
	echo 'setTimeout(function() {window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.(isset($_GET["op"]) ? limpiar($_GET["op"]) : false).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");}, 500);'."\n";
}
?>

function ShowHideSort(id) {
	if($("#adv-title-"+id).attr("class") == "adv-title-open") {
		$("#adv-title-"+id).attr("class", "adv-title-close");
		setCookie("MENU_USERS_SORT", "0", "", "<?php echo $_SERVER["HTTP_HOST"];?>");
	} else {
		$("#adv-title-"+id).attr("class", "adv-title-open");
		setCookie("MENU_USERS_SORT", "1", "", "<?php echo $_SERVER["HTTP_HOST"];?>");
	}
	$("#adv-block-"+id).slideToggle("slow");
}
<?php
if($_ORDER_SORT!==false) {
	echo 'setCookie("SORT_USERS_ARR", "'.base64_encode(serialize($_ORDER_SORT_SESS)).'", "", "'.(isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : false).'");'."\n";
}
if(isset($_POST["sort"]) && count($_POST["sort"])>0) {
	echo 'setTimeout(function() {window.history.replaceState(null, null, "'.$_SERVER["PHP_SELF"].'?op='.(isset($_GET["op"]) ? limpiar($_GET["op"]) : false).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'");}, 500);'."\n";
}
?>
</script><?php

function my_status($my_reiting){
	$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` WHERE `r_ot`<='$my_reiting' AND `r_do`>='".floor($my_reiting)."'");
	if(mysql_num_rows($sql_rang)>0) {
		$row_rang = mysql_fetch_array($sql_rang);
	}else{
		$sql_rang = mysql_query("SELECT `rang` FROM `tb_config_rang` ORDER BY `id` ASC LIMIT 1");
		$row_rang = mysql_fetch_array($sql_rang);
	}
	return '<span style="cursor:pointer; color: #006699;" title="������">'.$row_rang["rang"].'</span>';
}

require("navigator/navigator.php");
$perpage = 20;
$sql_p = mysql_query("SELECT `id` FROM `tb_users` $_WHERE_SEARCH");
$count = mysql_numrows($sql_p);
$pages_count = ceil($count / $perpage);
$page = (isset($_GET["page"]) && preg_match("|^[0-9]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
if ($page > $pages_count | $page<0) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos = 0;

$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

$attestat[100] ='<font color="#000"><img src="/img/att/att_100.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� ����������</font>';
$attestat[110]='<font color="green"><img src="/img/att/att_110.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> ���������� ��������</font>';
$attestat[120]='<font color="green"><img src="/img/att/att_120.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> ��������� ��������</font>';
$attestat[130]='<font color="green"><img src="/img/att/att_130.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> ������������ ��������</font>';
$attestat[135]='<font color="green"><img src="/img/att/att_135.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� ��������</font>';
$attestat[136]='<font color="green"><img src="/img/att/att_136.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� Capitaller</font>';
$attestat[140]='<font color="green"><img src="/img/att/att_140.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� ������������</font>';
$attestat[150]='<font color="green"><img src="/img/att/att_150.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� ������������</font>';
$attestat[170]='<font color="green"><img src="/img/att/att_170.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� �������</font>';
$attestat[190]='<font color="green"><img src="/img/att/att_190.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� �������</font>';
$attestat[300]='<font color="green"><img src="/img/att/att_300.ico" alt="" align="absmiddle" border="0" width="18" height="18" /> �������� ���������</font>';


if(isset($_GET["option"]) && limpiar($_GET["option"])=="edit") {

	if(count($_POST)>0) {
		$id = (isset($_POST["id"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id"]))) ? intval(limpiar(trim($_POST["id"]))) : false;
		$username = (isset($_POST["username"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", trim($_POST["username"]))) ? uc($_POST["username"]) : false;
		$password = (isset($_POST["password"]) && preg_match("|^[a-zA-Z0-9\-_-]{6,20}$|", trim($_POST["password"]))) ? uc($_POST["password"]) : false;
		$pass_oper = (isset($_POST["pass_oper"]) && preg_match("|^[a-zA-Z0-9\-_-]{6,15}$|", trim($_POST["pass_oper"]))) ? uc($_POST["pass_oper"]) : false;
		$email = (isset($_POST["email"]) && preg_match("|^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@+([_a-zA-Z0-9-]+\.)*[a-zA-Z0-9-]{1,200}\.[a-zA-Z]{1,4}$|", trim($_POST["email"]))) ? limpiar(trim($_POST["email"])) : false;
		$email_ok = (isset($_POST["email_ok"]) && (intval($_POST["email_ok"])==0 | intval($_POST["email_ok"])==1)) ? intval($_POST["email_ok"]) : "0";
		$fl18 = (isset($_POST["fl18"]) && (intval($_POST["fl18"])==0 | intval($_POST["fl18"])==1)) ? intval($_POST["fl18"]) : "0";
		$db_d = (isset($_POST["db_d"]) && (intval($_POST["db_d"])>0 | intval($_POST["db_d"])<=31)) ? intval($_POST["db_d"]) : false;
		$db_m = (isset($_POST["db_m"]) && (intval($_POST["db_m"])>0 | intval($_POST["db_m"])<=12)) ? intval($_POST["db_m"]) : false;
		$db_y = (isset($_POST["db_y"]) && (intval($_POST["db_y"])>(DATE("Y")-91) | intval($_POST["db_y"])<=(DATE("Y")-6))) ? intval($_POST["db_y"]) : false;
		$db_time_ = "$db_d.$db_m.$db_y";
		$db_time = DATE("d.m.Y",strtotime($db_time_));
		$db_time = strtotime($db_time);
		$sex = (isset($_POST["sex"]) && (intval($_POST["sex"])==0 | intval($_POST["sex"])==1 | intval($_POST["sex"])==2)) ? intval($_POST["sex"]) : "0";
		$fio = (isset($_POST["fio"]) && preg_match("|^[�-��-�a-zA-Z\- ]{1,160}$|", trim($_POST["fio"]))) ? htmlspecialchars(trim($_POST["fio"])) : false;
		$imay = (isset($_POST["imay"]) && preg_match("|^[�-��-�a-zA-Z\- ]{1,160}$|", trim($_POST["imay"]))) ? htmlspecialchars(trim($_POST["imay"])) : false;

		$reiting = (isset($_POST["reiting"])) ? floatval(str_replace(",",".",trim($_POST["reiting"]))) : "0";
		$rep_task = (isset($_POST["rep_task"]) && preg_match("|^[-+]?[\d]{1,11}$|", trim($_POST["rep_task"]))) ? intval(limpiar(trim($_POST["rep_task"]))) : false;

		$referer1 = (isset($_POST["referer"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", trim($_POST["referer"]))) ? uc($_POST["referer"]) : false;
		$refmoney = (isset($_POST["refmoney"])) ? abs(floatval(str_replace(",",".",trim($_POST["refmoney"])))) : "0";
		$ref2money = (isset($_POST["ref2money"])) ? abs(floatval(str_replace(",",".",trim($_POST["ref2money"])))) : "0";
		$ref3money = (isset($_POST["ref3money"])) ? abs(floatval(str_replace(",",".",trim($_POST["ref3money"])))) : "0";
		//$refmoney4 = (isset($_POST["refmoney4"])) ? abs(floatval(str_replace(",",".",trim($_POST["refmoney4"])))) : "0";
		//$refmoney5 = (isset($_POST["refmoney5"])) ? abs(floatval(str_replace(",",".",trim($_POST["refmoney5"])))) : "0";

		$visits = (isset($_POST["visits"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits"]))) ? intval(limpiar(trim($_POST["visits"]))) : "0";
		$visits_s = (isset($_POST["visits_s"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits_s"]))) ? intval(limpiar(trim($_POST["visits_s"]))) : "0";
		$visits_a = (isset($_POST["visits_a"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits_a"]))) ? intval(limpiar(trim($_POST["visits_a"]))) : "0";
		$visits_t = (isset($_POST["visits_t"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits_t"]))) ? intval(limpiar(trim($_POST["visits_t"]))) : "0";
		$visits_m = (isset($_POST["visits_m"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits_m"]))) ? intval(limpiar(trim($_POST["visits_m"]))) : "0";
		$visits_bs = (isset($_POST["visits_bs"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits_bs"]))) ? intval(limpiar(trim($_POST["visits_bs"]))) : "0";
		$visits_you = (isset($_POST["visits_you"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["visits_you"]))) ? intval(limpiar(trim($_POST["visits_you"]))) : "0";
		$youtube_serf = (isset($_POST["youtube_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["youtube_serf"]))) ? intval(limpiar(trim($_POST["youtube_serf"]))) : "0";
		$vip_serf = (isset($_POST["vip_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["vip_serf"]))) ? intval(limpiar(trim($_POST["vip_serf"]))) : "0";
		$ban_serf = (isset($_POST["ban_serf"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["ban_serf"]))) ? intval(limpiar(trim($_POST["ban_serf"]))) : "0";
		$konkurs_ads_task = (isset($_POST["konkurs_ads_task"])) ? abs(floatval(str_replace(",",".",trim($_POST["konkurs_ads_task"])))) : "0";
		$kon_board_m = (isset($_POST["kon_board_m"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["kon_board_m"]))) ? intval(limpiar(trim($_POST["kon_board_m"]))) : "0";

		$money_in = (isset($_POST["money_in"])) ? abs(floatval(str_replace(",",".",trim($_POST["money_in"])))) : "0";
		$money_rek = (isset($_POST["money_rek"])) ? abs(floatval(str_replace(",",".",trim($_POST["money_rek"])))) : "0";
		$money = (isset($_POST["money"])) ? (floatval(str_replace(",",".",trim($_POST["money"])))) : "0";
		$money_rb = (isset($_POST["money_rb"])) ? (floatval(str_replace(",",".",trim($_POST["money_rb"])))) : "0";
		$money_inv = (isset($_POST["money_inv"])) ? abs(floatval(str_replace(",",".",trim($_POST["money_inv"])))) : "0";
		$money_out = (isset($_POST["money_out"])) ? abs(floatval(str_replace(",",".",trim($_POST["money_out"])))) : "0";
		$block_wmid = (isset($_POST["block_wmid"]) && (intval($_POST["block_wmid"])==0 | intval($_POST["block_wmid"])==1)) ? intval($_POST["block_wmid"]) : "0";
		$block_agent = (isset($_POST["block_agent"]) && (intval($_POST["block_agent"])==0 | intval($_POST["block_agent"])==1)) ? intval($_POST["block_agent"]) : "0";
		$user_status = (isset($_POST["user_status"]) && (intval($_POST["user_status"])==0 | intval($_POST["user_status"])==1 | intval($_POST["user_status"])==2)) ? intval($_POST["user_status"]) : "0";
		$ban = (isset($_POST["ban"]) && (intval($_POST["ban"])==0 | intval($_POST["ban"])==1)) ? intval($_POST["ban"]) : "0";
		$act_phone = (isset($_POST["act_phone"]) && preg_match("|^[\d]{1}$|", trim($_POST["act_phone"]))) ? intval(trim($_POST["act_phone"])) : false;

		$wm_id = (isset($_POST["wmid"]) && preg_match("|^[\d]{12}$|", trim($_POST["wmid"]))) ? limpiar(trim($_POST["wmid"])) : false;
		$wm_purse = (isset($_POST["wm_purse"]) && preg_match("|^[R]{1}[\d]{12}$|", htmlspecialchars(trim($_POST["wm_purse"])))) ? trim($_POST["wm_purse"]) : false;
		$ym_purse = (isset($_POST["ym_purse"]) && preg_match("|^[\d]{13,16}$|", trim($_POST["ym_purse"]))) ? trim($_POST["ym_purse"]) : false;
		$qw_purse = (isset($_POST["qw_purse"]) && preg_match("/^\+?([87](?!95[4-79]|99[08]|907|94[^0]|336)([348]\d|9[0-6789]|7[0247])\d{8}|[1246]\d{9,13}|68\d{7}|5[1-46-9]\d{8,12}|55[1-9]\d{9}|55[12]19\d{8}|500[56]\d{4}|5016\d{6}|5068\d{7}|502[45]\d{7}|5037\d{7}|50[4567]\d{8}|50855\d{4}|509[34]\d{7}|376\d{6}|855\d{8}|856\d{10}|85[0-4789]\d{8,10}|8[68]\d{10,11}|8[14]\d{10}|82\d{9,10}|852\d{8}|90\d{10}|96(0[79]|17[01]|13)\d{6}|96[23]\d{9}|964\d{10}|96(5[69]|89)\d{7}|96(65|77)\d{8}|92[023]\d{9}|91[1879]\d{9}|9[34]7\d{8}|959\d{7}|989\d{9}|97\d{8,12}|99[^4568]\d{7,11}|994\d{9}|9955\d{8}|996[57]\d{8}|9989\d{8}|380[3-79]\d{8}|381\d{9}|385\d{8,9}|375[234]\d{8}|372\d{7,8}|37[0-4]\d{8}|37[6-9]\d{7,11}|30[69]\d{9}|34[67]\d{8}|3[12359]\d{8,12}|36\d{9}|38[1679]\d{8}|382\d{8,9}|46719\d{10})$/", trim($_POST["qw_purse"]))) ? htmlspecialchars(trim($_POST["qw_purse"]), NULL, "CP1251") : false;
		$py_purse = (isset($_POST["py_purse"]) && preg_match("|^[P]{1}[\d]{1,20}$|", trim($_POST["py_purse"]))) ? trim($_POST["py_purse"]) : false;
		$pm_purse = (isset($_POST["pm_purse"]) && preg_match("|^[U]{1}[\d]{1,20}$|", trim($_POST["pm_purse"]))) ? trim($_POST["pm_purse"]) : false;
		//$mb_purse = (isset($_POST["mb_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["mb_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["mb_purse"]))) ? htmlspecialchars(trim($_POST["mb_purse"]), NULL, "CP1251") : false;
		$be_purse = (isset($_POST["be_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["be_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["be_purse"]))) ? htmlspecialchars(trim($_POST["be_purse"]), NULL, "CP1251") : false;
		$mt_purse = (isset($_POST["mt_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["mt_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["mt_purse"]))) ? htmlspecialchars(trim($_POST["mt_purse"]), NULL, "CP1251") : false;
		$mg_purse = (isset($_POST["mg_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["mg_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["mg_purse"]))) ? htmlspecialchars(trim($_POST["mg_purse"]), NULL, "CP1251") : false;
		$tl_purse = (isset($_POST["tl_purse"]) && preg_match("/^\+?(79|73|74|78)/", trim($_POST["tl_purse"])) && preg_match("/^[+]?[\d]{11}$/", trim($_POST["tl_purse"]))) ? htmlspecialchars(trim($_POST["tl_purse"]), NULL, "CP1251") : false;
		$me_purse = (isset($_POST["me_purse"]) && preg_match("|^[\d]{14,22}$|", trim($_POST["me_purse"]))) ? trim($_POST["me_purse"]) : false;
		$vs_purse = (isset($_POST["vs_purse"]) && preg_match("|^[\d]{14,22}$|", trim($_POST["vs_purse"]))) ? trim($_POST["vs_purse"]) : false;
		$ms_purse = (isset($_POST["ms_purse"]) && preg_match("|^[\d]{14,22}$|", trim($_POST["ms_purse"]))) ? trim($_POST["ms_purse"]) : false;
		$ac_purse = (isset($_POST["ac_purse"]) && preg_match("|^[R]{1}[\d]{12}$|", str_ireplace(" ", "", strtoupper(htmlspecialchars(trim($_POST["ac_purse"])))))) ? str_ireplace(" ", "", strtoupper(htmlspecialchars(trim($_POST["ac_purse"])))) : false;
		$ac_purse = $ac_purse!=false ? (CheckWalletIdAC($ac_purse)==$ac_purse ? $ac_purse : false) : false;

		$qw_purse = str_ireplace("+","", $qw_purse);
		$be_purse = str_ireplace("+","", $be_purse);
		$mt_purse = str_ireplace("+","", $mt_purse);
		$mg_purse = str_ireplace("+","", $mg_purse);
		$tl_purse = str_ireplace("+","", $tl_purse);

		$investor = (isset($_POST["investor"]) && (intval($_POST["investor"])==0 | intval($_POST["investor"])==1)) ? intval($_POST["investor"]) : "0";
		
		if($referer1!="") {
			$sql_1 = mysql_query("SELECT `username`,`referer` FROM `tb_users` WHERE `username`='$referer1'");
			if(mysql_num_rows($sql_1)>0) {
				$row_1 = mysql_fetch_array($sql_1);

				$referer1 = $row_1["username"];
				$referer2 = $row_1["referer"];

				if($referer2!="") {
					$sql_2 = mysql_query("SELECT `referer` FROM `tb_users` WHERE `username`='$referer2'");
					if(mysql_num_rows($sql_2)>0) {
						$row_2 = mysql_fetch_array($sql_2);

						$referer3 = $row_2["referer"];
					}else{
						$referer3 = "";
					}
				}else{
					$referer3 = "";
				}
			}else{
				$referer1 = "";
				$referer2 = "";
				$referer3 = "";
			}
		}else{
			$referer1 = "";
			$referer2 = "";
			$referer3 = "";
		}

		$sql_r1 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer`='$username'");
		$referals1 = mysql_num_rows($sql_r1);

		$sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer2`='$username'");
		$referals2 = mysql_num_rows($sql_r2);

		$sql_r2 = mysql_query("SELECT `id` FROM `tb_users` WHERE `referer3`='$username'");
		$referals3 = mysql_num_rows($sql_r2);

		$sql_e = mysql_query("SELECT `id` FROM `tb_users` WHERE `id`='$id' AND `username`='$username'");
		if(mysql_num_rows($sql_e)<1) {
			echo '<br><span class="msg-error">������������ �� ������.</span><br><br>';
		}elseif($id==false) {
			echo '<br><span class="msg-error">������������ �� ������.</span><br><br>';
		}elseif($password==false) {
			echo '<span class="msg-error">������ ������������ ������! ������ ������ �������� �� ����� ��� �� 6 � �� ����� 20 ��������, � ��������� ������ ��������� �������, ����������� ������� "-" � "_"</span>';
		}elseif($pass_oper==false) {
			echo '<span class="msg-error">������ ������������ ������ ��� ��������! ������ ������ �������� �� ����� ��� �� 6 � �� ����� 15 ��������, � ��������� ������ ��������� �������, ����������� ������� "-" � "_"</span>';
		}elseif($email==false) {
			echo '<span class="msg-error">E-mail ������ �� ���������!</span>';
		}else{
			$_ERROR = 0;

			$sql_check_wm = mysql_query("SELECT `wmid`,`wm_purse`,`attestat` FROM `tb_users` WHERE `id`='$id'");
			if(mysql_num_rows($sql_check_wm)>0) {
				$row_check_wm = mysql_fetch_assoc($sql_check_wm);

				if(isset($wm_purse) && $wm_purse!=false && trim($row_check_wm["wm_purse"])!=$wm_purse) {
					require_once($_SERVER["DOCUMENT_ROOT"]."/auto_pay_req/wmxml.inc.php");
					$_RES_WM_8 = _WMXML8("", $wm_purse);

					if($_RES_WM_8["retval"]==0) {
						echo '<span class="msg-error">���� WMR �� ������ � ������� WebMoney, ��������� ������������ �����!</span>';

						$wm_id_x8 = false;
						$wm_purse_x8 = false;
						$wm_attestat_x8 = false;
						$_ERROR++;

					}elseif($_RES_WM_8["retval"]==1) {
						$wm_id_x8 = $_RES_WM_8["wmid"];
						$wm_purse_x8 = $_RES_WM_8["purse"];

						$_RES_WM_11 = _WMXML11($wm_id_x8);

						$sql_wmid_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `wmid`='$wm_id_x8'");
						$sql_wmr_check = mysql_query("SELECT `id` FROM `tb_users` WHERE `wm_purse`='$wm_purse_x8'");

						if(mysql_num_rows($sql_wmid_check)>0) {
							echo '<span class="msg-error">������������ � WMID '.$wm_id_x8.' ��� ���������������!</span>';

							$wm_id_x8 = false;
							$wm_purse_x8 = false;
							$wm_attestat_x8 = false;
							$_ERROR++;

						}elseif(mysql_num_rows($sql_wmr_check)>0) {
							echo '<span class="msg-error">������������ � WMR ��������� '.$wm_purse_x8.' ��� ���������������!</span>';

							$wm_id_x8 = false;
							$wm_purse_x8 = false;
							$wm_attestat_x8 = false;
							$_ERROR++;

						}else{
							$wm_id_x8 = (isset($wm_id_x8) && preg_match("|^[\d]{12}$|", htmlspecialchars(trim($wm_id_x8)))) ? htmlspecialchars(trim($wm_id_x8)) : false;
							$wm_purse_x8 = (isset($wm_purse_x8) && preg_match("|^[R]{1}[\d]{12}$|", htmlspecialchars(trim($wm_purse_x8)))) ? htmlspecialchars(trim($wm_purse_x8)) : false;
							$wm_attestat_x8 = isset($_RES_WM_8["newattst"]) ? $_RES_WM_8["newattst"] : 0;
						}
					}else{
						$wm_id_x8 = false;
						$wm_purse_x8 = false;
						$wm_attestat_x8 = false;
						$_ERROR++;

						echo '<span class="msg-error">������ ��������� ������! ��������� ������� ����� WMR �������� �����.</span>';
					}

				}elseif(isset($wm_purse) && $wm_purse==false) {
					$wm_id_x8 = false;
					$wm_purse_x8 = false;
					$wm_attestat_x8 = false;
				}else{
					$wm_id_x8 = $row_check_wm["wmid"];
					$wm_purse_x8 = $row_check_wm["wm_purse"];
					$wm_attestat_x8 = $row_check_wm["attestat"];
				}

			}else{
				$_ERROR++;

				echo '<span class="msg-error">������ ��������� ������! ������������ �� ������.</span>';
			}


			if($_ERROR==0) {

				if(isset($wm_id_x8) && isset($wm_purse_x8)) {
					if($wm_id_x8 == false | $wm_purse_x8 == false) {
						$wm_tab = ", `wmid`='', `wm_purse`='', `attestat`='0'";
					}else{
						$wm_tab = ", `wmid`='$wm_id_x8', `wm_purse`='$wm_purse_x8', `attestat`='$wm_attestat_x8'";
					}
				}else{
					$wm_tab = ", `wmid`='', `wm_purse`='', `attestat`='0'";
				}
				
				if($db_d!=false|$db_m!=false|$db_y!=false) {$db_time_tab = ", `db_time`='$db_time', `db_date`='".DATE("d.m.Y", $db_time)."'";}else{$db_time_tab = "";}
				if($sex!=false) {$sex_tab = ", `sex`='$sex'";}else{$sex_tab = "";}
				if($fio!=false) {$fio_tab = ", `fio`='$fio'";}else{$fio_tab = "";}
				if($imay!=false) {$imay_tab = ", `imay`='$imay'";}else{$imay_tab = "";}

				mysql_query("UPDATE `tb_users` SET 
					`password`='$password',
					`email`='$email',
					`email_ok`='$email_ok',
					`reiting`='$reiting', 
					`rep_task`='$rep_task', 
					`fl18`='$fl18',
					`referer`='$referer1',
					`referer2`='$referer2',
					`referer3`='$referer3',
					`visits`='$visits',
					`visits_t`='$visits_t',
					`visits_m`='$visits_m',
					`visits_a`='$visits_a',
					`visits_bs`='$visits_bs',
					`visits_you`='$visits_you',
					`vip_serf`='$vip_serf',
					`ban_serf`='$ban_serf',
					`youtube_serf`='$youtube_serf',
					`kon_board_m`='$kon_board_m',
					`referals`='$referals1',
					`referals2`='$referals2',
					`referals3`='$referals3',
					`konkurs_ads_task`='$konkurs_ads_task',					
					`refmoney`='$refmoney',
					`ref2money`='$ref2money',
					`ref3money`='$ref3money',					
					`money`='$money',
					`money_rb`='$money_rb',
					`money_out`='$money_out',
					`money_in`='$money_in',
					`money_rek`='$money_rek',
					`money_inv`='$money_inv',
					`user_status`='$user_status',
					`investor`='$investor', 
					`block_wmid`='$block_wmid',
					`block_agent`='$block_agent',
					`pass_oper`='$pass_oper', 
					`ym_purse`='$ym_purse', 
					`pm_purse`='$pm_purse', 
					`py_purse`='$py_purse', 
					`qw_purse`='$qw_purse', 					
					`ac_purse`='$ac_purse',
					`be_purse`='$be_purse',
					`mt_purse`='$mt_purse',
					`mg_purse`='$mg_purse',
					`tl_purse`='$tl_purse',
					`me_purse`='$me_purse',
					`vs_purse`='$vs_purse',
					`ms_purse`='$ms_purse'
                    $db_time_tab 
					$sex_tab 
					$fio_tab 
					$imay_tab
					$wm_tab
				 WHERE `id`='$id'") or die(mysql_error());

				echo '<span class="msg-ok">������������ <span style="color: #FFF;">'.$username.'</span> ������� ��������������!</span>';

				echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'")\', 2000); </script>';
				echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="2;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'"></noscript>';
			}
		}
	}

	$sql_e = mysql_query("SELECT * FROM `tb_users` WHERE `id`='$id'");
	if(mysql_num_rows($sql_e)>0) {
		$row = mysql_fetch_assoc($sql_e);

		echo '<h1 class="sp" style="margin-top:0; padding-top:0;"><b>�������������� ������������ <u>'.$row["username"].'</u></b></h1>';

		if( $row["attestat"]<100 && isset($row["wmid"]) && preg_match("|^[\d]{12}$|", trim($row["wmid"])) ) {
			require_once($_SERVER['DOCUMENT_ROOT']."/auto_pay_req/wmxml.inc.php");
			$r =  (isset($row["wmid"]) && preg_match("|^[\d]{12}$|", trim($row["wmid"])) ) ? _WMXML11($row["wmid"]) : "ERROR";
			$attkod = (isset($r) && $r!="ERROR") ? $r['att'] : $row["attestat"];

			mysql_query("UPDATE `tb_users` SET `attestat`='$attkod' WHERE `id`='$id'") or die(mysql_error());

			$attestat = isset($attestat[$attkod]) ? $attestat[$attkod] : '<span style="color:red;">�������� �� ���������</span>';
		}else{
			$attestat = isset($attestat[$row["attestat"]]) ? $attestat[$row["attestat"]] : '<span style="color:red;">�������� �� ���������</span>';
		}

		if(!preg_match("|^[\d]{12}$|", trim($row["wmid"]))) $attestat = 0;
		
		echo '<table class="tables_inv">';
	echo '<thead><tr align="center"><th align="center" colspan="2">������</th></tr></thead>';
	echo '<tr>';
		echo '<td align="center" width="80" height="80"><div id="uploadButton" class="button"><div id="avatar2"><img class="avatar" src="/avatar/'.$row["avatar"].'" width="80" height="80" border="0" align="middle" alt="" /></div></div></td>';
		echo '<td align="left" valign="top"><div align="justify">';
			echo '<b>�������� �� �������, ����� �������� ���!</b><br>';
			echo '�������� ��� ������� �� ������ ��������� 200x200 ��������, � ���� �� ����� 100 ��������. ����������� ������ 60x60 ��������. ����������� �������� ��������� �����: gif, jpg, jpeg, png. ��� ������� ����� ��������� � �������� 80�80 ��������.';
		echo '</div></td>';
	echo '</tr>';
	echo '</table>';

		echo '<form method="post" action="" id="newform">';
			echo '<table class="adv-cab">';
			echo '<tr><td width="50%" valign="top" style="margin:0 auto; padding:0 2px 0 0; border:none; background:none;">';
				echo '<table class="tables" style="margin:0px; padding:0px;">';
				echo '<tr><th>��������</th><th width="240">��������</th></tr>';
				echo '<tr><td><b>Id:</b></td><td><input type="hidden" name="id" value="'.$row["id"].'">'.$row["id"].'</td></tr>';
				echo '<tr><td><b>�����:</b></td><td><input type="hidden" name="username" value="'.$row["username"].'"><b>'.$row["username"].'</b></td></tr>';
				echo '<tr><td><b>������:</b></td><td><input type="text" class="ok" name="password" value="'.$row["password"].'"></td></tr>';
				echo '<tr><td><b>������ ��� ��������:</b></td><td><input type="text" class="ok" name="pass_oper" value="'.$row["pass_oper"].'"></td></tr>';
				echo '<tr><td><b>E-mail:</b></td><td><input type="text" class="ok" name="email" value="'.$row["email"].'"></td></tr>';

				echo '<tr><td><b>E-mail �����������?</b></td><td><select name="email_ok">';
					echo '<option value="0" '.("0" == $row["email_ok"] ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $row["email_ok"] ? 'selected="selected"' : false).'>��</option>';
				echo '</select></td></tr>';
				echo '<tr>';
		echo '<td align="left" nowrap="nowrap">���</td>';
		//echo '<td width="200">'.$username.'</td>';
		if($row["imay"]==false) {echo '<td width="200"><input type="text" name="imay" maxlength="160" value="" class="ok"></td>';}else{echo '<td width="200">'.$row["imay"].'</td>';}
		//echo '<td align="left">���� ������� ����� ����������� �� ����� � � ������������� � ��������!</td>';
	echo '</tr>';
				echo '<tr>';
		echo '<td align="left" nowrap="nowrap">���</td>';
		echo '<td colspan="2">';
			if($row["fio"]==false) {echo '<input type="text" name="fio" maxlength="160" value="" class="ok">';}else{echo $row["fio"];}
		echo '</td>';
	echo '</tr>';
	
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">���</td>';
		echo '<td>';
			if($row["sex"]>0 && $row["sex"]==1) {
				echo '�������';
			}elseif($row["sex"]>0 && $row["sex"]==2) {
				echo '�������';
			}else{
				echo '<select name="sex" style="width:auto">';
					echo '<option value="0">�����....</option>';
					echo '<option value="1">�������</option>';
					echo '<option value="2">�������</option>';
				echo '</select>';
			}
		echo '</td>';
		//echo '<td align="left"></td>';
	echo '</tr>';
	echo '<tr>';
		echo '<td align="left" nowrap="nowrap">���� ��������:</td>';
		echo '<td nowrap="nowrap">';
			$m_arr = array("������","�������","�����","������","���","����","����","�������","��������","�������","������","�������");

			//if($row_user["dbdate"]!=0) {
			//	echo DATE("d",$row_user["dbdate"])." ".$m_arr[DATE("m",$row_user["dbdate"])-1]." ".DATE("Y",$row_user["dbdate"])."�.";
			//}else{
				echo '<select name="db_d" style="width:auto;">';
					echo '<option value="">�����....</option>';
					for($i=1; $i<=31; $i++){
						echo '<option value="'.$i.'" '.(intval(DATE("d", $row["db_time"]))==intval($i) ? 'selected="selected"': false).'>'.$i.'</option>';
					}
				echo '</select>&nbsp;';
				echo '<select name="db_m" style="width:auto;">';
					echo '<option value="">�����....</option>';
					for($i=1; $i<=12; $i++){
						echo '<option value="'.$i.'" '.(intval(DATE("m", $row["db_time"]))==intval($i) ? 'selected="selected"': false).'>'.$m_arr[$i-1].'</option>';
					}
				echo '</select>&nbsp;';
				echo '<select name="db_y" style="width:auto;">';
					echo '<option value="">�����....</option>';
					for($i=(DATE("Y")-5); $i>=(DATE("Y")-92); $i--){
						echo '<option value="'.$i.'" '.(intval(DATE("Y", $row["db_time"]))==intval($i) ? 'selected="selected"': false).'>'.$i.'</option>';
					}
				echo '</select>';
			//}
		echo'</td>';
		//echo '<td align="left"></td>';
	echo '</tr>';

				echo '<tr>';
					echo '<td><img src="/img/wm16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />����� <span style="color:#006699; font-weight:bold;">WebMoney</span> [WMID]</td>';
					echo '<td>';
						//echo '<input type="text" class="ok" name="wmid" value="'.$row["wmid"].'" />';
						echo '<b>'.$row["wmid"].'</b>';
						if(isset($row["wmid"]) && $row["wmid"]!=false) echo ' <a href="https://passport.webmoney.ru/asp/certview.asp?wmid='.$row["wmid"].'" target="_blank">'.$attestat.'</a>';
					echo '</td>';
				echo '</tr>';
				echo '<tr><td><img src="/img/wmr16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />����� ����� <span style="color:#006699; font-weight:bold;">WebMoney</span> [WMR]</td><td><input type="text" class="ok" name="wm_purse" value="'.$row["wm_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/yandexmoney16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />����� ����� <span style="color:#006699; font-weight:bold;"><span style="color:#DE1200">�</span>�����.������</span></td><td><input type="text" class="ok" name="ym_purse" value="'.$row["ym_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/perfectmoney16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />����� ����� <span style="color:#DE1200; font-weight:bold;">PerfectMoney</span></td><td><input type="text" class="ok" name="pm_purse" value="'.$row["pm_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/payeer16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />����� ����� <span style="color:#000; font-weight:bold;">PAY</span><span style="color:#3498DB; font-weight:bold;">EER</span></td><td><input type="text" class="ok" name="py_purse" value="'.$row["py_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/qiwi16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#FC8000; font-weight:bold;">QIWI �������</span></td><td><input type="text" class="ok" name="qw_purse" value="'.$row["qw_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/be16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">����� ��������</span> <span style="color:#0f1010">��</span><span style="color:#e0b806">��</span><span style="color:#0f1010">��</span></td><td><input type="text" class="ok" name="be_purse" value="'.$row["be_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/mt16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">����� ��������</span> <span style="color:#ff0000; font-weight:bold;">���</span></td><td><input type="text" class="ok" name="mt_purse" value="'.$row["mt_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/mg16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">����� ��������</span> <span style="color:#049C64">����</span><span style="color:#00649e">���</span></td><td><input type="text" class="ok" name="mg_purse" value="'.$row["mg_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/tl16x16.png" width="20px" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#006699; font-weight:bold;">����� ��������</span> TELE2</span></td><td><input type="text" class="ok" name="tl_purse" value="'.$row["tl_purse"].'"></td></tr>';

				echo '<tr><td><img src="/img/me16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#22bdf1">Mae</span><span style="color:#ff0000">stro</span></td><td><input type="text" class="ok" name="me_purse" value="'.$row["me_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/vs16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#1c0cf5">VISA</span></td><td><input type="text" class="ok" name="vs_purse" value="'.$row["vs_purse"].'"></td></tr>';
				echo '<tr><td><img src="/img/ms16x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" /><span style="color:#ff0000">Master</span><span style="color:#ff9b00">Card</span></td><td><input type="text" class="ok" name="ms_purse" value="'.$row["ms_purse"].'"></td></tr>';

				$nbsp_ac = " ";
				$row["ac_purse"] = substr($row["ac_purse"], 0, 1).$nbsp_ac.substr($row["ac_purse"], 1, 4).$nbsp_ac.substr($row["ac_purse"], 5, 4).$nbsp_ac.substr($row["ac_purse"], 9, 4);
				echo '<tr><td><img src="/img/advcash_18x16.png" alt="" title="" border="0" align="absmiddle" style="margin:0px; padding:3px 6px 3px 0px;" />����� ����� <span style="color:#0F2D38">Adv</span><span style="color:#049C64">Cash</span></td><td><input type="text" class="ok" name="ac_purse" value="'.trim($row["ac_purse"]).'"></td></tr>';

				echo '<tr><td><b>������� 1��.:</b></td><td><input type="text" class="ok" name="referer" value="'.$row["referer"].'"></td></tr>';
				echo '<tr><td><b>������� 2��.:</b></td><td><input type="text" class="ok" name="referer2" value="'.$row["referer2"].'"></td></tr>';
				echo '<tr><td><b>������� 3��.:</b></td><td><input type="text" class="ok" name="referer3" value="'.$row["referer3"].'"></td></tr>';
				//echo '<tr><td><b>������� IV��.:</b></td><td><input type="text" class="ok" name="referer4" value="'.$row["referer4"].'"></td></tr>';
				//echo '<tr><td><b>������� V��.:</b></td><td><input type="text" class="ok" name="referer5" value="'.$row["referer5"].'"></td></tr>';

				echo '<tr><td><b>���� � ������� ����� WmLogin:</b></td><td><select name="block_wmid" class="ok">';
					echo '<option value="0" '.("0" == $row["block_wmid"] ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $row["block_wmid"] ? 'selected="selected"' : false).'>��</option>';
				echo '</select></td></tr>';

				echo '<tr><td><b>�������������� ������ �������� �� IP/��������:</b></td><td><select name="block_agent" class="ok">';
					echo '<option value="0" '.("0" == $row["block_agent"] ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $row["block_agent"] ? 'selected="selected"' : false).'>��</option>';
				echo '</select></td></tr>';

				echo '<tr><td><b>������:</b></td><td><select name="user_status" class="ok">';
					echo '<option value="0" '.("0" == $row["user_status"] ? 'selected="selected"' : false).'>������������</option>';
					echo '<option value="2" '.("2" == $row["user_status"] ? 'selected="selected"' : false).'>���������</option>';
					echo '<option value="1" '.("1" == $row["user_status"] ? 'selected="selected"' : false).'>�������������</option>';
				echo '</select></td></tr>';

				echo '<tr><td><b>������ ���������:</b></td><td><select name="investor" class="ok">';
					echo '<option value="0" '.("0" == $row["investor"] ? 'selected="selected"' : false).'>���</option>';
					echo '<option value="1" '.("1" == $row["investor"] ? 'selected="selected"' : false).'>��������</option>';
				echo '</select></td></tr>';

				echo '</table>';
			echo '</td>';

			echo '<td width="50%" valign="top" style="margin:0 auto; padding:0 0 0 2px; border:none; background:none;">';
				echo '<table class="tables" style="margin:0px; padding:0px;">';
				echo '<tr><th>��������</th><th width="125">��������</th></tr>';
	                        echo '<tr><td>�������:</td><td><input type="text" class="ok12" name="reiting" value="'.$row["reiting"].'"></td></tr>';
	                        echo '<tr><td>��������� �����������:</td><td><input type="text" class="ok12" name="rep_task" value="'.round($row["rep_task"],2).'"></td></tr>';
				echo '<tr><td>�������� I��:</td><td><input type="text" class="ok12" name="referals" value="'.$row["referals"].'"></td></tr>';
				echo '<tr><td>�������� II��:</td><td><input type="text" class="ok12" name="referals2" value="'.$row["referals2"].'"></td></tr>';
				echo '<tr><td>�������� III��:</td><td><input type="text" class="ok12" name="referals3" value="'.$row["referals3"].'"></td></tr>';
				
				echo '<tr><td>���������� �������� 1��.:</td><td><input type="text" class="ok12" name="refmoney" value="'.$row["refmoney"].'"></td></tr>';
				echo '<tr><td>���������� �������� 2��.:</td><td><input type="text" class="ok12" name="ref2money" value="'.$row["ref2money"].'"></td></tr>';
				echo '<tr><td>���������� �������� 3��.:</td><td><input type="text" class="ok12" name="ref3money" value="'.$row["ref3money"].'"></td></tr>';
				
				echo '<tr><td>����������� ������ � ��������:</td><td><input type="text" class="ok12" name="visits" value="'.$row["visits"].'"></td></tr>';
				echo '<tr><td>����������� ������������ � <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>:</td><td><input type="text" class="ok12" name="visits_you" value="'.$row["visits_you"].'"></td></tr>';
				echo '<tr><td>����������� ������ � ����-��������:</td><td><input type="text" class="ok12" name="visits_a" value="'.$row["visits_a"].'"></td></tr>';
				echo '<tr><td>����������� ������ � ��������� ��������:</td><td><input type="text" class="ok12" name="visits_bs" value="'.$row["visits_bs"].'"></td></tr>';
				echo '<tr><td>����������� �����:</td><td><input type="text" class="ok12" name="visits_m" value="'.$row["visits_m"].'"></td></tr>';
				echo '<tr><td>��������� �������:</td><td><input type="text" class="ok12" name="visits_t" value="'.$row["visits_t"].'"></td></tr>';
				echo '<tr><td>VIP-����:</td><td><input type="text" class="ok12" name="vip_serf" value="'.$row["vip_serf"].'"></td></tr>';
				echo '<tr><td>��������� VIP-����:</td><td><input type="text" class="ok12" name="ban_serf" value="'.$row["ban_serf"].'"></td></tr>';
				echo '<tr><td><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> VIP ���������:</td><td><input type="text" class="ok12" name="youtube_serf" value="'.$row["youtube_serf"].'"></td></tr>';
                echo '<tr><td>������� �� ��������:</td><td><input type="text" class="ok12" name="konkurs_ads_task" value="'.$row["konkurs_ads_task"].'"></td></tr>';
				echo '<tr><td>���������� �������:</td><td><input type="text" class="ok12" name="money_in" value="'.$row["money_in"].'"></td></tr>';
				echo '<tr><td>��������� �� �������:</td><td><input type="text" class="ok12" name="money_rek" value="'.$row["money_rek"].'"></td></tr>';
				echo '<tr><td>����������� �� ����� ������:</td><td><input type="text" class="ok12" name="kon_board_m" value="'.$row["kon_board_m"].'"></td></tr>';

				echo '<tr><td>�� �������� �����:</td><td><input type="text" class="ok12" name="money" value="'.$row["money"].'"></td></tr>';
				echo '<tr><td>�� ��������� �����:</td><td><input type="text" class="ok12" name="money_rb" value="'.$row["money_rb"].'"></td></tr>';
				echo '<tr><td>�� ������� ���������:</td><td><input type="text" class="ok12" name="money_inv" value="'.$row["money_inv"].'"></td></tr>';
				echo '<tr><td>���������:</td><td><input type="text" class="ok12" name="money_out" value="'.$row["money_out"].'"></td></tr>';
				echo '</table>';
			echo '</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td valign="top" colspan="3" style="margin:0 auto; padding:0; border:none; background:none;">';
				echo '<fieldset><legend>��������� ����������</legend>';
					echo 'IP ������ ��� �����������: <b>'.$row["ip"].'</b><br />';
					echo 'IP ������ ���������� �����: '.($row["lastiplog"]!=false ? '<b>'.$row["lastiplog"].'</b>' : '<b style="color:#FF0000;">���</b>').'<br>';
					echo '���� �����������: <b>'.DATE("d.m.Y�. � H:i:s",$row["joindate2"]).'</b><br />';
					echo '���� ���������� ������: '.($row["lastlogdate2"]!=false ? '<b>'.DATE("d.m.Y�. � H:i:s", $row["lastlogdate2"]).'</b>' : '<b style="color:#FF0000;">���</b>').'<br>';
				echo '</fieldset>';
			echo '</td>';
			echo '</tr>';
			echo '<tr><td valign="top" colspan="3" align="center" style="margin:0 auto; padding:5px 0; border:none; background:none;"><input type="submit" value="C��������" class="sub-blue160"></td></tr>';
			echo '</table>';
			echo '</form>';

			$perpage_h = 50;
			$sql_p_h = mysql_query("SELECT `id` FROM `tb_history` WHERE `user`='".$row["username"]."'");
			$count_h = mysql_numrows($sql_p_h);
			$pages_count_h = ceil($count_h / $perpage_h);
			$page_h = (isset($_GET["page_h"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page_h"]))) ? intval(trim($_GET["page_h"])) : "1";
			if ($page_h > $pages_count_h | $page_h<0) $page_h = $pages_count_h;
			$start_pos_h = ($page_h - 1) * $perpage_h;
			if($start_pos_h<0) $start_pos_h = 0;

			echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������� �������� ������������ '.$row["username"].'</b></h3>';

			if($count_h>$perpage_h) {echo "<br>"; universal_link_bar($count_h, $page_h, $_SERVER['PHP_SELF'], $perpage_h, 10, '&page_h=', "?op=$op&page=$page&option=edit&id=$id");}
			echo '<table class="tables">';
			echo '<thead><tr>';
				echo '<th class="top">�</th>';
				echo '<th class="top">����</th>';
				echo '<th class="top">�����</th>';
				echo '<th class="top">������</th>';
				echo '<th class="top">���������</th>';
			echo '</tr></thead>';

			$sql = mysql_query("SELECT * FROM `tb_history` WHERE `user`='".$row["username"]."' ORDER BY `id` DESC LIMIT $start_pos_h,$perpage_h");
			if(mysql_num_rows($sql)>0) {
				while ($row = mysql_fetch_array($sql)) {
					echo '<tr align="center">';
						echo '<td>'.$row["id"].'</td>';
						echo '<td>'.$row["date"].'</td>';
						echo '<td>'.number_format($row["amount"],2,".","'").'</td>';
						echo '<td>'.((strtolower($row["method"])=="webmoney" | strtolower($row["method"])=="yandexmoney" | strtolower($row["method"])=="perfectmoney" | strtolower($row["method"])=="payeer" | strtolower($row["method"])=="qiwi" | strtolower($row["method"])=="mobile" | strtolower($row["method"])=="sberbank" | strtolower($row["method"])=="paypal" | strtolower($row["method"])=="advcash") ? "������� �� ".(strtolower($row["method"])!="mobile" ? "��������� �������" : false)." <b>".str_ireplace("Mobile", "��������� �������", str_ireplace("SberBank", "��������", $row["method"]))."</b>" : $row["method"]).'</td>';
						echo '<td>';
							if($row["tipo"]==0 && $row["status_pay"]==0 && $row["status"]==false) {
								echo "������� ���������";
							}elseif($row["status"]!=false){
								echo $row["status"];
							}elseif($row["status_pay"]==1){
								echo "���������";
							}elseif($row["status_pay"]=="2") {
								echo '���������� �� ������';
							}
						echo '</td>';
					echo '</tr>';
				}
			}else{
				echo '<tr align="center"><td colspan="5">������� �� �������!</td></tr>';
			}
			echo '</table>';
			if($count_h>$perpage_h) {echo "<br>"; universal_link_bar($count_h, $page_h, $_SERVER['PHP_SELF'], $perpage_h, 10, '&page_h=', "?op=$op&page=$page&option=edit&id=$id");}
			echo "<br><br>";

	}else{
		echo '<br><span class="msg-error">������������ �� ������.</span><br><br>';
	}
	exit();
}


if(isset($_GET["option"]) && limpiar($_GET["option"])=="dell") {
	mysql_query("DELETE FROM `tb_users` WHERE `id`='$id'") or die(mysql_error());
	echo '<br><span class="msg-error">������������ ������.</span><br>';
	echo '<script type="text/javascript"> setTimeout(\'location.replace("'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'")\', 3000); </script>';
	echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="3;URL='.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'"></noscript>';
}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ ������������� �������</b></h3>';

$sql = mysql_query("SELECT * FROM `tb_users` $_WHERE_SEARCH ".($_ORDER_SORT!=false ? $_ORDER_SORT : "ORDER BY `id` ASC")." LIMIT $start_pos, $perpage");
$all_users = mysql_num_rows($sql);

############### ����� ������ ###############
echo '<table class="tables" style="margin:0 auto; padding:0; width:100%;">';
echo '<tbody>';
echo '<tr>';
	echo '<td align="left" style="text-shadow:0 1px 0 #FFF,1px 1px 1px #AAA; font: 12px Tahoma, Arial, sans-serif; line-height:1.3em;">';
		if(isset($_WHERE_SEARCH) && $_WHERE_SEARCH!==false) {
		 	echo '������� �������������: <b>'.number_format($count,0,"."," ").'</b><br>�������� ������� �� ��������: <b>'.$all_users.'</b> �� <b>'.number_format($count,0,"."," ").'</b>';
		}else{
			echo '������������� �����: <b>'.number_format($count,0,"."," ").'</b><br>�������� ������� �� ��������: <b>'.$all_users.'</b> �� <b>'.number_format($count,0,"."," ").'</b>';
		}
	echo '</td>';
	if( (isset($_WHERE_SEARCH) && $_WHERE_SEARCH!==false) | (isset($_ORDER_SORT) && $_ORDER_SORT!==false) ) {
		echo '<td align="right" width="100">';
			if(isset($_WHERE_SEARCH) && $_WHERE_SEARCH!==false) echo '<div id="ResetSearch" class="sub-red" onclick="deleteCookie(\'WHERE_USERS_ARR\', \'\', \''.$_SERVER["HTTP_HOST"].'\'); location.replace(\'\'); return false;">����� ������</div>';
			if(isset($_ORDER_SORT) && $_ORDER_SORT!==false) echo '<div id="ResetSearch" class="sub-red" onclick="deleteCookie(\'SORT_USERS_ARR\', \'\', \''.$_SERVER["HTTP_HOST"].'\'); location.replace(\'\'); return false;">����� �����-��</div>';
		echo '</td>';
	}
echo '</tr>';
echo '</tbody>';
echo '</table>';

echo '<span id="adv-title-1" class="adv-title-'.( (isset($_COOKIE["MENU_USERS_SEARSH"]) && filter_var($_COOKIE["MENU_USERS_SEARSH"], FILTER_VALIDATE_INT)) ? "open" : "close").'" onclick="ShowHideSearch(1);">����� �������������</span>';
echo '<div id="adv-block-1" style="display:'.( (isset($_COOKIE["MENU_USERS_SEARSH"]) && filter_var($_COOKIE["MENU_USERS_SEARSH"], FILTER_VALIDATE_INT)) ? "block" : "none").'; margin:0 auto; padding:0;">';
	echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'" id="newform" autocomplete="off">';
	echo '<table class="tables" style="margin:0 auto; padding:0; width:100%;">';
	echo '<tbody>';
	for($i=1; $i<=$param_count; $i++) {
		echo '<tr>';
			echo '<td style="border-right:none; padding:2px 2px 3px 5px; font-weight:bold; width:auto;">'.$i.'-� �������� ������ ��:</td>';
			echo '<td align="center" style="border-left:none; border-right:none; padding:2px 2px 3px 2px; width:auto;">';
				echo '<select name="param['.$i.']" style="width:100%; height:26px;">';
				for($y=0; $y<count($param_key_arr); $y++) {
					if($i>1 | $param_key_arr[$y]!="false") {
						echo '<option value="'.$param_key_arr[$y].'" '.($param_key_arr[$y]==$param_post[$i] ? 'selected="selected"' : false).'>'.$param_val_arr[$y].'</option>';
					}
				}
				echo '</select>';
			echo '</td>';
			echo '<td align="center" style="border-left:none; border-right:none; padding:2px 2px 3px 2px; width:auto;">';
				echo '<select name="operator['.$i.']" style="width:100%; height:26px;">';
				for($y=0; $y<count($oper_arr); $y++) {
					if($oper_key_arr[$y]!="false") echo '<option value="'.$oper_key_arr[$y].'" '.($oper_key_arr[$y]==$operator_post[$i] ? 'selected="selected"' : false).'>'.$oper_val_arr[$y].'</option>';
				}
				echo '</select>';
			echo '</td>';
			echo '<td align="center" style="border-left:none; padding:2px 7px 2px 2px;"><input type="text" class="ok" name="search_val['.$i.']" value="'.$search_post[$i].'" placeholder="������� �������� ��� ������" /></td>';
			if($i==1) {
				echo '<td align="center" rowspan="'.($param_count*2-1).'" width="100">';
					echo '<input type="submit" value="�����" class="sub-green" style="float:none;">';
				echo '</td>';
			}
		echo '</tr>';
		if($i!=$param_count) {
			echo '<tr>';
				echo '<td colspan="4" align="center" style="padding:2px 2px 3px 2px;">';
					echo '<select name="and_or['.$i.']" style="width:100px; height:26px;">';
					for($y=0; $y<count($and_or_arr); $y++) {
						echo '<option value="'.$and_or_key_arr[$y].'" '.($and_or_key_arr[$y]==$and_or_post[$i] ? 'selected="selected"' : false).'>'.$and_or_val_arr[$y].'</option>';
					}
					echo '</select>';
				echo '</td>';
			echo '</tr>';
		}
	}
	echo '</tbody>';
	echo '</table>';
	echo '</form>';
echo '</div>';
############### ����� ������ ###############

############### ���������� ������ ###############
echo '<span id="adv-title-2" class="adv-title-'.( (isset($_COOKIE["MENU_USERS_SORT"]) && filter_var($_COOKIE["MENU_USERS_SORT"], FILTER_VALIDATE_INT)) ? "open" : "close").'" onclick="ShowHideSort(2);">���������� �������������</span>';
echo '<div id="adv-block-2" style="display:'.( (isset($_COOKIE["MENU_USERS_SORT"]) && filter_var($_COOKIE["MENU_USERS_SORT"], FILTER_VALIDATE_INT)) ? "block" : "none").'; margin:0 auto; padding:0;">';
	echo '<form method="post" action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).(isset($_GET["page"]) ? "&page=".intval($_GET["page"]) : false).'" id="newform" autocomplete="off">';
	echo '<table class="tables" style="margin:0 auto; padding:0; width:100%;">';
	echo '<tbody>';
	echo '<tr>';
		echo '<td style="border-right:none; padding:2px 2px 3px 5px; font-weight:bold; width:140px;">���������� ��:</td>';
		echo '<td align="center" style="border-left:none; border-right:none; padding:2px 2px 3px 2px; width:auto;">';
			echo '<select name="sort" style="width:100%; height:26px;">';
			for($y=0; $y<count($order_key_arr); $y++) {
				echo '<option value="'.$order_key_arr[$y].'" '.($order_key_arr[$y]==$sort_post ? 'selected="selected"' : false).'>'.$order_val_arr[$y].'</option>';
			}
			echo '</select>';
		echo '</td>';
		echo '<td align="center" style="border-left:none; padding:2px 2px 3px 2px; width:auto;">';
			echo '<select name="asc_desc" style="width:100%; height:26px;">';
			for($y=0; $y<count($asc_desc_arr); $y++) {
				if($asc_desc_key_arr[$y]!="false") echo '<option value="'.$asc_desc_key_arr[$y].'" '.($asc_desc_key_arr[$y]==$asc_desc_post ? 'selected="selected"' : false).'>'.$asc_desc_val_arr[$y].'</option>';
			}
			echo '</select>';
		echo '</td>';
		echo '<td align="center" style="padding:2px 7px 2px 2px; width:100px;">';
			echo '<input type="submit" value="�����������" class="sub-green" style="float:none;">';
		echo '</td>';
	echo '</tr>';
	echo '</tbody>';
	echo '</table>';
	echo '</form>';
echo '</div>';
############### ���������� ������ ###############

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");

echo '<table class="tables" style="margin:2px auto; padding:0;">';
echo '<thead><tr align="center">';
	echo '<th colspan="2">������������</th>';
	echo '<th>��������</th>';
	echo '<th>�������</th>';
	echo '<th>���.����<br>����.����</th>';
	echo '<th>���������<br>���������</th>';
	echo '<th>�������</th>';
	echo '<th>IP ���-��<br>IP �����</th>';
	echo '<th>��� IP<br>[���������&nbsp;10]</th>';
	echo '<th>���� ���-��<br>���� �����</th>';
	echo '<th>��������</th>';
echo '</tr></thead>';

echo '<tbody>';
if($all_users>0) {
	while ($row = mysql_fetch_assoc($sql)) {
		echo '<tr align="center">';

			echo '<td align="center" valign="top" width="70px" style="border-right:none; padding-right:2px;">';
				echo '<img class="avatar" src="/avatar/'.$row["avatar"].'" border="0" alt="avatar" title="avatar" /><br>';
				echo '<b>'.my_status($row["reiting"]).'</b><br>';
			echo '</td>';

			echo '<td align="left" valign="top" style="border-left:none; padding-left:2px; padding-top:7px;">';
				echo 'ID: <b style="cursor:pointer;" title="�����">'.$row["id"].'</b><br>';
				echo '�����: <b style="cursor:pointer;" title="�����">'.$row["username"].'</b><br>';

				echo '<img src="/img/reiting.png" border="0" alt="" align="absmiddle" title="�������" style="margin:0; padding:0; margin-right:3px;" /><span class="'.($row["reiting"]==0 ? "text-gray" : ($row["reiting"]>0 ? "text-green" : "text-red")).'">'.round($row["reiting"],2).'</span><br>';
				echo '<img src="/img/task_16x16.png" border="0" alt="" title="��������� �����������" style="margin:0; padding:0; margin-right:4px;" align="absmiddle" /><span class="'.($row["rep_task"]==0 ? "text-gray" : ($row["rep_task"]>0 ? "text-green" : "text-red")).'">'.($row["rep_task"]>0 ? "+" : false).$row["rep_task"].'</span><br>';

				echo 'E-mail:&nbsp;<b '.($row["email_ok"]!=1 ? 'style="color:#FF0000;"' : false).'>'.$row["email"].'</b><br>';

				if(trim($row["wmid"])!=false) {
					echo 'WMID:&nbsp;<b>'.$row["wmid"].'</b><br>';
					echo 'WMR:&nbsp;&nbsp;<b>'.$row["wm_purse"].'</b><br>';
					if(isset($attestat[$row["attestat"]])) echo '<img src="/img/att/att_'.@$row["attestat"].'.ico" alt="" width="20" height="20" align="absmiddle" border="0" /> ';
				}
				if($row["country_cod"]!="") {
					echo '<img src="//'.$_SERVER["HTTP_HOST"].'/img/flags/'.@strtolower($row["country_cod"]).'.gif" alt="" title="'.get_country($row["country_cod"]).'" width="16" height="11" style="margin:3px 0px; padding:0;" align="absmiddle" /><br>';
				}
				if(trim($row["http_ref"])!=false) {
					echo '������ � '.$row["http_ref"].'<br>';
				}

				echo '<b id="bantext'.$row["id"].'" style="color:#FF0000;">'.($row["ban_date"]>0 ? "������������" : false).'</b>';
			echo '</td>';

			echo '<td><b>'.$row["referals"].'</b> - <b>'.$row["referals2"].'</b> - <b>'.$row["referals3"].'</b></td>';

			echo '<td>';
				if($row["referer"] != false) {
					echo '<b>'.$row["referer"].'</b><br>';
					echo '<b>'.$row["referer2"].'</b><br>';
					echo '<b>'.$row["referer3"].'</b><br>';
					//echo '<b>'.$row["referer4"].'</b><br>';
					//echo '<b>'.$row["referer5"].'</b>';
				}else{
					echo '-';
				}
			echo '</td>';

			echo '<td><b style="color:#0000EE;">'.number_format($row["money"],4,".","`").'</b><br><br><b style="color:#00688B;">'.number_format($row["money_rb"],2,".","`").'</b></td>';
			echo '<td><b style="color:#0000EE;">'.number_format($row["money_out"],2,".","`").'</b><br><br><b style="color:#00688B;">'.number_format($row["money_in"],2,".","`").'</b></td>';
			echo '<td><b style="color:#00688B;">'.number_format($row["money_rek"],2,".","`").'</b></td>';

			echo '<td>';
				$ip_reg_exp = explode(".", $row["ip"]);
				$ip_reg_1 = isset($ip_reg_exp[0]) ? $ip_reg_exp[0] : false;
				$ip_reg_2 = isset($ip_reg_exp[1]) ? $ip_reg_exp[1] : false;
				$ip_reg_3 = isset($ip_reg_exp[2]) ? $ip_reg_exp[2] : false;
				$ip_reg_4 = isset($ip_reg_exp[3]) ? $ip_reg_exp[3] : false;

				$ip_last_exp = explode(".", $row["lastiplog"]);
				$ip_last_1 = isset($ip_last_exp[0]) ? $ip_last_exp[0] : false;
				$ip_last_2 = isset($ip_last_exp[1]) ? $ip_last_exp[1] : false;
				$ip_last_3 = isset($ip_last_exp[2]) ? $ip_last_exp[2] : false;
				$ip_last_4 = isset($ip_last_exp[3]) ? $ip_last_exp[3] : false;

				$color_red = ' style="color:#FF0000;"'; $color_green = ' style="color:#008B00;"';

				if($row["ip"]=="") {
					echo "-";
				}else{
					echo '<b'.$color_green.'>'.$row["ip"].'</b>';
				}
				echo '<br><br>';

				if($row["lastiplog"]=="") {
					echo "-";
				}else{
					$color_1 = $color_green; $color_2 = $color_green; $color_3 = $color_green; $color_4 = $color_green;

					if($ip_reg_1!=$ip_last_1) {
						$color_1 = $color_red; $color_2 = $color_red; $color_3 = $color_red; $color_4 = $color_red;
					}elseif($ip_reg_2!=$ip_last_2) {
						$color_2 = $color_red; $color_3 = $color_red; $color_4 = $color_red;
					}elseif($ip_reg_3!=$ip_last_3) {
						$color_3 = $color_red; $color_4 = $color_red;
					}elseif($ip_reg_4!=$ip_last_4) {
						$color_4 = $color_red;
					}

					echo '<b'.$color_1.'>'.$ip_last_1.'</b><b'.$color_2.'>.'.$ip_last_2.'</b><b'.$color_3.'>.'.$ip_last_3.'</b><b'.$color_4.'>.'.$ip_last_4.'</b>';
				}
			echo '</td>';

			echo '<td>';
				$LogIp = array_unique(explode(", ", $row["log_ip_aut"]));
				echo implode("<br>", $LogIp);
			echo '</td>';


			echo '<td>';
				if(DATE("d.m.Y", $row["joindate2"])==DATE("d.m.Y", time())) {
					echo '<b style="color:#008B00">�������, � '.DATE("H:i", $row["joindate2"]).'</b>';
				}elseif(DATE("d.m.Y", $row["joindate2"])==DATE("d.m.Y", (time()-24*60*60))) {
					echo '<b style="color:#528B8B">�����, � '.DATE("H:i", $row["joindate2"]).'</b>';
				}else{
					echo '<b>'.DATE("d.m.Y�. H:i", $row["joindate2"]).'</b>';
				}
				echo '<br><br>';

				if($row["lastlogdate2"]==0 | $row["joindate2"]==$row["lastlogdate2"]) {
					echo '<b style="color:#FF0000;">���</b>';
				}elseif(DATE("d.m.Y", $row["lastlogdate2"])==DATE("d.m.Y", time())) {
					echo '<b style="color:#008B00">�������, � '.DATE("H:i", $row["lastlogdate2"]).'</b>';
				}elseif(DATE("d.m.Y", $row["lastlogdate2"])==DATE("d.m.Y", (time()-24*60*60))) {
					echo '<b style="color:#528B8B">�����, � '.DATE("H:i", $row["lastlogdate2"]).'</b>';
				}else{
					echo '<b>'.DATE("d.m.Y�. H:i", $row["lastlogdate2"]).'</b>';
				}
			echo '</td>';

			echo '<td width="100" nowrap="nowrap">';
				echo '<form method="get" action="">';
					echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
					echo '<input type="hidden" name="page" value="'.$page.'">';
					echo '<input type="hidden" name="option" value="edit">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="submit" value="�������������" class="sub-blue" style="float:none;">';
				echo '</form>';
				echo '<form method="get" action="" onClick=\'if(!confirm("�� ������� ��� ������ ������� ������������ '.$row["username"].'?")) return false;\'>';
					echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
					echo '<input type="hidden" name="page" value="'.$page.'">';
					echo '<input type="hidden" name="option" value="dell">';
					echo '<input type="hidden" name="id" value="'.$row["id"].'">';
					echo '<input type="submit" value="�������" class="sub-red" style="float:none;">';
				echo '</form>';
			echo '</td>';
		echo '</tr>';

		echo '<tr>';
			echo '<td align="left" colspan="12" style="border-top:none;">�������: <b>'.( (isset($row["agent"]) && trim($row["agent"])!=false) ? $row["agent"] : "��� �� ���������").'</b></td>';
		echo '</tr>';

	}
}else{
	echo '<tr>';
		echo '<td colspan="11" align="center" style="padding:0;"><b>������������� �� �������</b></td>';
	echo '</tr>';
}
echo '</tbody>';
echo '</table>';

if($count>$perpage) universal_link_bar($count, $page, $_SERVER['PHP_SELF'], $perpage, 10, '&page=', "?op=$op");

?>