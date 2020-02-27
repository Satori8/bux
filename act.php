<? 
echo "<script>parent.document.getElementById('_out').innerHTML = '";

include_once "smsc_api.php";

if ($_POST["sendsms"]) { 
    $r = send_sms($_POST["phone"], ok_code($_POST["phone"])); 

    if ($r[1] > 0) 
        echo "Код подтверждения отправлен на номер ".$_POST["phone"]; 
}

if ($_POST["ok"]) {
    $oc = ok_code($_POST["phone"]);

    if ($oc == $_POST["code"])
        echo "Номер активирован";
    else
        echo "Неверный код подтверждения";
}

echo "'</script>";

function ok_code($s) {
    return hexdec(substr(md5($s."<секретная строка>"), 7, 5));
}
?>
<form method="post" action="act.php" target="ifr">

<table>
<tr><td>Номер телефона<td><input name="phone">
<tr><td><br/>

<tr><td>Код подтверждения<td><input name="code" size="6">&nbsp;
<input type="submit" name="sendsms" value="Выслать код">
<tr><td><br/>

<tr><td><input type="submit" name="ok" value="Подтвердить"><td colspan="2" id="_out">
</table>

</form>
<iframe name="ifr" frameborder="0" height="0" width="0" style="visibility:hidden"></iframe>