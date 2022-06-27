<?php
/*
* 2007-2013 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class OrderFilter extends Module
{
	public $template_directory = '';
	public $smarty;
	
	public function __construct()
	{
		$this->name = 'orderfilter';
		$this->tab = 'administration';
		$this->version = '1.0';
		$this->author = 'Ittu';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Order Filter');
		$this->description = $this->l('Filter order with products.');
		$this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
	}

	public function install()
	{
		if (!parent::install())
			return false;

		$this->override_file();
		return true;
	}
	
	public function override_file(){
		
		//put js file in PS js folder 
		$jsfileData = file_get_contents(dirname(__FILE__).'/adminorderfilter.js');
		$jsfilepath = _PS_ROOT_DIR_."/js/adminorderfilter.js";
		 
		//put php file ajaxfilemanager folder
		$phpfileData = file_get_contents(dirname(__FILE__).'/ajax_products_list.php');
		$phpfilepath = _PS_ADMIN_DIR_."/ajax_products_list.php";
		if(file_put_contents($jsfilepath,$jsfileData) && file_put_contents($phpfilepath,$phpfileData))
		return true;
		else
		return false;
	}
	
	public function uninstall()
	{
		if (!parent::uninstall())
			return false;
		
		@unlink(_PS_ROOT_DIR_.'/cache/class_index.php');
		@unlink(_PS_ADMIN_DIR_.'/ajax_products_list.php');
		@unlink(_PS_ROOT_DIR_.'/js/adminorderfilter.js');
		@unlink(_PS_ROOT_DIR_.'/override/controllers/admin/AdminOrdersController.php');
		
		parent::uninstall();
		return true;
	}
}

