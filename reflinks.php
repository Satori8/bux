<?php
$pagetitle="Рекламные материалы";
include('header.php');
if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include('footer.php');
	exit();
}else{
	?><script type="text/javascript" language="JavaScript">
		function LoadRBlock(x) {
			var GetClass = $("#block-"+x).attr("class");
			for (z=1; z<=5; z++) {
				if (elem = $("#LoadBlock-"+z)) {$("#LoadBlock-"+z).attr("style", "display: none;");}
				if (elem = $("#block-"+z)) {if (z == x) {elem.attr("class", "refblock-line-active");} else {elem.attr("class", "refblock-line");}			        }
			}
			$("#LoadBlock-"+x).slideToggle("slow");
			return true;
		}

		function OpenBC(id) {
			$("#BCBlock-"+id).slideToggle("fast"); return true;
		}
	</script><?php

	echo '<table id="newform" class="" style="width:100%; margin:0 auto;">';
	echo '<tr><td align="center" style="margin:0; padding:0;">';

		echo '<h5 class="sp" style="font-size:16px; border-bottom:none; text-align:center; margin-top:10px; margin-bottom:2px;">Ваша ссылки для привлечения рефералов и рекламодателей:</h5>';
		echo '<b><font color="#000"> Реф ссылка на главную стр. </font></b><br />';
		echo '<input style="width:468px; text-align:center; margin:5px; padding:5px; display: block;" type="text" value="'.$url.'?r='.$partnerid.'" onFocus="this.select();"  readonly class="ok" /><br />';
		echo '<b><font color="#000"> Реф ссылка на стр регистрации.</font></b><br />';
		echo '<input style="width:468px; text-align:center; margin:5px; padding:5px; display: block;" type="text" value="'.$url.'register.php?r='.$partnerid.'" onFocus="this.select();"  readonly class="ok"><br />';
		echo '<b><font color="#000"> Реф ссылка на стр заказа рекламы.</font></b><br />';
		echo '<input style="width:468px; text-align:center; margin:5px; padding:5px; display: block;" type="text" value="'.$url.'advertise.php?r='.$partnerid.'" onFocus="this.select();" readonly class="ok"><br />';
		echo '<b><font color="#000"> Реф ссылка на стр просмотра ссылок в серфинге.</font></b><br />';
		echo '<input style="width:468px; text-align:center; margin:5px; padding:5px; display: block;" type="text" value="'.$url.'surfings?r='.$partnerid.'" onFocus="this.select();"  readonly class="ok"><br />';
		echo '<b><font color="#000"> Реф ссылка на стр просмотра видеороликов в серфинге YouTube.</font></b><br />';
		echo '<input style="width:468px; text-align:center; margin:5px; padding:5px; display: block;" type="text" value="'.$url.'surfingsy?r='.$partnerid.'" onFocus="this.select();"  readonly class="ok" />';

		echo '<div class="refblock-lines">';
			echo '<span id="block-1" class="refblock-line-active" onclick="LoadRBlock(\'1\');">468x60</span>';
			echo '<span id="block-2" class="refblock-line" onclick="LoadRBlock(\'2\');">200x300</span>';
			echo '<span id="block-3" class="refblock-line" onclick="LoadRBlock(\'3\');">100x100</span>';
			echo '<span id="block-4" class="refblock-line" onclick="LoadRBlock(\'4\');">728x90</span>';
			//echo '<span id="block-5" class="refblock-line" onclick="LoadRBlock(\'5\');">88x31</span>';
		echo '</div>';

		echo '<div id="LoadBlock-1" style="display: block;">';
			echo '<h5 class="sp" style="font-size:16px; border-bottom:none; text-align:center; margin-top:0px;">Баннеры 468x60</h5>';
			echo 'Для привлечения рефералов вы можете использовать любые баннеры, расположенные ниже.<br>Кликните по баннеру, чтобы получить его код.<br>';
			for($i=1; $i>=1; $i--) {
				echo '<img onClick="OpenBC(\'1'.$i.'\');" src="img/banners/468x60_'.$i.'.gif" width="468" height="60" border="0" style="margin:6px auto 4px; padding:4px; display:block; cursor:pointer; background-color:#EDEDED;">';
				echo '<div id="BCBlock-1'.$i.'" style="display: none;">';
					echo 'HTML код<br><textarea style="overflow: hidden; height:70px; width:468px;" onFocus="this.select();" readonly class="ok"><a href="'.$url.'?r='.$partnerid.'" target="_blank"><img src="'.$url.'img/banners/468x60_'.$i.'.gif" width="468" height="60" border="0"></a></textarea><br>';
					echo 'Ссылка на баннер<br><textarea style="overflow: hidden; height:16px; width:468px;" onFocus="this.select();" readonly class="ok">'.$url.'img/banners/468x60_'.$i.'.gif</textarea><br><br>';
				echo '</div>';
			}
		echo '</div>';

		echo '<div id="LoadBlock-2" style="display: none;">';
			echo '<h5 class="sp" style="font-size:16px; border-bottom:none; text-align:center; margin-top:0px;">Баннеры 200x300</h5>';
			echo 'Для привлечения рефералов вы можете использовать любые баннеры, расположенные ниже.<br>Кликните по баннеру, чтобы получить его код.<br>';
			for($i=1; $i>=1; $i--) {
				echo '<img onClick="OpenBC(\'2'.$i.'\');" src="img/banners/200x300_'.$i.'.gif" width="200" height="300" border="0" style="margin:6px auto 4px; padding:4px; display:block; cursor:pointer; background-color:#EDEDED;">';
				echo '<div id="BCBlock-2'.$i.'" style="display: none;">';
					echo 'HTML код<br><textarea style="overflow: hidden; height:70px; width:468px;" onFocus="this.select();" readonly class="ok"><a href="'.$url.'?r='.$partnerid.'" target="_blank"><img src="'.$url.'img/banners/200x300_'.$i.'.gif" width="200" height="300" border="0"></a></textarea><br>';
					echo 'Ссылка на баннер<br><textarea style="overflow: hidden; height:16px; width:468px;" onFocus="this.select();" readonly class="ok">'.$url.'img/banners/200x300_'.$i.'.gif</textarea><br><br>';
				echo '</div>';
			}
		echo '</div>';

		echo '<div id="LoadBlock-3" style="display: none;">';
			echo '<h5 class="sp" style="font-size:16px; border-bottom:none; text-align:center; margin-top:0px;">Баннеры 100x100</h5>';
			echo 'Для привлечения рефералов вы можете использовать любые баннеры, расположенные ниже.<br>Кликните по баннеру, чтобы получить его код.<br>';
			for($i=1; $i>=1; $i--) {
				echo '<img onClick="OpenBC(\'3'.$i.'\');" src="img/banners/100x100_'.$i.'.gif" width="100" height="100" border="0" style="margin:6px auto 4px; padding:4px; display:block; cursor:pointer; background-color:#EDEDED;">';
				echo '<div id="BCBlock-3'.$i.'" style="display: none;">';
					echo 'HTML код<br><textarea style="overflow: hidden; height:70px; width:468px;" onFocus="this.select();" readonly class="ok"><a href="'.$url.'?r='.$partnerid.'" target="_blank"><img src="'.$url.'img/banners/100x100_'.$i.'.gif" width="100" height="100" border="0"></a></textarea><br>';
					echo 'Ссылка на баннер<br><textarea style="overflow: hidden; height:16px; width:468px;" onFocus="this.select();" readonly class="ok">'.$url.'img/banners/100x100_'.$i.'.gif</textarea><br><br>';
				echo '</div>';
			}
		echo '</div>';

		echo '<div id="LoadBlock-4" style="display: none;">';
			echo '<h5 class="sp" style="font-size:16px; border-bottom:none; text-align:center; margin-top:0px;">Баннеры 728x90</h5>';
			echo 'Для привлечения рефералов вы можете использовать любые баннеры, расположенные ниже.<br> Кликните по баннеру, чтобы получить его код.<br>';
			for($i=1; $i>=1; $i--) {
				echo '<img onClick="OpenBC(\'4'.$i.'\');" src="img/banners/728x90_'.$i.'.gif" width="700" height="90" border="0" style="margin:6px auto 4px; padding:4px; display:block; cursor:pointer; background-color:#EDEDED;">';
				echo '<div id="BCBlock-4'.$i.'" style="display: none;">';
					echo 'HTML код<br><textarea style="overflow: hidden; height:50px; width:700px;" onFocus="this.select();" readonly class="ok"><a href="'.$url.'?r='.$partnerid.'" target="_blank"><img src="'.$url.'img/banners/728x90_'.$i.'.gif" width="728" height="90" border="0"></a></textarea><br>';
					echo 'Ссылка на баннер<br><textarea style="overflow: hidden; height:16px; width:700px;" onFocus="this.select();" readonly class="ok">'.$url.'img/banners/728x90_'.$i.'.gif</textarea><br><br>';
				echo '</div>';
			}
		echo '</div>';
		
		echo '<div id="LoadBlock-5" style="display: none;">';
			echo '<h5 class="sp" style="font-size:16px; border-bottom:none; text-align:center; margin-top:0px;">Ѕаннеры 88x31</h5>';
			echo 'Для привлечени¤ рефералов вы можете использовать любые баннеры, расположенные ниже.<br> Кликните по баннеру, чтобы получить его код.<br>';
			for($i=1; $i>=1; $i--) {
				echo '<img onClick="OpenBC(\'5'.$i.'\');" src="img/banners/88x31_'.$i.'.gif" width="88" height="31" border="0" style="margin:6px auto 4px; padding:4px; display:block; cursor:pointer; background-color:#EDEDED;">';
				echo '<div id="BCBlock-5'.$i.'" style="display: none;">';
					echo 'HTML код<br><textarea style="overflow: hidden; height:50px; width:468px;" onFocus="this.select();" readonly class="ok"><a href="http://supreme-garden.ru.com?r='.$partnerid.'" target="_blank"><img src="'.$url.'img/banners/88x31_'.$i.'.gif" width="88" height="31" border="0"></a></textarea><br>';
					echo 'Ссылка на баннер<br><textarea style="overflow: hidden; height:16px; width:468px;" onFocus="this.select();" readonly class="ok">'.$url.'img/banners/88x31_'.$i.'.gif</textarea><br><br>';
				echo '</div>';
			}
		echo '</div>';

	echo '</td></tr>';
	echo '</table>';

}
include('footer.php');?>