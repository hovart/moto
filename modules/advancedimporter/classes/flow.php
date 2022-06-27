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

class Flow extends ObjectModel
{
    const STATUS_ERROR = -1;
    const STATUS_WAITING = 0;
    const STATUS_PROCESSING = 1;
    const STATUS_IMPORTING = 2;
    const STATUS_FINISHED = 3;

    public $date_add;
    public $date_upd;
    public $filename;
    public $path;
    public $type;
    public $status;
    public $block_count = 0;
    public $error_count = 0;
    public $success_count = 0;
    public $started_at;
    public $ended_at;

    public static $definition = array(
        'table' => 'advancedimporter_flow',
        'primary' => 'id_advancedimporter_flow',
        'fields' => array(
            'started_at' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'ended_at' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_add' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
            'type' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 4),
            'filename' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'path' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'status' => array('type' => self::TYPE_INT, 'required' => true),
            'block_count' => array('type' => self::TYPE_INT, 'required' => true),
            'error_count' => array('type' => self::TYPE_INT, 'required' => true),
            'success_count' => array('type' => self::TYPE_INT, 'required' => true),
        ),
    );

    public function delete()
    {
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'advancedimporter_block`
            WHERE id_advancedimporter_flow = '.(int)$this->id
        );

        // Unlink file
        unlink(_PS_MODULE_DIR_.'advancedimporter/flows/import/'.$this->path);

        return parent::delete();
    }

    public static function getByPath($path)
    {
        $sympath = str_replace(_PS_MODULE_DIR_.'advancedimporter/flows/import/', '', $path);
        // Check file don't exists
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Flow');
        } else {
            $collection = new Collection('Flow');
        }
        $collection->where('path', '=', $sympath);

        if (count($collection)) {
            return $collection->getFirst();
        }

        $pathinfo = pathinfo($path);

        $flow = new Flow();
        $flow->filename = $pathinfo['filename'].'.'.$pathinfo['extension'];
        $flow->type = $pathinfo['extension'];
        $flow->path = $sympath;
        $flow->status = self::STATUS_WAITING;
        $flow->save();

        return $flow;
    }

    public function compileData()
    {
        $this->started_at = Db::getInstance()->getValue(
            'SELECT min(block.treatment_start)
            FROM '._DB_PREFIX_.'advancedimporter_block as block
            WHERE block.id_advancedimporter_flow = '.(int)$this->id.' AND block.result IN (-1, 2)'
        );
        $this->ended_at = Db::getInstance()->getValue(
            'SELECT max(block.treatment_end)
            FROM '._DB_PREFIX_.'advancedimporter_block as block
            WHERE block.id_advancedimporter_flow = '.(int)$this->id.' AND block.result IN (-1, 2)'
        );
        $this->block_count = (int)Db::getInstance()->getValue(
            'SELECT count(*) FROM '._DB_PREFIX_.'advancedimporter_block as block
            WHERE block.id_advancedimporter_flow = '.(int)$this->id.'
            AND block.id_parent = 0'
        );
        $this->success_count = (int)Db::getInstance()->getValue(
            'SELECT count(*) FROM '._DB_PREFIX_.'advancedimporter_block as block
            WHERE block.id_advancedimporter_flow = '.(int)$this->id.' AND block.result = 2
            AND block.id_parent = 0'
        );
        $this->error_count = (int)Db::getInstance()->getValue(
            'SELECT count(*) FROM '._DB_PREFIX_.'advancedimporter_block as block
            WHERE block.id_advancedimporter_flow = '.(int)$this->id.' AND block.result = -1
            AND block.id_parent = 0'
        );
        if ($this->block_count === $this->success_count + $this->error_count) {
            $this->status = self::STATUS_FINISHED;
        }

        $this->save();
    }
}
