function SignIn(aut)
{
	if (aut.user != null &&aut.user.value.length == 0)
		{
		alert('�� �� ����� "�����"');
		return false;
		}
	user = document.getElementById("user");
	if (user.value == '�����')
		{
		alert('�� �� ����� "�����"');
		return false;
		}
	if (aut.pass != null &&aut.pass.value.length == 0)
		{
		alert('�� �� ����� "������"');
		return false;
		}
	pass = document.getElementById("pass");
	if (pass.value == '------------')
		{
		alert('�� �� ����� "������"');
		return false;
		}
	if (aut.captcha != null &&aut.captcha.value.length == 0)
		{
		alert('�� �� ����� "�������� ���"');
		return false;
		}
}