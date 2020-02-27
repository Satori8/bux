<?php
$pagetitle="Отпуск";
include('header.php');
require_once('.zsecurity.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="msg-error">Ошибка! Для доступа к этой странице необходимо авторизоваться!</fieldset>';
	include('footer.php');
	exit();
}else{
	require('config.php');


	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_noactive' AND `howmany`='1'");
	$reit_noactive = mysql_result($sql,0,0);

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
         
        echo '<div style="color:#107; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f0ffff" align="justify"> ';
	echo '<img src="img/vacation.jpg"width="70" height="64" align="left">';
		echo '<b>Запланировали отпуск?</b> Вы не сможете посещать свой аккаунт продолжительное время и боитесь потерять его по причине двух месяцев неактивности? ';
		echo 'На <b>'.$domen.'</b> вы можете взять отпуск продолжительностью до 6 месяцев. Мы не ограничиваем как долго вы можете отсутствовать. ';
		echo 'По истечению срока одного отпуска вы можете сразу взять ещё, если сочтёте это необходимым. Предоставление права на отпуск — <b>бесплатно</b>.';
	echo '<br>';

	echo '<h2 class="sp">Почему полезно брать отпуск?</h2>';
	echo '<div align="justify">';
		echo 'Если вы планируете отсутствовать более 2-х месяцев, то очень важно ставить аккаунт в режим "отпуска". ';
		echo 'Во-первых, потому что пока аккаунт в отпуске, система не удалит его за неактивность согласно <a href="/tos.php" target="_blank">Правил проекта, пункт 1.15</a>. ';
		echo 'Во-вторых, на период отпуска аккаунт переходит в защищённый режим, в котором невозможно производить никаких действий до тех пор, ';
		echo 'пока владелец аккаунта не снимет режим отпуска путём ввода пароля для операций. Это защитит аккаунт от возможных злоумышленников.';
	echo '<br>';

	echo '<div align="center">';
		echo '<h2 class="sp"><b>Укажите количество дней отпуска и нажмите кнопку "В отпуск!"<br>(Не более 180 дней)</b></h2><br>';

		echo '<form name="count_vacation" onsubmit="return false;" id="newform">';
			echo '<input type="hidden" name="cnt" value="'.md5($username+DATE("H")+24664).'" />';
			echo '<input type="text" name="count_vac" maxlength="3" value="60" class="ok12" style="text-align:center;"><br><br>';
			echo '<span class="sub-blue" style="float:none; font-size:12px;" onclick="javascript:go_vacation();" />В отпуск!</a><br>';
		echo '</form>';
	echo '</div>';
echo '</div>';

         echo '<div style="color:#107; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="justify"> ';
	echo '<h2 class="sp">Есть два вида отпуска</h2>';
	echo '<b>1.</b> По-умолчанию, пока аккаунт находится в отпуске, на него действуют несколько ограничений:<br>';
	echo '<ul class="red">';
		echo '<li>Еженедельно снимается '.$reit_noactive.' балл рейтинга за неактивность на общих основаниях;</li>';
		echo '<li>Не начисляется доход от рефералов. При этом сами рефералы и ваш реферер получают заслуженные рефотчисления;</li>';
		echo '<li>По окончанию срока отпуска, если он не был прерван пользователем ранее, аккаунт вновь переходит в рабочий режим и через два месяца после этого может быть удалён, если владелец аккаунта не успеет вернуться.</li>';
	echo '</ul>';
	echo '<b>2.</b> В рамках услуги "Обслуживание аккаунта" система позаботится об аккаунте, пока его владелец находится в отпуске:<br>';
	echo '<ul class="green">';
		echo '<li>Во время отпуска не снимается рейтинг за неактивность;</li>';
		echo '<li>Начисляются доходы от рефералов;</li>';
		echo '<li>Автоответчик отвечает на ваши входящие письма, указывая время, когда вы вернётесь из отпуска;</li>';
		echo '<li>Аккаунт не удаляется за неактивность на проекте в течение всего срока действия услуги, независимо от того, находится аккаунт в отпуске или нет.</li>';
	echo '</ul><br>';


	echo '<div align="center">';
		echo '<h2 class="sp">Заказать услугу "Обслуживание аккаунта"</h2>';
		if($vac2>0 && $vac2>time()) {
			echo '<span class="msg-ok">Ваш аккаунт обслуживается. Окончание '.DATE("d.m.Y",$vac2).'</span>';
		}else{
			echo '<span class="msg-w">У вас ещё нет услуги "Обслуживание аккаунта"</span>';
		}

		echo 'Услуга "Обслуживание аккаунта" предоставляется сразу после оплаты и не зависит от того, находится аккаунт в отпуске или нет. Стоимость услуги <b>20.00</b> рублей в месяц. Укажите количество месяцев и нажмите кнопку "Оплатить"<br><br>';

		echo '<form name="pay_vac" onsubmit="return false;" id="newform">';
			echo '<input type="hidden" name="cnt" value="'.md5($username+DATE("H")+24664).'" />';
			echo '<input type="text" name="count_month" maxlength="3" value="3" class="ok12" style="text-align:center;"><br><br>';
			echo '<span class="sub-blue" style="float:none; font-size:12px;" onclick="javascript:pay_vacation();" />Оплатить</a><br>';
		echo '</form>';
	echo '</div>';
    echo '</div>';
 echo '</div>';

}

include('footer.php');?>