<?php
$ads = ( isset($_GET["ads"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", htmlspecialchars(trim($_GET["ads"]))) ) ? htmlspecialchars(trim($_GET["ads"])) : false;
$cab_link = ( isset($_COOKIE["cab_link"]) && preg_match("|^[a-zA-Z0-9\-_]{1,20}$|", htmlspecialchars(trim($_COOKIE["cab_link"]))) && $ads==false) ? htmlspecialchars(trim($_COOKIE["cab_link"])) : $ads;
if($cab_link!=false && $ads!=$cab_link) {
	echo '<script type="text/javascript">location.replace("/cabinet_ads?ads='.$cab_link.'");</script>';
	exit(); 
}
$pagetitle = "Управление рекламой";
if(!DEFINED("DOC_ROOT")) DEFINE("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
if(!DEFINED("CABINET")) DEFINE("CABINET", true);
require_once(DOC_ROOT."/.zsecurity.php");
include(DOC_ROOT."/header.php");
require(DOC_ROOT."/cabinet/cab_func.php");
require(DOC_ROOT."/config.php");

//echo '<script type="text/javascript" src="../scripts/jqpooop.js"></script>';

include(DOC_ROOT."/cabinet/cab_index.php");

include(DOC_ROOT."/footer.php");
?>