<?php
if (!DEFINED("ADMINKA")) {die ("Hacking attempt!");}

//ini_set('display_errors', 1);
//error_reporting(E_ALL);

define('DEF_PATH', $_SERVER['DOCUMENT_ROOT']);

if (isset($_POST['auth']))
{
  $sql = "SELECT * FROM tb_site WHERE id='1'";
  $result = mysql_query($sql);
  $row = mysql_fetch_array($result);

  $client_id = $row['client_id'];

  $redirect_url = $row["redirect_url"];

  if ($client_id == '' || $redirect_url == '')
  {
    echo '������� �� ��� ���������';
  }
  else
  {

    require DEF_PATH.'/yandex/yandex.php';

    $ym = new ymAPI($client_id, $redirect_url);

    $sc = array('account-info' => ' account-info',
            'operation-history' => ' operation-history',
            'operation-details' => ' operation-details',
            'payment-p2p' => ' payment-p2p',
            'money-source(&quot;wallet&quot;,&quot;card&quot;)' => 'money-source(&quot;wallet&quot;,&quot;card&quot;)'

      );

    $scope = $ym->checkScope($sc);

    $url = $ym->authorizeUri($scope);

    //$_SESSION['yandex_auth'] = 1;

    ?>
    <div style="padding: 30px;" align="center">������ �� ������ �������������� �� ���� ������.������ ��� ����������� ������ ����������.</div>
    <script>

     setTimeout( 'location="<?php echo $url; ?>";', 5000 );

    </script>
    <?

    exit();
  }
}

if (isset($_POST["yandex"]))
{
  $yandex = $_POST["yandex"];

  $client_id = $_POST["client_id"];

  $redirect_url = $_POST["redirect_url"];

  mysql_query("update tb_site set yandex='$yandex', client_id='$client_id', redirect_url='$redirect_url' where id='1'");
}


$sql = "SELECT * FROM tb_site WHERE id='1'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
?>

<form method="post" action="" id='MainForm'>
 <table class="adn">
  <tr class="lineb">
   <td align="left">��������</td>
   <td align="left"><table class="adn"><tr><td align="left" style="border: none; padding: 0">��������</td><td align="right" style="border: none; padding: 0"><a href="#" onclick="document.getElementById('MainForm').submit(); return false" class="liv">���������</a></td></tr></table></td>
  </tr>
  <tr class="liney">
   <td class="settab listsr"><input type=text value='<? echo $row["yandex"]; ?>' name='yandex'></td>
   <td class="listsl"><b>������.������</b><br>���� � ������.������</td>
  </tr>
  <tr  class="liney ell">
   <td class="settab listsr"><input type=text value='<? echo $row["client_id"]; ?>' name='client_id' ></td>
   <td class="listsl"><b>�������������</b><br>������������� �������� � ��</td>
  </tr>
  <tr class="liney">
   <td class="settab listsr"><input type=text value='<? echo $row["redirect_url"]; ?>' name='redirect_url'></td>
   <td class="listsl"><b>Redirect_url</b><br>����� ��������� � �������� ��</td>
  </tr>
 </table>
 <br />
 <br />
</form>
<br />
<br />
<?php if ($row['token'] == '') : ?>
<form action="" method="post">
 <table align="center">
  <thead>
   <tr>
    <td>
     <span style="color: #FF0000; font-weight: bold;">��������!</span> ��� ������ � API ������.����� ����� ������������ ������������ � �������.
    </td>
   </tr>
  </thead>
  <tfoot>
   <tr>
    <td><input type="hidden" name="auth" value="1" /><input type="submit" value="������������ � ������� ������.������" /></td>
   </tr>
  </tfoot>
 </table>
</form>
<?php endif; ?>
