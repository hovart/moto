<?php

if (!defined('_PS_VERSION_'))
	exit;

class TptnProdCarousel extends Module
{
	private $_html = '';
	private $pattern = '/^([A-Z_]*)[0-9]+/';
	private $spacer_size = '5';
	private $_postErrors = array();

	public function __construct()
	{
		$this->name = 'tptnprodcarousel';
		$this->tab = 'Blocks';
		$this->version = '1.0';
		$this->author = 'Templatin';
		$this->need_instance = 0;

		$this->bootstrap = true;
		parent::__construct();

		$this->displayName = $this->l('Category Products Carousel');
		$this->description = $this->l('Displays carousel for specific category products on homepage.');
	}

	function install()
	{
		$this->clearCache();
		if ( (parent::install() == false)
			|| (Configuration::updateGlobalValue('TPTNCRSL_SELECTED', '3,4') == false)
			|| ($this->registerHook('displayHome') == false)
			|| ($this->registerHook('addproduct') == false)
			|| ($this->registerHook('updateproduct') == false)
			|| ($this->registerHook('deleteproduct') == false) )
				return false;
		return true;
	}

	public function uninstall()
	{
		Configuration::deleteByName('TPTNCRSL_SELECTED');
		return parent::uninstall();
	}

	public function getContent()
	{
		if (Tools::isSubmit('submitModule')) {
			$this->clearCache();

			$items = Tools::getValue('items');
			if (!(is_array($items) && count($items) && Configuration::updateValue('TPTNCRSL_SELECTED', (string)implode(',', $items))))
				$errors[] =$this->l('Unable to update settings.');
			
			if (isset($errors) AND sizeof($errors))
				$this->_html .= $this->displayError(implode('<br />', $errors));
			else
				$this->_html .= $this->displayConfirmation($this->l('Settings updated'));
		}

		$this->_html .= $this->renderForm();

		return $this->_html;
	}

	public function clearCache()
	{
		$this->_clearCache('tptnprodcarousel.tpl', $this->getCacheId());
	}

	public function renderChoicesSelect()
	{
		$spacer = str_repeat('&nbsp;', $this->spacer_size);
		$items = $this->getMenuItems();
		
		$html = '<select multiple="multiple" id="availableItems" style="width: 300px; height: 160px;">';

		$shop = new Shop((int)Shop::getContextShopID());
		$html .= '<optgroup label="'.$this->l('Categories').'">';	
		$html .= $this->generateCategoriesOption(
			Category::getNestedCategories(null, (int)$this->context->language->id, true), $items);
		$html .= '</optgroup>';		
	
		$html .= '</select>';
		return $html;
	}

	private function getMenuItems()
	{	
		$conf = Configuration::get('TPTNCRSL_SELECTED');
		if (strlen($conf))
			return explode(',', Configuration::get('TPTNCRSL_SELECTED'));
		else
			return array();		
	}
	
	private function makeMenuOption()
	{
		$menu_item = $this->getMenuItems();

		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();
		$html = '<select multiple="multiple" name="items[]" id="items" style="width: 300px; height: 160px;">';
		foreach ($menu_item as $item)
		{
			if (!$item)
				continue;

			preg_match($this->pattern, $item, $values);
			$id = (int)substr($item, strlen($values[1]), strlen($item));
	
			$category = new Category((int)$id, (int)$id_lang);
			if (Validate::isLoadedObject($category))
				$html .= '<option selected="selected" value="'.$id.'">'.$category->name.'</option>'.PHP_EOL;		
		}
		return $html.'</select>';
	}

	private function generateCategoriesOption($categories, $items_to_skip = null)
	{
		$html = '';

		foreach ($categories as $key => $category)
		{
			if (isset($items_to_skip) && !in_array('CAT'.(int)$category['id_category'], $items_to_skip))
			{
				$shop = (object) Shop::getShop((int)$category['id_shop']);
				$html .= '<option value="'.(int)$category['id_category'].'">'
					.str_repeat('&nbsp;', $this->spacer_size * (int)$category['level_depth']).$category['name'].' ('.$shop->name.')</option>';
			}

			if (isset($category['children']) && !empty($category['children']))
				$html .= $this->generateCategoriesOption($category['children'], $items_to_skip);
		}

		return $html;
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
						'type' => 'link_choice',
						'label' => '',
						'name' => 'link',
						'lang' => true,
					),
				),
				'submit' => array(
					'name' => 'submitModule',
					'title' => $this->l('Save')
				)
			),
		);
		
		$helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this;
		$helper->identifier = $this->identifier;		
		$helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars = array(
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'choices' => $this->renderChoicesSelect(),
			'selected_links' => $this->makeMenuOption(),
		);
		return $helper->generateForm(array($fields_form));
	}

	public function hookDisplayHome($params)
	{
		$cid = (Configuration::get('TPTNCRSL_SELECTED'));
		$menu_item = explode(',', $cid);
		$id_lang = (int)$this->context->language->id;
		$id_shop = (int)Shop::getContextShopID();

		$categories = array();

		foreach ($menu_item as $item) {
			if (!$item)
				continue;
			$id = $item;

			$category = new Category((int)$id, $id_lang);
			if (Validate::isLoadedObject($category)) {
				$categories[$item]['id'] = $item;
				$categories[$item]['name'] = $category->name;
				$categories[$item]['products'] = $category->getProducts($id_lang, 1, 100);
			}
		}

		$this->smarty->assign(array(
			'categories' => $categories,
			'homeSize' => Image::getSize(ImageType::getFormatedName('home'))
		));

		return $this->display(__FILE__, 'tptnprodcarousel.tpl', $this->getCacheId());
	}

	public function hookAddProduct($params)
	{
		$this->clearCache();
	}

	public function hookUpdateProduct($params)
	{
		$this->clearCache();
	}

	public function hookDeleteProduct($params)
	{
		$this->clearCache();
	}

}
