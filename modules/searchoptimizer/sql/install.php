<?php
/**
* 2015 Novansis
*
* NOTICE OF LICENSE
*
* Conditions and limitations:
*
* A. This source code file is copyrighted, you cannot remove any copyright notice from this file.  You agree to prevent any unauthorized copying of this file.  Except as expressly provided herein, Novansis does not grant any express or implied right to you under copyrights, trademarks, or trade secret information.
*
* B. You may NOT:  (i) rent or lease the file to any third party; (ii) assign this file or transfer the file without the express written consent of Novansis; (iii) modify, adapt, or translate the file in whole or in part; or (iv) distribute, sublicense or transfer the source code form of any components of the file and derivatives thereof to any third party.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer versions in the future.
*
*  @author    Novansis <info@novansis.com>
*  @copyright 2015 Novansis SL
*  @license   http://www.novansis.com/

*/

$vxj43jnx2v2y = array();

$vxj43jnx2v2y[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'search_optimizer` (
    `id_search` int(11) NOT NULL AUTO_INCREMENT,
	`date_add` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `id_shop` int(11) NOT NULL,
	`id_lang` int(11) NOT NULL,
    `expression` varchar(150) NOT NULL,
    `products` varchar(300) NOT NULL,
    PRIMARY KEY  (`id_search`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';


foreach ($vxj43jnx2v2y as $vcli32jz0fsv)
	if (Db::getInstance()->execute($vcli32jz0fsv) == false)
		return false;
