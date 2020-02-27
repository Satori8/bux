<style>

.q_mess_block_ch, .q_mess_block_nh {
	margin: 5px 0 5px 0;
	padding: 5px;
	font-size: 14px;
	font-family: Tahoma;
	text-align: left;
	text-shadow: 0 1px 0 #f6f7f7;
	border-radius:4px; -moz-border-radius:4px; -webkit-border-radius:4px;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
	color: #552526;
	word-wrap: break-word;
}

.q_mess_block_ch {
	background: #77FD9F;
	background: -o-linear-gradient(top, #e48a0b 0px, #f1bd75 100%);
	background: -moz-linear-gradient(top, #e48a0b 0px, #f1bd75 100%);		
	background: -webkit-linear-gradient(top, #e48a0b 0px, #f1bd75 100%);
	background: -ms-linear-gradient(top, #e48a0b 0px, #f1bd75 100%);
}

.q_mess_block_nh {
	background: #DDDDDD;
	background: -o-linear-gradient(top, #F6F7F7 0px, #D7DCDC 100%);
	background: -moz-linear-gradient(top, #F6F7F7 0px, #D7DCDC 100%);
	background: -ms-linear-gradient(top, #F6F7F7 0px, #D7DCDC 100%);
	background: -webkit-linear-gradient(top, #F6F7F7 0px, #D7DCDC 100%);
}

.q_mess_al {
	height:15px;
	margin: 5px -5px -5px -5px;
	border-radius:0 0 4px 4px; -moz-border-radius:0 0 4px 4px; -webkit-border-radius:0 0 4px 4px;
	padding:3px 5px;
	font-size:12px;
}

a.q_mess_author {
	background: #ab0606;
	width: 100%;
	height: 17px;
	display: block;
	cursor: pointer;
	float: left;
	margin: 0px 0px 0px -5px;
	padding:2px 0px 0px 10px;
	border: none;
	border-radius:0px 6px 0px 4px; -moz-border-radius:0px 6px 0px 4px; -webkit-border-radius:0px 6px 0px 4px;
	text-shadow: none;
	color: #FFF;
	line-height:15px;
	min-width:75px;
}

a.q_mess_link {
	background: #ff7f50;
	width: 100%;
	height: 17px;
	display: inline-block;
	cursor: pointer;
	margin: 0px -5px 0px 0px;
	padding:2px 10px 0px 0px;
	float: right;
	border: none;
	border-radius:6px 0px 4px 0px; -moz-border-radius:6px 0px 4px 0px; -webkit-border-radius:6px 0px 4px 0px;
	text-shadow: none;
	color: #FFF;
	line-height:15px;
	min-width:75px;
	text-align: right;
}
</style>
<?php
if(!DEFINED("quick_mess_file")) DEFINE("quick_mess_file", $_SERVER["DOCUMENT_ROOT"].'/cache/cache_quick_mess.inc');

$sql = mysql_query("SELECT `price` FROM `tb_config` WHERE `item`='count_quick_mess_max' AND `howmany`='1'");
$count_quick_mess_max = number_format(mysql_result($sql,0,0), 0, ".", "");

if(!isset($quick_mess_arr) && is_file(quick_mess_file) ) {
	$quick_mess_arr = @unserialize(file_get_contents(quick_mess_file));

		echo '<div id="qm_block">';
			if($count_quick_mess_max>count($quick_mess_arr)){
				$count=count($quick_mess_arr);
			}else{
				$count=$count_quick_mess_max;
			}
		for($i=0; $i<$count; $i++){
			echo '<div class="'.($quick_mess_arr[$i]["color_s"]==1 ? "q_mess_block_ch" : "q_mess_block_nh").'">';
				echo '<div class="q_mess_desc">'.$quick_mess_arr[$i]["desc_s"].'</div>';
				echo '<div class="q_mess_al">';
					echo '<div style="float:left;"><a class="q_mess_author" href="/wall?uid='.$quick_mess_arr[$i]["user_id_s"].'" target="_blank">'.$quick_mess_arr[$i]["user_name_s"].'</a></div>';
					if($quick_mess_arr[$i]["url_s"]!=false) echo '<div style="float:right;"><a class="q_mess_link" href="'.$quick_mess_arr[$i]["url_s"].'" target="_blank">Перейти</a></div>';
				echo '</div>';
			echo '</div>';
		}
		echo '</div>';
}
echo '<div align="center" style="margin:10px auto 5px;"><span class="proc-btn" onClick="document.location.href=\'/advertise.php\'">Разместить сообщение</span></div>';
?>