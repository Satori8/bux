<div id="header">

	<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:170px; padding-top:0px; margin-top:0px;">
	<tr>
		<!--<td nowrap="nowrap" align="left" valign="middle" style="padding-left:20px;"><a href="/"><span class="nbtitleheg"><?=@$domen;?></span><span class="nbtitleheds">Система активной рекламы</span></a></td>-->
		<div style="position:relative;width:88px"> 
        <!--<div style="background:url('https://<?=@$domen;?>/img/ukrah/vosmoe_march_5.png');position:absolute;width:128px;height:98px;overflow:hidden;margin-left:365px;margin-top:25px"> </div>-->
		<!--<td nowrap="nowrap" align="left" valign="middle" style="padding-left:20px;"><a href="/"><span class="nbtitleheg"><?=@$domen;?></span><span class="nbtitleheds">Реклама жалящая в цель</span></a></td>-->
           <td nowrap="nowrap" align="left" valign="middle"><a href="/"><img src="/img/logo/logo.gif" alt="" title="Главная" border="0" /></a></td>
		<td align="right" valign="middle" width="45%"><?php include('includes/banner468x60.php');?></td>
	</tr>
	</table>
	
	<div class="menu-header"><div class="ddsmoothmenu" style="width:1250px; margin:0 auto;">
	    <ul class="ul-menu">
	        <li><a href='/index.php'<?if (strpos($_SERVER['PHP_SELF'], 'index.php' ) !== false) {?> class='active'<?}?>>Главная</a></li>
	        <li><a href='/advertise.php'<?if (strpos($_SERVER['PHP_SELF'], 'advertise.php' ) !== false) {?> class='active'<?}?>>Заказ рекламы</a></li>
	        <li><a href='/surfings' <?if (strpos($_SERVER['PHP_SELF'], 'surfings.php' ) !== false) {?> class='active'<?}?>>Заработать</a></li>
	        <li><a href='/konkurs' <?if (strpos($_SERVER['PHP_SELF'], 'konkurs.php' ) !== false) {?> class='active'<?}?>>Конкурсы</a></li>
	        <li><a href='/stat_pay.php' <?if (strpos($_SERVER['PHP_SELF'], 'stat_pay.php' ) !== false) {?> class='active'<?}?>>Выплаты</a></li>
	        <li><a href='/news.php' <?if (strpos($_SERVER['PHP_SELF'], 'news.php' ) !== false) {?>class='active'<?}?>>Новости</a></li>
	        <li><a href='/ref_wall'<?if (strpos($_SERVER['PHP_SELF'], 'ref_wall.php' ) !== false) {?> class='active'<?}?>>Реф-стена</a></li>
	        <li><a href='/tos.php' <?if (strpos($_SERVER['PHP_SELF'], 'tos.php' ) !== false) {?> class='active'<?}?>>Правила</a></li>
	        <li><a href='/top100.php' <?if (strpos($_SERVER['PHP_SELF'], 'top100.php' ) !== false) {?> class='active'<?}?>>ТОП 100</a></li>
	        <?
error_reporting(0);  
if($_SESSION["userLog"] =="") { ?>
<? }else{?>
	        <li><a href='/forum.php' <?if (strpos($_SERVER['PHP_SELF'], 'forum.php' ) !== false) {?> class='active'<?}?>>Форум</a></li>
	         <? }?>
	        <li><a href='/articles.php' <?if (strpos($_SERVER['PHP_SELF'], 'articles.php' ) !== false) {?> class='active'<?}?>>Статьи</a></li>
	        <li><a href='/catalog.php' <?if (strpos($_SERVER['PHP_SELF'], 'catalog.php' ) !== false) {?> class='active'<?}?>>Каталог</a></li>
	        <li><a href="/faq.php" <?if (strpos($_SERVER['PHP_SELF'], 'faq.php' ) !== false) {?> class='active'<?}?>>FAQ</a></li>
	        <li><a href='/contact.php' <?if (strpos($_SERVER['PHP_SELF'], 'contact.php' ) !== false) {?> class='active'<?}?>>Контакты</a></li>
	        </ul>
	        </div>
	        </div>

</div>