<?php
$pagetitle="Достижения";
include('header.php');
?><script type="text/javascript">
function ShowHideBlock(id) {
	if($("#adv-title"+id).attr("class") == "adv-title-open") {
		$("#adv-title"+id).attr("class", "adv-title-close")
	} else {
		$("#adv-title"+id).attr("class", "adv-title-open")
	}
	$("#adv-block"+id).slideToggle("slow");
}

function setChecked(type){
	var nodes = document.getElementsByTagName("input");
	for (var i = 0; i < nodes.length; i++) {
		if (nodes[i].name == "country[]") {
			if(type == "paste") nodes[i].checked = true;
			else  nodes[i].checked = false;
		}
	}
}
</script><?php

echo '<div id="InfoAds" style="display:block; align:justify; margin-bottom:15px;">';
	echo '<span id="adv-title-info" class="adv-title-open" onclick="ShowHideBlock(\'-info\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">Добро пожаловать на <b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b></span>';
	echo '<div id="adv-block-info" style="display:block; padding:5px 7px 10px 7px; text-align:justify; background-color:#FFFFFF;">';
//echo '<h3 class="sp" style="text-align:left;">Добро пожаловать на <b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b></h3>';
echo '<b>'.strtoupper($_SERVER["HTTP_HOST"]).'</b> - место не только для заработка, но и для самореализации. Каждый, работая на нашем проекте может выделиться своей индивидуальностью и не быть как все! Работая на проекте, Вы приобретете не только опыт, но и определенные достижения, которые позволят Вам выделиться среди участников проекта. Начав карьеру с самого низа, Вы можете войти в ТОП – пользователей проекта, тем самым затмив остальных пользователей своими наградами и суммой заработанных средств на счету.<br>';
echo '</div>';

echo '<span id="adv-title-bl" class="adv-title-open" onclick="ShowHideBlock(\'-bl\');" style="border-top:solid 1px #46A2FF; text-align:left; padding-left:50px; margin-bottom:2px;">Как получить достижение?</span>';
		echo '<div id="adv-block-bl" style="display:block; padding:2px 0px 10px 0px; text-align:center; background-color:#FFFFFF;">';
                         echo '<div style="color:#a85300; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#fbefdf" align="center"> ';
//echo '<h3 class="sp" style="text-align:left;">Как получить достижение?</h3>';
echo 'Для получения достижений, Вам нужно сделать необходимое количество действий<br> или же набрать определённую сумму на свой счет.<br>';
echo 'Все Ваши достижения будут отображаться у Вас на стене.<br><br>';
echo '</div>';
echo '</div>';
echo '</div>';

echo '<table class="tables">';
echo '<thead><tr align="center">';
	echo '<th colspan="6">Виды достижений</th>';
echo '</tr></thead>';

echo '<tr align="center">';
	echo '<td></td>';
	echo '<td><b>Бронза</b></td>';
	echo '<td><b>Серебро</b></td>';
	echo '<td><b>Золото</b></td>';
	echo '<td><b>Платина</b></td>';
	echo '<td><b>Изумруд</b></td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="width:190px; padding-left:10px;"><b>Кликер:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Кликер"><img src="/img/progress/clicker.png"></div>100 кл.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Кликер"><img src="/img/progress/clicker.png"></div>1000 кл.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Кликер"><img src="/img/progress/clicker.png"></div>5000 кл.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Кликер"><img src="/img/progress/clicker.png"></div>10000 кл.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Кликер"><img src="/img/progress/clicker.png"></div>20000 кл.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Задания:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Задания"><img src="/img/progress/tasker.png"></div>100 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Задания"><img src="/img/progress/tasker.png"></div>300 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Задания"><img src="/img/progress/tasker.png"></div>500 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Задания"><img src="/img/progress/tasker.png"></div>700 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Задания"><img src="/img/progress/tasker.png"></div>1000 исп.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Письма:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Письма"><img src="/img/progress/mailser.png"></div>100 чт.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Письма"><img src="/img/progress/mailser.png"></div>500 чт.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Письма"><img src="/img/progress/mailser.png"></div>700 чт.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Письма"><img src="/img/progress/mailser.png"></div>1000 чт.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Письма"><img src="/img/progress/mailser.png"></div>2000 чт.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Исполнитель тестов:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Тесты"><img src="/img/progress/tester.png"></div>100 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Тесты"><img src="/img/progress/tester.png"></div>500 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Тесты"><img src="/img/progress/tester.png"></div>700 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Тесты"><img src="/img/progress/tester.png"></div>1000 исп.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Тесты"><img src="/img/progress/tester.png"></div>2000 исп.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Рефовод:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Рефовод"><img src="/img/progress/refovod.png"></div>100 реф.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Рефовод"><img src="/img/progress/refovod.png"></div>500 реф.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Рефовод"><img src="/img/progress/refovod.png"></div>1000 реф.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Рефовод"><img src="/img/progress/refovod.png"></div>3000 реф.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Рефовод"><img src="/img/progress/refovod.png"></div>5000 реф.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Участник конкурсов:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Участник конкурсов"><img src="/img/progress/konkurser.png"></div>100 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Участник конкурсов"><img src="/img/progress/konkurser.png"></div>500 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Участник конкурсов"><img src="/img/progress/konkurser.png"></div>700 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Участник конкурсов"><img src="/img/progress/konkurser.png"></div>1000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Участник конкурсов"><img src="/img/progress/konkurser.png"></div>5000 руб.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Торговец рефералами:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Торговец рефералами"><img src="/img/progress/refbirj_prod.png"></div>500 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Торговец рефералами"><img src="/img/progress/refbirj_prod.png"></div>1000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Торговец рефералами"><img src="/img/progress/refbirj_prod.png"></div>2000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Торговец рефералами"><img src="/img/progress/refbirj_prod.png"></div>3000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Торговец рефералами"><img src="/img/progress/refbirj_prod.png"></div>5000 руб.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Покупатель рефералов:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Покупатель рефералами"><img src="/img/progress/refbirj_pok.png"></div>500 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Покупатель рефералами"><img src="/img/progress/refbirj_pok.png"></div>1000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Покупатель рефералами"><img src="/img/progress/refbirj_pok.png"></div>2000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Покупатель рефералами"><img src="/img/progress/refbirj_pok.png"></div>3000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Покупатель рефералами"><img src="/img/progress/refbirj_pok.png"></div>5000 руб.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Рекламодатель:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Рекламодатель"><img src="/img/progress/advertisers.png"></div>1000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Рекламодатель"><img src="/img/progress/advertisers.png"></div>3000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Рекламодатель"><img src="/img/progress/advertisers.png"></div>5000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Рекламодатель"><img src="/img/progress/advertisers.png"></div>10000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Рекламодатель"><img src="/img/progress/advertisers.png"></div>20000 руб.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="padding-left:10px;"><b>Много заработал:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="Много заработал"><img src="/img/progress/moneysers.png"></div>100 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="Много заработал"><img src="/img/progress/moneysers.png"></div>500 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="Много заработал"><img src="/img/progress/moneysers.png"></div>1000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="Много заработал"><img src="/img/progress/moneysers.png"></div>5000 руб.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="Много заработал"><img src="/img/progress/moneysers.png"></div>10000 руб.</td>';
echo '</tr>';

echo '<tr>';
	echo '<td align="left" style="width:190px; padding-left:10px;"><b>Активный участник форума:</b></td>';
	echo '<td align="center"><div class="progress_style stat_1" title="forum"><img src="/img/progress/forum.png"></div>100 пос.</td>';
	echo '<td align="center"><div class="progress_style stat_2" title="forum"><img src="/img/progress/forum.png"></div>500 пос.</td>';
	echo '<td align="center"><div class="progress_style stat_3" title="forum"><img src="/img/progress/forum.png"></div>1000 пос.</td>';
	echo '<td align="center"><div class="progress_style stat_4" title="forum"><img src="/img/progress/forum.png"></div>3000 пос.</td>';
	echo '<td align="center"><div class="progress_style stat_5" title="forum"><img src="/img/progress/forum.png"></div>5000 пос.</td>';
echo '</tr>';


echo '</table>';


include('footer.php');?>