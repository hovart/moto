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

class AdminAdvancedImporterDocumentationController extends ModuleAdminController
{
    public function __construct()
    {
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->bootstrap = true;
        parent::__construct();
    }

    public function renderList()
    {
        $smarty = Context::getContext()->smarty;
        $smarty->assign(array(
            'AI_VERSION' => $this->module->version,
        ));
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/documentation.tpl');
        } else {
            return $smarty->fetch(_PS_MODULE_DIR_.'advancedimporter/views/templates/admin/documentation-1.5.tpl');
        }
    }
}
