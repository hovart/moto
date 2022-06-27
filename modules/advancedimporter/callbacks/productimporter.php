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

class ProductImporter extends ObjectImporterAbstract
{
    /**
     * Import products.
     */
    public static function execute($block)
    {
        self::loadFieldsUsingExternalReference($block);
        self::loadFieldsUsingCollection($block);

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

                $block->id = $external_reference->id_object;
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

        if (!isset($block->id) && isset($block->supplier_reference) && $block->supplier_reference->value) {
            try {
                $reference = new AdvancedImporterSupplierReference($block->supplier_reference->value);
                $block->id = $reference->id_product;
            } catch (Exception $e) {
                Log::notice(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "Reference {$block->supplier_reference->value} is unknow"
                );
                // We do nothing : the product is a new product
            }
        }

        if (!isset($block->id) && isset($block->ean13) && $block->ean13->value) {
            try {
                $ean = new AdvancedImporterEan13($block->ean13->value);
                $block->id = $ean->id_product;
            } catch (Exception $e) {
                Log::notice(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "Ean13 {$block->ean13->value} is unknow"
                );
                // We do nothing : the product is a new product
            }
        }

        if (!isset($block->id) && isset($block->reference) && $block->reference->value) {
            try {
                $reference = new AdvancedImporterReference($block->reference->value);
                $block->id = $reference->id_product;
            } catch (Exception $e) {
                Log::notice(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "Reference {$block->reference->value} is unknow"
                );
                // We do nothing : the product is a new product
            }
        }

        if (!isset($product)) {
            if (isset($block->id)) {
                if (empty($block->id)) {
                    throw new Exception('Id could not be empty. For new products id tag must be missing.');
                }

                $product = new Product($block->id);
            } else {
                $product = new Product();
            }
        }

        // Don't update product if update attribute is false
        if (!$block->update && $product->id) {
            if (!empty($block->external_reference)) {
                // Save external reference
                $external_reference->external_reference = (string) $block->external_reference;
                $external_reference->object_type = 'Product';
                $external_reference->id_object = $product->id;
                $external_reference->save();
            }
            return;
        }

        self::setFields($block, $product);

        if (isset($block->use_price_without_tax) && !$block->use_price_without_tax) {
            $taxes = 0;
            foreach ($block->tax_fields as $field) {
                $taxes += (float) $product->{$field};
            }

            if ($product->price && isset($block->price)) {
                $product->price -= Tools::ps_round(self::getTax($product->price, $product, $block) + $taxes, 6);
            }
            if ($product->wholesale_price && isset($block->wholesale_price)) {
                $product->wholesale_price -= Tools::ps_round(
                    self::getTax($product->wholesale_price, $product, $block),
                    6
                );
            }
        }

        if (isset($block->unit_price)) {
            $product->unit_price = (float) $block->unit_price;
        } elseif (isset($block->unit_price_ratio)) {
            $product->unit_price = $product->price / $product->unit_price_ratio;
        }

        if (empty($product->link_rewrite) && !empty($product->name)) {
            $link_rewrite = array();
            foreach ($product->name as $key => $name) {
                $link_rewrite[$key] = self::str2url($name);
            }
            $product->link_rewrite = $link_rewrite;
        }

        // Product validation
        $errors = array();
        $errors = self::validateFields($product);
        if (count($errors)) {
            throw new Exception('Some fields are missing or not valid: '.implode('; ', $errors));
        }

        // Fix bug on save in an other shop and date_add
        if (empty($product->date_add) || $product->date_add == '0000-00-00 00:00:00') {
            $product->date_add = date('Y-m-d H:i:s');
        }

        $product->save();

        if (!empty($block->external_reference)) {
            // Save external reference
            $external_reference->external_reference = (string) $block->external_reference;
            $external_reference->object_type = 'Product';
            $external_reference->id_object = $product->id;
            $external_reference->save();
        }

        if (isset($block->carriers)) {
            $product->setCarriers((array) $block->carriers);
        }

        self::execCategoryPath($block->categorypath, $product);

        if (isset($block->features)) {
            self::execFeatures($block->features, $product);
        }
        self::execProductImages($block, $product);
        self::execCombinations($block, $product);

        self::execBlock($block, $product);

        // Clean images from media directory
        self::cleanUploadedImages($block);

        AIHistory::create(
            'product',
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            'Product',
            $product->id,
            isset($block->external_reference) ? (string) $block->external_reference : null
        );

        Log::sys(
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            "Product {$product->id} imported"
        );
    }

    public static function getTax($price, $product, $block)
    {
        if (empty($product->id_tax_rules_group)) {
            return 0;
        }

        $tax_manager = TaxManagerFactory::getManager(
            self::getDefaultShop($block)->getAddress(),
            (int) $product->id_tax_rules_group
        );
        $tax_rate = $tax_manager->getTaxCalculator()->getTotalRate();

        return $price - 100 * $price / (100 + $tax_rate);
    }

    public static function execFeatures($features, $product)
    {
        $product->setWsProductFeatures(array()); // Delete of features of the product
        foreach ($features as $feature) {
            if (isset($feature->id)) {
                $feature_id = $feature->id;
            } elseif (isset($feature->external_reference)) {
                $external_reference = ExternalReference::getByExternalReference(
                    $feature->external_reference,
                    'Feature'
                );
                $feature_id = $external_reference->id_object;
            } else {
                $feature_id = self::getFeatureIdByName($feature->name);
            }

            if (isset($feature->id_value)) {
                $feature_value_id = $feature->id_value;
                if ($feature->custom) {
                    throw new Exception("Feature value cannot be an id if custom it's to 1.");
                }
            } elseif (isset($feature->external_reference_value)) {
                $external_reference = ExternalReference::getByExternalReference(
                    $feature->external_reference_value,
                    'FeatureValue'
                );
                $feature_value_id = $external_reference->id_object;
            } else {
                if ($feature->custom) {
                    $feature_value_id = null;
                } else {
                    $feature_value_id = self::getFeatureValueIdByName(
                        $feature->name_value,
                        $feature_id,
                        $feature->custom
                    );
                }
            }
            $feature_value_id = $product->addFeaturesToDB($feature_id, $feature_value_id, $feature->custom);
            if ($feature->custom) {
                $languages = Language::getLanguages(false);
                foreach ($languages as $language) {
                    $product->addFeaturesCustomToDB(
                        $feature_value_id,
                        $language['id_lang'],
                        $feature->name_value
                    );
                }
            }
        }
    }

    public static function getFeatureIdByName($feature_name)
    {
        if ($result = Db::getInstance()->getRow(
            'SELECT fl.id_feature, fs.id_shop
            FROM '._DB_PREFIX_.'feature_lang as fl
            LEFT JOIN '._DB_PREFIX_.'feature_shop as fs
            ON fs.id_feature = fl.id_feature
            AND fs.id_shop = '.(int)Context::getContext()->shop->id.'
            WHERE fl.name  = "'.pSql($feature_name).'"'
        )) {

            $id = $result['id_feature'];
            $shop = $result['id_shop'];

            if (!$shop) {
                $feature = new Feature($id);
                self::associateToShop($feature, Context::getContext()->shop->id);
            }

            return $id;
        } else {
            $feature = new Feature();
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $feature->name[$language['id_lang']] = $feature_name;
            }
            $feature->save();

            self::associateToShop($feature, Context::getContext()->shop->id);

            return $feature->id;
        }
    }

    public static function getFeatureValueIdByName($feature_value_name, $feature_id, $custom)
    {
        if ($id = Db::getInstance()->getValue(
            'SELECT fvl.id_feature_value
            FROM '._DB_PREFIX_.'feature_value as fv
            INNER JOIN '._DB_PREFIX_.'feature_value_lang as fvl
            ON fv.id_feature_value = fvl.id_feature_value AND fv.id_feature = '.$feature_id.'
            WHERE fvl.value  = "'.pSql($feature_value_name).'"'
        )) {
            return $id;
        } else {
            $feature_value = new FeatureValue();
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $feature_value->value[$language['id_lang']] = $feature_value_name;
            }
            $feature_value->id_feature = $feature_id;
            $feature_value->custom = (int) $custom;
            $feature_value->save();

            return $feature_value->id;
        }
    }

    public static function execCategoryPath($categorypath_list, $product)
    {
        if (count($categorypath_list) === 0) {
            return;
        }

        $parent_categories = array();
        $root_category = Category::getRootCategory();

        foreach ($categorypath_list as $categorypath) {
            $parent_category = $root_category;
            foreach ($categorypath as $category_name) {
                if (empty($category_name)) {
                    continue;
                }
                $parent_category = self::getCategory($category_name, $parent_category);
            }
            $last_category = $parent_category->id;
            $parent_categories[$parent_category->id] = array('id' => $parent_category->id);
        }
        $product->setWsCategories($parent_categories);
        $product->id_category_default = $last_category;
        $product->save();

        foreach (array_keys($parent_categories) as $id) {
            Product::cleanPositions($id);
        }
    }

    public static function getCategory($category_name, $parent_category)
    {
        if ($id = Db::getInstance()->getValue(
            'SELECT c.id_category
            FROM '._DB_PREFIX_.'category as c
            INNER JOIN '._DB_PREFIX_.'category_lang as l
            ON c.id_category = l.id_category
            WHERE l.name  = "'.pSql($category_name).'"
            AND c.id_parent = '.$parent_category->id
        )) {
            $category = new Category($id);
        } else {
            $category = new Category();

            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $category->name[$language['id_lang']] = $category_name;
                $link_rewrite = self::str2url($category_name);
                if (empty($link_rewrite)) {
                    $link_rewrite = uniqid();
                }
                $category->link_rewrite[$language['id_lang']] = $link_rewrite;
                $category->description[$language['id_lang']] = $category_name;
            }

            $category->id_parent = $parent_category->id;
            $category->save();
        }

        return $category;
    }

    public static function execCombinations($block, $product)
    {
        $combinations = array();
        if (isset($block->combinations)) {
            $combinations = (array) $block->combinations;
        } else {
            return;
        }

        $images = Image::getImages(Context::getContext()->language->id, (int) $product->id);

        foreach ($combinations as $combination_data) {
            if (isset($combination_data->id) && empty($combination_data->id)) {
                throw new Exception('Combinations id could not be empty. For new combinations id tag must be missing.');
            }
        }

        $combinations_inserted = array();
        foreach ($combinations as $combination_data) {
            $combination = null;
            if (isset($combination_data->external_reference)) {
                try {
                    $external_reference = ExternalReference::getByExternalReference(
                        (string) $combination_data->external_reference,
                        'Combination'
                    );
                    $combination = $external_reference->getInstance();
                } catch (ExternalReferenceException $e) {
                    Log::notice(
                        self::$id_advancedimporter_block,
                        self::$id_advancedimporter_flow,
                        "External Reference {$combination_data->external_reference} for combination is unknow"
                    );
                    // We do nothing : the combination is a new combination
                    $external_reference = new ExternalReference();
                }
            }

            if (!isset($combination)) {
                if (isset($combination_data->id)) {
                    $combination = new Combination($combination_data->id);
                } else {
                    $combination = new Combination();
                }
            }

            foreach (Combination::$definition['fields'] as $field => $value) {
                if (isset($combination_data->$field)) {
                    if (is_object($combination_data->$field)) {
                        $fields = array();
                        foreach ($combination_data->$field as $key => $value) {
                            $fields[$key] = $value;
                        }
                        $combination->$field = $fields;
                    } else {
                        $combination->$field = $combination_data->$field;
                    }
                }
            }

            if (isset($block->use_price_without_tax) && !$block->use_price_without_tax) {
                if ($combination->price && isset($combination_data->price)) {
                    $combination->price -= Tools::ps_round(self::getTax($combination->price, $product, $block), 6);
                }
                if ($combination->unit_price_impact && isset($combination_data->unit_price_impact)) {
                    $combination->unit_price_impact -= self::getTax(
                        $combination->unit_price_impact,
                        $product,
                        $block
                    );
                }
                if ($combination->wholesale_price && isset($combination_data->wholesale_price)) {
                    $combination->wholesale_price -= Tools::ps_round(
                        self::getTax(
                            $combination->wholesale_price,
                            $product,
                            $block
                        )
                    );
                }
            }

            $combination->id_product = $product->id;
            $errors = self::validateFields($combination);
            if (count($errors)) {
                throw new Exception('Some fields are missing or not valid in combination: '.implode('; ', $errors));
            }
            $combination->save();
            $combinations_inserted[] = $combination->id;

            if (isset($combination_data->external_reference)) {
                // Save external reference
                $external_reference->external_reference = (string) $combination_data->external_reference;
                $external_reference->object_type = 'Combination';
                $external_reference->id_object = $combination->id;
                $external_reference->save();
            }

            foreach ($combination_data->external_reference_attributes as $external_reference) {
                try {
                    $attr_external_reference = ExternalReference::getByExternalReference(
                        $external_reference,
                        'Attribute'
                    );
                    $combination_data->attributes[] = $attr_external_reference->id_object;
                } catch (ExternalReferenceException $e) {
                    throw new Exception("External Reference {$external_reference} for attribute is unknow");
                }
            }

            if (!isset($combination_data->attributes) || !count($combination_data->attributes)) {
                throw new Exception('Combinations must contain one or more attributes');
            }

            if (Db::getInstance()->getValue(
                'SELECT count(*) as count
                FROM '._DB_PREFIX_.'attribute
                WHERE id_attribute IN ('.implode(',', $combination_data->attributes).')
                GROUP BY id_attribute_group
                HAVING count > 1'
            )) {
                throw new Exception('Some attributes are from the same attribute group');
            }

            Db::getInstance()->execute(
                'DELETE FROM '._DB_PREFIX_.'product_attribute_combination
                WHERE id_product_attribute = '.(int) $combination->id
            );

            foreach ($combination_data->attributes as $id_attribute) {
                Db::getInstance()->execute(
                    'INSERT IGNORE INTO '._DB_PREFIX_.'product_attribute_combination(id_attribute, id_product_attribute)
                    VALUES ('.(int) $id_attribute.','.(int) $combination->id.')'
                );
            }

            Db::getInstance()->execute(
                'DELETE FROM '._DB_PREFIX_.'product_attribute_image
                WHERE id_product_attribute = '.(int) $combination->id
            );
            foreach ($combination_data->images_by_position as $image_position) {
                if (!isset($images[$image_position - 1])) {
                    throw new Exception("No image in position $image_position");
                }

                Db::getInstance()->execute(
                    'INSERT IGNORE INTO '._DB_PREFIX_.'product_attribute_image (id_image, id_product_attribute)
                    VALUES ('.(int) $images[$image_position - 1]['id_image'].','.(int) $combination->id.')'
                );
            }
            if (!empty($combination_data->images)) {
                $images = self::execProductImages($combination_data, $product);
                foreach ($images as $image) {
                    Db::getInstance()->execute(
                        'INSERT IGNORE INTO '._DB_PREFIX_.'product_attribute_image (id_image, id_product_attribute)
                        VALUES ('.(int) $image->id.','.(int) $combination->id.')'
                    );
                }
            }
        }

        if ($block->autodelete_combinations) {
            if (class_exists('PrestaShopCollection')) {
                $collection = new PrestaShopCollection('Combination');
            } else {
                $collection = new Collection('Combination');
            }
            $collection->where('id_product', '=', (int) $product->id);

            foreach ($collection as $item) {
                if (!in_array($item->id, $combinations_inserted)) {
                    $item->delete();
                }
            }
        }
    }

    public static function cleanUploadedImages($block)
    {
        $local_images = array();
        if (isset($block->images)) {
            $images = (array) $block->images->url;
        } else {
            return;
        }

        // Get images real path, and check exists
        foreach ($images as $img) {
            $img = self::modify($img->value, $img->modifier);
            if (preg_match('/:\/\//', $img)) {
                continue;
            }
            $local_images[] = $img;

            if (!file_exists($img)) {
                throw new Exception("File $img not found.");
            }
        }

        // Moving images
        if (isset($block->images->copy) && $block->images->copy == 'move') {
            foreach ($local_images as $path) {
                try {
                    unlink($path);
                } catch (Exception $e) {
                    Log::notice(
                        self::$id_advancedimporter_block,
                        self::$id_advancedimporter_flow,
                        "Cannot remove img $path. Please check the right on directory ".dirname($path)
                    );
                }
            }
        }
    }

    /**
     * Defined a custom str2url.
     */
    public static function str2url($str)
    {
        static $allow_accented_chars = null;
        static $has_mb_strtolower = null;

        if ($has_mb_strtolower === null) {
            $has_mb_strtolower = function_exists('mb_strtolower');
        }

        if (!is_string($str)) {
            return false;
        }

        if ($str == '') {
            return '';
        }

        if ($allow_accented_chars === null) {
            $allow_accented_chars = Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL');
        }

        $return_str = trim($str);

        if ($has_mb_strtolower) {
            $return_str = mb_strtolower($return_str, 'utf-8');
        }
        if (!$allow_accented_chars) {
            $return_str = Tools::replaceAccentedChars($return_str);
        }

        // Remove all non-whitelist chars.
        if ($allow_accented_chars) {
            $return_str = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]\-\p{L}]/u', '', $return_str);
        } else {
            $return_str = preg_replace('/[^a-zA-Z0-9\s\'\:\/\[\]\-]/', '', $return_str);
        }

        $return_str = preg_replace('/[\s\'\:\/\[\]\-]+/', ' ', $return_str);
        $return_str = str_replace(array(' ', '/'), '-', $return_str);

        // If it was not possible to lowercase the string with mb_strtolower, we do it after the transformations.
        // This way we lose fewer special chars.
        if (!$has_mb_strtolower) {
            $return_str = Tools::strtolower($return_str);
        }

        return $return_str;
    }
}
