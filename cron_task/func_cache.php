<?php

function cache_stat_links(){
	$sql = mysql_query("SELECT `id`,`url`,`description`,`color` FROM `tb_ads_slink` WHERE `status`='1' AND `date_end`>'".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_row($sql)) {
			$stat_link_arr[] = array('id_sl' => $row["0"], 'url_sl' => $row["1"], 'desc_sl' => $row["2"], 'color_sl' => $row["3"]);						
		}
	}else{
		$stat_link_arr = array();
	}
	@file_put_contents(ROOT_DIR."/cache/stat_link.inc", serialize($stat_link_arr));
	mysql_query("ALTER TABLE `tb_ads_slink` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_slink`");
}

function cache_stat_kat(){
	$sql = mysql_query("SELECT `id`,`url`,`description`,`color` FROM `tb_ads_kat` WHERE `status`='1' AND `date_end`>'".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_row($sql)) {
			$stat_kat_arr[] = array('id_sl' => $row["0"], 'url_sl' => $row["1"], 'desc_sl' => $row["2"], 'color_sl' => $row["3"]);						
		}
	}else{
		$stat_kat_arr = array();
	}
	@file_put_contents(ROOT_DIR."/cache/stat_kat.inc", serialize($stat_kat_arr));
	mysql_query("ALTER TABLE `tb_ads_kat` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_kat`");
}

function cache_kontext(){
	$sql = mysql_query("SELECT `id`,`title`,`description`,`color`,`date`,`plan`,`totals`,`views` FROM `tb_ads_kontext` WHERE `status`='1' AND `totals`>'0' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_row($sql)) {
			$kontext_arr[] = array(
				'id_kl' => $row["0"], 
				'title_kl' => $row["1"], 
				'desc_kl' => $row["2"], 
				'color_kl' => $row["3"], 
				'date_kl' => $row["4"], 
				'plan_kl' => $row["5"], 
				'totals_kl' => $row["6"], 
				'views_kl' => $row["7"]
			);						
		}
	}else{
		$kontext_arr = array();
	}
	@file_put_contents(ROOT_DIR."/cache/kontext_link.inc", serialize($kontext_arr));
	mysql_query("ALTER TABLE `tb_ads_kontext` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_kontext`");
}

function cache_frm_links(){
	$sql = mysql_query("SELECT * FROM `tb_ads_frm` WHERE `status`='1' AND `date_end`>='".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$frm_links_array[] = array('dataN' => $row['date'], 'dataO' => $row['date_end'], 'link' => $row['url'], 'text' => $row['description']);
		}
	}else{
		$frm_links_array = array();
	}
	@file_put_contents(ROOT_DIR."/cache/frm_links.inc", serialize($frm_links_array));
	mysql_query("ALTER TABLE `tb_ads_frm` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_frm`");
}

function cache_txt_links(){
	$sql = mysql_query("SELECT * FROM `tb_ads_txt` WHERE `status`='1' AND `date_end`>='".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$txt_links_array[] = array('dataN' => $row['date'], 'dataO' => $row['date_end'], 'link' => $row['url'], 'text' => $row['description']);
		}
	}else{
		$txt_links_array = array();
	}
	@file_put_contents(ROOT_DIR."/cache/txt_links.inc", serialize($txt_links_array));
	mysql_query("ALTER TABLE `tb_ads_txt` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_txt`");
}


function cache_rek_cep(){
	$sql_del = mysql_query("SELECT `id` FROM `tb_ads_rc` WHERE `status`='1' ORDER BY `id` DESC");
	$all_rek_cep = mysql_num_rows($sql_del);
	if($all_rek_cep>5) {
		$kol_dell_rek_cep =($all_rek_cep-5);
		mysql_query("DELETE FROM `tb_ads_rc` WHERE `status`='1' ORDER BY `id` ASC LIMIT $kol_dell_rek_cep") or die(mysql_error());
	}

	$sql = mysql_query("SELECT * FROM `tb_ads_rc` WHERE `status`='1' ORDER BY `id` DESC LIMIT 5");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$rek_cep_array[] = array('id' => $row['id'], 'color' => $row['color'], 'url' => $row['url'], 'description' => $row['description'], 'view' => $row['view']);
		}
	}else{
		$rek_cep_array = array();
	}
	@file_put_contents(ROOT_DIR."/cache/rek_cep.inc", serialize($rek_cep_array));
	mysql_query("ALTER TABLE `tb_ads_rc` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_rc`");
}

function cache_banners(){
	$sql = mysql_query("SELECT * FROM `tb_ads_banner` WHERE `status`='1' AND `date_end`>='".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$type_write = $row["type"];
			if($type_write=="100x100") 	$b_arr_100x100[] = array('datS' => $row['date'], 'datE' => $row['date_end'], 'id_b' => $row['id'], 'urlbanner' => $row["urlbanner_load"], 'count_visit' => $row['members']);
			if($type_write=="200x300") 	$b_arr_200x300[] = array('datS' => $row['date'], 'datE' => $row['date_end'], 'id_b' => $row['id'], 'urlbanner' => $row["urlbanner_load"], 'count_visit' => $row['members']);
			if($type_write=="468x60") 	$b_arr_468x60[] = array('datS' => $row['date'], 'datE' => $row['date_end'], 'id_b' => $row['id'], 'urlbanner' => $row["urlbanner_load"], 'count_visit' => $row['members']);
			if($type_write=="468x60_frm") 	$b_arr_468x60_frm[] = array('datS' => $row['date'], 'datE' => $row['date_end'], 'id_b' => $row['id'], 'urlbanner' => $row["urlbanner_load"], 'count_visit' => $row['members']);
			if($type_write=="728x90") 	$b_arr_728x90[] = array('datS' => $row['date'], 'datE' => $row['date_end'], 'id_b' => $row['id'], 'urlbanner' => $row["urlbanner_load"], 'count_visit' => $row['members']);

		}
	}else{
		$b_arr_100x100 = array();
		$b_arr_200x300 = array();
		$b_arr_468x60 = array();
		$b_arr_728x90 = array();
	}
	if(!isset($b_arr_100x100)) 	$b_arr_100x100 = array();
	if(!isset($b_arr_200x300)) 	$b_arr_200x300 = array();
	if(!isset($b_arr_468x60)) 	$b_arr_468x60 = array();
	if(!isset($b_arr_468x60_frm)) 	$b_arr_468x60_frm = array();
	if(!isset($b_arr_728x90)) 	$b_arr_728x90 = array();

	@file_put_contents(ROOT_DIR."/cache/banners100x100.inc", serialize($b_arr_100x100));
	@file_put_contents(ROOT_DIR."/cache/banners200x300.inc", serialize($b_arr_200x300));
	@file_put_contents(ROOT_DIR."/cache/banners468x60.inc", serialize($b_arr_468x60));
	@file_put_contents(ROOT_DIR."/cache/banners468x60_frm.inc", serialize($b_arr_468x60_frm));
	@file_put_contents(ROOT_DIR."/cache/banners728x90.inc", serialize($b_arr_728x90));
	mysql_query("ALTER TABLE `tb_ads_banner` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_banner`");
}

function cache_beg_stroka(){
	$sql = mysql_query("SELECT `id`,`url`,`description`,`color` FROM `tb_ads_beg_stroka` WHERE `status`='1' AND `date_end`>'".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_row($sql)) {
			$beg_stroka_arr[] = array('id_beg' => $row["0"], 'url_beg' => $row["1"], 'desc_beg' => $row["2"], 'color_beg' => $row["3"]);
		}
	}else{
		$beg_stroka_arr = array();
	}
	@file_put_contents(ROOT_DIR."/cache/beg_stroka.inc", serialize($beg_stroka_arr));
	mysql_query("ALTER TABLE `tb_ads_beg_stroka` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_beg_stroka`");
}

function cache_catalog(){
	$sql = mysql_query("SELECT * FROM `tb_ads_catalog` WHERE `status`='1' AND `date_end`>='".time()."' ORDER BY `id` DESC");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$catalog_array[] = array("DateStart" => $row["date"], "DateEnd" => $row["date_end"], "Title" => $row["title"], "Link" => $row["url"], "Color" => $row["color"]);
		}
	}else{
		$catalog_array = array();
	}
	@file_put_contents(ROOT_DIR."/cache/catalog.inc", serialize($catalog_array));
	mysql_query("ALTER TABLE `tb_ads_catalog` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_catalog`");
}

function cache_pay_row(){
	$sql_set_status = mysql_query("SELECT `id` FROM `tb_ads_pay_row` WHERE `status`='1' ORDER BY `id` DESC");
	$all_set_status = mysql_num_rows($sql_set_status);
	if($all_set_status > 1) {
		$kol_set_status = ($all_set_status - 1);
		mysql_query("UPDATE `tb_ads_pay_row` SET `status`='3' WHERE `status`='1' ORDER BY `id` ASC LIMIT $kol_set_status") or die(mysql_error());
	}

	$sql = mysql_query("SELECT `id`,`username`,`date`,`url`,`description`,`views` FROM `tb_ads_pay_row` WHERE `status`='1' ORDER BY `id` DESC LIMIT 1");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$pay_row_arr[] = array(
				'id_pr' => $row["id"], 
				'user_pr' => $row["username"], 
				'date_pr' => $row["date"], 
				'url_pr' => $row["url"],
				'desc_pr' => $row["description"], 
				'views_pr' => $row["views"]
			);
		}
	}else{
		$pay_row_arr = array();
	}
	@file_put_contents(ROOT_DIR."/cache/cache_pay_row.inc", serialize($pay_row_arr));
	mysql_query("ALTER TABLE `tb_ads_pay_row` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_pay_row`");
}

function cache_quick_mess(){
	$sql_set_status = mysql_query("SELECT `id` FROM `tb_ads_quick_mess` WHERE `status`='1' ORDER BY `id` DESC");
	$all_set_status = mysql_num_rows($sql_set_status);
	if($all_set_status > 10) {
		$kol_set_status = ($all_set_status - 10);
		mysql_query("UPDATE `tb_ads_quick_mess` SET `status`='3' WHERE `status`='1' ORDER BY `id` ASC LIMIT $kol_set_status") or die(mysql_error());
	}

	$sql_del = mysql_query("SELECT `id` FROM `tb_ads_quick_mess` WHERE `status`='3' ORDER BY `id` DESC");
	$all_del = mysql_num_rows($sql_del);
	if($all_del > 5) {
		$kol_del = ($all_del - 5);
		mysql_query("DELETE FROM `tb_ads_quick_mess` WHERE `status`='3' ORDER BY `id` ASC LIMIT $kol_del") or die(mysql_error());
	}

	$sql = mysql_query("SELECT `id`,`id_us`,`username`,`url`,`description`,`color` FROM `tb_ads_quick_mess` WHERE `status`='1' ORDER BY `id` DESC LIMIT 10");
	if(mysql_num_rows($sql)>0) {
		while($row = mysql_fetch_assoc($sql)) {
			$quick_mess_arr[] = array(
				'id_s' => $row["id"], 
				'user_id_s' => $row["id_us"], 
				'user_name_s' => $row["username"], 
				'url_s' => $row["url"],
				'desc_s' => $row["description"], 
				'color_s' => $row["color"]
			);
		}
	}else{
		$quick_mess_arr = array();
	}
	@file_put_contents(ROOT_DIR."/cache/cache_quick_mess.inc", serialize($quick_mess_arr));
	mysql_query("ALTER TABLE `tb_ads_quick_mess` ORDER BY `id` ASC");
	mysql_query("OPTIMIZE TABLE `tb_ads_quick_mess`");
}

?>