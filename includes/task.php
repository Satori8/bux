<div class="usermenu">

<script src="/js/js_task1.js"></script>
    <span id="tsk_mnu1" class="usermnutitle-g active">По параметрам</span>
    <div id="tsk_mnu_block1" class="usermnublock">
        <span class="usermnudelim"></span>
<span class="usermnuline-act"><b>Все задания</b></span>
<span class="usermnuline" onclick="javascript:gotfilter(1,1);">Только новые задания</span>
<span class="usermnuline" onclick="javascript:gotfilter(2,1);">Многоразовые задания</span>
<span class="usermnuline" onclick="javascript:gotfilter(3,1);">Избранное</span>
<span class="usermnuline" onclick="javascript:gotfilter(4,1);">Над которыми работаю</span>
<span class="usermnuline" onclick="javascript:gotfilter(7,1);">Находятся на проверке</span>
<span class="usermnuline" onclick="javascript:gotfilter(5,1);">Оплачены</span>
<span class="usermnuline" onclick="javascript:gotfilter(6,1);">Отклонены</span>
<span class="usermnuline" onclick="javascript:gotfilter(8,1);">Готовые к исполнению</span>
<span class="usermnuline" style="color: #B21400" onclick="javascript:gotfilter(9,1);">Мусорка</span>
<span class="usermnudelim"></span>
</div>
<span id="tsk_mnu2" class="usermnutitle-g ">По категории</span>
<div id="tsk_mnu_block2" class="usermnublock" style="display: none;">
    <span class="usermnudelim"></span>
<span class="usermnuline-act"><b>Все задания</b></span>
<span class="usermnuline" onclick="javascript:gotfilter(1,2);">Клики</span>
<span class="usermnuline" onclick="javascript:gotfilter(1,2);">Рег. без активности</span>
<span class="usermnuline" onclick="javascript:gotfilter(1,2);">Рег. с активностью</span>
<span class="usermnuline" onclick="javascript:gotfilter(1,2);">Постинг в форумы/блоги</span>
<span class="usermnuline" onclick="javascript:gotfilter(1,2);">Бонусы</span>
<span class="usermnuline" onclick="javascript:gotfilter(1,2);">Оставить отзыв или проголосовать</span>
<span class="usermnuline" onclick="javascript:gotfilter(9,2);">Загрузка файлов</span>
<span class="usermnuline" onclick="javascript:gotfilter(2,2);">YouTube</span>
<span class="usermnuline" onclick="javascript:gotfilter(10,2);">Социальные сети</span>
<span class="usermnuline" onclick="javascript:gotfilter(14,2);">Написать статью</span>
<span class="usermnuline" onclick="javascript:gotfilter(11,2);">Играть в игры</span>
<span class="usermnuline" onclick="javascript:gotfilter(12,2);">Инвестировать</span>
<span class="usermnuline" onclick="javascript:gotfilter(3,2);">Приглашение в реф SEO</span>
<span class="usermnuline" onclick="javascript:gotfilter(4,2);">Перевод кредитов/баллов</span>
<span class="usermnuline" onclick="javascript:gotfilter(5,2);">Forex</span>
<span class="usermnuline" onclick="javascript:gotfilter(8,2);">Мобильные устройства</span>
<span class="usermnuline" onclick="javascript:gotfilter(6,2);">Работа с капчей</span>
<span class="usermnuline" onclick="javascript:gotfilter(7,2);">Работа с криптовалютами</span>
<span class="usermnuline" onclick="javascript:gotfilter(13,2);">Экономические Игры/Фермы</span>
<span class="usermnuline" onclick="javascript:gotfilter(15,2);">Зарубежные сайты</span>
<span class="usermnuline" onclick="javascript:gotfilter(0,2);">Прочее</span>
    <span class="usermnudelim"></span>
    </div>
    <form class="formsearchh" name="taskselectform" onsubmit="return false;">
        <span id="tsk_mnu3" class="usermnutitle-g ">По № задания</span>
        <div id="tsk_mnu_block3" class="usermnublock" style="display: none;">
            <span class="usermnudelim"></span>
            <input type="text" name="tasknum" maxlength="13" value="" style="margin-left: 2px;">
            <input class="btnsearchh" type="button" value="" onclick="javascript:gotsearch(document, 1);">
            <span class="usermnudelim"></span>
        </div>
        <span id="tsk_mnu4" class="usermnutitle-g ">По № рекламодателя</span>
        <div id="tsk_mnu_block4" class="usermnublock" style="display: none;">
            <span class="usermnudelim"></span>
            <input type="text" name="taskuser" maxlength="13" value="" style="margin-left: 2px;">
            <input class="btnsearchh" type="button" value="" onclick="javascript:gotsearch(document, 2);">
            <span class="usermnudelim"></span>
        </div>
        <span id="tsk_mnu5" class="usermnutitle-g ">По URL-адресу сайта</span>
        <div id="tsk_mnu_block5" class="usermnublock" style="display: none;">
            <span class="usermnudelim"></span>
            <input type="text" name="taskurl" maxlength="40" value="" style="margin-left: 2px;">
            <input class="btnsearchh" type="button" value="" onclick="javascript:gotsearch(document, 3);">
            <span class="usermnudelim"></span>
        </div>
    </form>
    </div>