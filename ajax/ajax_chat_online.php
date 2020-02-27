<?php
session_start();
header('Content-type: text/html; charset=windows-1251');

if (!(DEFINED('ROOT_DIR'))) {
	DEFINE('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
}


if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
	function escape($value)
	{
		global $mysqli;
		return $mysqli->real_escape_string($value);
	}
	
	function getRealIP() {
		if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
			$client_ip = (!empty($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : ((!empty($_ENV["REMOTE_ADDR"])) ? $_ENV["REMOTE_ADDR"] : "unknown" );
		        $entries = explode('[, ]', $_SERVER["HTTP_X_FORWARDED_FOR"]);
			reset($entries);
			while (list(, $entry) = reset($entries)) {
				$entry = trim($entry);
				if(preg_match('/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/', $entry, $matches)) {
					$private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.((1[6-9])|(2[0-9])|(3[0-1]))\..*/', '/^10\..*/');
					$found_ip = preg_replace($private_ip, $client_ip, $matches[1]);
					if($client_ip != $found_ip) {$client_ip = $found_ip; break;}
				}
			}
		}else{
			$client_ip = (!empty($_SERVER["REMOTE_ADDR"])) ? $_SERVER["REMOTE_ADDR"] : ((!empty($_ENV["REMOTE_ADDR"])) ? $_ENV["REMOTE_ADDR"] : "unknown" );
		}
		return $client_ip;
	}
	
	require ROOT_DIR . '/config_mysqli.php';

	$user_name = ((isset($_SESSION['userLog']) && preg_match('|^[a-zA-Z0-9\\-_-]{3,25}$|', trim($_SESSION['userLog'])) ? escape(htmlentities(stripslashes(trim($_SESSION['userLog'])))) : false));

	$user_id = ((isset($_SESSION['userID']) && preg_match('|^[\\d]{1,11}$|', intval(trim($_SESSION['userID']))) ? escape(intval(trim($_SESSION['userID']))) : false));
	$user_ip = escape(getRealIP());

	$chat_status = ((isset($_SESSION['ChatStatus']) && preg_match('|^[-]?[0-2]{1}$|', intval(trim($_SESSION['ChatStatus']))) ? escape(intval(trim($_SESSION['ChatStatus']))) : false));
	$chat_avatar = ((isset($_SESSION['ChatAvatar']) ? escape(htmlspecialchars(trim($_SESSION['ChatAvatar']), ENT_QUOTES, 'CP1251', false)) : 'no.png'));

	if (($user_name != false) && ($chat_status != -1)) {
		$mysqli->query('INSERT INTO `tb_chat_online` (`user_name`,`user_id`,`user_avatar`,`user_status`,`time`,`ip`) VALUES(\'' . $user_name . '\',\'' . $user_id . '\',\'' . $chat_avatar . '\',\'' . $chat_status . '\',\'' . (time() + 120) . '\',\'' . $user_ip . '\') ON DUPLICATE KEY UPDATE `user_name`=\'' . $user_name . '\', `user_id`=\'' . $user_id . '\', `user_avatar`=\'' . $chat_avatar . '\', `user_status`=\'' . $chat_status . '\', `time`=\'' . (time() + 120) . '\', `ip`=\'' . $user_ip . '\'') || exit($mysqli->error);
	}


	$mysqli->query('DELETE FROM `tb_chat_online` WHERE `time`<\'' . time() . '\'') || exit(my_json_encode('ERROR', $mysqli->error));
	$user_status_arr = array(-1 => '<span class="chat-ban">[заблокирован]</span>', 1 => '[<span class="chat-admin">Администратор</span>]', 2 => '[<span class="chat-moder">Модератор</span>]');
	//$user_status_arr = array(-1 => '<span class="chat-ban">[' . "\xe7\xe0\xe1\xeb\xee\xea\xe8\xf0\xee\xe2\xe0\xed" . ']</span>', 1 => '[<span class="chat-admin">' . "\xe0\xe4\xec\xe8\xed\xe8\xf1\xf2\xf0\xe0\xf2\xee\xf0" . '</span>]', 2 => '[<span class="chat-moder">' . "\xec\xee\xe4\xe5\xf0\xe0\xf2\xee\xf0" . '</span>]');
	($sql = $mysqli->query('SELECT * FROM `tb_chat_online` WHERE `user_name` IN (SELECT `username` FROM `tb_users` USE INDEX (`username`))')) || exit($mysqli->error);

	if (0 < $sql->num_rows) {
		$chat_users_arr = array();

		while ($row = $sql->fetch_assoc()) {
			$user_status = ((isset($user_status_arr[$row['user_status']]) ? $user_status_arr[$row['user_status']] : false));
			$class_login = (($row['user_status'] == -1 ? 'chat-user-login-ban' : 'chat-user-login'));
			$chat_users_arr[] = '<div id="chat-user-' . $row['user_id'] . '" class="chat-online-line">';
			$chat_users_arr[] = '<a href="/wall?uid=' . $row['user_id'] . '" target="_blank" title="Перейти на стену пользователя ' . $row['user_name'] . '"><img src="avatar/' . $row['user_avatar'] . '" class="chat-avatar-small" alt="" /></a>';
			$chat_users_arr[] = '<span class="chat-user-info"><div class="' . $class_login . '" onClick="UserToChat(\\\'chat-user-to\\\', \\\'' . $row['user_name'] . '\\\')">' . $row['user_name'] . '</div>' . $user_status . '</span>';
			$chat_users_arr[] = '<span class="chat-user-vp"><a class="vp-mail" href="/newmsg.php?name=' . $row['user_name'] . '" target="_blank" title="Написать пользователю ' . $row['user_name'] . ' по ВП"></a></span>';
			$chat_users_arr[] = '</div>';
		}

		echo '$(".chat-online-cnt").html("' . number_format($sql->num_rows, 0, '.', ' ') . '"); ' . "\r\n";
		echo '$(".chat-online-users").html(\'' . implode('', $chat_users_arr) . '\');';
	}


	$sql->free();
	$mysqli->close();
}


?>