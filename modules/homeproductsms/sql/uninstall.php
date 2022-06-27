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

$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'homeproductsms`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'homeproductsms_gen_set`';
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'homeproductsms_lang`';

foreach ($sql as $query)
	if (Db::getInstance()->execute($query) == false)
		return false;