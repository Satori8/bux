<?php

/******************************************************************************
 *                                                                            *
 *   xbbeditor.config.php, v 0.00 2007/07/25 - This is part of xBB library    *
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

// Умолчальные значения настроек xBBEditor-а

// Список шрифтов, предлагаемых на выбор пользователя
$fonts = array(
    'Arial'          ,
    'Courier'        ,
    'Geneva'         ,
    'Impact'         ,
    'Optima'         ,
    'Times New Roman',
    'Verdana'        ,
    'Tahoma'         ,
    'Symbol'
);
// Палитра цветов, предлагаемых на выбор пользователя
$colors = array(
    array('Black',  'Maroon',  'Green',  'Navy'),
    array('Silver', 'Red',     'Lime',   'Blue'),
    array('Gray',   'Purple',  'Olive',  'Teal'),
    array('White',  'Fuchsia', 'Yellow', 'Aqua')
);
// Основные смайлики, предлагаемые на выбор пользователя
$smiles = array(
	array(
        	array('1.gif' , ':D'     ),
	        array('2.gif' , ':)'     ),
        	array('3.gif' , ':('     ),
	        array('4.gif' , ':heap:' ),
	),
	array(
        	array('5.gif' , ':ooi:'  ),
		array('6.gif' , ':so:'   ),
		array('7.gif' , ':surp:' ),
		array('8.gif' , ':ag:'   ),
	),
	array(
		array('9.gif' , ':ir:'   ),
		array('10.gif', ':oops:' ),
		array('11.gif', ':P'     ),
		array('12.gif', ':cry:'  ),
	),
	array(
		array('13.gif', ':rage:' ),
		array('15.gif', ':roll:' ),
		array('16.gif', ':wink:' ),
		array('17.gif', ':yes:'  ),
	),
	array(
		array('18.gif', ':bot:'  ),
		array('19.gif', ':z)'    ),
		array('20.gif', ':arrow:'),
		array('21.gif', ':vip:'),
	),
	array(
		array('22.gif', ':Heppy:' ),
		array('23.gif', ':think:'  ),
		array('24.gif', ':bye:'  ),
		array('25.gif', ':roul:'  ),
	),
	array(
		array('26.gif', ':pst:'  ),
	),
	//array(
	//	array('27.gif', ':o:'  ),
	//	array('28.gif', ':closed:'  ),
	//	array('29.gif', ':cens:'  ),
	//	array('30.gif', ':tani:'  ),
	//	array('31.gif', ':appl:'  ),
	//),
	//array(
	//	array('32.gif', ':idnk:'  ),
	//	array('33.gif', ':sing:'  ),
	//	array('34.gif', ':shock:'  ),
	//	array('35.gif', ':tgu:'  ),
	//	array('36.gif', ':res:'  ),
	//),
	//array(
	//	array('37.gif', ':alc:'  ),
	//	array('38.gif', ':lam:'  ),
	//	array('39.gif', ':box:'  ),
	//	array('40.gif', ':tom:'  ),
	//	array('41.gif', ':lol:'  ),
	//),
	//array(
	//	array('42.gif', ':vill:'  ),
	//	array('43.gif', ':idea:'  ),
	//	array('44.gif', ':oops:'  ),
	//	array('45.gif', ':E:'  ),
	//	array('46.gif', ':sex:'  ),
	//),
	//array(
	//	array('47.gif', ':horns:'  ),
	//	array('48.gif', ':love:'  ),
	//	array('49.gif', ':poz:'  ),
	//	array('50.gif', ':roza:'  ),
	//	array('51.gif', ':meg:'  ),
	//),
	//array(
	//	array('52.gif', ':dj:'  ),
	//	array('53.gif', ':rul:'  ),
	//	array('54.gif', ':offln:'  ),
	//	array('55.gif', ':sp:'  ),
	//	array('56.gif', ':stapp:'  ),
	//),
	//array(
	//	array('57.gif', ':girl:'  ),
	//	array('58.gif', ':heart:'  ),
	//	array('59.gif', ':kiss:'  ),
	//	array('60.gif', ':spam:'  ),
	//	array('61.gif', ':party:'  ),
	//),
	//array(
	//	array('62.gif', ':ser:'  ),
	//	array('63.gif', ':eam:'  ),
	//	array('64.gif', ':gift:'  ),
	//	array('65.gif', ':adore:'  ),
	//	array('66.gif', ':pie:'  ),
	//),
	//array(
	//	array('67.gif', ':egg:'  ),
	//	array('68.gif', ':cnrt:'  ),
	//	array('69.gif', ':oftop:'  ),
	//	array('70.gif', ':foo:'  ),
	//	array('71.gif', ':mob:'  ),
	//),
	//array(
	//	array('72.gif', ':hoo:'  ),
	//	array('73.gif', ':tog:'  ),
	//	array('74.gif', ':pnk:'  ),
	//	array('75.gif', ':pati:'  ),
	//	array('76.gif', ':-({|=:'  ),
	//),
	//array(
	//	array('77.gif', ':haaw:'  ),
	//	array('78.gif', ':angel:'  ),
	//	array('79.gif', ':kil:'  ),
	//	array('80.gif', ':died:'  ),
	//	array('81.gif', ':cof:'  ),
	//),
	//array(
	//	array('82.gif', ':fruit:'  ),
	//	array('83.gif', ':tease:'  ),
	//	array('84.gif', ':evil:'  ),
	//	array('85.gif', ':exc:'  ),
	//	array('86.gif', ':niah:'  ),
	//),
	//array(
	//	array('87.gif', ':Head:'  ),
	//	array('88.gif', ':gl:'  ),
	//	array('90.gif', ':granat:'  ),
	//	array('91.gif', ':gans:'  ),
	//	array('92.gif', ':ny:'  ),
	//),
	//array(
	//	array('93.gif', ':mvol:'  ),
	//	array('94.gif', ':boat:'  ),
	//	array('95.gif', ':phone:'  ),
	//	array('96.gif', ':cop:'  ),
	//	array('97.gif', ':smok:'  ),
	//),
	//array(
	//	array('98.gif', ':bic:'  ),
	//	array('99.gif', ':ban:'  ),
	//	array('100.gif', ':bar:'  ),
	//),
);
