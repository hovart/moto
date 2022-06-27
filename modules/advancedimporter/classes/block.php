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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/log.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/flow.php';

class Block extends ObjectModel
{
    public $id_shop;
    public $created_at;
    public $planned_at;
    public $treatment_start;
    public $treatment_end;
    public $channel;
    public $callback;
    public $block;
    public $result;
    public $memory;
    public $error;
    public $id_advancedimporter_flow;
    public $id_parent = 0;

    public static $cache_block_executed = array();
    public static $flow_list = array();

    public static $definition = array(
        'table' => 'advancedimporter_block',
        'primary' => 'id_advancedimporter_block',
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'created_at' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
            'planned_at' => array('type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDateFormat'),
            'treatment_start' => array('type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDateFormat'),
            'treatment_end' => array('type' => self::TYPE_DATE, 'required' => false, 'validate' => 'isDateFormat'),
            'channel' => array('type' => self::TYPE_INT, 'required' => true, 'validate' => 'isUnsignedInt'),
            'callback' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'block' => array('type' => self::TYPE_HTML, 'required' => false),
            'id_parent' => array('type' => self::TYPE_INT, 'required' => false),
            'result' => array('type' => self::TYPE_INT, 'required' => false, 'validate' => 'isUnsignedInt'),
            'memory' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'required' => false),
            'error' => array('type' => self::TYPE_STRING, 'required' => false),
            'id_advancedimporter_flow' => array(
                'type' => self::TYPE_INT,
                'required' => true,
                'validate' => 'isUnsignedInt',
            ),
        ),
    );

    public static function getNextBlock($channel, $id_shop = null)
    {
        if (is_null($id_shop)) {
            $id_shop = Context::getContext()->shop->id;
        }

        $cache_clause = '';
        if (count(self::$cache_block_executed)) {
            $cache_clause = 'AND `id_advancedimporter_block`
                NOT IN ('.implode(',', array_keys(self::$cache_block_executed)).')';
        }

        $date = new DateTime();
        $result = Db::getInstance()->getRow(
            'SELECT *
            FROM `'._DB_PREFIX_.'advancedimporter_block`
            WHERE
                id_shop = '.$id_shop.'
                AND channel = '.(int) $channel.'
                AND (treatment_start  IS NULL OR treatment_start = "0000-00-00 00:00:00")
                AND (
                    `planned_at` <= "'.$date->format('Y-m-d H:i:s').'"
                    OR
                    `planned_at` IS NULL
                )
                '.$cache_clause.'
            ORDER BY `planned_at`, `id_advancedimporter_block`'
        );

        if (!$result) {
            return false;
        }

        return new self($result['id_advancedimporter_block']);
    }

    public static function writeCache()
    {
        if (empty(self::$cache_block_executed)) {
            return;
        }

        $queries = array();
        foreach (self::$cache_block_executed as $id => $data) {
            $queries[] = 'UPDATE `'._DB_PREFIX_.'advancedimporter_block`
                SET
                    treatment_start = "'.pSql($data['treatment_start']).'",
                    treatment_end = "'.pSql($data['treatment_end']).'",
                    result = '.(int) $data['result'].',
                    memory = '.(int) $data['memory'].',
                    error = '.(is_null($data['error']) ? 'NULL' : "'".pSQL($data['error'])."'").'
                WHERE `id_advancedimporter_block` = '.(int) $id.';';
        }
        Db::getInstance()->execute(implode($queries));
        foreach (self::$flow_list as $flow_id) {
            if ($flow_id == 0) {
                continue;
            }
            $flow = new Flow($flow_id);
            $flow->compileData();
        }
        self::$cache_block_executed = array();
        self::$flow_list = array();
    }

    protected function setBlockExecuting()
    {
        /*
        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'advancedimporter_block`
            SET treatment_start = "'.$date->format('Y-m-d H:i:s').'"
            WHERE `id_advancedimporter_block` = '.$this->id
        );
         */
        $date = new DateTime();
        self::$cache_block_executed[$this->id] = array(
            'treatment_start' => $date->format('Y-m-d H:i:s'),
            'treatment_end' => '0000-00-00 00:00:00',
            'result' => 0,
            'memory' => 0,
            'error' => null
        );
        self::$flow_list[$this->id_advancedimporter_flow] = $this->id_advancedimporter_flow;
    }

    protected function setBlockExecuted($memory, $result_code = 1, $error = null)
    {
        $date = new DateTime();
        self::$cache_block_executed[$this->id]['treatment_end'] = $date->format('Y-m-d H:i:s');
        self::$cache_block_executed[$this->id]['result'] = $result_code;
        self::$cache_block_executed[$this->id]['memory'] = $memory;
        self::$cache_block_executed[$this->id]['error'] = $error;
    }

    public function run($identifier = null)
    {
        $memory_start = memory_get_usage();
        $date = new DateTime();
        $this->setBlockExecuting();

        $result_code = 2;
        $error_message = null;

        error_log("$identifier - EXEC BLOCK");

        Log::sys(
            $this->id,
            $this->id_advancedimporter_flow,
            "Start processing block {$this->id} : {$this->callback}"
        );

        try {
            list($class, $method) = explode('::', $this->callback);
            $block_value = null;
            if (!empty($this->block)) {
                $block_value = Tools::jsonDecode($this->block);
            }

            $constants = get_defined_constants(true);

            if (json_last_error() !== JSON_ERROR_NONE && $json_error = json_last_error_msg()) {
                throw new Exception("JSON Error : $json_error");
            }

            if (!class_exists($class)) {
                $file_to_include = _PS_MODULE_DIR_.'advancedimporter/callbacks/'.Tools::strtolower($class).'.php';
                if (!file_exists($file_to_include)) {
                    throw new Exception("Class $class not found");
                }

                include $file_to_include;
            }

            // For retrocompatibility with PHP 5.2, instanciate de class
            //$obj = new $class();

            if (property_exists($class, 'id_shop')) {
                // We link the class to the block in order to allow class to log with the block id
                //$obj::$id_shop = $this->id_shop;

                //After PHP 5.2, this syntax would be better
                $class::$id_shop = $this->id_shop;
            }
            if (property_exists($class, 'id_advancedimporter_block')) {
                // We link the class to the block in order to allow class to log with the block id
                //$obj::$id_advancedimporter_block = $this->id;

                //After PHP 5.2, this syntax would be better
                $class::$id_advancedimporter_block = $this->id;
            }

            $class::$id_advancedimporter_flow = $this->id_advancedimporter_flow;

            //call_user_func(array($class, $method), $block_value);

            //After PHP 5.2, this syntax would be better

            // Start a transaction to rollback if something goes wrong
            Db::getInstance()->execute('START TRANSACTION');
            $class::$method($block_value);
            // All is ok, commit in DB
            Db::getInstance()->execute('COMMIT');

            Log::success(
                $this->id,
                $this->id_advancedimporter_flow,
                "Block {$this->id} processed"
            );
        } catch (Exception $e) {
            Db::getInstance()->execute('ROLLBACK');
            $result_code = -1;
            $error_message = $e->getMessage();
            Log::error(
                $this->id,
                $this->id_advancedimporter_flow,
                "Error durring processing block {$this->id}: $error_message"
            );
        }
        Log::sys(
            $this->id,
            $this->id_advancedimporter_flow,
            "End processing block {$this->id}"
        );
        self::setBlockExecuted(
            memory_get_usage() - $memory_start,
            $result_code,
            $error_message
        );
    }

    public function delete()
    {
        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'advancedimporter_log`
            WHERE id_advancedimporter_block = '.(int)$this->id
        );

        return parent::delete();
    }
}
