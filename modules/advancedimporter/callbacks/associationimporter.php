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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/objectimporterabstract.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/ean13.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/reference.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/externalreference.php';

class AssociationImporter extends ObjectImporterAbstract
{
    /**
     * Import products.
     */
    public static function execute($block)
    {
        self::loadFieldsUsingExternalReference($block);
        self::loadFieldsUsingCollection($block);

        if ($block->ean13) {
            try {
                $ean = new AdvancedImporterEan13($block->ean13);
                $block->productid = $ean->id_product;
            } catch (Exception $e) {
                $a = 'Do nothing'; // Silent exception, no products with this reference
                $a .= 'Do nothing more';
            }
        } elseif ($block->reference) {
            try {
                $reference = new AdvancedImporterReference($block->reference);
                $block->productid = $reference->id_product;
            } catch (Exception $e) {
                $a = 'Do nothing'; // Silent exception, no products with this reference
            }
        }

        if (!empty($block->external_reference)) {
            try {
                $external_reference = ExternalReference::getByExternalReference(
                    (string) $block->external_reference,
                    'Product'
                );

                if (!empty($block->id) && $block->id != $external_reference->id_object) {
                    throw new Exception(
                        "External Reference \"{$block->external_reference}\" already exist for the id "
                        ."{$external_reference->id_object}."
                    );
                }

                $block->productid = $external_reference->id_object;
                $product = $external_reference->getInstance();
            } catch (ExternalReferenceException $e) {
                Log::notice(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "External Reference {$block->external_reference} is unknow"
                );
                // We do nothing : the product is a new product
                $external_reference = new ExternalReference();
            }
        }

        if (!isset($product)) {
            $product = new Product($block->productid);
        }

        $categories = array();
        $category_list = array();
        if ($block->mode == 'add') {
            foreach ($product->getWsCategories() as $cat) {
                $categories[$cat['id']] = $cat;
                $category_list[] = $cat['id'];
            }
        }

        $default_category = null;
        foreach ($block->categories as $category) {
            if (isset($category->external_reference)) {
                try {
                    $external_reference = ExternalReference::getByExternalReference(
                        (string) $category->external_reference,
                        'Category'
                    );

                    $category->id = $external_reference->id_object;
                } catch (ExternalReferenceException $e) {
                    throw new Exception("External Reference {$category->external_reference} for a category is unknow");
                }
            }
            if ($category->is_default) {
                $default_category = $category->id;
            }

            $categories[$category->id] = array('id' => $category->id);
            $category_list[] = $category->id;
        }
        $category_list = array_unique($category_list);

        $product->setWsCategories($categories);
        if (!is_null($default_category)) {
            $product->id_category_default = $default_category;
            $product->save();
        }

        Log::sys(
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            'Associate categories '.implode(', ', $category_list).' to product "{$product->id}"'
        );
    }
}
