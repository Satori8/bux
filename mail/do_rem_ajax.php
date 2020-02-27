<?
ini_set('display_errors',0);
if (isset($_POST['postid'])) {
$postid = $_POST['postid'];
require_once("../config.php");
mysql_query("delete from tb_mail_in where id='$postid'");
mysql_query("delete from tb_mail_out WHERE ident='$postid'");
}
?>