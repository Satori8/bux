var timeStr, dateStr;
function clock(){// задаем функцию
now= new Date();
 
// получаем время
hours= now.getHours();
minutes= now.getMinutes();
seconds= now.getSeconds();
timeStr= ((hours < 10) ? "0" : "") + hours;  
 
// добавляем ноль, если показатели ЧАСЫ, МИНУТЫ, СЕКУНДЫ меньше 10  
 
timeStr+= ((minutes < 10) ? ":0" : ":") + minutes;  
timeStr+= ((seconds < 10) ? ":0" : ":") + seconds;  
document.clock.time.value = timeStr;
 
// получаем дату  
date= now.getDate();
month= now.getMonth()+1;
year= now.getFullYear();
dateStr= ((date < 10) ? "0" : "") + date;  
dateStr+= "." + ((month < 10) ? "0" : "") + month;  
dateStr+= "." + year;  
document.clock.date.value = dateStr;  
 
Timer= setTimeout("clock()",1000);}
// заставляем скрипт выполняться каждую секунду