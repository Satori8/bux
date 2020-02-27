<?php
$pagetitle = "Кабинет управления инвестициями";
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
include(ROOT_DIR."/header.php");
//$sql_inv=mysql_fetch_array(mysql_query("SELECT * FROM `tb_users` WHERE `username`='".$username."'"));

if(!isset($_SESSION["userLog"]) && !isset($_SESSION["userPas"])) {
	echo '<span class="msg-error">Для доступа к этой странице необходимо авторизоваться!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
}

/*if($sql_inv["investor"]=='0'){
	echo '<span class="msg-error">Доступ закрыт!</span>';
	include(ROOT_DIR."/footer.php");
	exit();
}*/

$d_page = "Info";
$l_page = (isset($_GET["lp"]) && limpiar($_GET["lp"])!=false) ? limpiar($_GET["lp"]) : $d_page;
?>

<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="flot/js/excanvas.min.js"></script><![endif]-->
<script language="javascript" type="text/javascript" src="flot/js/jquery.flot.js"></script>

<script type="text/javascript" language="JavaScript">
	var tm, NowPage, RPage;
	var DefaultPage = "<?php echo $l_page;?>";

	function HideMsg(id, timer) {
	        clearTimeout(tm);
		tm = setTimeout(function() {$("#"+id).slideToggle(700);}, timer);
		return false;
	}

	function getUrlVars() {
		var vars = {};
		var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
			vars[key] = value;
		});
		return vars ? vars : DefaultPage;
	}

	function LoadPageInvest(page) {
		var page = page ? page : DefaultPage;
		$("#"+page).parent().children().attr("class", "invest_sub");
		$("#"+page).attr("class", "invest_sub_act");
		//$("#PageInv").html("").hide();
		$("#info-msg-invest").html("").hide();

		if(NowPage != page) {
			$("#PageInv").html("").hide();

			$.ajax({
				type: "POST", url: "ajax/ajax_invest.php", 
				data: {'op':'LoadPage', 'page':page}, 
				dataType: 'json',
				error: function(request, status, errortext) {
					$("#loading").slideToggle();
					var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
					$("#info-msg-invest").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
					//console.log(request, status, errortext);
				},
				beforeSend: function() { $("#loading").slideToggle(); }, 
				success: function(data) {
					$("#loading").slideToggle();

					NowPage = page;
					var result = data.result ? data.result : data;
					var message = data.message ? data.message : data;

					if(!RPage) { RPage = 0; window.history.pushState(null, null, "<?php echo (isset($_SERVER["REDIRECT_URL"]) ? $_SERVER["REDIRECT_URL"] : $_SERVER["PHP_SELF"])."?lp=";?>"+page); }

					if (result == "OK") {
						$("#PageInv").html(message).show();
						if(page=="Stat") GrafInvest();
						return false;
					} else {
						$("#PageInv").html('<span class="msg-error">'+message+'</span>').show();
						return false;
					}
				}
			});
		}
	}

	function GrafInvest() {
		$.ajax({
			type: "GET", url: "ajax/ajax_invest_grafik.php?rnd="+Math.random(), 
			data: {'op':'graf'}, 
			error: function() {$("#loading").slideToggle(); alert("Ошибка! Обновите страницу."); return false;}, 
			success: function(data) {
				$("#PageInv").append(data).show();
			}
		});
	}

	function BuyShares() {
		var count_buy = $.trim($("#count_buy").val());
		$("#info-msg-buy").html("").hide();
		$("#info-msg-invest").html("").hide();

		$.ajax({
			type: "POST", url: "ajax/ajax_invest.php", 
			data: {'op':'BuyShares', 'count_buy':count_buy}, 
			dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#info-msg-buy").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
				//console.log(request, status, errortext);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();

				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					var ObjMsg = jQuery.parseJSON(message);
					var SysCntShares = ObjMsg.SysCntShares;
					var InvCntShares = ObjMsg.InvCntShares;
					var MyCntShares = ObjMsg.MyCntShares;
					var MsgTxt = ObjMsg.MsgTxt;

					$("#SysCntShares").html(SysCntShares); $("#InvCntShares").html(InvCntShares); $("#MyCntShares").html(MyCntShares);
					LoadHistory("Buy");

					$("#BuyForm").slideToggle("fast");
					$("#info-msg-buy").html('<span class="msg-ok">'+MsgTxt+'</span>').slideToggle("fast");
					HideMsg("info-msg-buy", 4000);

					setTimeout(function(){
						NowPage = false; RPage = false; $("#count_buy").val("100");
						$("#BuyForm").slideToggle("fast");
					}, 4000);
					return false;
				} else {
					$("#info-msg-buy").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
					HideMsg("info-msg-buy", 5000);
					return false;
				}
			}
		});
	}

	function SellShares() {
		var count_sell = $.trim($("#count_sell").val());
		var cena_one_sell = $.trim($("#cena_one_sell").val());
		$("#info-msg-sell").html("").hide();
		$("#info-msg-invest").html("").hide();

		$.ajax({
			type: "POST", url: "ajax/ajax_invest.php", 
			data: {'op':'SellShares', 'count_sell':count_sell, 'cena_one_sell':cena_one_sell}, 
			dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#info-msg-sell").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
				//console.log(request, status, errortext);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();

				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#SellForm").slideToggle("fast");
					$("#info-msg-sell").html('<span class="msg-ok">'+message+'</span>').slideToggle("fast");
					HideMsg("info-msg-sell", 4000);

					setTimeout(function(){
						NowPage = false; RPage = false; $("#count_sell").val("0"); $("#cena_one_sell").val("0.00");
						$("#SellForm").slideToggle("fast");
					}, 4000);
					return false;
				} else {
					$("#info-msg-sell").html('<span class="msg-error">'+message+'</span>').slideToggle("fast");
					HideMsg("info-msg-sell", 5000);
					return false;
				}
			}
		});
	}

	function DelBirj(id) {
		$.ajax({
			type: "POST", url: "ajax/ajax_invest.php", 
			data: {'op':'DelBirj', 'id':id}, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;
				if (result == "OK") {
					$("#trbirj"+id).remove();
					setTimeout(function(){alert(message);}, 100);
					return false;
				}else{
					alert(message);
					return false;
				}
			}
		});
	}

	function BuyBirj(id) {
		$.ajax({
			type: "POST", url: "ajax/ajax_invest.php", 
			data: {'op':'BuyBirj', 'id':id}, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;
				if (result == "OK") {
					$("#trbirj"+id).remove();
					setTimeout(function(){alert(message);}, 100);
					return false;
				}else{
					alert(message);
					return false;
				}
			}
		});
	}

	function LoadHistory(type) {
		$.ajax({
			type: "POST", url: "ajax/ajax_invest.php", 
			data: {'op':'LoadHistory', 'type':type}, 
			dataType: 'json',
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;
				if (result == "OK") {
					$("#History"+type).html(message).show();
					return false;
				}else{
					$("#History"+type).html(message).show();
					return false;
				}
			}
		});
	}
	
	function AddBalance(op, id) {
		var id = id ? id : false;
		if(op != "InBalance") $("#info-msg-bal").html("").hide();
		$("#info-msg-pay").html("").hide();
		$("#info-msg-invest").html("").hide();

		$.ajax({
			type: "POST", url: "ajax/ajax_invest.php", 
			data: {'op':op, 'id':id, 'money_add':$.trim($("#money_add").val()), 'method_pay':$.trim($("#method_pay").val())}, 
			dataType: 'json',
			error: function(request, status, errortext) {
				$("#loading").slideToggle();
				var error = new Array(); error["rState"] = request.readyState!==false ? request.readyState : false; error["rText"]  = request.responseText!=false ? request.responseText : errortext; error["status"] = request.status!==false ? request.status : false; error["statusText"] = request.statusText!==false ? request.statusText : false;
				$("#info-msg-bal").show().html('<span class="msg-error">ОШИБКА AJAX! readyState:'+error["rState"]+'; responseText:'+error["rText"]+'; status:'+error["status"]+'['+error["statusText"]+']<br>STATUS: '+status+'<br>ERROR:'+errortext+'</span>');
				//console.log(request, status, errortext);
			},
			beforeSend: function() { $("#loading").slideToggle(); }, 
			success: function(data) {
				$("#loading").slideToggle();
				var result = data.result ? data.result : data;
				var message = data.message ? data.message : data;

				if (result == "OK") {
					$("#FormAddBalance").hide();
					$("#info-msg-bal").html(message).show();
					if(op == "InBalance" && data.cnt_text) {
						var cnt_obj = data.cnt_text ? jQuery.parseJSON(data.cnt_text) : data;
						$("#balance_inv").html(cnt_obj.cnt_ib);
						$("#my_bal_os").html(cnt_obj.cnt_ob);
						setTimeout(function(){
							$("#money_add").val("");
							$("#FormAddBalance").show();
							$("#info-msg-bal").html("").hide();
						}, 5000);
					}
				}else{
					if(op == "InBalance") {
						$("#FormAddBalance").hide();
						$("#info-msg-pay").html(message).show();
						HideMsg("info-msg-pay", 5000);
					}else{
						$("#info-msg-bal").html(message).show();
						HideMsg("info-msg-bal", 5000);
					}
				}
				return false;
			}
		});
	}

	function ChangeBalance() {
		$("#info-msg-bal").html("").hide();
		$("#FormAddBalance").show();
		return false;
	}

	function str_replace(search, replace, subject) {
		return subject.split(search).join(replace);
	}

	function MoneyValid() {
		var money_add = $.trim($("#money_add").val());
		money_add = str_replace(",", ".", money_add);
		money_add = money_add.match(/(\d+(\.)?(\d){0,2})?/);
		money_add = money_add[0] ? money_add[0] : '';
		$("#money_add").val(money_add);
		return false;
	}

	$(document).ready(function() { LoadPageInvest(DefaultPage); })
	window.addEventListener("popstate", function(){ RPage=1; LoadPageInvest(getUrlVars()["lp"]); }, false);

</script>




<?php

echo '<div id="MenuInv" style="display:block; height:45px;">';
	echo '<span id="Info" class="invest_sub'.($l_page=="Info" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Информация</span>';
	echo '<span id="Stat" class="invest_sub'.($l_page=="Stat" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Статистика</span>';
	echo '<span id="Top10" class="invest_sub'.($l_page=="Top10" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">ТОП10</span>';
	echo '<span id="News" class="invest_sub'.($l_page == "News" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Новости</span>';
	echo '<span id="Balance" class="invest_sub'.($l_page == "Balance" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Баланс</span>';
	echo '<span id="Buy"  class="invest_sub'.($l_page=="Buy" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Купить акции</span>';
	echo '<span id="Sell" class="invest_sub'.($l_page=="Sell" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Продать акции</span>';
	echo '<span id="Birj" class="invest_sub'.($l_page=="Birj" ? "_act" : false).'" onClick="LoadPageInvest($(this).attr(\'id\')); return false;">Биржа</span>';
echo '</div>';

echo '<div id="PageInv" style="display:none;"></div>';
echo '<div id="info-msg-invest" style="display:none;"></div>';

include(ROOT_DIR."/footer.php");
?>