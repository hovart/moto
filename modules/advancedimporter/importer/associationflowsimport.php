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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/xmlimportflowsabstract.php';

class AssociationFlowsImport extends XmlImportFlowsAbstract
{
    /**
     * Get channel number block.
     */
    public function getChannel()
    {
        return 1;
    }

    /**
     * Get callback.
     */
    public function getCallback()
    {
        return 'AssociationImporter::exec';
    }

    public function isFileConcerned($file = null)
    {
        if (!parent::isFileConcerned($file)) {
            return false;
        }

        return (bool) count($this->last_xml_loaded->xpath('/associations'));
    }

    public function translate($block)
    {
        $result = array('categories' => array());

        $this->parseDefaultFields($block, $result);

        if (!(int) (string) $block['productid']
            && !(string) $block['reference']
            && !(string) $block['ean13']
            && !(string) $block['external-reference']
        ) {
            throw new Exception('Attribute "external-reference", "productid", "ean13" or "reference" must be definedd');
        }

        if ((string) $block['external-reference']) {
            $result['external_reference'] = trim((string) $block['external-reference']);
        }

        if ((int) (string) $block['productid']) {
            $result['productid'] = (int) (string) $block['productid'];
        } else {
            $result['productid'] = 0;
        }

        if (Tools::strlen((string) $block['reference'])) {
            $result['reference'] = (string) $block['reference'];
        } else {
            $result['reference'] = 0;
        }

        if (Tools::strlen((string) $block['ean13'])) {
            $result['ean13'] = (string) $block['ean13'];
        } else {
            $result['ean13'] = 0;
        }

        if ($block->mode) {
            $result['mode'] = (string) $block->mode;
        } else {
            $result['mode'] = 'add';
        }

        foreach ($block->category as $category) {
            $category_data = array();
            $category_data['is_default'] = (bool) $category['is-default'];
            if (isset($category['use-external-reference'])) {
                $category_data['external_reference'] = (string) $category;
            } else {
                $category_data['id'] = (int) (string) $category;
            }

            $result['categories'][] = $category_data;
        }

        return $result;
    }
}
