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

class AIHistory extends ObjectModel
{
    public static $definition = array(
        'table' => 'advancedimporter_history',
        'primary' => 'id_advancedimporter_history',
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
            'flow_type' => array(
                'type' => self::TYPE_STRING,
                'required' => true,
                'size' => 255
            ),
            'object_type' => array(
                'type' => self::TYPE_STRING,
                'required' => true,
                'size' => 255
            ),
            'object_id' => array(
                'type' => self::TYPE_INT,
                'required' => true
            ),
            'object_external_reference' => array(
                'type' => self::TYPE_STRING,
                'required' => false,
                'size' => 255
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'required' => true,
                'validate' => 'isDateFormat'
            ),
        ),
    );

    public $flow_type;
    public $id_advancedimporter_block;
    public $id_advancedimporter_flow;
    public $object_type;
    public $object_id;
    public $object_external_reference;
    public $date_add;

    public static function create(
        $flow_type,
        $id_advancedimporter_block,
        $id_advancedimporter_flow,
        $object_type,
        $object_id,
        $object_external_reference
    ) {
        if (!Configuration::getGlobalValue('AI_HISTORY_ENABLE')) {
            return;
        }

        $history = new AIHistory();
        $history->flow_type = $flow_type;
        $history->id_advancedimporter_block = $id_advancedimporter_block;
        $history->id_advancedimporter_flow = $id_advancedimporter_flow;
        $history->object_type = $object_type;
        $history->object_id = $object_id;
        $history->object_external_reference = $object_external_reference;
        $history->save();
    }
}

