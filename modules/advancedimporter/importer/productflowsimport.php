<?php
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xmlimportflowsabstract.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/externalreferencesupplier.php';

class ProductFlowsImport extends XmlImportFlowsAbstract
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
        return 'ProductImporter::exec';
    }

    public function isFileConcerned($file = null)
    {
        if (!parent::isFileConcerned($file)) {
            return false;
        }

        return (bool) count($this->last_xml_loaded->xpath('/products'));
    }

    protected function preExport()
    {
        $autodelete = $this->last_xml_loaded->attributes()->autodelete;
        if (empty($autodelete)) {
            return;
        }

        $autodelete = trim($autodelete);

        ExternalReferenceSupplier::flagToDeleteSupplier($autodelete, 'Product');
        foreach ($this->last_xml_loaded as $subblock) {
            if (!isset($subblock['external-reference'])) {
                continue;
            }
            $external_reference = ExternalReferenceSupplier::getByExternalReference(
                (string) $subblock['external-reference'],
                'Product'
            );
            $external_reference->to_delete = 0;
            $external_reference->supplier = $autodelete;
            $external_reference->save();
        }

        if (self::ENABLE_COLLECTION_UPDATER) {
            $collectionUpdater = new CollectionUpdater('Block');
        }

        $count = 0;
        foreach (ExternalReferenceSupplier::getToDeleteFromSupplier($autodelete, 'Product') as $reference) {
            $block_obj = new Block();
            $block_obj->callback = 'DeleteImporter::exec';
            $block_obj->block = Tools::jsonEncode(
                array(
                    'object_model' => 'Product',
                    'external_reference' => $reference->external_reference,
                    'fields_using_external_reference' => array(),
                    'fields_using_collection' => array(),
                )
            );
            $block_obj->id_shop = Context::getContext()->shop->id;
            $block_obj->channel = $this->getChannel();
            $block_obj->id_parent = $this->id_block;
            $created_at = new DateTime();
            $block_obj->created_at = $created_at->format('Y-m-d H:i:s');
            $block_obj->id_advancedimporter_flow = $this->flow->id;
            if (!self::ENABLE_COLLECTION_UPDATER) {
                $block_obj->save();
            } else {
                $collectionUpdater->addItem($block_obj);

                if ($count % 100 === 0) {
                    $collectionUpdater->flush();
                }
            }
            $reference->delete();
        }

        if (self::ENABLE_COLLECTION_UPDATER) {
            $collectionUpdater->flush();
        }
    }

    public function translate($block)
    {
        $result = array();
        $languages = $this->getLanguages();
        $default_language = (int) Configuration::getGlobalValue('PS_LANG_DEFAULT');

        $this->parseObjectFields($block, $result, 'Product');

        if (!isset($block->attributes()->{'autodelete-combinations'})) {
            $result['autodelete_combinations'] = true;
        } else {
            $result['autodelete_combinations'] = (bool) (int) $block->attributes()->{'autodelete-combinations'};
        }

        if ($block->unit_price) {
            $result['unit_price'] = (float) $block->unit_price;
        }

        if ($block->price_type) {
            $result['use_price_without_tax'] = ((string) $block->price_type == 'te') ? 1 : 0;
        } else {
            $result['use_price_without_tax'] = 1;
        }

        if ($block->tax_fields) {
            $result['tax_fields'] = explode(',', (string) $block->tax_fields);
        } else {
            $result['tax_fields'] = array();
        }

        if (isset($block->tax)) {
            if ($id_tax_rules_group = TaxRulesGroup::getIdByName((string) $block->tax)) {
                $result['id_tax_rules_group'] = array(
                    'value' => $id_tax_rules_group,
                    'modifier' => '',
                );
            }
        }

        if (isset($block->carrier)) {
            $result['carriers'] = array();
            foreach ($block->carrier as $carrier) {
                $result['carriers'][] = (int) $carrier;
            }
        }

        if (isset($block->combinations)) {
            $result['combinations'] = array();
            foreach ($block->combinations as $combination) {
                $array = array();
                if (isset($combination->id)) {
                    $array['id'] = (string) $combination->id;
                }

                if (isset($combination['external-reference'])) {
                    $array['external_reference'] = (string) $combination['external-reference'];
                }

                foreach (Combination::$definition['fields'] as $field => $value) {
                    if ($combination->$field) {
                        if (isset($value['lang']) && $value['lang']) {
                            foreach ($combination->$field as $subblock) {
                                if (!isset($result[$field])) {
                                    $array[$field] = array();
                                }

                                if (isset($languages[(string) $subblock['lang']])) {
                                    $array[$field][$languages[(string) $subblock['lang']]] = (string) $subblock;
                                } elseif (!(string) $subblock['lang']) {
                                    $array[$field][$default_language] = (string) $subblock;
                                }
                            }
                        } else {
                            $array[$field] = (string) $combination->$field;
                        }
                    }
                }

                if (isset($combination->images)) {
                    // There are two cases :
                    //   - using url (new way)
                    //   - using position (old way, deprecated)
                    if (isset($combination->images->url)) {
                        $array['images'] = array(
                            'url' => array(),
                        );

                        if (isset($combination->images['update'])) {
                            $array['images']['update'] = (int) $combination->images['update'];
                        } else {
                            $array['images']['update'] = 1;
                        }

                        foreach ($combination->images->url as $url) {
                            $array['images']['url'][] = array(
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
                    } else {
                        $array['images_by_position'] = array();
                        foreach ($combination->images as $image) {
                            $array['images_by_position'][] = (string) $image;
                        }
                    }
                }

                $array['attributes'] = array();
                $array['external_reference_attributes'] = array();
                if (isset($combination->attributes)) {
                    foreach ($combination->attributes as $attribute) {
                        if (isset($attribute['use-external-reference'])) {
                            $array['external_reference_attributes'][] = (string) $attribute;
                        } else {
                            $array['attributes'][] = (int) $attribute;
                        }
                    }
                }

                $result['combinations'][] = $array;
            }
        }

        $result['categorypath'] = array();
        foreach ($block->categorypath as $categorypath) {
            if (empty($categorypath['separator'])) {
                $categorypath['separator'] = '>';
            }

            $item = explode((string) $categorypath['separator'], (string) $categorypath);
            $item = array_map('trim', $item);
            if (count($item) && empty($item[0])) {
                unset($item[0]);
            }

            $result['categorypath'][] = $item;
        }

        if (isset($block->feature)) {
            $result['features'] = array();
            foreach ($block->feature as $feature) {
                $feature_data = array();
                if (isset($feature['id'])) {
                    $feature_data['id'] = (int) $feature['id'];
                } elseif (isset($feature['external-reference'])) {
                    $feature_data['external_reference'] = (string) $feature['external-reference'];
                } else {
                    $feature_data['name'] = (string) $feature['name'];
                }

                if (isset($feature['id-value'])) {
                    $feature_data['id_value'] = (int) $feature['id-value'];
                } elseif (isset($feature['external-reference-value'])) {
                    $feature_data['external_reference_value'] = (string) $feature['external-reference-value'];
                } else {
                    $feature_data['name_value'] = (string) $feature['name-value'];
                }

                if (isset($feature['custom']) && $feature['custom'] == 1) {
                    $feature_data['custom'] = true;
                } else {
                    $feature_data['custom'] = false;
                }

                $result['features'][] = $feature_data;
            }
        }

        return $result;
    }
}
