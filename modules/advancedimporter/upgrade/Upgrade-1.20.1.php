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

function upgrade_module_1_20_1($module)
{
    // Process Module upgrade to 1.20.1

    require_once _PS_MODULE_DIR_.'advancedimporter/classes/flow.php';

    $return = Db::getInstance()->execute(
        'ALTER TABLE `'._DB_PREFIX_.'advancedimporter_flow`
        ADD COLUMN status INT UNSIGNED NOT NULL,
        ADD COLUMN block_count INT UNSIGNED NOT NULL default 0,
        ADD COLUMN success_count INT UNSIGNED NOT NULL default 0,
        ADD COLUMN error_count INT UNSIGNED NOT NULL default 0,
        ADD COLUMN started_at DATETIME DEFAULT NULL,
        ADD COLUMN ended_at DATETIME DEFAULT NULL,
        ADD COLUMN type VARCHAR(4) NOT NULL'
    );

    $return &= Db::getInstance()->execute(
        'UPDATE `'._DB_PREFIX_.'advancedimporter_flow`
        SET
            status = '.(int)FLOW::STATUS_FINISHED.',
            type = "xml"'
    );

    return $return;
}
