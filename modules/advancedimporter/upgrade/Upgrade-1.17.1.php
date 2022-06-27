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

function upgrade_module_1_17_1($module)
{
    // Process Module upgrade to 1.17.1

    $return = true;
    $return &= Db::getInstance()->execute('
        CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'advancedimporter_csv_template` (
            `id_advancedimporter_csv_template` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `template` TEXT,
            `data` TEXT,
            `filepath` VARCHAR(255) DEFAULT NULL,
            `roottag` VARCHAR(20) DEFAULT NULL,
            `delimiter` VARCHAR(1) DEFAULT NULL,
            `enclosure` VARCHAR(1) DEFAULT NULL,
            `escape` VARCHAR(1) DEFAULT NULL,
            `encoding` VARCHAR(10) DEFAULT NULL,
            `flow_type` VARCHAR(32) DEFAULT NULL,
            `ignore_first_line` INTEGER DEFAULT NULL,
            `advanced_mode` INTEGER DEFAULT 0,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_advancedimporter_csv_template`)
        ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;
    ');

    $return &= Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'tab
        WHERE module = "advancedimporter"');

    $return &= Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'module_access
        WHERE `id_module` = '.(int) $module->id);

    $module->createAdminTabs();

    require_once _PS_MODULE_DIR_.'advancedimporter/classes/cron.php';

    $cron = new Cron();
    $cron->description = $module->l('Convert CSV');
    $cron->callback = 'CsvConverter::convertFromCollection';
    $cron->block = '';
    $cron->crontime = '*/5 * * * *';
    $cron->channel = 1;
    $cron->id_shop = 1;
    $cron->save();

    return $return;
}
