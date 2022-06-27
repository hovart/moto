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
require_once _PS_MODULE_DIR_.'advancedimporter/classes/externalreference.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/history.php';

class ObjectImporter extends ObjectImporterAbstract
{
    /**
     * Import products.
     */
    public static function execute($block)
    {
        if (empty($block->object_model)) {
            throw new Exception('object_model is missing or empty.');
        }

        $object_model = $block->object_model;

        // Include class file if class is unknow
        if (!class_exists($object_model)) {
            if (!file_exists(_PS_MODULE_DIR_.'advancedimporter/classes/objectmodel/'.$object_model.'.php')) {
                throw new Exception("object_model \"{$object_model}\" is not a valid class.");
            }

            require_once _PS_MODULE_DIR_.'advancedimporter/classes/objectmodel/'.$object_model.'.php';
        }

        if (!empty($block->external_reference)) {
            try {
                $external_reference = ExternalReference::getByExternalReference(
                    (string) $block->external_reference,
                    $object_model
                );

                if (!empty($block->id) && $block->id != $external_reference->id_object) {
                    throw new Exception(
                        "External Reference \"{$block->external_reference}\" already exist for the id "
                        ."{$external_reference->id_object}."
                    );
                }

                $block->id = $external_reference->id_object;
                $object = $external_reference->getInstance();
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

        self::loadFieldsUsingExternalReference($block);
        self::loadFieldsUsingCollection($block);

        if (!isset($object)) {
            if (isset($block->id)) {
                if (empty($block->id)) {
                    throw new Exception("Id could not be empty, for the object \"{$object_model}\".");
                }

                $object = new $object_model($block->id);
            } else {
                $object = new $object_model();
            }
        }

        // Don't update object if update attribute is false
        if (!$block->update && $object->id) {
            if (!empty($block->external_reference)) {
                // Save external reference
                $external_reference->external_reference = (string) $block->external_reference;
                $external_reference->object_type = $object_model;
                $external_reference->id_object = $object->id;
                $external_reference->save();
            }
            return;
        }

        self::setFields($block, $object);

        // Object validation
        $errors = array();
        $errors = self::validateFields($object);
        if (count($errors)) {
            throw new Exception('Some fields are missing or not valid: '.implode('; ', $errors));
        }

        $object->save();

        if (!empty($block->external_reference)) {
            // Save external reference
            $external_reference->external_reference = (string) $block->external_reference;
            $external_reference->object_type = $object_model;
            $external_reference->id_object = $object->id;
            $external_reference->save();
        }

        self::execImages($block, $object);
        self::execBlock($block, $object);

        AIHistory::create(
            'object',
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            get_class($object),
            $object->id,
            isset($block->external_reference) ? (string) $block->external_reference : null
        );

        Log::sys(
            self::$id_advancedimporter_block,
            self::$id_advancedimporter_flow,
            "Object {$object_model} {$object->id} imported"
        );
    }
}
