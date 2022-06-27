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

class OrderInvoicePayment extends ObjectModel
{
    /** @var String Type */
    public $type;

    /** @var int Id order invoice */
    public $id_order_invoice;

    /** @var int Id payment */
    public $id_order_payment;

    /** @var int Id order*/
    public $id_order;

    public $table = 'order_invoice_payment';

    public $ids = array(
        'id_order_invoice',
        'id_order_payment',
    );

    public static $definition = array(
        'fields' => array(
            'id_order_invoice' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_order_payment' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'id_order' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        if (empty($id)) {
            return $this;
        }

        $id_values = explode('-', $id);
        foreach ($this->ids as $position => $key) {
            $this->$key = $id_values[$position];
        }

        $this->def = ObjectModel::getDefinition($this);

        return $this;
    }

    public function save($null_values = false, $autodate = true)
    {
        $this->validateFields();
        $this->validateFieldsLang();

        $this->id = $this->id_order_invoice.'-'.$this->id_tax;

        // Remove row
        $this->delete();

        // Create row
        $row = array();
        foreach (self::$definition['fields'] as $field => $details) {
            $row[$field] = $this->$field;
        }
        Db::getInstance()->insert($this->table, $row);
    }

    public function delete()
    {
        foreach ($this->ids as $key) {
            if (empty($this->$key)) {
                throw new Exception('Missing '.$key);
            }
        }

        $where = array();
        foreach ($this->ids as $key) {
            $where[] = '`'.$key.'` = '.(int) $this->$key;
        }

        Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.$this->table.'`
			WHERE '.implode(' AND ', $where));
    }
}
