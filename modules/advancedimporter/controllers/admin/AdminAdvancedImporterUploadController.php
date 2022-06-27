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

require_once _PS_MODULE_DIR_.'advancedimporter/classes/block.php';

class AdminAdvancedImporterUploadController extends ModuleAdminController
{
    protected $colorOnBackground = true;
    protected $color_on_background = true; /* Ne sert que si un jour PS réutilise la norme */

    public $tpl_view_vars = array();

    public function __construct()
    {
        if (Tools::getValue('submitAddadvancedimporter_upload')) {
            $this->processForm();
        }

        $this->bootstrap = true;
        parent::__construct();
    }

    public function initContent()
    {
        $this->display = 'view';

        return parent::initContent();
    }

    public function initToolbarTitle()
    {
        $this->toolbar_title = array_unique($this->breadcrumbs);
    }

    public function renderView()
    {
        $this->tpl_view_vars['moduleDir'] = _PS_MODULE_DIR_;
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->tpl_view_vars['display_button'] = true;
        } else {
            $this->tpl_view_vars['display_button'] = false;
        }

        return parent::renderView();
    }
    public function initToolbar()
    {
        $this->toolbar_btn['save'] = array(
            'href' => '#',
            'desc' => $this->l('Upload'),
        );

        return false;
    }
    public function processForm()
    {
        if (!$_FILES['AI_FILE_TO_UPLOAD']['size']) {
            return;
        }

        $file_to_move = $_FILES['AI_FILE_TO_UPLOAD']['tmp_name'];
        $file_name = $_FILES['AI_FILE_TO_UPLOAD']['name'];

        /*
        if (Tools::getValue('AI_CONVERT_CSV') === '1' && $_FILES['AI_FILE_TO_UPLOAD']['type'] === 'text/csv') {
            if (!$this->transformCsv()) {
                return;
            }

            $file_name = str_replace('.csv', '.xml', $file_name);
        }
         */

        try {
            if (!move_uploaded_file($file_to_move, _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'.$file_name)) {
                $this->errors[] = sprintf(
                    $this->l('Not possible to upload file. Please check the right on the directory "%s"'),
                    _PS_MODULE_DIR_.'advancedimporter/flows/import/queue/'
                );

                return;
            }
        } catch (Exception $e) {
            return;
        }
        $this->confirmations[] = $this->l('Flow imported correctly');
    }

    public function transformCsv()
    {
        $csv_file = $_FILES['AI_FILE_TO_UPLOAD']['tmp_name'];
        if (!($handle = fopen($csv_file, 'r')) !== false) {
            $this->errors[] = sprintf($this->l('Cannot open file "%s"'), $csv_file);

            return false;
        }

        $row_name = fgetcsv($handle, 1000, ',');

        $xml = new SimpleXMLElement('<'.$row_name[0].'/>');

        while (($data = fgetcsv($handle, 1000, ',')) !== false) {
            foreach ($data as $key => $row) {
                if ($key === 0) {
                    $node = $xml->addChild($row);
                    continue;
                }
                preg_match('/([\w\d]+)(?:\[(\w+)\])?/', $row_name[$key], $match);

                $subnode = $node->addChild($match[1], $row);
                if (isset($match[2])) {
                    $subnode->addAttribute('lang', $match[2]);
                }
            }
        }

        $xml->asXml($csv_file);

        return true;
    }
}
