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

class Cron extends ObjectModel
{
    public $description;
    public $callback;
    public $block;
    public $crontime;
    public $channel;
    public $id_shop;

    public static $definition = array(
        'table' => 'advancedimporter_cron',
        'primary' => 'id_advancedimporter_cron',
        'fields' => array(
            'description' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 50),
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'callback' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'block' => array('type' => self::TYPE_HTML, 'required' => false),
            'crontime' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 20),
            'channel' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
        ),
    );

    public function plannify($date = null)
    {
        if (is_null($date)) {
            $date = new DateTime();
        }

        $block = new Block();
        $block->planned_at = $date->format('Y-m-d H:i:s');
        $block->callback = $this->callback;
        if (!empty($this->block)) {
            $block->block = $this->block;
        }

        $block->id_shop = $this->id_shop;
        $block->channel = $this->channel;
        $block->id_advancedimporter_flow = 0;
        $created_at = new DateTime();
        $block->created_at = $created_at->format('Y-m-d H:i:s');
        $block->save();

        return $block;
    }
}
