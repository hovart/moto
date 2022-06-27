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

class ProductPack extends ObjectModel
{
    /** @var int Product Pack */
    public $id_product_pack;

    /** @var int Product ID */
    public $id_product_item;

    /** @var int Attribute ID */
    public $id_product_attribute_item;

    /** @var int Default group ID */
    public $quantity;

    public static $definition = array(
        'table' => 'pack',
        'fields' => array(
            'id_product_pack' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
                'required' => true,
            ),
            'id_product_item' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
                'required' => true,
            ),
            'id_product_attribute_item' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ),
            'quantity' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
        ),
    );

    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        if (empty($id)) {
            return $this;
        }

        list($this->id_product_pack, $this->id_product_item, $this->id_product_attribute_item) = explode('-', $id);

        return $this;
    }

    public function save($null_values = false, $autodate = true)
    {
        if (empty($this->id_product_pack)) {
            throw new Exception('Missing customer id');
        }
        if (empty($this->id_product_item)) {
            throw new Exception('Missing group id');
        }

        $this->id = (int)$this->id_product_pack.'-'
        .(int)$this->id_product_item
        .'-'.(int)$this->id_product_attribute_item;

        // Remove link
        $this->delete(false);

        // Create link
        $row = array(
            'id_product_pack' => (int) $this->id_product_pack,
            'id_product_item' => (int) $this->id_product_item,
            'id_product_attribute_item' => (int) $this->id_product_attribute_item,
        );
        Db::getInstance()->insert('pack', $row);

        Configuration::updateGlobalValue('PS_PACK_FEATURE_ACTIVE', '1');
    }

    public function delete($updateFeatureActive = true)
    {
        if (empty($this->id_product_pack)) {
            throw new Exception('Missing customer id');
        }
        if (empty($this->id_product_item)) {
            throw new Exception('Missing group id');
        }

        Db::getInstance()->execute(
            'DELETE FROM `'._DB_PREFIX_.'pack`
            WHERE `id_product_pack` = '.(int) $this->id_product_pack
            .' AND `id_product_item` = '.(int) $this->id_product_item
            .' AND `id_product_attribute_item` = '.(int) $this->id_product_attribute_item
        );

        if ($updateFeatureActive) {
            Configuration::updateGlobalValue('PS_PACK_FEATURE_ACTIVE', Pack::isCurrentlyUsed());
        }
    }
}
