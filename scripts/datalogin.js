function Formdata(data)
{
	if (data.username != null &&data.username.value.length == 0)
		{
		alert('�� �� ����� "�����"');
		return false;
		}
	username = document.getElementById("username");
	if (username.value == '�����')
		{
		alert('�� �� ����� "�����"');
		return false;
		}
	if (data.password != null &&data.password.value.length == 0)
		{
		alert('�� �� ����� "������"');
		return false;
		}
	password = document.getElementById("password");
	if (password.value == '------------')
		{
		alert('�� �� ����� "������"');
		return false;
		}
	if (data.captcha != null &&data.captcha.value.length == 0)
		{
		alert('�� �� ����� "�������� ���"');
	