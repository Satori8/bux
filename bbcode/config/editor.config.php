<?php

/******************************************************************************
 *                                                                            *
 *   editor.config.php, v 0.01 2007/07/25 - This is part of xBB library       *
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

// ����������� �������� �������� xBBEditor-�

class Xbb {
    /*
    ���� � ���������� xBB
    */
    public $path = '/bbcode/';
    /*
    ������������� ���������
    */
    public $textarea_id = '';
    /*
    ������ ���� ���������
    */
    public $area_width = '700px';
    /*
    ������ ���� ���������
    */
    public $area_height = '600px';
    /*
    ����������� ����� ���������.
    �������������: 'plain' (����� � textarea), 'highlight' (����� � iframe)
    */
    public $state = 'plain';
    /*
    �����������. ��� ������ ���� ������ ������� ���� ��������� � ������
    ����� ���� �������������� � i18n.
    */
    public $lang = 'ru_cp1251';
}