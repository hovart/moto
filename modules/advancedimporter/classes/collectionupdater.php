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

/**
 * Create or Update a collection of items
 *
 * @TODO Manage shop fields
 * @TODO Manage lang fields
 */
class CollectionUpdater
{
    protected $collection_save = array();
    protected $collection_update = array();

    protected $shops = array();
    protected $table;
    protected $primary;
    protected $fields = array();
    protected $shop_fields = array();
    protected $multilangshop = array();
    protected $lang_fields = array();

    public function __construct($type, $shops = null)
    {
        if (is_null($shops)) {
            $this->shops = array(Context::getContext()->shop->id);
        } elseif (!is_array($shops)) {
            $this->shops = array($shops);
        } else {
            $this->shops = $shops;
        }
        $this->table = $type::$definition['table'];
        $this->primary = $type::$definition['primary'];
        $this->multilangshop = isset($type::$definition['multilang_shop']) && $type::$definition['multilang_shop'];
        foreach ($type::$definition['fields'] as $field => $value) {
            if (isset($value['shop']) && $value['shop']) {
                $this->shop_fields[] = $field;
            } elseif (isset($value['lang']) && $value['lang']) {
                $this->lang_fields[] = $field;
            } else {
                $this->fields[] = $field;
            }
        }

        $this->collection_save = array(
            'values' => array(),
            'lang_values' => array(),
            'shop_values' => array(),
        );

        $this->collection_update = array(
            'values' => array(),
            'lang_values' => array(),
            'shop_values' => array(),
        );
    }

    /**
     * Format and protect value for SQL querie
     *
     * @param mixed $value Value du protect
     * @return mixed Value protected
     */
    protected function formatValue($value)
    {
        if (is_null($value)) {
            return 'null';
        } elseif (is_int($value)) {
            return (int)$value;
        } elseif (is_float($value)) {
            return (float)$value;
        } elseif (is_bool($value)) {
            return (int)$value;
        } else {
            return "'".pSql($value, true)."'";
        }
    }

    /**
     * Add an item to the collection
     *
     * @param $item
     */
    public function addItem($item)
    {

        $values = array();
        foreach ($this->fields as $field) {
            $values[$field] = $this->formatValue($item->$field);
        }
        if (!empty($item->id)) {
            $this->collection_update['values'][$item->id] = $values;
        } else {
            $this->collection_save['values'][] = $values;
        }

        $values = array();
        foreach ($this->lang_fields as $field) {
            foreach ($item->$field as $id_lang => $value) {
                $values[$field] = $this->formatValue($value);
                $values['id_lang'] = (int)$id_lang;
            }
        }
        if (!empty($item->id)) {
            $this->collection_update['lang_values'][$item->id] = $values;
        } else {
            $this->collection_save['lang_values'][] = $values;
        }

        $values = array();
        foreach ($this->shop_fields as $field) {
            $values[$field] = $this->formatValue($item->$field);
        }
        if (!empty($item->{$this->primary})) {
            $this->collection_update['shop_values'][$item->id] = $values;
        } else {
            $this->collection_save['shop_values'][] = $values;
        }

    }

    /**
     * Save in database and flush data
     *
     * @TODO Support lang and shop tables
     */
    public function flush()
    {
        $sqlDatas = array();
        foreach ($this->collection_update['values'] as $id => $row) {
            $row[] = $id;
            $sqlDatas[] = '('.implode(',', $row).')';
        }
        foreach ($this->collection_save['values'] as $id => $row) {
            $row[] = 'null';
            $sqlDatas[] = '('.implode(',', $row).')';
        }

        // Delete all entity
        $deleteQuery = 'DELETE FROM `'._DB_PREFIX_.bqSql($this->table).'`
            WHERE `'.bqSQl($this->primary).'`
                IN ('.implode(',', array_keys($this->collection_update['values'])).')';

        if (count($this->collection_update['values'])) {
            Db::getInstance()->execute($deleteQuery);
        }

        $protectedFields = array_map(
            function ($value) {
                return '`'.bqSql($value).'`';
            },
            $this->fields
        );
        $sqlQuery = 'INSERT INTO `'._DB_PREFIX_.bqSql($this->table).'`
            ('.implode(',', $protectedFields).',`'.bqSql($this->primary).'`)
            VALUES '.implode(',', $sqlDatas);

        if (count($sqlDatas)) {
            Db::getInstance()->execute($sqlQuery);
        }

        $this->collection_save = array(
            'values' => array(),
            'lang_values' => array(),
            'shop_values' => array(),
        );

        $this->collection_update = array(
            'values' => array(),
            'lang_values' => array(),
            'shop_values' => array(),
        );
    }
}
