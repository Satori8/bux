<?php
require_once 'config.php';
require_once 'funciones.php';

require_once 'phar://api/yandex/yandex-php-library_0.4.1.phar.bz2/vendor/autoload.php';
use Yandex\SafeBrowsing\SafeBrowsingClient;
$safeBrowsing = new SafeBrowsingClient("a6197d965bcc4a1c9c1b409190c82342");

$sql = mysql_query("SELECT COUNT(*),`id`,`url` FROM `tb_ads_dlink` WHERE `url` NOT LIKE '%".$_SERVER["HTTP_HOST"]."%' GROUP BY `url` HAVING COUNT(`url`)>'0'  ORDER BY `id` ASC");
$count_uniq = mysql_num_rows($sql);
$arr_url = array();

if($count_uniq>0) {
	while ($row = mysql_fetch_assoc($sql)){
		$id = $row["id"];
		$url = $row["url"];
		$arr_url[$id] = getHost($url);
	}
}

$arr_url = array_unique($arr_url);

echo "Уникальных доменов: <b>".count($arr_url)."</b>";
echo '<table border="1" cellspacing="0" cellpadding="4">';
echo '<thead bgcolor="#CCC">';
echo '<tr>';
	echo '<th>ID</th>';
	echo '<th>URL</th>';
	echo '<th>Статус в яндекс</th>';
echo '</tr>';
echo '</thead>';
foreach($arr_url as $key => $val) {
	if($safeBrowsing->searchUrl($val)) {
		echo '<tr>';
			echo '<td>'.$key.'</td>';
			echo '<td>'.$val.'</td>';
			echo '<td><b style="color:red;">Опасен</b></td>';
		echo '</tr>';
	}
}
echo '</table>';

?>