function SignIn(aut)
{
	if (aut.user != null &&aut.user.value.length == 0)
		{
		alert('Вы не ввели "Логин"');
		return false;
		}
	user = document.getElementById("user");
	if (user.value == 'Логин')
		{
		alert('Вы не ввели "Логин"');
		return false;
		}
	if (aut.pass != null &&aut.pass.value.length == 0)
		{
		alert('Вы не ввели "Пароль"');
		return false;
		}
	pass = document.getElementById("pass");
	if (pass.value == '------------')
		{
		alert('Вы не ввели "Пароль"');
		return false;
		}
	if (aut.captcha != null &&aut.captcha.value.length == 0)
		{
		alert('Вы не ввели "Защитный код"');
		return false;
		}
}