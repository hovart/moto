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

class ExternalReference extends ObjectModel
{
    public $id_object;
    public $object_type;
    public $external_reference;
    public $date_add;
    public $date_upd;
    public $instance = null;

    public static $definition = array(
        'table' => 'advancedimporter_externalreference',
        'primary' => 'id_advancedimporter_externalreference',
        'fields' => array(
            'id_object' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'object_type' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'external_reference' => array('type' => self::TYPE_STRING, 'required' => true, 'size' => 255),
            'date_add' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
            'date_upd' => array('type' => self::TYPE_DATE, 'required' => true, 'validate' => 'isDateFormat'),
        ),
    );

    public function getInstance()
    {
        if (is_null($this->instance)) {
            $class = $this->object_type;
            $this->instance = new $class($this->id_object);
        }

        return $this->instance;
    }

    public static function getByExternalReference($external_reference, $object_type)
    {
        $query = new DbQuery();
        $query->select('er.id_advancedimporter_externalreference');
        $query->from('advancedimporter_externalreference', 'er');
        $query->where('er.external_reference = \''.pSQL(trim($external_reference)).'\'');
        $query->where('er.object_type = \''.pSQL($object_type).'\'');

        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if ($id) {
            $reference = new self($id);
            if ($reference->getInstance()->id) {
                return $reference;
            } else {
                // The object do not exists
                // the reference must be removed
                $reference->delete();
            }
        }

        throw new ExternalReferenceException(
            'Unknow External Reference "'.$external_reference.'" of object type "'.$object_type.'"'
        );
    }
}
