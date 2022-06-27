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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/externalreference.php';

class ExternalReferenceExporter
{
    public static $id_advancedimporter_flow;
    public static $channel;

    /**
     * Full export of the external references.
     */
    public static function full($block)
    {
        // Get external reference collection
        $collection = self::getCollection($block);

        self::export($collection, $block);
    }

    /**
     * Export of the external references created yesterday.
     */
    public static function daily($block)
    {
        // Get external reference collection
        $collection = self::getCollection($block);

        $yesterday = new DateTime();
        $yesterday->sub(new DateInterval('P1D'));
        $today = new DateTime();

        $collection->where('date_add', '<', $today->format('Y-m-d'));
        $collection->where('date_add', '>=', $yesterday->format('Y-m-d'));

        self::export($collection, $block);
    }

    protected static function getCollection($block)
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('ExternalReference');
        } else {
            $collection = new Collection('ExternalReference');
        }

        if (isset($block->object_types)) {
            $collection->where('object_type', 'in', (array) $block->object_types);
        }

        return $collection;
    }

    protected static function export($collection, $block)
    {
        $xml = new SimpleXmlElement('<externalreferences />');
        foreach ($collection as $external_reference) {
            $row = $xml->addChild('externalreference');
            foreach ($external_reference as $name => $value) {
                $row->addChild($name, $value);
            }
        }

        file_put_contents(_PS_MODULE_DIR_.'advancedimporter/flows/export/'.$block->file, $xml->asXml());
    }
}
