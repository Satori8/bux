<?php 

$email_conf = array (
	'smtp_host' => 'mail.scorpionbux.info',
	'smtp_log' => 'support@scorpionbux.info',
	'smtp_pass' => '8T1h3Z1v',
	'smtp_port' => '25',
	'smtp_name' => 'ScorpionBux',
	'smtp_charset' => 'UTF-8',
);

$email_temp = array (

  'rega_1' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
		  <tbody>
		    <tr><td align="center">
		      <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
		        <tbody>
		          <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
		          <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Добро пожаловать на проект <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
		          <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">Код для подтверждения E-mail: <b>{CODE}</b></td></tr>
		          <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
		        </tbody>
		      </table>
		    </td></tr>
		  </tbody>
		</table>
  ',
  
  'rega_2' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Добро пожаловать на проект <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    <u>Ваши регистрационные данные:</u><br><br>
								ID: <b>{ID}</b><br>
								Логин: <b>{LOGIN}</b><br>
								Пароль: <b>{PASS}</b><br>
								Пароль для операций: <b>{PIN}</b><br>
								IP адрес: <b>{IP}</b><br><br>
								<span style="color:#FF0000;">Внимание! Обязательно сохраните данные. Пароль можно изменить в разделе профиль.</span>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'login' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Уведомление безопасности <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    <u><b>{LOGIN}</b></u><br><br>
								<b>Вы получили это письмо, потому что в Ваш аккаунт на проекте <a style="text-decoration:none; color:#000;">scorpionbux.info</a></b><br>
								<b>выполнен вход с</b><br>
								IP адреса: <b>{IP}</b><br>
								<b>если это были не вы срочно авторизуйтись и измените пароль!</b>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'recc_1' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Активация аккаунта на проекте <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						  <u>Ваши регистрационные данные:</u><br><br>
							Ваш ID:<b> {ID}</b><br>
				             Ваш логин:<b> {LOGIN}</b><br>
							 Для активации аккаунта пройдите по ссылке ниже:<br>
						    <a href="https://scorpionbux.info/{CODE}" target="_blank">https://scorpionbux.info/{CODE}</a>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'mess' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Вам прислали новое сообщение на проекте <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						 <u>Вам пришло новое сообщение</u><br><br>
                        От пользователя:<br>
						<img src="https://scorpionbux.info/avatar/{AVATAR}" style="width:60px; height:60px" border="0" title=""><b> {LOGIN}</b><br>
						<a href="https://scorpionbux.info/inbox.php" target="_blank">Перейдите в ваш аккаунт для прочтения!</a>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'recc_2' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Восстановление данных для авторизации на проекте <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    Здравствуйте!<br>
				        Вы запросили данные для авторизации на проекте <b>scorpionbux.info</b></a><br>
				        Запрос был осуществлен с IP адреса: <b>{IP}</b><br>
				        Ваш ID:<b> {ID}</b><br>
				        Ваш логин:<b> {LOGIN}</b><br>
				        Ваш пароль:<b> {PASS}</b><br>
				        Ваш пароль для операций:<b> {PIN}</b><br>
				        <span style="color:#FF0000;">Внимание!</span> Обязательно сохраните данные. Пароль можно изменить в разделе профиль.
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'pass_op' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Восстановление пароля для операций на проекте <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    Здравствуйте!<br>
				        Вы запросили пароль для операций на проекте <b>scorpionbux.info</b></a><br>
				        Запрос был осуществлен с IP адреса: <b>{IP}</b><br>
				        Ваш ID:<b> {ID}</b><br>
				        Ваш логин:<b> {LOGIN}</b><br>
				        Ваш пароль для операций:<b> {PIN}</b><br>
				        <span style="color:#FF0000;">Внимание!</span> обязательно запишите или запомните пароль для операций. При заказе нового пароля, старый будет недействителен!
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'birthday' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Уважаемый, {LOGIN} поздравсляем Вас с Днем Рождения!</td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    <b>С днем рожденья поздравляем!<br>
						    Здоровья, успехов и счастья желаем,<br>
						    Пусть мир улыбается солнышком ясным,<br>
						    Пусть каждый твой день будет самым прекрасным,<br>
						    Пусть всегда на пути твоём счастье стоит,<br>
						    Пусть улыбка твоя людям радость дарит!</b><br><br>
				        С уважением, Администрация проекта <b style="color:#009E58;">scorpionbux.info</b>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'budil' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Информационное сообщение системы!</td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    Здравствуйте!<br>
						    Вы зарегистрированы на сайте <a href="http://scorpionbux.info">http://scorpionbux.info</a> ваш логин <b>{LOGIN}</b>.<br>
						    Вы давно не заходили в свой аккаунт и ваш реферер <b>{LOGIN_REF}</b> отправил вам напоминание.<br>
						    {TEXT} <a href=\"http://scorpionbux.info\">Вернуться в систему и продолжить работу!</a>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'rass' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Письмо рекламодателя <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    Вы получили это письмо рекламодателя, так как являетесь зарегистрированным пользователем системы scorpionbux.info<br><br>
						    <b>Содержание письма:</b><br>
				        {TEXT}<br><br>
				        Администрация scorpionbux.info не имеет никакого отношения и не несет никакой ответственности за содержание данного письма.<br>
				        Чтобы отписаться от получения писем, войдите в свой аккаунт и в профиле снимите галочку в чекбоксе «отправлять рекламные письма на e-mail».<br><br>
				        <i>Это автоматическое сообщение, отвечать на него не надо, мы все равно не получим Ваш ответ на него.</i>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'add_din' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
		  <tbody>
		    <tr><td align="center">
		      <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
		        <tbody>
		          <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
		          <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">Доброго времени суток, уважаемый пользователь проекта <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
		          <tr><td align="left" style="font-size:13px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">				  
			     Здравствуйте уважаемый <b>{LOGIN}</b>!<br/>
				 Вы пополнили баланс на сумму {MONEY} руб.<br/>
                     На рекламный счет начислен кэшбэк в размере {BONUSKEH} руб. ({BONUS}% от суммы начисления)
				  
				  </td></tr>
		          <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">С уважением, автоматическая служба уведомлений <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
		        </tbody>
		      </table>
		    </td></tr>
		  </tbody>
		</table>
  ',
  
);

?>