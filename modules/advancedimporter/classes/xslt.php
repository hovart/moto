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

class Xslt extends ObjectModel
{
    public $date_add;
    public $date_upd;
    public $xml;
    public $use_tpl;
    public $xpath_query;
    public $item_root;
    public $nodes = array();
    public $schema = array();
    public $nodes_serialized;
    public $schema_serialized;

    public static $definition = array(
        'table' => 'advancedimporter_xslt',
        'primary' => 'id_advancedimporter_xslt',
        'fields' => array(
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDateFormat'),
            'use_tpl' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'xml' => array('type' => self::TYPE_HTML, 'required' => true),
            'xpath_query' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'item_root' => array('type' => self::TYPE_STRING, 'required' => false, 'size' => 255),
            'nodes_serialized' => array('type' => self::TYPE_STRING, 'required' => false),
            'schema_serialized' => array('type' => self::TYPE_STRING, 'required' => false),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        parent::__construct($id, $id_lang, $id_shop);

        $this->nodes = Tools::jsonDecode($this->nodes_serialized, true);
        $this->schema = Tools::jsonDecode($this->schema_serialized, true);

        if (empty($this->nodes)) {
            $this->nodes = array();
        }

        if (empty($this->schema)) {
            $this->schema = array();
        }
    }

    public function save($null_values = false, $auto_date = true)
    {
        $this->nodes_serialized = Tools::jsonEncode($this->nodes);
        $this->schema_serialized = Tools::jsonEncode($this->schema);
        return parent::save($null_values, $auto_date);
    }
}
