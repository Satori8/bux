<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

$sql_articles = mysql_query("SELECT `id` FROM `tb_ads_articles` WHERE `status`='0'") or die(mysql_error());
$count_articles[0] = mysql_num_rows($sql_articles);

$sql_articles = mysql_query("SELECT `id` FROM `tb_ads_articles` WHERE `status`='1'") or die(mysql_error());
$count_articles[1] = mysql_num_rows($sql_articles);

$sql_articles = mysql_query("SELECT `id` FROM `tb_ads_articles` WHERE `status`='2'") or die(mysql_error());
$count_articles[2] = mysql_num_rows($sql_articles);

$sql_articles = mysql_query("SELECT `id` FROM `tb_ads_articles` WHERE `status`='4'") or die(mysql_error());
$count_articles[4] = mysql_num_rows($sql_articles);

?>

<script>
$(document).ready(function(){
	setInterval(function(){
		if($(".text_blink").css("color") == "rgb(255, 0, 0)") {
			$(".text_blink").css("color", "#000000");
		}else{
			$(".text_blink").css("color", "#FF0000");
		}
	}, 1000);
});
</script>

<ul id="nav">

	<li><b>��������� �����</b>
	<ul>
		<li><a href="index.php?op=site_config">��������� �����</a></li>
		<li><a href="index.php?op=checkloading">������ �������� �� ����</a></li>
		<li><a href="index.php?op=site_history">������� ��������</a></li>
		<li><a href="index.php?op=2">����������� ���������</a></li>
		<li><a href="index.php?op=3">���������� �����</a></li>
		<li><a href="index.php?op=stat_advertise">���������� �������</a></li>
		<li><a href="index.php?op=4">������ ������ ������</a></li>
		<li><a href="index.php?op=5">������ ������ WMID</a></li>
		<li><a href="index.php?op=black_ip_reg">�� IP ������� ����������� ��� ���-��</a></li>
		<li><a href="index.php?op=6">��������� ��������</a></li>
		<li><a href="index.php?op=7">��������� ��������</a></li>
		<li><a href="index.php?op=min_pay_config">������� ������ � �������� ��� ������</a></li>
                <li><a href="index.php?op=board_config">����� ������</a></li>
                <li><a href="index.php?op=config_bonus">������</a></li>
                <li><a href="index.php?op=config_money_bonus">����� ��� ����������</a></li>
				<li><a href="index.php?op=knb_config">���</a></li>
                 <li><a href="index.php?op=mail_vp_config">������� �����</a></li>
                <li><a href="index.php?op=config_cabinet">������� �������������</a></li>
                <li><a href="index.php?op=config_kopilka">������� �������</a></li>
                <li><a href="index.php?op=config_redemption">��������� ������ � ��������</a></li>
                <li><a href="index.php?op=config_free_users">��������� ������������</a></li>
                <li><a href="index.php?op=config_ref_wall">��������� ���-�����</a></li>
				<li><a href="index.php?op=notif_config">��������� �������� �� ��������</a></li>
				<li><a href="index.php?op=250">��������� �������� ��������� </a></li>
	    <li><a href="index.php?op=251">��������� �������� � ������</a></li>	
				
	</ul>
	</li>
	
	<li><b>�������� ���������</b>
	<ul>
		<li><a href="index.php?op=invest_delivery">�������� ��������</a></li>
	</ul>
	</li>
	
	<li><b>���</b>
	<ul>
		<li><a href="index.php?op=chat_config">���������</a></li>
		<li><a href="index.php?op=chat_adv_list">������� � ����</a></li>
		<li><a href="index.php?op=chat_moder">���������� ����</a></li>
		<li><a href="index.php?op=chat_ban_users">��������������� ������������</a></li>
		<li><a href="index.php?op=chat_mess_arhiv">����� ���������</a></li>
	</ul>
	</li>
	
	 <li><b>��������� �������</b>
	<ul>
		<li><a href="index.php?op=config_pay">��������� ��������</a></li>
		</ul>
	</li>
	
	<li><b>�������</b>
	<ul>
	    <li><a href="index.php?op=pay_config">��������� ������</a></li>
		<!--<li><a href="index.php?op=12">��������� ������</a></li>-->
		<li><a href="index.php?op=13">������ �������</a></li>
		<li><a href="index.php?op=14">����-�������</a></li>
	</ul>
	</li>

	<li><b>�����</b>
	<ul>
		<li><a href="index.php?op=site_action_config">��������� �����</a></li>
		<li><a href="index.php?op=site_action_ref">������ ��������� ��� �����</a></li>
	</ul>
	</li>

	<li><b>�������</b>
	<ul>
		<li><a href="index.php?op=news_add">�������� �������</a></li>
		<li><a href="index.php?op=news_edit">������������� �������</a></li>
	</ul>
	</li>

	<li><b>�����������</b>
	<ul>
		<li><a href="index.php?op=notification_add">�������� �����������</a></li>
		<li><a href="index.php?op=notification_edit">������������� �����������</a></li>
	</ul>
	</li>

	<li><b>���������</b>
	<ul>
		<li><a href="index.php?op=hint_tips_add">�������� ���������</a></li>
		<li><a href="index.php?op=hint_tips_edit">�������������� ���������</a></li>
	</ul>
	</li>
	
	<li><b>������������ ���������</b>
	<ul>
		<li><a href="index.php?op=pay_visits_config">���������</a></li>
		<li><a href="index.php?op=pay_visits_adreq">������������ ������</a></li>
		<li><a href="index.php?op=pay_visits_add">�������� ������</a></li>
		<li><a href="index.php?op=pay_visits_views">�������� � ��������������</a></li>
	</ul>
	</li>

	<li><b>�������������� ������</b>
	<ul>
		<li><a href="index.php?op=invest_config">���������</a></li>
		<li><a href="index.php?op=invest_users">������ ����������</a></li>
		<li><a href="index.php?op=invest_money_in">������� ���������� �������</a></li>
		<li><a href="index.php?op=config_money">����� �� ���������� �������</a></li>
		<li><a href="index.php?op=invest_buy_history">������� ������� �����</a></li>
		<li><a href="index.php?op=invest_birja">����� ����� [���������]</a></li>
		<li><a href="index.php?op=invest_birja_history">����� ����� [�������]</a></li>
		<li><a href="index.php?op=invest_news_add">�������� �������</a></li>
		<li><a href="index.php?op=invest_news_edit">������������� �������</a></li>
		<li><a href="index.php?op=invest_delivery">�������� ��������� ����������</a></li>
		<li><a href="index.php?op=invest_welcome">����������� ����� ����������</a></li>
	</ul>
	</li>

	<li><b>������������</b>
	<ul>
		<li><a href="index.php?op=users_edit">������������� ������������</a></li>
		<li><a href="index.php?op=users_ban_add">�������� ������������</a></li>
		<li><a href="index.php?op=users_ban_list">���������� ������������</a></li>
		<li><a href="index.php?op=users_vac_list">������������ � �������</a></li>
		<li><a href="index.php?op=users_repeat_purse">��������� ���������</a></li>
		<li><a href="index.php?op=mail_in">�������� ����� �������������</a></li>
		<li><a href="index.php?op=mail_out">��������� ����� �������������</a></li>
		<li><a href="index.php?op=users_online">������������ ������</a></li>
	</ul>
	</li>

	<li><b>������� ������</b>
	<ul>
		<li><a href="index.php?op=pay_row_config">���������</a></li>
		<li><a href="index.php?op=pay_row_add">�������� �������</a></li>
		<li><a href="index.php?op=pay_row_adreq">������������ ������</a></li>
		<li><a href="index.php?op=pay_row_edit">�������������� �������</a></li>
		<li><a href="index.php?op=pay_row_arhiv">����� ������</a></li>
	</ul>
	</li>
	
	<li><b>������� ���������</b>
	<ul>
		<li><a href="index.php?op=quick_mess_config">���������</a></li>
		<li><a href="index.php?op=quick_mess_add">�������� ���������</a></li>
		<li><a href="index.php?op=quick_mess_edit">�������� � ��������������</a></li>
	</ul>
	</li>

	<li><b>������������ �����</b>
	<ul>
		<li><a href="index.php?op=tests_config">��������� ������</a></li>
		<li><a href="index.php?op=tests_adreq">������������ ������ ������</a></li>
		<li><a href="index.php?op=tests_add">�������� ����</a></li>
		<li><a href="index.php?op=tests_edit">�������������� ������</a></li>
		<li><a href="index.php?op=tests_claims">������ �� �����</a></li>
	</ul>
	</li>

	<li><b>�������� �� e-mail</b>
	<ul>
		<li><a href="index.php?op=sent_emails_config">��������� ��������</a></li>
		<li><a href="index.php?op=sent_emails_adreq">������������ ������ ��������</a></li>
		<li><a href="index.php?op=sent_emails_add">�������� ��������</a></li>
		<li><a href="index.php?op=sent_emails_edit">�������������� ��������</a></li>
	</ul>
	</li>

	<li><b>������� ������ <?php if($count_articles[2] > 0) echo '<span id="li_art_moder" class="text_blink" style="float:right; display:block; padding-right:55px; color:#FF0000; font-size:14px; font-weight:bold; text-shadow:1px 2px 3px #FFF;">'.$count_articles[2].'</span>';?></b>
	<ul>
		<li><a href="index.php?op=articles_config">���������</a></li>
		<li><a href="index.php?op=articles_add">�������� ������</a></li>
		<li><a href="index.php?op=articles_adreq">������������ ������ <span style="float:right; display:block; padding-right:12px; font-size:14px;">[<span id="art_req" style="color:blue; padding:0 1px; font-size:12px; font-weight:bold;"><?=$count_articles[0];?></span>]</span></a></li>
		<li><a href="index.php?op=articles_moder">������ �� ��������� <span style="float:right; display:block; padding-right:12px; font-size:14px;">[<span id="art_moder" style="color:red; padding:0 1px; font-size:12px; font-weight:bold;"><?=$count_articles[2];?></span>]</span></a></li>
		<li><a href="index.php?op=articles_ban">��������������� ������ <span style="float:right; display:block; padding-right:12px; font-size:14px;">[<span id="art_ban" style="color:black; padding:0 1px; font-size:12px; font-weight:bold;"><?=$count_articles[4];?></span>]</span></a></li>
		<li><a href="index.php?op=articles_edit">�������������� ������ <span style="float:right; display:block; padding-right:12px; font-size:14px;">[<span id="art_edit" style="color:green; padding:0 1px; font-size:12px; font-weight:bold;"><?=$count_articles[1];?></span>]</span></a></li>
	</ul>
	</li>

	<li><b>������������ ������</b>
	<ul>
		<li><a href="index.php?op=dlinks_config">���������</a></li>
		<li><a href="index.php?op=dlinks_adreq">����� �������</a></li>
		<li><a href="index.php?op=dlinks_add">�������� ������</a></li>
		<li><a href="index.php?op=dlinks_edit">�������������� ������</a></li>
		<li><a href="index.php?op=dlinks_claims">������</a></li>
		<li><a href="index.php?op=antiautocliker">���� ����-������</a></li>
	</ul>
	</li>
	
	<li><b>YouTube ������</b>
	<ul>
		<li><a href="index.php?op=youtube_config">���������</a></li>
		<li><a href="index.php?op=youtube_adreq">����� �������</a></li>
		<li><a href="index.php?op=youtube_add">�������� youtube</a></li>
		<li><a href="index.php?op=youtube_edit">�������������� ������</a></li>
		<li><a href="index.php?op=youtube_claims">������</a></li>
		<li><a href="index.php?op=antiautoclik">���� ����-������</a></li>
	</ul>
	</li>

	<li><b>������-�����. ������</b>
	<ul>
		<li><a href="index.php?op=24">���������</a></li>
		<li><a href="index.php?op=25">����� �������</a></li>
		<li><a href="index.php?op=26">�������� ������</a></li>
		<li><a href="index.php?op=27">�������� ������</a></li>
	</ul>
	</li>
	
	<li><b>����-������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span></b>
	<ul>
		<li><a href="index.php?op=config_autoyou">��������� �������</a></li>
		<li><a href="index.php?op=adreq_autoyou">������ �������</a></li>
		<li><a href="index.php?op=add_autoyou">�������� �������</a></li>
		<li><a href="index.php?op=edit_autoyou">�������� �������</a></li>
	</ul>
	</li>

	<li><b>����-�������</b>
	<ul>
		<li><a href="index.php?op=32">��������� �������</a></li>
		<li><a href="index.php?op=33">������ �������</a></li>
		<li><a href="index.php?op=34">�������� �������</a></li>
		<li><a href="index.php?op=35">�������� �������</a></li>
	</ul>
	</li>

	<li><b>����������� ������</b>
	<ul>
		<li><a href="index.php?op=36">��������� �������</a></li>
		<li><a href="index.php?op=37">������ �������</a></li>
		<li><a href="index.php?op=38">�������� �������</a></li>
		<li><a href="index.php?op=39">�������� �������</a></li>
	</ul>
	</li>

	<li><b>������� ������</b>
	<ul>
		<li><a href="index.php?op=beg_stroka_config">��������� �������</a></li>
		<li><a href="index.php?op=beg_stroka_adreq">������ �������</a></li>
		<li><a href="index.php?op=beg_stroka_add">�������� �������</a></li>
		<li><a href="index.php?op=beg_stroka_edit">�������� �������</a></li>
	</ul>
	</li>

	<li><b>������������� ������</b>
	<ul>
		<li><a href="index.php?op=403">��������� �������</a></li>
		<li><a href="index.php?op=404">������ �������</a></li>
		<li><a href="index.php?op=405">�������� �������</a></li>
		<li><a href="index.php?op=406">�������� �������</a></li>
	</ul>
	</li>

	<li><b>����������� �������</b>
	<ul>
		<li><a href="index.php?op=40">��������� �������</a></li>
		<li><a href="index.php?op=41">������ �������</a></li>
		<li><a href="index.php?op=42">�������� �������</a></li>
		<li><a href="index.php?op=43">�������� �������</a></li>
	</ul>
	</li>

	<li><b>����������� �������</b>
	<ul>
		<li><a href="index.php?op=44">��������� �������</a></li>
		<li><a href="index.php?op=45">������ �������</a></li>
		<li><a href="index.php?op=46">�������� �������</a></li>
		<li><a href="index.php?op=47">�������� �������</a></li>
	</ul>
	</li>

	<li><b>��������� ����������</b>
	<ul>
		<li><a href="index.php?op=48">��������� �������</a></li>
		<li><a href="index.php?op=49">������ �������</a></li>
		<li><a href="index.php?op=50">�������� �������</a></li>
		<li><a href="index.php?op=51">�������� �������</a></li>
	</ul>
	</li>

	<li><b>������ �� ������</b>
	<ul>
		<li><a href="index.php?op=52">��������� �������</a></li>
		<li><a href="index.php?op=53">������ �������</a></li>
		<li><a href="index.php?op=54">�������� �������</a></li>
		<li><a href="index.php?op=55">�������� �������</a></li>
	</ul>
	</li>

	<li><b>������</b>
	<ul>
		<li><a href="index.php?op=mails_config">��������� �������</a></li>
		<li><a href="index.php?op=mails_adreq">������ �������</a></li>
		<li><a href="index.php?op=mails_add">�������� �������</a></li>
		<li><a href="index.php?op=mails_edit">�������� �������</a></li>
		<li><a href="index.php?op=mails_claims">������</a></li>
	</ul>
	</li>

	<li><b>��������� �������</b>
	<ul>
		<li><a href="index.php?op=60">��������� �������</a></li>
		<li><a href="index.php?op=61">������ �������</a></li>
		<li><a href="index.php?op=62">�������� �������</a></li>
		<li><a href="index.php?op=63">�������� �������</a></li>
	</ul>
	</li>

	<li><b>������ �������</b>
	<ul>
		<li><a href="index.php?op=100">��������� �������</a></li>
		<li><a href="index.php?op=101">������ �������</a></li>
	</ul>
	</li>

	<li><b>�������</b>
	<ul>
		<li><a href="index.php?op=200">���������</a></li>
		<li><a href="index.php?op=201&page=task_view">���������� ������ ���������</a></li>
		<li><a href="index.php?op=202">�������� ���� �������</a></li>
                <li><a href="index.php?op=task_stat">����� ����������</a></li>
                 <li><a href="index.php?op=task_claims">������</a></li>
	</ul>
	</li>

	<li><b>�������</b>
	<ul>
		<li><a href="index.php?op=203">���������</a></li>
		<li><a href="index.php?op=204">����������</a></li>
		<li><a href="index.php?op=205">�������� ��������</a></li>
		<li><a href="index.php?op=206">����������� ��������</a></li>
	</ul>
	</li>

	<li><b>�����</b>
	<ul>
		<li><a href="index.php?op=207">������� ������</a></li>
		<li><a href="index.php?op=208">���������� ������</a></li>
		<li><a href="index.php?op=209">��������� ��������</a></li>
		<li><a href="index.php?op=210">������������</a></li>
	</ul>
	</li>

	<!--<li><b>��������� ���������</b>
	<ul>
		<li><a href="index.php?op=konkurs_title">��������� � ���������</a></li>
		<li><a href="index.php?op=free_users">������������ ��� ��������</a></li>
		<li><a href="index.php?op=konkurs_users_exp">������������ ����������� �� ���������</a></li>
		<li><a href="index.php?op=konkurs_config_ads">������� �������������� �1</a></li>
		<li><a href="index.php?op=konkurs_config_ads_big">������� �������������� �2</a></li>
		<li><a href="index.php?op=konkurs_config_click">������� �������� �1</a></li>
		<li><a href="index.php?op=konkurs_config_click_big">������� �������� �2</a></li>
		<li><a href="index.php?op=konkurs_config_ref">������� ����������� ���������</a></li>
		<li><a href="index.php?op=konkurs_config_task">������� �� ���������� �������</a></li>
		<li><a href="index.php?op=konkurs_config_ed_hit">������� �����������</a></li>
		<li><a href="index.php?op=konkurs_config_complex">����������� �������</a></li>
	</ul>
	</li>-->
	
	<li><b>��������� ���������</b>
	<ul>
		<li><a href="index.php?op=konkurs_title">��������� � ���������</a></li>
		<li><a href="index.php?op=free_users">������������ ��� ��������</a></li>
		<li><a href="index.php?op=konkurs_users_exp">������������ ����������� �� ���������</a></li>
		<li><a href="index.php?op=konkurs_config_ads">������� �������������� �1</a></li>
		<li><a href="index.php?op=konkurs_config_ads_big">������� �������������� �2</a></li>
		<li><a href="index.php?op=konkurs_config_serf">���������� ������ � ��������</a></li>
		
		<li><a href="index.php?op=konkurs_config_test">������� �� ����������� ������</a></li>
		<li><a href="index.php?op=konkurs_config_click">������� ��������</a></li>
		<li><a href="index.php?op=konkurs_config_youtub">������� YOUTUB</a></li>
		<li><a href="index.php?op=konkurs_config_ref">������� ����������� ���������</a></li>
		<li><a href="index.php?op=konkurs_config_task">������� �� ���������� �������</a></li>
		
		<li><a href="index.php?op=konkurs_config_complex">����������� �������</a></li>
		
	</ul>
	</li>
	
	<li><b>����� ���������</b>
	<ul>
		<li><a href="index.php?op=213">���������</a></li>
		<li><a href="index.php?op=214">���������</a></li>
		<li><a href="index.php?op=215">����������</a></li>

	</ul>
	</li>

</ul>