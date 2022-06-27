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

class DeleteFlowsImport extends XmlImportFlowsAbstract
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
        return 'DeleteImporter::exec';
    }

    public function isFileConcerned($file = null)
    {
        if (!parent::isFileConcerned($file)) {
            return false;
        }

        return (bool) count($this->last_xml_loaded->xpath('/delete'));
    }

    public function translate($block)
    {
        $result = array();
        $languages = array();
        foreach (Language::getIsoIds(false) as $lang) {
            $languages[$lang['iso_code']] = $lang['id_lang'];
        }

        $type = Tools::ucfirst((string) $block['type']);

        if (empty($type)) {
            throw new Exception('Type is not define');
        }

        if (!class_exists($type)) {
            if (!file_exists(_PS_MODULE_DIR_.'advancedimporter/classes/objectmodel/'.$type.'.php')) {
                throw new Exception('Type "'.$type.'" is unknow');
            }

            require_once _PS_MODULE_DIR_.'advancedimporter/classes/objectmodel/'.$type.'.php';
        }

        if (!is_subclass_of($type, 'ObjectModel')) {
            throw new Exception('Type "'.$type.'" do not extends ObjectModel');
        }

        $this->parseObjectFields($block, $result, $type);

        return $result;
    }
}
