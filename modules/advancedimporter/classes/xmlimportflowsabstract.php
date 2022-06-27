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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/importflowsinterface.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/flow.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xslt.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/collectionupdater.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xmlfile.php';

abstract class XmlImportFlowsAbstract implements ImportFlowsInterface
{
    const ENABLE_COLLECTION_UPDATER = true;

    public static $id_advancedimporter_block;
    public $last_xml_loaded;
    //public $file_cache_content;
    protected $import_block = false;
    protected $id_block = 0;

    protected $flow;
    protected $xml;

    public function __construct($xml = false, $flow = null, $id_block = null)
    {
        if ($xml !== false) {
            $this->import_block = true;
            $this->xml = $xml;
            $this->flow = $flow;
            $this->id_block = $id_block;
            return $this;
        }

        $date = new DateTime();
        $date_formated = $date->format('Y-m-d-H-i');

        if (!is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/')) {
            throw new Exception(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/ must be writable');
        }

        if (!is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/imported/')) {
            throw new Exception(_PS_MODULE_DIR_.'advancedimporter/flows/import/imported/ must be writable');
        }

        if (!is_writable(_PS_MODULE_DIR_.'advancedimporter/flows/import/error/')) {
            throw new Exception(_PS_MODULE_DIR_.'advancedimporter/flows/import/error/ must be writable');
        }

        $files_not_concerned = array();

        foreach ($this->getFiles() as $file) {
            try {
                if ($this->isFileConcerned($file)) {
                    $this->flow = Flow::getByPath(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file);
                    $this->flow->status = FLOW::STATUS_IMPORTING;
                    $this->flow->save();

                    try {
                        rename(
                            _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file,
                            _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file.'.'.$date_formated
                        );

                        $this->flow->path = 'imported/'.$file.'.'.$date_formated;
                        $this->flow->save();
                    } catch (Exception $e) {
                        Log::error(
                            self::$id_advancedimporter_block,
                            $this->flow->id,
                            "$file cannot be moved into directory \"imported\": ".$e->getMessage()
                        );
                    }

                    try {
                        $this->export($this->last_xml_loaded);
                        Log::sys(
                            self::$id_advancedimporter_block,
                            $this->flow->id,
                            "$file imported"
                        );
                        try {
                            rename(
                                _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file.'.'.$date_formated,
                                _PS_MODULE_DIR_.'advancedimporter/flows/import/imported/'.$file.'.'.$date_formated
                            );
                            $this->flow->status = FLOW::STATUS_PROCESSING;

                            $this->flow->path = 'imported/'.$file.'.'.$date_formated;
                            $this->flow->compileData();
                            $this->flow->save();
                        } catch (Exception $e) {
                            Log::error(
                                self::$id_advancedimporter_block,
                                $this->flow->id,
                                "$file cannot be moved into directory \"imported\": ".$e->getMessage()
                            );
                        }
                    } catch (Exception $e) {
                        rename(
                            _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file.'.'.$date_formated,
                            _PS_MODULE_DIR_.'advancedimporter/flows/import/error/'.$file.'.'.$date_formated
                        );

                        $this->flow->path = 'error/'.$file.'.'.$date_formated;
                        $this->flow->status = FLOW::STATUS_ERROR;
                        $this->flow->compileData();
                        $this->flow->save();

                        Log::error(
                            self::$id_advancedimporter_block,
                            $this->flow->id,
                            "$file cannot be imported: ".$e->getMessage()
                        );
                    }
                } else {
                    $files_not_concerned[] = $file;
                }
            } catch (Exception $e) {
                Log::error(
                    self::$id_advancedimporter_block,
                    !empty($this->flow) ? $this->flow->id : 0,
                    'Error: '.$e->getMessage()
                );
                $files_not_concerned[] = $file;
            }
        }
    }

    protected function preExport()
    {
        // Do nothing
    }

    protected function postExport()
    {
        // Do nothing
    }

    public function export()
    {
        $count = 0;

        if (!$this->import_block) {
            $this->preExport();
        }

        if (!$this->import_block && self::ENABLE_COLLECTION_UPDATER) {
            $collectionUpdater = new CollectionUpdater('Block');
        }

        foreach ($this->last_xml_loaded as $subblock) {
            $count++;
            $block_obj = new Block();
            $block_obj->callback = $this->getCallback();
            $block_obj->block = Tools::jsonEncode($this->translate($subblock));
            $block_obj->id_shop = Context::getContext()->shop->id;
            $block_obj->channel = $this->getChannel();
            $block_obj->id_parent = $this->id_block;
            $created_at = new DateTime();
            $block_obj->created_at = $created_at->format('Y-m-d H:i:s');
            $block_obj->id_advancedimporter_flow = $this->flow->id;
            if ($this->import_block || !self::ENABLE_COLLECTION_UPDATER) {
                $block_obj->save();
            } else {
                $collectionUpdater->addItem($block_obj);

                if ($count % 100 === 0) {
                    $collectionUpdater->flush();
                }
            }

            if ($this->import_block) {
                $block_obj->run();
            }
        }

        if (!$this->import_block) {
            $this->postExport();
        }

        if (!$this->import_block && self::ENABLE_COLLECTION_UPDATER) {
            $collectionUpdater->flush();
        }
    }

    public function isFileConcerned($file = null)
    {
        if (!$this->import_block) {
            if (!preg_match('/\.xml$/', Tools::strtolower($file))) {
                return false;
            }

            $file_object = AIXmlFile::getInstance($file);

        } else {
            $file_object = AIXmlFile::getInstanceFromXml($this->xml);
            $file = 'block';
        }

        $this->last_xml_loaded = $file_object->getXml();

        return true;
    }

    protected function getLanguages()
    {
        static $languages = array();

        if (empty($languages)) {
            foreach (Language::getIsoIds(false) as $lang) {
                $languages[$lang['iso_code']] = $lang['id_lang'];
            }
        }

        return $languages;
    }

    protected function parseDefaultFields($block, &$result)
    {
        $languages = $this->getLanguages();

        if ((string) $block['external-reference']) {
            $result['external_reference'] = trim((string) $block['external-reference']);
        }

        if ($block->id) {
            $result['id'] = (string) $block->id;
        }

        if ($block->shop) {
            $result['shop'] = array();
            foreach ($block->shop as $shop) {
                $result['shop'][] = (int) $shop;
            }
        }

        if ($block->block) {
            $result['block'] = array();
            foreach ($block->block as $subblock) {
                foreach ($subblock->children() as $child) {
                    $xml_block = $child->asXml();
                    $xml_block = str_replace('"', '###double-quote###', $xml_block);
                    $xml_block = str_replace('\'', '###simple-quote###', $xml_block);
                    $result['block'][] = $xml_block;
                }
            }
        }

        $result['fields_using_external_reference'] = array();
        foreach ($block->external_reference as $ref) {
            $result['fields_using_external_reference'][] = array(
                'for' => (string) $ref['for'],
                'external_reference' => (string) $ref,
                'type' => (string) $ref['type'],
            );
        }

        $result['fields_using_collection'] = array();
        foreach ($block->findOne as $ref) {
            $filters = array();
            foreach ($ref->children() as $attribute => $value) {
                $filters[(string) $attribute] = (string) $value;
            }
            $result['fields_using_collection'][] = array(
                'for' => (string) $ref['for'],
                'type' => (string) $ref['type'],
                'identifier' => (string) $ref['identifier'],
                'filters' => $filters,
            );
        }
    }

    protected function parseObjectFields($block, &$result, $type)
    {
        $languages = $this->getLanguages();
        $default_language = (int) Configuration::getGlobalValue('PS_LANG_DEFAULT');

        $this->parseDefaultFields($block, $result);

        $type = Tools::ucfirst($type);

        $result['object_model'] = $type;

        if (!class_exists($type)) {
            if (!file_exists(_PS_MODULE_DIR_.'advancedimporter/classes/objectmodel/'.$type.'.php')) {
                throw new Exception("object_model \"{$type}\" is not a valid class.");
            }

            require_once _PS_MODULE_DIR_.'advancedimporter/classes/objectmodel/'.$type.'.php';
        }

        $result['update'] = true;
        if (isset($block->attributes()->update)) {
            $result['update'] = (bool) (int) $block->attributes()->update;
        }

        foreach ($type::$definition['fields'] as $field => $value) {
            if (isset($block->$field)) {
                if (isset($value['lang']) && $value['lang']) {
                    foreach ($block->$field as $subblock) {
                        if (!isset($result[$field])) {
                            $result[$field] = array();
                        }

                        if (isset($languages[(string) $subblock['lang']])) {
                            $result[$field][$languages[(string) $subblock['lang']]] = array(
                                'value' => (string) $subblock,
                                'modifier' =>
                                isset($subblock->attributes()->modifier) ?
                                (string) $subblock->attributes()->modifier : '',
                            );
                        } elseif (!(string) $subblock['lang']) {
                            $result[$field][$default_language] = array(
                                'value' => (string) $subblock,
                                'modifier' =>
                                isset($subblock->attributes()->modifier) ?
                                (string) $subblock->attributes()->modifier : '',
                            );
                        }
                    }
                } else {
                    $val = (string)$block->$field;
                    if ($value['type'] === ObjectModel::TYPE_FLOAT && !isset($block->$field->attributes()->modifier)) {
                        $val = (float)str_replace(',', '.', $val);
                    }

                    $result[$field] = array(
                        'value' => $val,
                        'modifier' =>
                        isset($block->$field->attributes()->modifier) ?
                        (string) $block->$field->attributes()->modifier : '',
                    );
                }
            }
        }

        if (isset($block->image)) {
            $result['image'] = array(
                'value' => (string) $block->image,
                'modifier' => isset($block->image->attributes()->modifier) ?
                    (string) $block->image->attributes()->modifier : '',
            );
        }

        if (isset($block->images)) {
            $result['images'] = array();

            if (isset($block->images['insertion'])) {
                $result['images']['insertion'] = (string) $block->images['insertion'];
            }

            if (isset($block->images['update'])) {
                $result['images']['update'] = (int) $block->images['update'];
            } else {
                $result['images']['update'] = 1;
            }

            if (isset($block->images->copy)) {
                $result['images']['copy'] = (string) $block->images->copy;
            }

            $result['images']['url'] = array();
            foreach ($block->images->url as $url) {
                $result['images']['url'][] = array(
                    'value' => (string) $url,
                    'modifier' => isset($url->attributes()->modifier) ?
                        (string) $url->attributes()->modifier : '',
                    'position' => isset($url->attributes()->position) ?
                        (int) $url->attributes()->position : null,
                    'update' => isset($url->attributes()->update) ?
                        (bool) (int) $url->attributes()->update: null,
                    'cover' => isset($url->attributes()->cover) ?
                        (bool) (int) $url->attributes()->cover: null,
                    'external_reference' => isset($url->attributes()->{'external-reference'}) ?
                        (string) $url->attributes()->{'external-reference'} : '',
                    'legend' => isset($url->attributes()->legend) ?
                        (string) $url->attributes()->legend: null,
                );
            }
        }
    }

    protected function isAttributeSet($field, $block)
    {
        if (isset($block->$field) && Tools::strlen((string) $block->$field) > 0) {
            return true;
        }

        if ($block->xpath('fields_using_external_reference[for='.$field.']')) {
            return true;
        }

        if ($block->xpath('findOne[for='.$field.']')) {
            return true;
        }

        return false;
    }

    public function getFiles()
    {
        $dir_path = _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/';
        $dir = opendir($dir_path);
        $file_collection = array();
        while ($file = readdir($dir)) {
            if ($file == '..' || $file == '.' || $file == 'PROCESSING') {
                continue;
            }

            $file_collection[] = $file;
        }
        closedir($dir);

        return $file_collection;
    }
}
