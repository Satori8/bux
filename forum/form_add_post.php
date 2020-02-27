<?php
echo '<div align="center" style="margin-top:30px;"><span id="blockno" class="subbig" onclick="javascript:showHide();">Комментировать</span></div>';

echo '<div id="blockyes" style="display: none;">';
	echo '<form action="" method="POST" id="forum" name="forumform" onsubmit="return FormC(); return false;">';
	echo '<input type="hidden" name="id_r" value="'.$id_r.'">';
	echo '<input type="hidden" name="id_pr" value="'.$id_pr.'">';
	echo '<input type="hidden" name="id_t" value="'.$id_t.'">';
	echo '<input type="hidden" name="hs" value="'.md5($id_r+$id_pr+$id_t+2205).'">';
	echo '<input type="hidden" name="addcomm" value="1">';

	echo '<table class="forum_com_add" align="center" border="0">';
	echo '<thead><tr><th align="center" nowrap="nowrap" colspan="2">Добавление комментария по теме</th></tr></thead>';
	echo '<tbody>';
	echo '<tr>';
		echo '<td class="ct" width="200">Ваш комментарий &darr;</td>';
		echo '<td class="ct">';
			echo '<span class="bbc-bold" title="Выделить жирным" onClick="javascript:addtag(\'[b]\',\'[/b]\'); return false;">B</span>';
			echo '<span class="bbc-italic" title="Выделить курсивом" onClick="javascript:addtag(\'[i]\',\'[/i]\'); return false;">i</span>';
			echo '<span class="bbc-uline" title="Выделить подчёркиванием" onClick="javascript:addtag(\'[u]\',\'[/u]\'); return false;">U</span>';
			echo '<span class="bbc-url" title="Цитата" onClick="javascript:addtag(\'[quote]\',\'[/quote]\'); return false;">Цитата</span>';
			echo '<span class="bbc-url" title="Выделить URL" onClick="javascript:addtag(\'[url]\',\'[/url]\'); return false;">URL</span>';
		echo '</td>';
	echo '</tr>';
	echo '<tr><td class="ct" colspan="2"><textarea name="post" id="post" style="width:99%; height:200px;"></textarea></td></tr>';

		echo '<tr><td class="ct" colspan="2">';

		for($i=0; $i<40; $i++){
			echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
		}

		for($i=80; $i<99; $i++){
			echo '<a href="" onclick="SetSmile(\''.$smileskod[$i].'\'); return false;"><img src="bbcode/images/smiles/'.($i+1).'.gif" alt="" align="middle" border="0" style="padding:1px; margin:1px;"></a> ';
		}

		echo '</td></tr>';

	echo '</tbody>';
	echo '</table>';

	echo '<div align="center" style="margin-top:15px;"><input type="submit" class="subblue" value="Сохранить"></div>';
	echo '</form>';
echo '</div>';
?>