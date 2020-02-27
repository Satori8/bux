<?php
session_start();
header('Content-type: text/html; charset=windows-1251');

if (!(DEFINED('ROOT_DIR'))) {
	DEFINE('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
}


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	require ROOT_DIR . '/funciones.php';
	require ROOT_DIR . '/config_mysqli.php';

	$user_name = ((isset($_SESSION['userLog']) && preg_match('|^[a-zA-Z0-9\\-_-]{3,25}$|', trim($_SESSION['userLog'])) ? htmlentities(stripslashes(trim($_SESSION['userLog']))) : false));
	$chat_status = 0;
	($sql = $mysqli->query('SELECT * FROM `tb_chat_adv` WHERE `status`=\'1\' ORDER BY `id` DESC')) || exit($mysqli->error);

	if (0 < $sql->num_rows) {
		$promo_arr = array();
		($sql_user = $mysqli->query('SELECT `user_status` FROM `tb_chat_users` WHERE `user_name`=\'' . $user_name . '\'')) || exit($mysqli->error);
		$chat_status = ((0 < $sql_user->num_rows ? $sql_user->fetch_object()->user_status : 0));
		$sql_user->free();

		while ($row = $sql->fetch_assoc()) {
			$row['description'] = str_replace(array("\r", "\n"), ' ', $row['description']);
			$row['url'] = str_replace(array("\r", "\n"), ' ', $row['url']);
			$promo_class = (($row['color'] == 1 ? 'promo-link-h' : 'promo-link-n'));
			$promo_arr[] = '{promo_id:\'' . $row['id'] . '\', promo_domen:\'' . @gethost($row['url']) . '\', promo_url:\'' . $row['url'] . '\', promo_desc:\'' . $row['description'] . '\', promo_class:\'' . $promo_class . '\'}';
		}

		$promo_arr = ((isset($promo_arr) && is_array($promo_arr) && (0 < count($promo_arr)) ? implode(', ', $promo_arr) : false));
	}
	 else {
		$promo_arr = false;
	}

	$panel = '<div class="promotion-panel">';
	$panel .= '<span class="promo-add" title="' . "\xc4\xee\xe1\xe0\xe2\xe8\xf2\xfc" . ' ' . "\xf1\xf1\xfb\xeb\xea\xf3" . '" onClick="FuncChat(false, \\\'form-chat-promotion\\\', false, chat_token, true, \\\'' . "\xd0\xe0\xe7\xec\xe5\xf9\xe5\xed\xe8\xe5" . ' ' . "\xf0\xe5\xea\xeb\xe0\xec\xfb" . ' ' . "\xe2" . ' ' . "\xd7\xc0\xd2\xe5" . '\\\', 550);"></span>';
	$panel .= '<span class="promo-info" title="' . "\xcf\xee\xf1\xec\xee\xf2\xf0\xe5\xf2\xfc" . ' ' . "\xe2\xf1\xe5" . ' ' . "\xf1\xf1\xfb\xeb\xea\xe8" . '" onClick="FuncChat(false, \\\'promo-list\\\', false, chat_token, true, \\\'' . "\xcf\xee\xf1\xeb\xe5\xe4\xed\xe8\xe5" . ' 5 ' . "\xf1\xf1\xfb\xeb\xee\xea" . '\\\', 630);"></span>';
	$panel .= '</div>';

	if ($promo_arr != false) {
		echo 'var promo_start = 1;' . "\r\n";
		echo 'var promo_arr = [' . $promo_arr . '];' . "\r\n";
		echo '$(\'.box-promotion\').html(\'<span class="chat-promo"></span>' . $panel . '\');' . "\r\n".'RotatePromo()';
	}
	 else {
		echo 'var promo_start = 1;' . "\r\n";
		echo 'var promo_start = 1;' . "\r\n".'var promo_arr = [];' . "\r\n";
		echo '$(\'.box-promotion\').html(\'<span class="chat-promo">' . "\xd0\xe5\xea\xeb\xe0\xec\xed\xee\xe5" . ' ' . "\xec\xe5\xf1\xf2\xee" . ' ' . "\xf1\xe2\xee\xe1\xee\xe4\xed\xee" . '!</span>' . $panel . '\');' . "\r\n";
	}

	$sql->free();
	$mysqli->close();
}


?>