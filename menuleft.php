<?php

echo '<div id="leftcolumn">';

	if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		echo '<div id="block">';
			echo '<div id="block-title">'.$username.' (ID:'.$partnerid.')</div>';
			echo '<div id="block-text">';
				include_once('memberstats.php');
			echo '</div>';
		echo '</div>';
		
		/*echo '<div id="block">';
			echo '<div id="block-title-pohot">Акция</div>';
			echo '<div id="block-text" align="center">';
			include_once('bonus_status.php');
				
			echo '</div>';
		echo '</div>';*/

               echo '<div id="block">';
			echo '<div id="block-title">Аукцион</div>';
			echo '<div id="block-text" align="center">';
				echo $auction.'<br /><span class="proc-btn" onClick="document.location.href=\'/auction.php\'">Перейти в аукцион</span>';
			echo '</div>';
		echo '</div>';

                echo '<div id="block-menu">';
			echo '<div id="block-text-menu">';
				include_once('menu_user.php');
			echo '</div>';
		echo '</div>';
		
		echo '<div id="block">';
			echo '<div id="block-title">Копилка нашего проекта</div>';
			echo '<div id="block-content" align="center">';
				if (!DEFINED("KOPILKA")) DEFINE("KOPILKA", true);
				require_once("kopilka/kopilka.php");
			echo '</div>';
		echo '</div>';

	}else{
		echo '<div id="block-stat">';
			include_once('sitestats.php');
	echo '</div>';

		echo '<div id="block-stat">';
				include_once('includes/login_form.php');
		echo '</div>';
/*
                echo '<div id="block">';
		echo '<div id="block-title-news">TEST DRIVE</div>';
		echo '<div id="block-text">';
			echo '<div align="center"><a href="http://supreme-garden.ru/advertise.php?ads=test_drive"><img src="/img/banners/SG-TEST.gif" width="200" height="100" border="0"></a></div>';
		echo '</div>';
	echo '</div>';
*/
		echo '<div id="block">';
			echo '<div id="block-title">Реклама баннер 200x300</div>';
			echo '<div id="block-text">';
				include_once('includes/banner200x300.php');
			echo '</div>';
		echo '</div>';
	}
   if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
		echo '<div id="block">';
			echo '<div id="block-title">Реклама баннер 200x300</div>';
			echo '<div id="block-text">';
				include_once('includes/banner200x300.php');
			echo '</div>';
		echo '</div>';
		

	}
		echo '<div id="block">';
		echo '<div id="block-title">Подсказка</div>';
		echo '<div id="block-content" style="margin:0px; padding:0px;">';
			include_once('includes/hint_tips.php');
		echo '</div>';
	echo '</div>';
	

echo '</div>';

?>