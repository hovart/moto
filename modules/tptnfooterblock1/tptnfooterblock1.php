<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnFooterBlock1 extends Module
{
	public function __construct()
	{
		$this->name = 'tptnfooterblock1';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();	

		$this->displayName = $this->l('Footer block 1');
		$this->description = $this->l('Adds a custom block in footer.');
	}

	public function install()
	{
		return 
			parent::install() && 
			$this->registerHook('displayFooter') &&
			$this->installFixtures();
	}
	
	protected function installFixtures()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $lang)
			$this->installFixture((int)$lang['id_lang']);

		return true;
	}
	
	protected function installFixture($id_lang)
	{
		$values['tfb1title'][(int)$id_lang] = 'Custom block';
		$values['tfb1link1'][(int)$id_lang] = 'Dummy text';
		$values['tfb1url1'][(int)$id_lang] = 'http://www.templatin.com';
		Configuration::updateValue('TFB1TITLE', $values['tfb1title']);
		Configuration::updateValue('TFB1LINK1', $values['tfb1link1']);
		Configuration::updateValue('TFB1URL1', $values['tfb1url1']);
	}

	public function uninstall()
	{
		Configuration::deleteByName('TFB1TITLE');
		Configuration::deleteByName('TFB1LINK1');
		Configuration::deleteByName('TFB1URL1');
		Configuration::deleteByName('TFB1LINK2');
		Configuration::deleteByName('TFB1URL2');
		Configuration::deleteByName('TFB1LINK3');
		Configuration::deleteByName('TFB1URL3');
		Configuration::deleteByName('TFB1LINK4');
		Configuration::deleteByName('TFB1URL4');
		Configuration::deleteByName('TFB1LINK5');
		Configuration::deleteByName('TFB1URL5');
		Configuration::deleteByName('TFB1LINK6');
		Configuration::deleteByName('TFB1URL6');
		return parent::uninstall();
	}

	public function hookDisplayFooter($params)
	{
		if (!$this->isCached('tptnfooterblock1.tpl', $this->getCacheId()))
		{
			$this->smarty->assign(array(
				'tfb1title' => Configuration::get('TFB1TITLE', $this->context->language->id),
				'tfb1link1' => Configuration::get('TFB1LINK1', $this->context->language->id),
				'tfb1url1' => Configuration::get('TFB1URL1', $this->context->language->id),
				'tfb1link2' => Configuration::get('TFB1LINK2', $this->context->language->id),
				'tfb1url2' => Configuration::get('TFB1URL2', $this->context->language->id),
				'tfb1link3' => Configuration::get('TFB1LINK3', $this->context->language->id),
				'tfb1url3' => Configuration::get('TFB1URL3', $this->context->language->id),
				'tfb1link4' => Configuration::get('TFB1LINK4', $this->context->language->id),
				'tfb1url4' => Configuration::get('TFB1URL4', $this->context->language->id),
				'tfb1link5' => Configuration::get('TFB1LINK5', $this->context->language->id),
				'tfb1url5' => Configuration::get('TFB1URL5', $this->context->language->id),
				'tfb1link6' => Configuration::get('TFB1LINK6', $this->context->language->id),
				'tfb1url6' => Configuration::get('TFB1URL6', $this->context->language->id)
			));
		}

		return $this->display(__FILE__, 'tptnfooterblock1.tpl', $this->getCacheId());
	}

	public function postProcess()
	{
		if (Tools::isSubmit('submitModule'))
		{
			$languages = Language::getLanguages(false);
			$values = array();
			
			foreach ($languages as $lang)
			{
				$values['tfb1title'][$lang['id_lang']] = Tools::getValue('tfb1title_'.$lang['id_lang']);
				$values['tfb1link1'][$lang['id_lang']] = Tools::getValue('tfb1link1_'.$lang['id_lang']);
				$values['tfb1url1'][$lang['id_lang']] = Tools::getValue('tfb1url1_'.$lang['id_lang']);
				$values['tfb1link2'][$lang['id_lang']] = Tools::getValue('tfb1link2_'.$lang['id_lang']);
				$values['tfb1url2'][$lang['id_lang']] = Tools::getValue('tfb1url2_'.$lang['id_lang']);
				$values['tfb1link3'][$lang['id_lang']] = Tools::getValue('tfb1link3_'.$lang['id_lang']);
				$values['tfb1url3'][$lang['id_lang']] = Tools::getValue('tfb1url3_'.$lang['id_lang']);
				$values['tfb1link4'][$lang['id_lang']] = Tools::getValue('tfb1link4_'.$lang['id_lang']);
				$values['tfb1url4'][$lang['id_lang']] = Tools::getValue('tfb1url4_'.$lang['id_lang']);
				$values['tfb1link5'][$lang['id_lang']] = Tools::getValue('tfb1link5_'.$lang['id_lang']);
				$values['tfb1url5'][$lang['id_lang']] = Tools::getValue('tfb1url5_'.$lang['id_lang']);
				$values['tfb1link6'][$lang['id_lang']] = Tools::getValue('tfb1link6_'.$lang['id_lang']);
				$values['tfb1url6'][$lang['id_lang']] = Tools::getValue('tfb1url6_'.$lang['id_lang']);
			}

			Configuration::updateValue('TFB1TITLE', $values['tfb1title']);
			Configuration::updateValue('TFB1LINK1', $values['tfb1link1']);
			Configuration::updateValue('TFB1URL1', $values['tfb1url1']);
			Configuration::updateValue('TFB1LINK2', $values['tfb1link2']);
			Configuration::updateValue('TFB1URL2', $values['tfb1url2']);
			Configuration::updateValue('TFB1LINK3', $values['tfb1link3']);
			Configuration::updateValue('TFB1URL3', $values['tfb1url3']);
			Configuration::updateValue('TFB1LINK4', $values['tfb1link4']);
			Configuration::updateValue('TFB1URL4', $values['tfb1url4']);
			Configuration::updateValue('TFB1LINK5', $values['tfb1link5']);
			Configuration::updateValue('TFB1URL5', $values['tfb1url5']);
			Configuration::updateValue('TFB1LINK6', $values['tfb1link6']);
			Configuration::updateValue('TFB1URL6', $values['tfb1url6']);

			$this->_clearCache('tptnfooterblock1.tpl');
			return $this->displayConfirmation($this->l('The settings have been updated.'));
		}
		return '';
	}

	public function getContent()
	{
		return $this->postProcess().$this->renderForm();
	}
	
	public function renderForm()
	{
		$fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Settings'),
					'icon' => 'icon-cogs'
				),
				'input' => array(
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Block Title'),
						'name' => 'tfb1title'
					),			
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Link 1'),
						'name' => 'tfb1link1',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('URL 1'),
						'name' => 'tfb1url1',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Link 2'),
						'name' => 'tfb1link2',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('URL 2'),
						'name' => 'tfb1url2',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Link 3'),
						'name' => 'tfb1link3',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('URL 3'),
						'name' => 'tfb1url3',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Link 4'),
						'name' => 'tfb1link4',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('URL 4'),
						'name' => 'tfb1url4',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Link 5'),
						'name' => 'tfb1link5',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('URL 5'),
						'name' => 'tfb1url5',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('Link 6'),
						'name' => 'tfb1link6',
					),
					array(
						'type' => 'text',
						'lang' => true,
						'label' => $this->l('URL 6'),
						'name' => 'tfb1url6',
					)
				),
				'submit' => array(
					'title' => $this->l('Save')
				)
			),
		);

		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->module = $this;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'uri' => $this->getPathUri(),
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}
	
	public function getConfigFieldsValues()
	{
		$languages = Language::getLanguages(false);
		$fields = array();

		foreach ($languages as $lang)
		{
			$fields['tfb1title'][$lang['id_lang']] = Tools::getValue('tfb1title_'.$lang['id_lang'], Configuration::get('TFB1TITLE', $lang['id_lang']));
			$fields['tfb1link1'][$lang['id_lang']] = Tools::getValue('tfb1link1_'.$lang['id_lang'], Configuration::get('TFB1LINK1', $lang['id_lang']));
			$fields['tfb1url1'][$lang['id_lang']] = Tools::getValue('tfb1url1_'.$lang['id_lang'], Configuration::get('TFB1URL1', $lang['id_lang']));
			$fields['tfb1link2'][$lang['id_lang']] = Tools::getValue('tfb1link2_'.$lang['id_lang'], Configuration::get('TFB1LINK2', $lang['id_lang']));
			$fields['tfb1url2'][$lang['id_lang']] = Tools::getValue('tfb1url2_'.$lang['id_lang'], Configuration::get('TFB1URL2', $lang['id_lang']));
			$fields['tfb1link3'][$lang['id_lang']] = Tools::getValue('tfb1link3_'.$lang['id_lang'], Configuration::get('TFB1LINK3', $lang['id_lang']));
			$fields['tfb1url3'][$lang['id_lang']] = Tools::getValue('tfb1url3_'.$lang['id_lang'], Configuration::get('TFB1URL3', $lang['id_lang']));
			$fields['tfb1link4'][$lang['id_lang']] = Tools::getValue('tfb1link4_'.$lang['id_lang'], Configuration::get('TFB1LINK4', $lang['id_lang']));
			$fields['tfb1url4'][$lang['id_lang']] = Tools::getValue('tfb1url4_'.$lang['id_lang'], Configuration::get('TFB1URL4', $lang['id_lang']));
			$fields['tfb1link5'][$lang['id_lang']] = Tools::getValue('tfb1link5_'.$lang['id_lang'], Configuration::get('TFB1LINK5', $lang['id_lang']));
			$fields['tfb1url5'][$lang['id_lang']] = Tools::getValue('tfb1url5_'.$lang['id_lang'], Configuration::get('TFB1URL5', $lang['id_lang']));
			$fields['tfb1link6'][$lang['id_lang']] = Tools::getValue('tfb1link6_'.$lang['id_lang'], Configuration::get('TFB1LINK6', $lang['id_lang']));
			$fields['tfb1url6'][$lang['id_lang']] = Tools::getValue('tfb1url6_'.$lang['id_lang'], Configuration::get('TFB1URL6', $lang['id_lang']));
		}

		return $fields;
	}
}
