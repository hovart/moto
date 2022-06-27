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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/cron.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/csvtemplate.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/xslt.php';

class AdminAdvancedImporterTemplateAssistantController extends ModuleAdminController
{
    protected $steps = array(
        'index',
        'identifier',
        'name',
        'price',
        'category',
        'image',
        'stock',
        'feature',
        'supplier',
        'end',
    );
    protected $xpathes = array();

    public function __construct()
    {
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->bootstrap = true;
        parent::__construct();

        $this->loadObjectModel();
        $this->processForm();
    }

    public function goToNextStep()
    {
        $currentStepIndex = array_search($this->getStep(), $this->steps);
        $this->step = $this->steps[$currentStepIndex + 1];

        if ($this->getStep() == 'end') {
            return $this->processEndForm();
        }

    }

    public function loadObjectModel()
    {
        if ($file = Tools::getValue('file')) {
            // Does it's a CSV or an XML ?
            if (preg_match('/\.xml$/Usi', $file)) {
                $this->loadObjectFromXmlFile(_PS_MODULE_DIR_.'advancedimporter/flows/import/'.$file);
                $this->isXml = true;
            } elseif (preg_match('/\.csv/Usi', $file)) {
                $this->loadObjectFromCsvFile(_PS_MODULE_DIR_.'advancedimporter/flows/import/'.$file);
                $this->isXml = false;
            }
        } elseif (Tools::getValue('xslt')) {
            $this->object = new Xslt((int)Tools::getValue('xslt'));
            $this->isXml = true;
        } elseif (Tools::getValue('csv-template')) {
            $this->object = new CsvTemplate((int)Tools::getValue('csv-template'));
            $this->isXml = false;
        } else {
            throw new Exception('Missing file, xslt or csv template');
        }

    }

    public function setMedia()
    {
        $this->addJS(_PS_MODULE_DIR_.'advancedimporter/views/js/templateassistant.js');
        return parent::setMedia();
    }

    protected function processForm()
    {
        $step = Tools::getValue('step');
        if (empty($step)) {
            return;
        }

        if (!in_array($step, $this->steps)) {
            return;
        }

        $method = 'process'.Tools::ucfirst($step).'Form';

        return $this->$method();
    }

    protected function getStep()
    {
        $step = Tools::getValue('step');

        if (empty($step)) {
            $step = 'index';
        }

        if (isset($this->step)) {
            $step = $this->step;
        }

        return $step;
    }

    public function renderList()
    {
        $step = $this->getStep();
        if (empty($step)) {
            return;
        }

        if (!in_array($step, $this->steps)) {
            return;
        }

        $method = 'render'.Tools::ucfirst($step).'Form';

        return $this->$method();
    }

    protected function renderEndForm()
    {
    }

    protected function processEndForm()
    {
        if ($this->isXml) {
            $template = $this->getXmlTemplate();
        } else {
            $template = $this->getCsvTemplate();
        }
        if ($this->isXml) {
            $this->object->xml = $template;
            $this->object->use_tpl = false;
        } else {
            $this->object->template = $template;
            $this->object->advanced_mode = true;
        }
        $this->object->save();
        $this->confirmations[] = $this->l('The template is save');
    }

    protected function renderFeatureForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Features'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                ),
            )
        );

        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Feature', $this->context->language->id);
        } else {
            $collection = new Collection('Feature', $this->context->language->id);
        }

        foreach ($collection as $feature) {
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => sprintf($this->l('Feature %s'), $feature->name),
                'name' => 'feature_'.$feature->id,
                'options' => array(
                    'query' => $this->getNodeList(),
                    'id' => 'id',
                    'name' => 'label',
                ),
            );
        }

        $fields_form['form']['submit'] = array(
            'name' => 'submit_configuration',
            'title' => $this->l('Save')
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        foreach ($collection as $feature) {
            $helper->fields_value['feature_'.$feature->id] = Tools::getValue(
                'feature_'.$feature->id,
                Tools::getValue(
                    'name_'.$feature->id,
                    isset($this->object->schema['feature_'.$feature->id]) ?
                    $this->object->schema['feature_'.$feature->id] : ''
                )
            );
        }

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processFeatureForm()
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Feature', $this->context->language->id);
        } else {
            $collection = new Collection('Feature', $this->context->language->id);
        }

        foreach ($collection as $feature) {
            $this->object->schema['feature_'.$feature->id] = Tools::getValue('feature_'.$feature->id);
        }
        $this->object->save();

        $this->goToNextStep();
    }

    protected function renderSupplierForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Supplier and Manufacturer'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Supplier'),
                        'name' => 'supplier',
                        'options' => array(
                            'query' => $this->getNodeList(),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Manufacturer'),
                        'name' => 'manufacturer',
                        'options' => array(
                            'query' => $this->getNodeList(),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        $helper->fields_value['supplier'] = Tools::getValue(
            'supplier',
            Tools::getValue(
                'supplier',
                isset($this->object->schema['supplier']) ? $this->object->schema['supplier'] : null
            )
        );
        $helper->fields_value['manufacturer'] = Tools::getValue(
            'manufacturer',
            Tools::getValue(
                'manufacturer',
                isset($this->object->schema['manufacturer']) ? $this->object->schema['manufacturer'] : null
            )
        );

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processSupplierForm()
    {
        $this->object->schema['supplier'] = Tools::getValue('supplier');
        $this->object->schema['manufacturer'] = Tools::getValue('manufacturer');
        $this->object->save();

        $this->goToNextStep();
    }

    protected function renderStockForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Stock and status'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Stock'),
                        'name' => 'stock',
                        'options' => array(
                            'query' => $this->getNodeList(),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Status'),
                        'name' => 'active',
                        'options' => array(
                            'query' => $this->getNodeList(),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        $helper->fields_value['stock'] = Tools::getValue(
            'stock',
            Tools::getValue(
                'stock',
                isset($this->object->schema['stock']) ? $this->object->schema['stock'] : null
            )
        );
        $helper->fields_value['active'] = Tools::getValue(
            'active',
            Tools::getValue(
                'active',
                isset($this->object->schema['active']) ? $this->object->schema['active'] : null
            )
        );

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processStockForm()
    {
        $this->object->schema['stock'] = Tools::getValue('stock');
        $this->object->schema['active'] = Tools::getValue('active');
        $this->object->save();

        $this->goToNextStep();
    }

    protected function processCategoryForm()
    {
        for ($i = 1; $i <= 3; $i++) {
            $this->object->schema['category_path_'.$i] = Tools::getValue('category_path_'.$i.'_selected', array());
            $this->object->schema['category_separator_'.$i] = Tools::getValue('category_separator_'.$i);
        }
        $this->object->save();

        $this->goToNextStep();
    }

    protected function renderCategoryForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Categories'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                    array(
                        'type' => 'swap',
                        'label' => $this->l('Categorie 1'),
                        'name' => 'category_path_1',
                        'multilple' => true,
                        'size' => 10,
                        'desc' => $this->l('Set the category path in the correct order. ')
                            .$this->l('Set only one category attachement.'),
                        'options' => array(
                            'query' => $this->getNodeList(false),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Category separator 1'),
                        'name' => 'category_separator_1',
                        'desc' => $this->l('If the value if a path of category, set a separator. ')
                            .$this->l('For example, if your path looks like "cat 1 > cat2", set ">" as separator'),
                    ),
                    array(
                        'type' => 'swap',
                        'label' => $this->l('Categorie 2'),
                        'name' => 'category_path_2',
                        'multilple' => true,
                        'size' => 10,
                        'desc' => $this->l('Set the category path in the correct order. ')
                            .$this->l('Set only one category attachement.'),
                        'options' => array(
                            'query' => $this->getNodeList(false),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Category separator 2'),
                        'name' => 'category_separator_2',
                        'desc' => $this->l('If the value if a path of category, set a separator. ')
                            .$this->l('For example, if your path looks like "cat 1 > cat2", set ">" as separator'),
                    ),
                    array(
                        'type' => 'swap',
                        'label' => $this->l('Categorie 3'),
                        'name' => 'category_path_3',
                        'multilple' => true,
                        'size' => 10,
                        'desc' => $this->l('Set the category path in the correct order. ')
                            .$this->l('Set only one category attachement.'),
                        'options' => array(
                            'query' => $this->getNodeList(false),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Category separator 3'),
                        'name' => 'category_separator_3',
                        'desc' => $this->l('If the value if a path of category, set a separator. ')
                            .$this->l('For example, if your path looks like "cat 1 > cat2", set ">" as separator'),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        for ($i = 1; $i <= 3; $i++) {
            $helper->fields_value['category_path_'.$i] = Tools::getValue(
                'category_path_'.$i,
                Tools::getValue(
                    'category_path_'.$i.'_selected',
                    isset($this->object->schema['category_path_'.$i]) ?
                    $this->object->schema['category_path_'.$i] : array()
                )
            );
            $helper->fields_value['category_separator_'.$i] = Tools::getValue(
                'category_separator_'.$i,
                Tools::getValue(
                    'category_separator_'.$i,
                    isset($this->object->schema['category_separator_'.$i]) ?
                    $this->object->schema['category_separator_'.$i] : ''
                )
            );
        }

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function renderImageForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Images'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                    array(
                        'type' => 'swap',
                        'label' => $this->l('Images'),
                        'name' => 'images',
                        'multilple' => true,
                        'size' => 10,
                        'options' => array(
                            'query' => $this->getNodeList(false),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        $helper->fields_value['images'] = Tools::getValue(
            'images',
            Tools::getValue(
                'images_selected',
                isset($this->object->schema['images']) ? $this->object->schema['images'] : array()
            )
        );

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processImageForm()
    {
        $this->object->schema['images'] = Tools::getValue('images_selected');
        $this->object->save();

        $this->goToNextStep();
    }

    protected function renderPriceForm()
    {
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('TaxRulesGroup');
        } else {
            $collection = new Collection('TaxRulesGroup');
        }

        $collection->where('active', '=', true);

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Price and taxes'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Price'),
                        'name' => 'price',
                        'options' => array(
                            'query' => $this->getNodeList(),
                            'id' => 'id',
                            'name' => 'label',
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Tax rules group'),
                        'name' => 'id_tax_rules_group',
                        'options' => array(
                            'query' => $collection,
                            'id' => 'id',
                            'name' => 'name',
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Include tax'),
                        'name' => 'ti',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                        'desc' => $this->l('Does the price in the document include the taxes?'),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save'),
                ),
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        $helper->fields_value['price'] = Tools::getValue(
            'price',
            Tools::getValue(
                'price',
                isset($this->object->schema['price']) ? $this->object->schema['price'] : ''
            )
        );
        $helper->fields_value['id_tax_rules_group'] = Tools::getValue(
            'id_tax_rules_group',
            Tools::getValue(
                'id_tax_rules_group',
                isset($this->object->schema['id_tax_rules_group']) ? $this->object->schema['id_tax_rules_group'] : ''
            )
        );
        $helper->fields_value['ti'] = Tools::getValue(
            'ti',
            Tools::getValue(
                'ti',
                isset($this->object->schema['ti']) ? (bool) $this->object->schema['ti'] : false
            )
        );

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processPriceForm()
    {
        $this->object->schema['price'] = Tools::getValue('price');
        $this->object->schema['id_tax_rules_group'] = Tools::getValue('id_tax_rules_group');
        $this->object->schema['ti'] = Tools::getValue('ti');
        $this->object->save();

        $this->goToNextStep();
    }

    protected function renderNameForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Name and descrption'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                ),
            )
        );

        foreach ($this->context->controller->getLanguages() as $lang) {
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => sprintf($this->l('Product name (%s)'), $lang['iso_code']),
                'name' => 'name_'.$lang['iso_code'],
                'options' => array(
                    'query' => $this->getNodeList(),
                    'id' => 'id', 'name' => 'label'
                ),
            );
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => sprintf($this->l('Product description (%s)'), $lang['iso_code']),
                'name' => 'description_'.$lang['iso_code'],
                'options' => array(
                    'query' => $this->getNodeList(),
                    'id' => 'id', 'name' => 'label'
                ),
            );
        }

        $fields_form['form']['submit'] = array(
            'name' => 'submit_configuration',
            'title' => $this->l('Save')
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        foreach ($this->context->controller->getLanguages() as $lang) {
            $helper->fields_value['name_'.$lang['iso_code']] = Tools::getValue(
                'name_'.$lang['iso_code'],
                Tools::getValue(
                    'name_'.$lang['iso_code'],
                    isset($this->object->schema['name_'.$lang['iso_code']]) ?
                    $this->object->schema['name_'.$lang['iso_code']] : ''
                )
            );
            $helper->fields_value['description_'.$lang['iso_code']] = Tools::getValue(
                'description_'.$lang['iso_code'],
                Tools::getValue(
                    'description_'.$lang['iso_code'],
                    isset($this->object->schema['description_'.$lang['iso_code']]) ?
                    $this->object->schema['description_'.$lang['iso_code']] : ''
                )
            );
        }

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processNameForm()
    {
        foreach ($this->context->controller->getLanguages() as $lang) {
            $this->object->schema['name_'.$lang['iso_code']] = Tools::getValue('name_'.$lang['iso_code']);
            $this->object->schema['description_'.$lang['iso_code']] = Tools::getValue('description_'.$lang['iso_code']);
        }
        $this->object->save();
        $this->goToNextStep();
    }

    protected function renderIdentifierForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Identifier'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'object_id'
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Product identifier'),
                        'name' => 'identifier',
                        'options' => array(
                            'query' => $this->getNodeList(),
                            'id' => 'id', 'name' => 'label'
                        ),
                        'desc' => $this->l('Identifier is a uniq reference to identify the product.'),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Is the reference'),
                        'name' => 'is_reference',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                        'desc' => $this->l(
                            'Does the identifier is an EAN code? An ean code is an numeric with a maximum of 13 chars.'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Is an EAN code'),
                        'name' => 'is_ean',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                        'desc' => $this->l('Use this value as reference for the products.'),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['object_id'] = $this->object->id;
        $helper->fields_value['identifier'] = Tools::getValue(
            'identifier',
            isset($this->object->schema['identifier']) ? $this->object->schema['identifier'] : ''
        );

        $ref = false;
        if (isset($this->object->schema['reference'])) {
            $identifier = Tools::getValue('identifier', $this->object->schema['identifier']);
            $ref = $this->object->schema['reference'] == $identifier;
        }
        $helper->fields_value['is_reference'] = Tools::getValue(
            'is_reference',
            isset($this->object->schema['reference']) ? $ref : false
        );

        $ean = false;
        if (isset($this->object->schema['ean'])) {
            $identifier = Tools::getValue('identifier', $this->object->schema['identifier']);
            $ean = $this->object->schema['ean'] == $identifier;
        }
        $helper->fields_value['is_ean'] = Tools::getValue(
            'is_ean',
            isset($this->object->schema['ean']) ? $ean : false
        );

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function processIdentifierForm()
    {
        $this->object->schema['identifier'] = Tools::getValue('identifier');
        if (Tools::getValue('is_ean')) {
            $this->object->schema['ean'] = Tools::getValue('identifier');
        } else {
            $this->object->schema['ean'] = null;
        }
        if (Tools::getValue('is_reference')) {
            $this->object->schema['reference'] = Tools::getValue('identifier');
        } else {
            $this->object->schema['reference'] = null;
        }
        $this->object->save();
        $this->goToNextStep();
    }

    protected function processIndexForm()
    {
        if ($this->isXml) {
            $this->processXsltIndexForm();
        } else {
            $this->processCsvIndexForm();
        }
    }

    protected function processXsltIndexForm()
    {
        if (!$this->object->item_root) {
            $item_root = Tools::getValue('item_root');
            if (empty($item_root)) {
                $this->errors[] = $this->l('Missing item root');
                return;
            }

            // Try to load Xslt from item root
            if (class_exists('PrestaShopCollection')) {
                $collection = new PrestaShopCollection('Xslt');
            } else {
                $collection = new Collection('Xslt');
            }
            $collection->where('xpath_query', '=', $item_root);

            $object = $collection->getFirst();
            if ($object) {
                $this->object = new Xslt($object->id);
            } else {
                $this->object->item_root = $item_root;
                $this->object->xpath_query = $item_root;

                // Force reload nodes
                $file = Tools::getValue('file');
                $this->loadObjectFromXmlFile(_PS_MODULE_DIR_.'advancedimporter/flows/import/'.$file);
            }
        } else {
            $xpath_query = Tools::getValue('xpath_query');

            if (empty($xpath_query)) {
                $this->errors[] = $this->l('Missing Xpath query');
                return;
            }
            $this->object->xpath_query = $xpath_query;
        }

        // Xml cannot be empty
        if (empty($this->object->xml)) {
            $this->object->xml = ' ';
        }
        $this->object->advanced_mode = true;

        $this->object->save();

        $this->goToNextStep();
    }

    protected function processCsvIndexForm()
    {
        $filepath = Tools::getValue('filepath');
        if (empty($filepath)) {
            $this->errors[] = $this->l('Missing filepath');
            return;
        }


        // Try to load Csv Template from filename
        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('CsvTemplate');
        } else {
            $collection = new Collection('CsvTemplate');
        }
        $collection->where('filepath', '=', $filepath);

        $object = $collection->getFirst();
        if ($object) {
            $this->object = new CsvTemplate($object->id);
        } else {
            $this->object->filepath = $filepath;
        }

        // Template cannot be empty
        if (empty($this->object->template)) {
            $this->object->template = '<product />';
        }
        $this->object->advanced_mode = true;
        $this->object->ignore_first_line = (bool) Tools::getValue('ignore_first_line');
        $this->object->save();

        $this->goToNextStep();
    }

    protected function renderIndexForm()
    {
        if ($this->isXml) {
            return $this->renderXsltIndexForm();
        } else {
            return $this->renderCsvIndexForm();
        }
    }

    protected function renderXsltIndexForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Initialisation'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                ),
            ),
        );

        if (empty($this->object->item_root)) {
            $fields_form['form']['input'][] = array(
                'type' => 'select',
                'label' => $this->l('Item node'),
                'name' => 'item_root',
                'options' => array(
                    'query' => $this->mapXpathes($this->xpathes),
                    'id' => 'id',
                    'name' => 'name',
                ),
                'desc' => $this->l('Select the node of the items'),
            );
        } else {
            $fields_form['form']['input'][] = array(
                'type' => 'text',
                'label' => $this->l('Xpath'),
                'name' => 'xpath_query',
            );
        }

        $fields_form['form']['submit'] = array(
            'name' => 'submit_configuration',
            'title' => $this->l('Save'),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['item_root'] = $this->object->item_root;
        $helper->fields_value['xpath_query'] = $this->object->xpath_query;

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    protected function mapXpathes($xpathes)
    {
        $pathes = array(
            array(
                'id' => '',
                'name' => '',
            ),
        );
        foreach ($xpathes as $xpath) {
            $pathes[] = array(
                'id' => $xpath,
                'name' => $xpath,
            );
        }

        return $pathes;
    }

    protected function renderCsvIndexForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Initialisation'),
                    'icon' => 'icon-link'
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'step'
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('File path'),
                        'name' => 'filepath',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Ignore first line'),
                        'name' => 'ignore_first_line',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                        'desc' => $this->l('Does the first line must not be imported?'),
                    ),
                ),
                'submit' => array(
                    'name' => 'submit_configuration',
                    'title' => $this->l('Save')
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ?
            Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->fields_value['step'] = $this->getStep();
        $helper->fields_value['filepath'] = $this->object->filepath;
        $helper->fields_value['ignore_first_line'] = $this->object->ignore_first_line;

        $helper->languages = $this->context->controller->getLanguages();
        $helper->default_form_language = (int) $this->context->language->id;

        return $helper->generateForm(array($fields_form));
    }

    public function loadObjectFromXmlFile($file)
    {
        if (empty($this->object)) {
            $this->object = new Xslt(Tools::getValue('object_id', null));
        }

        if ($this->object->id) {
            return;
        }

        $this->object->nodes = array();

        $xml = new SimpleXmlElement(Tools::file_get_contents($file));

        // There are multiple steps

        if (!$this->object->item_root) {
            // First : determine the product xPath
            $this->recursiveGetItemRoot('/'.$xml->getName(), $xml, $this->xpathes);
        } else {
            // Second determine the nodes
            $this->object->nodes = array();
            foreach ($xml->xpath($this->object->item_root) as $key => $value) {
                if ($key > 5) {
                    break;
                }
                $this->recursiveSetNodes('.', $value, $this->object->nodes);
            }

            foreach ($this->object->nodes as $key => $value) {
                $this->object->nodes[$key]['value'] = Tools::substr(
                    implode(', ', $this->object->nodes[$key]['value']),
                    0,
                    50
                );
                $this->object->nodes[$key]['label'] = $this->object->nodes[$key]['id'].' : '
                    .$this->object->nodes[$key]['value'];
            }
        }
    }

    protected function recursiveSetNodes($path, $xml, &$nodes)
    {
        foreach ($xml as $key => $value) {
            if (!isset($nodes[$path.'/'.$key])) {
                $nodes[$path.'/'.$key] = array(
                    'id' => $path.'/'.$key,
                    'name' => $path.'/'.$key,
                    'label' => '',
                    'value' => array(),
                );
            }

            $str_value = trim((string) $value);
            if (!empty($str_value)) {
                $nodes[$path.'/'.$key]['value'][] = $str_value;
            }
            $this->recursiveSetNodes($path.'/'.$key, $value, $nodes);
        }

        foreach ($xml->attributes() as $attribute => $value) {
            if (!isset($nodes[$path.'/@'.$attribute])) {
                $nodes[$path.'/@'.$attribute] = array(
                    'id' => $path.'/@'.$attribute,
                    'name' => $path.'/@'.$attribute,
                    'label' => '',
                    'value' => array(),
                );
            }

            $str_value = trim((string) $value);
            if (!empty($str_value)) {
                $nodes[$path.'/@'.$attribute]['value'][] = htmlentities($str_value);
            }
        }
    }

    protected function recursiveGetItemRoot($path, $xml, &$pathes)
    {
        foreach ($xml as $key => $value) {
            if (in_array($path.'/'.$key, $pathes)) {
                return;
            }
            $pathes[] = $path.'/'.$key;
            $this->recursiveGetItemRoot($path.'/'.$key, $value, $pathes);
        }
    }

    public function loadObjectFromCsvFile($file)
    {
        $this->object = new CsvTemplate(Tools::getValue('object_id', null));
        if ($this->object->id) {
            return;
        }

        $this->object->filepath = preg_replace('/^.*\/([^\/]+)\.csv/Usi', '$1*.csv', $file);
        $this->object->nodes = array();

        $count = 0;
        $delimiter = $this->getDelimiter($file);
        if (($handle = fopen($file, 'r')) !== false) {
            while (($data = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$count) {
                    foreach ($data as $key => $value) {
                        $this->object->nodes[$key] = array(
                            'id' => $this->convertIntToLetters($key),
                            'name' => $value,
                            'label' => '',
                            'value' => array(),
                        );
                    }
                } else {
                    foreach ($data as $key => $value) {
                        $this->object->nodes[$key]['value'][] = $value;
                    }
                }

                if ($count > 5) {
                    break;
                }

                $count++;
            }

            foreach ($this->object->nodes as $key => $value) {
                $this->object->nodes[$key]['value'] = Tools::substr(
                    implode(', ', $this->object->nodes[$key]['value']),
                    0,
                    50
                );
                $this->object->nodes[$key]['label'] = $this->object->nodes[$key]['id'].' : '
                .$this->object->nodes[$key]['name']. ' : '.$this->object->nodes[$key]['value'];
            }
        } else {
            throw new Exception("Cannot open file $file");
        }
    }

    protected function getDelimiter($filepath)
    {
        if (($handle = fopen($filepath, 'r')) != false) {
            // Try to determine a delimiter
            $candidates = array(
                ',' => 0,
                ';' => 0,
                "\t" => 0,
                "|" => 0,
            );
            $row = 0;
            while (($data = fgets($handle)) !== false && $row < 500) {
                $stats = count_chars($data, 1);
                foreach (array_keys($candidates) as $key) {
                    if (!isset($stats[ord($key)])) {
                        unset($candidates[$key]);
                        continue;
                    }

                    $candidates[$key] += $stats[ord($key)];
                }
                $row++;
            }
            if (!empty($candidates)) {
                arsort($candidates);
                $candidates_keys = array_keys($candidates);
                return $candidates_keys[0];
            }
        }
        fclose($handle);
        return ';';
    }

    public function convertIntToLetters($number)
    {
        $result = '';


        do {
            $rest = $number % 26;
            $number = ($number - $rest) / 26;

            if (!empty($result)) {
                $rest--;
            }
            $result = chr(65 + $rest).$result;
        } while ($number != 0);

        return $result;
    }

    public function convertLettersToInt($letters)
    {
        if (is_numeric($letters)) {
            return $letters - 1;
        }

        $numeric = 0;
        $digit = 0;
        foreach (array_reverse(str_split($letters)) as $letter) {
            $numeric += (ord(Tools::strtolower($letter)) - 96) * pow(26, $digit);
            ++$digit;
        }

        return $numeric - 1;
    }

    protected function getNodeList($addEmpty = true)
    {
        if (!$addEmpty) {
            return $this->object->nodes;
        }

        return array_merge(
            array(
                array(
                    'id' => '',
                    'label' => '',
                ),
            ),
            $this->object->nodes
        );
    }

    protected function formatXml($xml, $ignore_header = false)
    {
        $dom = new DOMDocument("1.0");
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXml());

        if ($ignore_header) {
            return $dom->saveXML($dom->documentElement);
        }

        return $dom->saveXML();
    }

    protected function getXmlTemplate()
    {
        $xpath_root = preg_replace('/^\/([0-9a-z_-]+)\/.+$/Usi', '/$1', $this->object->item_root);
        $item_root = preg_replace('/^\/[0-9a-z_-]+\/(.+)$/Usi', './$1', $this->object->item_root);

        $xml = '<!DOCTYPE xsl:stylesheet  [
    <!ENTITY nbsp   " ">
    <!ENTITY copy   "©">
    <!ENTITY reg    "®">
    <!ENTITY trade  "™">
    <!ENTITY mdash  "—">
    <!ENTITY ldquo "“">
    <!ENTITY rdquo "”">
    <!ENTITY pound  "£">
    <!ENTITY yen    "¥">
    <!ENTITY euro   "€">
]>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="xml" encoding="utf-8" indent="yes"/>
    <xsl:variable name="cdataStart"><![CDATA[ <![CDATA ]]></xsl:variable>
    <xsl:variable name="cdataEnd"><![CDATA[ ]] ]]></xsl:variable>
    <xsl:template match="'.$xpath_root.'">
        <products>
            <xsl:for-each select="'.$item_root.'">
                <product />
            </xsl:for-each>
        </products>
    </xsl:template>
</xsl:stylesheet>';

        $template = new SimpleXmlElement($xml);
        $content = $template->xpath('*/*/*/product')[0];


        if (!empty($this->object->schema['identifier'])) {
            $node = $content->addChild('xsl:attribute', null, 'http://www.w3.org/1999/XSL/Transform');
            $node->addAttribute('name', 'external-reference');
            $subnode = $node->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subnode->addAttribute('select', $this->getXmlValue('identifier'));
        }

        $block_node = $content->addChild('block');

        $this->addXmlNode($content, 'ean', 'ean13');
        $this->addXmlNode($content, 'reference');
        $this->addXmlNode($content, 'price');

        if (!empty($this->object->schema['id_tax_rules_group'])) {
            $content->addChild('id_tax_rules_group', $this->object->schema['id_tax_rules_group']);
        }

        foreach ($this->context->controller->getLanguages() as $lang) {
            $this->addLangXmlNode($content, 'name', $lang['iso_code']);
            $this->addLangXmlNode($content, 'description', $lang['iso_code']);
        }

        if (!empty($this->object->schema['images'])) {
            $node = $content->addChild('images');
            $node->addAttribute('insertion', 'replace');
            foreach ($this->object->schema['images'] as $image) {
                $subnode = $node->addChild('xsl:for-each', null, 'http://www.w3.org/1999/XSL/Transform');
                $subnode->addAttribute('select', $image);
                $subsubnode = $subnode->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
                $subsubnode->addAttribute('test', "current() != ''");
                $subsubsubnode = $subsubnode->addChild('url', null, '');
                $subsubsubsubnode = $subsubsubnode->addChild(
                    'xsl:value-of',
                    null,
                    'http://www.w3.org/1999/XSL/Transform'
                );
                $subsubsubsubnode->addAttribute('select', 'current()');
            }
        }

        for ($i = 1; $i <= 3; $i++) {
            if (!empty($this->object->schema['category_path_'.$i])) {
                $separator = $this->object->schema['category_separator_'.$i];
                if (empty($separator)) {
                    $separator = '>';
                }

                $node = $content->addChild('categorypath');
                $node->addAttribute('separator', $separator);

                $first = true;
                foreach ($this->object->schema['category_path_'.$i] as $category) {
                    $subnode = $node->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
                    $subnode->addAttribute('test', "$category != ''");

                    if (!$first) {
                        $separator_node = $subnode->addChild(
                            'xsl:value-of',
                            null,
                            'http://www.w3.org/1999/XSL/Transform'
                        );
                        $separator_node->addAttribute('select', "concat('$separator', '')");
                    }

                    $subsubnode = $subnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
                    $subsubnode->addAttribute('select', $category);

                    $first = false;
                }
            }
        }

        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Feature', $this->context->language->id);
        } else {
            $collection = new Collection('Feature', $this->context->language->id);
        }

        foreach ($collection as $feature) {
            $feature_value = $this->object->schema['feature_'.$feature->id];
            if (empty($feature_value)) {
                continue;
            }
            $node = $content->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
            $node->addAttribute('test', $this->getXmlValue('feature_'.$feature->id));
            $subnode = $node->addChild('feature', null, '');
            $subnode->addAttribute('id', $feature->id);
            $subsubnode = $subnode->addChild('xsl:attribute', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubnode->addAttribute('name', 'name-value');
            $subsubsubnode = $subsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubnode->addAttribute('select', $this->getXmlValue('feature_'.$feature->id));
        }

        $stock = $this->object->schema['stock'];
        if (!empty($stock)) {
            $node = $block_node->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
            $node->addAttribute('test', $this->getXmlValue('stock'));
            $subnode = $node->addChild('stocks', null, '');
            $subsubnode = $subnode->addChild('stock', null, '');
            $subsubnode->addChild('product', '{{id}}', '');
            $subsubsubnode = $subsubnode->addChild('quantity', null, '');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('stock'));
        }

        $supplier = $this->object->schema['supplier'];
        if (!empty($supplier)) {
            $node = $block_node->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
            $node->addAttribute('test', $this->getXmlValue('supplier'));
            $subnode = $node->addChild('objects', null, '');
            $subsubnode = $subnode->addChild('object', null, '');
            $subsubnode->addAttribute('type', 'Supplier');
            $subsubnode->addAttribute('update', '0');
            $subsubsubnode = $subsubnode->addChild('xsl:attribute', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubnode->addAttribute('name', 'external-reference');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('supplier'));
            $subsubnode->addChild('active', 1, '');
            $subsubsubnode = $subsubnode->addChild('name', null, '');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('supplier'));

            $subsubnode = $subnode->addChild('object', null, '');
            $subsubnode->addAttribute('type', 'ProductSupplier');
            $subsubnode->addAttribute('update', '0');
            $subsubsubnode = $subsubnode->addChild('xsl:attribute', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubnode->addAttribute('name', 'external-reference');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('supplier'));
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', "concat('-', '{{id}}')");

            $subsubnode->addChild('id_product', '{{id}}', '');
            $subsubnode->addChild('id_product_attribute', 0, '');
            $subsubsubnode = $subsubnode->addChild('external_reference', null, '');
            $subsubsubnode->addAttribute('for', 'id_supplier');
            $subsubsubnode->addAttribute('type', 'Supplier');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('supplier'));
        }

        $manufacturer = $this->object->schema['manufacturer'];
        if (!empty($manufacturer)) {
            $node = $block_node->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
            $node->addAttribute('test', $this->getXmlValue('manufacturer'));
            $subnode = $node->addChild('objects', null, '');
            $subsubnode = $subnode->addChild('object', null, '');
            $subsubnode->addAttribute('type', 'Manufacturer');
            $subsubnode->addAttribute('update', '0');
            $subsubsubnode = $subsubnode->addChild('xsl:attribute', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubnode->addAttribute('name', 'external-reference');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('manufacturer'));
            $subsubnode->addChild('active', 1, '');
            $subsubsubnode = $subsubnode->addChild('name', null, '');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('manufacturer'));

            $subnode = $node->addChild('products', null, '');
            $subsubnode = $subnode->addChild('product', null, '');
            $subsubnode->addChild('id', '{{id}}', '');

            $subsubsubnode = $subsubnode->addChild('external_reference', null, '');
            $subsubsubnode->addAttribute('for', 'id_manufacturer');
            $subsubsubnode->addAttribute('type', 'Manufacturer');
            $subsubsubsubnode = $subsubsubnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
            $subsubsubsubnode->addAttribute('select', $this->getXmlValue('manufacturer'));
        }

        $this->addXmlNode($content, 'active');
        if (empty($this->object->schema['active'])) {
            $content->addChild('active', 1);
        }

        $content->addChild(
            'price_type',
            $this->object->schema['ti'] ? 'ti' : 'te'
        );

        return $this->formatXml($template);
    }

    protected function getXmlValue($key)
    {
        return $this->object->schema[$key];
    }

    protected function addXmlNode($template, $key, $attributeName = null)
    {
        if (empty($this->object->schema[$key])) {
            return;
        }

        if (empty($attributeName)) {
            $attributeName = $key;
        }

        $node = $template->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
        $node->addAttribute('test', $this->getXmlValue($key));
        $subnode = $node->addChild($attributeName, null, '');
        $subsubnode = $subnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
        $subsubnode->addAttribute('select', $this->getXmlValue($key));
    }

    protected function addLangXmlNode($template, $key, $lang, $attributeName = null)
    {
        if (empty($this->object->schema[$key.'_'.$lang])) {
            return;
        }

        if (empty($attributeName)) {
            $attributeName = $key;
        }

        $node = $template->addChild('xsl:if', null, 'http://www.w3.org/1999/XSL/Transform');
        $node->addAttribute('test', $this->getXmlValue($key.'_'.$lang));
        $subnode = $node->addChild($attributeName, null, '');
        $subnode->addAttribute('lang', $lang);
        $subsubnode = $subnode->addChild('xsl:value-of', null, 'http://www.w3.org/1999/XSL/Transform');
        $subsubnode->addAttribute('select', $this->getXmlValue($key.'_'.$lang));
    }

    protected function getCsvTemplate()
    {
        $template = new SimpleXmlElement('<product />');
        if (!empty($this->object->schema['identifier'])) {
            $template->addAttribute('external-reference', $this->getCsvValue('identifier'));
        }

        $block_node = $template->addChild('block');

        $this->addCsvNode($template, 'ean', 'ean13');
        $this->addCsvNode($template, 'reference');
        $this->addCsvNode($template, 'price');

        if (!empty($this->object->schema['id_tax_rules_group'])) {
            $template->addChild('id_tax_rules_group', $this->object->schema['id_tax_rules_group']);
        }

        $this->addCsvNode($template, 'id_tax_rules_group');
        foreach ($this->context->controller->getLanguages() as $lang) {
            $this->addLangCsvNode($template, 'name', $lang['iso_code']);
            $this->addLangCsvNode($template, 'description', $lang['iso_code']);
        }

        if (!empty($this->object->schema['images'])) {
            $node = $template->addChild('images');
            $node->addAttribute('insertion', 'replace');
            foreach ($this->object->schema['images'] as $image) {
                $subnode = $node->addChild('csv_if_not_null');
                $subnode->addAttribute('column', $this->convertLettersToInt($image));
                $subnode->addChild('url', '{{'.$this->convertLettersToInt($image).'}}');
            }
        }

        for ($i = 1; $i <= 3; $i++) {
            if (!empty($this->object->schema['category_path_'.$i])) {
                $separator = $this->object->schema['category_separator_'.$i];
                if (empty($separator)) {
                    $separator = '>';
                }

                $node = $template->addChild('categorypath');
                $node->addAttribute('separator', $separator);

                $first = true;
                foreach ($this->object->schema['category_path_'.$i] as $category) {
                    $text = '';
                    if (!$first) {
                        $text = $separator;
                    }
                    $text .= '{{'.$this->convertLettersToInt($category).'}}';

                    $subnode = $node->addChild('csv_if_not_null', $text);
                    $subnode->addAttribute('column', $this->convertLettersToInt($category));

                    $first = false;
                }
            }
        }

        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('Feature', $this->context->language->id);
        } else {
            $collection = new Collection('Feature', $this->context->language->id);
        }

        foreach ($collection as $feature) {
            $feature_value = $this->object->schema['feature_'.$feature->id];
            if (empty($feature_value)) {
                continue;
            }
            $node = $template->addChild('csv_if_not_null');
            $node->addAttribute('column', $this->getCsvValue('feature_'.$feature->id, false));
            $subnode = $node->addChild('feature');
            $subnode->addAttribute('id', $feature->id);
            $subnode->addAttribute('name-value', $this->getCsvValue('feature_'.$feature->id));
        }

        $stock = $this->object->schema['stock'];
        if (!empty($stock)) {
            $node = $block_node->addChild('csv_if_not_null');
            $node->addAttribute('column', $this->getCsvValue('stock', false));
            $subnode = $node->addChild('stocks');
            $subsubnode = $subnode->addChild('stock');
            $subsubnode->addChild('product', '{{id}}');
            $subsubnode->addChild('quantity', $this->getCsvValue('stock'));
        }

        $supplier = $this->object->schema['supplier'];
        if (!empty($supplier)) {
            $node = $block_node->addChild('csv_if_not_null');
            $node->addAttribute('column', $this->getCsvValue('supplier', false));
            $subnode = $node->addChild('objects');
            $subsubnode = $subnode->addChild('object');
            $subsubnode->addAttribute('type', 'Supplier');
            $subsubnode->addAttribute('update', '0');
            $subsubnode->addAttribute('external-reference', $this->getCsvValue('supplier'));
            $subsubnode->addChild('active', 1);
            $subsubnode->addChild('name', $this->getCsvValue('supplier'));

            $subsubnode = $subnode->addChild('object');
            $subsubnode->addAttribute('type', 'ProductSupplier');
            $subsubnode->addAttribute('update', '0');
            $subsubnode->addAttribute('external-reference', $this->getCsvValue('supplier').'-{{id}}');

            $subsubnode->addChild('id_product', '{{id}}');
            $subsubnode->addChild('id_product_attribute', 0);
            $subsubsubnode = $subsubnode->addChild('external_reference', $this->getCsvValue('supplier'));
            $subsubsubnode->addAttribute('for', 'id_supplier');
            $subsubsubnode->addAttribute('type', 'Supplier');
        }

        $manufacturer = $this->object->schema['manufacturer'];
        if (!empty($manufacturer)) {
            $node = $block_node->addChild('csv_if_not_null');
            $node->addAttribute('column', $this->getCsvValue('manufacturer', false));
            $subnode = $node->addChild('objects');
            $subsubnode = $subnode->addChild('object');
            $subsubnode->addAttribute('type', 'Manufacturer');
            $subsubnode->addAttribute('update', '0');
            $subsubnode->addAttribute('external-reference', $this->getCsvValue('manufacturer'));
            $subsubnode->addChild('active', 1);
            $subsubsubnode = $subsubnode->addChild('name', $this->getCsvValue('manufacturer'));

            $subnode = $node->addChild('products');
            $subsubnode = $subnode->addChild('product');
            $subsubnode->addChild('id', '{{id}}');

            $subsubsubnode = $subsubnode->addChild('external_reference', $this->getCsvValue('manufacturer'));
            $subsubsubnode->addAttribute('for', 'id_manufacturer');
            $subsubsubnode->addAttribute('type', 'Manufacturer');
        }

        $this->addCsvNode($template, 'active');
        if (empty($this->object->schema['active'])) {
            $template->addChild('active', 1);
        }

        $template->addChild(
            'price_type',
            $this->object->schema['ti'] ? 'ti' : 'te'
        );

        return $this->formatXml($template, true);
    }

    protected function getCsvValue($key, $format = true)
    {
        if ($format) {
            return '{{'.$this->convertLettersToInt($this->object->schema[$key]).'}}';
        }
        return $this->convertLettersToInt($this->object->schema[$key]);
    }

    protected function addCsvNode($template, $key, $attributeName = null)
    {
        if (empty($this->object->schema[$key])) {
            return;
        }

        if (empty($attributeName)) {
            $attributeName = $key;
        }

        $node = $template->addChild('csv_if_not_null');
        $node->addAttribute('column', $this->getCsvValue($key, false));
        $node->addChild($attributeName, $this->getCsvValue($key));
    }

    protected function addLangCsvNode($template, $key, $lang, $attributeName = null)
    {
        if (empty($this->object->schema[$key.'_'.$lang])) {
            return;
        }

        if (empty($attributeName)) {
            $attributeName = $key;
        }

        $node = $template->addChild('csv_if_not_null');
        $node->addAttribute('column', $this->getCsvValue($key.'_'.$lang, false));
        $subnode = $node->addChild($attributeName, $this->getCsvValue($key.'_'.$lang));

        $subnode->addAttribute('lang', $lang);
    }
}
