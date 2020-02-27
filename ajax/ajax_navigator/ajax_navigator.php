<?php
function universal_link_bar($count, $page, $URL, $perpage, $show_link, $page_text='&rp=', $before_page='') {
	$bar_content = '';
	$class_prev_next = "nav-prev-next";
	$class_prev_next_act = "nav-prev-next-act";
	$class_td_l = "nav-left";
	$class_td_c = "nav-center";
	$class_td_r = "nav-right";
	$class_page = "nav-page";
	$class_page_act = "nav-page-act";
	$pages_count = ceil($count / $perpage);
	$sperator = '';
	$y = $show_link;

	$begin = $page - intval($show_link / 2);
	unset($show_dots);

	if($page != 1 && $pages_count>=1) {
		if($page==2) {
			$bar_content.= '<td class="'.$class_td_l.'">';
				$bar_content.= '<span class="'.$class_prev_next_act.'" onClick="LoadPage(1);"><span class="text14">&larr;</span>&nbsp;Предыдущая</span></span>';
			$bar_content.= '</td>';
			$bar_content.= '<td class="'.$class_td_c.'">';
		}else{
			$bar_content.= '<td class="'.$class_td_l.'">';
				$bar_content.= '<span class="'.$class_prev_next_act.'" onClick="LoadPage('.($page - 1).');"><span class="text14">&larr;</span>&nbsp;Предыдущая</span></span>';
			$bar_content.= '</td>';
			$bar_content.= '<td class="'.$class_td_c.'">';
		}

	}elseif($pages_count>=0) {
		$bar_content.= '<td class="'.$class_td_l.'">';
			$bar_content.= '<span class="'.$class_prev_next.'"><span class="text14">&larr;</span>&nbsp;Предыдущая</span>';
		$bar_content.= '</td>';
		$bar_content.= '<td class="'.$class_td_c.'">';

		if ($pages_count <= $show_link + 1) $show_dots = 'no';

		if (($begin > 2) && !isset($show_dots) && ($pages_count - $show_link > 2)) {
			$bar_content.= '<span class='.$class_page.' onClick="LoadPage(1);">1</span>';
		}
	}

	for ($j = 1; $j < $page; $j++) {
		if ((($begin + 1 + $show_link - $j) > $pages_count+1) && ($pages_count-$show_link + $j > 0)) {
			$page_link = $pages_count - $show_link + $j;

			if (!isset($show_dots) && ($pages_count-$show_link) >= 1) {
				$bar_content.= '<span class='.$class_page.' onClick="LoadPage(1);">1</span>';
				if(($pages_count-$show_link) > 1) $bar_content.= '&hellip;';
				$show_dots = "no";
			}

			$bar_content.= '<span class='.$class_page.' onClick="LoadPage('.$page_link.');">'.$page_link.'</span>'.$sperator;
		}else{
			continue;
		}
	}

	for ($j = 1; $j <= $show_link; $j++) {
		$i = $begin + $j-1; // Номер ссылки

		if ($i < 1) { $show_link++; continue; }

		if (!isset($show_dots) && $begin > 1 ) {
			if ($begin + $show_link - 1 <= $pages_count) {
				$bar_content.= '<span class='.$class_page.' onClick="LoadPage(1);">1</span>';

				if(($pages_count-$show_link) > 1) $bar_content.= '&hellip;';
				$show_dots = "no";
			}
		}

		if ($i > $pages_count) break;

		if ($pages_count>1)  {
			if ($i == $page && $pages_count>1) {
				$bar_content.= '<span class='.$class_page_act.'>'.$i.'</span>';
			}elseif($i==1) {
				$bar_content.= '<span class='.$class_page.' onClick="LoadPage(1);">'.$i.'</span>';
			}else{
				$bar_content.= '<span class='.$class_page.' onClick="LoadPage('.$i.');">'.$i.'</span>';
			}
		}

		if (($i != $pages_count) && ($j != $show_link)) $bar_content.= "$sperator";

		if (($j == $show_link) && ($i+1) <= $pages_count && ($pages_count-$y) > 1 ) {
			$bar_content.= '&hellip;';
		}
	}

	if ($begin + $show_link +0 <= $pages_count) {
		$bar_content.= '<span class='.$class_page.' onClick="LoadPage('.$pages_count.');">'.$pages_count.'</span>';
	}

	if ($page != $pages_count && $pages_count>=0) {
		$bar_content.= '</td>';
		$bar_content.= '<td class="'.$class_td_r.'">';
			$bar_content.= '<span class="'.$class_prev_next_act.'" onClick="LoadPage('.($page + 1).');">Следующая&nbsp;<span class="text14">&rarr;</span></span>';
		$bar_content.= '</td>';

	}elseif($pages_count>=0) {
		$bar_content.= '</td>';
		$bar_content.= '<td class="'.$class_td_r.'">';
			$bar_content.= '<span class="'.$class_prev_next.'">Следующая&nbsp;<span class="text14">&rarr;</span></span>';
		$bar_content.= '</td>';
	}

	return '<table class="ajax-nav"><tr>'.$bar_content.'</tr></table>';
}

?>