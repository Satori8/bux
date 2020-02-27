function on(n){  
eval("document.getElementById('text'+n).style.display='block';");
eval("document.getElementById('ontext'+n).style.display='none';");
eval("document.getElementById('offtext'+n).style.display='inline';");
}  
function off(n){  
eval("document.getElementById('text'+n).style.display='none';");
eval("document.getElementById('ontext'+n).style.display='inline';");
eval("document.getElementById('offtext'+n).style.display='none';");
}