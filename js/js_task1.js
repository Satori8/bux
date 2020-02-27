$(document).ready(function(){
    $("#tsk_mnu1").click(function(){
        if (document.getElementById('tsk_mnu_block1').style.display != '') {
            $('.usermnutitle-g').each(function () {
                if($(this).hasClass('active')){$(this).removeClass('active');}
            })
            $(this).addClass('active');
            document.cookie="tmblock=1";
            $("#tsk_mnu_block1").slideToggle("fast");
            document.getElementById('tsk_mnu_block2').style.display = 'none'; 
            document.getElementById('tsk_mnu_block3').style.display = 'none'; 
            document.getElementById('tsk_mnu_block4').style.display = 'none'; 
            document.getElementById('tsk_mnu_block5').style.display = 'none'; 
        }        
        return false;
    });
    $("#tsk_mnu2").click(function(){
        if (document.getElementById('tsk_mnu_block2').style.display != '') {
            $('.usermnutitle-g').each(function () {
                if($(this).hasClass('active')){$(this).removeClass('active');}
            })
            $(this).addClass('active');
            document.cookie="tmblock=2";
            $("#tsk_mnu_block2").slideToggle("fast");
            document.getElementById('tsk_mnu_block1').style.display = 'none'; 
            document.getElementById('tsk_mnu_block3').style.display = 'none'; 
            document.getElementById('tsk_mnu_block4').style.display = 'none'; 
            document.getElementById('tsk_mnu_block5').style.display = 'none'; 
        }        
        return false;
    });
    $("#tsk_mnu3").click(function(){
        if (document.getElementById('tsk_mnu_block3').style.display != '') {
            $('.usermnutitle-g').each(function () {
                if($(this).hasClass('active')){$(this).removeClass('active');}
            })
            $(this).addClass('active');
            document.cookie="tmblock=3";
            $("#tsk_mnu_block3").slideToggle("fast");
            document.getElementById('tsk_mnu_block2').style.display = 'none'; 
            document.getElementById('tsk_mnu_block1').style.display = 'none'; 
            document.getElementById('tsk_mnu_block4').style.display = 'none'; 
            document.getElementById('tsk_mnu_block5').style.display = 'none'; 
        }        
        return false;
    });
    $("#tsk_mnu4").click(function(){
        if (document.getElementById('tsk_mnu_block4').style.display != '') {
            $('.usermnutitle-g').each(function () {
                if($(this).hasClass('active')){$(this).removeClass('active');}
            })
            $(this).addClass('active');
            document.cookie="tmblock=4";
            $("#tsk_mnu_block4").slideToggle("fast");
            document.getElementById('tsk_mnu_block2').style.display = 'none'; 
            document.getElementById('tsk_mnu_block3').style.display = 'none'; 
            document.getElementById('tsk_mnu_block1').style.display = 'none'; 
            document.getElementById('tsk_mnu_block5').style.display = 'none'; 
        }        
        return false;
    });
    $("#tsk_mnu5").click(function(){
        if (document.getElementById('tsk_mnu_block5').style.display != '') {
            $('.usermnutitle-g').each(function () {
                if($(this).hasClass('active')){$(this).removeClass('active');}
            })
            $(this).addClass('active');
            document.cookie="tmblock=5";
            $("#tsk_mnu_block5").slideToggle("fast");
            document.getElementById('tsk_mnu_block2').style.display = 'none'; 
            document.getElementById('tsk_mnu_block3').style.display = 'none'; 
            document.getElementById('tsk_mnu_block4').style.display = 'none'; 
            document.getElementById('tsk_mnu_block1').style.display = 'none'; 
        }        
        return false;
    });
});


function gotfilter(fval, fmode) {
    if (fmode == 1) {
        document.cookie="tfilter1="+fval;
        document.cookie="tfilter2=-1";
        document.cookie="tftask=0";
        document.cookie="tfuser=0";
        document.cookie="tfsite=";
    } else {
        document.cookie="tfilter2="+fval;
        document.cookie="tfilter1=-1";
        document.cookie="tftask=0";
        document.cookie="tfuser=0";
        document.cookie="tfsite=";
    }
    window.location.href = "/work-task.php?f=1";
    return true;
}

function gosorttask(val) {
    document.cookie="tasksort="+val;
    window.location.reload(true);
    return true;
}

function gotsearch(doc, fmode) {
    if (fmode == 1) {
        var val1 = doc.forms['taskselectform'].tasknum.value;
        if (val1 != '') {
            document.cookie="tftask="+val1;
            document.cookie="tfilter1=-1";
            document.cookie="tfilter2=-1";
            document.cookie="tfuser=0";
            document.cookie="tfsite=";
        }
    } else
    if (fmode == 2) {
        var val2 = doc.forms['taskselectform'].taskuser.value;
        if (val2 != '') {
            document.cookie="tfuser="+val2;
            document.cookie="tfilter1=-1";
            document.cookie="tfilter2=-1";
            document.cookie="tftask=0";
            document.cookie="tfsite=";
        }
    } else
    if (fmode == 3) {
        var val3 = doc.forms['taskselectform'].taskurl.value;
        if (val3 != '') {
            document.cookie="tfsite="+val3;
            document.cookie="tfilter1=-1";
            document.cookie="tfilter2=-1";
            document.cookie="tftask=0";
            document.cookie="tfuser=0";
        }
    }
    window.location.href = "/work-task.php?f=1";
    return true;
}

function sendfav(doc, adv, cnt)
{
    var myReq = getHTTPRequest();
    var params = "mode=4&cnt="+cnt+"&adv="+adv;
    function setstate()
    {
        if ((myReq.readyState == 4)&&(myReq.status == 200)) {
            var resvalue = myReq.responseText;
            if (resvalue == 'ok') {
                doc.getElementById('fav'+adv).style.display = 'none';
            }
        }
    } 
    myReq.open("POST", "/proc-service/us-worktask.php", true);
    myReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    myReq.setRequestHeader("Content-lenght", params.length);
    myReq.setRequestHeader("Connection", "close");
    myReq.onreadystatechange = setstate;
    myReq.send(params);
    return false;
}