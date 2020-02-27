<?php

echo '<div id="rightcolumn">';

	echo '<div id="block">';
		echo '<div id="block-title">Доска почета</div>';
		echo '<div id="block-text">';
			include_once('includes/my_board.php');
		echo '</div>';
	echo '</div>';

	$rand=rand(0,10);
	
	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
	echo '<div id="block">';
		echo '<div id="block-title-news">Дни рождения сегодня</div>';
		echo '<div id="block-text" align="center" style="margin:0 auto; padding:0;">';
			include_once('includes/birthdays.php');
		echo '</div>';
	echo '</div>';
	}

	if($rand>'5'){
	echo '<div id="block">';
		echo '<div id="block-title-news">Быстрые сообщения</div>';
		echo '<div id="block-text" align="center" style="margin:0 auto; padding:0;">';
			include_once('includes/quick_mess_s.php');
		echo '</div>';
	echo '</div>';
	}else{
	echo '<div id="block">';
		echo '<div id="block-title">Контекстная реклама</div>';
		echo '<div id="block-text">';
			include_once('includes/stat_kontext.php');
		echo '</div>';
	echo '</div>';
	}
	
	
	echo '<div id="block">';
		echo '<div id="block-title">Реклама баннер 100x100</div>';
		echo '<div id="block-text">';
			include_once('includes/banner100x100.php');
		echo '</div>';
	echo '</div>';	
	
echo '</div>';

?>