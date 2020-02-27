<?php
if(!DEFINED("link_EDIT")) {die ("Hacking attempt!");}

	echo '<a name="goto"></a>';
	echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование платной строки № '.$id.'</h5>';

	$sql = mysql_query("SELECT * FROM `tb_ads_link` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_link' and howmany='1'");
		$cena_link = mysql_result($sql,0,0);

		$sql = mysql_query("SELECT price FROM tb_config WHERE item='cena_color_link' and howmany='1'");
		$cena_color_link = mysql_result($sql,0,0);


		?><script type="text/javascript" language="JavaScript">

		function save_ads(id, type) {
			var url = $.trim($('#url').val());
			var description = $.trim($('#description').val());
			if ($.trim($('#color').val())) var color = $.trim($('#color').val());
			else var color = 0;

			if ((url == '') | (url == 'http://') | (url == 'https://')) {
				gebi("url").style.background = "#FFDBDB";
				gebi("url").focus();
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали URL-адрес сайта!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			} else if (description == '') {
				gebi("description").style.background = "#FFDBDB";
				gebi("description").focus();
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали краткое описание ссылки!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			} else {
				$.ajax({
					type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: {'op':'save', 'type':type, 'id':id, 'url':url, 'description':description, 'color':color}, 
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
			if (!gebi('color')) var color = 0;
			else var color = gebi('color').value;

			var cena_link = <?php echo $cena_link;?>;
			var cena_color = <?php echo $cena_color_link;?>;

			var price = (cena_link + color * cena_color);
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
				echo '<td><b>URL сайта (ссылка):</b></td>';
				echo '<td><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Описание ссылки:</b></td>';
				echo '<td><input type="text" id="description" maxlength="80" value="'.$row["description"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Выделение цветом:</b></td>';
				echo '<td>';
					if($row["status"]=="0" || $row["status"]=="3") {
						echo '<select id="color" onChange="obsch();" onClick="obsch();">';
							echo '<option value="0" '.("".$row["color"]."" == "0" ? 'selected="selected"' : false).'>Нет</option>';
							echo '<option value="1" '.("".$row["color"]."" == "1" ? 'selected="selected"' : false).'>Да (+'.number_format($cena_color_link,2,".","'").' руб./сутки)</option>';

						echo '</select>';

					}elseif($row["status"]=="1") {
						if($row["color"]=="1") {
							echo '<b>ДА</b>';
						}else{
							echo '<b>НЕТ</b>';
						}
					}else{
						echo '';
					}
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Стоимость показа:</b></td>';
				echo '<td id="price"></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div><br>';

		echo '<div id="info-msg-cab"></div>';

		echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'link\');" class="proc-btn" style="float:none; width:160px;">Сохранить</span></div>';

		?><script language="JavaScript">obsch();</script><?php
}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>