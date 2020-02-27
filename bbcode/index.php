<?php

/******************************************************************************
 *                                                                            *
 *   index.php, v 0.07 2007/07/26 - This is part of xBB library               *
 *   Copyright (C) 2006-2007  Dmitriy Skorobogatov  dima@pc.uz                *
 *                                                                            *
 *   This program is free software; you can redistribute it and/or modify     *
 *   it under the terms of the GNU General Public License as published by     *
 *   the Free Software Foundation; either version 2 of the License, or        *
 *   (at your option) any later version.                                      *
 *                                                                            *
 *   This program is distributed in the hope that it will be useful,          *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of           *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            *
 *   GNU General Public License for more details.                             *
 *                                                                            *
 *   You should have received a copy of the GNU General Public License        *
 *   along with this program; if not, write to the Free Software              *
 *   Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA *
 *                                                                            *
 ******************************************************************************/

error_reporting(E_ALL | E_STRICT);
header('Content-type: text/html; charset=windows-1251');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1251" />
<title>xBBEditor demo</title>
<meta name="author" content="Dmitriy Skorobogatov" />
<link href="./style.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src='./xbb.js.php'></script>

<script type="text/javascript">
XBB.textarea_id = 'test_js'; // ������������� textarea
XBB.area_width = '720px';
XBB.area_height = '400px';
XBB.state = 'plain'; // 'plain' or 'highlight'
XBB.lang = 'ru_cp1251'; // �����������
onload = function() {
    XBB.init(); // �������������� ���������
}
</script>

</head>
<body>



<div align="center">
<form action="preview.php" name="test" target="_blank" method="post">
<textarea style="width:700px;height:400px" name="xbb_textarea" id="test_js">
[h1 align=center]xBB ������ 0.29[/h1]
�������� ������� �� ������ 0.28:
[ol]
[*]���������� ����. ���� �� ����������� "�� ����", - ����������. ������ ���� [nobb]'www.����-��'[/nobb] ����������������� � ���� [nobb]'./www.����-��'[/nobb], - ����������. ������ ���, ��� ��������, ������������� � ���� [nobb]'http://www.����-��'[/nobb].

[*]� ������ ������������ IE (��� '&lt;br /&gt;&lt;br /&gt;' ���������� ���� '&lt;br /&gt;') ��������� �������� �������������� ������. ������ � IE ����� �������� ����� ��������. � ������ ��������� ��� �������� ��� ������.

[*]��������� ����� ����: [bbcode]@l;bdo@r;, @l;big@r;, @l;blockquote@r;, @l;br@r;, @l;cite@r;, @l;del@r;, @l;em@r;, @l;h4@r;, @l;h5@r;, @l;h6@r;, @l;ins@r;, @l;ol@r;, @l;p@r;, @l;pre@r;, @l;small@r;, @l;strong@r;, @l;ul@r;, @l;var@r;[/bbcode].

[*]�������� ����������� � HTML ���� [bbcode]@l;quote@r;[/bbcode]. ��������� [tt]div[/tt] ������� �� ����� ������������ ���������� [tt]blockquote[/tt].

[*]������� ������ �������� ��������.
[/ol]

������� ��������� � [b]xBBEditor[/b]:

[ol]
[*]��� ��������� �������� � ������, ����������� ����������������� ��������� ��� ������� ���������� �������� ���������� �� ���������� Ajax ��� ��������� ����. ��� ����� ������ ��� ��������� �� ���������� JavaScript � ������, ����������� ��� ��������� ����.

[*]�������� ����. ���� ����� � ���������� ���������� ����� ��������, �� ��� �������� ��������� �������� �������������� ����. ������ ����� �� ����������.

[*]����������� ������. ������ ������� ��� ������ ��� ���������/������� �������� ���������� CSS, � �� ����� ���������� ��������. ����� ������� �������� ����� ����������� �������� � ������ �� "����" � ������ ������� ���������� � �����. �������� ��� ���� ������.

[*]����� ����� ������. ��� ������� ��������� ������ ����������� ������. ��� ���������� � ���������, - ����. ��� ����� ��������� ������� ������� ���������.

[*]�������� ����. ���� �������� ��������� � ������ ��������� ����, �� � FF ��� ������� ����� ��������� �������� �����. ������ ����� �� ����������.

[*]������� ������ ���������������� ���������.

[*]������ ��� ���� ���������������� ����, ������� ���������� ������ �������, ������� ������ � �������� ��������, ������������ �� ����� ������������.
[/ol]

��� �� ����� ������ ������������, ��� [b]xBBEditor[/b] ���� ��� �������� ����� ����������������� �����������. ������������ ��� � ������� �������� ������������� � ������� �������������. ������ xBB ����� �� ������� �� xBBEditor-� � ����� ���� ����������� � ���� � ����� ������ ���������� ��� ��� ������ ���� ���������.

������������ �������� � ��������� �����
[right][i][b]������� ������������[/b], 25.07.2007[/i][/right]</textarea>
<br />&nbsp;<br />
<input type="submit" value="Send" />
</form>
</div>

</body>
</html>