<?php
/**
* 2007-2015 Mack Stores
*
* NOTICE OF LICENSE
*
* This code is a property of MackStores. In No way any one is authorised to use this code  or modify this code and redistribute without prior
* permission from the authour i.e MackStores
*
*
*  @author    Mack Stores contact:-sales@mackstores.com
*  @copyright 2007-2015 Mack Stores
*  International Registered Trademark & Property of Mack Stores
*/

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'homeproductsms` (
			`id_ms` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
			`en_dis_blk` INT NOT NULL,
			`prd_cat_shw` TEXT NOT NULL,
			`prd_cat_dsp_order` INT NOT NULL,
		    `prd_num_dsp` INT NOT NULL,
			`prd_disp_new` INT NOT NULL,
			`hdg_title` TEXT NOT NULL,
			`add_cart_shw` INT NOT NULL,
			`hdg_hgt` INT NOT NULL,
			`more_shw` INT NOT NULL,
			`qck_view_shw` INT NOT NULL,
			`dsp_prc` INT NOT NULL,
			`tim_cnt_dwn_shw` INT NOT NULL,
			`pos_blk` INT NOT NULL,
			`en_dis_adv` TEXT NOT NULL,
			`mkst_advert_blk` TEXT NOT NULL,
			`prd_disp_sale` INT NOT NULL,
			`prd_disp_reductions` INT NOT NULL


)  ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'homeproductsms_lang` (
			`id_mss` INT UNSIGNED NOT NULL,
			`id_lang` INT UNSIGNED NOT NULL,
			`hdg_title_lang` TEXT NOT NULL,
			PRIMARY KEY (`id_mss`, `id_lang`)
)  ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'homeproductsms_gen_set` (
			`id_ms_gen` INT NOT NULL  PRIMARY KEY ,
			`head_disp_typ` INT NOT NULL,
			`center_ms` INT NOT NULL
)  ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci';

$sql[] = 'INSERT INTO `'._DB_PREFIX_.'homeproductsms_gen_set` VALUES (1,0,0)';




foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;