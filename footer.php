<?php
				//echo '</div>';
				echo '</div>';
			echo '</div>';
		echo '</div></div>';
	echo '</div>';


	if(!function_exists('mysql_close()') && function_exists('mysql_query')) {@mysql_close();}

	echo '<div id="footer">';
		echo '<table border="0" width="100%" align="center" id="banners">';
		echo '<tr>';
			echo '<td align="left">';
                                echo '<!noindex><!-- begin WebMoney Transfer : attestation label --><a href="https://passport.webmoney.ru/asp/certview.asp?wmid='.$site_wmid.'" target="_blank"><img src="/img/wm/attestat.png" alt="Здесь находится аттестат нашего WM идентификатора '.$site_wmid.'" title="Здесь находится аттестат нашего WM идентификатора '.$site_wmid.'" border="0" /></a><!-- end WebMoney Transfer : attestation label --></noindex>';
				echo '<!-- begin WebMoney Transfer : accept label --><a href="https://www.webmoney.ru/" target="_blank"><img src="/img/wm/wm_pay.png" alt="www.megastock.ru" title="www.megastock.ru" border="0" /></a><!-- end WebMoney Transfer : accept label -->';
                                echo '<!noindex><a href="https://payeer.com/" rel="nofollow" target="_blank"><img src="/img/payeer88x31.png" border="0" title="Мы принимаем Payeer" /></a></noindex>';
                                echo '<!noindex><a href="https://www.roboxchange.com/" title="Robokassa" target="_blank"><img src="/img/robokassa.png" alt="" border="0" /></a></noindex>';
                                echo '<!noindex><a href="https://megakassa.ru/" title="Платежный агрегатор МегаКасса" target="_blank"><img src="https://megakassa.ru/pr/light_ru.jpg" alt="MegaKassa" /></a></noindex>';
                                //echo '<a href="https://megakassa.ru/" title="Платежный агрегатор МегаКасса" target="_blank"><img src="https://megakassa.ru/pr/dark_ru.jpg" alt="MegaKassa" /></a>';
                                echo '<!noindex><a href="https://money.yandex.ru/" rel="nofollow" target="_blank"><img src="/img/yandex_88x31.gif" border="0" title="Мы принимаем Яндекс.Деньги" /></a></noindex>';
                                	
                                echo '<!noindex><a href="https://perfectmoney.is" title="Perfect Money - новое поколение платежных систем Интернета"><img hspace="5" src="//perfectmoney.is/img/banners/ru_RU/88-31-1.jpg" width="88" height="31"></a><br></noindex>';
                                echo '<!noindex><a href="https://qiwi.ru" target="_blank" rel="nofollow" title="Мы принимаем QIWI"><img src="/img/qiwi_88x31.jpg" alt="" border="0"></a></noindex>';
                                //echo '<a href="https://www.walletone.com/" target="_blank" rel="nofollow"><img src="/img/wallet_one.png" alt="" border="0"></a>';
                                //echo '<!noindex><a href="//www.free-kassa.ru/"><img src="//www.free-kassa.ru/img/fk_btn/15.png"></a></noindex>';
                                echo '<!noindex><a href="https://advcash.com/"><img src="img/advcash_88x31.png" alt="AdvCash" title="Мы принимаем Advanced Cash"></a></noindex>';
                                //echo '<!--begin of Bonbone--><a href="http://bonbone.ru/" title="Популярный каталог интернет ресурсов" target="_blank"><img src="http://bonbone.ru/bon.php?609743" width=88 height=31 border=0></a><!--end of Bonbone-->';
                                echo '<!-- Yandex.Metrika informer --><a href="https://metrika.yandex.ru/stat/?id=51798176&amp;from=informer" target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/51798176/3_0_FF2020FF_FF0000FF_1_pageviews" style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="51798176" data-lang="ru" /></a><!-- /Yandex.Metrika informer -->';
				                //echo '<!--LiveInternet counter--><script type="text/javascript" language="javascript" src="scripts/liveinternet.js"></script><!--/LiveInternet-->';
                                //echo '<!-- Rating@Mail.ru logo --><a href="httpS://top.mail.ru/jump?from=2856040" target="_blank"><img src="//top-fwz1.mail.ru/counter?id=2856040;t=615;l=1" style="border:0;" height="31" width="88" alt="Рейтинг@Mail.ru" /></a><!-- //Rating@Mail.ru logo -->';
                                
                                echo '<!noindex><a href="https://yandex.ru/cy?base=0&amp;host='.$_SERVER["HTTP_HOST"].'" target="_blank"><img src="https://www.yandex.ru/cycounter?'.$_SERVER["HTTP_HOST"].'" width="88" height="31" alt="Индекс цитирования" border="0" /></a></noindex>';
                                
                                
			echo '</td>';
			echo '<td align="right">';
			echo '<a href="/sitestats_all.php">Статистика</a>&nbsp;&#183;&nbsp;<a href="/contact.php">Контакты</a>&nbsp;&#183;&nbsp;';
				echo '<a href="/faq.php">FAQ</a>&nbsp;&#183;&nbsp;<a href="/tos.php">Правила</a>&nbsp;&#183;&nbsp;<a href="/black_list_sites.php">Черный список</a><br>';
				//echo 'Тех.Поддержка - <a href="mailto:supreme-garden@yandex.ru">Supreme-Garden.ru</a><a> &copy; '.date("Y").'</a><br>';
                 
                if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {
					echo '<a>Пользователей</a> <span class="stat">'.number_format($users_all,0,"."," ").'</span>&nbsp;&hellip;&nbsp;';
					echo '<a>Новых за 24 часа:</a> <span class="stat">'.number_format($users_24h,0,"."," ").'</span>&nbsp;&hellip;&nbsp;';
					echo '<a>Выплачено:</a> <span class="stat">'.number_format($sumpay,2,"."," ").'</span> <a>руб.</a>';
					echo '<br>';
				}else{
					echo '<a>Разработка сайта - </a><a href="mailto:supreme-garden@yandex.ru">Supreme-Garden</a><a> &copy;2018 - '.date("Y").'</a><br>';
					
					echo '<a>Тех.Поддержка - </a><a href="mailto:supreme-garden@yandex.ru">Supreme-Garden.ru</a><a> &copy; '.date("Y").'</a><br>';
				}				 
								
								echo '<a>Страница сгенерирована за '.round((microtime(true)-$sysstart) ,3).' сек.</a>';
			echo '</td>';
		echo '</tr>';
		echo '</table>';
	echo '</div>';
?>


<div title="Количество онлайн" class="onlinecss" onclick="location.href='/online.php'"><div id="onlineload"></div></div>
<?
if(isset($_SESSION["userLog"]) && isset($_SESSION["userPas"])) {?>
<div title="Тех поддержка" class="supportcss" onclick="location.href='/newmsg.php?name=Admin';">Тех поддержка</div>
<?}?>
<div id="scroll">
<a style='position: fixed; bottom: 100px; right: 5px; cursor:pointer; display:none;' href='#' id='Go_Top'><img src="/images/scroll-up.png" alt="Наверх" title="Наверх"></a>
<a style='position: fixed; bottom: 55px; right: 5px; cursor:pointer; display:none;' href='#' id='Go_Bottom'><img src="/images/scroll-down.png" alt="Вниз" title="Вниз"></a>
</div>
<script type="text/javascript" src="/js/scroll.js"></script>
</body>
</body>
</html>