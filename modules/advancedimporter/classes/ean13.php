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

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdvancedImporterEan13
{
    public $id_product;
    public $id_product_attribute = 0;

    public function __construct($ean13)
    {
        if (empty($ean13)) {
            return;
        }

        $res = self::getProductIdByEan13($ean13);

        if ($res === false) {
            throw new Exception('Unknow ean13 "'.$ean13.'"');
        }

        $this->id_product = $res['id_product'];
        $this->id_product_attribute = $res['id_product_attribute'];
    }

    /**
     * For a given ean13 reference, returns the corresponding id product and id combination.
     *
     * @param string $ean13
     *
     * @return array
     */
    public static function getProductIdByEan13($ean13)
    {
        $query = new DbQuery();
        $query->select('p.id_product');
        $query->from('product', 'p');
        $query->where('p.ean13 = \''.pSQL($ean13).'\'');

        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if ($id) {
            return array('id_product' => $id, 'id_product_attribute' => 0);
        }

        $query = new DbQuery();
        $query->select('c.id_product, c.id_product_attribute');
        $query->from('product_attribute', 'c');
        $query->where('c.ean13 = \''.pSQL($ean13).'\'');

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (isset($res[0])) {
            return array(
                'id_product' => $res[0]['id_product'],
                'id_product_attribute' => $res[0]['id_product_attribute'],
            );
        }

        return false;
    }
}
