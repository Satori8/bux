<?
ini_set('display_errors',0);
if (isset($_POST['postid'])) {
$postid = $_POST['postid'];
require_once("../config.php");
mysql_query("update tb_mail_in set nst='1' where id='$postid'");
mysql_query("UPDATE tb_mail_out SET nst='1' WHERE ident='$postid'");
}
?>