<?php 

$email_conf = array (
	'smtp_host' => 'mail.scorpionbux.info',
	'smtp_log' => 'support@scorpionbux.info',
	'smtp_pass' => '8T1h3Z1v',
	'smtp_port' => '25',
	'smtp_name' => 'ScorpionBux',
	'smtp_charset' => 'UTF-8',
);

$email_temp = array (

  'rega_1' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
		  <tbody>
		    <tr><td align="center">
		      <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
		        <tbody>
		          <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
		          <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">����� ���������� �� ������ <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
		          <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">��� ��� ������������� E-mail: <b>{CODE}</b></td></tr>
		          <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
		        </tbody>
		      </table>
		    </td></tr>
		  </tbody>
		</table>
  ',
  
  'rega_2' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">����� ���������� �� ������ <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    <u>���� ��������������� ������:</u><br><br>
								ID: <b>{ID}</b><br>
								�����: <b>{LOGIN}</b><br>
								������: <b>{PASS}</b><br>
								������ ��� ��������: <b>{PIN}</b><br>
								IP �����: <b>{IP}</b><br><br>
								<span style="color:#FF0000;">��������! ����������� ��������� ������. ������ ����� �������� � ������� �������.</span>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'login' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">����������� ������������ <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    <u><b>{LOGIN}</b></u><br><br>
								<b>�� �������� ��� ������, ������ ��� � ��� ������� �� ������� <a style="text-decoration:none; color:#000;">scorpionbux.info</a></b><br>
								<b>�������� ���� �</b><br>
								IP ������: <b>{IP}</b><br>
								<b>���� ��� ���� �� �� ������ ������������� � �������� ������!</b>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'recc_1' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">��������� �������� �� ������� <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						  <u>���� ��������������� ������:</u><br><br>
							��� ID:<b> {ID}</b><br>
				             ��� �����:<b> {LOGIN}</b><br>
							 ��� ��������� �������� �������� �� ������ ����:<br>
						    <a href="https://scorpionbux.info/{CODE}" target="_blank">https://scorpionbux.info/{CODE}</a>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'mess' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">��� �������� ����� ��������� �� ������� <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						 <u>��� ������ ����� ���������</u><br><br>
                        �� ������������:<br>
						<img src="https://scorpionbux.info/avatar/{AVATAR}" style="width:60px; height:60px" border="0" title=""><b> {LOGIN}</b><br>
						<a href="https://scorpionbux.info/inbox.php" target="_blank">��������� � ��� ������� ��� ���������!</a>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'recc_2' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">�������������� ������ ��� ����������� �� ������� <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    ������������!<br>
				        �� ��������� ������ ��� ����������� �� ������� <b>scorpionbux.info</b></a><br>
				        ������ ��� ����������� � IP ������: <b>{IP}</b><br>
				        ��� ID:<b> {ID}</b><br>
				        ��� �����:<b> {LOGIN}</b><br>
				        ��� ������:<b> {PASS}</b><br>
				        ��� ������ ��� ��������:<b> {PIN}</b><br>
				        <span style="color:#FF0000;">��������!</span> ����������� ��������� ������. ������ ����� �������� � ������� �������.
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'pass_op' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">�������������� ������ ��� �������� �� ������� <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    ������������!<br>
				        �� ��������� ������ ��� �������� �� ������� <b>scorpionbux.info</b></a><br>
				        ������ ��� ����������� � IP ������: <b>{IP}</b><br>
				        ��� ID:<b> {ID}</b><br>
				        ��� �����:<b> {LOGIN}</b><br>
				        ��� ������ ��� ��������:<b> {PIN}</b><br>
				        <span style="color:#FF0000;">��������!</span> ����������� �������� ��� ��������� ������ ��� ��������. ��� ������ ������ ������, ������ ����� ��������������!
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'birthday' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">���������, {LOGIN} ������������ ��� � ���� ��������!</td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    <b>� ���� �������� �����������!<br>
						    ��������, ������� � ������� ������,<br>
						    ����� ��� ��������� ��������� �����,<br>
						    ����� ������ ���� ���� ����� ����� ����������,<br>
						    ����� ������ �� ���� ���� ������� �����,<br>
						    ����� ������ ���� ����� ������� �����!</b><br><br>
				        � ���������, ������������� ������� <b style="color:#009E58;">scorpionbux.info</b>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'budil' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">�������������� ��������� �������!</td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    ������������!<br>
						    �� ���������������� �� ����� <a href="http://scorpionbux.info">http://scorpionbux.info</a> ��� ����� <b>{LOGIN}</b>.<br>
						    �� ����� �� �������� � ���� ������� � ��� ������� <b>{LOGIN_REF}</b> �������� ��� �����������.<br>
						    {TEXT} <a href=\"http://scorpionbux.info\">��������� � ������� � ���������� ������!</a>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'rass' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
			<tbody>
			  <tr><td align="center">
			    <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
						<tbody>
						  <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
					    <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">������ ������������� <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
						  <tr><td align="left" style="font-size:12px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">
						    �� �������� ��� ������ �������������, ��� ��� ��������� ������������������ ������������� ������� scorpionbux.info<br><br>
						    <b>���������� ������:</b><br>
				        {TEXT}<br><br>
				        ������������� scorpionbux.info �� ����� �������� ��������� � �� ����� ������� ��������������� �� ���������� ������� ������.<br>
				        ����� ���������� �� ��������� �����, ������� � ���� ������� � � ������� ������� ������� � �������� ����������� ��������� ������ �� e-mail�.<br><br>
				        <i>��� �������������� ���������, �������� �� ���� �� ����, �� ��� ����� �� ������� ��� ����� �� ����.</i>
						  </td></tr>
						  <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
						</tbody>
			    </table>
			  </td></tr>
			</tbody>
		</table>
  ',
  
  'add_din' => '
    <table align="center" border="0" cellpadding="10" cellspacing="0" style="width:100%; background-color:#E5E5E5;">
		  <tbody>
		    <tr><td align="center">
		      <table align="center" cellpadding="0" cellspacing="0" style="border:1px solid #DDD; width:100%; background-color:#FFFFFF;">
		        <tbody>
		          <tr><td align="left" style="background:url(https://scorpionbux.info/img/logo/logo.gif) no-repeat bottom left; background-color:#dfba86; padding:46px;"></td></tr>
		          <tr><td style="background-color:#ff7f50; font-size:16px; line-height:16px; text-align:center; text-shadow: 1px 1px 1px #000; padding:15px; color:#FFF; font-weight: normal;">������� ������� �����, ��������� ������������ ������� <a style="text-decoration:none; color:#FFF;">scorpionbux.info</a></td></tr>
		          <tr><td align="left" style="font-size:13px; font-family:Arial,Helvetica,sans-serif; line-height:20px; padding:20px;">				  
			     ������������ ��������� <b>{LOGIN}</b>!<br/>
				 �� ��������� ������ �� ����� {MONEY} ���.<br/>
                     �� ��������� ���� �������� ������ � ������� {BONUSKEH} ���. ({BONUS}% �� ����� ����������)
				  
				  </td></tr>
		          <tr><td align="left" style="border-top:1px solid #DDD; font-size:12px; padding:10px 20px;">� ���������, �������������� ������ ����������� <a href="https://scorpionbux.info" style="color:#009E58;">scorpionbux.info</a></td></tr>
		        </tbody>
		      </table>
		    </td></tr>
		  </tbody>
		</table>
  ',
  
);

?>