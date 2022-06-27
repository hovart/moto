<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnProdTabs extends Module
{
	public function __construct()
	{
		$this->name = 'tptnprodtabs';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Product Tabs');
		$this->description = $this->l('Displays Featured, New, Specials and Best-seller products on homepage.');
	}

	public function install()
	{
		if ( (parent::install() == false)
			|| (Configuration::updateValue('TOTAL_PROD', 100) == false)
			|| (Configuration::updateValue('FEAT_PROD', 1) == false)
			|| (Configuration::updateValue('NEW_PROD', 1) == false)
			|| (Configuration::updateValue('SPCL_PROD', 1) == false)
			|| (Configuration::updateValue('BEST_PROD', 1) == false)
			|| ($this->registerHook('displayHome') == false) )
				return false;
		return true;
	}
	
	public function uninstall()
	{
		Configuration::deleteByName('TOTAL_PROD');
		Configuration::deleteByName('FEAT_PROD');
		Configuration::deleteByName('NEW_PROD');
		Configuration::deleteByName('SPCL_PROD');
		Configuration::deleteByName('BEST_PROD');
		return parent::uninstall();
	}
	
	public function hookDisplayHome($params)
	{
		$total = (int)(Configuration::get('TOTAL_PROD'));

		$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
		$featProducts = $category->getProducts((int)Context::getContext()->language->id, 1, $total, 'position');

		$newProducts = Product::getNewProducts((int)Context::getContext()->language->id, 0, $total);
		$spclProducts = Product::getPricesDrop((int)Context::getContext()->language->id, 0, $total);
		$bestProducts = ProductSale::getBestSalesLight((int)Context::getContext()->language->id, 0, $total);
		
		$this->smarty->assign(array(
			'featured_products' => $featProducts,
			'show_featured_prod' => (int)(Configuration::get('FEAT_PROD')),
			'new_products' => $newProducts,
			'show_new_prod' => (int)(Configuration::get('NEW_PROD')),
			'special_products' => $spclProducts,
			'show_special_prod' => (int)(Configuration::get('SPCL_PROD')),
			'best_products' => $bestProducts,
			'show_best_prod' => (int)(Configuration::get('BEST_PROD')),
			'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
			'self' => dirname(__FILE__)
		));

		return $this->display(__FILE__, 'tptnprodtabs.tpl', $this->getCacheId());
	}

	public function getContent()
	{
		$html = '';

		if (Tools::isSubmit('submitModule'))
		{
			Configuration::updateValue('TOTAL_PROD', (int)(Tools::getValue('total_prod')));
			Configuration::updateValue('FEAT_PROD', (int)(Tools::getValue('featured_prod')));
			Configuration::updateValue('NEW_PROD', (int)(Tools::getValue('new_prod')));
			Configuration::updateValue('SPCL_PROD', (int)(Tools::getValue('special_prod')));
			Configuration::updateValue('BEST_PROD', (int)(Tools::getValue('best_prod')));

			$html .= $this->displayConfirmation($this->l('Configuration updated'));
			$this->clearCache();
		}
		
		$html .= $this->renderForm();

		return $html;
	}

	public function clearCache()
	{
		$this->_clearCache('tptnprodtabs.tpl', $this->getCacheId());
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
						'label' => $this->l('Total products to display'),
						'name' => 'total_prod',
						'class' => 'fixed-width-sm'
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display Featured Products'),
						'name' => 'featured_prod',
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
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display New Products'),
						'name' => 'new_prod',
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
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display Product Specials'),
						'name' => 'special_prod',
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
					),
					array(
						'type' => 'switch',
						'label' => $this->l('Display Best-seller Products'),
						'name' => 'best_prod',
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
					),			
				),
				'submit' => array(
					'title' => $this->l('Save')				
				),
			)
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table =  $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();

		$helper->identifier = $this->identifier;
		$helper->submit_action = 'submitModule';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'fields_value' => $this->getConfigFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id
		);

		return $helper->generateForm(array($fields_form));
	}

	public function getConfigFieldsValues()
	{
		return array(
			'total_prod'	=> Tools::getValue('total_prod', Configuration::get('TOTAL_PROD')),
			'featured_prod'	=> Tools::getValue('featured_prod', Configuration::get('FEAT_PROD')),
			'new_prod'		=> Tools::getValue('new_prod', Configuration::get('NEW_PROD')),
			'special_prod'	=> Tools::getValue('special_prod', Configuration::get('SPCL_PROD')),
			'best_prod'		=> Tools::getValue('best_prod', Configuration::get('BEST_PROD'))
		);
	}
	
}
