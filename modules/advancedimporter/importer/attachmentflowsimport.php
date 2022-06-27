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

class AttachmentFlowsImport extends XmlImportFlowsAbstract
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
        return 'AttachmentImporter::exec';
    }

    public function isFileConcerned($file = null)
    {
        if (!parent::isFileConcerned($file)) {
            return false;
        }

        return (bool) count($this->last_xml_loaded->xpath('/attachments'));
    }

    public function translate($block)
    {
        $result = array();

        $this->parseObjectFields($block, $result, 'attachment');

        $path = (string)$block->path;

        if (empty($path)) {
            throw new Exception('Path is not define');
        }

        $result['path'] = $path;

        if (empty($result['file_name'])) {
            $path_info = pathinfo($path);
            $result['file_name'] = array(
                'value' => $path_info['filename'].'.'.$path_info['extension'],
                'modifier' => '',
            );
        }

        $result['product'] = array();
        foreach ($block->product as $product) {
            $result['product'][] = (string) $product;
        }

        return $result;
    }
}
