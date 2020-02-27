<?php
if(!DEFINED("BANNERS_EDIT")) {die ("Hacking attempt!");}

	echo '<a name="goto"></a>';
	echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование баннера № '.$id.'</h5>';

	$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);
		$size_banner = $row["type"];

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='banner".$size_banner."' AND `howmany`='1'");
		$cena_banner = mysql_result($sql,0,0);

		?><script type="text/javascript" language="JavaScript">

		function save_ads(id, type) {
			var url = $.trim($('#url').val());
			var urlbanner = $.trim($('#urlbanner').val());

			if ((url == '') | (url == 'http://') | (url == 'https://')) {
				gebi("url").style.background = "#FFDBDB";
				gebi("url").focus();
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали URL-адрес сайта!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			} else if (urlbanner == '') {
				gebi("urlbanner").style.background = "#FFDBDB";
				gebi("urlbanner").focus();
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали URL-адрес сайта баннера!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			} else {
				$.ajax({
					type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: {'op':'save', 'type':type, 'id':id, 'url':url, 'urlbanner':urlbanner}, 
					beforeSend: function() { $('#loading').show(); }, 
					success: function(data) { 
						$('#loading').hide(); 
						if (data == "OK") {document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>#goto";}
						else alert(data);
					}
				});
			}
		}

		function obsch(){
			var price = <?php echo $cena_banner;?>;
			gebi('price').innerHTML = '<span style="color:#228B22;"><b>' + number_format(price, 2, '.', ' ') + '</b> руб./сутки</span>';
		}
		</script><?php

		echo '<div id="newform">';
		echo '<table class="tables">';
		echo '<thead><tr>';
			echo '<th class="top" width="180">Параметр</a>';
			echo '<th class="top">Значение</a>';
		echo '</thead></tr>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td width="200"><b>Размер баннера:</b></td>';
				echo '<td>'.$size_banner.'</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>URL сайта (ссылка):</b></td>';
				echo '<td><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>URL баннера (ссылка):</b></td>';
				echo '<td><input type="text" id="urlbanner" maxlength="300" value="'.$row["urlbanner"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Стоимость показа:</b></td>';
				echo '<td id="price"></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div><br>';

		echo '<div id="info-msg-cab"></div>';

		echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'banners\');" class="proc-btn" style="float:none; width:160px;">Сохранить</span></div>';

		?><script language="JavaScript">obsch();</script><?php
}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>