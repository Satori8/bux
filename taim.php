<div align="center"><strong><span id="seconds"><?echo date( 'Y-� m-� d-� ', time() );?></span></strong>
				<script language="JavaScript">
                   var hours = <?php echo date("H"); ?>;
                    var min = <?php echo date("i"); ?>;
                    var sec = <?php echo date("s"); ?>;
                   	function display() {
                   	sec+=1;
                   	if (sec>=60){
					   min+=1;
					   sec=0;
                   	}
                   	if (min>=60) {
					   hours+=1;
					   min=0;
                   	}
                   	if (hours>=24) hours=0;
                   
                   	if (sec<10) {
                   		sec2display = "0"+sec;
				   	}else{
                   		sec2display = sec;
				   	}
                    
                    if (min<10){
                    	min2display = "0"+min;
					}else{
                    	min2display = min;
					}
					
                    if (hours<10){
                    	hour2display = "0"+hours;
					}else{
                   		hour2display = hours;
					}
					
                    document.getElementById("seconds").innerHTML = hour2display+":"+min2display+":"+sec2display;
                    setTimeout("display();", 1000);
                    }
                    
					display();
				</script>
			</div>

<?php
function rus_date() {
    $translate = array(
    "am" => "��",
    "pm" => "��",
    "AM" => "��",
    "PM" => "��",
    "Monday" => "�����������",
    "Mon" => "��",
    "Tuesday" => "�������",
    "Tue" => "��",
    "Wednesday" => "�����",
    "Wed" => "��",
    "Thursday" => "�������",
    "Thu" => "��",
    "Friday" => "�������",
    "Fri" => "��",
    "Saturday" => "�������",
    "Sat" => "��",
    "Sunday" => "�����������",
    "Sun" => "��",
    "January" => "������",
    "Jan" => "���",
    "February" => "�������",
    "Feb" => "���",
    "March" => "�����",
    "Mar" => "���",
    "April" => "������",
    "Apr" => "���",
    "May" => "���",
    "May" => "���",
    "June" => "����",
    "Jun" => "���",
    "July" => "����",
    "Jul" => "���",
    "August" => "�������",
    "Aug" => "���",
    "September" => "��������",
    "Sep" => "���",
    "October" => "�������",
    "Oct" => "���",
    "November" => "������",
    "Nov" => "���",
    "December" => "�������",
    "Dec" => "���",
    "st" => "��",
    "nd" => "��",
    "rd" => "�",
    "th" => "��"
    );
    
    if (func_num_args() > 1) {
        $timestamp = func_get_arg(1);
        return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
        return strtr(date(func_get_arg(0)), $translate);
    }
} 
 
echo " ". rus_date("l j F Y")." "; 
?>
