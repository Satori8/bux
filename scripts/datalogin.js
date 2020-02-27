function Formdata(data)
{
	if (data.username != null &&data.username.value.length == 0)
		{
		alert('Вы не ввели "Логин"');
		return false;
		}
	username = document.getElementById("username");
	if (username.value == 'Логин')
		{
		alert('Вы не ввели "Логин"');
		return false;
		}
	if (data.password != null &&data.password.value.length == 0)
		{
		alert('Вы не ввели "Пароль"');
		return false;
		}
	password = document.getElementById("password");
	if (password.value == '------------')
		{
		alert('Вы не ввели "Пароль"');
		return false;
		}
	if (data.captcha != null &&data.captcha.value.length == 0)
		{
		alert('Вы не ввели "Защитный код"');
	