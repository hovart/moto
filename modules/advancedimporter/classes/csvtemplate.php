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

class CsvTemplate extends ObjectModel
{
    public $date_add;
    public $date_upd;
    public $template;
    public $data = '';
    public $advanced_mode = 0;
    public $filepath = '*.csv';
    public $roottag = 'products';
    public $ignore_first_line = 1;
    public $enclosure = '"';
    public $delimiter = ',';
    public $escape = '\\';
    public $flow_type = 'product.product';
    public $encoding = 'UTF-8';
    public $nodes = array();
    public $schema = array();
    public $nodes_serialized;
    public $schema_serialized;

    public static $definition = array(
        'table' => 'advancedimporter_csv_template',
        'primary' => 'id_advancedimporter_csv_template',
        'fields' => array(
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'template' => array('type' => self::TYPE_HTML, 'required' => true),
            'data' => array('type' => self::TYPE_STRING, 'required' => false),
            'filepath' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'roottag' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 30),
            'enclosure' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 1),
            'delimiter' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 1),
            'escape' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 1),
            'encoding' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 10),
            'flow_type' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 32),
            'ignore_first_line' => array('type' => self::TYPE_INT, 'required' => true),
            'advanced_mode' => array('type' => self::TYPE_INT, 'required' => true),
            'nodes_serialized' => array('type' => self::TYPE_STRING, 'required' => false),
            'schema_serialized' => array('type' => self::TYPE_STRING, 'required' => false),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);

        $this->nodes = $this->unserialize($this->nodes_serialized, true);
        $this->schema = $this->unserialize($this->schema_serialized, true);

        if (empty($this->nodes)) {
            $this->nodes = array();
        }

        if (empty($this->schema)) {
            $this->schema = array();
        }
    }

    public function save($null_values = false, $auto_date = true)
    {
        $this->nodes_serialized = serialize($this->nodes);
        $this->schema_serialized = serialize($this->schema);
        return parent::save($null_values, $auto_date);
    }

    protected function unserialize($string)
    {
        $string2 = preg_replace_callback(
            '!s:(\d+):"(.*?)";!s',
            function ($m) {
                $len = Tools::strlen($m[2]);
                $result = "s:$len:\"{$m[2]}\";";
                return $result;
            },
            $string
        );
        return Tools::unserialize($string2);
    }
}
