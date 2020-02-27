<?php 
$pagetitle="Главная";
include("header.php");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_user' AND `howmany`='1'") or die(mysql_error());
$bon_reg_user = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_ref' AND `howmany`='1'") or die(mysql_error());
$bon_reg_ref = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_popoln' AND `howmany`='1'") or die(mysql_error());
$bon_popoln =  mysql_result($sql,0);

echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:2px;"><b>Добро пожаловать на '.$domen.'</b></h1>';

echo '<div class="text-justify">';
	echo '<img src="/img/user4.png" alt="" title="" class="img-left" width="170px" />';
	echo 'На сегодняшний день практически каждый человек имеет свободный доступ в Интернет. Кто-то проводит все свободное время в соц. сетях и играх, а кто-то имеет дополнительный источник дохода, работая на нашем проекте. <b style="color:#ff7f50">'.$domen.' </b>— это Система Активной Рекламы, предоставляющая возможность зарабатывать без приложений физических усилий. У нас имеется 10 способов заработка, среди которых: оплачиваемые задания, серфинг сайтов, </b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинг, чтение писем, VIP-серфинг, авто-серфинг сайтов, авто-серфинг <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>, баннерный серфинг сайтов, оплачиваемые посещения. Здесь никто не будет уточнять ваше образование и специальность, поскольку с оплачиваемыми действиями может справиться каждый! Вывести заработанные деньги можно мгновенно на популярные платёжные системы WebMoney Яндекс.Деньги и Payeer. При возникновении каких-либо вопросов вы можете обратиться в техническую поддержку, где получите развернутый ответ на свой вопрос. По мере повышения рейтинга, пользователь будет получать некоторые привилегии на <b style="color:#ff7f50">'.$domen.'</b>. У нас присутствует система начисления рефбека, благодаря которой ваши заработки вырастут в разы. Желаете иметь пассивный источник дохода? Мы можем вам его предложить! Привлекайте активных рефералов и получайте % от их заработка.';
echo '</div><br>';
echo '<h4 class="sp">Специально для Вас:</h4>';
echo '<ul class="green">';
	if($bon_reg_user>0) echo '<li><span style="color:#DE1200">бонус за регистрацию -  <b>'.number_format($bon_reg_user, 2, ".", "").' руб.</span></b>;</li>';
	if($bon_reg_ref>0) echo '<li><span style="color:#DE1200">бонус за привлечение реферала -  <b>'.number_format($bon_reg_ref, 2, ".", "").' руб.</span></b>;</li>';
	echo '<li>3-х-уровневая реферальная система;</li>';
	echo '<li>заработок на серфинге, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> серфинге, баннерном серфинге, авто-серфинге, чтении писем, выполнении заданий;</li>';
	echo '<li>интересная работа, быстрый <a href="/status.php" style="border-bottom:1px dotted;">карьерный рост</a>;</li>';
	echo '<li>мгновенные выплаты;</li>';
	echo '<li>свободный график работы;</li>';
	echo '<li>ежедневная зарплата.</li>';
echo '</ul>';

echo '<div align="center">';
echo '<tr align="center">';
		    echo '<td>';
			    echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:2px;"><b>Автоматические выплаты</b></h1>';
		        echo '<img src="/img/pay_img/webmoney.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/yandex.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/payeer.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/qiwi.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/perfect_money.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/megafon.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/mts.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/visa_mastercard.png" width="118" height="72" border="0" alt="">';
            echo '</td>';
		echo '</tr>';
echo'</div>';
echo '<br>';
echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/register.php" style="width:160px; float:none;">Приступить</span></div>';

echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:20px;"><b>Все для максимальной раскрутки</b></h1>';
echo '<div class="text-justify">';
	echo '<img src="/images/test1.png" alt="" title="" class="img-right" width="100px" />';
	echo 'Если вы имеете свой сайт, значит, вам наверняка знакома проблема с его раскруткой. ';
	echo 'Ведь сайтов миллионы, а заветное место в ТОПе предусмотрено лишь для избранных — именно так считает большинство владельцев не раскрученных ресурсов. ';
	echo 'Мы готовы развеять подобные мысли и доказать, что занять высокое место в поисковой выдаче может каждый. <b style="color:#ff7f50">'.$domen.'</b> — это эффективная рекламная площадка с выгодной для рекламодателя ценовой политикой. ';
	echo ' С помощью нашего сервиса вебмастер может заказать определённое количество уникальных за последние 24 часа посетителей на свой сайт. ';
	echo 'Благодаря тому, что Администрацией сервиса регулярно производится удаление пользователей, использующих методы заработка, не соответствующие правилам (автокликеры, автобраузеры и другое), мы можем гарантировать, что на ваш ресурс попадают реальные люди. ';
        echo 'На <b style="color:#ff7f50">'.$domen.'</b> довольно часто проводятся акции и конкурсы, во время действия которых заказать раскрутку можно по сниженной цене. Нужно отметить, что получить первый приток посетителей можно уже через несколько минут с момента размещения рекламы. В вашем арсенале 17 методов продвижения, выберете наиболее подходящий для сайта и кошелька — займите своё место в ТОПе';
echo '</div>';
echo '<h4 class="sp">Специально для Вас:</h4>';
echo '<ul class="green">';
        if($bon_popoln>0) echo '<li><span style="color:#DE1200">бонус за пополнение рекламного счета + <b>'.number_format($bon_popoln, 0, ".", "").' %</span></b>;</li>';
	echo '<li>заказ рекламы без регистрации, мгновенное размещение;</li>';
	echo '<li>множество способов эффективной и недорогой раскрутки сайтов;</li>';
	echo '<li>многофункциональная система управления рекламными кампаниями;</li>';
	echo '<li>раскрутка Вашего интернет ресурса, повышение тИЦ и PR.</li>';
echo '</ul><br>';
echo '<div align="center">';
echo '<tr align="center">';
		    echo '<td>';
			    echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:2px;"><b>Автоматическое размещение рекламы</b></h1>';
		        echo '<img src="/img/pay/webmoney.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/yandex_money.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/payeer.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/robokassa.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/qiwi.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/perfect_money.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/megakassa.png" width="118" height="72" border="0" alt="">';
            echo '</td>';
		echo '</tr>';
echo'</div>';
echo '<br>';
echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php" style="width:160px; float:none;">Рекламировать</span></div>';

include("footer.php");
?>