<?php
session_start();
error_reporting (E_ALL);
header("Content-type: text/html; charset=windows-1251");
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
require(ROOT_DIR."/config.php");
require(ROOT_DIR."/funciones.php");
$user_name = (isset($_SESSION["userLog"]) && preg_match("|^[a-zA-Z0-9\-_-]{3,25}$|", trim($_SESSION["userLog"]))) ? htmlspecialchars(trim($_SESSION["userLog"])) : false;
?>

<h3 class="sp" style="margin-top:1px;">График дохода</h3>
<div align="left"><div id="placeholder" style="width:770px; height:300px;"></div></div>

<script language="javascript" type="text/javascript">
	var all_data = [
		{color: "rgb(56, 56, 255)", data: [
			<?php
			$time_s = strtotime(DATE("d.m.Y", (time()-(13*24*60*60))));
			$time_e = strtotime(DATE("d.m.Y", (time()+(0*24*60*60))));
			$period = (1*24*60*60);

			for($i=$time_s; $i<=$time_e; $i=$i+$period) {
				$sql = mysql_query("SELECT `money` FROM `tb_invest_stat` WHERE `username`='$user_name' AND `date`='".DATE("d.m.Y", ($i+3*60*60))."' ORDER BY `id` ASC LIMIT 14") or die(mysql_error());
				$row = mysql_fetch_array($sql);
				echo '["'.($i+3*60*60).'000", '.round($row["money"],6).'], ';
			}
			?>
		]}
	];

	var plot_conf = {
		series: {lines: {show: true, lineWidth: 2}},
		xaxis: {mode: "time", timeformat: "%d.%b", monthNames: ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"]}
	};

	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 15,
			left: x + 15,
			border: '1px solid #000',
			padding: '2px',
			'background-color': 'gold',
			opacity: 0.80
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;

	$("#placeholder").bind("plothover", function (event, pos, item) {
		$("#x").text(pos.x.toFixed(0));
		$("#y").text(pos.y.toFixed(4));

		if (item) {
			if (previousPoint != item.dataIndex) {
				previousPoint = item.dataIndex;

				$("#tooltip").remove();
				var x = item.datapoint[0].toFixed(0),
				    y = item.datapoint[1].toFixed(4);
				var d = new Date([x]);
				var dt = d.toLocaleDateString();

				showTooltip(item.pageX, item.pageY, "доход " + y + " руб.");
			}
		}else{
			$("#tooltip").remove();
			previousPoint = null;            
		}
	});

	$.plot($("#placeholder"), all_data, plot_conf);
</script>