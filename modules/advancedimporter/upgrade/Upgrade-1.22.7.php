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

function upgrade_module_1_22_7($module)
{
    // Process Module upgrade to 1.22.7

    $module->createAdminTabs();
    $return = true;

    $return &= Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'advancedimporter_csv_template`
        ADD COLUMN `nodes_serialized` TEXT,
        ADD COLUMN `schema_serialized` TEXT'
    );

    $return &= Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'advancedimporter_xslt`
        ADD COLUMN `nodes_serialized` TEXT,
        ADD COLUMN `schema_serialized` TEXT,
        ADD COLUMN `item_root` VARCHAR(25)'
    );

    return $return;
}
