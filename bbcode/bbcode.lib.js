/******************************************************************************
 *                                                                            *
 *   bbcode.lib.js, v 0.00 2007/07/25 - This is part of xBB library           *
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

function bbcode(code) {
    /* ����� BBCode */
    this.text = code;
    /* ��������� ��������������� ������� ������ BBCode. */
    this.syntax = [];
    /* ������ �������������� �����. */
    this.tags = [];
    /* ������, ����������/����������� �������������� ������. */
    this.autolinks = true;
    /* ������ ����� ��� �������������� ������. */
    this.preg_autolinks = {
        pattern   : [
            /(\w+:\/\/[A-z0-9\.\?\+\-\/_=&%#:;]+[\w/=]+)/,
            /([^/])(www\.[A-z0-9\.\?\+\-/_=&%#:;]+[\w/=]+)/,
            /([\w]+[\w\-\.]+@[\w\-\.]+\.[\w]+)/,
        ],
        highlight : [
            '<' + 'span class="bb_autolink">$1<' + '/span>',
            '$1<' + 'span class="bb_autolink">$2<' + '/span>',
            '<' + 'span class="bb_autolink">$1<' + '/span>',
        ]
    };
    /* �������������� �������� � ������ ���������. */
    this.mnemonics = [];
    /* �������� ��������, ������������ �� ����� ������������. */
    this.smiles = [];
    /* ������ �������, ������������ �� ����� ������������ */
    this.fonts = [];
    /* ������� ������, ������������ �� ����� ������������ */
    this.colors = [];
    /* Id ������� � ������������ bbcode */
    this.iframeId = 'xbb_iframe';
    /* Id textarea � ������� bbcode */
    this.textareaId = 'xbb_textarea';
    /*
    div, ������������ ��� ��������� ������,
    ������������ �� ������ ����� � ������
    */
    this.transportDiv = parent.document.getElementById('xbb_transport_div');
    /*
    �����, � ������� � ������ ������ ��������� ��������. ��������� ��������:
    'plain' (textarea) ��� 'highlight' (��������� ����������)
    */
    this.state = 'plain';
    /* ��� ���� �������. - ������� ���������� ��������������� �������. */
    var _cursor = 0;
    /*
    get_token() - ������� ������ ����� BBCode � ���������� ��������� ����

                        "����� (��� �������) - �������"

    ������� - ��������� ������ this.text, ������������ � ������� _cursor
    ���� ������ ����� ���� ���������:

    0 - ���������� ���������� ������ ("[")
    1 - ����������� ���������� c����� ("]")
    2 - ������� ������� ('"')
    3 - �������� ("'")
    4 - ��������� ("=")
    5 - ������ ���� ("/")
    6 - ������������������ ���������� ��������
        (" ", "\t", "\n", "\r", "\0" ��� "\x0B")
    7 - ������������������ ������ ��������, �� ���������� ������ ����
    8 - ��� ����
    */
    this.get_token = function() {
        var token = '';
        var token_type = NaN;
        var char_type = NaN;
        var cur_char;
        while (true) {
            token_type = char_type;
            if (! this.text.charAt(_cursor)) {
                if (isNaN(char_type)) {
                    return false;
                } else {
                    break;
                }
            }
            cur_char = this.text.charAt(_cursor);
            switch (cur_char) {
                case '[':
                    char_type = 0;
                    break;
                case ']':
                    char_type = 1;
                    break;
                case '"':
                    char_type = 2;
                    break;
                case "'":
                    char_type = 3;
                    break;
                case "=":
                    char_type = 4;
                    break;
                case '/':
                    char_type = 5;
                    break;
                case ' ':
                    char_type = 6;
                    break;
                case "\t":
                    char_type = 6;
                    break;
                case "\n":
                    char_type = 6;
                    break;
                case "\r":
                    char_type = 6;
                    break;
                case "\0":
                    char_type = 6;
                    break;
                case "\x0B":
                    char_type = 6;
                    break;
                default:
                    char_type = 7;
            }
            if (isNaN(token_type)) {
                token = cur_char;
            } else if (5 >= token_type) {
                break;
            } else if (char_type == token_type) {
                token += cur_char;
            } else {
                break;
            }
            _cursor += 1;
        }
        if (this.in_array(token.toLowerCase(), this.tags)) {
            token_type = 8;
        }
        return [token_type, token];
    }

    this.parse = function(code) {
        if (code) { this.text = code; }
        /*
        ���������� ����� �������� ���������
        ������ ��������� ��������� ��������:
        0  - ������ ������������ ��� ��������� ��� ����. ������� ��� ������.
        1  - ��������� ������ "[", ������� ������� ������� ����. ������� ���
             ����, ��� ������ "/".
        2  - ����� � ���� ������������� ������ "[". ������� ���������� ������
             �������. ������� ��� ����, ��� ������ "/".
        3  - ����� � ���� �������������� ������. ������� ������ �� �������� "[".
             ������� ��� ������.
        4  - ����� ����� "[" ����� ������ "/". ������������, ��� ������ �
             ����������� ���. ������� ��� ���� ��� ������ "]".
        5  - ����� ����� "[" ����� ��� ����. �������, ��� ��������� �
             ����������� ����. ������� ������ ��� "=" ��� "/" ��� "]".
        6  - ����� ���������� ���� "]". ������� ��� ������.
        7  - ����� ����� "[/" ����� ��� ����. ������� "]".
        8  - � ����������� ���� ����� "=". ������� ������ ��� �������� ��������.
        9  - � ����������� ���� ����� "/", ���������� �������� ����. �������
             "]".
        10 - � ����������� ���� ����� ������ ����� ����� ���� ��� �����
             ��������. ������� "=" ��� ��� ������� �������� ��� "/" ��� "]".
        11 - ����� '"' ���������� �������� ��������, ������������ ���������.
             ������� ��� ������.
        12 - ����� "'" ���������� �������� ��������, ������������ �����������.
             ������� ��� ������.
        13 - ����� ������ ��������������� �������� ��������. ������� ��� ������.
        14 - � ����������� ���� ����� "=" ����� ������. ������� ��������
             ��������.
        15 - ����� ��� ��������. ������� ������ ��� "=" ��� "/" ��� "]".
        16 - ��������� ������ �������� ��������, ������������� ���������.
             ������� ��� ������.
        17 - ���������� �������� ��������. ������� ������ ��� ��� ����������
             �������� ��� "/" ��� "]".
        18 - ��������� ������ �������� ��������, ������������� �����������.
             ������� ��� ������.
        19 - ��������� ������ ��������������� �������� ��������. ������� ���
             ������.
        20 - ����� ������ ����� �������� ��������. ������� ��� ����������
             �������� ��� "/" ��� "]".

        �������� ��������� ��������:
        */
        var finite_automaton = {
         // ���������� |   ��������� ��� ������� ������� (������)   |
         //  ��������� |  0 |  1 |  2 |  3 |  4 |  5 |  6 |  7 |  8 |
                   0 : [  1 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ]
                ,  1 : [  2 ,  3 ,  3 ,  3 ,  3 ,  4 ,  3 ,  3 ,  5 ]
                ,  2 : [  2 ,  3 ,  3 ,  3 ,  3 ,  4 ,  3 ,  3 ,  5 ]
                ,  3 : [  1 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ]
                ,  4 : [  2 ,  6 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ,  7 ]
                ,  5 : [  2 ,  6 ,  3 ,  3 ,  8 ,  9 , 10 ,  3 ,  3 ]
                ,  6 : [  1 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ,  0 ]
                ,  7 : [  2 ,  6 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ]
                ,  8 : [ 13 , 13 , 11 , 12 , 13 , 13 , 14 , 13 , 13 ]
                ,  9 : [  2 ,  6 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ,  3 ]
                , 10 : [  2 ,  6 ,  3 ,  3 ,  8 ,  9 ,  3 , 15 , 15 ]
                , 11 : [ 16 , 16 , 17 , 16 , 16 , 16 , 16 , 16 , 16 ]
                , 12 : [ 18 , 18 , 18 , 17 , 18 , 18 , 18 , 18 , 18 ]
                , 13 : [ 19 ,  6 , 19 , 19 , 19 , 19 , 17 , 19 , 19 ]
                , 14 : [  2 ,  3 , 11 , 12 , 13 , 13 ,  3 , 13 , 13 ]
                , 15 : [  2 ,  6 ,  3 ,  3 ,  8 ,  9 , 10 ,  3 ,  3 ]
                , 16 : [ 16 , 16 , 17 , 16 , 16 , 16 , 16 , 16 , 16 ]
                , 17 : [  2 ,  6 ,  3 ,  3 ,  3 ,  9 , 20 , 15 , 15 ]
                , 18 : [ 18 , 18 , 18 , 17 , 18 , 18 , 18 , 18 , 18 ]
                , 19 : [ 19 ,  6 , 19 , 19 , 19 , 19 , 20 , 19 , 19 ]
                , 20 : [  2 ,  6 ,  3 ,  3 ,  3 ,  9 ,  3 , 15 , 15 ]
            };
        // ��������� �������� ��������� ��������
        var mode = 0;
        this.syntax = [];
        var decomposition = {};
        var token_key = -1;
        var value = '';
        var previous_mode;
        var type;
        var name;
        _cursor = 0;
        var token = this.get_token();
        // ��������� ������ ������ � ������� ������������ ��������:
        while (token) {
            previous_mode = mode;
            mode = finite_automaton[previous_mode][token[0]];
            if (-1 < token_key) {
                type = this.syntax[token_key].type;
            } else {
                type = false;
            }
            switch (mode) {
                case 0:
                    if ('text' == type) {
                        this.syntax[token_key].str += token[1];
                    } else {
                        this.syntax[++token_key] = {
                            type : 'text',
                            str  : token[1]
                        };
                    }
                    break;
                case 1:
                    decomposition = {
                        name   : '',
                        type   : '',
                        str    : '[',
                        layout : [[0, '[']]
                    };
                    break;
                case 2:
                    if ('text' == type) {
                        this.syntax[token_key].str += decomposition.str;
                    } else {
                        this.syntax[++token_key] = {
                            type : 'text',
                            str  : decomposition.str
                        };
                    }
                    decomposition = {
                        name   : '',
                        type   : '',
                        str    : '[',
                        layout : [[0, '[']]
                    };
                    break;
                case 3:
                    if ('text' == type) {
                        this.syntax[token_key].str += decomposition.str;
                        this.syntax[token_key].str += token[1];
                    } else {
                        this.syntax[++token_key] = {
                            type : 'text',
                            str  : decomposition.str + token[1]
                        };
                    }
                    decomposition = {};
                    break;
                case 4:
                    decomposition.type = 'close';
                    decomposition.str += '/';
                    decomposition.layout[decomposition.layout.length] = [1, '/'];
                    break;
                case 5:
                    decomposition.type = 'open';
                    name = token[1].toLowerCase();
                    decomposition.name = name;
                    decomposition.str += token[1];
                    decomposition.layout[decomposition.layout.length] = [2, token[1]];
                    if (! decomposition.attrib) {
                        decomposition.attrib = {};
                    }
                    decomposition.attrib[name] = '';
                    break;
                case 6:
                    if (! decomposition.name) {
                        decomposition.name = '';
                    }
                    if (13 == previous_mode || 19 == previous_mode) {
                        decomposition.layout[decomposition.layout.length] = [7, value];
                    }
                    decomposition.str += ']';
                    decomposition.layout[decomposition.layout.length] = [0, ']'];
                    this.syntax[++token_key] = decomposition;
                    decomposition = {};
                    break;
                case 7:
                    decomposition.name = token[1].toLowerCase();
                    decomposition.str += token[1];
                    decomposition.layout[decomposition.layout.length] = [2, token[1]];
                    break;
                case 8:
                    decomposition.str += '=';
                    decomposition.layout[decomposition.layout.length] = [3, '='];
                    break;
                case 9:
                    decomposition.type = 'open/close';
                    decomposition.str += '/';
                    decomposition.layout[decomposition.layout.length] = [1, '/'];
                    break;
                case 10:
                    decomposition.str += token[1];
                    decomposition.layout[decomposition.layout.length] = [4, token[1]];
                    break;
                case 11:
                    decomposition.str += '"';
                    decomposition.layout[decomposition.layout.length] = [5, '"'];
                    value = '';
                    break;
                case 12:
                    decomposition.str += "'";
                    decomposition.layout[decomposition.layout.length] = [5, "'"];
                    value = '';
                    break;
                case 13:
                    if (! decomposition.attrib) {
                        decomposition.attrib = {};
                    }
                    decomposition.attrib[name] = token[1];
                    value = token[1];
                    decomposition.str += token[1];
                    break;
                case 14:
                    decomposition.str += token[1];
                    decomposition.layout[decomposition.layout.length] = [4, token[1]];
                    break;
                case 15:
                    name = token[1].toLowerCase();
                    decomposition.str += token[1];
                    decomposition.layout[decomposition.layout.length] = [6, token[1]];
                    if (! decomposition.attrib) {
                        decomposition.attrib = {};
                    }
                    decomposition.attrib[name] = '';
                    break;
                case 16:
                    decomposition.str += token[1];
                    if (! decomposition.attrib) {
                        decomposition.attrib = {};
                    }
                    decomposition.attrib[name] += token[1];
                    value += token[1];
                    break;
                case 17:
                    decomposition.str += token[1];
                    decomposition.layout[decomposition.layout.length] = [7, value];
                    value = '';
                    decomposition.layout[decomposition.layout.length] = [5, token[1]];
                    break;
                case 18:
                    decomposition.str += token[1];
                    decomposition.attrib[name] += token[1];
                    value += token[1];
                    break;
                case 19:
                    decomposition.str += token[1];
                    decomposition.attrib[name] += token[1];
                    value += token[1];
                    break;
                case 20:
                    decomposition.str += token[1];
                    if (13 == previous_mode || 19 == previous_mode) {
                        decomposition.layout[decomposition.layout.length] = [7, value];
                    }
                    value = '';
                    decomposition.layout[decomposition.layout.length] = [4, token[1]];
                    break;
            }
            token = this.get_token();
        }
        if (decomposition.type) {
            if ('text' == type) {
                this.syntax[token_key].str += decomposition.str;
            } else {
                this.syntax[++token_key] = {
                    type : 'text',
                    str  : decomposition.str
                };
            }
        }
    }

    this.highlight = function() {
        var chars = [
            ['@l;' , '<span class="bb_spec_char">@l;</span>' ],
            ['@r;' , '<span class="bb_spec_char">@r;</span>' ],
            ['@q;' , '<span class="bb_spec_char">@q;</span>' ],
            ['@a;' , '<span class="bb_spec_char">@a;</span>' ],
            ['@at;', '<span class="bb_spec_char">@at;</span>']
        ];
        var link_search = this.preg_autolinks.pattern;
        var link_replace = this.preg_autolinks.highlight;
        var str = '';
        var elem;
        var val;
        for (var i_syntax in this.syntax) {
            elem = this.syntax[i_syntax].str;
            if ('text' == this.syntax[i_syntax].type) {
                elem = this.htmlspecialchars(elem);
                elem = this.strtr(elem, chars);
                for (var i_mnemonic in this.mnemonics) {
                    elem = elem.replace(
                        this.mnemonics[i_mnemonic],
                        '<span class="bb_mnemonic">' + this.mnemonics[i_mnemonic] + '</span>'
                    );
                }
                for (var i = 0; link_search[i]; ++i) {
                    elem = elem.replace(link_search[i], link_replace[i]);
                }
                str += elem;
            } else {
                str += '<span class="bb_tag">';
                var trim_val = '';
                for (var i_val in this.syntax[i_syntax].layout) {
                    val = this.syntax[i_syntax].layout[i_val];
                    switch (val[0]) {
                        case 0:
                            str += '<span class="bb_bracket">' + val[1] + '</span>';
                            break;
                        case 1:
                            str += '<span class="bb_slash">/</span>';
                            break;
                        case 2:
                            str += '<span class="bb_tagname">' + val[1] + '</span>';
                            break;
                        case 3:
                            str += '<span class="bb_equal">=</span>';
                            break;
                        case 4:
                            str += val[1];
                            break;
                        case 5:
                            trim_val = val[1].replace(/\s/, '');
                            if (! trim_val) {
                            	str += val[1];
                            } else {
                                str += '<span class="bb_quote">' + val[1] + '</span>';
                            }
                            break;
                        case 6:
                            str += '<span class="bb_attrib_name">'
                                + this.htmlspecialchars(val[1]) + '</span>';
                            break;
                        case 7:
                            trim_val = val[1].replace(/\s/, '');
                            if (! trim_val) {
                            	str += val[1];
                            } else {
                                str += '<span class="bb_attrib_val">'
                                    + this.strtr(this.htmlspecialchars(val[1]), chars)
                                    + '</span>';
                            }
                            break;
                        default:
                            str += val[1];
                    }
                }
                str += '</span>';
            }
        }
        str = this.nl2br(str);
        str = str.replace(/\s\s/, '&nbsp;&nbsp;');
        return str;
    }

    /*
    ��������� ���������� ���� � ������� <br /> �� ������ ������ � ����������
    <p> ��������� �����.
    */
    this.innerText = function(node) {
        if (node.innerText) {
            return node.innerText;
        }
        if (node.textContent) {
            for (var t = [], l = (c = node.childNodes).length, p, i = 0; i < l; i++) {
                t[t.length] =
                    'p' == (p = c[i].nodeName.toLowerCase())
                        ? '\n' + c[i].textContent + '\n'
                        : 'br' == p ? '\n' : c[i].textContent;
            }
            return t.join('');
        }
        return '';
    }

    /* ������ ������� in_array � PHP */
    this.in_array = function(needle, haystack) {
        for (var i = 0; haystack[i]; i++) {
            if (haystack[i] == needle) {
                return true;
            }
        }
        return false;
    }

    /* ������ ������� nl2br � PHP */
    this.nl2br = function(str) {
        if (typeof(str) == "string") {
            return str.replace(/(\r\n)|(\n\r)|\r|\n/g, '<br />');
        }
        return str;
    }

    /* ������ ������� htmlspecialchars � PHP */
    this.htmlspecialchars = function(str) {
        str = str.replace(/&/g, '&amp;');
        str = str.replace('/\"/g', '&quot;');
        str = str.replace("/\'/g", '&#039;');
        str = str.replace(/</g, '&lt;');
        str = str.replace(/>/g, '&gt;');
        return str
    }

    /*
    ������ ������� strtr � PHP
    pairs = [['a', 'b'], ['c', 'd']];
    str1 = strtr("abcdabcdabcdabcd", pairs);
    str2 = strtr("abcdabcdabcdabcd", "dcba", "hgfe");
    */
    this.strtr = function(str, pairs, to) {
        if ((typeof(pairs)=="object") && (pairs.length)) {
            for (i in pairs) {
                str = str.replace(RegExp(pairs[i][0], "g"), pairs[i][1]);
            }
            return str;
        } else {
            pairs2 = new Array();
            for (i = 0; i < pairs.length; i++) {
                pairs2[i] = [pairs.substr(i,1), to.substr(i,1)];
            }
            return strtr(str, pairs2);
        }
    }

    this.parse();
}

/*
��������� �������� ������ bbcode.
���� ������� �������� ������� ��� ������ � textarea
*/

// Remember the current position.
function storeCaret(text) {
	// Only bother if it will be useful.
	if (typeof(text.createTextRange) != "undefined")
		text.caretPos = document.selection.createRange().duplicate();
}

// Replaces the currently selected text with the passed text.
function replaceText(text, textarea) {
	// Attempt to create a text range (IE).
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos;
		if (caretPos.text.charAt(caretPos.text.length - 1) == ' ') {
		    caretPos.text = text + ' ';
		} else {
		    caretPos.text = text;
		}
		caretPos.select();
	}
	// Mozilla text range replace.
	else if (typeof(textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var scrollPos = textarea.scrollTop;

		textarea.value = begin + text + end;

		if (textarea.setSelectionRange)
		{
			textarea.focus();
			textarea.setSelectionRange(begin.length + text.length, begin.length + text.length);
		}
		textarea.scrollTop = scrollPos;
	}
	// Just put it on the end.
	else {
		textarea.value += text;
		textarea.focus(textarea.value.length - 1);
	}
}

// Surrounds the selected text with text1 and text2.
function surroundText(text1, text2, textarea) {
	textarea = xbb_textarea;
	// Can a text range be created?
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange) {
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? text1 + caretPos.text + text2 + ' ' : text1 + caretPos.text + text2;

		if (temp_length == 0) {
			caretPos.moveStart("character", -text2.length);
			caretPos.moveEnd("character", -text2.length);
			caretPos.select();
		}
		else
			textarea.focus(caretPos);
	}
	// Mozilla text range wrap.
	else if (typeof(textarea.selectionStart) != "undefined") {
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;

		textarea.value = begin + text1 + selection + text2 + end;

		if (textarea.setSelectionRange) {
			if (selection.length == 0)
				textarea.setSelectionRange(newCursorPos + text1.length, newCursorPos + text1.length);
			else
				textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	}
	// Just put them on the end, then.
	else {
		textarea.value += text1 + text2;
		textarea.focus(textarea.value.length - 1);
	}
}

function doinsert(text1, text2) {
    textarea = xbb_textarea;
	// Can a text range be created?
	if (typeof(textarea.caretPos) != "undefined" && textarea.createTextRange)
	{
		var caretPos = textarea.caretPos, temp_length = caretPos.text.length;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ?  caretPos.text + text1 + text2 + ' ' :  caretPos.text + text1 + text2;

		if (temp_length == 0)
		{
			caretPos.moveStart("character", 0);
			caretPos.moveEnd("character", 0);
			caretPos.select();
		}
		else
			textarea.focus(caretPos);
	}
	// Mozilla text range wrap.
	else if (typeof(textarea.selectionStart) != "undefined")
	{
		var begin = textarea.value.substr(0, textarea.selectionStart);
		var selection = textarea.value.substr(textarea.selectionStart, textarea.selectionEnd - textarea.selectionStart);
		var end = textarea.value.substr(textarea.selectionEnd);
		var newCursorPos = textarea.selectionStart;
		var scrollPos = textarea.scrollTop;

		textarea.value = begin + text1 + selection + text2 + end;

		if (textarea.setSelectionRange)
		{
			if (selection.length == 0)
				textarea.setSelectionRange(newCursorPos + text1.length + text2.length , newCursorPos + text1.length + text2.length);
			else
				textarea.setSelectionRange(newCursorPos, newCursorPos + text1.length + selection.length + text2.length);
			textarea.focus();
		}
		textarea.scrollTop = scrollPos;
	}
	// Just put them on the end, then.
	else
	{
		textarea.value += text1 + text2;
		textarea.focus(textarea.value.length - 1);
	}

}

function tag_url()
{
var FoundErrors = '';
var enterURL   = prompt(text_enter_url, "http://");
var enterTITLE = prompt(text_enter_url_name, "My WebPage");

if (!enterURL || enterURL=='http://') {FoundErrors = 1;}
else if (!enterTITLE) {FoundErrors = 1;}

if (FoundErrors) {return;}

doinsert ('[url=' + enterURL + ']'+enterTITLE, '[/url]');
}

function tag_email()
{
var emailAddress = prompt(text_enter_email, "");

if (!emailAddress) {return;}

doinsert("[email]"+emailAddress,"[/email]");
}

function tag_image()
{
var FoundErrors = '';
var enterURL   = prompt(text_enter_image, "http://");

if (!enterURL || enterURL=='http://' || enterURL.length<10) {return;}

doinsert("[img]"+enterURL,"[/img]");
}

function tag_list()
{
var listvalue = "init";
var thelist = "";

while ( (listvalue != "") && (listvalue != null) )
{
listvalue = prompt(list_prompt, "");
if ( (listvalue != "") && (listvalue != null) )
{
thelist = thelist+"[*]"+listvalue+"\n";
}
}

if ( thelist != "" )
{
doinsert( "[list]\n" + thelist, "[/list]\n");
}
}