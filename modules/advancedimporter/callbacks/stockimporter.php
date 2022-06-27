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
require_once _PS_MODULE_DIR_.'advancedimporter/classes/supplierreference.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/externalreference.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/history.php';

class StockImporter extends ObjectImporterAbstract
{
    /**
     * Import products.
     */
    public static function execute($block)
    {
        self::loadFieldsUsingExternalReference($block);
        self::loadFieldsUsingCollection($block);

        if (isset($block->product_external_reference)) {
            try {
                $external_reference = ExternalReference::getByExternalReference(
                    (string) $block->product_external_reference,
                    'Product'
                );

                $block->product = $external_reference->id_object;
            } catch (ExternalReferenceException $e) {
                throw new Exception("External Reference {$block->product_external_reference} for a product is unknow");
            }
        } elseif (!empty($block->supplier_reference)) {
            $reference = new AdvancedImporterSupplierReference($block->supplier_reference);
            $block->product = $reference->id_product;

            if ($reference->id_product_attribute) {
                $block->combination = $reference->id_product_attribute;
            }
        } elseif (!empty($block->ean13)) {
            $ean = new AdvancedImporterEan13($block->ean13);
            $block->product = $ean->id_product;

            if ($ean->id_product_attribute) {
                $block->combination = $ean->id_product_attribute;
            }
        } elseif (!empty($block->reference)) {
            $reference = new AdvancedImporterReference($block->reference);
            $block->product = $reference->id_product;

            if ($reference->id_product_attribute) {
                $block->combination = $reference->id_product_attribute;
            }
        }

        if (isset($block->combination_external_reference)) {
            try {
                $external_reference = ExternalReference::getByExternalReference(
                    (string) $block->combination_external_reference,
                    'Combination'
                );

                $block->combination = $external_reference->id_object;
            } catch (ExternalReferenceException $e) {
                throw new Exception(
                    "External Reference {$block->combination_external_reference} "
                    ."for a combination is unknow"
                );
            }
        }

        // If the combination is set alone
        if ((int) (string) $block->product == 0) {
            $combination = new Combination((int) (string) $block->combination);
            $block->product = $combination->id_product;
        }

        if (empty($block->warehouse)) {
            self::importStock($block);
        } else {
            self::importAdvancedStock($block);
        }

        AIHistory::create(
            'stock',
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            'Product',
            $block->product,
            null
        );

        Log::sys(
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            "Quantity of product {$block->product}({$block->combination}) updated"
        );
    }

    protected static function importStock($block)
    {
        if ($block->mode == 'set') {
            StockAvailable::setQuantity(
                (int) (string) $block->product,
                (int) (string) $block->combination,
                (int) (string) $block->quantity,
                self::getShopList($block)
            );
        } else {
            StockAvailable::updateQuantity(
                (int) (string) $block->product,
                (int) (string) $block->combination,
                (int) (string) $block->quantity,
                self::getShopList($block)
            );
        }

        if ((int) (string) $block->sellable_out_of_stock != 2) {
            StockAvailable::setProductOutOfStock(
                (int) (string) $block->product,
                (int) (string) $block->sellable_out_of_stock,
                null,
                (int) (string) $block->combination
            );
        }
    }

    protected static function importAdvancedStock($block)
    {
        $warehouse = new Warehouse((int) (string) $block->warehouse);

        $reason = (int) (string) $block->reason;
        if ($reason <= 0 || !StockMvtReason::exists($reason)) {
            throw new Exception('The reason is not valid.');
        }

        if (!empty($block->price) && !empty($block->currency)
            && ((int) (string) $block->currency) != $warehouse->id_currency
        ) {
            // First convert price to the default currency
            $price_converted_to_default_currency = Tools::convertPrice(
                (float) (string) $block->price,
                (int) (string) $block->currency,
                false
            );

            // Convert the new price from default currency to needed currency
            $block->price = Tools::convertPrice($price_converted_to_default_currency, $warehouse->id_currency, true);
        }

        foreach (self::getShopList($block) as $shop) {
            StockAvailable::setProductDependsOnStock(
                (int) (string) $block->product,
                1,
                $shop,
                (int) (string) $block->combination
            );
        }

        // add stock
        $stock_manager = StockManagerFactory::getManager();

        $employee = new Employee((int) (string) $block->employee);

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'product_shop`
            SET `advanced_stock_management`= 1
            WHERE id_product='.(int) (string) $block->product
        );

        Db::getInstance()->execute(
            'UPDATE `'._DB_PREFIX_.'product`
            SET `advanced_stock_management`= 1
            WHERE id_product='.(int) (string) $block->product
        );

        $quantity = (int) (string) $block->quantity;

        if ($block->mode == 'set') {
            $current_quantity = StockAvailable::getQuantityAvailableByProduct(
                (int) (string) $block->product,
                (int) (string) $block->combination
            );

            $quantity = $quantity - $current_quantity;

            if ($quantity == 0) {
                Log::sys(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "Quantity of product {$block->product}({$block->combination}) already set"
                );
                return;
            }
        }

        $current_employee = Context::getContext()->employee;
        Context::getContext()->employee = $employee;

        if ($quantity >= 0) {
            if ($stock_manager->addProduct(
                (int) (string) $block->product,
                (int) (string) $block->combination,
                $warehouse,
                $quantity,
                (int) (string) $reason,
                isset($block->price) ? (float) (int) $block->price : null,
                (float) (bool) $block->usable,
                null,
                $employee
            )) {
                // Create warehouse_product_location entry if we add stock to a new warehouse
                $id_wpl = (int) WarehouseProductLocation::getIdByProductAndWarehouse(
                    (int) (string) $block->product,
                    (int) (string) $block->combination,
                    (int) (string) $block->warehouse
                );
                if (!$id_wpl) {
                    $wpl = new WarehouseProductLocation();
                    $wpl->id_product = (int) (string) $block->product;
                    $wpl->id_product_attribute = (int) (string) $block->combination;
                    $wpl->id_warehouse = (int) $warehouse->id;
                    $wpl->location = (string) $block->location;
                    $wpl->save();
                } elseif (isset($block->location)) {
                    $wpl = new WarehouseProductLocation($id_wpl);
                    $wpl->location = (string) $block->location;
                    $wpl->save();
                }

                StockAvailable::synchronize((int) (string) $block->product);

            } else {
                throw new Exception('An error occurred. No stock was added.');
            }
        } else {
            if ($stock_manager->removeProduct(
                (int) (string) $block->product,
                (int) (string) $block->combination,
                $warehouse,
                -$quantity,
                (int) (string) $reason,
                (float) (bool) $block->usable,
                null,
                null,
                $employee
            )) {
                // Create warehouse_product_location entry if we add stock to a new warehouse
                $id_wpl = (int) WarehouseProductLocation::getIdByProductAndWarehouse(
                    (int) (string) $block->product,
                    (int) (string) $block->combination,
                    (int) (string) $block->warehouse
                );
                if (!$id_wpl) {
                    $wpl = new WarehouseProductLocation();
                    $wpl->id_product = (int) (string) $block->product;
                    $wpl->id_product_attribute = (int) (string) $block->combination;
                    $wpl->id_warehouse = (int) $warehouse->id;
                    $wpl->location = (string) $block->location;
                    $wpl->save();
                } elseif (isset($block->location)) {
                    $wpl = new WarehouseProductLocation($id_wpl);
                    $wpl->location = (string) $block->location;
                    $wpl->save();
                }

                StockAvailable::synchronize((int) (string) $block->product);

            } else {
                throw new Exception('An error occurred. No stock was added.');
            }

            Context::getContext()->employee = $current_employee;
        }
    }
}
