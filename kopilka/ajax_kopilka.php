<?php
@session_start();
error_reporting(E_ALL);
header('Content-type: text/html; charset=windows-1251');
require '' . $_SERVER['DOCUMENT_ROOT'] . '/config.php';
require '' . $_SERVER['DOCUMENT_ROOT'] . '/funciones.php';
sleep(0);

if (isset($_SESSION['userLog']) && isset($_SESSION['userPas'])) {
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_summa_in\'');
	$v_kopilke = p_floor(mysql_result($sql, 0, 0), 2);
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_cash_in\'');
	$kopilka_cash_in = mysql_result($sql, 0, 0);
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_cash_out\'');
	$kopilka_cash_out = mysql_result($sql, 0, 0);
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_reit_out\'');
	$kopilka_reit_out = mysql_result($sql, 0, 0);
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_period_out\'');
	$kopilka_period_out = mysql_result($sql, 0, 0);
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_min_click\'');
	$kopilka_min_click = mysql_result($sql, 0, 0);
	$sql = mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'reit_kop\'');
	$reit_kop = mysql_result($sql, 0, 0);

	$username = ((isset($_SESSION['userLog']) && preg_match('|^[a-zA-Z0-9\\-_-]{3,20}$|', trim($_SESSION['userLog'])) ? uc($_SESSION['userLog']) : false));

	$option = ((isset($_POST['op']) && preg_match('|^[a-zA-Z0-9\\-_]{3,20}$|', limpiar($_POST['op'])) ? limpiar($_POST['op']) : false));
	$laip = getRealIP();

	if ($option == 'kop_dll_coment') {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			$id_com = ((isset($_POST['id']) && preg_match('|^[\\d]{1,11}$|', intval(limpiar($_POST['id']))) ? intval(limpiar($_POST['id'])) : false));
			$sql_status = mysql_query('SELECT `id`,`user_status`,`forum_status` FROM `tb_users` WHERE `username`=\'' . $username . '\'');

			if (0 < mysql_num_rows($sql_status)) {
				$row_status = mysql_fetch_array($sql_status);
				$id_tab = $row_status['id'];
				$users_status = $row_status['user_status'];
				$forum_status = $row_status['forum_status'];

				if (($users_status == 1) | ($forum_status == 1) | ($forum_status == 2)) {
					$sql_check_com = mysql_query('SELECT `id` FROM `tb_kopilka_in` WHERE `id`=\'' . $id_com . '\'');

					if (0 < mysql_num_rows($sql_check_com)) {
						if (mysql_query('UPDATE `tb_kopilka_in` SET `comment`=\'' . "�" . ' ' . "�������" . ' ' . "��" . ' ' . "��������" . '\'  WHERE `id`=\'' . $id_com . '\'')) {
							exit('OK');
							return 1;
						}


						exit("������" . '! ' . "��" . ' ' . "�������" . ' ' . "����������" . ' ' . "������" . '!');
						return 1;
					}


					exit("������" . '! ' . "��" . ' ' . "�������" . ' ' . "����������" . ' ' . "������" . '!');
					return 1;
				}


				exit("��" . ' ' . "����������" . ' ' . "����" . ' ' . "���" . ' ' . "��������" . ' ' . "�����������" . '!');
				return 1;
			}


			exit('ERRORLOG');
			return 1;
		}


		exit("������" . '! ' . "��" . ' ' . "�������" . ' ' . "����������" . ' ' . "������" . '!');
		return 1;
	}


	if ($option == 'money_plus_us') {
		if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
			$sql_user = mysql_query('SELECT `id`,`username`,`reiting` FROM `tb_users` WHERE `username`=\'' . $username . '\'');

			if (0 < mysql_num_rows($sql_user)) {
				$row_user = mysql_fetch_assoc($sql_user);
				$user_id = $row_user['id'];
				$user_name = $row_user['username'];
				$user_reit = $row_user['reiting'];
				$sql_check_ban = mysql_query('SELECT `id` FROM `tb_black_users` WHERE `name`=\'' . $user_name . '\' LIMIT 1');
				$sql_check_time = mysql_query('SELECT `id` FROM `tb_kopilka_out` WHERE `time`>=\'' . (time() - ($kopilka_period_out * 60 * 60)) . '\' AND `username`=\'' . $user_name . '\' ORDER BY `id` DESC LIMIT 1');
				$sql_stat_us = mysql_query('SELECT `' . strtolower(DATE('D')) . '` FROM `tb_users_stat_copilka` WHERE `username`=\'' . $user_name . '\' AND `type`=\'serf_copilka\'');

				if (0 < mysql_num_rows($sql_stat_us)) {
					$row_stat_us = mysql_fetch_assoc($sql_stat_us);
					$user_click_day = $row_stat_us[strtolower(DATE('D'))];
				}
				 else {
					$user_click_day = 0;
				}

				if (0 < mysql_num_rows($sql_check_ban)) {
					exit("��" . ' ' . "��" . ' ' . "������" . ' ' . "��������" . ' ' . "�����" . ', ' . "���" . ' ' . "�������" . ' ' . "������������" . '.');
					return 1;
				}


				if ($user_reit < $kopilka_reit_out) {
					exit("���" . ' ' . "���������" . ' ' . "������" . ' ' . "���" . ' ' . "�����" . ' ' . "�����" . ' ' . $kopilka_reit_out . ' ' . "�" . ' ' . "�����" . ' ' . "��������" . '.');
					return 1;
				}


				if ($user_click_day < $kopilka_min_click) {
					exit("���" . ' ' . "���������" . ' ' . "������" . ' ' . "���" . ' ' . "����������" . ' ' . "�������" . ' ' . "��" . ' ' . "�����" . ' ' . count_text($kopilka_min_click, "������", "����", "�����", '') . ' ' . "�" . ' ' . "��������" . ' ' . "�" . ' ' . "����" . '.<br>' . "��" . ' ' . "�������" . ' ' . "��" . ' ' . "�������" . ' ' . count_text($user_click_day, "������", "����", "�����", '') . '.');
					return 1;
				}


				if ($v_kopilke < $kopilka_cash_out) {
					exit("�" . ' ' . "�������" . ' ' . "��" . ' ' . "����������" . ' ' . "�������" . ' ' . "���" . ' ' . "���������" . ' ' . "������");
					return 1;
				}


				if (0 < mysql_num_rows($sql_check_time)) {
					exit("��" . ' ' . "���" . ' ' . "��������" . ' ' . "�����" . ' ' . "��" . ' ' . "���������" . ' ' . count_text($kopilka_period_out, "�����", "���", "����", '') . ' ');
					return 1;
				}


				mysql_query('UPDATE `tb_users` SET `money`=`money`+\'' . $kopilka_cash_out . '\' WHERE `username`=\'' . $user_name . '\'');
				mysql_query('UPDATE `tb_config` SET `price`=`price`-\'' . $kopilka_cash_out . '\' WHERE `item`=\'kopilka_summa_in\'');
				mysql_query('UPDATE `tb_config` SET `price`=`price`+\'' . $kopilka_cash_out . '\', `howmany`=`howmany`+\'1\' WHERE `item`=\'kopilka_summa_out\'');
				mysql_query('INSERT INTO `tb_kopilka_out` (`date`,`time`,`username`,`money`,`ip`) ' . "\n\t\t\t\t\t" . 'VALUES(\'' . DATE('d.m.Y H:i') . '\',\'' . time() . '\',\'' . $user_name . '\',\'' . $kopilka_cash_out . '\',\'' . $laip . '\')') || exit(mysql_error());
				mysql_query('INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) ' . "\n\t\t\t\t\t" . 'VALUES(\'1\',\'' . $user_name . '\',\'' . $user_id . '\',\'' . DATE('d.m.Y H:i') . '\',\'' . time() . '\',\'' . $kopilka_cash_out . '\',\'' . "�����" . ' ' . "��" . ' ' . "�������" . '\',\'' . "���������" . '\',\'prihod\')') || exit(mysql_error());
				exit('OK');
				return 1;
			}


			exit('ERRORLOG');
			return 1;
		}


		exit("������" . '! ' . "��" . ' ' . "�������" . ' ' . "����������" . ' ' . "������" . '!');
		return 1;
	}


	if ($option == 'money_plus') {
		$moneyadd = ((isset($_POST['moneyadd']) ? round(abs(floatval(str_replace(',', '.', trim($_POST['moneyadd'])))), 2) : false));
		$comment = ((isset($_POST['comment']) ? mysql_real_escape_string(htmlspecialchars(clear_html_scripts_tags($_POST['comment']), NULL, 'CP1251')) : false));

		if ($comment != false) {
			$comment = limitatexto(iconv('UTF-8', 'CP1251', $comment), 200);
		}


		$methodadd = ((isset($_POST['methodadd']) && preg_match('|^[\\d]{1,2}$|', intval(limpiar($_POST['methodadd']))) ? intval(limpiar($_POST['methodadd'])) : false));

		if ($comment == false) {
			$comment = "�" . ' ' . "�������" . ' ' . "��" . ' ' . "��������";
		}


		$sql_check_ban = mysql_query('SELECT `id` FROM `tb_black_users` WHERE `name`=\'' . $username . '\' LIMIT 1');

		if (0 < mysql_num_rows($sql_check_ban)) {
			exit("������" . '! ' . "��" . ' ' . "��������" . '.');
			return 1;
		}


		if ($username == false) {
			exit('ERRORLOG');
			return 1;
		}


		if ($moneyadd == false) {
			exit("��" . ' ' . "�������" . ' ' . "�����");
			return 1;
		}


		if ($moneyadd < $kopilka_cash_in) {
			exit("�����������" . ' ' . "�����" . ' ' . number_format($kopilka_cash_in, 2, '.', '\'') . ' ' . "���" . '.');
			return 1;
		}


		if (($methodadd == false) | (($methodadd != 1) && ($methodadd != 2))) {
			exit("��" . ' ' . "������" . ' ' . "���" . ' ' . "�����");
			return 1;
		}


		if ($methodadd == 1) {
			$money_table = 'money';
		}
		 else if ($methodadd == 2) {
			$money_table = 'money_rb';
		}
		 else {
			$money_table = 'money';
		}

		$sql_user = mysql_query('SELECT `id`,`username`,`' . $money_table . '` FROM `tb_users` WHERE `username`=\'' . $username . '\'');

		if (0 < mysql_num_rows($sql_user)) {
			$row_user = mysql_fetch_assoc($sql_user);
			$user_id = $row_user['username'];
			$user_name = $row_user['username'];
			$money_user = $row_user[$money_table];

			if ($money_user < $moneyadd) {
				exit("��" . ' ' . "����������" . ' ' . "�������");
				return 1;
			}

			function IN_KOP($summa)
			{
				switch (strlen($summa)) {
				case 1:
					$summa = '000000000' . $summa;
					break;

				case 2:
					$summa = '00000000' . $summa;
					break;

				case 3:
					$summa = '0000000' . $summa;
					break;

				case 4:
					$summa = '000000' . $summa;
					break;

				case 5:
					$summa = '00000' . $summa;
					break;

				case 6:
					$summa = '0000' . $summa;
					break;

				case 7:
					$summa = '000' . $summa;
					break;

				case 8:
					$summa = '00' . $summa;
					break;

				case 9:
					$summa = '0' . $summa;
					break;

				case 10:
					$summa = $summa;
				}

				return $summa;
			}

			$kopilka_summa_in = mysql_result(mysql_query('SELECT `price` FROM `tb_config` WHERE `item`=\'kopilka_summa_in\''), 0, 0);
			$kopilka_summa_in = p_floor($kopilka_summa_in + $moneyadd, 2);
			$kopilka_summa_in = number_format($kopilka_summa_in, 2, '.', '');
			$reit_add = floor($moneyadd / 10) * $reit_kop;
			mysql_query('UPDATE `tb_users` SET `' . $money_table . '`=`' . $money_table . '`-\'' . $moneyadd . '\', `reiting`=`reiting`+\'' . $reit_add . '\' WHERE `username`=\'' . $username . '\'');
			mysql_query('UPDATE `tb_config` SET `price`=`price`+\'' . $moneyadd . '\', `howmany`=`howmany`+\'1\' WHERE `item`=\'kopilka_summa_in\'');
			mysql_query('INSERT INTO `tb_history` (`status_pay`,`user`,`user_id`,`date`,`time`,`amount`,`method`,`status`,`tipo`) ' . "\n\t\t\t\t\t" . 'VALUES(\'1\',\'' . $user_name . '\',\'' . $user_id . '\',\'' . DATE('d.m.Y H:i') . '\',\'' . time() . '\',\'' . $moneyadd . '\',\'' . "����������" . ' ' . "�������" . ' ' . "�������" . '\',\'' . "�������" . '\',\'rashod\')') || exit(mysql_error());
			mysql_query('INSERT INTO `tb_kopilka_in` (`date`,`time`,`method_pay`,`username`,`comment`,`money`,`ip`) ' . "\n\t\t\t\t\t" . 'VALUES(\'' . DATE('d.m.Y H:i:s') . '\', \'' . time() . '\', \'' . $methodadd . '\', \'' . $username . '\', \'' . $comment . '\', \'' . $moneyadd . '\', \'' . $laip . '\')');
			exit('' . IN_KOP($kopilka_summa_in) . '');
			return 1;
		}


		exit('ERRORLOG');
		return 1;
	}


	if ($option == 'ViewMoneyIn') {
		$sql_status = mysql_query('SELECT `user_status` FROM `tb_users` WHERE `username`=\'' . $username . '\'');

		if (0 < mysql_num_rows($sql_status)) {
			$row_status = mysql_fetch_assoc($sql_status);
			$users_status = $row_status['user_status'];
		}
		 else {
			$users_status = 0;
		}

		$message_text = '';
		$message_text .= '<table class="tables" style="margin:0; padding:0; width:100%;">';
		$message_text .= '<thead><tr align="center">';
		$message_text .= '<th width="120" align="center" style="padding:4px 5px;">' . "������������" . '</th>';
		$message_text .= '<th align="center" style="padding:4px 5px;">' . "�����������" . '</th>';
		$message_text .= '<th width="120" align="center" style="padding:4px 5px;">' . "����" . '</th>';
		$message_text .= '<th width="70" align="center" style="padding:4px 5px;">' . "�����" . '</th>';
		$message_text .= '</thead></tr>';
		$sql = mysql_query('SELECT * FROM `tb_kopilka_in` ORDER BY `id` DESC LIMIT 100');

		if (0 < mysql_num_rows($sql)) {
			while ($row = mysql_fetch_assoc($sql)) {
				$message_text .= '<tr id="claims-' . $row['id'] . '" align="left">';
				$message_text .= '<td align="center" style="padding:2px 5px;">' . $row['username'] . '</td>';
				$message_text .= '<td style="padding:2px 5px;"><div id="coment' . $row['id'] . '">' . (($row['comment'] == false ? "�" . ' ' . "�������" . ' ' . "��" . ' ' . "��������" : $row['comment'])) . ' ' . ((($users_status == 1) && ($row['comment'] != "�" . ' ' . "�������" . ' ' . "��" . ' ' . "��������") ? '<span onClick="kop_dll_coment(\'' . $row['id'] . '\')" class="workcomp" title="' . "�������" . ' ' . "�����������" . '"></span>' : false)) . '</div></td>';
				$message_text .= '<td align="center" style="padding:2px 5px;">' . DATE('d.m.Y' . "�" . '. H:i', $row['time']) . '</td>';
				$message_text .= '<td align="center" style="padding:2px 5px;">' . $row['money'] . '</td>';
				$message_text .= '</tr>';
			}
		}
		 else {
			$message_text .= '<tr align="center"><td colspan="4"><b>' . "���" . ' ' . "������" . '</b></td></tr>';
		}

		$message_text .= '</table>';
		exit($message_text);
		return 1;
	}


	if ($option == 'InfoKop') {
		$message_text = '';
		$message_text .= '<div style="margin:0 auto; padding:8px 10px; font-size:13px; text-align:justify;">';
		$message_text .= "���" . ' ' . "�������" . ' ' . "�������" . ' <b>' . strtoupper($_SERVER['HTTP_HOST']) . '</b>. ' . "�����" . ' ' . "������������" . ' ' . "�����" . ' ' . "�" . ' ' . "��" . ' ' . "������������" . ' ' . "�����" . ' ' . "�����" . ' ' . "��" . ' ' . "�������" . ' ' . "�������" . ' ' . "�" . ' ' . "���" . ' ' . "��" . ' ' . "��������" . ' ' . "����" . ' ' . "�����������" . '. ';
		$message_text .= "���" . ' ' . "�������" . ' ' . "�������" . ' ' . "�" . ' ' . "�������" . ' ' . "�������" . ', ' . "�����" . ' ' . "���" . ' ' . "������������" . ', ' . "�������" . ' ' . "�����" . ' <b>' . count_text($kopilka_reit_out, "������", "����", "�����", '') . '</b> ' . "��������" . ' ' . "�" . ' ' . "���������" . ' ' . "��" . ' ' . "�����" . ' <b>' . count_text($kopilka_min_click, "������", "����", "�����", '') . '</b> ' . "�" . ' ' . "����" . ' ' . "�" . ' ' . "�������� (��������� ��������)" . ', ' . "�����" . ' ' . "��������" . ' ' . "�����" . ' ' . "�" . ' ' . "�������" . ' <b>' . number_format($kopilka_cash_out, 2, '.', '') . '</b> ' . "���" . '. ' . "������" . ' <b>' . count_text($kopilka_period_out, "�����", "���", "����", '') . '</b>.<br><br>';
		$message_text .= '<b>' . "����������" . ':</b>';
		$message_text .= '<div style="text-align:left;">';
		$message_text .= '&nbsp;-&nbsp;' . "��������" . ' ' . "�����" . ' ' . "�����" . ' ' . "����" . ' ' . "���" . ' ' . "�" . ' <b>' . count_text($kopilka_period_out, "�����", "���", "����", '') . '</b>.<br>';
		$message_text .= '&nbsp;-&nbsp;' . "��������" . ' ' . "�����" . ' ' . "�����" . ' ' . "������" . ' ' . "��" . ', ' . "���" . ' ' . "�����" . ' ' . "�����" . ' <b>' . count_text($kopilka_reit_out, "������", "����", "�����", '') . '</b> ' . "��������" . ' ' . "�" . ' ' . "������" . ' ' . "��" . ' ' . "�����" . ' <b>' . count_text($kopilka_min_click, "������", "����", "�����", '') . '</b> ' . "�" . ' ' . "����" . ' ' . "�" . ' ' . "�������� (��������� ��������)" . '. ';
		$message_text .= "���" . ' ' . "�����������" . ' ' . "����������" . ' ' . "�������" . ' ' . "�������������" . ' ' . "��" . ' ' . "�����������" . ' ' . "�����" . '-' . "���������" . ' ' . "�" . ' ' . "�����" . ' ' . "����������" . ' ' . "�������" . '.<br>';
		$message_text .= '&nbsp;-&nbsp;' . "����" . ' ' . "�������" . ' ' . "�����" . ' ' . "�����" . ', ' . "��" . ' ' . "�" . ' ' . "������������" . ' ' . "��������������" . ' ' . "��" . ' ' . "������" . ' ' . "��������" . ' ' . "������" . '.';
		$message_text .= '</div><br>';
		$message_text .= "��" . ' ' . "������" . ' ' . "������" . ' <b>10 ' . "���" . '.</b> ' . "�����������" . ' ' . "��" . ' ' . "����������" . ' ' . "�������" . ' ' . "�������" . ' ' . "�����������" . ' <b>' . $reit_kop . ' ' . "������" . '</b> ' . "��������" . '.<br><br>';
		$message_text .= "���" . ' ' . "�������" . ' ' . "���������" . ' ' . "���������" . ' ' . "������������" . ', ' . "�������" . ' ' . "���" . ' ' . "������" . ' ' . "������" . ' ' . "��" . ' ' . "�������" . ' ' . "�����" . ' ' . "���������" . ' ' . "�������" . ', ' . "�����" . ' ' . "������" . ' ' . "�����" . ' ' . "��������" . ' ' . "����" . ' ' . "��������" . ' ' . "�����" . ', ' . "���" . ' ' . "�����" . ' ' . "��������" . ' ' . "����������" . ' ' . "���������" . ' ' . "�������������" . ' ' . "������" . ' ' . "������" . ' ' . "�������" . '.<br><br>';
		$message_text .= "���" . ' ' . "��" . ' ' . "��������" . ' ' . "�����" . ', ' . "�����" . ' ' . "������" . ' ' . "��" . ' ' . "������" . ' ' . "������" . ' ' . "���" . ' ' . "�����" . ' ' . "��������" . ', ' . "����" . ' ' . "������" . ' ' . "���" . ' ' . "������" . ' ' . "��" . ' ' . "��������" . ' ' . "�����" . ', ' . "����" . ' ' . "������" . ' ' . "�����" . ' ' . "������" . ' ' . "��" . ' ' . "�������" . ' ' . "�������" . ' ' . "�" . ' ' . "�������" . ' ' . "���" . ' ' . "������" . '.';
		$message_text .= '</div>';
		exit($message_text);
		return 1;
	}


	exit('ERROR');
	return 1;
}


exit('ERRORLOG');
function clear_html_scripts_tags($text)
{
	$text = trim($text);
	$text = preg_replace('#([-0-9a-z_\\.]+@[-0-9a-z_\\.]+\\.[a-z]{2,6})#i', '', $text);
	$text = preg_replace('\'<script[^>]*?>.*?</script>\'si', '', $text);
	$text = preg_replace('#<a\\s+href=[\'|"]?([^\\#]+?)[\'|"]?\\s.*>(?!<img).+?</a>#i', '', $text);
	$text = preg_replace('#http://[^\\s]+#i', '', $text);
	$text = preg_replace('#www\\.[-\\d\\w\\._&\\?=%]+#i', '', $text);
	$text = str_replace("\n\n", "\n", $text);
	$text = strip_tags($text);
	return $text;
}

function count_text($count, $text1, $text2, $text3, $text)
{
	if (0 <= $count) {
		if ((10 <= $count) && ($count <= 20)) {
			return $count . ' ' . $text1;
		}


		switch (substr($count, -1, 1)) {
		case 1:
			return $count . ' ' . $text2;
		case 2:

		case 3:

		case 4:
			return $count . ' ' . $text3;
		case 5:

		case 6:

		case 7:

		case 8:

		case 9:

		case 0:
			return $count . ' ' . $text1;
		}

		return;
	}


	return $text;
}


?>