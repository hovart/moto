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

class AttachmentImporter extends ObjectImporterAbstract
{
    /**
     * Import products.
     */
    public static function execute($block)
    {
        self::loadFieldsUsingExternalReference($block);
        self::loadFieldsUsingCollection($block);

        if (!isset($block->id) && isset($block->reference) && $block->reference) {
            try {
                $reference = new AdvancedImporterReference($block->reference);
                $block->id = $reference->id_product;
            } catch (Exception $e) {
                Log::notice(
                    self::$id_advancedimporter_block,
                    self::$id_advancedimporter_flow,
                    "Reference {$block->reference} is unknow"
                );
                // We do nothing : the product is a new product
            }
        }

        if (!empty($block->external_reference)) {
            try {
                $external_reference = ExternalReference::getByExternalReference(
                    (string) $block->external_reference,
                    'Attachment'
                );

                if (!empty($block->id) && $block->id != $external_reference->id_object) {
                    throw new Exception(
                        "External Reference \"{$block->external_reference}\" already exist for the id "
                        ."{$external_reference->id_object}."
                    );
                }

                $block->id = $external_reference->id_object;
                $attachment = $external_reference->getInstance();
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

        if (!isset($attachment)) {
            if (isset($block->id)) {
                if (empty($block->id)) {
                    throw new Exception('Id could not be empty. For new products id tag must be missing.');
                }

                $attachment = new Attachment($block->id);
            } else {
                $attachment = new Attachment();
            }
        }

        // Don't update attachment if update attribute is false
        if (!$block->update && $attachment->id) {
            return;
        }

        self::setFields($block, $attachment);

        if ($attachment->file) {
            $uniqid = $attachment->file;
        } else {
            do {
                $uniqid = sha1(microtime());
            } while (file_exists(_PS_DOWNLOAD_DIR_.$uniqid));
        }

        self::upload((string)$block->path, $uniqid);

        $attachment->file = $uniqid;
        if (!$attachment->mime) {
            $attachment->mime = mime_content_type(_PS_DOWNLOAD_DIR_.$uniqid);
        }


        $res = $attachment->save();

        if (!$res) {
            throw new Exception("Attachment could not be saved.");
        }

        if (!empty($block->external_reference)) {
            // Save external reference
            $external_reference->external_reference = (string) $block->external_reference;
            $external_reference->object_type = 'Attachment';
            $external_reference->id_object = $attachment->id;
            $external_reference->save();
        }


        foreach ($block->product as $product) {
            try {
                $attachment->attachProduct((int)$product);
            } catch (Exception $e) {
                // Silent exception
                // Product is already attached
                // There are SQL error in this case
            }
        }
    }

    public static function upload($url, $uniqid)
    {
        $url = str_replace(' ', '%20', trim($url));

        if (!is_writable(_PS_DOWNLOAD_DIR_)) {
            throw new Exception("Download dir is not writable");
        }

        // 'file_exists' doesn't work on distant file, and getimagesize makes the import slower.
        // Just hide the warning, the processing will be the same.
        if (!copy($url, _PS_DOWNLOAD_DIR_.$uniqid)) {
            throw new Exception("Attachment '$url' could not be uploaded");
        }
    }
}
