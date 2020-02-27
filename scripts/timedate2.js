function clock(){
if (!document.all&&!document.getElementById) return
thetime=document.getElementById? document.getElementById("time"): document.all.time

var Digital=new Date()
var hours=Digital.getHours()
var minutes=Digital.getMinutes()
var seconds=Digital.getSeconds()
var dn="PM"
if (hours<=9) hours="0"+hours  
if (minutes<=9) minutes="0"+minutes  
if (seconds<=9) seconds="0"+seconds  
var newtime=hours+":"+minutes+":"+seconds
thetime.innerHTML="<u style='font-size:14;color:#FFFF00;'>"+newtime+"</u>"
 
if (!document.all&&!document.getElementById) return
thedata=document.getElementById? document.getElementById("data"): document.all.data
var Digital=new Date()
var date=Digital.getDate()
var month=Digital.getMonth()+1
var fullyear=Digital.getFullYear()
if (date<=9) date="0"+date
if (month<=9) month="0"+month
var newdate=date+"."+month+"."+fullyear
thedata.innerHTML="<u style='font-size:14;color:#FFFF00;'>"+newdate+"ã.</u>"
setTimeout("clock()",1000)}