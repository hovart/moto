<?php

if (!defined('_CAN_LOAD_FILES_'))
	exit;

include_once _PS_MODULE_DIR_.'tptnfooterblock2/tptnfooterblock2Class.php';

class TptnFooterblock2 extends Module
{
	public function __construct()
	{
		$this->name = 'tptnfooterblock2';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

	 	parent::__construct();

		$this->displayName = $this->l('Footer block 2 - Templatin');
		$this->description = $this->l('Adds a block with additional links in footer.');
	}

	public function install()
	{
		return parent::install() &&	$this->installDB() && $this->registerHook('displayFooter');
	}
	
	public function installDB()
	{
		$return = true;
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnfooterblock2` (
				`id_tptnfooterblock2` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_shop` int(10) unsigned NOT NULL ,
				PRIMARY KEY (`id_tptnfooterblock2`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		$return &= Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'tptnfooterblock2_lang` (
				`id_tptnfooterblock2` INT UNSIGNED NOT NULL AUTO_INCREMENT,
				`id_lang` int(10) unsigned NOT NULL ,
				`text` VARCHAR(300) NOT NULL,
				`url` VARCHAR(300) NOT NULL,
				PRIMARY KEY (`id_tptnfooterblock2`, `id_lang`)
			) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
		
		return $return;
	}

	public function uninstall()
	{
		return $this->uninstallDB() && parent::uninstall();
	}

	public function uninstallDB()
	{
		return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tptnfooterblock2`') && Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'tptnfooterblock2_lang`');
	}
	
	public function getContent()
	{
		$html = '';
		$id_tptnfooterblock2 = (int)Tools::getValue('id_tptnfooterblock2');
		
		if (Tools::isSubmit('savetptnfooterblock2'))
		{
			if ($id_tptnfooterblock2 = Tools::getValue('id_tptnfooterblock2'))
				$tptnfooterblock2 = new FooterBlock2Class((int)$id_tptnfooterblock2);
			else
				$tptnfooterblock2 = new FooterBlock2Class();
			$tptnfooterblock2->copyFromPost();
			$tptnfooterblock2->id_shop = $this->context->shop->id;
			
			if ($tptnfooterblock2->validateFields(false) && $tptnfooterblock2->validateFieldsLang(false))
			{
				$tptnfooterblock2->save();
				$this->_clearCache('tptnfooterblock2.tpl');
			}
			else
				$html .= '<div class="conf error">'.$this->l('An error occurred while attempting to save.').'</div>';
		}
		
		if (Tools::isSubmit('updatetptnfooterblock2') || Tools::isSubmit('addtptnfooterblock2'))
		{
			$helper = $this->initForm();
			foreach (Language::getLanguages(false) as $lang)
				if ($id_tptnfooterblock2)
				{
					$tptnfooterblock2 = new FooterBlock2Class((int)$id_tptnfooterblock2);
					$helper->fields_value['text'][(int)$lang['id_lang']] = $tptnfooterblock2->text[(int)$lang['id_lang']];
					$helper->fields_value['url'][(int)$lang['id_lang']] = $tptnfooterblock2->url[(int)$lang['id_lang']];
				}	
				else {
					$helper->fields_value['text'][(int)$lang['id_lang']] = Tools::getValue('text_'.(int)$lang['id_lang'], '');
					$helper->fields_value['url'][(int)$lang['id_lang']] = Tools::getValue('url_'.(int)$lang['id_lang'], '');
				}
			if ($id_tptnfooterblock2 = Tools::getValue('id_tptnfooterblock2'))
			{
				$this->fields_form[0]['form']['input'][] = array('type' => 'hidden', 'name' => 'id_tptnfooterblock2');
				$helper->fields_value['id_tptnfooterblock2'] = (int)$id_tptnfooterblock2;
 			}
				
			return $html.$helper->generateForm($this->fields_form);
		}
		else if (Tools::isSubmit('deletetptnfooterblock2'))
		{
			$tptnfooterblock2 = new FooterBlock2Class((int)$id_tptnfooterblock2);
			$tptnfooterblock2->delete();
			$this->_clearCache('tptnfooterblock2.tpl');
			Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'));
		}
		else
		{
			$helper = $this->initList();
			return $html.$helper->generateList($this->getListContent((int)Configuration::get('PS_LANG_DEFAULT')), $this->fields_list);
		}
	}

	protected function getListContent($id_lang)
	{
		return  Db::getInstance()->executeS('
			SELECT r.`id_tptnfooterblock2`, r.`id_shop`, rl.`text`, rl.`url`
			FROM `'._DB_PREFIX_.'tptnfooterblock2` r
			LEFT JOIN `'._DB_PREFIX_.'tptnfooterblock2_lang` rl ON (r.`id_tptnfooterblock2` = rl.`id_tptnfooterblock2`)
			WHERE `id_lang` = '.(int)$id_lang.' '.Shop::addSqlRestrictionOnLang());
	}
	
	protected function initForm()
	{
		$default_lang = (int)Configuration::get('PS_LANG_DEFAULT');

		$this->fields_form[0]['form'] = array(
			'legend' => array(
				'title' => $this->l('New Link'),
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Text:'),
					'lang' => true,
					'name' => 'text'
				),
				array(
					'type' => 'text',
					'label' => $this->l('URL:'),
					'lang' => true,
					'name' => 'url'
				)
			),
			'submit' => array(
				'title' => $this->l('Save'),
				'class' => 'button'
			)
		);

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'tptnfooterblock2';
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
		$helper->submit_action = 'savetptnfooterblock2';
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
			'id_tptnfooterblock2' => array(
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
		$helper->identifier = 'id_tptnfooterblock2';
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

	public function hookDisplayFooter($params)
	{
		
		if (!$this->isCached('tptnfooterblock2.tpl', $this->getCacheId()))
		{
			$tptn_footerblock2 = $this->getListContent($this->context->language->id);
			$this->context->smarty->assign('tptn_footerblock2', $tptn_footerblock2);
		}
		return $this->display(__FILE__, 'tptnfooterblock2.tpl', $this->getCacheId());
	}
}
