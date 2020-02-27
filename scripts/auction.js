timer_containers = false;
function az(i){
	return (i < 10)? '0' + i: '' + i;
}
function dz(i){
	return (i.substr(0, 1) == '0' && i.length == 2)? i.substr(1): i;
}
function ct(){
	var tc = false, n = false, p_tag = 'div', t = timer_containers;
	for(var i = 0; i < t.length; ++i){
		if( typeof t[i].innerHTML == "undefined"
			|| t[i].innerHTML.indexOf(':') != 2
			) continue;
		tc = t[i].innerHTML;
		m = dz(tc.substring(0, tc.indexOf(':')));
		s = dz(tc.substr(tc.indexOf(':')+1));
		if(m == 0 && s == 0) continue;
		if(s == 0 && m > 0){ --m; s = 59 }
		else if(s == 0) s = 59;
		else --s;
		t[i].innerHTML = az(m) + ':' + az(s);
		if(m == 0 && s <= 59)
			t[i].style.color = 'red';
		if(m == 0 && s == 0){
			n = t[i].parentNode;
			do{
				if(n.tagName.toLowerCase() == p_tag.toLowerCase()){
					n.style.display = 'none';
					n = true;
				}else n = n.parentNode;
			}while(n !== true);
		}
	}
	setTimeout('ct()', 1000);
}
function timer_init(){
	var n = 'span', t = document.getElementsByTagName(n), c = new Array();
	for(var i = 0; i < t.length; ++i)
		if(t[i].className == 'end_time')
			c[c.length] = t[i];
	timer_containers = c;
	setTimeout('ct()', 1000);
}