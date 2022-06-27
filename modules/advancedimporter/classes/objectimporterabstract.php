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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/importercollection.php';
class ObjectImporterAbstract
{
    const IMPORT_BLOCKS_ON_THE_FLY = true;

    public static $id_advancedimporter_block;
    public static $id_advancedimporter_flow;
    public static $id_shop;

    /**
     * Validate fields of an object.
     */
    public static function validateFields($object)
    {
        $errors = array();
        $class = get_class($object);
        if (empty($class::$definition['fields'])) {
            return $errors;
        }

        foreach ($class::$definition['fields'] as $field => $detail) {
            if (isset($detail['lang']) && $detail['lang']) {
                if (empty($object->$field)) {
                    if (($res = $object->validateField($field, null)) !== true) {
                        $errors[] = $res;
                    }
                } else {
                    foreach ($object->$field as $id_lang => $value) {
                        if (($res = $object->validateField($field, $value, $id_lang)) !== true) {
                            $errors[] = $res;
                        }
                    }
                }
            } elseif (($res = $object->validateField($field, $object->$field)) !== true) {
                $errors[] = $res;
            }
        }

        return $errors;
    }

    public static function getShopList($block)
    {
        $shop = array();
        if (isset($block->shop)) {
            if (is_array($block->shop)) {
                $shop = $block->shop;
            }

            foreach ($shop as $key => $id) {
                if (empty($id)) {
                    unset($shop[$key]);
                }
            }
        }
        if (!count($shop)) {
            if (!empty(self::$id_shop)) {
                $shop[] = self::$id_shop;
            } else {
                $shop[] = Context::getContext()->shop->id;
            }
        }

        return $shop;
    }

    public static function getDefaultShop($block)
    {
        static $shop_instance_list = array();

        $shop_list = self::getShopList($block);
        $shop_id = $shop_list[0];
        if (!isset($shop_instance_list[$shop_id])) {
            $shop_instance_list[$shop_id] = new Shop($shop_id);
        }

        return $shop_instance_list[$shop_id];
    }

    public static function exec($block)
    {
        $current_shop = Context::getContext()->shop->id;

        try {
            $shop_list = self::getShopList($block);
            foreach ($shop_list as $shop) {
                self::setShop((int) $shop);
                static::execute($block);
            }
        } catch (Exception $e) {
            Context::getContext()->shop->id = (int) $current_shop;
            Shop::setContext(Shop::CONTEXT_SHOP, (int) $current_shop);

            throw $e;
        }

        Context::getContext()->shop->id = (int) $current_shop;
        Shop::setContext(Shop::CONTEXT_SHOP, (int) $current_shop);
    }

    public static function execute($block)
    {
        throw new Exception('ObjectImporterAbstract::execute must be overrided');
    }

    public static function execBlock($block, $object)
    {
        if (!isset($block->block)) {
            return;
        }

        foreach ($block->block as $xml_block) {
            $trimed_xml_block = trim($xml_block);
            if (empty($trimed_xml_block)) {
                continue;
            }
            unset($trimed_xml_block);
            $xml_block = str_replace('{{id}}', $object->id, $xml_block);
            if (!empty($block->external_reference)) {
                $xml_block = str_replace('{{external_reference}}', $block->external_reference, $xml_block);
            }
            if (!empty($block->ean13)) {
                $xml_block = str_replace('{{ean13}}', $block->ean13->value, $xml_block);
            }
            if (!empty($block->reference)) {
                $xml_block = str_replace('{{reference}}', $block->reference->value, $xml_block);
            }

            $xml_block = str_replace('#{', '{', $xml_block);
            $xml_block = str_replace('}#', '}', $xml_block);
            $xml_block = str_replace('\{', '{', $xml_block);
            $xml_block = str_replace('\}', '}', $xml_block);
            $xml_block = str_replace('###double-quote###', '"', $xml_block);
            $xml_block = str_replace('###simple-quote###', '\'', $xml_block);

            if (self::IMPORT_BLOCKS_ON_THE_FLY) {
                foreach (ImporterCollection::getInstance() as $importer) {
                    $flow = new Flow();
                    $flow->id = self::$id_advancedimporter_flow;
                    $object_importer = new $importer($xml_block, $flow, self::$id_advancedimporter_block);
                    if ($object_importer->isFileConcerned()) {
                        $object_importer->export();
                    }
                }
            } else {
                $rand_name = get_class($object).'-'.$object->id.'-'.rand(0, getrandmax()).'.xml';
                $path = _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$rand_name;

                file_put_contents($path, $xml_block);
            }
        }
    }

    public static function execImages($block, $object)
    {
        switch ($block->object_model) {
            case 'Category':
                if (isset($block->image)) {
                    self::execObjectImage($block, $object);
                }
                break;
            case 'Product':
                if (isset($block->images)) {
                    self::execProductImages($block, $object);
                }
                break;
            case 'Attribute':
                if (isset($block->texture)) {
                    self::execAttributeTexture($block, $object);
                }
                break;
        }
    }

    public static function removeAttributeTexture($block, $object)
    {
        $file = _PS_COL_IMG_DIR_.((int) $object->id).'.jpg';

        if (!file_exists($file)) {
            return;
        }

        unlink($file);
    }

    public static function execAttributeTexture($block, $object)
    {
        $img = (string) $block->texture;
        $img = trim($img);

        if (empty($img)) {
            return self::removeAttributeTexture($block, $object);
        }

        if (!preg_match('/:\/\//', $img)) {
            $img = _PS_ROOT_DIR_.$img;
            if (!file_exists($img)) {
                throw new Exception("File $img not found.");
            }
        }

        $url = str_replace(' ', '%20', $img);

        if (!self::copyImg($object->id, $url, null, self::convertEntity($block->object_model), false)) {
            throw new Exception("Error copying image: $url");
        }
    }

    public static function execObjectImage($block, $object)
    {
        $img = (string) $block->image;
        if (!preg_match('/:\/\//', $img)) {
            $img = _PS_ROOT_DIR_.$img;
            if (!file_exists($img)) {
                throw new Exception("File $img not found.");
            }
        }

        $img = trim($img);
        $url = str_replace(' ', '%20', $img);

        if (!self::copyImg($object->id, $url, null, self::convertEntity($block->object_model))) {
            throw new Exception("Error copying image: $url");
        }
    }

    public static function execProductImages($block, $product)
    {
        $images = array();
        $image_collection = array();

        if (isset($block->images)) {
            $images = (array) $block->images->url;
        } else {
            return;
        }


        foreach ($images as $key => $img) {
            $images[$key]->value = self::modify($img->value, $img->modifier);
        }

        // Get images real path, and check exists
        foreach ($images as $key => $img) {
            if (preg_match('/:\/\//', $images[$key]->value)) {
                continue;
            }
            $images[$key]->value = _PS_ROOT_DIR_.$images[$key]->value;
            if (!file_exists($images[$key]->value)) {
                throw new Exception("File {$images[$key]->value} not found.");
            }
        }

        // Delete images if insertion = replace
        if (isset($block->images->insertion) && $block->images->insertion == 'replace') {
            $product->deleteImages();
        }

        $global_update = (bool) $block->images->update;

        $product_cover_data = Image::getCover((int) $product->id);
        if ($product_cover_data === false) {
            $product_cover = false;
        } else {
            $product_cover = (int) $product_cover_data['id_image'];
        }

        foreach ($images as $key => $img) {

            $url = $img->value;
            $url = trim($url);
            $url = str_replace(' ', '%20', $url);

            $update = $global_update;
            if (!is_null($img->update)) {
                $update = (bool)$img->update;
            }

            $external_reference_label = (string) $img->external_reference;
            if (empty($external_reference_label)) {
                $external_reference_label = md5($url).$product->id;
            }

            try {
                $external_reference = ExternalReference::getByExternalReference($external_reference_label, 'Image');
                $image = new Image($external_reference->id_object);
            } catch (Exception $e) {
                $image = new Image();
                $external_reference = new ExternalReference();
                $external_reference->external_reference = $external_reference_label;
                $external_reference->object_type = 'Image';
            }

            if (!is_null($img->position)) {
                $image->position = (int) $img->position;
            }

            if (!is_null($img->legend)) {
                $image->legend = (string) $img->legend;
            }

            // If image do not exist or was removed (external ref still exists)
            if (!$image->id || !$image->id_product) {
                $image->position = Image::getHighestPosition($product->id) + 1;
                $image->id_product = $product->id;
            }

            if (!is_null($img->cover)) {
                if ($product_cover && $img->cover) {
                    Image::deleteCover($product->id);
                }
                $image->cover = (bool) $img->cover;
            } else {
                $image->cover = (!$key && !$product_cover || $product_cover === $image->id) ? true : false;
            }

            $image_exists = (bool)$image->id;

            if ($image->save()) {
                // associate image to selected shops
                try {
                    $image->associateTo(self::getShopList($block));
                } catch (Exception $e) {
                    // For an unknow reason, this request can crash
                }

                $image_collection[] = $image;
                $external_reference->id_object = $image->id;
                $external_reference->save();

                if (!$image_exists || $update) {
                    if (!self::copyImg($product->id, $url, $image->id)) {
                        $image->delete();
                        Log::error(
                            self::$id_advancedimporter_block,
                            self::$id_advancedimporter_flow,
                            "Error copying image: $url"
                        );
                    }
                }
            } else {
                Log::error(self::$id_advancedimporter_block, self::$id_advancedimporter_flow, "Cannot save image $url");
            }
        }

        return $image_collection;
    }

    /**
     * From AdminImportController.
     */
    protected static function copyImg($id_entity, $url, $id_image = null, $entity = 'products', $regenerate = true)
    {
        $tmpfile = tempnam(_PS_TMP_IMG_DIR_, 'ps_import');
        $watermark_types = explode(',', Configuration::get('WATERMARK_TYPES'));

        switch ($entity) {
            default:
            case 'products':
                $image_obj = new Image($id_image);
                $path = $image_obj->getPathForCreation();
                break;
            case 'categories':
                $path = _PS_CAT_IMG_DIR_.(int) $id_entity;
                break;
            case 'manufacturers':
                $path = _PS_MANU_IMG_DIR_.(int) $id_entity;
                break;
            case 'suppliers':
                $path = _PS_SUPP_IMG_DIR_.(int) $id_entity;
                break;
            case 'attribute':
                $path = _PS_COL_IMG_DIR_.(int) $id_entity;
                break;
        }
        $url = str_replace(' ', '%20', trim($url));

        // Evaluate the memory required to resize the image: if it's too much, you can't resize it.
        if (!ImageManager::checkImageMemoryLimit($url)) {
            return false;
        }

        // 'file_exists' doesn't work on distant file, and getimagesize makes the import slower.
        // Just hide the warning, the processing will be the same.
        if (copy($url, $tmpfile)) {
            // We cannot use Tools::copy that is not comptible with PrestaShop < 1.5.5

            ImageManager::resize($tmpfile, $path.'.jpg');
            if ($regenerate) {
                $images_types = ImageType::getImagesTypes($entity);

                foreach ($images_types as $image_type) {
                    ImageManager::resize(
                        $tmpfile,
                        $path.'-'.Tools::stripslashes($image_type['name']).'.jpg',
                        $image_type['width'],
                        $image_type['height']
                    );
                    if (in_array($image_type['id_image_type'], $watermark_types)) {
                        Hook::exec('actionWatermark', array('id_image' => $id_image, 'id_product' => $id_entity));
                    }
                }
            }
        } else {
            unlink($tmpfile);

            return false;
        }
        unlink($tmpfile);

        return true;
    }

    protected static function loadFieldsUsingExternalReference(&$block)
    {
        foreach ($block->fields_using_external_reference as $field) {
            try {
                $reference = ExternalReference::getByExternalReference(
                    (string) $field->external_reference,
                    (string) $field->type
                );
                $for = (string) $field->for;
                if ($for === 'id') {
                    $block->$for = (int) $reference->id_object;
                } else {
                    $block->$for = (object) array(
                        'value' => $reference->id_object,
                        'modifier' => '',
                    );
                }
            } catch (Exception $e) {
                throw new Exception(
                    'Unknow external reference "'.(string) $field->external_reference.'" of type "'
                    .(string) $field->type.'"'
                );
            }
        }
    }

    protected static function loadFieldsUsingCollection(&$block)
    {
        foreach ($block->fields_using_collection as $field) {
            $collection = new PrestaShopCollection((string) $field->type);
            foreach ($field->filters as $filter => $value) {
                $collection->where($filter, '=', (string) $value);
            }

            $entity = $collection->getFirst();
            if (!$entity) {
                $inlineFilters = array();
                foreach ($field->filters as $filter => $value) {
                    $inlineFilters[] = "$filter => $value";
                }
                $stringFilters = implode(', ', $inlineFilters);
                $type = (string) $field->type;
                Log::notice(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "No entity found for findOne type '$type' [$stringFilters]"
                );
                continue;
            }


            $for = (string) $field->for;
            $identifier = (string) $field->identifier;

            if ($for === 'id') {
                $block->$for = (int) $entity->id;
            } else {
                $block->$for = (object) array(
                    'value' => $entity->$identifier,
                    'modifier' => '',
                );
            }
        }
    }

    protected static function setFields($block, &$object)
    {
        $object_model = get_class($object);

        foreach ($object_model::$definition['fields'] as $field => $value) {
            if (isset($block->$field)) {
                if (!isset($block->$field->value)) {
                    $fields = array();
                    foreach ($block->$field as $key => $value) {
                        $fields[$key] = self::modify($value->value, $value->modifier);
                    }

                    $object->$field = $fields;
                } else {
                    if ($value['type'] === ObjectModel::TYPE_FLOAT) {
                        if (gettype($block->$field) === 'string' && empty($block->$field->modifier)) {
                            $block->$field->value = (float) str_replace(',', '.', $block->$field->value);
                        }
                    }
                    $object->$field = self::modify($block->$field->value, $block->$field->modifier);
                }
            }
        }
    }

    public static function modify($value, $modifier)
    {
        if (empty($modifier)) {
            return $value;
        }

        $params = array($value);

        if (strpos($modifier, '::') !== false) {
            list($class, $function) = explode('::', $modifier);

            if (!class_exists($class) && strpos($class, 'Helper') === 0) {
                $filename = _PS_MODULE_DIR_.'advancedimporter/classes/'
                    .Tools::strtolower(str_replace('Helper', 'helper/', $class)).'.php';
                require $filename;
            }

            self::extractModifierParams($function, $params);

            return call_user_func_array(array($class, $function), $params);

        } else {
            self::extractModifierParams($modifier, $params);

            return call_user_func_array($modifier, $params);
        }
    }

    public static function extractModifierParams(&$function, &$params)
    {
        preg_match('/^([A-Z_0-9]+)(?:\((.*)\))?$/Usi', $function, $matches);

        $function = $matches[1];
        if (isset($matches[2])) {
            foreach (str_getcsv($matches[2], ',', "'") as $value) {
                $params[] = $value;
            }
        }
    }

    protected static function convertEntity($entity)
    {
        switch ($entity) {
            case 'Product':
                return 'product';
            case 'Category':
                return 'categories';
            case 'Manufacturer':
                return 'manufacturers';
            case 'Supplier':
                return 'suppliers';
            case 'Attribute':
                return 'attribute';
        }
    }

    public static function setShop($shop)
    {
        Context::getContext()->shop->id = (int) $shop;
        Shop::setContext(Shop::CONTEXT_SHOP, (int) $shop);
        // To fix usage of POST from native Category Class of the PrestaShop
        // Using _POST is not a good idea, but there are no other solutions in this case
        $_POST['checkBoxShopAsso_category'] = array(
            $shop => array()
        );
    }

    public static function associateToShop($object, $shop)
    {
        $class = get_class($object);
        $table = $class::$definition['table'];

        $insert = array();
        $insert[] = array(
            $class::$definition['primary'] => (int)$object->id,
            'id_shop' => (int)$shop,
        );
        return Db::getInstance()->insert($table.'_shop', $insert, false, true, Db::INSERT_IGNORE);
    }
}
