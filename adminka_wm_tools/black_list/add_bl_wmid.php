<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ ������ WMID, ����������� ��� ����������� �� �����</b></h3>';

function universal_link_bar($page, $count, $pages_count, $show_link) {
	if ($pages_count == 1) return false;
		$sperator = ' &nbsp;';
		$style1 = 'style="font-weight: bold; border:1px solid #B5B5B5; padding:1px 5px 1px 5px; background:#FFF"';
		$style2 = 'style="font-weight: bold; border:1px solid #B5B5B5; padding:1px 5px 1px 5px; background:#CCC"';
		$begin = $page - intval($show_link / 2);
		unset($show_dots);

		if ($pages_count <= $show_link + 1) $show_dots = 'no';
		if (($begin > 2) && !isset($show_dots) && ($pages_count - $show_link > 2)) {
			echo '<a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page=1> 1 </a> ';
		}

		for ($j = 0; $j < $page; $j++) {
			if (($begin + $show_link - $j > $pages_count) && ($pages_count-$show_link + $j > 0)) {
				$page_link = $pages_count - $show_link + $j;

				if (!isset($show_dots) && ($pages_count-$show_link > 1)) {
					echo ' <a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page='.($page_link - 1).'><b>...</b></a> ';
					$show_dots = "no";
				}

				echo ' <a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page='.$page_link.'>'.$page_link.'</a> '.$sperator;
			} else continue;
		}

		for ($j = 0; $j <= $show_link; $j++) {
			$i = $begin + $j;

			if ($i < 1) {
				$show_link++;
				continue;
			}

			if (!isset($show_dots) && $begin > 1) {
				echo ' <a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page='.($i-1).'><b>...</b></a> ';
				$show_dots = "no";
			}

			if ($i > $pages_count) break;
			if ($i == $page) {
				echo ' <a '.$style2.' >'.$i.'</a> ';
			}else{
				echo ' <a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page='.$i.'>'.$i.'</a> ';
			}

			if (($i != $pages_count) && ($j != $show_link)) echo $sperator;
			if (($j == $show_link) && ($i < $pages_count)) {
				echo ' <a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page='.($i+1).'><b>...</b></a> ';
		}
	}

	if ($begin + $show_link + 1 < $pages_count) {
		echo ' <a '.$style1.' href='.$_SERVER['PHP_SELF'].'?op='.limpiar($_GET["op"]).'&page='.$pages_count.'> '.$pages_count.' </a>';
	}
	return true;
}

$perpage = 20;
if (empty($_GET['page']) || ($_GET['page'] <= 0)) {
	$page = 1;
}else{
	$page = (int)$_GET['page'];
}

$count = mysql_numrows(mysql_query("SELECT `id` FROM `tb_black_wmid`"));
$pages_count = ceil($count / $perpage);

if ($page > $pages_count) $page = $pages_count;
$start_pos = ($page - 1) * $perpage;
if($start_pos<0) $start_pos=0;


$option = isset($_GET["option"]) ? limpiar(trim($_GET["option"])) : false;

if($option=="addbl" && count($_POST)>0) {
	$wmid = (isset($_POST["wmid"]) && preg_match("|^[\d]{12}$|", trim($_POST["wmid"]))) ? limpiar(trim($_POST["wmid"])) : false;
	$reason = (isset($_POST["reason"])) ? htmlspecialchars(trim($_POST["reason"])) : false;


	$sql_bl = mysql_query("SELECT `wmid` FROM `tb_black_wmid` WHERE `wmid`='$wmid'");
	$row = mysql_fetch_array($sql_bl);

	if($wmid==false || $reason==false){
		echo '<span class="msg-error">������ ��������� �� ��� ����, ���� ��������� �� ���������!!!</span>';
	}elseif(mysql_num_rows($sql_bl)>0){
		echo '<span class="msg-error">������! WMID '.$wmid.' � ������ ������!</span>';
	}else{
		mysql_query("INSERT INTO `tb_black_wmid` (`wmid`,`reason`,`date`,`ip`) VALUES('$wmid','$reason','".time()."','".@$_SERVER['REMOTE_ADDR']."')") or die(mysql_error());

		echo '<span class="msg-ok">WMID '.$wmid.' ������� �������� � ������ ������.</span>';
	}
}else{
	$wmid = false;
	$reason = false;
}

echo '<form action="'.$_SERVER["PHP_SELF"].'?op='.limpiar($_GET["op"]).'&page='.$page.'&option=addbl" method="POST">';
	echo '<table>';
		echo '<th colspan="2"><div align="center">���������� WMID � ������ ������</div></th>';
		echo '<tr><td width="200"><b>WMID:</b></td><td><input type="text" style="width:60%" name="wmid" value="'.$wmid.'"></td></tr>';
		echo '<tr><td width="200"><b>������� ����������:</b></t�><td><input type="text" style="width:60%" name="reason" value="'.$reason.'"></td></tr>';
		echo '<tr><td colspan="2"><div align="center"><input type="submit" value="�������� WMID" class="sub-blue160"></div></td></tr>';
	echo '</table>';
echo '</form>';
echo '<br><br>';


if($option=="dell") {
	$id = (isset($_GET["id"]) && preg_match("|^[\d]{1,11}$|", trim($_GET["id"]))) ? intval(limpiar(trim($_GET["id"]))) : false;

	$sql_del = mysql_query("SELECT `id` FROM `tb_black_wmid` WHERE `id`='$id'");
	if(mysql_num_rows($sql_del)>0){
		mysql_query("DELETE FROM `tb_black_wmid` WHERE `id`='$id'") or die(mysql_error());
		echo '<span class="msg-ok">WMID ������� ������ �� ������� ������.</span>';
	}else{
		echo '<span class="msg-error">������ WMID ��� ������ �� ������� ������!</span>';
	}
}

echo '<h3 class="sp" style="margin-top:0; padding-top:0;"><b>������ ��������������� WMID, ����������� ��� ����������� �� �����</b></h3>';

echo '<table>';
	echo '<tr>';
		echo '<th>ID</th>';
		echo '<th>WMID</th>';
		echo '<th>�������</th>';
		echo '<th>����</th>';
		echo '<th></th>';
	echo '</tr>';



$sql = mysql_query("SELECT * FROM `tb_black_wmid` ORDER BY `id` DESC LIMIT  $start_pos,$perpage");
if(mysql_num_rows($sql)>0) {
	while ($row=mysql_fetch_array($sql)) {
		echo '<tr align="center">';
		echo '<td>'.$row["id"].'</td>';
		echo '<td>'.$row["wmid"].'</td>';
		echo '<td>'.$row["reason"].'</td>';
		echo '<td>'.DATE("d.m.Y H:i", $row["date"]).'</td>';

		echo '<td><form method="get" action="">';
			echo '<input type="hidden" name="op" value="'.limpiar($_GET["op"]).'">';
			echo '<input type="hidden" name="page" value="'.$page.'">';
			echo '<input type="hidden" name="option" value="dell">';
			echo '<input type="hidden" name="id" value="'.$row["id"].'">';
			echo '<input type="submit" value="�������" class="sub-red">';
		echo '</form></td>';
		echo '</tr>';
	}
}else{
	echo '<tr><td colspan="5"><div style="font-weight:bold; color:#FF0000; text-align:center;">������� �� �������!</div></td></tr>';
}
echo '</table>';
if($count>$perpage) {
	echo '<div align="center">';
		universal_link_bar($page, $count, $pages_count, 10);
	echo '</div>';
}

?>