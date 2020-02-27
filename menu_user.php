<div class="usermenu">

<?php
$vblock1 = ( (isset($_COOKIE["vblock1"]) && limpiar($_COOKIE["vblock1"])=="1") | !isset($_COOKIE["vblock1"]) ) ? 'style="display: block;"' : 'style="display: none;"';
$vblock2 = (isset($_COOKIE["vblock2"]) && limpiar($_COOKIE["vblock2"])=="1") ? 'style="display: block;"' : 'style="display: none;"';
$vblock3 = ( (isset($_COOKIE["vblock3"]) && limpiar($_COOKIE["vblock3"])=="1") | !isset($_COOKIE["vblock3"]) ) ? 'style="display: block;"' : 'style="display: none;"';
$vblock4 = (isset($_COOKIE["vblock4"]) && limpiar($_COOKIE["vblock4"])=="1") ? 'style="display: block;"' : 'style="display: none;"';
$vblock5 = (isset($_COOKIE["vblock5"]) && limpiar($_COOKIE["vblock5"])=="1") ? 'style="display: block;"' : 'style="display: none;"';
$vblock6 = (isset($_COOKIE["vblock6"]) && limpiar($_COOKIE["vblock6"])=="1") ? 'style="display: block;"' : 'style="display: none;"';
$vblock7 = (isset($_COOKIE["vblock7"]) && limpiar($_COOKIE["vblock7"])=="1") ? 'style="display: block;"' : 'style="display: none;"';
$vblock8 = (isset($_COOKIE["vblock8"]) && limpiar($_COOKIE["vblock8"])=="1") ? 'style="display: block;"' : 'style="display: none;"';
?>

<span id="mnu_title1" class="usermenutitle-g">Заработать</span>
<div id="mnu_tblock1" class="usermenublock" <?=$vblock1;?>>
	<a href="/view_task.php?page=task" class="usermenuline">Выполнение заданий</a>
	<a href="/surfings" class="usermenuline">Серфинг сайтов</a>
	<a href="/surfingsy" class="usermenuline"><b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b> серфинг</a>
	<a href="/work-pay-visits" class="usermenuline">Оплачиваемые посещения</a>
	<a href="/tests-views" class="usermenuline">Прохождение тестов</a>
	<a href="/views_mails_new.php" class="usermenuline">Чтение писем</a>
	<a href="/views_sites_as.php" class="usermenuline">Авто-серфинг сайтов</a>	
	<a href="/surfing_asgo" class="usermenuline">Авто-серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></a>
	<a href="/refkonkurs.php?page=view" class="usermenuline">Конкурсы от реферера <?php echo ( (isset($count_ref_kon) && $count_ref_kon>0) ? '<b style="color:black;">[</b><b style="color:red;">'.$count_ref_kon.'</b><b style="color:black;">]</b>' : '<b style="color:black;">[0]</b>');?></a>	
	<a href="/investor_tools" class="usermenuline">Кабинет инвестора</a>
</div>

<span id="mnu_title8" class="usermenutitle-g">Партнерская программа</span>
<div id="mnu_tblock8" class="usermenublock"<?=$vblock8;?>>
	<a href="/partner.php" class="usermenuline">Партнерская программа</a>
	<a href="/reflinks.php" class="usermenuline" style="color:#FF0000;"><b>Рекламные материалы</b></a>
</div>

<span id="mnu_title2" class="usermenutitle-r">Реклама</span>
<div id="mnu_tblock2" class="usermenublock" <?=$vblock2;?>>
	<a href="/cabinet_ads" class="usermenuline">Управление рекламой</a>
	<a href="/advertise.php" class="usermenuline">Заказать рекламу</a>
	<a href="/advertise.php?ads=packet" class="usermenuline">Пакеты рекламы</a>
	<?php if($test_drive==0) {echo '<a href="/advertise.php?ads=test_drive" class="usermenuline" style="color:#FF0000;"><b>Test Drive</b></a>';} ?>
	<?php if($test_drive_youtube==0) {echo '<a href="/advertise.php?ads=test_drive_youtube" class="usermenuline" style="color:#FF0000;"><b>Test Drive</b> <b></b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b</a>';} ?>
        <a href="/adv_mailforrefs.php" class="usermenuline">Рассылка пользователям</a>
	<a href="/ads_task.php" class="usermenuline">Управление заданиями</a>
	<a href="/black_list_all.php" class="usermenuline">Черный список</a>
</div>

<span id="mnu_title3" class="usermenutitle-b">Аккаунт</span>
<div id="mnu_tblock3" class="usermenublock" <?=$vblock3;?>>
	<a href="/profile.php" class="usermenuline">Профиль</a>
	<a href="/members.php" class="usermenuline">Моя статистика</a>
	<a href="/wall.php?uid=<?=$partnerid;?>" class="usermenuline">Стена пользователя</a>
	<a href="/vacation.php" class="usermenuline">Уйти в отпуск</a>
	<a href="/history.php" class="usermenuline">История операций</a>
	<a href="/status.php" class="usermenuline">Карьерный рост</a>
	<a href="/progress.php" class="usermenuline">Достижения</a>
	<a href="/stat.php" class="usermenuline">Лидеры Проекта</a>
	<a href="/notebook.php" class="usermenuline">Блокнот</a>
	<a href="/bannedusers.php" class="usermenuline" style="color:#FF0000;"><b>Нарушители</b></a>
	<a href="/newmsg.php?name=Admin" class="usermenuline">Тех.поддержка <?php echo''.$where_user.'';?></a>
</div>

<span id="mnu_title4" class="usermenutitle-b">Работа с рефералами</span>
<div id="mnu_tblock4" class="usermenublock" <?=$vblock4;?>>
	<a href="/reflinks.php" class="usermenuline">Рекламные материалы</a>
	<a href="/referals1.php" class="usermenuline">Мои рефералы 1-го уровня</a>
	<a href="/referals2.php" class="usermenuline">Мои рефералы 2-го уровня</a>
	<a href="/referals3.php" class="usermenuline">Мои рефералы 3-го уровня</a>
	
	<a href="/welcome_ref.php" class="usermenuline">Приветствие для рефералов</a>
	<a href="/ref_bonus.php" class="usermenuline">Бонусы рефералам</a>
	
	<a href="/mailforrefs.php" class="usermenuline">Рассылка рефералам I уровня</a>
	<a href="/mailforrefs2.php" class="usermenuline">Рассылка рефералам II уровня</a>
	<a href="/mailforrefs3.php" class="usermenuline">Рассылка рефералам III уровня</a>
	<a href="/null_referer.php" class="usermenuline">Выкуп у реферера</a>
        <a href="/free-user" class="usermenuline">Свободные пользователи</a>
    
</div>

<span id="mnu_title5" class="usermenutitle-b">Мои сообщения</span>
<div id="mnu_tblock5" class="usermenublock" <?=$vblock5;?>>
	<a href="/newmsg.php" class="usermenuline">Новое сообщение</a>
	<a href="/inbox.php" class="usermenuline">Входящие сообщения<?php echo ( (isset($inbox) && $inbox>0) ? '<b style="color:black;">[</b><b style="color:red;">'.$inbox.'</b><b style="color:black;">]</b>' : '<b style="color:black;">[0]</b>');?></a>
	<a href="/outbox.php" class="usermenuline">Исходящие сообщения</a>
	
</div>

<span id="mnu_title6" class="usermenutitle-b">Конкурсы</span>
<div id="mnu_tblock6" class="usermenublock" <?=$vblock6;?>>
	<a href="/refkonkurs.php?page=view" class="usermenuline">Конкурсы от реферера <?php echo ( (isset($count_ref_kon) && $count_ref_kon>0) ? '<b style="color:black;">[</b><b style="color:red;">'.$count_ref_kon.'</b><b style="color:black;">]</b>' : '<b style="color:black;">[0]</b>');?></a>
	<a href="/refkonkurs.php?page=myview" class="usermenuline">Конкурсы для рефералов <?php echo ( (isset($count_my_ref_kon) && $count_my_ref_kon>0) ? '<b style="color:black;">[</b><b style="color:red;">'.$count_my_ref_kon.'</b><b style="color:black;">]</b>' : '<b style="color:black;">[0]</b>');?></a>
</div>


<span id="mnu_title7" class="usermenutitle-b">Биржа рефералов</span>
<div id="mnu_tblock7" class="usermenublock" <?=$vblock7;?>>
	<a href="/refbirj.php" class="usermenuline">Купить рефералов</a>
	<a href="/ref.php" class="usermenuline">Продать рефералов</a>
</div>


<a href="/logout.php" class="usermenutitle-r" style="color:#FFF;">Выход</a>


</div>