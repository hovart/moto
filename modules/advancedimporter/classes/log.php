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

class Log extends ObjectModel
{
    public $id_advancedimporter_block;
    public $id_advancedimporter_flow;
    public $type;
    public $message;
    public $id_shop;
    public $date_add;

    public static $definition = array(
        'table' => 'advancedimporter_log',
        'primary' => 'id_advancedimporter_log',
        'fields' => array(
            'id_advancedimporter_block' => array(
                'type' => self::TYPE_INT,
                'required' => true,
                'validate' => 'isUnsignedInt',
            ),
            'id_advancedimporter_flow' => array(
                'type' => self::TYPE_INT,
                'required' => false,
                'validate' => 'isUnsignedInt',
            ),
            'type' => array(
                'type' => self::TYPE_STRING,
                'required' => true,
                'size' => 20
            ),
            'message' => array(
                'type' => self::TYPE_STRING,
                'required' => true
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'required' => true,
                'validate' => 'isUnsignedInt'
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'required' => true,
                'validate' => 'isDateFormat'
            ),
        ),
    );

    public static function addLog($id_block, $id_flow, $log_type, $message)
    {
        if (!Configuration::getGlobalValue('AI_LOG_ENABLE')) {
            return;
        }

        $id_shop = Context::getContext()->shop->id;
        $date = new DateTime();

        return Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'advancedimporter_log`
            SET
                `id_advancedimporter_block` = '.(int) $id_block.',
                `id_advancedimporter_flow` = '.(int) $id_flow.',
                type = "'.pSql($log_type).'",
                message = "'.pSql($message).'",
                id_shop = '.$id_shop.',
                date_add = "'.$date->format('Y-m-d H:i:s').'"'
        );
    }

    public static function success($id_block, $id_flow, $message)
    {
        if (!Configuration::getGlobalValue('AI_SUCCESSLOG_ENABLE')) {
            return;
        }

        self::addLog($id_block, $id_flow, 'success', $message);
    }

    public static function error($id_block, $id_flow, $message)
    {
        if (!Configuration::getGlobalValue('AI_ERRORLOG_ENABLE')) {
            return;
        }

        self::addLog($id_block, $id_flow, 'error', $message);
    }

    public static function notice($id_block, $id_flow, $message)
    {
        if (!Configuration::getGlobalValue('AI_NOTICELOG_ENABLE')) {
            return;
        }

        self::addLog($id_block, $id_flow, 'notice', $message);
    }

    public static function sys($id_block, $id_flow, $message)
    {
        if (!Configuration::getGlobalValue('AI_SYSLOG_ENABLE')) {
            return;
        }

        self::addLog($id_block, $id_flow, 'sys', $message);
    }
}
