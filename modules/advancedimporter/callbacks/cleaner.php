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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/block.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/flow.php';

class Cleaner
{
    public static $id_advancedimporter_flow;
    public static $channel;

    /**
     * Remove block not link to a block.
     *
     * @deprecated since 1.15.2
     */
    public static function exec($block)
    {
        self::block($block); // Retrocompatibility
    }

    public static function block($block)
    {
        $ttl = (int) $block->ttl;

        // Outdated blocks
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'advancedimporter_block`
            WHERE
                treatment_start <> "0000-00-00 00:00:00"
                AND id_advancedimporter_flow = 0
                AND treatment_start < DATE_SUB(NOW(), INTERVAL '.(int) $ttl.' SECOND)
            LIMIT 100000'
        );

        // Outdated blocks without errors
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'advancedimporter_block`
            WHERE
                treatment_start <> "0000-00-00 00:00:00"
                AND result = 2
                AND treatment_start < DATE_SUB(NOW(), INTERVAL '.(int) $ttl.' SECOND)
            LIMIT 100000'
        );

        // Blocks attached to flows deleted
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'advancedimporter_block`
            WHERE
                id_advancedimporter_flow NOT IN
                    (SELECT id_advancedimporter_flow FROM '._DB_PREFIX_.'advancedimporter_flow)
                AND id_advancedimporter_flow <> 0
            LIMIT 100000'
        );

        // Logs attached to flows deleted
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'advancedimporter_log`
            WHERE
                id_advancedimporter_block NOT IN
                    (SELECT id_advancedimporter_block FROM '._DB_PREFIX_.'advancedimporter_block)
                AND id_advancedimporter_block <> 0
            LIMIT 100000'
        );
    }

    public static function flow($block)
    {
        $ttl = (int) $block->ttl;

        $collection = new Collection('Flow');
        $collection->sqlWhere('date_add < DATE_SUB(NOW(), INTERVAL '.(int) $ttl.' SECOND)');
        $collection->setPageSize('10');

        foreach ($collection as $flow) {
            $flow->delete();
        }
    }
}
