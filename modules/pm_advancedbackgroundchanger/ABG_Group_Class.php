<?php

class ABG_Group_Class extends ObjectModel{
	public $id;
	public $id_shop;
 	public $bg_position ;
 	public $bg_repeat ;
 	public $bg_fixed ;
 	public $bg_color ;
 	public $overlay ;
 	public $date_start ;
	public $delay;
	public $date_end ;
	public $activation;
 	public $name ;
	public $default_group ;
	public $usertype ;
 	public $image_bg ;

	public $slides = array();
	public $products_categories = array();
	public $productscategorieslive = array();
	public $categories = array();
	public $categorieslive = array();
	public $products = array();
	public $productslive = array();
	public $manufacturers = array();
	public $manufacturerslive = array();
	public $suppliers = array();
	public $supplierslive = array();
	public $usergroup = array();
	public $usergrouplive = array();
	public $cms = array();
	public $cmslive = array();
	public $pages = array();
	public $zones = array();

	protected 	$table 				= 	'pm_advanced_bg_group';
	public	 	$identifier 		= 	'id_group';

	protected 	$fieldsRequired 	= 	array(	'activation',
												'name',
												'delay',
												'date_start',
												'date_end',
												'default_group',
												'usertype',
										);

	protected 	$fieldsSize 		= 	array(	'name'	=>30,
												'activation' =>3,
												'default_group'	=>3,
												'delay'	=>10,
										);

	protected 	$fieldsValidate 	= 	array(	'name' 			=> 'isGenericName',
												'overlay' 		=> 'isGenericName',
												'activation' 	=> 'isUnsignedInt',
												'default_group' => 'isUnsignedInt',
												'date_start'	=> 'isDate',
												'date_end' 		=> 'isDate'	,
												'delay'			=> 'isUnsignedInt',
												'usertype'		=> 'isUnsignedInt',
										);

	public static $definition = array(
		'table' => 'pm_advanced_bg_group',
		'primary' => 'id_group',
		'multishop' => false,
		'multilang_shop' => false
	);

	public function __construct($id = NULL, $id_lang = NULL) {
		parent::__construct($id, $id_lang);
		if ($id) {
			$this->slides = $this->getGroupSlides();
			$this->categories = $this->getGroupCategories();
			$this->products_categories = $this->getGroupProductsCategories();
			$this->products = $this->getGroupProducts();
			$this->manufacturers = $this->getGroupManufacturers();
			$this->suppliers = $this->getGroupSuppliers();
			$this->cms = $this->getGroupCMS();
			$this->pages = $this->getSpecialPages();
			$this->usergroup = $this->getUserGroup();
			$this->zones = $this->getGroupZones();
		}
	}
	
	public function getFields() {
		parent::validateFields();
		if (isset($this->id))
			$fields['id_group'] = (int)($this->id);

		$fields['id_shop'] 		= (int)($this->id_shop);
		$fields['bg_repeat'] 	= pSQL($this->bg_repeat);
		$fields['bg_position'] 	= pSQL($this->bg_position);
		$fields['bg_fixed'] 	= pSQL($this->bg_fixed);
		$fields['bg_color'] 	= pSQL($this->bg_color);
		$fields['overlay']	 	= pSQL($this->overlay);
		$fields['delay'] 		= (int)($this->delay);
		$fields['date_start'] 	= pSQL($this->date_start);
		$fields['date_end'] 	= pSQL($this->date_end);
		$fields['name'] 		= pSQL($this->name);
		$fields['activation'] 	= (int)($this->activation);
		$fields['default_group']= (int)($this->default_group);
		$fields['usertype']		= (int)($this->usertype);

		return $fields;
	}
	
	public static function getGroups($actif=false) {
		if ($actif) {
			return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_group` bgg
			WHERE bgg.`iactivation`i = 1
			AND ((NOW() BETWEEN bgg.`date_start` and bgg.`date_end`) OR (bgg.`date_start` = bgg.`date_end`))' .
			(_PS_VERSION_ >= 1.5 ? ' AND bgg.`id_shop`='.(int)Context::getContext()->shop->id : ''));
		} else {
			return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_group` bgg'.
			(_PS_VERSION_ >= 1.5 ? ' WHERE bgg.`id_shop`='.(int)Context::getContext()->shop->id : ''));
		}
	}
	
	// récupère les pages diverses où le groupe sera affiché
	public function getSpecialPages($with_title = false, $id_lang = false) {
		return abg_core_class::Db_ExecuteS('
			SELECT igim.`page`'.(_PS_VERSION_ >= 1.4 && $with_title && $id_lang ? ', ml.`title`' : '').' 
			FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_page` igim
			'.(_PS_VERSION_ >= 1.4 && $with_title && $id_lang ? 
			'JOIN `'._DB_PREFIX_.'meta` m ON m.`page`=igim.`page`
			JOIN `'._DB_PREFIX_.'meta_lang` ml ON m.`id_meta` = ml.`id_meta`
			'.(_PS_VERSION_ >= 1.5 ? Shop::addSqlRestrictionOnLang('ml') : '') : '').'
			WHERE igim.'.$this->identifier.' = '.(int)$this->id . (_PS_VERSION_ >= 1.4 && $with_title && $id_lang ? ' AND ml.`id_lang` = '.(int)$id_lang.' ' : '') .
			' ORDER BY igim.`page` ASC');
	}
	
	// récupère les pages CMS où le groupe sera affiché
	public function getGroupZones() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_clickzone` m,
				`'._DB_PREFIX_.'pm_advanced_bg_clickzone_lang` ml ,
				`'._DB_PREFIX_.'pm_advanced_bg_idrule_idczone` igim
			WHERE igim.id_rule = '.(int)$this->id .'
			AND m.id_czone = ml.id_czone
			AND m.id_czone = igim.id_czone
			AND ml.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			AND m.status = 1');
	}
	
	// récupère les pages CMS où le groupe sera affiché
	public function getGroupCMS() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'cms` m,
				`'._DB_PREFIX_.'cms_lang` ml ,
				`'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcms` igim
			WHERE igim.'.$this->identifier.' = '.(int)$this->id .'
			AND m.id_cms = ml.id_cms
			AND m.id_cms = igim.id_cms
			AND ml.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			;');
	}
	
	// Récupère les groupes d'utilisateurs oùle groupe sera affiché
	public function getUserGroup() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'group` m,
				`'._DB_PREFIX_.'group_lang` ml ,
				`'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` igim
			WHERE igim.id_rule = '.(int)$this->id .'
			AND m.id_group = ml.id_group
			AND m.id_group = igim.id_group
			AND ml.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			;');
	}
	
	// récupère les livreurs où le groupe sera affiché
	public function getGroupSuppliers() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'supplier` m,
				`'._DB_PREFIX_.'pm_advanced_bg_idgroup_idsupp` igim
			WHERE igim.'.$this->identifier.' = '.(int)$this->id .'
			AND m.id_supplier = igim.id_supplier
			;');
	}
	
	// récupère les fabricants où le groupe sera affiché
	public function getGroupManufacturers() {
		$req = abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'manufacturer` m,
				`'._DB_PREFIX_.'pm_advanced_bg_idgroup_idman` igim
			WHERE igim.'.$this->identifier.' = '.(int)$this->id .'
			AND m.id_manufacturer = igim.id_manufacturer
			;');
		return $req;
	}
	
	// récupère les produits où  le groupe sera affiché
	public function getGroupProducts() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'product` p,
				`'._DB_PREFIX_.'product_lang` pl ,
				`'._DB_PREFIX_.'pm_advanced_bg_idgroup_idproduct` igip
				' . (_PS_VERSION_ >= 1.5 ? ', `' . _DB_PREFIX_ . 'product_shop` ps ' : '') . '
			WHERE igip.'.$this->identifier.' = '.(int)$this->id .'
			'.(_PS_VERSION_ >= 1.5 ? ' AND p.`id_product`=ps.`id_product` ' : '').'
			'.(_PS_VERSION_ >= 1.5 ? Shop::addSqlRestriction(false, 'ps') : '').'
			AND p.id_product= pl.id_product
			AND p.id_product = igip.id_product
			AND pl.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			'.(_PS_VERSION_ >= 1.5 ? ' GROUP BY p.`id_product` ' : '').'
			;');
	}
	
	// récupère les categories où le groupe sera affiché
	public function getGroupCategories() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'category` c				
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category`'.(_PS_VERSION_ >= 1.5 ? Shop::addSqlRestrictionOnLang('cl'):'').')
			INNER JOIN `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcat` igic ON (c.id_category = igic.id_cat)
			WHERE igic.'.$this->identifier.' = '.(int)$this->id .'
			AND cl.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			ORDER BY cl.name ASC;');
	}

	// récupère les categories des produits où le groupe sera affiché
	public function getGroupProductsCategories() {
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'category` c				
			LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category`'.(_PS_VERSION_ >= 1.5 ? Shop::addSqlRestrictionOnLang('cl'):'').')
			INNER JOIN `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idprodcat` igipc ON (c.id_category = igipc.id_cat)
			WHERE igipc.'.$this->identifier.' = '.(int)$this->id .'
			AND cl.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			ORDER BY cl.name ASC;');
	}

	// récupère les slides du groupe
	public function getGroupSlides($actif=false, $type = false) {
		global $cookie;
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_slide` s,
				`'._DB_PREFIX_.'pm_advanced_bg_slide_lang` sl ,
				`'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` igis
			WHERE igis.'.$this->identifier.' = '.(int)$this->id .'
			AND s.id_slide = sl.id_slide
			AND s.id_slide = igis.id_slide
			AND sl.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).
			($type == 'static'?'
			AND s.bg_type = 1':'').
			($type == 'slide'?'
			AND s.bg_type = 2':'').
			($actif?'
			AND s.activation = 1
			AND ((NOW() BETWEEN s.`date_start` and s.`date_end`)
			OR (s.`date_start` = s.`date_end`))':'').'
			ORDER BY igis.sort
			');
	}
	
	private function saveDependance() {
		$this->deleteDependance();
		// Categories
		if (is_array($this->categorieslive) && sizeof($this->categorieslive)) {
			if (Tools::getValue('bool_cat') || Tools::getValue('duplicateGroup')) {
				foreach ($this->categorieslive as $cat) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcat` (`'.$this->identifier.'`, id_cat) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($cat) ? $cat['id_category'] : $cat).'")');
				}
			}
		}
		// Categories products
		if (is_array($this->productscategorieslive) && sizeof($this->productscategorieslive)) {
			if (Tools::getValue('bool_prod_cat') || Tools::getValue('duplicateGroup')) {
				foreach ($this->productscategorieslive as $cat) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idprodcat` (`'.$this->identifier.'`, id_cat) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($cat) ? $cat['id_category'] : $cat).'")');
				}
			}
		}
		// Products
		if (is_array($this->productslive) && sizeof($this->productslive)) {
			if (Tools::getValue('bool_prod') || Tools::getValue('duplicateGroup')) {
				foreach ($this->productslive as $prod) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idproduct` (`'.$this->identifier.'`, id_product) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($prod) ? $prod['id_product'] : $prod).'")');
				}
			}
		}
		// Manufacturers
		if (is_array($this->manufacturerslive) && sizeof($this->manufacturerslive)) {
			if (Tools::getValue('bool_manu') || Tools::getValue('duplicateGroup')) {
				foreach ($this->manufacturerslive as $manu) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idman` (`'.$this->identifier.'`, id_manufacturer) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($manu) ? $manu['id_manufacturer'] : $manu).'")');
				}
			}
		}
		// Suppliers
		if (is_array($this->supplierslive) && sizeof($this->supplierslive)) {
			if (Tools::getValue('bool_supp') || Tools::getValue('duplicateGroup')) {
				foreach ($this->supplierslive as $supp) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idsupp` (`'.$this->identifier.'`, id_supplier) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($supp) ? $supp['id_supplier'] : $supp).'")');
				}
			}
		}
		// User type
		if (is_array($this->usergrouplive) && sizeof($this->usergrouplive)) {
			if ($this->usertype == 2 || Tools::getValue('duplicateGroup')) {
				foreach ($this->usergrouplive as $usergr) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` (`id_rule`, id_group) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($usergr) ? $usergr['id_group'] : $usergr).'")');
				}
			}
		}
		// CMS Page
		if (is_array($this->cmslive) && sizeof($this->cmslive)) {
			if(Tools::getValue('bool_cms') || Tools::getValue('duplicateGroup') ) {
				foreach ($this->cmslive as $cm) {
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcms` (`'.$this->identifier.'`, id_cms) VALUES ("'.(int)$this->id.'", "'.(int)(is_array($cm) ? $cm['id_cms'] : $cm).'")');
				}
			}
		}
		// Specials pages
		if (isset($_POST['special_pages']) && $_POST['special_pages']) {
			if (_PS_VERSION_ < 1.4) {
				$pages_array = explode(',', $_POST['special_pages']);
			} else {
				$pages_array = array_unique(Tools::getValue('special_pages'));
			}
			if (Tools::getValue('bool_spe')) {
				foreach ($pages_array as $path) {
					$path = trim($path);
					abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_page` (`'.$this->identifier.'`, page) VALUES ("'.(int)$this->id.'", "'.$path.'")');
				}
			}
		}
		if( Tools::getValue('duplicateGroup') ){
			foreach ($this->pages  as $path) {
				abg_core_class::Db_Execute('INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_page` (`'.$this->identifier.'`, page) VALUES ("'.(int)$this->id.'", "'.$path['page'].'")');
			}
		}
	}
	
	public function save($nullValues = false, $autodate = true) {
		if(_PS_VERSION_ >= 1.5) $this->id_shop = Context::getContext()->shop->id;
		if(parent::save($nullValues,$autodate)){
			if (Tools::isSubmit('Submit_Group') || Tools::isSubmit('Submit_Wizard') || Tools::getValue('duplicateGroup') == 1) {
				// Groupe par défaut, on supprime les dépendances
				if($this->default_group == 1) {
					$this->deleteDependance(false);
				} else {
					$this->saveDependance();
				}
			}
			return true;
		}
		return false;
	}
	
	public function deleteDependance($deleteGroupRestriction = true) {
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcat` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idprodcat` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idproduct` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcms` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idman` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idsupp` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_page` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		if ($deleteGroupRestriction)
			abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` WHERE `id_rule` = '.(int)$this->id);
	}


	public function delete(){
		$this->deleteDependance();
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		return parent::delete();
	}
}
