<?php

echo '<div id="rightcolumn">';

	echo '<div id="block">';
		echo '<div id="block-title">����� ������</div>';
		echo '<div id="block-text">';
			include_once('includes/my_board.php');
		echo '</div>';
	echo '</div>';

	$rand=rand(0,10);
	
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	echo '<div id="block">';
		echo '<div id="block-title-news">��� �������� �������</div>';
		echo '<div id="block-text" align="center" style="margin:0 auto; padding:0;">';
			include_once('includes/birthdays.php');
		echo '</div>';
	echo '</div>';
	}

	if($rand>'5'){
	echo '<div id="block">';
		echo '<div id="block-title-news">������� ���������</div>';
		echo '<div id="block-text" align="center" style="margin:0 auto; padding:0;">';
			include_once('includes/quick_mess_s.php');
		echo '</div>';
	echo '</div>';
	}else{
	echo '<div id="block">';
		echo '<div id="block-title">����������� �������</div>';
		echo '<div id="block-text">';
			include_once('includes/stat_kontext.php');
		echo '</div>';
	echo '</div>';
	}
	
	
	echo '<div id="block">';
		echo '<div id="block-title">������� ������ 100x100</div>';
		echo '<div id="block-text">';
			include_once('includes/banner100x100.php');
		echo '</div>';
	echo '</div>';	
	
echo '</div>';

?>