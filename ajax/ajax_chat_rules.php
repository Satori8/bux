<?php
session_start();
header("Content-type: text/html; charset=windows-1251");

if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && $_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest") {
	$allowed_url = array("yadi.sk", "skrinshoter.ru", "prntscr.com", "radikall.com", "radikal.ru", "rghost.ru", "joxi.ru", "mepic.ru", "screenshotlink.ru", "clip2net.com", "snag.gy");
	$status_chat_rules = (!isset($_COOKIE["status_chat_rules"]) | (isset($_COOKIE["status_chat_rules"]) && intval($_COOKIE["status_chat_rules"])!=1)) ? 0 : 1;

	echo '<div style="padding:10px 5px;">';
		echo '<div class="text-red"><b>� ���� ���������:</b></div><hr style="margin:5px auto 10px;">';
		echo '<div class="chat-rules-line"><b>1.</b> ��������� ����� ���������� ���������� ���������, � ��� ����� ������ ������� ��� ������ � �� ����������, ���-������, ������ �� ��������� ������� (�� ����������� ������ �� ����� ����������: '.implode(", ", $allowed_url).').</div>';
		echo '<div class="chat-rules-line"><b>2.</b> ������������� ������������� �������, �����������, �������������� ������������ � ����� ������������� ����������� � �������������.</div>';
		echo '<div class="chat-rules-line"><b>3.</b> ������� (������ �������� ������ ���������� ���������� ����������� ��� ������������� ���������, ��� ��������� ��� � ���������� ��������).</div>';
		echo '<div class="chat-rules-line"><b>4.</b> ��������� �������� ������������� ��� ���������, � ��� �� ���������, ��� � ��� ������. </div>';
		echo '<div class="chat-rules-line"><b>5.</b> ��������� �� ������, �������������� ��� �������� ��� ����������� ������ ������������� �� ������������ ����� ���� �����.</div>';
		echo '<div class="chat-rules-line"><b>6.</b> �������������� ������� ���-��� ���������: ��������� �� ����� ��� 3-5 ������� � ������� � ����.</div>';
		echo '<div class="chat-rules-line"><b>7.</b> �������������� ������� ��������� �������� (Caps Lock). �������������� �������������� ��-����� � ��������� ���������. ��� �������� ������ ����� � ����������� � ������ �������������.</div>';
		echo '<div class="chat-rules-line"><b>8.</b> ��������� � ����� ���� ���������� �������, �������, ������������ � ������������������ ����, ����������, ������������ ���������� � ����-����������� ������������.</div>';
		echo '<div class="chat-rules-line" style="margin-top:20px;"> - ���������� ���� ����� ����� ������������� ����������� ������ ������ � ���� �� ����� ���� �� ������������ ����������.</div>';
		echo '<div class="chat-rules-line" style="margin-bottom:20px;"> - ������������� ����� ����� ����� ���������� �����������, ������ �� ���������� ��������!</div>';
		echo '<div style="text-align:center; font-weight:bold;">�� ��������� �������! ��������� � ��� ������������.</div>';
		if($status_chat_rules!=1) echo '<div style="text-align:center; margin-top:15px;"><span class="sd_sub big green" onClick="setCookie(\'status_chat_rules\', 1, \'/\', (location.hostname || document.domain)); $.modalpopup(\'close\'); return false;">� ��������� ����������</span></div>';
	echo '</div>';
}

?>