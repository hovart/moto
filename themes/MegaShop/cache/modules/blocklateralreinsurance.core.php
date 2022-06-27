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

if (!defined('_CAN_LOAD_FILES_'))
	exit;

include_once _PS_MODULE_DIR_.'blocklateralreinsurance/lateralreinsuranceClass.php';

class BlocklateralreinsuranceModule extends Module
{
	public function __construct()
	{
		$this->name = 'blocklateralreinsurance';
		if (version_compare(_PS_VERSION_, '1.4.0.0') >= 0)
			$this->tab = 'front_office_features';
		else
			$this->tab = 'Blocks';
		$this->version = '2.0';

		parent::__construct();

		$this->displayName = $this->l('lateralreassurance block');
		$this->description = $this->l('Adds an information block aimed at offering helpful information to reassure customers that your store is trustworthy.');
	}

	public function install()
	{
		return parent::install() &&
			$this->installDB() &&
			Configuration::updateValue('blocklateralreinsurance_nbblocks', 5) &&
			$this->registerHook('rightcolumn') && $this->installFixtures();
			$this->registerHook('leftcolumn') && $this->installFixtures();
			$this->registerHook('footer') && $this->installFixtures();
			$this->registerHook('home') && $this->installFixtures();
			$this->registerHook('top') && $this->installFixtures();
	}
	
	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lateralreinsurance` (
				`id_lateralreinsurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL ,
				`file_name` VARCHAR(100) NOT NULL,
				PRIMARY KEY (`id_lateralreinsurance`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'lateralreinsurance_lang` (
				`id_lateralreinsurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_lang` int(10) unsigned NOT NULL ,
				`text` VARCHAR(300) NOT NULL,
				`url` varchar(255) NOT NULL,
				PRIMARY KEY (`id_lateralreinsurance`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
		// Delete configuration
		return Configuration::deleteByName('blocklateralreinsurance_nbblocks') &&
			$this->uninstallDB() &&
			parent::uninstall();
	}

	public function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lateralreinsurance`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'lateralreinsurance_lang`');
	}

	public function addToDB()
	{
		if (isset($_POST['nbblocks']))
		{
			for ($i = 1; $i <= (int)$_POST['nbblocks']; $i++)
			{
				$filename = explode('.', $_FILES['info'.$i.'_file']['name']);
				if (isset($_FILES['info'.$i.'_file']) && isset($_FILES['info'.$i.'_file']['tmp_name']) && !empty($_FILES['info'.$i.'_file']['tmp_name']))
				{
					if ($error = ImageManager::validateUpload($_FILES['info'.$i.'_file']))
						return false;
					elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['info'.$i.'_file']['tmp_name'], $tmpName))
						return false;
					elseif (!ImageManager::resize($tmpName, '/home/ddauteuil/www/mgd/modules/blocklateralreinsurance'.'/img/'.$filename[0].'.jpg'))
						return false;
					unlink($tmpName);
				}
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'lateralreinsurance` (`filename`,`text`)
											VALUES ("'.((isset($filename[0]) && $filename[0] != '') ? pSQL($filename[0]) : '').
					'", "'.((isset($_POST['info'.$i.'_text']) && $_POST['info'.$i.'_text'] != '') ? pSQL($_POST['info'.$i.'_text']) : '').'")');
			}
			return true;
		} else
			return false;
	}

	public function removeFromDB()
	{
		$dir = opendir('/home/ddauteuil/www/mgd/modules/blocklateralreinsurance'.'/img');
		while (false !== ($file = readdir($dir)))
		{
			$path = '/home/ddauteuil/www/mgd/modules/blocklateralreinsurance'.'/img/'.$file;
			if ($file != '..' && $file != '.' && !is_dir($file))
				unlink($path);
		}
		closedir($dir);

		return Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'lateralreinsurance`');
	}

	public function getContent()
	{
		$html = '';
		$id_lateralreinsurance = (int)Tools::getValue('id_lateralreinsurance');

		if (Tools::isSubmit('saveblocklateralreinsurance'))
		{
			if ($id_lateralreinsurance = Tools::getValue('id_lateralreinsurance'))
				$lateralreinsurance = new lateralreinsuranceClass((int)$id_lateralreinsurance);
			else
				$lateralreinsurance = new lateralreinsuranceClass();
			$lateralreinsurance->copyFromPost();
			$lateralreinsurance->id_shop = $this->context->shop->id;
			
			if ($lateralreinsurance->validateFields(false) && $lateralreinsurance->validateFieldsLang(false))
			{
				$lateralreinsurance->save();
				if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name']))
				{
					if ($error = ImageManager::validateUpload($_FILES['image']))
						return false;
					elseif (!($tmpName = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['image']['tmp_name'], $tmpName))
						return false;
					elseif (!ImageManager::resize($tmpName, '/home/ddauteuil/www/mgd/modules/blocklateralreinsurance'.'/img/lateralreinsurance-'.(int)$lateralreinsurance->id.'-'.(int)$lateralreinsurance->id_shop.'.jpg'))
						return false;
					unlink($tmpName);
					$lateralreinsurance->file_name = 'lateralreinsurance-'.(int)$lateralreinsurance->id.'-'.(int)$lateralreinsurance->id_shop.'.jpg';
					$lateralreinsurance->save();
				}
				$this->_clearCache('blocklateralreinsurance.tpl');
			}
			else
				$html .= '<div class="conf error">'.$this->l('An error occurred while attempting to save.').'</div>';
		}
		
		if (Tools::isSubmit('updateblocklateralreinsurance') || Tools::isSubmit('addblocklateralreinsurance'))
		{
			$helper = $this->initForm();
			foreach (Language::getLanguages(false) as $lang)
				if ($id_lateralreinsurance)
				{
					$lateralreinsurance = new lateralreinsuranceClass((int)$id_lateralreinsurance);
					$helper->fields_value['text'][(int)$lang['id_lang']] = $lateralreinsurance->text[(int)$lang['id_lang']];
				}	
				else
					$helper->fields_value['text'][(int)$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang'], '');
			if ($id_lateralreinsurance = Tools::getValue('id_lateralreinsurance'))
			{
				$this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_lateralreinsurance');
				$helper->fields_value['id_lateralreinsurance'] = (int)$id_lateralreinsurance;
 			}
				
			return $html.$helper->generateForm($this->fields_form);
		}
		else if (Tools::isSubmit('deleteblocklateralreinsurance'))
		{
			$lateralreinsurance = new lateralreinsuranceClass((int)$id_lateralreinsurance);
			if (file_exists('/home/ddauteuil/www/mgd/modules/blocklateralreinsurance'.'/img/'.$lateralreinsurance->file_name))
				unlink('/home/ddauteuil/www/mgd/modules/blocklateralreinsurance'.'/img/'.$lateralreinsurance->file_name);
			$lateralreinsurance->delete();
			$this->_clearCache('blocklateralreinsurance.tpl');
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $html.$helper->generateList($this->getListContent((int)Configuration::get('PS_LANG_DEFAULT')), $this->fields_list);
		}

		if (isset($_POST['submitModule']))
		{
			Configuration::updateValue('blocklateralreinsurance_nbblocks', ((isset($_POST['nbblocks']) && $_POST['nbblocks'] != '') ? (int)$_POST['nbblocks'] : ''));
			if ($this->removeFromDB() && $this->addToDB())
			{
				$this->_clearCache('blocklateralreinsurance.tpl');
				$output = '<div class="conf confirm">'.$this->l('The block configuration has been updated.').'</div>';
			}
			else
				$output = '<div class="conf error"><img src="../img/admin/disabled.gif"/>'.$this->l('An error occurred while attempting to save.').'</div>';
		}
	}

	protected function getListContent($id_lang)
	{
		return  Db::getInstance()->executeS('
			SELECT r.`id_lateralreinsurance`, r.`id_shop`, r.`file_name`, rl.`text` 
			FROM `'._DB_PREFIX_.'lateralreinsurance` r
			LEFT JOIN `'._DB_PREFIX_.'lateralreinsurance_lang` rl ON (r.`id_lateralreinsurance` = rl.`id_lateralreinsurance`)
			WHERE `id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang());
	}

	protected function initForm()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('New lateralreassurance block.'),
			),
			'input' => array(
				array(
					'type' => 'file',
					'label' => $this->l('Image:'),
					'name' => 'image',
					'value' => true
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Text:'),
					'lang' => true,
					'name' => 'text',
					'cols' => 40,
					'rows' => 10
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button'
			)
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'blocklateralreinsurance';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		foreach (Language::getLanguages(false) as $lang)
			$helper->languages[] = array(
				'id_lang' => $lang['id_lang'],
				'iso_code' => $lang['iso_code'],
				'name' => $lang['name'],
				'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
			);

		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = $default_lang;
		$helper->allow_employee_form_lang = $default_lang;
		$helper->toolbar_scroll = true;
		$helper->title = $this->displayName;
		$helper->submit_action = 'saveblocklateralreinsurance';
		$helper->toolbar_btn =  array(
			'save' =>
			array(
				'desc' => $this->l('Save'),
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&save'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			),
			'back' =>
			array(
				'href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
				'desc' => $this->l('Back to list')
			)
		);
		return $helper;
	}

	protected function initList()
	{
		$this->fields_list = array(
			'id_lateralreinsurance' => array(
				'title' => $this->l('Id'),
				'width' => 120,
				'type' => 'text',
			),
			'text' => array(
				'title' => $this->l('Text'),
				'width' => 140,
				'type' => 'text',
				'filter_key' => 'a!lastname'
			),
		);

		if (Shop::isFeatureActive())
			$this->fields_list['id_shop'] = array('title' => $this->l('ID Shop'), 'align' => 'center', 'width' => 25, 'type' => 'int');

		$helper = new HelperList();
		$helper->shopLinkType = '';
		$helper->simple_header = true;
		$helper->identifier = 'id_lateralreinsurance';
		$helper->actions = array('edit', 'delete');
		$helper->show_toolbar = true;
		$helper->imageType = 'jpg';
		$helper->toolbar_btn['new'] =  array(
			'href' => AdminController::$currentIndex.'&configure='.$this->name.'&add'.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
			'desc' => $this->l('Add new')
		);

		$helper->title = $this->displayName;
		$helper->table = $this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		return $helper;
	}

	public function hookRightColumn($params)
	{
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS($this->_path.'style.css', 'all');
		if (!$this->isCached('blocklateralreinsurance.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance.tpl', $this->getCacheId());
	}
	public function hookLeftColumn($params)
	{
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS($this->_path.'style.css', 'all');
		if (!$this->isCached('blocklateralreinsurance.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance.tpl', $this->getCacheId());
	}
	public function hookFooter($params)
	{
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS($this->_path.'style3.css', 'all');
		if (!$this->isCached('blocklateralreinsurance3.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance3.tpl', $this->getCacheId());
	}
	public function hookHome($params)
	{
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS($this->_path.'style2.css', 'all');
		if (!$this->isCached('blocklateralreinsurance2.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance2.tpl', $this->getCacheId());
	}
	public function hookTop($params)
	{
		// Check if not a mobile theme
		if ($this->context->getMobileDevice() != false)
			return false;

		$this->context->controller->addCSS($this->_path.'style4.css', 'all');
		if (!$this->isCached('blocklateralreinsurance4.tpl', $this->getCacheId()))
		{
			$infos = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign(array('infos' => $infos, 'nbblocks' => count($infos)));
		}
		return $this->display(__FILE__, 'blocklateralreinsurance4.tpl', $this->getCacheId());
	}


	public function installFixtures()
	{
		$return = true;
		$tab_texts = array(
			array('text' => $this->l('Money back guarantee.'), 'file_name' => 'reinsurance-1-1.jpg'),
			array('text' => $this->l('In-store exchange.'), 'file_name' => 'reinsurance-2-1.jpg'),
			array('text' => $this->l('Payment upon shipment.'), 'file_name' => 'reinsurance-3-1.jpg'),
			array('text' => $this->l('Free Shipping.'), 'file_name' => 'reinsurance-4-1.jpg'),
			array('text' => $this->l('100% secure payment processing.'), 'file_name' => 'reinsurance-5-1.jpg')
		);
		
		foreach($tab_texts as $tab)
		{
			$lateralreinsurance = new lateralreinsuranceClass();
			foreach (Language::getLanguages(false) as $lang)
				$lateralreinsurance->text[$lang['id_lang']] = $tab['text'];
			$lateralreinsurance->file_name = $tab['file_name'];
			$lateralreinsurance->id_shop = $this->context->shop->id;
			$return &= $lateralreinsurance->save();
		}
		return $return;
	}
}
