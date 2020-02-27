<?php
if(!DEFINED("PAY_ROW_EDIT")) {die ("Hacking attempt!");}
echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование ссылки в платной строке № '.$id.'</h5>';

	$sql = mysql_query("SELECT * FROM `tb_ads_pay_row` WHERE `id`='$id' AND (`status`='0' OR `status`='1') AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_assoc($sql);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_pay_row' AND `howmany`='1'");
		$cena_pay_row = number_format(mysql_result($sql,0,0), 2, ".", "");

		?><script type="text/javascript" language="JavaScript">

		function save_ads(id, type) {
			var url = $.trim($('#url').val());
			var description = $.trim($('#description').val());

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
					type: "POST", url: "/cabinet/ajax/ajax_adv.php", data: {'op':'save', 'type':type, 'id':id, 'url':url, 'description':description}, 
					beforeSend: function() { $('#loading').show(); }, 
					success: function(data) { 
						$('#loading').hide(); 
						if (data == "OK") {document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>";}
						else alert(data);
					}
				});
			}
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
				echo '<td><b>Описание ссылки</b></td>';
				echo '<td><input type="text" id="description" maxlength="60" value="'.$row["description"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>URL сайта (ссылка)</b></td>';
				echo '<td><input type="text" id="url" maxlength="300" value="'.$row["url"].'" class="ok" onkeydown="this.style.background=\'#FFFFFF\';"></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div><br>';

		echo '<div id="info-msg-cab"></div>';

		echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'pay_row\');" class="proc-btn" style="float:none; width:160px;">Сохранить</span></div>';
}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>