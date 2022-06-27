<?php
/**
*  Since 2007 Michel Dumont | Graphart créations 
*
*  @author    Michel Dumont <michel|at|dumont|dot|com>
*  @copyright 2012 - 2015
*  @version   1.0.3 - 2015-07-24
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  @prestashop version 1.5.x
*/

if (!defined('_CAN_LOAD_FILES_')) exit;
class MdgHfpBlock extends ObjectModel
{
	const SQL_1 = mdg_hfp::SQL_1;


	public $id_shop;

	public $active = true;
	public $is_tab;
	public $position;
	public $type;
	public $restrict_type;
	public $sort_by;
	public $unsold; /* Number days for unsold */
	public $number = 4;
	public $hook;
	public $categories_ids;
	public $products_ids;
	public $products_names;
	public $restricted_categories_ids;
	public $restricted_products_ids;
	public $restricted_products_names;

	public $title;

	public $options;
	
	public $errors = array();

	public static $definition = array(
		'table' => self::SQL_1,
		'primary' => 'id_block',
		'multilang' => true,
		'multi_shop' => true,
		'fields' => array(
			'id_shop' 			=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'active' 			=> array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
			'is_tab'			=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'position' 			=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'type' 				=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'restrict_type' 	=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'sort_by' 			=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'unsold' 			=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'number'			=> array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
			'hook' 				=> array('type' => self::TYPE_STRING, 'validate' => 'isString'),
			'categories_ids'	=> array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'products_ids' 		=> array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'products_names'	=> array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'restricted_categories_ids' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'restricted_products_ids' 	=> array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
			'restricted_products_names' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),

			/* Lang fields */
			'title' 			=> array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isString'),
		),
	);




/* =============================================================== //
	FRONT END
/* =============================================================== */
	public static function getBlocks($hook = 'displayHome')
	{
		$id_lang 	= (int)Context::getContext()->language->id;
		$id_shop 	= (int)Context::getContext()->shop->id;
		$Class		= get_class();
		$result 	= array();

		$sql = 'SELECT id_block as id FROM `'._DB_PREFIX_.self::SQL_1.'` WHERE hook="'.($hook).'" AND id_shop='.$id_shop.' AND active=1 ORDER BY position ASC';
		if (!$res = Db::getInstance()->executeS($sql))
			return $result;

		$id_category = Tools::getValue('id_category');
		$id_product  = Tools::getValue('id_product');

		$count_tab = false;

		foreach ($res as $row)
		{
			$_block = new $Class($row['id'], $id_lang);
			
		// Check restriction
			//Seulement sur des catégories définies
			if ($_block->restrict_type == 2 && (!$id_category || !in_array($id_category, explode('-', $_block->restricted_categories_ids))))
				continue;
			// Seulement sur des produits définis
			elseif ($_block->restrict_type == 3 && (!$id_product || !in_array($id_product, explode('-', $_block->restricted_products_ids))))
				continue;
			
			$block = array('id' => $row['id']);
			foreach (self::$definition['fields'] as $k => $v)
				$block[$k] = $_block->$k;
			
			$block['products'] = self::getBlockProducts($block);
			
			if ($block['is_tab'] && !$count_tab)
				Context::getContext()->smarty->assign('count_tab', true);

			$result[] = $block;
		}

		return $result;
	}
	
	public static function getBlockProducts($block)
	{
		$context = Context::getContext();
		$id_lang = (int)$context->language->id;
		$id_shop = (int)$context->shop->id;
		
		$sql = 'SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).'
						DAY)) > 0 AS new, product_shop.price AS orderprice
				FROM `'._DB_PREFIX_.'category_product` cp
				LEFT JOIN `'._DB_PREFIX_.'product` p
					ON p.`id_product` = cp.`id_product`
				'.Shop::addSqlAssociation('product', 'p').'
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa
				ON (p.`id_product` = pa.`id_product`)
				'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1').'
				'.Product::sqlStock('p', 'product_attribute_shop', false, $context->shop).'
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl
					ON (product_shop.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('cl').')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
					ON (p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = '.(int)$id_lang.Shop::addSqlRestrictionOnLang('pl').')
				LEFT JOIN `'._DB_PREFIX_.'image` i
					ON (i.`id_product` = p.`id_product`)'.
				Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1').'
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il
					ON (image_shop.`id_image` = il.`id_image`
					AND il.`id_lang` = '.(int)$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m
					ON m.`id_manufacturer` = p.`id_manufacturer`
				';

		switch ($block['sort_by'])
		{
			case 1:	
				$order_by = 'cp.position ASC';
				break;
			case 2: 
				$order_by = 'cp.position DESC';
				break;
			default: 
				$order_by = 'RAND()';
		}
		switch ($block['type'])
		{
			case 1: //Catégories
				$categories_in = '';
				foreach (explode('-', $block['categories_ids']) as $v)
				{
					$c = new Category($v);
					if ($c->active && $c->checkAccess($context->customer->id))
						$categories_in .= $v.',';
				}
				$categories_in .= trim($categories_in, ',');
				
				$sql .= 'WHERE product_shop.`id_shop` = '.$id_shop
							.' AND cp.id_category IN('.$categories_in.')'
							.' AND product_shop.`active` = 1'
							.' AND product_shop.`visibility` IN ("both", "catalog")
						GROUP BY product_shop.id_product 
						ORDER BY '.$order_by.'
						LIMIT '.$block['number'];
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
				return $result ? Product::getProductsProperties($id_lang, $result) : array();
			
			case 2 : // products
				$sql .= 'WHERE product_shop.`id_shop` = '.$id_shop
							.' AND p.id_product IN('.str_replace('-', ',', $block['products_ids']).')'
							.' AND product_shop.`active` = 1'
							.' AND product_shop.`visibility` IN ("both", "catalog")
						GROUP BY product_shop.id_product 
						ORDER BY '.$order_by.'
						LIMIT '.$block['number'];
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
				return $result ? Product::getProductsProperties($id_lang, $result) : array();
			
			case 3 : // New products
				return Product::getNewProducts($id_lang, 0, (int)$block['number']);
			
			case 4 : // Best sales
				return ProductSale::getBestSales($id_lang, 0, (int)$block['number']);
			
			case 5 : // Prices Drop
				return Product::getPricesDrop($id_lang, 0, (int)$block['number']);
			
			case 6 : // Invendus
				$sql .= 'WHERE product_shop.`id_shop` = '.$id_shop
							.' AND product_shop.`active` = 1'
							.' AND product_shop.`visibility` IN ("both", "catalog")'
							.' AND p.`id_product` NOT IN(SELECT p.`id_product`
									FROM `'._DB_PREFIX_.'orders` o
									LEFT JOIN `'._DB_PREFIX_.'order_detail` od ON o.`id_order` = od.`id_order`
									LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = od.`product_id`
									WHERE o.valid = 1 
										AND TIMESTAMPDIFF(DAY,o.`date_add`,NOW())<='.(int)$block['unsold'].'
										AND p.`active` = 1
									GROUP BY p.`id_product`)
						GROUP BY product_shop.id_product 
						ORDER BY '.$order_by.'
						LIMIT '.$block['number'];
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
				return $result ? Product::getProductsProperties($id_lang, $result) : array();
		}
		
	}


/* =============================================================== //
	BO
/* =============================================================== */
	public function setJqueryPlugin()
	{
		return array('autocomplete');	
	}
	
	public function process()
	{
		$process	= false;
		$id_shop 	= (int)Context::getContext()->shop->id;

		$id 		= Tools::getValue('id');
		$Class 		= get_class();
		
		if (Tools::isSubmit('activemdg_hfp'))
		{
			$self = new self($id);
			$self->active = (int)!$self->active;
			$self->save();
			Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules', false).'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=mdg_hfp&module_name=mdg_hfp');
		}
		
		if (Tools::isSubmit('deletemdg_hfp'))
		{
			$self = new self($id);
			$self->delete();
			Tools::redirectAdmin(Context::getContext()->link->getAdminLink('AdminModules', false).'&token='.Tools::getAdminTokenLite('AdminModules').'&configure=mdg_hfp&module_name=mdg_hfp');
		}
		
		if (Tools::isSubmit('submit_form'))
		{
			$process = true;

			$this->copyFromPost();

			// Requis
			if (empty($this->title[(int)(Configuration::get('PS_LANG_DEFAULT'))]))
			{
				$this->errors[] = 'Default language title must be filled';
				return array('process'=>$process, 'errors'=>$this->errors);
			}
			
			// Defaut
			$this->id = $id ? $id : null;
			$this->id_shop = $id_shop;
			
			// Traitement des catégories
			$categories = Tools::getValue('categories', array());
			$this->categories_ids = implode('-', $categories);
			$rcategories = Tools::getValue('rcategories', array());
			$this->restricted_categories_ids = implode('-', $rcategories);
			
			// Traitement des produits
			$this->products_ids 	= trim(Tools::getValue('inputproducts'), '-');
			$this->products_names 	= trim(Tools::getValue('nameproducts'), '¤');
			$this->restricted_products_ids 		= trim(Tools::getValue('inputrestricted_products'), '-');
			$this->restricted_products_names 	= trim(Tools::getValue('namerestricted_products'), '¤');
			
			// Save
			$this->save();
		}
		
		
		return array('process'=>$process, 'errors'=>$this->errors);
	}

	public function renderForm($m)
	{
		$fields_values = $this->getThisValues($this->id); 
		
		$rootCategory_id = (Shop::getContext() == Shop::CONTEXT_SHOP ? Category::getRootCategory()->id_category : 0);
		
		$id_categories = new HelperTreeCategories('categories_ids');
		$id_categories->setRootCategory($rootCategory_id)->setUseCheckBox(true)->setInputName('categories')->setSelectedCategories(explode('-', $fields_values['categories_ids']));

		$id_rcategories = new HelperTreeCategories('restricted_categories_ids');
		$id_rcategories->setRootCategory($rootCategory_id)->setUseCheckBox(true)->setInputName('rcategories')->setSelectedCategories(explode('-', $fields_values['restricted_categories_ids']));
		
		$fields_form = array(
			array(
				'form' => array(
					'legend' => array(
						'title' => $m->l('General settings', 'mdghfpblock'),
						'icon' 	=> 'icon-cogs',
					),
					'input' => array(
						array(
							'type' 		=> 'text',
							'label' 	=> $m->l('Title block', 'mdghfpblock'),
							'name' 		=> 'title',
							'lang'		=> true,
							'required' 	=> true,
							'col' 		=> 4,
						),
						array(
							'type' 		=> 'switch',
							'label' 	=> $m->l('Enabled', 'mdghfpblock'),
							'name' 		=> 'active',
							'values' 	=> array(
								array('id' => 'active_on', 'value' => 1, 'label' => $m->l('Enabled', 'mdghfpblock')),
								array('id' => 'active_off', 'value' => 0, 'label' => $m->l('Disabled', 'mdghfpblock'))
							),
						),
						array(
							'type' 		=> 'switch',
							'label' 	=> $m->l('Display in tab', 'mdghfpblock'),
							'name' 		=> 'is_tab',
							'hint'		=> $m->l('All blocks from the same hook with this option will form a group of tabs', 'mdghfpblock'),
							'values'	=> array(
								array('id' => 'active_on', 'value' => 1, 'label' => $m->l('Enabled', 'mdghfpblock')),
								array('id' => 'active_off', 'value' => 0, 'label' => $m->l('Disabled', 'mdghfpblock'))
							),
						),
						array(
							'type' 		=> 'select',
							'label' 	=> $m->l('Sort product', 'mdghfpblock'),
							'name' 		=> 'sort_by',
							'options'	=> array(
								'id' 		=> 'id_option',
								'name' 		=> 'name',
								'query'		=> array(
									array('id_option' => 0, 'name' => $m->l('Random', 'mdghfpblock')),
									array('id_option' => 1, 'name' => $m->l('Position asc', 'mdghfpblock')),
									array('id_option' => 2, 'name' => $m->l('Position desc', 'mdghfpblock')),
								)
							),
						),
						array(
							'type' 		=> 'select',
							'label' 	=> $m->l('Display on', 'mdghfpblock'),
							'name' 		=> 'hook',
							'options'	=> array(
								'id' 		=> 'id_option',
								'name' 		=> 'name',
								'query'		=> array(
									array('id_option' => 'displayHome', 'name' => $m->l('displayHome', 'mdghfpblock')),
									array('id_option' => 'displayLeftColumn', 'name' => $m->l('displayLeftColumn', 'mdghfpblock')),
									array('id_option' => 'displayRightColumn', 'name' => $m->l('displayRightColumn', 'mdghfpblock')),
									array('id_option' => 'displayFooterProduct', 'name' => $m->l('displayFooterProduct', 'mdghfpblock')),
									array('id_option' => 'displayCategoryMDG', 'name' => $m->l('displayCategoryMDG (not native)', 'mdghfpblock')),
									array('id_option' => 'displayShoppingCartFooter', 'name' => $m->l('displayShoppingCartFooter', 'mdghfpblock')),
								)
							),
						),
						array(
							'type' 		=> 'text',
							'label' 	=> $m->l('Display position', 'mdghfpblock'),
							'name' 		=> 'position',
							'col' 		=> 1,
							'hint'		=> $m->l('Can manage the display order of blocks.', 'mdghfpblock')
						),
						array(
							'type' 		=> 'text',
							'label' 	=> $m->l('Number of products', 'mdghfpblock'),
							'name' 		=> 'number',
							'col' 		=> 1,
							'hint'		=> $m->l('Maximum number of products to display.', 'mdghfpblock')
						),
					),
					'submit' => array(
						'title' => $this->id ? $m->l('Edit') : $m->l('Save', 'mdghfpblock'),
						'icon'	=> $this->id ? 'process-icon-edit' : 'process-icon-save'
					),
					'buttons' => array(
						array(
							'title' => $m->l('New', 'mdghfpblock'),
							'class' => $this->id ? 'btn btn-default pull-right' : 'hidden',
							'icon'	=> 'process-icon-cancel',
							'href'	=> $m->currentIndex.'&token='.Tools::getValue('token').'&form_tab='.Tools::getValue('form_tab', 1)
						)
					)
				),
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $m->l('Block content', 'mdghfpblock'),
						'icon' 	=> 'icon-cogs',
					),
					'input' => array(
						array(
							'type' 		=> 'select',
							'label' 	=> $m->l('Display from', 'mdghfpblock'),
							'name' 		=> 'type',
							'options'	=> array(
								'id' 		=> 'id_option',
								'name' 		=> 'name',
								'query'		=> array(
									array('id_option' => 1, 'name' => $m->l('products from categories', 'mdghfpblock')),
									array('id_option' => 2, 'name' => $m->l('your own products list', 'mdghfpblock')),
									array('id_option' => 3, 'name' => $m->l('new', 'mdghfpblock')),
									array('id_option' => 4, 'name' => $m->l('best sellers', 'mdghfpblock')),
									array('id_option' => 5, 'name' => $m->l('specials', 'mdghfpblock')),
									array('id_option' => 6, 'name' => $m->l('unsold', 'mdghfpblock')),
								)
							),
						),
						array(
							'type' 		=> 'categories_select',
							'label' 	=> $m->l('Select a category', 'mdghfpblock'),
							'name' 		=> 'categories',
							'category_tree' => $id_categories->render()
						),
						array(
							'type' 		=> 'autocomplete-product',
							'label' 	=> $m->l('Choose your products', 'mdghfpblock'),
							'name' 		=> 'products',
							'values'	=> array(
								'input'		=> explode('-', $fields_values['products_ids']),
								'name'		=> explode('¤', $fields_values['products_names']),
							),
							'required' 	=> true,
							'col' 		=> 4,
						),
						array(
							'type' 		=> 'text',
							'label' 	=> $m->l('Unsold from', 'mdghfpblock'),
							'name' 		=> 'unsold',
							'col' 		=> 1,
							'hint'		=> $m->l('Indicates how many days the products must be unsold to be displayed.', 'mdghfpblock')
						),
					),
					'submit' => array(
						'title' => $this->id ? $m->l('Edit') : $m->l('Save', 'mdghfpblock'),
						'icon'	=> $this->id ? 'process-icon-edit' : 'process-icon-save'
					),
					'buttons' => array(
						array(
							'title' => $m->l('New', 'mdghfpblock'),
							'class' => $this->id ? 'btn btn-default pull-right' : 'hidden',
							'icon'	=> 'process-icon-cancel',
							'href'	=> $m->currentIndex.'&token='.Tools::getValue('token').'&form_tab='.Tools::getValue('form_tab', 1)
						)
					)
				),
			),
			array(
				'form' => array(
					'legend' => array(
						'title' => $m->l('Restrictions', 'mdghfpblock'),
						'icon' 	=> 'icon-cogs',
					),
					'input' => array(
						array(
							'type' 		=> 'select',
							'label' 	=> $m->l('Restriction', 'mdghfpblock'),
							'name' 		=> 'restrict_type',
							'options'	=> array(
								'id' 		=> 'id_option',
								'name' 		=> 'name',
								'query'		=> array(
									array('id_option' => 1, 'name' => $m->l('No restriction', 'mdghfpblock')),
									array('id_option' => 2, 'name' => $m->l('Display only in categories', 'mdghfpblock')),
									array('id_option' => 3, 'name' => $m->l('Display only in products', 'mdghfpblock')),
								)
							),
						),
						array(
							'type' 		=> 'categories_select',
							'label' 	=> $m->l('Select a category', 'mdghfpblock'),
							'name' 		=> 'restricted_categories',
							'category_tree' => $id_rcategories->render()
						),
						array(
							'type' 		=> 'autocomplete-product',
							'label' 	=> $m->l('Choose your products', 'mdghfpblock'),
							'name' 		=> 'restricted_products',
							'values'	=> array(
								'input'		=> explode('-', $fields_values['restricted_products_ids']),
								'name'		=> explode('¤', $fields_values['restricted_products_names']),
							),
							'required' 	=> true,
							'col' 		=> 4,
						),
					),
					'submit' => array(
						'title' => $this->id ? $m->l('Edit') : $m->l('Save', 'mdghfpblock'),
						'icon'	=> $this->id ? 'process-icon-edit' : 'process-icon-save'
					),
					'buttons' => array(
						array(
							'title' => $m->l('New', 'mdghfpblock'),
							'class' => $this->id ? 'btn btn-default pull-right' : 'hidden',
							'icon'	=> 'process-icon-cancel',
							'href'	=> $m->currentIndex.'&token='.Tools::getValue('token').'&form_tab='.Tools::getValue('form_tab', 1)
						)
					)
				),
			),
		);
		

		$helper 							= new HelperForm();
		$helper->show_toolbar 				= false;
		$helper->table 						= 'form';
		$helper->allow_employee_form_lang 	= Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		
		$helper->module 	= $m;
		$helper->identifier = self::$definition['primary'];

		// START FIX BUG - Language
		$languages = Language::getLanguages(true);
		foreach ($languages as &$language) $language['is_default'] = ($language['id_lang'] == Context::getContext()->language->id);
		// END FIX BUG - Language
		$helper->languages 				= $languages;
		$helper->default_form_language 	= Context::getContext()->language->id;
		$helper->id_language		 	= Context::getContext()->language->id;

		$helper->submit_action 		= 'submit_form';
		$helper->currentIndex 		= $m->currentIndex.($this->id?'&id='.$this->id:'');
		$helper->token 				= Tools::getAdminTokenLite('AdminModules');
		$helper->tpl_vars 			= array('fields_value'=> $fields_values);
		$helper->override_folder	= '/';

		return $helper->generateForm($fields_form);
	}


	public function renderList($m)
	{
		$fields_list = array(
			'id' => array(
				'title' => $m->l('Id', 'mdghfpblock'),
				'type' => 'text',
			),
			'position' => array(
				'title' => $m->l('Position', 'mdghfpblock'),
				'type' => 'text',
			),
			'active' => array(
				'title' => '',
				'type' => 'boolean',
				'active' => 'active', 
			),
			'title' => array(
				'title' => $m->l('Title', 'mdghfpblock'),
				'type' => 'text',
			),
			'hook' => array(
				'title' => $m->l('Hook', 'mdghfpblock'),
				'type' => 'text',
			),
		);

		$helper = new HelperList();
		$helper->shopLinkType 		= '';
		$helper->simple_header 		= true;
		$helper->identifier 		= 'id';
		$helper->actions 			= array('edit', 'delete');

		$helper->title 				= '<i class="icon-list-ul"></i> '.$m->l('Blocks list', 'mdghfpblock');
		$helper->table 				= self::SQL_1;
		$helper->token 				= Tools::getAdminTokenLite('AdminModules');
		$helper->currentIndex 		= $m->currentIndex;
		$helper->override_folder	= '/';
		
		$list = $this->getListArray('DESC');

		return (is_array($list) && count($list)) ? $helper->generateList($list, $fields_list) : false;
	}




/* =============================================================== //
	TOOLS
/* =============================================================== */
	/* copyFromPost
	 * Définie les variables de l'objet à partir du contenu du formulaire
	 */
	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST as $key => $value)
			if (key_exists($key, $this) && $key != 'id_'.$this->table)
				$this->{$key} = $value;

		/* Multilingual fields */
		if (count($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
				foreach ($this->fieldsValidateLang as $field => $validation)
					if (Tools::getValue($field.'_'.(int)($language['id_lang'])))
						$this->{$field}[(int)($language['id_lang'])] = Tools::getValue($field.'_'.(int)($language['id_lang']));
		}
	}
	/* getThisValues
	 * Retourne les valeur de l'objet sous forme de tableau
	 * Utilisé en premier lieu pour helper.form
	 */
	public function getThisValues($id)
	{
		$languages 	= Language::getLanguages(false);
		$Class  	= get_class();
		$Obj		= new $Class($id);
		
		$fields_values 		 = array();
		$fields_values['id'] = (int)$id;
		
		foreach (self::$definition['fields'] as $k => $f)
			if (isset($f['lang']) && $f['lang'])
				foreach ($languages as $lang)
					$fields_values[$k][$lang['id_lang']] = Tools::getValue($k.'_'.(int)$lang['id_lang'], (!empty($Obj->{$k}) ? $Obj->{$k}[$lang['id_lang']] : null) );
			else
				$fields_values[$k] = Tools::getValue($k, $Obj->$k);
		
		return $fields_values;
	}
	/* getListArray
	 * Retourne la liste des objet complets sous forme de tableau
	 * Utilisé en premier lieu pour helper.list
	 */
	public function getListArray($way = 'ASC')
	{
		$id_lang 	= (int)Context::getContext()->language->id;
		$id_shop 	= (int)Context::getContext()->shop->id;
		$Class		= get_class();
		$result 	= array();

		$sql = 'SELECT '.self::$definition['primary'].' as id FROM `'._DB_PREFIX_.self::$definition['table'].'` WHERE id_shop='.$id_shop.' ORDER BY '.(isset(self::$definition['fields']['position']) ? 'position' : self::$definition['primary']).' '.$way;
		if (!$res = Db::getInstance()->executeS($sql))
			return $result;

		foreach ($res as $row)
			$result[] = (array)new $Class($row['id'], $id_lang);

		return $result;
	}
}