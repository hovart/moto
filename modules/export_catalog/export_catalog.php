<?php
/**
 * Catalog in CSV format module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Samdha <contact@samdha.net>
 * @copyright Samdha
 * @license   commercial license see license.txt
 * @link      http://scripts.sil.org/OFL logo license
 * @link      http://fontawesome.io - Font Awesome by Dave Gandy - logo author
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

include(_PS_MODULE_DIR_.'export_catalog/autoloader.php');
spl_autoload_register('exportCatalogAutoload');

class Export_Catalog extends Samdha_ExportCatalog_Main
{
    public function __construct()
    {
        $this->module_key = '2273c3ab9415bd47e68a79cc0efe273e';
        $this->name = 'export_catalog';
        return parent::__construct();
    }
}
