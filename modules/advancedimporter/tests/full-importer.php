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

class FullImporterTest extends TestAbstract
{
    public function testProduct1()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-1.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('demo-1', 'Product');
    }

    public function testProduct2()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-2.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
    }

    public function testProduct3()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-3.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
    }

    public function testProduct4()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-4.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('product-demo-2', 'Product');
    }

    public function testProduct5()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('object-2.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-5.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('demo-1', 'Product');
    }

    public function testProduct6()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-6.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('specific-price-1', 'specificPrice');
    }

    public function testProduct7()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-7.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('demo-1', 'Product');
    }

    public function testProduct8()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-8.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->import('association');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('demo-1', 'Category');
    }

    public function testProduct9()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('object-3.xml');
        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->copy('product-9.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->cleanRef('feature-test', 'Feature');
        $this->cleanRef('feature-value-test', 'FeatureValue');
    }

    public function testProduct10()
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

        $this->cleanRef('product-demo-1', 'Product');
        $this->cleanRef('demo-recto-color', 'AttributeGroup');
        $this->cleanRef('demo-recto-color-gray', 'Attribute');
        $this->cleanRef('demo-recto-color-blue', 'Attribute');
        $this->cleanRef('demo-recto-color-red', 'Attribute');
        $this->cleanRef('combination-1', 'Combination');
        $this->cleanRef('combination-2', 'Combination');
    }

    public function testProduct11()
    {
        $this->cleanQueue();
        $this->cleanDb();
        $this->copy('product-11.xml');
        $this->import('product');
        $this->execBlocks();
        $this->checkErrors();

        $this->import('object');
        $this->execBlocks();
        $this->checkErrors();

        $this->import('product');
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

    public function testStocks()
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
        $this->copy('stock-2.xml');
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
}
