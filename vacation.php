<?php
$pagetitle="������";
include('header.php');
require_once('.zsecurity.php');

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<fieldset class="msg-error">������! ��� ������� � ���� �������� ���������� ��������������!</fieldset>';
	include('footer.php');
	exit();
}else{
	require('config.php');


	$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='reit_noactive' AND `howmany`='1'");
	$reit_noactive = mysql_result($sql,0,0);

	$username = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? uc($_SESSION["userLog"]) : false;
         
        echo '<div style="color:#107; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#f0ffff" align="justify"> ';
	echo '<img src="img/vacation.jpg"width="70" height="64" align="left">';
		echo '<b>������������� ������?</b> �� �� ������� �������� ���� ������� ��������������� ����� � ������� �������� ��� �� ������� ���� ������� ������������? ';
		echo '�� <b>'.$domen.'</b> �� ������ ����� ������ ������������������ �� 6 �������. �� �� ������������ ��� ����� �� ������ �������������. ';
		echo '�� ��������� ����� ������ ������� �� ������ ����� ����� ���, ���� ������ ��� �����������. �������������� ����� �� ������ � <b>���������</b>.';
	echo '<br>';

	echo '<h2 class="sp">������ ������� ����� ������?</h2>';
	echo '<div align="justify">';
		echo '���� �� ���������� ������������� ����� 2-� �������, �� ����� ����� ������� ������� � ����� "�������". ';
		echo '��-������, ������ ��� ���� ������� � �������, ������� �� ������ ��� �� ������������ �������� <a href="/tos.php" target="_blank">������ �������, ����� 1.15</a>. ';
		echo '��-������, �� ������ ������� ������� ��������� � ���������� �����, � ������� ���������� ����������� ������� �������� �� ��� ���, ';
		echo '���� �������� �������� �� ������ ����� ������� ���� ����� ������ ��� ��������. ��� ������� ������� �� ��������� ���������������.';
	echo '<br>';

	echo '<div align="center">';
		echo '<h2 class="sp"><b>������� ���������� ���� ������� � ������� ������ "� ������!"<br>(�� ����� 180 ����)</b></h2><br>';

		echo '<form name="count_vacation" onsubmit="return false;" id="newform">';
			echo '<input type="hidden" name="cnt" value="'.md5($username+DATE("H")+24664).'" />';
			echo '<input type="text" name="count_vac" maxlength="3" value="60" class="ok12" style="text-align:center;"><br><br>';
			echo '<span class="sub-blue" style="float:none; font-size:12px;" onclick="javascript:go_vacation();" />� ������!</a><br>';
		echo '</form>';
	echo '</div>';
echo '</div>';

         echo '<div style="color:#107; font-size:12px;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.6); padding:10px; margin:10px;background:#e9fcff" align="justify"> ';
	echo '<h2 class="sp">���� ��� ���� �������</h2>';
	echo '<b>1.</b> ��-���������, ���� ������� ��������� � �������, �� ���� ��������� ��������� �����������:<br>';
	echo '<ul class="red">';
		echo '<li>����������� ��������� '.$reit_noactive.' ���� �������� �� ������������ �� ����� ����������;</li>';
		echo '<li>�� ����������� ����� �� ���������. ��� ���� ���� �������� � ��� ������� �������� ����������� �������������;</li>';
		echo '<li>�� ��������� ����� �������, ���� �� �� ��� ������� ������������� �����, ������� ����� ��������� � ������� ����� � ����� ��� ������ ����� ����� ����� ���� �����, ���� �������� �������� �� ������ ���������.</li>';
	echo '</ul>';
	echo '<b>2.</b> � ������ ������ "������������ ��������" ������� ����������� �� ��������, ���� ��� �������� ��������� � �������:<br>';
	echo '<ul class="green">';
		echo '<li>�� ����� ������� �� ��������� ������� �� ������������;</li>';
		echo '<li>����������� ������ �� ���������;</li>';
		echo '<li>������������ �������� �� ���� �������� ������, �������� �����, ����� �� �������� �� �������;</li>';
		echo '<li>������� �� ��������� �� ������������ �� ������� � ������� ����� ����� �������� ������, ���������� �� ����, ��������� ������� � ������� ��� ���.</li>';
	echo '</ul><br>';


	echo '<div align="center">';
		echo '<h2 class="sp">�������� ������ "������������ ��������"</h2>';
		if($vac2>0 && $vac2>time()) {
			echo '<span class="msg-ok">��� ������� �������������. ��������� '.DATE("d.m.Y",$vac2).'</span>';
		}else{
			echo '<span class="msg-w">� ��� ��� ��� ������ "������������ ��������"</span>';
		}

		echo '������ "������������ ��������" ��������������� ����� ����� ������ � �� ������� �� ����, ��������� ������� � ������� ��� ���. ��������� ������ <b>20.00</b> ������ � �����. ������� ���������� ������� � ������� ������ "��������"<br><br>';

		echo '<form name="pay_vac" onsubmit="return false;" id="newform">';
			echo '<input type="hidden" name="cnt" value="'.md5($username+DATE("H")+24664).'" />';
			echo '<input type="text" name="count_month" maxlength="3" value="3" class="ok12" style="text-align:center;"><br><br>';
			echo '<span class="sub-blue" style="float:none; font-size:12px;" onclick="javascript:pay_vacation();" />��������</a><br>';
		echo '</form>';
	echo '</div>';
    echo '</div>';
 echo '</div>';

}

include('footer.php');?>