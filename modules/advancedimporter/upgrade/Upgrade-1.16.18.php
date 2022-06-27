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

function upgrade_module_1_16_18($module)
{
    $return = true;

    $return &= Db::getInstance()->execute('
        ALTER TABLE `'._DB_PREFIX_.'advancedimporter_block`
        DROP INDEX `planned_at`,
        DROP INDEX `treatment_end`');

    $return &= Db::getInstance()->execute('
        ALTER TABLE `'._DB_PREFIX_.'advancedimporter_block`
        ADD INDEX (`planned_at`),
        ADD INDEX (`treatment_end`),
        ADD INDEX (`id_shop`),
        ADD INDEX (`id_advancedimporter_flow`)');

    $return &= Db::getInstance()->execute('
        ALTER TABLE `'._DB_PREFIX_.'advancedimporter_externalreference`
        ADD INDEX (`external_reference`),
        ADD INDEX (`object_type`)');

    $return &= Db::getInstance()->execute('
        ALTER TABLE `'._DB_PREFIX_.'advancedimporter_log`
        ADD INDEX (`type`),
        ADD INDEX (`id_shop`)');

    return $return;
}
