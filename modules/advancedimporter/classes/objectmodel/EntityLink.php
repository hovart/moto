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

class EntityLink extends ObjectModel
{
    /** @var string */
    public $table_name;

    /** @var string */
    public $entity_left_name;

    /** @var string */
    public $entity_right_name;

    /** @var int */
    public $id_entity_left;

    /** @var int */
    public $id_entity_right;

    public static $definition = array(
        'fields' => array(
            'table_name' => array('type' => self::TYPE_STRING),
            'entity_left_name' => array('type' => self::TYPE_STRING),
            'entity_right_name' => array('type' => self::TYPE_STRING),
            'id_entity_left' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'id_entity_right' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        if (empty($id)) {
            return $this;
        }

        list($this->id_entity_left, $this->id_entity_right) = explode('-', $id);

        return $this;
    }

    public function validate()
    {
        if (empty($this->table_name)) {
            throw new Exception('Missing table name');
        }
        if (empty($this->entity_left_name)) {
            throw new Exception('Missing entity left name');
        }
        if (empty($this->entity_right_name)) {
            throw new Exception('Missing entity right name');
        }
        if (empty($this->id_entity_left)) {
            throw new Exception('Missing entity left id');
        }
        if (empty($this->id_entity_right)) {
            throw new Exception('Missing entity right id');
        }
    }

    public function save($null_values = false, $autodate = true)
    {
        $this->validate();

        $this->id = $this->id_entity_left.'-'.$this->id_entity_right;

        // Remove link
        $this->delete();

        // Create link
        $row = array(
            $this->entity_left_name => (int) $this->id_entity_left,
            $this->entity_right_name => (int) $this->id_entity_right,
        );
        Db::getInstance()->insert($this->table_name, $row);
    }

    public function delete()
    {
        $this->validate();

        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.$this->table_name.'`
            WHERE `'.$this->entity_left_name.'` = '.(int) $this->id_entity_left
            .' AND `'.$this->entity_right_name.'` = '.(int) $this->id_entity_right
        );
    }
}
