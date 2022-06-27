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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/externalreferenceexception.php';

class ExternalReferenceSupplier extends ObjectModel
{
    public $id_object;
    public $object_type;
    public $external_reference;
    public $to_delete = 0;
    public $date_add;
    public $date_upd;

    public static $definition = array(
        'table' => 'advancedimporter_externalreference_supplier',
        'primary' => 'id_advancedimporter_externalreference_supplier',
        'fields' => array(
            'object_type' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'external_reference' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'supplier' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'to_delete' => array('type' => self::TYPE_BOOL),
            'date_add' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
        ),
    );

    public static function getByExternalReference($external_reference, $object_type)
    {
        $query = new DbQuery();
        $query->select('er.id_advancedimporter_externalreference_supplier');
        $query->from('advancedimporter_externalreference_supplier', 'er');
        $query->where('er.external_reference = \''.pSQL(trim($external_reference)).'\'');
        $query->where('er.object_type = \''.pSQL($object_type).'\'');

        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if (!$id) {
            $obj = new ExternalReferenceSupplier();
            $obj->external_reference = $external_reference;
            $obj->object_type = $object_type;
            return $obj;
        }

        return new self($id);
    }

    public static function getToDeleteFromSupplier($supplier, $object_type)
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('ExternalReferenceSupplier');
        } else {
            $collection = new Collection('ExternalReferenceSupplier');
        }
        $collection->where('supplier', '=', $supplier);
        $collection->where('to_delete', '=', true);
        $collection->where('object_type', '=', $object_type);

        return $collection;
    }

    public static function flagToDeleteSupplier($supplier, $object_type)
    {
        Db::getInstance(_PS_USE_SQL_SLAVE_)->execute(
            'UPDATE `'._DB_PREFIX_.'advancedimporter_externalreference_supplier`
            SET to_delete = 1
            WHERE supplier = "'.pSql($supplier).'"
            AND object_type = "'.pSql($object_type).'"'
        );
    }
}
