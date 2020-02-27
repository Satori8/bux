function focus_username(e){
	if (e.value == 'Логин')
	{
		e.value = '';
		return false;
	}
}

function focus_password(e){
	if (e.value == '------------')
	{
		e.value = '';
		return false;
	}
}

function refresh_on(_element_id){
	var element = document.getElementById(_element_id);
	if (element)
		{
		element.src = element.src + '?' + (new Date()).getMilliseconds()
		}
}