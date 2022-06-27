<?php
/**
 * 2013-2016 MADEF IT.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@madef.fr so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    MADEF IT <contact@madef.fr>
 *  @copyright 2013-2016 MADEF IT
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

function upgrade_module_1_23_1($module)
{
    // Process Module upgrade to 1.23.1

    $module->createAdminTabs();

    Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedimporter_history` (
            `id_advancedimporter_history` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `flow_type` VARCHAR(255) DEFAULT NULL,
            `id_advancedimporter_block` INT UNSIGNED NOT NULL,
            `id_advancedimporter_flow` INT UNSIGNED NOT NULL,
            `object_type` VARCHAR(255) DEFAULT NULL,
            `object_id` INT UNSIGNED NOT NULL,
            `object_external_reference` VARCHAR(255) DEFAULT NULL,
            `date_add` datetime NOT NULL,
            PRIMARY KEY (`id_advancedimporter_history`)
        ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
    ');

    Configuration::updateGlobalValue('AI_HISTORY_ENABLE', 1);

    return true;
}
