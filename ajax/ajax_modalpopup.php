<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/funciones.php");
sleep(0);

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
		$option = ( isset($_POST["op"]) && preg_match("|^[a-zA-Z0-9\-_]{3,20}$|", limpiar($_POST["op"])) ) ? limpiar($_POST["op"]) : false;

		if($option=="LoadNot" && !isset($_COOKIE["ModBLock"])) {
			if(!DEFINED("cache_not_file")) DEFINE("cache_not_file", ROOT_DIR."/cache/cache_notification.inc");
			if(!isset($not_arr) && is_file(cache_not_file) ) {
				$not_arr = unserialize(file_get_contents(cache_not_file));
				$not_count = count($not_arr);

				if($not_count>0) {
					foreach($not_arr as $key => $value) {
						if(isset($_COOKIE["ModBlock"][$key])) {
							unset($not_arr[$key]);
						}
					}

					if(count($not_arr)>0) {
						shuffle($not_arr);
						SETCOOKIE("ModBLockID", $not_arr["0"]["id_not"], (time()+2*24*60*60), "/");
						SETCOOKIE("ModBLock", (time()+1*24*60*60), (time()+1*24*60*60), "/");

						echo '<div class="box-modal" id="ModalNot" style="text-align:justify;">';
							echo '<div class="box-modal-title">'.$not_arr["0"]["title_not"].'</div>';
							echo '<div class="box-modal-close modalpopup-close"></div>';
							echo '<div class="box-modal-content">';
								echo '<div align="center"><img src="'.$not_arr["0"]["url_img_not"].'" alt="" title="" border="0" style="cursor:pointer;" onClick="GoNot(\''.$not_arr["0"]["url_not"].'\'); return false;" /></div>';
								echo '<br>';
								echo ''.($not_arr["0"]["desc_not"]).'';
								echo '<br><br>';
								echo '<div class="sub-gray" onClick="GoNot(\''.$not_arr["0"]["url_not"].'\'); return false;">Подробнее</div>';
							echo '</div>';
						echo '</div>';
			
						?><script type="text/javascript">
						document.getElementById("LoadModalNot").style.display = '';

						function GoNot(url) {
							$('#LoadModalNot').modalpopup('close');
							window.open(url, '_blank');
						}

						function getCookie(name) {
							var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"))
							return matches ? decodeURIComponent(matches[1]) : undefined 
						}

						$('#LoadModalNot').modalpopup({
							closeOnEsc: false,
							closeOnOverlayClick: false,
							beforeClose: function(data, el) {
								//if(confirm("Закрыть окно?")) {
									var last_id_mod = getCookie("ModBLockID");

									if(last_id_mod != "undefined" && last_id_mod > 0) {
										var expires = new Date();
										expires.setTime(expires.getTime() + (2 * 24 * 60 * 60 * 1000));
				        					document.cookie="ModBlock["+last_id_mod+"]=" + <?=(time()+2*24*60*60);?> + "; path=/; expires=" + expires.toUTCString();
									}
									document.getElementById("LoadModalNot").style.display = 'none';
									return true;
								//} else {
								//	return false;
								//}
							}
						});
						</script><?
					}
				}
			}

		}elseif($option=="LoadNews" /*&& !isset($_COOKIE["ReadNews"])*/) {

			echo '<div class="box-modal" id="ModalNews" style="text-align:justify;">';
				echo '<div class="box-modal-title">Системное сообщение</div>';
				echo '<div class="box-modal-close modalpopup-close"></div>';
				echo '<div class="box-modal-content">';
					echo '<div align="center">';
						echo 'Уважаемый, <b>'.ucfirst($username).'</b>!<br>У нас есть свежие новости сайта, которые ждут вашего прочтения.';
					echo '</div>';
					echo '<br><br>';
					echo '<div class="sub-gray" onClick="GoNot(\'/news.php\'); return false;">Прочитать</div>';
				echo '</div>';
			echo '</div>';

			?><script type="text/javascript">
			document.getElementById("LoadModalNews").style.display = '';

			function GoNot(url) {
				$('#LoadModalNews').modalpopup('close');
				window.location.href = url;
			}

			function getCookie(name) {
				var matches = document.cookie.match(new RegExp("(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"))
				return matches ? decodeURIComponent(matches[1]) : undefined 
			}

			$('#LoadModalNews').modalpopup({
				closeOnEsc: false,
				closeOnOverlayClick: false,
				beforeClose: function(data, el) {
					var ReadNews = getCookie("ReadNews");

					if(ReadNews != "undefined" && ReadNews != 1) {
						var expires = new Date();
						expires.setTime(expires.getTime() + (24 * 60 * 60 * 1000));
        					document.cookie="ReadNews=1; path=/;";
					}
					document.getElementById("LoadModalNews").style.display = 'none';
					return true;
				}
			});
			</script><?
		}
	}
}

?>