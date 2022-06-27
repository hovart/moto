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

require_once dirname(__FILE__).'/../../../config/config.inc.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/block.php';
require_once _PS_MODULE_DIR_.'advancedimporter/tests/abstract.php';

class StockTest extends TestAbstract
{
    public function testStock1()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('attribute-1.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-10.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('stock-1.xml');
        $this->import('stock');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('demo-recto-color', 'AttributeGroup');
        $this->cleanRef('demo-recto-color-gray', 'Attribute');
        $this->cleanRef('demo-recto-color-blue', 'Attribute');
        $this->cleanRef('demo-recto-color-red', 'Attribute');
        $this->cleanRef('combination-1', 'Combination');
        $this->cleanRef('combination-2', 'Combination');
    }

    public function testStock2()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('attribute-1.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-10.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('stock-2.xml');
        $this->import('stock');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('demo-recto-color', 'AttributeGroup');
        $this->cleanRef('demo-recto-color-gray', 'Attribute');
        $this->cleanRef('demo-recto-color-blue', 'Attribute');
        $this->cleanRef('demo-recto-color-red', 'Attribute');
        $this->cleanRef('combination-1', 'Combination');
        $this->cleanRef('combination-2', 'Combination');
    }

    public function testStock3()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('attribute-1.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-10.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('stock-3.xml');
        $this->import('stock');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('demo-recto-color', 'AttributeGroup');
        $this->cleanRef('demo-recto-color-gray', 'Attribute');
        $this->cleanRef('demo-recto-color-blue', 'Attribute');
        $this->cleanRef('demo-recto-color-red', 'Attribute');
        $this->cleanRef('combination-1', 'Combination');
        $this->cleanRef('combination-2', 'Combination');
    }

    public function testStock4()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('attribute-1.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-10.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('stock-4.xml');
        $this->import('stock');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('demo-recto-color', 'AttributeGroup');
        $this->cleanRef('demo-recto-color-gray', 'Attribute');
        $this->cleanRef('demo-recto-color-blue', 'Attribute');
        $this->cleanRef('demo-recto-color-red', 'Attribute');
        $this->cleanRef('combination-1', 'Combination');
        $this->cleanRef('combination-2', 'Combination');
    }

    public function testStock5()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('attribute-1.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-10.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('stock-5.xml');
        $this->import('stock');
        $this->execBlocks();
        $this->checkErrors();

        $external_reference = ExternalReference::getByExternalReference('product-demo-1', 'Product');
        $id_product = $external_reference->id_object;

        $external_reference = ExternalReference::getByExternalReference('combination-1', 'Combination');
        $id_product_attribute = $external_reference->id_object;

        $stock_id = StockAvailable::getStockAvailableIdByProductId($id_product, $id_product_attribute);
        $stock_available = new StockAvailable($stock_id);
        $stock = $stock_available->quantity;

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('demo-recto-color', 'AttributeGroup');
        $this->cleanRef('demo-recto-color-gray', 'Attribute');
        $this->cleanRef('demo-recto-color-blue', 'Attribute');
        $this->cleanRef('demo-recto-color-red', 'Attribute');
        $this->cleanRef('combination-1', 'Combination');
        $this->cleanRef('combination-2', 'Combination');

        $this->assertEquals(10, $stock);
    }
}
