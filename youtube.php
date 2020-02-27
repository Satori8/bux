<?php 
$pagetitle="You Tube отзывы/обзоры ".$_SERVER["HTTP_HOST"];
include("header.php");
?>
<style>
.page-title {
	color: #fae897;
	font-size: 22px;
	font-weight: 400;
	font-family: Raleway;
	text-shadow: 0px 0px 7px rgba(0,10,30,0.2);
	background: #4b9bd8 url(/img/title.png) no-repeat 80% top;
	height: 60px; line-height: 56px;
	text-transform: uppercase;
	margin-bottom: 20px;
	letter-spacing: 1px;
}

.content-inside {
    font:14px Tahoma, Arial, sans-serif;
	border:1px solid #c2c5c8;
	border-radius:3px;
	position:relative;
	z-index:1;
	text-shadow:0px 1px 0px #fff;
	background:#fafafa;
	color:#108;
}

.container{
	width:1100px !important;
}
</style>
<div class="page-title">
		<div class="container" style="padding-left: 70px;">
				YouTube отзывы / обзоры <?=$domen?>
			
		</div>
</div>
		
<div class="content-inside" style="margin-bottom:20px;padding: 15px;">
		<center>Дорогие друзья! <br>
Каждый пользователь проекта, сможет попасть на эту страницу!<br>
Вам просто нужно снять обзор проекта с даты: <b> 11.08.2017 </b> и отправить ссылку на видео в поддержку!<br>
Интересные обзоры будут вознаграждены финансовой составляющей!<br>
</center>
<div align="center" style="color:#3A5FCD"><h3>Смотрите видео от наших пользователей:</h3></div>
<hr>
<br>
<!--<span class="msg-error">Доступных видео для просмотра нет!</span>
<script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>-->

<div style="margin:20px 0 0 0px">
<div style="
	color: #F5AC23;
    font-weight: bold;
    margin-bottom:10px;
    text-shadow: 1px 1px 1px #303030, 0 0 4px #303030;
    text-align:center;
    font-size:24px;">
    Видео от пользователя: Artkar</div>
<center class="youtub" style="display: block;"><iframe id="ytplayer"
        width="610" height="440"
        src="https://www.youtube.com/embed/IyvjbQeJHkw?enablejsapi=1&autoplay=0"
        frameborder="0"
        allowfullscreen
        style="padding-top: 23px;padding-left:2px;"
></iframe></center>
<br>
<hr>
<div style="margin:20px 0 0 0px">
<div style="
	color: #F5AC23;
    font-weight: bold;
    margin-bottom:10px;
    text-shadow: 1px 1px 1px #303030, 0 0 4px #303030;
    text-align:center;
    font-size:24px;">
    Видео от пользователя: Artkar</div>
<center class="youtub" style="display: block;"><iframe id="ytplayer"
        width="610" height="440"
        src="https://www.youtube.com/embed/27hccpyLYTA?enablejsapi=1&autoplay=0"
        frameborder="0"
        allowfullscreen
        style="padding-top: 23px;padding-left:2px;"
></iframe></center>
<br>
<hr>
<div style="margin:20px 0 0 0px">
<div style="
	color: #F5AC23;
    font-weight: bold;
    margin-bottom:10px;
    text-shadow: 1px 1px 1px #303030, 0 0 4px #303030;
    text-align:center;
    font-size:24px;">
    Видео от пользователя: Artkar</div>
<style> .youtub{
    height:484px;
    background:url(img/ramka/ramka.gif) no-repeat center;
    margin:0;
    padding:0px;
    
    padding-left:0;
}
    </style>

<center class="youtub" style="display: block;"><iframe id="ytplayer"
        width="610" height="440"
        src="https://www.youtube.com/embed/PlGzEfbLwEg?enablejsapi=1&autoplay=0"
        frameborder="0"
        allowfullscreen
        style="padding-top: 23px;padding-left:2px;"
></iframe></center>

<br>
<hr>

</div>
<hr>

</div>
</div>
</div>
</div>
<?php 
include("footer.php");
?>