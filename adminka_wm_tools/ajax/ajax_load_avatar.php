<?php
@session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
require("".$_SERVER['DOCUMENT_ROOT']."/config.php");
require("".$_SERVER['DOCUMENT_ROOT']."/funciones.php");
sleep(0);

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	exit("Ошибка! Для доступа к этой странице необходимо авторизоваться!");
}else{
	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,20}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(strtolower(stripslashes(trim($_SESSION["userLog"])))) : false;

	$sql_user = mysql_query("SELECT `id`,`username` FROM `tb_users` WHERE `username`='$username'");
	if(mysql_num_rows($sql_user)>0) {
		$row_user = mysql_fetch_assoc($sql_user);
		$user_id = $row_user["id"];
		$user_name = $row_user["username"];

		//exit("$user_id $user_name");
	}else{
		exit("Ошибка! Для доступа к этой странице необходимо авторизоваться!");
	}

	if(count($_POST) > 0) {
		$error = $_FILES["user_avatar"]["error"];

		if($error==2 | $_FILES["user_avatar"]["size"] > 102400 ) {
			exit("Ошибка! Максимальный объем файла не должен превыщать 100 kb.");
		}elseif($error==1) {
			exit("Ошибка! Размер принятого файла превысил максимально допустимый размер!");
		}elseif($error==3) {
			exit("Ошибка! Загружаемый файл был получен только частично. Повторите попытку!");
		}elseif($error==4) {
			exit("Ошибка! Отсутствует файл для загрузки!");
		}else{
			$allowed_filetypes = array('gif','png','jpg','jpeg');
			$format_name = explode(".", trim($_FILES["user_avatar"]["name"]));
			$format_name = strtolower($format_name[count($format_name) - 1]);

			if(!in_array($format_name, $allowed_filetypes)) {
				exit("Ошибка! Допустимые форматы аватара для загрузки - gif, png, jpeg, jpg");

			}elseif(image_valid($_FILES["user_avatar"]["type"]) === "false") {
				exit("Ошибка! Допустимые форматы аватара для загрузки - gif, png, jpeg, jpg");

			}else{
				$size = getimagesize($_FILES["user_avatar"]["tmp_name"]);
				$w = $size["0"];
				$h = $size["1"];
				$mime = $size["mime"];
				$format = strtolower(substr($mime, strpos($mime, "/")+1));

				if(image_valid($mime) === "false") {
					exit("Ошибка! Допустимые форматы аватара для загрузки - gif, png, jpeg, jpg");
				}elseif($w > 200 | $h > 200) {
					exit("Максимальный размер картинки 200x200px.<br>Ваше изображение - ".$w."x".$h."px, не соответствует этим требованиям!");
				}elseif($w < 60 | $h < 60) {
					exit("Минимальный размер картинки 60x60px.<br>Ваше изображение ".$w."x".$h."px, не соответствует этим требованиям!");
				}else{
					if(is_uploaded_file($_FILES["user_avatar"]["tmp_name"])) {
						$uploaddir = $_SERVER['DOCUMENT_ROOT']."/avatar/";
						$tchk = ".";
						$uploadfile = $uploaddir . basename($user_id. $tchk . $format);
						$user_table = basename($user_id. $tchk . $format);

						if($w != $h){
							if($w > $h) $h = $w;
							if($h > $w) $w = $h;

							require($_SERVER['DOCUMENT_ROOT']."/includes/imgresize.php");

							if(move_uploaded_file($_FILES["user_avatar"]["tmp_name"], $uploadfile)) {

								if(img_resize("$uploadfile", "$uploadfile", $w, $h)) {
									mysql_query("UPDATE `tb_users` SET `avatar`='$user_table' WHERE `id`='$user_id' AND `username`='$user_name'") or die(mysql_error());

									//exit("Аватар успешно загружен на сервер1 $user_table");
									exit("SUCCES");
								}else{
									unlink($uploadfile);
									exit("Ошибка1 при загрузке файла на сервер!");
								}
							}else{
								exit("Ошибка2 при загрузке файла на сервер!");
							}
						}else{
							if(move_uploaded_file($_FILES["user_avatar"]["tmp_name"], $uploadfile)) {
								mysql_query("UPDATE `tb_users` SET `avatar`='$user_table' WHERE `id`='$user_id' AND `username`='$user_name'") or die(mysql_error());

								//exit("Аватар успешно загружен на сервер1 $user_table");
								exit("SUCCES");
							}else{
								exit("Ошибка3 при загрузке файла на сервер!");
							}
						}
					}else{
						exit("Ошибка4 при загрузке файла на сервер!");
					}
				}

			}

		}
	}else{
		exit("Ошибка5 при загрузке файла на сервер!");
	}
}

?>