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
require_once _PS_MODULE_DIR_.'advancedimporter/classes/log.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/importflowsinterface.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/flow.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xslt.php';
require_once _PS_MODULE_DIR_.'advancedimporter/importer/productflowsimport.php';
require_once _PS_MODULE_DIR_.'advancedimporter/importer/objectflowsimport.php';

class ProductTest extends PHPUnit_Framework_TestCase
{
    public function testProduct1()
    {
        $importer = new ProductImporterTest();
        $block = $importer->importFile('product-1.xml');

        $product = $this->getLastModifiedObject('product', 'Product');

        $this->assertEquals(20, round($product->price, 6));

        $external_reference = ExternalReference::getByExternalReference('demo-1', 'Product');
        $product_id = $external_reference->id_object;

        $this->assertEquals($product_id, $product->id);

        // Reset data
        $product->delete();
        $external_reference->delete();
    }

    public function testProduct3()
    {
        $importer = new ProductImporterTest();
        $block = $importer->importFile('product-3.xml');

        $product = $this->getLastModifiedObject('product', 'Product');

        $external_reference = ExternalReference::getByExternalReference('product-demo-1', 'Product');
        $product_id = $external_reference->id_object;

        $this->assertEquals($product_id, $product->id);

        // Reset data
        $product->delete();
        $external_reference->delete();
    }

    public function testProduct5()
    {
        $importer = new ObjectImporterTest();
        $block = $importer->importFile('object-2.xml');
        $tax_rules_group = $this->getLastModifiedObject('tax_rules_group', 'TaxRulesGroup');
        $external_reference_tax = ExternalReference::getByExternalReference('tax-1', 'TaxRulesGroup');
        $tax_rules_group_id = $external_reference_tax->id_object;
        $this->assertEquals($tax_rules_group_id, $tax_rules_group->id);

        $importer = new ProductImporterTest();
        $block = $importer->importFile('product-5.xml');

        $product = $this->getLastModifiedObject('product', 'Product');

        $external_reference_product = ExternalReference::getByExternalReference('demo-1', 'Product');
        $product_id = $external_reference_product->id_object;

        $this->assertEquals($product_id, $product->id);

        // Reset data
        $tax_rules_group->delete();
        $product->delete();
        $external_reference_tax->delete();
        $external_reference_product->delete();
    }

    private function getLastModifiedObject($table, $type)
    {
        switch ($table) {
            case 'tax_rules_group':
                $sql = 'SELECT max(id_'.bqSql($table).') FROM `'._DB_PREFIX_.bqSql($table).'`';
                break;
            default:
                $sql = 'SELECT id_'.bqSql($table).' FROM `'._DB_PREFIX_.bqSql($table).'` order by date_upd desc';
        }
        $object_id = Db::getInstance()->getValue($sql);

        return new $type($object_id);
    }
}

class ProductImporterTest extends ProductFlowsImport
{
    public function importFile($file)
    {
        $this->flow = new Flow();
        $this->flow->id = 1;
        $file_content = Tools::file_get_contents(_PS_MODULE_DIR_.'advancedimporter/tests/files/'.$file);
        $this->last_xml_loaded = new SimpleXmlElement($file_content);
        $this->import_block = true;

        return $this->export();
    }
}

class ObjectImporterTest extends ObjectFlowsImport
{
    public function importFile($file)
    {
        $this->flow = new Flow();
        $this->flow->id = 1;
        $file_content = Tools::file_get_contents(_PS_MODULE_DIR_.'advancedimporter/tests/files/'.$file);
        $this->last_xml_loaded = new SimpleXmlElement($file_content);
        $this->import_block = true;

        return $this->export();
    }
}
