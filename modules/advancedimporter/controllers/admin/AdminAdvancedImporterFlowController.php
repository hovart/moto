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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/flow.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/block.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/converter/csvconverter.php';
require_once _PS_MODULE_DIR_.'advancedimporter/classes/csvtemplate.php';

class AdminAdvancedImporterFlowController extends ModuleAdminController
{
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public function __construct()
    {
        $this->table = 'advancedimporter_flow';
        $this->className = 'Flow';
        $this->list_no_link = true;

        $this->module = 'advancedimporter';
        $this->multishop_context = Shop::CONTEXT_ALL;

        $this->_orderBy = 'id_advancedimporter_flow';
        $this->_orderWay = 'DESC';

        $this->bootstrap = true;

        $this->addWaitingFlows();

        $status_list = array(
            FLOW::STATUS_ERROR => $this->l('Error'),
            FLOW::STATUS_WAITING => $this->l('Waiting'),
            FLOW::STATUS_PROCESSING => $this->l('Processing the file'),
            FLOW::STATUS_IMPORTING => $this->l('Importing'),
            FLOW::STATUS_FINISHED => $this->l('Finished'),
        );
        $this->fields_list = array(
            'id_advancedimporter_flow' => array(
                'title' => $this->l('ID'),
                'align' => 'center',
                'width' => 30,
            ),
            'date_add' => array(
                'title' => $this->l('Created at'),
                'align' => 'center',
                'width' => 120,
            ),
            'started_at' => array(
                'title' => $this->l('Started at'),
                'align' => 'center',
                'width' => 120,
            ),
            'ended_at' => array(
                'title' => $this->l('Ended at'),
                'align' => 'center',
                'width' => 120,
            ),
            'filename' => array(
                'title' => $this->l('filename'),
                'align' => 'left',
            ),
            'path' => array(
                'title' => $this->l('path'),
                'align' => 'left',
            ),
            'block_count' => array(
                'title' => $this->l('Number of blocks'),
                'align' => 'left',
                'width' => 60,
            ),
            'success_count' => array(
                'title' => $this->l('Number of success'),
                'align' => 'left',
                'width' => 60,
            ),
            'error_count' => array(
                'title' => $this->l('Number of errors'),
                'align' => 'left',
                'width' => 60,
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'align' => 'center',
                'type' => 'select',
                'list' => $status_list,
                'filter_key' => 'status',
                'width' => 60,
            ),
        );

        $this->addRowAction('preview');
        $this->addRowAction('template');
        $this->addRowAction('downloadreport');
        $this->addRowAction('download');
        $this->addRowAction('delete');
        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete'),
                'confirm' => $this->l('Are you sure?'),
            ),
        );

        parent::__construct();

        $this->renderForm();
    }

    /**
     * AdminController::getList() override.
     *
     * @see AdminController::getList()
     */
    public function getList(
        $id_lang,
        $order_by = null,
        $order_way = null,
        $start = 0,
        $limit = null,
        $id_lang_shop = false
    ) {
        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);

        foreach ($this->_list as &$item) {
            switch ($item['status']) {
                case FLOW::STATUS_ERROR:
                    $item['status'] = $this->l('Error');
                    break;
                case FLOW::STATUS_WAITING:
                    $item['status'] = $this->l('Waiting');
                    break;
                case FLOW::STATUS_PROCESSING:
                    $item['status'] = $this->l('Processing the file');
                    break;
                case FLOW::STATUS_IMPORTING:
                    $item['status'] = $this->l('Importing');
                    break;
                case FLOW::STATUS_FINISHED:
                    $item['status'] = $this->l('Finished');
                    break;
            }
        }
    }


    /*
    public function postProcess()
    {
        switch (Tools::getValue('customaction')) {
            case 'delete':
                $this->deleteAll();
                break;
            default:
                break;
        }

        return parent::postProcess();
    }

    public function deleteAll()
    {
        Db::getInstance()->execute('truncate `'._DB_PREFIX_.$this->table.'`');
    }
     */

    public function initToolbar()
    {
        $return = parent::initToolbar();
        unset($this->toolbar_btn['new']);

        $this->toolbar_btn['new'] = array(
            'href' => $this->context->link->getAdminLink('AdminAdvancedImporterUpload'),
            'desc' => $this->l('Add a new flow'),
        );
        return $return;
    }

    /**
     * Custom action icon "download".
     */
    public function displayDownloadLink($token = null, $id = null)
    {
        if (!array_key_exists('download', self::$cache_lang)) {
            self::$cache_lang['download'] = $this->l('Download flow');
        }

        $this->context->smarty->assign(array(
            'module_dir' => __PS_BASE_URI__.'modules/advancedimporter/',
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&download&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['download'],
            'classes' => '',
        ));

        if (version_compare(_PS_VERSION_, '1.6', '>')) {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/download.tpl'
            );
        } else {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/download-1.5.tpl'
            );
        }
    }

    /**
     * Custom action icon "view".
     */
    public function displayPreviewLink($token = null, $id = null)
    {
        if (!array_key_exists('preview', self::$cache_lang)) {
            self::$cache_lang['preview'] = $this->l('View');
        }

        $this->context->smarty->assign(array(
            'module_dir' => __PS_BASE_URI__.'modules/advancedimporter/',
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&preview&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['preview'],
            'classes' => 'btn btn-default js-preview-flow',
        ));

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/preview.tpl'
        );
    }

    /**
     * Custom action icon "template".
     */
    public function displayTemplateLink($token = null, $id = null)
    {
        if (!array_key_exists('template', self::$cache_lang)) {
            self::$cache_lang['template'] = $this->l('Create a template');
        }

        $flow = new Flow($id);
        if ($flow->status != Flow::STATUS_WAITING) {
            return false;
        }

        $this->context->smarty->assign(array(
            'module_dir' => __PS_BASE_URI__.'modules/advancedimporter/',
            'href' => $this->context->link->getAdminLink('AdminAdvancedImporterTemplateAssistant')
            .'&file='.urlencode($flow->path),
            'action' => self::$cache_lang['template'],
            'classes' => '',
        ));

        return $this->context->smarty->fetch(
            _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/template.tpl'
        );
    }

    /**
     * Custom action icon "download report".
     */
    public function displayDownloadReportLink($token = null, $id = null)
    {
        if (!array_key_exists('downloadreport', self::$cache_lang)) {
            self::$cache_lang['downloadreport'] = $this->l('Download report');
        }

        $this->context->smarty->assign(array(
            'module_dir' => __PS_BASE_URI__.'modules/advancedimporter/',
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&downloadreport&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['downloadreport'],
            'classes' => '',
        ));

        if (version_compare(_PS_VERSION_, '1.6', '>')) {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/download.tpl'
            );
        } else {
            return $this->context->smarty->fetch(
                _PS_MODULE_DIR_.'advancedimporter/views/templates/admin/list_action/download-1.5.tpl'
            );
        }
    }

    protected function preview()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }
        $smarty = Context::getContext()->smarty;
        $smarty->assign('flow', $obj);
        $smarty->assign('jquery', '../js/jquery/jquery-'._PS_JQUERY_VERSION_.'.min.js');
        $filepath = _PS_MODULE_DIR_.'advancedimporter/flows/import/'.$obj->path;

        if ($obj->type === 'csv') {
            $smarty->assign('content', $this->getCsvAsArray($filepath));
            $smarty->assign('fields', $this->getFieldsListAsArray());
            echo $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/previewCsv.tpl');
        } else {
            $smarty->assign('content', Tools::file_get_contents($filepath));
            echo $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/previewXml.tpl');
        }
    }

    protected function getFieldsListAsArray()
    {
        $values = $this->getFieldsList(true);
        $values[] = $this->module->l('external reference');
        asort($values);
        array_unshift($values, $this->module->l('Select a value'));
        return $values;
    }

    protected function getFieldsList($onlyValues = false)
    {
        $converter = new CsvConverter($this->module, 'product', 'product');

        if ($onlyValues) {
            return array_keys($converter->getAvailableNodes());
        }
        return $converter->getAvailableNodes();
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
                foreach ($candidates as $key => $values) {
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

    protected function getCsvAsArray($filepath)
    {
        $content = array();
        $columnCount = 0;

        $delimiter = $this->getDelimiter($filepath);

        if (($handle = fopen($filepath, 'r')) != false) {
            $row = 0;
            while (($data = fgetcsv($handle, null, $delimiter)) !== false && $row < 500) {
                $content[] = $data;
                $columnCount = max(count($data), $columnCount);
                $row++;
            }

            $this->setFirstLine($content, $columnCount);
        }
        fclose($handle);

        $json = array(
            'colHeaders' => array(),
            'columns' => array(),
            'data' => array(),
        );

        for ($i = 0; $i < $columnCount; $i++) {
            $json['colHeaders'][] = $this->toLetters($i);
        }

        foreach ($json['colHeaders'] as $key => $col) {
            $json['columns'][] = array(
                'data' => $key,
                'readOnly' => true,
            );
        }

        $json['data'] = $content;

        return $json;
    }

    protected function setFirstLine(&$content, $columnCount)
    {
        $header = array();
        for ($i = 0; $i < $columnCount; $i++) {
            $header[] = $this->module->l('Select a value');
        }

        $template = $this->getTemplateToUse();
        if ($template) {
            parse_str(urldecode($template->data), $values);
            foreach ($values['node'] as $value) {
                $array = array_keys($value['values']['node']);
                $key = $this->toInt(
                    $value['values']['node'][$array[count($value['values']['node']) - 1]]
                );
                $header[$key] = $value['name'];
            }

            if (!empty($values['external_reference'])) {
                $key = $this->toInt($values['external_reference']);
                $header[$key] = $this->module->l('external reference');
            }
        }

        array_unshift($content, $header);
    }

    protected function toInt($letters)
    {
        $length = Tools::strlen($letters);
        $result = 0;
        for ($i = $length - 1; $i >= 0; $i--) {
            $range = pow(26, ($length - $i - 1));
            $padding = 0;
            if ($range > 1) {
                $padding = 1;
            }
            $result += (ord($letters[$i]) - 65 + $padding) * $range;
        }
        return $result;
    }

    protected function toLetters($i)
    {
        $prefix = '';
        if ($i >= 26) {
            $prefix = $this->toLetters((int)$i/26 - 1);
        }
        return $prefix.chr(($i % 26) + 65);
    }

    protected function createCsvTemplate()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        // Use existing template?
        $template = $this->getTemplateToUse();
        $template_id = 0;
        if ($template) {
            $template_id = $template->id;
        }

        // Convert fields to form values
        $converter = new CsvConverter($this->module, 'product', 'product');
        $identifiers = $converter->getNodesIdentifiers();
        $attributes = $converter->getNodesAttributes();

        $form = array(
            'external_reference' => '',
            'node' => array(),
        );
        foreach (Tools::getValue('node') as $col => $value) {
            $letters = $this->toLetters($col);
            if ($value === $this->module->l('external reference')) {
                $form['external_reference'] = $letters;
            } elseif (in_array($value, array_keys($identifiers))) {
                $values = array();
                foreach ($identifiers[$value] as $identifier) {
                    $values[$identifier] = '';
                }
                $attrs = array();
                foreach ($attributes[$value] as $attribute) {
                    $attrs[$attribute] = '';
                }
                $values[$identifier] = $letters;
                $form['node'][] = array(
                    'name' => $value,
                    'condition' => $letters,
                    'values' => array(
                        'attributes' => $attrs,
                        'node' => $values,
                    ),
                );
            }
        }
        $query = http_build_query($form);
        $query = http_build_query(
            array(
                'data' => $query,
                'delimiter' => $this->getDelimiter(_PS_MODULE_DIR_.'advancedimporter/flows/import/'.$obj->path),
            )
        );

        $url = $this->context->link->getAdminLink('AdminAdvancedImporterCsvTemplate')
            .'&addadvancedimporter_csv_template'
            .'&id_advancedimporter_csv_template='.(int)$template_id
            .'&'.$query;

        Tools::redirectAdmin($url);
        die();
    }

    protected function getTemplateToUse()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if (class_exists('PrestaShopCollection')) {
            $collection = new PrestaShopCollection('CsvTemplate');
        } else {
            $collection = new Collection('CsvTemplate');
        }

        foreach ($collection as $template) {
            if (fnmatch($template->filepath, $obj->filename)) {
                return $template;
            }
        }

        return false;
    }

    public function renderForm()
    {
        if (Tools::getIsset('preview')) {
            $this->preview();
            exit();
        } elseif (Tools::getIsset('createCsvTemplate')) {
            $this->createCsvTemplate();
            exit();
        } elseif (Tools::getIsset('download')) {
            $flow = new Flow((int) Tools::getValue('id_advancedimporter_flow'));
            $filepath = _PS_MODULE_DIR_.'advancedimporter/flows/import/'.$flow->path;

            $size = filesize($filepath);
            header('Content-Type: application/force-download; name="'.$flow->filename.'"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Length: '.$size);
            header('Content-Disposition: attachment; filename="'.$flow->filename.'"');
            header('Expires: 0');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');
            readfile($filepath);
            exit();
        } elseif (Tools::getIsset('downloadreport')) {
            $flow = new Flow((int) Tools::getValue('id_advancedimporter_flow'));

            header('Content-Type: application/force-download; name="report-'.basename($flow->filename, '.xml').'.csv"');
            header('Content-Transfer-Encoding: binary');
            header('Content-Disposition: attachment; filename="report-'.basename($flow->filename, '.xml').'.csv"');
            header('Expires: 0');
            header('Cache-Control: no-cache, must-revalidate');
            header('Pragma: no-cache');

            // Get all blocks with error
            if (class_exists('PrestaShopCollection')) {
                $collection = new PrestaShopCollection('Block');
            } else {
                $collection = new Collection('Block');
            }
            $collection->sqlWhere('error IS NOT NULL');
            $collection->where('error', '<>', '');
            $collection->where('id_advancedimporter_flow', '=', $flow->id);

            $out = fopen('php://output', 'w');
            fputcsv(
                $out,
                array(
                    $this->l('ID'),
                    $this->l('Error'),
                    $this->l('Block'),
                )
            );
            foreach ($collection as $block) {
                fputcsv(
                    $out,
                    array(
                        $block->id,
                        $block->error,
                        $block->block,
                    )
                );
            }

            exit();
        }
    }

    public function addWaitingFlows()
    {
        foreach (array('xml', 'XML', 'csv', 'CSV') as $ext) {
            $array = glob(_PS_MODULE_DIR_.'advancedimporter/flows/import/queue/*.'.$ext);
            if ($array !== false) {
                foreach ($array as $file) {
                    Flow::getByPath($file);
                }
            }
        }
    }
}
