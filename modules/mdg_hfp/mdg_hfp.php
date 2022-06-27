<?php
/**
* 2012 Michel Dumont | Graphart créations 
*
*  @author    Michel Dumont <miche-dumont.fr>
*  @copyright 2012 - 2015
*  @version   2.6.4 - 2015-07-24
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @prestashop version 1.5.x
*/

if (!defined('_CAN_LOAD_FILES_')) exit;

require_once dirname(__file__).'/classes/MdgHfpBlock.php';	
class mdg_hfp extends Module
{
	const SQL_1 = 'mdg_hfp';
	const SQL_2 = 'mdg_hfp_lang';

	public function __construct()
	{
		$this->name = 'mdg_hfp';
		$this->tab = 'front_office_features';
		$this->version = '2.6.5';
		$this->author = 'Michel DUMONT';
		$this->need_instance = 0;
		$this->module_key = 'e40a600bdba6b085c59a19f73c50867e';
		
		$this->ps_versions_compliancy = array('min' => '1.6', 'max' => '1.7');
		
		$this->bootstrap = 1;
		parent::__construct();

		$this->displayName = $this->l('(mdg) Blocs featured products');
		$this->description = $this->l('Display a lot of features products blocs on your homepage');

		$this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
	}


/* =============================================================== //
	BO
/* =============================================================== */
	public function getContent()
	{
		self::update();

		$this->context->controller->addjQueryPlugin('hoverIntent');
		$this->context->controller->addJS($this->_path.'views/js/admin-app.js');

		$this->id 			= Tools::getValue('id');
		$this->form_tab 	= Tools::getValue('form_tab', 1);
		$this->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&module_name='.$this->name;
		
		$this->class_name = 'MdgHfpBlock';

		require_once dirname(__file__).'/classes/'.$this->class_name.'.php';

		$this->Class = new $this->class_name($this->id);
	
		
		// SET Medias
		if (method_exists($this->Class, 'setCSS'))
			foreach ($this->Class->setCSS() as $csspath)
				$this->context->controller->addCSS($this->_path.$csspath, 'all');
		if (method_exists($this->Class, 'setJS'))
			foreach ($this->Class->setJS() as $jspath)
				$this->context->controller->addJS($this->_path.$jspath);
		if (method_exists($this->Class, 'setJqueryPlugin'))
			$this->context->controller->addJqueryPlugin($this->Class->setJqueryPlugin());

		$output = '';
		
		$process = method_exists($this->Class, 'process') ? $this->Class->process() : null;
		if ($process['process'])
			if (count($process['errors'])) foreach ($process['errors'] as $error) $output .= $this->displayError($this->l($error));
			else $output .= $this->displayConfirmation($this->l('Treatment completed'));
		
		$output .= $this->renderTabs();

		return $output;
	}

	public function renderTabs()
	{
		$output = '<div class="row">';
		
		$output .= method_exists($this->Class, 'renderForm') ? '<div class="col-lg-12">'.$this->Class->renderForm($this).'</div>' : null;
		$output .= method_exists($this->Class, 'renderList') ? $this->Class->renderList($this) : null;
		
		$output .= '<script type="text/javascript">
						$(function(){
							app._path = "'.$this->_path.'";
							app.form.initialize();
							app.list.initialize();
						});	
					</script>';
		
		$output .= '</div>';
		
		return $output;	
	}



/* =============================================================== //
	HOOKS
/* =============================================================== */

	public function hookdisplayHeader($params)
	{ 
		$this->context->controller->addCSS($this->_path.'views/css/'.$this->name.'.css', 'all');
		if (!Tools::getvalue('id_product'))
			$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/jquery.idTabs.js');
		if (Tools::isSubmit('id_category'))
			$this->context->smarty->assign(array(
				'HOOK_DISPLAYCATEGORYMDG' => Hook::exec('displayCategoryMDG'),
			));
	}

	public function hookdisplayHome($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize('home_default'),
			'blocks' => MdgHfpBlock::getBlocks(),
			'hook' => 'home'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayCategoryMDG()
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize('home_default'),
			'blocks' => MdgHfpBlock::getBlocks('displayCategoryMDG'),
			'hook' => 'categoryProducts'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayFooterProduct($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize('home_default'),
			'blocks' => MdgHfpBlock::getBlocks('displayFooterProduct'),
			'hook' => 'footerProduct'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayShoppingCartFooter($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize('home_default'),
			'blocks' => MdgHfpBlock::getBlocks('displayShoppingCartFooter'),
			'hook' => 'shoppingCartFooter'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayLeftColumn($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize('home_default'),
			'smallSize' => Image::getSize('small_default'),
			'blocks' => MdgHfpBlock::getBlocks('displayLeftColumn'),
			'hook' => 'columns'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}
	public function hookdisplayRightColumn($params)
	{
		$this->context->smarty->assign(array(
			'homeSize' => Image::getSize('home_default'),
			'smallSize' => Image::getSize('small_default'),
			'blocks' => MdgHfpBlock::getBlocks('displayRightColumn'),
			'hook' => 'columns'
		));
		return $this->display(__FILE__, $this->name.'.tpl');
	}






/* =============================================================== //
	TOOLS
/* =============================================================== */
	public function runSql($sql)
	{
		foreach ($sql as $s)
			if (!Db::getInstance()->Execute($s))
				return false;
		return true;
	}

	



/* =============================================================== //
	INSTALL
/* =============================================================== */
	public static function update()
	{
		$tmp = Db::getInstance()->ExecuteS('SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "'._DB_PREFIX_.self::SQL_1.'"');
		$columns = array();
		foreach($tmp as $v)
		{
			$columns[] = $v['COLUMN_NAME'];
		}

		if (!in_array('unsold', $columns))
		{
			Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.self::SQL_1.'` ADD `unsold` int(10) NOT NULL');
			Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.self::SQL_1.'` ADD `sort_by` int(10) NOT NULL');
		}
	}
	public function install()
	{
		if (Shop::isFeatureActive())
			Shop::setContext(Shop::CONTEXT_ALL);

		$sql   = array();
		$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::SQL_1.'` (
					`id_block` 					int(10) unsigned NOT NULL auto_increment,
					`id_shop` 					int(10) unsigned NOT NULL,
					`active` 					tinyint(1) NOT NULL, 
					`is_tab` 					tinyint(1) NOT NULL, 
					`position` 					int(10) NOT NULL, 
					`sort_by` 					int(10) NOT NULL, /* 0: shuffle, 1: position asc, 2: position desc */
					`type` 						int(10) NOT NULL, 
					`restrict_type` 			int(10) NOT NULL, 
					`number` 					int(10) NOT NULL, 
					`unsold` 					int(10) NOT NULL, /* Number of unsold days for a product */
					`hook` 						varchar(255) NOT NULL,
					`categories_ids` 			text NOT NULL,
					`products_ids` 				text NOT NULL,
					`products_names` 			text NOT NULL,
					`restricted_categories_ids` text NOT NULL,
					`restricted_products_ids` 	text NOT NULL,
					`restricted_products_names` text NOT NULL,
					PRIMARY KEY (`id_block`)
				  ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';
		$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.self::SQL_2.'` (
					`id_lang` int(10) unsigned NOT NULL, 
					`id_block` int(10) unsigned NOT NULL,
					`title` varchar(255) NOT NULL,
					PRIMARY KEY (`id_lang`,`id_block`)
				  ) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8';

	
		if (!parent::install()
			|| !$this->registerHook('displayCategoryMDG')
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('displayLeftColumn')
			|| !$this->registerHook('displayRightColumn')
			|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('displayShoppingCartFooter')
			|| !$this->runSql($sql))
				return false;
		return true;
	}

	public function uninstall()
	{
		$sql = array();
		$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.self::SQL_1.'`';
		$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.self::SQL_2.'`';

		if (!parent::uninstall()
			|| !$this->runSql($sql))
				return false;
		return true;
	}
}