<?php

if($moder_post==1) {$moder_post2=0;}else{$moder_post2=1;}


if(count($_POST)>0 && isset($_POST["editpost"])) {

	$p_id_pr = (isset($_POST["id_pr"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_pr"]))) ? intval(limpiar(trim($_POST["id_pr"]))) : false;
	$p_id_t = (isset($_POST["id_t"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_t"]))) ? intval(limpiar(trim($_POST["id_t"]))) : false;
	$p_id_p = (isset($_POST["id_p"]) && preg_match("|^[\d]{1,11}$|", trim($_POST["id_p"]))) ? intval(limpiar(trim($_POST["id_p"]))) : false;
	$p_hs = (isset($_POST["hs"]) && preg_match("|^[a-zA-Z0-9]{32,48}$|", trim($_POST["hs"]))) ? htmlspecialchars(trim($_POST["hs"])) : false;
	$check_hs1 = preg_match('/^[a-zA-Z0-9\$\!\/]{32,48}$/i', $p_hs);
	$check_hs2 = md5($p_id_pr+$p_id_t+$p_id_p+2205);

	//echo "$p_hs<br>$check_hs1<br>$check_hs2<br>";

	$post = (isset($_POST["post"])) ? (trim($_POST["post"])) : false;
	$post = str_replace("'", '"', $post);
	$post = str_replace("`", '"', $post);
	$post = str_replace("\\", "", $post);

	if (get_magic_quotes_gpc()) {$post = stripslashes($post);}

	if($check_hs1==1 && $p_hs==$check_hs2 && $username!=false && ($edit_post==1 | $moder_post==1)) {
		$sql_t = mysql_query("SELECT `id` FROM `tb_forum_p` WHERE `id`='$p_id_p' AND `moder`='$moder_post' AND (`username`='$username' OR $moder_post=1) AND `ident_pr`='$p_id_pr' AND `ident_t`='$p_id_t'");
		if(mysql_num_rows($sql_t)>0) {

			mysql_query("UPDATE `tb_forum_p` SET `text`='$post', `edit_user`='$username', `edit_date`='".time()."' WHERE `id`='$p_id_p'") or die(mysql_error());

			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'?th='.$p_id_t.'#post-'.$p_id_p.'");</script> ';
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'?th='.$p_id_t.'#post-'.$p_id_p.'"></noscript>';
		}else{
			echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script> ';
			echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
		}
	}else{
		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script> ';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
	}
}



if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"]) && $username!=false && ($edit_post==1 | $moder_post==1)) {

	$sql_p = mysql_query("SELECT * FROM `tb_forum_p` WHERE `id`='$post_edit' AND `moder`='$moder_post' AND (`username`='$username' OR $moder_post=1)");
	if(mysql_num_rows($sql_p)>0) {
		$row_p = mysql_fetch_array($sql_p);

		echo '<div id="blockyes" style="display: block;">';
		echo '<form action="" method="POST" id="forum" name="forumform" onsubmit="return FormC(); return false;">';
			echo '<input type="hidden" name="id_pr" value="'.$id_pr.'">';
			echo '<input type="hidden" name="id_t" value="'.$id_t.'">';
			echo '<input type="hidden" name="id_p" value="'.$post_edit.'">';
			echo '<input type="hidden" name="hs" value="'.md5($id_pr+$id_t+$post_edit+2205).'">';
			echo '<input type="hidden" name="editpost" value="1">';

			echo '<table class="forum_com_add" align="center" border="0">';
			echo '<thead><tr><th align="center" nowrap="nowrap" colspan="2">Редактирование комментария</th></tr></thead>';
			echo '<tbody>';
			echo '<tr>';
				echo '<td class="ct" width="200">Ваш комментарий &darr;</td>';
				echo '<td class="ct">';
					echo '<span class="bbc-bold" title="Выделить жирным" onClick="javascript:addtag(\'[b]\',\'[/b]\'); return false;">B</span>';
					echo '<span class="bbc-italic" title="Выделить курсивом" onClick="javascript:addtag(\'[i]\',\'[/i]\'); return false;">i</span>';
					echo '<span class="bbc-uline" title="Выделить подчёркиванием" onClick="javascript:addtag(\'[u]\',\'[/u]\'); return false;">U</span>';
					echo '<span class="bbc-url" title="Цитата" onClick="javascript:addtag(\'[quote]\',\'[/quote]\'); return false;">Цитата</span>';
					echo '<span class="bbc-url" title="Выделить URL" onClick="javascript:addtag(\'[url]\',\'[/url]\'); return false;">URL</span>';
				echo '</td>';
			echo '</tr>';
			echo '<tr><td class="ct" colspan="2"><textarea name="post" id="post" style="width:99%; height:200px;">'.$row_p["text"].'</textarea></td></tr>';

			echo '<tr><td class="ct" colspan="2">';
				for($i=0; $i<40; $i++){
					echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
				}
				for($i=80; $i<99; $i++){
					echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
				}
			echo '</td></tr>';

			echo '</tbody>';
			echo '</table>';

			echo '<div align="center" style="margin-top:15px;"><input type="submit" class="subblue" value="Сохранить"></div>';
			echo '</form>';
		echo '</div>';
	}else{
		echo '<script type="text/javascript">location.replace("'.$_SERVER["PHP_SELF"].'");</script> ';
		echo '<noscript><META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$_SERVER["PHP_SELF"].'"></noscript>';
	}
}else{
	echo '<div class="foruminfo">Для доступа к этому разделу необходимо авторизоваться или <a href="/register.php">зарегистрироваться</a></div>';
}
?>