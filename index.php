<?php 
$pagetitle="�������";
include("header.php");

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_user' AND `howmany`='1'") or die(mysql_error());
$bon_reg_user = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_reg_ref' AND `howmany`='1'") or die(mysql_error());
$bon_reg_ref = mysql_result($sql,0,0);

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='bon_popoln' AND `howmany`='1'") or die(mysql_error());
$bon_popoln =  mysql_result($sql,0);

echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:2px;"><b>����� ���������� �� '.$domen.'</b></h1>';

echo '<div class="text-justify">';
	echo '<img src="/img/user4.png" alt="" title="" class="img-left" width="170px" />';
	echo '�� ����������� ���� ����������� ������ ������� ����� ��������� ������ � ��������. ���-�� �������� ��� ��������� ����� � ���. ����� � �����, � ���-�� ����� �������������� �������� ������, ������� �� ����� �������. <b style="color:#ff7f50">'.$domen.' </b>� ��� ������� �������� �������, ��������������� ����������� ������������ ��� ���������� ���������� ������. � ��� ������� 10 �������� ���������, ����� �������: ������������ �������, ������� ������, </b><span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> �������, ������ �����, VIP-�������, ����-������� ������, ����-������� <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span>, ��������� ������� ������, ������������ ���������. ����� ����� �� ����� �������� ���� ����������� � �������������, ��������� � ������������� ���������� ����� ���������� ������! ������� ������������ ������ ����� ��������� �� ���������� �������� ������� WebMoney ������.������ � Payeer. ��� ������������� �����-���� �������� �� ������ ���������� � ����������� ���������, ��� �������� ����������� ����� �� ���� ������. �� ���� ��������� ��������, ������������ ����� �������� ��������� ���������� �� <b style="color:#ff7f50">'.$domen.'</b>. � ��� ������������ ������� ���������� �������, ��������� ������� ���� ��������� �������� � ����. ������� ����� ��������� �������� ������? �� ����� ��� ��� ����������! ����������� �������� ��������� � ��������� % �� �� ���������.';
echo '</div><br>';
echo '<h4 class="sp">���������� ��� ���:</h4>';
echo '<ul class="green">';
	if($bon_reg_user>0) echo '<li><span style="color:#DE1200">����� �� ����������� -  <b>'.number_format($bon_reg_user, 2, ".", "").' ���.</span></b>;</li>';
	if($bon_reg_ref>0) echo '<li><span style="color:#DE1200">����� �� ����������� �������� -  <b>'.number_format($bon_reg_ref, 2, ".", "").' ���.</span></b>;</li>';
	echo '<li>3-�-��������� ����������� �������;</li>';
	echo '<li>��������� �� ��������, <span style="color: #3F3F3F;">You</span><span style="border-radius: 5px; background-color: #E62117; padding: 2px; color: #FFFFFF;">Tube</span> ��������, ��������� ��������, ����-��������, ������ �����, ���������� �������;</li>';
	echo '<li>���������� ������, ������� <a href="/status.php" style="border-bottom:1px dotted;">��������� ����</a>;</li>';
	echo '<li>���������� �������;</li>';
	echo '<li>��������� ������ ������;</li>';
	echo '<li>���������� ��������.</li>';
echo '</ul>';

echo '<div align="center">';
echo '<tr align="center">';
		    echo '<td>';
			    echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:2px;"><b>�������������� �������</b></h1>';
		        echo '<img src="/img/pay_img/webmoney.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/yandex.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/payeer.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/qiwi.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/perfect_money.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/megafon.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/mts.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay_img/visa_mastercard.png" width="118" height="72" border="0" alt="">';
            echo '</td>';
		echo '</tr>';
echo'</div>';
echo '<br>';
echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/register.php" style="width:160px; float:none;">����������</span></div>';

echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:20px;"><b>��� ��� ������������ ���������</b></h1>';
echo '<div class="text-justify">';
	echo '<img src="/images/test1.png" alt="" title="" class="img-right" width="100px" />';
	echo '���� �� ������ ���� ����, ������, ��� ��������� ������� �������� � ��� ����������. ';
	echo '���� ������ ��������, � �������� ����� � ���� ������������� ���� ��� ��������� � ������ ��� ������� ����������� ���������� �� ������������ ��������. ';
	echo '�� ������ �������� �������� ����� � ��������, ��� ������ ������� ����� � ��������� ������ ����� ������. <b style="color:#ff7f50">'.$domen.'</b> � ��� ����������� ��������� �������� � �������� ��� ������������� ������� ���������. ';
	echo ' � ������� ������ ������� ��������� ����� �������� ����������� ���������� ���������� �� ��������� 24 ���� ����������� �� ���� ����. ';
	echo '��������� ����, ��� �������������� ������� ��������� ������������ �������� �������������, ������������ ������ ���������, �� ��������������� �������� (�����������, ������������ � ������), �� ����� �������������, ��� �� ��� ������ �������� �������� ����. ';
        echo '�� <b style="color:#ff7f50">'.$domen.'</b> �������� ����� ���������� ����� � ��������, �� ����� �������� ������� �������� ��������� ����� �� ��������� ����. ����� ��������, ��� �������� ������ ������ ����������� ����� ��� ����� ��������� ����� � ������� ���������� �������. � ����� �������� 17 ������� �����������, �������� �������� ���������� ��� ����� � �������� � ������� ��� ����� � ����';
echo '</div>';
echo '<h4 class="sp">���������� ��� ���:</h4>';
echo '<ul class="green">';
        if($bon_popoln>0) echo '<li><span style="color:#DE1200">����� �� ���������� ���������� ����� + <b>'.number_format($bon_popoln, 0, ".", "").' %</span></b>;</li>';
	echo '<li>����� ������� ��� �����������, ���������� ����������;</li>';
	echo '<li>��������� �������� ����������� � ��������� ��������� ������;</li>';
	echo '<li>������������������� ������� ���������� ���������� ����������;</li>';
	echo '<li>��������� ������ �������� �������, ��������� ��� � PR.</li>';
echo '</ul><br>';
echo '<div align="center">';
echo '<tr align="center">';
		    echo '<td>';
			    echo '<h1 class="sp" style="color:#ab0606; font-size:16px; text-align:center; padding-top:2px;"><b>�������������� ���������� �������</b></h1>';
		        echo '<img src="/img/pay/webmoney.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/yandex_money.png" width="118" height="72" border="0" alt="">';
                echo '<img src="/img/pay/payeer.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/robokassa.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/qiwi.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/perfect_money.png" width="118" height="72" border="0" alt="">';
				echo '<img src="/img/pay/megakassa.png" width="118" height="72" border="0" alt="">';
            echo '</td>';
		echo '</tr>';
echo'</div>';
echo '<br>';
echo '<div align="center"><span class="proc-btn" onClick="document.location.href=\'/advertise.php" style="width:160px; float:none;">�������������</span></div>';

include("footer.php");
?>