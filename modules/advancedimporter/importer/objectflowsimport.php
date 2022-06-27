<?php
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xmlimportflowsabstract.php';

class ObjectFlowsImport extends XmlImportFlowsAbstract
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
        return 'ObjectImporter::exec';
    }

    public function isFileConcerned($file = null)
    {
        if (!parent::isFileConcerned($file)) {
            return false;
        }

        return (bool) count($this->last_xml_loaded->xpath('/objects'));
    }

    public function translate($block)
    {
        $result = array();
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

        $tmp = array();
        foreach ($block as $key => $value) {
            if (isset($result[$key])) {
                continue;
            }
            if (isset($tmp[$key]) && !is_array($tmp[$key])) {
                $tmp[$key] = array($tmp[$key]);
            }

            if (isset($tmp[$key])) {
                $tmp[$key][] = (string) $value;
            } else {
                $tmp[$key] = (string) $value;
            }
        }
        $result = array_merge($result, $tmp);

        return $result;
    }
}
