<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=cp1251">
	<meta http-equiv="Pragma" content="no-cache">
	<meta http-equiv="Expires" content="-1">
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="icon" type="favicon.png" />
	<title><?php echo strtoupper($_SERVER["HTTP_HOST"]);?> | Автоматический серфинг</title>
</head>

<frameset rows="74,*" border="0">
	<frame src="<?php echo "//".$_SERVER["HTTP_HOST"]."/autoserf/";?>auto_s.php" name="head" marginwidth="0" marginheight="0" scrolling="no" noresize>
	<frame name="site" marginwidth="0" marginheight="0" noresize>
</frameset>

</html>