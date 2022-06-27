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

class CustomerGroup extends ObjectModel
{
    /** @var int Default group ID */
    public $id_customer;

    /** @var int Default group ID */
    public $id_group;

    public static $definition = array(
        'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_group' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        if (empty($id)) {
            return $this;
        }

        list($this->id_group, $this->id_customer) = explode('-', $id);

        return $this;
    }

    public function save($null_values = false, $autodate = true)
    {
        if (empty($this->id_customer)) {
            throw new Exception('Missing customer id');
        }
        if (empty($this->id_group)) {
            throw new Exception('Missing group id');
        }

        $this->id = $this->id_group.'-'.$this->id_customer;

        // Remove link
        $this->delete();

        // Create link
        $row = array('id_customer' => (int) $this->id_customer, 'id_group' => (int) $this->id_group);
        Db::getInstance()->insert('customer_group', $row);
    }

    public function delete()
    {
        if (empty($this->id_customer)) {
            throw new Exception('Missing customer id');
        }
        if (empty($this->id_group)) {
            throw new Exception('Missing group id');
        }

        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'customer_group`
            WHERE `id_customer` = '.(int) $this->id_customer
            .' AND `id_group` = '.(int) $this->id_group
        );
    }
}
