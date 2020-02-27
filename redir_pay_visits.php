<?php
session_start();
if(!DEFINED("ROOT_DIR")) DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);

$id = (isset($_GET["id"]) && is_string($_GET["id"]) && preg_match("|^[\d]{1,11}$|", intval(trim($_GET["id"])))) ? intval($_GET["id"]) : false;
$type_ads = ( isset($_GET["type"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", trim($_GET["type"])) ) ? htmlspecialchars(trim($_GET["type"]), ENT_QUOTES, "CP1251", false) : false;

if($id != false && $type_ads == "pay_visits") {
	require(ROOT_DIR."/config_mysqli.php");

	$sql = $mysqli->query("SELECT `id`,`url`,`hide_httpref` FROM `tb_ads_pay_vis` WHERE `id`='$id'") or die($mysqli->error);
	if($sql->num_rows > 0) {
		$row = $sql->fetch_assoc();
		$id = $row["id"];
		$url = $row["url"];
		$hide_httpref = $row["hide_httpref"];

		$_SESSION["id_pv"] = $id;
		$_SESSION["status_pv"] = "success-$id";

		if($hide_httpref == 1) {
			header("referrer: never");
			header("referrer: no-referrer");
			header("Referrer-Policy: no-referrer");
			header("X-Robots-Tag: noindex, nofollow");
			header("Refresh: 0; url=$url");
		}else{
			header("Refresh: 0; url=$url");
		}
	}
	$sql->free();

	$mysqli->close();
}

?>