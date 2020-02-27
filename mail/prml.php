var sav_id = 0;

var scrollTopPos = 0;

var sav_contwrap = $('#contwrap').html();

var sav_uslist = $('#us_list').html();

var contwrap = document.getElementById("contwrap");

var userslist = document.getElementById("us_list");

var timeout_CC;

var scanmode = "";

////////////////////////////////////////////////////////

function getOffset(elem) {
    if (elem.getBoundingClientRect) {
        return getOffsetRect(elem)
    } else {
        return getOffsetSum(elem)
    }
}

function getOffsetSum(elem) {
    var top=0, left=0
    while(elem) {
        top = top + parseInt(elem.offsetTop)
        left = left + parseInt(elem.offsetLeft)
        elem = elem.offsetParent
    }

    return {top: top, left: left}
}

function getOffsetRect(elem) {
    var box = elem.getBoundingClientRect()
    var body = document.body
    var docElem = document.documentElement
    var scrollTop = window.pageYOffset || docElem.scrollTop || body.scrollTop
    var scrollLeft = window.pageXOffset || docElem.scrollLeft || body.scrollLeft
    var clientTop = docElem.clientTop || body.clientTop || 0
    var clientLeft = docElem.clientLeft || body.clientLeft || 0
    var top  = box.top +  scrollTop - clientTop
    var left = box.left + scrollLeft - clientLeft
    return { top: Math.round(top), left: Math.round(left) }
}

////////////////////////////////////////////////////////

function ScrollContent() {
contwrap.scrollTop = contwrap.scrollHeight;
}

////////////////////////////////////////////////////////

function CheckCurrent (cur_id,flag) {
clearTimeout(timeout_CC);
if (sav_id==0) {
document.getElementById("prmlist").style.width="200px";
document.getElementById("prmlist").style.margin="5px 5px 5px 0";
document.getElementById("prmlist").style.padding="5px 0";
document.getElementById("prmlist").style.direction="rtl";
document.getElementById("prmled").style.width="720px";
document.getElementById("prmled").style.margin="0 0 5px 0";
document.getElementById("cwcont").style.display="block";
document.getElementById("us_list").style.height="588px";
var w210 = document.querySelectorAll('.w210');
for(var i = 0, w_210; w_210 = w210[i]; i++) {
w_210.classList.remove("w210");
w_210.style.display="block";
}
}

var sav_id_=sav_id;
sav_id=cur_id;

if (sav_id_!=sav_id) {
if (document.forms["form_"+cur_id].sname.value!="su") {
document.forms["prmlfrm"].touser.value = document.forms["form_"+cur_id].sname.value;
} else {
document.forms["prmlfrm"].touser.value = "Admin";
}
}

document.forms["prmlfrm"].uid.value = document.forms["form_"+cur_id].uid.value;
document.forms["prmlfrm"].sname.value = document.forms["form_"+cur_id].sname.value;
document.forms["prmlfrm"].rname.value = document.forms["form_"+cur_id].rname.value;
document.forms["prmlfrm"].ava.value = document.forms["form_"+cur_id].ava.value;
document.forms["prmlfrm"].onoff.value = document.forms["form_"+cur_id].ava.value;

var checked_item = document.querySelectorAll('.item_checked');
for(var i = 0, checkeditem; checkeditem = checked_item[i]; i++) {
checkeditem.classList.remove("item_checked");
}
document.getElementById(cur_id).classList.add("item_checked");

if (flag=='1' || sav_id_==0) {
scrollTopPos=$('.item_checked').position().top;
userslist.scrollTop = scrollTopPos-$('#us_list').offset().top;
}
Call("form_"+cur_id,cur_id);
setTimeout('ShowUsers(document.forms["prmlfrm"].rname.value,true)',2000);
}

////////////////////////////////////////////////////////

function SendMessage() {
if (document.forms["prmlfrm"].touser.value == "") { alert("Не указан получатель"); } else {
if (document.forms["prmlfrm"].message.value == "") { alert("Нельзя отправлять пустое сообщение"); } else {
document.getElementById("edsub").disabled="disabled";
setTimeout('document.getElementById("edsub").disabled="";',5000);
Call("prmlfrm",document.forms["prmlfrm"].uid.value);
setTimeout('ShowUsers(document.forms["prmlfrm"].rname.value,true)',2000);
document.getElementById("edmes").value='';
//alert("Cообщение отправлено");
}
}
}

////////////////////////////////////////////////////////

function ShowOne() {
document.forms["prmlfrm1"].uid.value = document.forms["prmlfrm"].uid.value;
document.forms["prmlfrm1"].sname.value = document.forms["prmlfrm"].touser.value;
document.forms["prmlfrm1"].rname.value = document.forms["prmlfrm"].rname.value;
document.forms["prmlfrm1"].ava.value = document.forms["prmlfrm"].ava.value;
document.forms["prmlfrm1"].onoff.value = document.forms["prmlfrm"].onoff.value;
document.forms["prmlfrm1"].mode.value = document.forms["prmlfrm"].mode.value;
if (document.forms["prmlfrm1"].sname.value == "") { alert("Не указан пользователь"); } else {
Call("prmlfrm1",document.forms["prmlfrm1"].uid.value);
setTimeout('ShowUsers(document.forms["prmlfrm1"].rname.value,true)',2000);
}
}

////////////////////////////////////////////////////////

function Call(fid,curr_id) {
$("#loaddiv").show();
sav_contwrap = $('#contwrap').html();
var msg = $('#'+fid).serialize();
$.ajax({
type: 'POST',
cache: false,
dataType: 'text',
url: 'mail/do_mail_ajax.php',
data: msg,
success: function(data) {
 $('#contwrap').html(data);
 if (sav_contwrap != $('#contwrap').html()) {
 ScrollContent();
 }
 setTimeout('$("#loaddiv").hide("slow")',500); 

timeout_CC = setTimeout(function(){ CheckCurrent(curr_id,"0"); }, 10000);
},
error:  function(xhr, str){
 setTimeout('$("#loaddiv").hide("slow")',500); 
// alert('Ошибка обработки данных');
timeout_CC = setTimeout(function(){ CheckCurrent(curr_id,"0"); }, 10000);
}
});
}

////////////////////////////////////////////////////////

function ShowUsers(curuser,flag) {
sav_uslist = $('#us_list').html();
scanmode = document.forms["prmlfrm"].mode.value;
sender = document.forms["prmlfrm"].sname.value;
$.ajax({
type: 'POST',
cache: false,
dataType: 'text',
url: 'mail/do_senders_ajax'+scanmode+'.php',
data: {'curuser':curuser,'sender':sender},
success: function(data) {

$('#us_list').html(data);
 
if (flag==true) {

document.getElementById("prmlist").style.width="200px";
document.getElementById("prmlist").style.margin="5px 5px 5px 0";
document.getElementById("prmlist").style.padding="5px 0";
document.getElementById("prmlist").style.direction="rtl";
document.getElementById("prmled").style.width="720px";
document.getElementById("prmled").style.margin="0 0 5px 0";
document.getElementById("cwcont").style.display="block";
document.getElementById("us_list").style.height="588px";

var w210 = document.querySelectorAll('.w210');
for(var i = 0, w_210; w_210 = w210[i]; i++) {
w_210.classList.remove("w210");
w_210.style.display="block";
}
}

if (sav_id!='0') {
document.getElementById(sav_id).classList.add("item_checked");
document.forms["prmlfrm"].uid.value = document.forms["form_"+sav_id].uid.value;
document.forms["prmlfrm"].sname.value = document.forms["form_"+sav_id].sname.value;
document.forms["prmlfrm"].rname.value = document.forms["form_"+sav_id].rname.value;
document.forms["prmlfrm"].ava.value = document.forms["form_"+sav_id].ava.value;
document.forms["prmlfrm"].onoff.value = document.forms["form_"+sav_id].ava.value;
}

},
error:  function(xhr, str){
// alert('Ошибка обработки данных');
}
});
}

////////////////////////////////////////////////////////

function doOld(fid) {
if (document.getElementById("com_"+fid).style.cursor=="pointer") {
$.ajax({
type: 'POST',
cache: false,
dataType: 'text',
url: 'mail/do_old_ajax.php',
data: 'postid='+fid,
success: function(data) {
document.getElementById("new_"+fid).style.display="none";
document.getElementById("com_"+fid).style.cursor="";
ShowUsers(document.forms["prmlfrm"].rname.value,true);
},
error:  function(xhr, str){
// alert('Ошибка обработки данных');
}
});
}
}

////////////////////////////////////////////////////////

function doRem(fid) {
$.ajax({
type: 'POST',
cache: false,
dataType: 'text',
url: 'mail/do_rem_ajax.php',
data: 'postid='+fid,
success: function(data) {
document.getElementById("com_"+fid).style.display="none";
ShowUsers(document.forms["prmlfrm"].rname.value,true);
},
error:  function(xhr, str){
// alert('Ошибка обработки данных');
}
});
}

////////////////////////////////////////////////////////

function HideBlock(id,fid) {
document.getElementById(id).style.display="none";
$.ajax({
type: 'POST',
cache: false,
dataType: 'text',
url: 'mail/do_oldn_ajax.php',
data: 'postid='+fid,
success: function(data) {
},
error:  function(xhr, str){
// alert('Ошибка обработки данных');
}
});
}	 	 
