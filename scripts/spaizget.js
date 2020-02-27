function spaizGetElementById(id){
if (document.getElementById(id)) {
return document.getElementById(id);
} else if (document.all[id]) {
 return document.all[id];
 } else if (document.layers & document.layers[id]) {
  return (document.layers[id]);
  } else {
   return false;
  }
}
function toggle_visibility(id, flag)  {
if (spaizGetElementById(id)) {
spaizGetElementById(id).style.visibility = (flag) ? 'visible' : 'hidden';
}
}