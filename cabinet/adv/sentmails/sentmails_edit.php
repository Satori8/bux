<?php
if(!DEFINED("SENTMAILS_EDIT")) {die ("Hacking attempt!");}

	echo '<a name="goto"></a>';
	echo '<h5 class="sp" style="margin-top:0px; font-size:14px; font-weight:bold; text-align:center;">Редактирование рассылки на e-mail № '.$id.'</h5>';

	$sql = mysql_query("SELECT * FROM `tb_ads_emails` WHERE `id`='$id' AND `username`='$username'");
	if(mysql_num_rows($sql)>0) {
		$row = mysql_fetch_array($sql);

		$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_sent_mails' AND `howmany`='1'");
		$cena_sent_mails = mysql_result($sql,0,0);

		?>


<script type="text/javascript" language="JavaScript">
		function save_ads(id, type) {
			var url = $.trim($('#url').val());
			var subject = $.trim($('#subject').val());
			var message = $.trim($('#message').val());

			if (subject == '') {
				gebi("subject").style.background = "#FFDBDB";
				gebi("subject").focus();
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали тему сообщения!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			} else if (message == '') {
				gebi("message").style.background = "#FFDBDB";
				gebi("message").focus();
				gebi("info-msg-cab").innerHTML = '<span class="msg-error">Вы не указали текст сообщения!</span>';
				gebi("info-msg-cab").style.display = "";
				setTimeout(function() {$('#info-msg-cab').fadeOut('slow')}, 2000); clearTimeout();
			} else {
				$.ajax({
					type: "POST", url: "/cabinet/ajax/ajax_adv.php", 

                                         data: {
                                             'op':'save', 
                                             'type':type, 
                                             'id':id, 
                                             'subject':subject, 
                                             'message':message
                                        }, 
					beforeSend: function() { $('#loading').show(); }, 
					success: function(data) { 
						$('#loading').hide(); 
						if (data == "OK") {document.location.href = "<?php if(isset($_SERVER["REDIRECT_URL"])) {echo $_SERVER["REDIRECT_URL"];} echo "?ads=".$ads;?>#goto";}
						else alert(data);
					}
				});
			}
		}

		function messchange() {
			var subject = gebi('subject').value;
			var mess = gebi('mess').value;

			if(subject.length > 255) {
				gebi('subject').value = subject.substr(0,255);
			}
			if(mess.length > 1024) {
				gebi('mess').value = mess.substr(0,1024);
			}
			gebi('count1').innerHTML = 'Осталось <b>'+(255-subject.length)+'</b> символов';
			gebi('count2').innerHTML = 'Осталось <b>'+(1024-mess.length)+'</b> символов';
		}
		</script>


<?php

		echo '<div id="newform">';
		echo '<table class="tables">';
		echo '<thead><tr>';
			echo '<th class="top" width="180">Параметр</a>';
			echo '<th class="top">Значение</a>';
		echo '</thead></tr>';
		echo '<tbody>';
			echo '<tr>';
				echo '<td><b>Тема сообщения:</b></td>';
				echo '<td>';
					echo '<textarea id="subject" class="ok" onkeydown="this.style.background=\'#FFFFFF\';" onkeyup="messchange();">'.$row["subject"].'</textarea>';
					echo '<div align="right" id="count1" style="color:#696969;"></div>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Тема сообщения:</b></td>';
				echo '<td>';
					echo '<textarea id="message" class="ok" onkeydown="this.style.background=\'#FFFFFF\';" onkeyup="messchange();">'.$row["message"].'</textarea>';
					echo '<div align="right" id="count2" style="color:#696969;"></div>';
				echo '</td>';
			echo '</tr>';
			echo '<tr>';
				echo '<td><b>Стоимость рассылки:</b></td>';
				echo '<td><span style="color:#228B22;"><b>'.number_format($cena_sent_mails, 2, ".", " ").'</b> руб./сутки</span></td>';
			echo '</tr>';
		echo '</tbody>';
		echo '</table>';
		echo '</div><br>';

		echo '<div id="info-msg-cab"></div>';
                
               echo '<div align="center"><span onClick="save_ads('.$row["id"].', \'sentmails\');" class="proc-btn" style="float:none; width:160px;">Сохранить</span></div>';
		

		?>

               <script language="JavaScript">messchange();</script>

<?php

}else{
	echo '<span class="msg-error">Рекламная площадка № '.$id.' у вас не найдена</span>';
}
?>