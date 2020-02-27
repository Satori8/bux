<?php
if(!DEFINED("ROOT_DIR")) 	DEFINE("ROOT_DIR", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("REFRESH_TIME")) 	DEFINE("REFRESH_TIME", 1*60*60);
if(!DEFINED("tcy_pr_file")) 	DEFINE("tcy_pr_file", ROOT_DIR."/cache/tcy_pr.inc");

if(!isset($tcy_pr_arr) && is_file(tcy_pr_file) && filemtime(tcy_pr_file) > (time()-REFRESH_TIME)){
	$tcy_pr_arr = @unserialize(file_get_contents(tcy_pr_file));
}else{
	include_once(ROOT_DIR."/tcy_pr/tcy_pr_class.php");
	$TCY_PR = new TCY_PR();
	$site = "https://".$_SERVER["HTTP_HOST"]."/";

	$pr = $TCY_PR->check_pr($site);
	$tcy = $TCY_PR->check_tcy($site);

	$pr = $pr!=false ? intval(trim($pr)) : "0";
	$tcy = $tcy!=false ? intval(trim($tcy)) : "0";

	$tcy_pr_arr = array("PR" => $pr, "TCY" => $tcy);

	if(!isset($tcy_pr_arr)) $tcy_pr_arr = array();

	if(is_file(tcy_pr_file)) file_put_contents(tcy_pr_file, serialize($tcy_pr_arr));
}

$_PR  = ( isset($tcy_pr_arr) && is_array($tcy_pr_arr) && isset($tcy_pr_arr["PR"]) )  ? intval(trim($tcy_pr_arr["PR"]))  : "0";
$_TCY = ( isset($tcy_pr_arr) && is_array($tcy_pr_arr) && isset($tcy_pr_arr["TCY"]) ) ? intval(trim($tcy_pr_arr["TCY"])) : "0";

?>
