<div align="center"><strong><span id="seconds"><?echo date( 'Y-ã m-ì d-÷ ', time() );?></span></strong>
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
    "am" => "äï",
    "pm" => "ïï",
    "AM" => "ÄÏ",
    "PM" => "ÏÏ",
    "Monday" => "Ïîíåäåëüíèê",
    "Mon" => "Ïí",
    "Tuesday" => "Âòîğíèê",
    "Tue" => "Âò",
    "Wednesday" => "Ñğåäà",
    "Wed" => "Ñğ",
    "Thursday" => "×åòâåğã",
    "Thu" => "×ò",
    "Friday" => "Ïÿòíèöà",
    "Fri" => "Ïò",
    "Saturday" => "Ñóááîòà",
    "Sat" => "Ñá",
    "Sunday" => "Âîñêğåñåíüå",
    "Sun" => "Âñ",
    "January" => "ßíâàğÿ",
    "Jan" => "ßíâ",
    "February" => "Ôåâğàëÿ",
    "Feb" => "Ôåâ",
    "March" => "Ìàğòà",
    "Mar" => "Ìàğ",
    "April" => "Àïğåëÿ",
    "Apr" => "Àïğ",
    "May" => "Ìàÿ",
    "May" => "Ìàÿ",
    "June" => "Èşíÿ",
    "Jun" => "Èşí",
    "July" => "Èşëÿ",
    "Jul" => "Èşë",
    "August" => "Àâãóñòà",
    "Aug" => "Àâã",
    "September" => "Ñåíòÿáğÿ",
    "Sep" => "Ñåí",
    "October" => "Îêòÿáğÿ",
    "Oct" => "Îêò",
    "November" => "Íîÿáğÿ",
    "Nov" => "Íîÿ",
    "December" => "Äåêàáğÿ",
    "Dec" => "Äåê",
    "st" => "îå",
    "nd" => "îå",
    "rd" => "å",
    "th" => "îå"
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
