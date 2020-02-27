<?php
$pagetitle = "Уведомления о заданиях";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для просмотра уведомлений необходимо авторизоваться!</span>';
}else{
	?>
	<script type="text/javascript" language="JavaScript">
		var tm;

		function HideMsg(id, timer) {
		        clearTimeout(tm);
			tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
			return false;
		}

		function DelNot(id) {
			if(confirm("Вы уверены что хотите удалить уведомление №"+id+" ?")) {
				$.ajax({
					type: "POST", url: "ajax/ajax_del_not.php?rnd="+Math.random(), 
					data: {'op':'DelNot', 'id':id}, 
					dataType: 'json',
					error: function() {
						$("#loading").slideToggle();
						alert("ОШИБКА AJAX!");
						return false;
					},
					beforeSend: function() { $("#loading").slideToggle(); }, 
					success: function(data) {
						$("#loading").slideToggle();
						var result = data.result ? data.result : data;
						var message = data.message ? data.message : data;
						if(result == "OK") {
							$("#idnot_"+id).remove();
							return false;
						}else{
							alert(message);
							return false;
						}
					}
				});
			}
		}
	</script>
	<?php

	require(ROOT_DIR."/config.php");
	require(ROOT_DIR."/navigator/navigator.php");

	$perpage = 50;
	$sql_p = mysql_query("SELECT `id` FROM `tb_ads_task_notif` WHERE `user_name`='$username'");
	$count = mysql_numrows($sql_p);
	$pages_count = ceil($count / $perpage);
	$page = (isset($_GET["page"]) && preg_match("|^[0-9\-]{1,11}$|", trim($_GET["page"]))) ? intval(trim($_GET["page"])) : "1";
	if ($page > $pages_count | $page<0) $page = $pages_count;
	$start_pos = ($page - 1) * $perpage;
	if($start_pos<0) $start_pos = 0;

	$SERVER_PAGE = isset($_SERVER["REDIRECT_URL"]) ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"];

	if($count>$perpage) universal_link_bar($count, $page, $SERVER_PAGE, $perpage, 10, '?page=', "");
	echo '<table class="tables" style="margin-top:10px;">';
	echo '<thead><tr align="center"><th colspan="2">Уведомление</th></tr></thead>';
	echo '<tbody>';

	$sql_task_not = mysql_query("SELECT * FROM `tb_ads_task_notif` WHERE `user_name`='$username' ORDER BY `id` DESC");
	if(mysql_num_rows($sql_task_not)>0) {
		while($row_tn = mysql_fetch_assoc($sql_task_not)) {
			echo '<tr id="idnot_'.$row_tn["id"].'">';
				echo '<td align="left" style="padding:5px;">';
					if($row_tn["type"]=="good") {
						$text_not = 'Рекламодатель '.$row_tn["rek_name"].' <span class="text-green">подтвердил</span> выполненное Вами задание [ID: '.$row_tn["ident"].']';
					}elseif($row_tn["type"]=="good_auto") {
						$text_not = 'Выполненное Вами задание [ID: '.$row_tn["ident"].'] <span class="text-green">подтверждено</span> автоматически системой';
					}elseif($row_tn["type"]=="dorab") {
						$text_not = 'Рекламодатель '.$row_tn["rek_name"].' отправил выполненное Вами задание [ID: '.$row_tn["ident"].'] <span class="text-orange">на доработку</span>.';
						if(trim($row_tn["message"])!=false) $text_not.= '<br>Причина: '.$row_tn["message"];
					}elseif($row_tn["type"]=="bad") {
						$text_not = 'Рекламодатель '.$row_tn["rek_name"].' <span class="text-red">отклонил</span> выполненное Вами задание [ID: '.$row_tn["ident"].'].';
						if(trim($row_tn["message"])!=false) $text_not.= '<br>Причина: '.$row_tn["message"];
					}
					echo $row_tn["status"]==1 ? "$text_not" : "<b>$text_not</b>";
				echo '</td>';
				echo '<td align="center" width="18"><span onClick="DelNot(\''.$row_tn["id"].'\')" class="workcomp" title="Удалить уведомление"></span></td>';
			echo '</tr>';
		}
	}else{
		echo '<tr align="center"><td><b>Новых уведомлений нет</b></td></tr>';
	}

	echo '</tbody>';
	echo'</table>';
	if($count>$perpage) universal_link_bar($count, $page, $SERVER_PAGE, $perpage, 10, '?page=', "");

	mysql_query("UPDATE `tb_ads_task_notif` SET `status`='1' WHERE `status`='0' AND `user_name`='$username'") or die(mysql_error());

}

include(ROOT_DIR."/footer.php");
?>