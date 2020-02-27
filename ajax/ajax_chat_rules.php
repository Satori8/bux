<?php
session_start();
header("Content-type: text/html; charset=windows-1251");

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	$allowed_url = array("yadi.sk", "skrinshoter.ru", "prntscr.com", "radikall.com", "radikal.ru", "rghost.ru", "joxi.ru", "mepic.ru", "screenshotlink.ru", "clip2net.com", "snag.gy");
	$status_chat_rules = (!isset($_COOKIE["status_chat_rules"]) | (isset($_COOKIE["status_chat_rules"]) && intval($_COOKIE["status_chat_rules"])!=1)) ? 0 : 1;

	echo '<div style="padding:10px 5px;">';
		echo '<div class="text-red"><b>В ЧАТе запрещено:</b></div><hr style="margin:5px auto 10px;">';
		echo '<div class="chat-rules-line"><b>1.</b> Указывать любую информацию рекламного характера, в том числе номера заданий или призыв к их выполнению, реф-ссылки, ссылки на сторонние ресурсы (за исключением ссылок на сайты скриншотов: '.implode(", ", $allowed_url).').</div>';
		echo '<div class="chat-rules-line"><b>2.</b> Использование ненормативной лексики, оскорбления, неуважительные высказывания в адрес пользователей модераторов и администрации.</div>';
		echo '<div class="chat-rules-line"><b>3.</b> Флудить (флудом является частое повторение одинаковых осмысленных или неосмысленных сообщений, как текстовых так и содержащих смайлики).</div>';
		echo '<div class="chat-rules-line"><b>4.</b> Обсуждать действия администрации или модерации, а так же УКАЗЫВАТЬ, что и как делать. </div>';
		echo '<div class="chat-rules-line"><b>5.</b> Клеветать на проект, препятствовать его развитию или отталкивать других пользователей от приобретения каких либо услуг.</div>';
		echo '<div class="chat-rules-line"><b>6.</b> Злоупотреблять большим кол-вом смайликов: сообщение из более чем 3-5 смайлов в диалоге в чате.</div>';
		echo '<div class="chat-rules-line"><b>7.</b> Злоупотреблять верхним регистром символов (Caps Lock). Злоупотреблять использованием ВВ-кодов в написании сообщений. Это является дурным тоном и неуважением к другим пользователям.</div>';
		echo '<div class="chat-rules-line"><b>8.</b> Запрещена в любом виде пропаганда насилия, расизма, политических и националистический идей, извращений, употребления наркотиков и анти-религиозные высказывания.</div>';
		echo '<div class="chat-rules-line" style="margin-top:20px;"> - Модераторы ЧАТа имеют право заблокировать нарушителям правил доступ к ЧАТу на любой срок по собственному усмотрению.</div>';
		echo '<div class="chat-rules-line" style="margin-bottom:20px;"> - Администрация сайта имеет право наказывать нарушителей, вплоть до блокировки аккаунта!</div>';
		echo '<div style="text-align:center; font-weight:bold;">Не нарушайте правила! Общайтесь в своё удовольствие.</div>';
		if($status_chat_rules!=1) echo '<div style="text-align:center; margin-top:15px;"><span class="sd_sub big green" onClick="setCookie(\'status_chat_rules\', 1, \'/\', (location.hostname || document.domain)); $.modalpopup(\'close\'); return false;">С правилами ознакомлен</span></div>';
	echo '</div>';
}

?>