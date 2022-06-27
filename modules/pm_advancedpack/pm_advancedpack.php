<?php
/**
 * @name Advanced Pack 5
 * @author Presta-Module.com <support@presta-module.com> - http://www.presta-module.com
 * @copyright Presta-Module 2015 - http://www.presta-module.com
 * @version 5.0.5
 * @psversion 1.5, 1.6
 * @languages EN, FR
 * @category front_office_features
 *
 * 		 	 ____     __  __
 * 			|  _ \   |  \/  |
 * 			| |_) |  | |\/| |
 * 			|  __/   | |  | |
 * 			|_|      |_|  |_|
 *
 *
 *************************************
 **         Advanced Pack 5         **
 *************************************
 */
if (!defined('_PS_VERSION_'))
	exit;
include_once _PS_ROOT_DIR_ . '/modules/pm_advancedpack/AdvancedPack.php';
include_once _PS_ROOT_DIR_ . '/modules/pm_advancedpack/AdvancedPackCoreClass.php';
class pm_advancedpack extends AdvancedPackCoreClass {
	const PACK_CONTENT_SHOPPING_CART = 1;
	const PACK_CONTENT_BLOCK_CART = 2;
	const PACK_CONTENT_ORDER_CONFIRMATION_EMAIL = 3;
	static $_preventInfiniteLoop = false;
	static $_preventUpdateQuantityCompleteHook = false;
	static $_validateOrderProcess = false;
	static $_productListQuantityToUpdate = array();
	protected $_defaultConfiguration = array(
		'bootstrapTheme' => false,
		'enablePackCrossSellingBlock' => true,
		'limitPackNbCrossSelling' => 0,
		'orderByCrossSelling' => 'date_add_asc',
		'showImagesOnlyForCombinations' => false,
		'enableViewThisPackButton' => true,
		'viewThisPackButtonBackgroundColor' => array('#4ea870', '#4ea870'),
		'viewThisPackButtonFontColor' => '#ffffff',
		'enableBuyThisPackButton' => true,
		'buyThisPackButtonBackgroundColor' => array('#009ad0', '#009ad0'),
		'buyThisPackButtonFontColor' => '#ffffff',
		'showProductsAvailability' => false,
		'showProductsFeatures' => true,
		'showProductsShortDescription' => true,
		'showProductsLongDescription' => true,
		'autoScrollBuyBlock' => true,
		'tabActiveBackgroundColor' => array('#009ad0', '#007ab7'),
		'tabActiveFontColor' => '#ffffff',
		'tabActiveBorderColor' => '#0079b6',
		'ribbonBackgroundColor' => array('#1899cf', '#127bb8'),
		'ribbonFontColor' => '#ffffff',
		'ribbonBorderColor' => '#009ad0',
		'iconPlusFontColor' => '#000000',
		'iconRemoveFontColor' => '#000000',
		'iconCheckFontColor' => '#000000',
		'imageFormatProductZoom' => 'thickbox_default',
		'imageFormatProductCover' => 'home_default',
		'imageFormatProductCoverMobile' => 'home_default',
		'imageFormatProductSlideshow' => 'cart_default',
		'imageFormatProductFooterCover' => 'medium_default',
	);
	protected $_cssMapTable = array(
		'tabActiveBackgroundColor' => array(
			array(
				'type' => 'bg_gradient',
				'selector' => '#ap5-pack-product-tab-list > li.active > a',
			),
		),
		'tabActiveFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '#ap5-pack-product-tab-list > li.active > a',
			),
		),
		'tabActiveBorderColor' => array(
			array(
				'type' => 'border_color',
				'selector' => '#ap5-pack-product-tab-list > li.active > a',
			),
		),
		'ribbonBackgroundColor' => array(
			array(
				'type' => 'bg_gradient',
				'selector' => '.ap5-pack-product-content .ribbon',
			),
			array(
				'type' => 'keyframes_spin',
				'selector' => 'keyframes_spin',
			),
		),
		'ribbonFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '.ap5-pack-product-content .ribbon',
			),
		),
		'ribbonBorderColor' => array(
			array(
				'type' => 'border_top_color',
				'selector' => '.ap5-pack-product-content .ribbon:before, .ap5-pack-product-content .ribbon:after',
			),
		),
		'iconPlusFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '.ap5-pack-product .ap5-pack-product-icon-plus:before',
			),
		),
		'iconRemoveFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '.ap5-pack-product:hover .ap5-pack-product-icon-remove:after',
			),
		),
		'iconCheckFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '.ap5-is-excluded-product .ap5-pack-product-icon-check:after',
			),
		),
		'viewThisPackButtonFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '.ap5-product-footer-pack-name a span.ap5-view-pack-button',
			),
		),
		'viewThisPackButtonBackgroundColor' => array(
			array(
				'type' => 'bg_gradient',
				'selector' => '.ap5-product-footer-pack-name a span.ap5-view-pack-button',
			),
		),
		'buyThisPackButtonFontColor' => array(
			array(
				'type' => 'color',
				'selector' => '.ap5-product-footer-pack-name a span.ap5-buy-pack-button',
			),
		),
		'buyThisPackButtonBackgroundColor' => array(
			array(
				'type' => 'bg_gradient',
				'selector' => '.ap5-product-footer-pack-name a span.ap5-buy-pack-button',
			),
		),
	);
	protected $_file_to_check = array('css');
	public function __construct() {
		$this->need_instance = 0;
		$this->name = 'pm_advancedpack';
		$this->module_key = '7e2464eca3e8dc2d1a5a7e93da1d82b4';
		$this->author = 'Presta-Module';
		$this->tab = 'pricing_promotion';
		$this->version = '5.0.5';
		$this->displayName = 'Advanced Pack';
		$this->description = $this->l('Add a product bundling strategy into your store, sell more !');
		if (version_compare(_PS_VERSION_, '1.6.0.0', '<'))
			$this->_defaultConfiguration['imageFormatProductSlideshow'] = 'medium_default';
		parent::__construct();
	}
	public function install() {
		if (!parent::install()
			|| !$this->installDatabase()
			|| !$this->registerHook('displayHeader')
			|| !$this->registerHook('displayFooter')
			|| !$this->registerHook('displayFooterProduct')
			|| !$this->registerHook('actionValidateOrder')
			|| !$this->registerHook('moduleRoutes')
			|| !$this->registerHook('displayOverrideTemplate')
			|| !$this->registerHook('actionProductAdd')
			|| !$this->registerHook('actionProductUpdate')
			|| !$this->registerHook('actionProductDelete')
			|| !$this->registerHook('actionCartSave')
			|| !$this->registerHook('actionBeforeCartUpdateQty')
			|| !$this->registerHook('displayShoppingCartFooter')
			|| (version_compare(_PS_VERSION_, '1.6.0.0', '<') && !$this->registerHook('displayRightColumn'))
			|| !$this->registerHook('actionObjectOrderAddAfter')
			|| !$this->registerHook('actionObjectCombinationDeleteAfter')
			|| !$this->registerHook('actionUpdateQuantity')
			|| !$this->registerHook('displayBackOfficeHeader')
			|| !$this->registerHook('displayAdminProductsExtra')
			|| !$this->registerHook('actionAdminControllerSetMedia')
			|| !$this->_addCustomAttributeGroup()
			|| !$this->_addAdminTab()
			|| !$this->_updateModulePosition()
		) return false;
		$this->_checkIfModuleIsUpdate(true, false, true);
		return true;
	}
	private function _updateModulePosition() {
		$res = true;
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
			$hookList = array('displayFooterProduct', 'actionValidateOrder');
		else
			$hookList = array('displayLeftColumn', 'displayRightColumn', 'displayFooterProduct', 'actionValidateOrder');
		foreach ($hookList as $hookName) {
			$idHook = Hook::getIdByName($hookName);
			if ($idHook) {
				foreach (Shop::getContextListShopID() as $idShop)
					$res &= Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'hook_module`
						SET `position`=0
						WHERE `id_module` = '.(int)$this->id.'
						AND `id_hook` = '.(int)$idHook.' AND `id_shop` = '.$idShop);
				$res &= $this->cleanPositions($idHook, Shop::getContextListShopID());
			}
		}
		if (!$res)
			$this->context->controller->errors[] = $this->displayName . ' - ' . $this->l('Unable to update module position for hook right & left column');
		return $res;
	}
	private function _addAdminTab() {
		$res = true;
		if (!Validate::isLoadedObject(Tab::getInstanceFromClassName('AdminPacks'))) {
			$adminTab = new Tab();
			foreach (Language::getLanguages(false) as $lang)
				$adminTab->name[(int)$lang['id_lang']] = $this->l('Packs');
			$adminTab->class_name = 'AdminPacks';
			$adminTab->id_parent = Tab::getInstanceFromClassName('AdminProducts')->id_parent;
			$adminTab->module = $this->name;
			$res &= $adminTab->add();
			$res &= $adminTab->updatePosition('l', 2);
		}
		if (!$res)
			$this->context->controller->errors[] = $this->displayName . ' - ' . $this->l('Unable to add AdminTab "AdminPacks"');
		return $res;
	}
	private function _addCustomAttributeGroup() {
		Configuration::updateValue('PS_COMBINATION_FEATURE_ACTIVE', true);
		$alreadyExists = (AdvancedPack::getPackAttributeGroupId() !== false);
		if (!$alreadyExists) {
			$attributeGroupObj = new AttributeGroup();
			$attributeGroupObj->is_color_group = false;
			$attributeGroupObj->group_type = 'select';
			foreach (Language::getLanguages(false) as $lang) {
				$attributeGroupObj->name[$lang['id_lang']] = 'AP5-Pack';
				$isoCode = Tools::strtolower($lang['iso_code']);
				if (in_array($isoCode, array('fr', 'be', 'lu')))
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Contenu du pack';
				else if (in_array($isoCode, array('es', 'ar', 'mx')))
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Contenido del pack';
				else if ($isoCode == 'it')
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Contenuto della pacchetto';
				else if ($isoCode == 'nl')
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Pak inhoud';
				else if ($isoCode == 'dk')
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Pack indhold';
				else if (in_array($isoCode, array('de', 'at')))
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Packungsinhalt';
				else if (in_array($isoCode, array('pt', 'br')))
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Conteúdo da pacote';
				else
					$attributeGroupObj->public_name[$lang['id_lang']] = 'Pack content';
			}
			if (!$attributeGroupObj->add()) {
				$this->context->controller->errors[] = $this->displayName . ' - ' . $this->l('Unable to add custom attribute group');
				return false;
			} else {
				return true;
			}
		}
		return $alreadyExists;
	}
	protected function _updateDb() {
		$columnsToAdd = array(
			array ('pm_advancedpack', 'allow_remove_product', 'tinyint(3) unsigned DEFAULT 0', 'fixed_price')
		);
		foreach ($columnsToAdd as $columnInfos)
			$this->_columnExists($columnInfos[0], $columnInfos[1], true, $columnInfos[2], (isset($columnInfos[3]) ? $columnInfos[3] : false));
	}
	public function getContent() {
		if (Tools::getIsset('adminPackContentUpdate') && Tools::getIsset('getProductExtraInformations') && Tools::getValue('getProductExtraInformations') && Tools::getIsset('productId') && Tools::getValue('productId')) {
			$idProduct = (int)Tools::getValue('productId');
			$warehouseListId = array();
			$idWarehouse = 0;
			if (Validate::isUnsignedId($idProduct) && $idProduct > 0) {
				$productObj = new Product($idProduct, true, $this->context->language->id);
				if (Validate::isLoadedObject($productObj)) {
					if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && Product::usesAdvancedStockManagement($productObj->id)) {
						$warehouseList = Warehouse::getProductWarehouseList($productObj->id, ($productObj->hasAttributes() ? Product::getDefaultAttribute($productObj->id) : 0));
						if (self::_isFilledArray($warehouseList)) {
							foreach ($warehouseList as $warehouseRow)
								$warehouseListId[] = (int)$warehouseRow['id_warehouse'];
							$warehouseListId = array_unique($warehouseListId);
							if (sizeof($warehouseListId) == 1)
								$idWarehouse = current($warehouseListId);
						}
					}
				}
			}
			die(Tools::jsonEncode(array('idWarehouse' => $idWarehouse, 'warehouseListId' => $warehouseListId)));
		} else if (Tools::getIsset('adminPackContentUpdate') && Tools::getIsset('updatePackPriceSimulation') && Tools::getValue('updatePackPriceSimulation') && Tools::getIsset('productFormValues') && Tools::getValue('productFormValues')) {
			$productFormValues = $packProducts = array();
			parse_str(Tools::getValue('productFormValues'), $productFormValues);
			$packClassicPrice = $packClassicPriceWt = $packPrice = $packPriceWt = $totalPackEcoTax = $totalPackEcoTaxWt = 0;
			$packSettings = array('fixedPrice' => null);
			$idTaxRulesGroup = array();
			$advancedStockManagement = $advancedStockManagementAlert = false;
			if (((Configuration::hasKey('PS_FORCE_ASM_NEW_PRODUCT') && Configuration::get('PS_FORCE_ASM_NEW_PRODUCT')) || !Configuration::hasKey('PS_FORCE_ASM_NEW_PRODUCT')) && Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
				$advancedStockManagement = true;
			if (isset($productFormValues['ap5_price_rules']) && $productFormValues['ap5_price_rules'] == 3 && isset($productFormValues['ap5_fixed_pack_price']) && $productFormValues['ap5_fixed_pack_price'] > 0)
				$packSettings['fixedPrice'] = $productFormValues['ap5_fixed_pack_price'];
			list( , $useTax) = AdvancedPack::getAddressInstance();
			foreach ($productFormValues['ap5_productList'] as $idProductPack) {
				$packProducts[] = array(
					'id_product_pack' => (is_numeric($idProductPack) && $idProductPack ? (int)$idProductPack : null),
					'id_product' => $productFormValues['ap5_originalIdProduct-' . $idProductPack],
					'quantity' => $productFormValues['ap5_quantity-' . $idProductPack],
					'reduction_amount' => ($productFormValues['ap5_reductionType-' . $idProductPack] == 'percentage' ? $productFormValues['ap5_reductionAmount-' . $idProductPack] / 100 : $productFormValues['ap5_reductionAmount-' . $idProductPack]),
					'reduction_type' => $productFormValues['ap5_reductionType-' . $idProductPack],
					'exclusive' => (isset($productFormValues['ap5_exclusive-' . $idProductPack]) && $productFormValues['ap5_exclusive-' . $idProductPack] ? (int)$productFormValues['ap5_exclusive-' . $idProductPack] : 0),
					'use_reduc' => (isset($productFormValues['ap5_useReduc-' . $idProductPack]) && $productFormValues['ap5_useReduc-' . $idProductPack] ? (int)$productFormValues['ap5_useReduc-' . $idProductPack] : 0),
					'default_id_product_attribute' => (isset($productFormValues['ap5_customCombinations-' . $idProductPack]) && $productFormValues['ap5_customCombinations-' . $idProductPack] ? $productFormValues['ap5_defaultCombination-' . $idProductPack] : (int)Product::getDefaultAttribute((int)$productFormValues['ap5_originalIdProduct-' . $idProductPack])),
					'customCombinations' => (isset($productFormValues['ap5_customCombinations-' . $idProductPack]) && $productFormValues['ap5_customCombinations-' . $idProductPack] ? $productFormValues['ap5_combinationInclude-' . $idProductPack] : array()),
				);
				$idTaxRulesGroup[] = (int)Product::getIdTaxRulesGroupByIdProduct((int)$productFormValues['ap5_originalIdProduct-' . $idProductPack]);
				if ($advancedStockManagement && !Product::usesAdvancedStockManagement((int)$productFormValues['ap5_originalIdProduct-' . $idProductPack]))
					$advancedStockManagementAlert = true;
			}
			$idTaxRulesGroup = array_unique($idTaxRulesGroup);
			if (!sizeof($idTaxRulesGroup))
				$finalIdTaxRulesGroup = null;
			else if (sizeof($idTaxRulesGroup) == 1)
				$finalIdTaxRulesGroup = (int)current($idTaxRulesGroup);
			else
				$finalIdTaxRulesGroup = 0;
			$packProducts = AdvancedPack::getPackPriceTable($packProducts, $packSettings['fixedPrice'], (is_null($finalIdTaxRulesGroup) ? 0 : $finalIdTaxRulesGroup), $useTax, true);
			foreach ($packProducts as $packProduct) {
				$packClassicPrice += $packProduct['priceInfos']['productClassicPrice'] * $packProduct['priceInfos']['quantity'];
				$packClassicPriceWt += $packProduct['priceInfos']['productClassicPriceWt'] * $packProduct['priceInfos']['quantity'];
				$packPriceWt += $packProduct['priceInfos']['productPackPriceWt'] * $packProduct['priceInfos']['quantity'];
				$packPrice += $packProduct['priceInfos']['productPackPrice'] * $packProduct['priceInfos']['quantity'];
				$totalPackEcoTax += $packProduct['priceInfos']['productEcoTax'] * $packProduct['priceInfos']['quantity'];
				$totalPackEcoTaxWt += $packProduct['priceInfos']['productEcoTax'] * $packProduct['priceInfos']['quantity'];
			}
			$this->context->smarty->assign(array(
				'packClassicPrice' => $packClassicPrice,
				'packClassicPriceWt' => $packClassicPriceWt,
				'discountPercentage' => ($packPrice <= $packClassicPrice) ? number_format((1 - ($packPrice / $packClassicPrice)) * -100, 2) : 0,
				'packPrice' => $packPrice,
				'packPriceWt' => $packPriceWt,
				'totalPackEcoTax' => $totalPackEcoTax,
				'totalPackEcoTaxWt' => $totalPackEcoTaxWt
			));
			die(Tools::jsonEncode(array('advancedStockManagementAlert' => $advancedStockManagementAlert, 'idTaxRulesGroup' => $finalIdTaxRulesGroup, 'html' => $this->display(__FILE__, 'views/templates/hook/' . Tools::substr(_PS_VERSION_, 0, 3) . '/admin-product-tab-pack-price-simulation.tpl'))));
		} else if (Tools::getIsset('adminPackContentUpdate') && Tools::getIsset('addPackLine') && Tools::getValue('addPackLine') && Tools::getIsset('productId') && Tools::getValue('productId')) {
			$idProduct = (int)Tools::getValue('productId');
			if (Validate::isUnsignedId($idProduct) && $idProduct > 0) {
				$productObj = new Product($idProduct, true, $this->context->language->id);
				if (Validate::isLoadedObject($productObj)) {
					$uniqid = uniqid(self::$_module_prefix);
					$packContent = array(
						$uniqid => array(
							'id_product_pack' => $uniqid,
							'id_product' => $idProduct,
							'productObj' => $productObj,
							'productCombinations' => $productObj->getAttributesResume($this->context->language->id),
							'productCombinationsWhiteList' => array(),
							'exclusive' => 0,
							'use_reduc' => 0,
							'quantity' => 1,
							'reduction_type' => 'percentage',
							'reduction_amount' => 0
						)
					);
					$this->context->smarty->assign(array(
						'link' => $this->context->link,
						'defaultCurrency' => Currency::getDefaultCurrency(),
						'packContent' => $packContent
					));
					die(Tools::jsonEncode(array('html' => $this->display(__FILE__, 'views/templates/hook/' . Tools::substr(_PS_VERSION_, 0, 3) . '/admin-product-tab-pack-table.tpl'))));
				}
			}
		} else if (Tools::getIsset('adminPackContentUpdate') && Tools::getIsset('packContent')) {
			$packContent = $packContentJSON = array();
			foreach (Tools::getValue('packContent') as $idProductPack => $idProduct) {
				$productObj = new Product($idProduct, true, $this->context->language->id);
				$packContentJSON[$idProductPack] = array(
					'id_product_pack' => $idProductPack,
					'id_product' => $idProduct,
				);
				$packContent[$idProductPack] = array(
					'id_product_pack' => $idProductPack,
					'id_product' => $idProduct,
					'productObj' => $productObj,
					'productCombinations' => $productObj->getAttributesResume($this->context->language->id),
					'productCombinationsWhiteList' => array(),
					'exclusive' => 0,
					'use_reduc' => 0,
					'quantity' => 0,
					'reduction_type' => 'percentage',
					'reduction_amount' => (float)0.10,
				);
			}
			$this->context->smarty->assign(array(
				'link' => $this->context->link,
				'defaultCurrency' => Currency::getDefaultCurrency(),
				'packContent' => $packContent
			));
			die(Tools::jsonEncode(array('packContent' => $packContentJSON, 'html' => $this->display(__FILE__, 'views/templates/hook/' . Tools::substr(_PS_VERSION_, 0, 3) . '/admin-product-tab-pack-table.tpl'))));
		} else if (Tools::getIsset('adminProductList') && Tools::getIsset('q')) {
			$query = Tools::getValue('q', false);
			if (!$query OR $query == '' OR Tools::strlen($query) < 1)
				die();
			if($pos = strpos($query, ' (ref:'))
				$query = Tools::substr($query, 0, $pos);
			$excludeIds = implode(',', array_map('intval', AdvancedPack::getIdsPacks(true)));
			$sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, p.`cache_default_attribute`
					FROM `'._DB_PREFIX_.'product` p
					'.Shop::addSqlAssociation('product', 'p').'
					LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = '.(int)Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('pl').')
					WHERE (pl.name LIKE \'%'.pSQL($query).'%\' OR p.reference LIKE \'%'.pSQL($query).'%\')'.
					(!empty($excludeIds) ? ' AND p.id_product NOT IN ('.$excludeIds.') ' : ' ').
					'AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' .
					' GROUP BY p.id_product';
			$items = Db::getInstance()->executeS($sql);
			if ($items)
				foreach ($items AS $item)
					echo trim($item['name']).(!empty($item['reference']) ? ' (ref: '.$item['reference'].')' : '').'|'.(int)($item['id_product'])."\n";
			die;
		} else if (Tools::getIsset('dismissRating') && Tools::getValue('dismissRating')) {
			if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
				Configuration::updateGlobalValue('PM_'.AdvancedPackCoreClass::$_module_prefix.'_DISMISS_RATING', 1);
			else
				Configuration::updateValue('PM_'.AdvancedPackCoreClass::$_module_prefix.'_DISMISS_RATING', 1);
			die;
		} else {
			if (Tools::getIsset('submitModuleConfiguration') && Tools::isSubmit('submitModuleConfiguration') || Tools::getIsset('submitAdvancedStyles') && Tools::isSubmit('submitAdvancedStyles')) {
				$this->_postProcess();
			}
			parent::getContent();
			if (!$this->_checkPermissions())
				return;
			if (Tools::getValue('makeUpdate'))
				$this->_checkIfModuleIsUpdate(true);
			if (!$this->_checkIfModuleIsUpdate(false)) {
				$this->context->controller->warnings [] = '
					<p>' . $this->l('We have detected that you installed a new version of the module on your shop') . '</p>
					<p style="text-align: center"><a href="' . $this->_base_config_url . '&makeUpdate=1" class="button">' . $this->l('Please click here in order to finish the installation process') . '</a></p>';
				return;
			}
			$this->_showRating(false);
			$this->_html .= '<div id="pm-module-configuration-wrapper">';
			if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
				$this->_html .= '<div class="alert alert-info">';
			else
				$this->_html .= '<div class="info">';
			$this->_html .= '<p>'.$this->l('Do you want to add a new pack ?').'</p>';
			$this->_html .= '<p><a href="'. $this->context->link->getAdminLink('AdminPacks') .'"><strong>'.$this->l('Please go to Catalog > Packs, or click here').'</strong></a></p>';
			$this->_html .= '</div>';
			$this->_html .= '<div id="ap5-config-tab">';
			$this->_html .= '<ul>
				<li><a href="#ap5-configuration">'. $this->l('Configuration') .'</a></li>
				<li><a href="#ap5-advanced-styles">'. $this->l('Advanced Styles') .' - CSS</a></li>
			</ul>';
			$this->_html .= '<div id="ap5-configuration">';
			$this->_renderConfigurationForm();
			$this->_html .= '</div>';
			$this->_html .= '<div id="ap5-advanced-styles">';
			$this->_displayAdvancedStyles();
			$this->_html .= '</div>';
			$this->_html .= '</div><!-- end ap5-config-tab -->';
			$this->_html .= '<script>$(function() { $( "#ap5-config-tab" ).tabs(); });</script>';
			$this->_html .= '</div><!-- end pm-module-configuration-wrapper -->';
			$this->_displaySupport();
			return $this->_html;
		}
	}
	private function _postProcess() {
		if (Tools::getIsset('submitModuleConfiguration') && Tools::isSubmit('submitModuleConfiguration')) {
			$config = $this->_getModuleConfiguration();
			foreach (array('bootstrapTheme', 'enablePackCrossSellingBlock', 'enableViewThisPackButton', 'enableBuyThisPackButton', 'showImagesOnlyForCombinations', 'autoScrollBuyBlock', 'showProductsAvailability', 'showProductsFeatures', 'showProductsShortDescription', 'showProductsLongDescription') as $configKey)
				$config[$configKey] = (bool)Tools::getValue($configKey);
			foreach (array('tabActiveBackgroundColor', 'ribbonBackgroundColor', 'viewThisPackButtonBackgroundColor', 'buyThisPackButtonBackgroundColor') as $configKey)
				$config[$configKey] = (array)Tools::getValue($configKey);
			foreach (array('tabActiveFontColor', 'tabActiveBorderColor', 'ribbonFontColor', 'ribbonBorderColor', 'iconPlusFontColor', 'iconRemoveFontColor', 'iconCheckFontColor', 'viewThisPackButtonFontColor', 'buyThisPackButtonFontColor', 'imageFormatProductZoom', 'imageFormatProductCover', 'imageFormatProductCoverMobile', 'imageFormatProductSlideshow', 'imageFormatProductFooterCover', 'orderByCrossSelling') as $configKey)
				$config[$configKey] = trim(Tools::getValue($configKey));
			foreach (array('limitPackNbCrossSelling') as $configKey)
				$config[$configKey] = (int)trim(Tools::getValue($configKey));
			$this->_setModuleConfiguration($config);
			$this->_generateCSS();
			$this->context->controller->confirmations[] = $this->l('Module configuration successfully saved');
		} else if (Tools::getIsset('submitAdvancedStyles') && Tools::isSubmit('submitAdvancedStyles') && Tools::getIsset('advancedCSSStyles')) {
			$this->_updateAdvancedStyles(Tools::getValue('advancedCSSStyles'));
			$this->context->controller->confirmations[] = $this->l('Module configuration successfully saved');
		}
	}
	private function _renderConfigurationForm() {
		$config = $this->_getModuleConfiguration();
		$this->_startForm(array('id' => 'formGlobalOptions', 'iframetarget' => false, 'target' => '_self'));
		$this->_html .= '<h4>'. $this->l('Settings for pack page') .'</h4>';
		if (version_compare(_PS_VERSION_, '1.6.0.0', '<'))
			$this->_displayInputActive(array(
				'obj' => $config,
				'key_active' => 'bootstrapTheme',
				'key_db' => 'bootstrapTheme',
				'defaultvalue' => $this->_defaultConfiguration['bootstrapTheme'],
				'tips' => $this->l('Please only select Yes if you are sure about it.'),
				'label' => $this->l('Are you using a Boostrap theme ?')));
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
			$this->_displayInputActive(array(
				'obj' => $config,
				'key_active' => 'autoScrollBuyBlock',
				'key_db' => 'autoScrollBuyBlock',
				'defaultvalue' => $this->_defaultConfiguration['autoScrollBuyBlock'],
				'tips' => $this->l('If the user scroll down to bottom, the buy block will follow in order to be always visible and grow your conversion rate.'),
				'label' => $this->l('Enable auto-scroll of buy block ?')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'showImagesOnlyForCombinations',
			'key_db' => 'showImagesOnlyForCombinations',
			'defaultvalue' => $this->_defaultConfiguration['showImagesOnlyForCombinations'],
			'tips' => $this->l('If enabled, will only show product images that are linked to the selected combination. Example for dresses, if you choose red color, only red dresses images will be displayed (images+combinations associations must be done correctly on the product).'),
			'label' => $this->l('Only show images linked to the current product combinations ?')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'showProductsLongDescription',
			'key_db' => 'showProductsLongDescription',
			'defaultvalue' => $this->_defaultConfiguration['showProductsLongDescription'],
			'label' => $this->l('Show pack product description')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'showProductsShortDescription',
			'key_db' => 'showProductsShortDescription',
			'defaultvalue' => $this->_defaultConfiguration['showProductsShortDescription'],
			'label' => $this->l('Show pack product short description')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'showProductsFeatures',
			'key_db' => 'showProductsFeatures',
			'defaultvalue' => $this->_defaultConfiguration['showProductsFeatures'],
			'label' => $this->l('Show pack product features')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'showProductsAvailability',
			'key_db' => 'showProductsAvailability',
			'defaultvalue' => $this->_defaultConfiguration['showProductsAvailability'],
			'label' => $this->l('Show pack product availability')));
		$this->_displayInputGradient(array(
			'obj' => $config,
			'key' => 'tabActiveBackgroundColor',
			'defaultvalue' => $this->_defaultConfiguration['tabActiveBackgroundColor'],
			'label' => $this->l('Active tabs background color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'tabActiveFontColor',
			'defaultvalue' => $this->_defaultConfiguration['tabActiveFontColor'],
			'label' => $this->l('Active tabs text color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'tabActiveBorderColor',
			'defaultvalue' => $this->_defaultConfiguration['tabActiveBorderColor'],
			'label' => $this->l('Active tabs border color'))
		);
		$this->_displayInputGradient(array(
			'obj' => $config,
			'key' => 'ribbonBackgroundColor',
			'defaultvalue' => $this->_defaultConfiguration['ribbonBackgroundColor'],
			'label' => $this->l('Ribbon (used for quantity) background color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'ribbonFontColor',
			'defaultvalue' => $this->_defaultConfiguration['ribbonFontColor'],
			'label' => $this->l('Ribbon (used for quantity) text color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'ribbonBorderColor',
			'defaultvalue' => $this->_defaultConfiguration['ribbonBorderColor'],
			'label' => $this->l('Ribbon (used for quantity) border color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'iconPlusFontColor',
			'defaultvalue' => $this->_defaultConfiguration['iconPlusFontColor'],
			'label' => $this->l('Product separator icon color (between products)'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'iconRemoveFontColor',
			'defaultvalue' => $this->_defaultConfiguration['iconRemoveFontColor'],
			'label' => $this->l('Remove product icon color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'iconCheckFontColor',
			'defaultvalue' => $this->_defaultConfiguration['iconCheckFontColor'],
			'label' => $this->l('Reinstate product icon color'))
		);
		$this->_displaySelect(array(
			'obj' => $config,
			'key' => 'imageFormatProductCover',
			'options' => self::_getProductsImagesTypes(),
			'defaultvalue' => $this->_defaultConfiguration['imageFormatProductCover'],
			'label' => $this->l('Size of products main image'),
			'tips' => $this->l('Define the product image size you want to show for this option.')));
		$this->_displaySelect(array(
			'obj' => $config,
			'key' => 'imageFormatProductCoverMobile',
			'options' => self::_getProductsImagesTypes(),
			'defaultvalue' => $this->_defaultConfiguration['imageFormatProductCoverMobile'],
			'label' => $this->l('Size of products main image (mobile)'),
			'tips' => $this->l('Define the product image size you want to show for this option.')));
		$this->_displaySelect(array(
			'obj' => $config,
			'key' => 'imageFormatProductSlideshow',
			'options' => self::_getProductsImagesTypes(),
			'defaultvalue' => $this->_defaultConfiguration['imageFormatProductSlideshow'],
			'label' => $this->l('Size of products thumbnails'),
			'tips' => $this->l('Define the product image size you want to show for this option.')));
		$this->_displaySelect(array(
			'obj' => $config,
			'key' => 'imageFormatProductZoom',
			'options' => self::_getProductsImagesTypes(),
			'defaultvalue' => $this->_defaultConfiguration['imageFormatProductZoom'],
			'label' => $this->l('Size of products zoom'),
			'tips' => $this->l('Define the product image size you want to show for this option.')));
		$this->_html .= '<h4>'. $this->l('Settings for "This product is also available in pack" block') .'</h4>';
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'enablePackCrossSellingBlock',
			'key_db' => 'enablePackCrossSellingBlock',
			'defaultvalue' => $this->_defaultConfiguration['enablePackCrossSellingBlock'],
			'tips' => $this->l('If enabled, show a list of packs related to the current product (product footer hook)'),
			'label' => $this->l('Show "This product is also available in pack" block ?')));
		$this->_displayInputText(array(
			'obj' => $config,
			'type' => 'number',
			'min' => 0,
			'maxlength' => 2,
			'size' => '50px',
			'required' => true,
			'key' => 'limitPackNbCrossSelling',
			'defaultvalue' => $this->_defaultConfiguration['limitPackNbCrossSelling'],
			'label' => $this->l('Maximum pack to show (0 = unlimited)')));
		$this->_displaySelect(array(
			'obj' => $config,
			'key' => 'orderByCrossSelling',
			'options' => $this->_getCrossSellingOrderByOptions(),
			'defaultvalue' => $this->_defaultConfiguration['orderByCrossSelling'],
			'label' => $this->l('Sort pack list by')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'enableViewThisPackButton',
			'key_db' => 'enableViewThisPackButton',
			'defaultvalue' => $this->_defaultConfiguration['enableViewThisPackButton'],
			'tips' => $this->l('If enabled, show a button in order to go to the pack page'),
			'label' => $this->l('Show "View this pack" button ?')));
		$this->_displayInputActive(array(
			'obj' => $config,
			'key_active' => 'enableBuyThisPackButton',
			'key_db' => 'enableBuyThisPackButton',
			'defaultvalue' => $this->_defaultConfiguration['enableBuyThisPackButton'],
			'tips' => $this->l('If enabled, show a button in order to add the pack to cart without going on the pack page'),
			'label' => $this->l('Show "Buy this pack" button ?')));
		$this->_displayInputGradient(array(
			'obj' => $config,
			'key' => 'viewThisPackButtonBackgroundColor',
			'defaultvalue' => $this->_defaultConfiguration['viewThisPackButtonBackgroundColor'],
			'label' => $this->l('"View this pack" button background color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'viewThisPackButtonFontColor',
			'defaultvalue' => $this->_defaultConfiguration['viewThisPackButtonFontColor'],
			'label' => $this->l('"View this pack" button text color'))
		);
		$this->_displayInputGradient(array(
			'obj' => $config,
			'key' => 'buyThisPackButtonBackgroundColor',
			'defaultvalue' => $this->_defaultConfiguration['buyThisPackButtonBackgroundColor'],
			'label' => $this->l('"Buy this pack" button background color'))
		);
		$this->_displayInputColor(array(
			'obj' => $config,
			'key' => 'buyThisPackButtonFontColor',
			'defaultvalue' => $this->_defaultConfiguration['buyThisPackButtonFontColor'],
			'label' => $this->l('"Buy this pack" button text color'))
		);
		$this->_displaySelect(array(
			'obj' => $config,
			'key' => 'imageFormatProductFooterCover',
			'options' => self::_getProductsImagesTypes(),
			'defaultvalue' => $this->_defaultConfiguration['imageFormatProductFooterCover'],
			'label' => $this->l('Size of products main image'),
			'tips' => $this->l('Define the product image size you want to show for this option.')));
		$this->_displaySubmit($this->l('   Save   '), 'submitModuleConfiguration');
		$this->_endForm(array('id' => 'formGlobalOptions', 'includehtmlatend' => true));
	}
	public function hookModuleRoutes() {
		return array(
			'module-pm_advancedpack-add_pack' => array(
				'controller' => 'add_pack',
				'rule' => 'pack/add/{id_pack}',
				'keywords' => array(
					'id_pack'		=> array('regexp' => '[0-9]+', 'param' => 'id_pack'),
					'module'		=> array('regexp' => 'pm_advancedpack', 'param' => 'module'),
					'controller'	=> array('regexp' => 'add_pack', 'param' => 'controller'),
				),
				'params' => array(
					'fc' => 'module',
					'module' => 'pm_advancedpack',
					'ajax' => 1
				)
			),
			'module-pm_advancedpack-update_pack' => array(
				'controller' => 'update_pack',
				'rule' => 'pack/update/{id_pack}',
				'keywords' => array(
					'id_pack'		=> array('regexp' => '[0-9]+', 'param' => 'id_pack'),
					'module'		=> array('regexp' => 'pm_advancedpack', 'param' => 'module'),
					'controller'	=> array('regexp' => 'update_pack', 'param' => 'controller'),
				),
				'params' => array(
					'fc' => 'module',
					'module' => 'pm_advancedpack',
					'ajax' => 1
				)
			),
			'module-pm_advancedpack-update_cart' => array(
				'controller' => 'update_cart',
				'rule' => 'pack/update_cart',
				'keywords' => array(
					'module'		=> array('regexp' => 'pm_advancedpack', 'param' => 'module'),
					'controller'	=> array('regexp' => 'update_cart', 'param' => 'controller'),
				),
				'params' => array(
					'fc' => 'module',
					'module' => 'pm_advancedpack',
					'ajax' => 1
				)
			),
		);
	}
	public function hookActionValidateOrder($params) {
		self::$_validateOrderProcess = false;
		if (isset($params['order']) && isset($params['cart']) && isset($params['orderStatus']) && Validate::isLoadedObject($params['order']) && Validate::isLoadedObject($params['cart']) && Validate::isLoadedObject($params['orderStatus'])) {
			$order = $params['order'];
			$cart = $params['cart'];
			$orderStatus = $params['orderStatus'];
			$orderHasPack = $outOfStock = $orderHasNoTaxPack = false;
			$vatDifferenceFixedPricePack = 0;
			list($vatAddress, $useTax) = AdvancedPack::getAddressInstance();
			$vatAddress = new Address((int)($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
			if (self::_isFilledArray($order->product_list)) {
				foreach ($order->product_list as $orderProduct) {
					if ((int)$orderProduct['id_product_attribute'] && AdvancedPack::isValidPack((int)$orderProduct['id_product'])) {
						$orderHasPack = true;
						$packProducts = AdvancedPack::getPackContent((int)$orderProduct['id_product'], (int)$orderProduct['id_product_attribute']);
						if (self::_isFilledArray($packProducts)) {
							$packFixedPrice = AdvancedPack::getPackFixedPrice((int)$orderProduct['id_product']);
							$packProducts = AdvancedPack::getPackPriceTable($packProducts, $packFixedPrice, AdvancedPack::getPackIdTaxRulesGroup((int)$orderProduct['id_product']), $useTax, true, true);
							foreach ($packProducts as $packProduct) {
								$null = null;
								$product = new Product((int)$packProduct['id_product'], false, (int)$order->id_lang);
								$idTaxRules = (int)Product::getIdTaxRulesGroupByIdProduct((int)$packProduct['id_product']);
								$taxManager = TaxManagerFactory::getManager($vatAddress, $idTaxRules);
								$taxCalculator = $taxManager->getTaxCalculator();
								$orderDetail = new OrderDetail();
								$orderDetail->id_shop = $order->id_shop;
								$orderDetail->id_order = $order->id;
								$orderDetail->product_id = (int)($packProduct['id_product']);
								$orderDetail->product_attribute_id = (int)($packProduct['id_product_attribute'] ? (int)($packProduct['id_product_attribute']) : null);
								$orderDetail->download_deadline = '0000-00-00 00:00:00';
								$orderDetail->download_hash = null;
								if ($id_product_download = ProductDownload::getIdFromIdProduct((int)($packProduct['id_product']))) {
									$productDownload = new ProductDownload((int)($id_product_download));
									$orderDetail->download_deadline = $productDownload->getDeadLine();
									$orderDetail->download_hash = $productDownload->getHash();
									unset($productDownload);
								}
								$orderDetail->ecotax = Tools::convertPrice((float)$product->ecotax, (int)$order->id_currency);
								if (!Tax::excludeTaxeOption()) {
									$orderDetail->tax_computation_method = (int)$taxCalculator->computation_method;
								}
								$orderDetail->ecotax_tax_rate = 0;
								if (!empty($product->ecotax))
									$orderDetail->ecotax_tax_rate = Tax::getProductEcotaxRate($order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
								$orderDetail->total_shipping_price_tax_incl = 0;
								$specific_price = null;
								$orderDetail->original_product_price = AdvancedPack::getPriceStaticPack((int)$packProduct['id_product'], false, (int)$packProduct['id_product_attribute'], 6, null, false, (bool)$packProduct['use_reduc'], 1, null, null, null, $null, true, true);
								$orderDetail->product_price = $orderDetail->original_product_price;
								$orderDetail->unit_price_tax_incl = (float)$packProduct['priceInfos']['productPackPriceWt'];
								$orderDetail->unit_price_tax_excl = (float)$packProduct['priceInfos']['productPackPrice'];
								$orderDetail->total_price_tax_incl = (float)$orderDetail->unit_price_tax_incl * (int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity'];
								$orderDetail->total_price_tax_excl = (float)$orderDetail->unit_price_tax_excl * (int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity'];
								if ($product->id_supplier > 0)
									$orderDetail->purchase_supplier_price = (float)ProductSupplier::getProductPrice((int)$product->id_supplier, (int)$packProduct['id_product'], (int)$packProduct['id_product_attribute'], true);
								$orderDetail->reduction_amount = 0.00;
								$orderDetail->reduction_percent = 0.00;
								$orderDetail->reduction_amount_tax_incl = 0.00;
								$orderDetail->reduction_amount_tax_excl = 0.00;
								if ($packProduct['reduction_amount'] > 0) {
									if ($packProduct['reduction_type'] == 'amount') {
										$orderDetail->reduction_amount = (float)Tools::ps_round($useTax ? $taxCalculator->addTaxes($packProduct['reduction_amount']) : $packProduct['reduction_amount'], 2);
										$orderDetail->reduction_amount_tax_incl = (float)$orderDetail->reduction_amount;
										$orderDetail->reduction_amount_tax_excl = (float)$packProduct['reduction_amount'];
									} else if ($packProduct['reduction_type'] == 'percentage') {
										$orderDetail->reduction_percent = (float)$packProduct['reduction_amount'] * 100;
									}
								}
								$orderDetail->group_reduction = (float)(Group::getReduction((int)($order->id_customer)));
								$quantityDiscount = SpecificPrice::getQuantityDiscount((int)$packProduct['id_product'], $this->context->shop->id,
									(int)$cart->id_currency, (int)$vatAddress->id_country,
									(int)$this->context->customer->id_default_group, (int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity'], false, null, null, $null, true, true);
								$unitPrice = AdvancedPack::getPriceStaticPack((int)$packProduct['id_product'], true,
									((int)$packProduct['id_product_attribute'] ? (int)$packProduct['id_product_attribute'] : null),
									2, null, false, (bool)$packProduct['use_reduc'], 1, (int)$order->id_customer, null, (int)$order->{Configuration::get('PS_TAX_ADDRESS_TYPE')}, $null, true, true);
								$orderDetail->product_quantity_discount = 0.00;
								if ($quantityDiscount)
								{
									$orderDetail->product_quantity_discount = $unitPrice;
									if (Product::getTaxCalculationMethod((int)$order->id_customer) == PS_TAX_EXC)
										$orderDetail->product_quantity_discount = Tools::ps_round($unitPrice, 2);
									if (isset($orderDetail->tax_calculator))
										$orderDetail->product_quantity_discount -= $orderDetail->tax_calculator->addTaxes($quantityDiscount['price']);
								}
								$orderDetail->discount_quantity_applied = (($specific_price && $specific_price['from_quantity'] > 1) ? 1 : 0);
								$attributeDatas = AdvancedPack::getProductAttributeList((int)$packProduct['id_product_attribute'], $order->id_lang);
								$orderDetail->product_name = $this->l('Pack') . ' ' . (int)$orderProduct['id_product'] . ' - ' . $product->name . ((isset($attributeDatas['attributes']) && $attributeDatas['attributes'] != null) ? ' - '.$attributeDatas['attributes'] : '');
								$orderDetail->product_quantity = (int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity'];
								$productStockAvailable = StockAvailable::getQuantityAvailableByProduct((int)$packProduct['id_product'], (int)$packProduct['id_product_attribute']);
								if ($orderDetail->product_attribute_id != null) {
									$productCombination = new Combination($orderDetail->product_attribute_id);
									$orderDetail->product_ean13 = empty($productCombination->ean13) ? null : pSQL($productCombination->ean13);
									$orderDetail->product_upc = empty($productCombination->upc) ? null : pSQL($productCombination->upc);
									$orderDetail->product_reference = empty($productCombination->reference) ? null : pSQL($productCombination->reference);
									$orderDetail->product_weight = (float)$product->weight + (float)$productCombination->weight;
									$orderDetail->purchase_supplier_price = (float)$productCombination->wholesale_price;	
									if ($orderDetail->product_reference == null)
										$orderDetail->product_reference = empty($product->reference) ? null : pSQL($product->reference);
									if ($orderDetail->product_ean13 == null)
										$orderDetail->product_ean13 = empty($product->ean13) ? null : pSQL($product->ean13);
									if ($orderDetail->product_upc == null)
										$orderDetail->product_upc = empty($product->upc) ? null : pSQL($product->upc);
								} else {
									$orderDetail->product_ean13 = empty($product->ean13) ? null : pSQL($product->ean13);
									$orderDetail->product_upc = empty($product->upc) ? null : pSQL($product->upc);
									$orderDetail->product_reference = empty($product->reference) ? null : pSQL($product->reference);
									$orderDetail->product_weight = (float)$product->weight;
									$orderDetail->purchase_supplier_price = (float)$product->wholesale_price;	
								}
								$orderDetail->product_supplier_reference = empty($product->supplier_reference) ? null : pSQL($product->supplier_reference);
								$orderDetail->id_warehouse = 0;
								if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && StockAvailable::dependsOnStock((int)$orderDetail->product_id)) {
									$warehouseList = Warehouse::getProductWarehouseList($orderDetail->product_id, (int)$orderDetail->product_attribute_id);
									if (self::_isFilledArray($warehouseList)) {
										$defaultWarehouse = current($warehouseList);
										$orderDetail->id_warehouse = (int)$defaultWarehouse['id_warehouse'];
									}
								}
								$productQuantity = (int)(Product::getQuantity($orderDetail->product_id, $orderDetail->product_attribute_id));
								$orderDetail->product_quantity_in_stock = ($productQuantity - ((int)($packProduct['quantity']) * (int)$orderProduct['cart_quantity']) < 0) ?
									$productQuantity : ((int)($packProduct['quantity'] * (int)$orderProduct['cart_quantity']));
								if ($orderStatus->id != Configuration::get('PS_OS_CANCELED') && $orderStatus->id != Configuration::get('PS_OS_ERROR')) {
									$updateQuantity = true;
									self::$_preventUpdateQuantityCompleteHook = true;
									if (!StockAvailable::dependsOnStock((int)$packProduct['id_product']))
										$updateQuantity = StockAvailable::updateQuantity((int)$packProduct['id_product'], (int)$packProduct['id_product_attribute'], -(int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity']);
									self::$_preventUpdateQuantityCompleteHook = false;
									if ($updateQuantity)
										$productStockAvailable -= (int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity'];
									if ($productStockAvailable < 0 && Configuration::get('PS_STOCK_MANAGEMENT'))
										$outOfStock = true;
									Product::updateDefaultAttribute((int)$packProduct['id_product']);
								}
								if ($orderDetail->add()) {
									Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'pm_advancedpack_cart_products` SET `id_order`=' . (int)$orderDetail->id_order . ' WHERE `id_cart`=' . (int)$order->id_cart . ' AND `id_pack`=' . (int)$orderProduct['id_product'] . ' AND `id_product_attribute`=' . (int)$orderDetail->product_attribute_id);
									if ($orderStatus->logable)
										ProductSale::addProductSale((int)$packProduct['id_product'], (int)$packProduct['quantity'] * (int)$orderProduct['cart_quantity']);
									if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && StockAvailable::dependsOnStock((int)$packProduct['id_product']))
										StockAvailable::synchronize((int)$packProduct['id_product'], $order->id_shop);
									if ($taxCalculator == null || !($taxCalculator instanceOf TaxCalculator) || count($taxCalculator->taxes) == 0 || $order->total_products <= 0) {
										continue;
									} else {
										$ratio = $orderDetail->unit_price_tax_excl / $order->total_products;
										$order_reduction_amount = $order->total_discounts_tax_excl * $ratio;
										$discounted_price_tax_excl = $orderDetail->unit_price_tax_excl - $order_reduction_amount;
										$values = '';
										foreach ($taxCalculator->getTaxesAmount($discounted_price_tax_excl) as $id_tax => $amount) {
											$unit_amount = (float)Tools::ps_round($amount, 2);
											$total_amount = $unit_amount * $orderDetail->product_quantity;
											$values .= '('.(int)$orderDetail->id.','.(float)$id_tax.','.$unit_amount.','.(float)$total_amount.'),';
										}
										Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'order_detail_tax` WHERE id_order_detail='.(int)$orderDetail->id);
										$values = rtrim($values, ',');
										Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'order_detail_tax` (id_order_detail, id_tax, unit_amount, total_amount) VALUES '.$values);
									}
								}
							}
						}
					}
				}
			}
			if ($orderHasPack) {
				$orderDetailsList = OrderDetail::getList($order->id);
				if (self::_isFilledArray($orderDetailsList)) {
					foreach ($orderDetailsList as $orderDetailRow) {
						if ((int)$orderDetailRow['product_attribute_id'] && AdvancedPack::isValidPack((int)$orderDetailRow['product_id'])) {
							AdvancedPack::updatePackStock((int)$orderDetailRow['product_id']);
							$odObj = new OrderDetail((int)$orderDetailRow['id_order_detail']);
							if ($odObj->delete())
								AdvancedPack::setStockAvailableQuantity((int)$orderDetailRow['product_id'], (int)$orderDetailRow['product_attribute_id'], 0, false);
							if (!AdvancedPack::getPackIdTaxRulesGroup((int)$orderDetailRow['product_id'])) {
								$orderHasNoTaxPack = true;
								$vatDifference = AdvancedPack::getPackPrice((int)$orderDetailRow['product_id'], true, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$orderDetailRow['product_id'], (int)$orderDetailRow['product_attribute_id'])) - AdvancedPack::getPackPrice((int)$orderDetailRow['product_id'], false, true, true, 6, AdvancedPack::getIdProductAttributeListByIdPack((int)$orderDetailRow['product_id'], (int)$orderDetailRow['product_attribute_id']));
								$order->total_products -= (float)$vatDifference;
								$order->total_paid_tax_excl -= (float)Tools::ps_round((float)$vatDifference, 2);
							}
						}
					}
				}
				if ($orderHasNoTaxPack) {
					$order->total_products -= $vatDifferenceFixedPricePack;
					$order->total_paid_tax_excl -= (float)Tools::ps_round((float)$vatDifferenceFixedPricePack, 2);
					$order->save();
				}
				AdvancedPack::clearAP5Cache();
				if ($outOfStock && Configuration::get('PS_STOCK_MANAGEMENT')) {
					$lastOrderHistory = $order->getCurrentOrderState();
					if (Validate::isLoadedObject($lastOrderHistory) && $lastOrderHistory->id_order_state != Configuration::get('PS_OS_OUTOFSTOCK')) {
						$history = new OrderHistory();
						$history->id_order = (int)$order->id;
						$history->changeIdOrderState(Configuration::get('PS_OS_OUTOFSTOCK'), $order, true);
						$history->addWithemail();
					}
				}
			}
			if (self::_isFilledArray(self::$_productListQuantityToUpdate)) {
				$this->_massUpdateQuantity(self::$_productListQuantityToUpdate);
				self::$_productListQuantityToUpdate = array();
			}
		}
	}
	protected function getCurrentProduct($transformDescription = false) {
		if (is_object($this->context->controller) && preg_match('/^ProductController/i', get_class($this->context->controller))) {
			if (method_exists($this->context->controller, 'getProduct'))
				$product = $this->context->controller->getProduct();
			else {
				$id_product = (int)Tools::getValue('id_product');
				if (Validate::isUnsignedId($id_product)) {
					$product = new Product((int)$id_product, true, $this->context->language->id, $this->context->shop->id);
					if ($transformDescription && Validate::isLoadedObject($product))
						AdvancedPack::transformProductDescriptionWithImg($product);
				}
			}
			if (Validate::isLoadedObject($product))
				return $product;
		}
		return false;
	}
	public function hookDisplayOverrideTemplate($params) {
		$product = $this->getCurrentProduct();
		if (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id)) {
			$this->_assignSmartyVars('pack', $product->id);
			return $this->getTemplatePath('views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack.tpl');
		}
	}
	private function _assignSmartyImageTypeVars() {
		$config = $this->_getModuleConfiguration();
		foreach ($config as $configKey => $configValue) {
			if (preg_match('/^imageFormat/', $configKey)) {
				$imageTypeSize = Image::getSize($configValue);
				$this->context->smarty->assign(array(
					$configKey => $configValue,
					$configKey.'Width' => ($imageTypeSize['width'] ? (int)$imageTypeSize['width'] : ''),
					$configKey.'Height' =>($imageTypeSize['height'] ? (int)$imageTypeSize['height'] : '')
				));
			}
		}
	}
	private function _assignSmartyVars($context, $idPack = null) {
		if ($context == 'pack' && $idPack) {
			$packAttributesList = array();
			$packErrorsList = array();
			$packFatalErrorsList = array();
			$packContent = AdvancedPack::getPackContent($idPack, null, false, $packAttributesList);
			$packQuantityList = AdvancedPack::getPackAvailableQuantityList($idPack);
			if ($packContent !== false) {
				foreach ($packContent as $packProduct) {
					$product = new Product((int)$packProduct['id_product']);
					if (!isset($packAttributesList[$packProduct['id_product_pack']]) || !is_numeric($packAttributesList[$packProduct['id_product_pack']]))
						$defaultIdProductAttribute = (int)$packProduct['default_id_product_attribute'];
					else
						$defaultIdProductAttribute = (int)$packAttributesList[$packProduct['id_product_pack']];
					if (Validate::isLoadedObject($product) && !$product->active)
						$packFatalErrorsList[(int)$packProduct['id_product_pack']][] = $this->getFrontTranslation('errorProductIsDisabled');
					else if (Validate::isLoadedObject($product) && !$product->checkAccess(isset(Context::getContext()->customer) ? Context::getContext()->customer->id : 0))
						$packFatalErrorsList[(int)$packProduct['id_product_pack']][] = $this->getFrontTranslation('errorProductAccessDenied');
					else if (Validate::isLoadedObject($product) && !$product->available_for_order)
						$packFatalErrorsList[(int)$packProduct['id_product_pack']][] = $this->getFrontTranslation('errorProductIsNotAvailableForOrder');
					else if (isset($packQuantityList[(int)$packProduct['id_product_pack']]) && array_sum($packQuantityList[(int)(int)$packProduct['id_product_pack']]) <= 0)
						$packFatalErrorsList[(int)$packProduct['id_product_pack']][] = $this->getFrontTranslation('errorProductIsOutOfStock');
					else if (isset($packQuantityList[(int)$packProduct['id_product_pack']][$defaultIdProductAttribute]) && $packQuantityList[(int)(int)$packProduct['id_product_pack']][$defaultIdProductAttribute] <= 0)
						$packErrorsList[(int)$packProduct['id_product_pack']][] = $this->getFrontTranslation('errorProductOrCombinationIsOutOfStock');
				}
			}
			$packContent = AdvancedPack::getPackContent($idPack, null, true);
			$config = $this->_getModuleConfiguration();
			$this->_assignSmartyImageTypeVars();
			$currentProduct = $this->getCurrentProduct(true);
			$currentProduct->quantity = AdvancedPack::getPackAvailableQuantity($idPack);
			$this->context->smarty->assign(array(
				'product' => $currentProduct,
				'bootstrapTheme' => (bool)$config['bootstrapTheme'],
				'productsPack' => $packContent,
				'productsPackUnique' => AdvancedPack::getPackContentGroupByProduct($packContent),
				'packAvailableQuantity' => AdvancedPack::getPackAvailableQuantity($idPack),
				'packMaxImagesPerProduct' => AdvancedPack::getMaxImagesPerProduct($packContent),
				'productsPackErrors' => $packErrorsList,
				'productsPackFatalErrors' => $packFatalErrorsList,
				'packAttributesList' => array(),
				'packAllowRemoveProduct' => AdvancedPack::getPackAllowRemoveProduct($idPack),
				'packExcludeList' => array(),
				'packShowProductsAvailability' => (isset($config['showProductsAvailability']) ? $config['showProductsAvailability'] : $this->_defaultConfiguration['showProductsAvailability']),
				'packShowProductsFeatures' => (isset($config['showProductsFeatures']) ? $config['showProductsFeatures'] : $this->_defaultConfiguration['showProductsFeatures']),
				'packShowProductsShortDescription' => (isset($config['showProductsShortDescription']) ? $config['showProductsShortDescription'] : $this->_defaultConfiguration['showProductsShortDescription']),
				'packShowProductsLongDescription' => (isset($config['showProductsLongDescription']) ? $config['showProductsLongDescription'] : $this->_defaultConfiguration['showProductsLongDescription']),
				'groups' => null,
				'combinations' => null,
				'combinationImages' => null,
				'attributesCombinations' => array(),
			));
		}
	}
	public function hookDisplayHeader($params) {
		$product = $this->getCurrentProduct();
		if (Validate::isLoadedObject($product)) {
			if (AdvancedPack::isValidPack($product->id)) {
				$this->context->controller->addCSS($this->_path.'css/owl.carousel.min.css', 'all');
				$this->context->controller->addCSS($this->_path.'css/animate.min.css', 'all');
				$this->context->controller->addCSS($this->_path.'css/pack.css', 'all');
				$this->context->controller->addJS($this->_path.'js/owl.carousel.min.js');
				$this->context->controller->addJS($this->_path.'js/pack.js');
				$this->removeJSFromController(_THEME_JS_DIR_.'product.js');
				$config = $this->_getModuleConfiguration();
				if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
					$this->context->controller->addCSS($this->_path.str_replace('{id_shop}', $this->context->shop->id, self::DYN_CSS_FILE), 'all');
					Media::addJsDef(array(
						'ap5_autoScrollBuyBlock' => (bool)$config['autoScrollBuyBlock'],
						'ap5_updatePackURL' => self::getPackUpdateURL($product->id),
						'ap5_isPS16' => true,
						'ap5_bootstrapTheme' => true,
					));
				} else {
					$this->context->controller->addCSS($this->_path.'css/pack-ps15.css', 'all');
					$this->context->controller->addCSS($this->_path.str_replace('{id_shop}', $this->context->shop->id, self::DYN_CSS_FILE), 'all');
					$this->smarty->assign(array(
						'ap5_updatePackURL' => self::getPackUpdateURL($product->id),
						'ap5_bootstrapTheme' => (bool)$config['bootstrapTheme']
					));
					return $this->display(__FILE__, 'views/templates/hook/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-header.tpl');
				}
			} else if (self::_isFilledArray(AdvancedPack::getIdPacksByIdProduct($product->id))) {
				$config = $this->_getModuleConfiguration();
				if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
					Media::addJsDef(array(
						'ap5_isPS16' => true,
						'ap5_bootstrapTheme' => true,
					));
				} else {
					$this->smarty->assign(array(
						'ap5_updatePackURL' => self::getPackUpdateURL($product->id),
						'ap5_bootstrapTheme' => (bool)$config['bootstrapTheme']
					));
					return $this->display(__FILE__, 'views/templates/hook/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-header.tpl');
				}
			}
		}
	}
	public function hookActionProductAdd($params) {
		if (self::$_preventInfiniteLoop)
			return;
		$idProduct = false;
		if (isset($params['product']) && Validate::isLoadedObject($params['product'])) {
			$idProduct = (int)$params['product']->id;
		} else if (isset($params['id_product']) && (int)$params['id_product'] > 0) {
			$idProduct = (int)$params['id_product'];
		}
		if ($idProduct !== false) {
			if (Tools::getIsset('ap5_is_edited_pack') && Tools::getValue('ap5_is_edited_pack')) {
				$this->_postProcessAdminProducts($idProduct, true);
				$this->_updatePackFields((int)$idProduct, true);
			}
		}
	}
	public function hookActionProductUpdate($params) {
		if (self::$_preventInfiniteLoop)
			return;
		$idProduct = false;
		if (isset($params['product']) && Validate::isLoadedObject($params['product'])) {
			$idProduct = (int)$params['product']->id;
		} else if (isset($params['id_product']) && (int)$params['id_product'] > 0) {
			$idProduct = (int)$params['id_product'];
		}
		if ($idProduct !== false) {
			if (AdvancedPack::isValidPack($idProduct)) {
				if (Tools::getIsset('ap5_is_edited_pack') && Tools::getValue('ap5_is_edited_pack'))
					$this->_postProcessAdminProducts($idProduct, false, (Tools::getIsset('ap5_is_major_edited_pack') && Tools::getValue('ap5_is_major_edited_pack')));
				$this->_updatePackFields((int)$idProduct);
			} else {
				if (Shop::getContext() != Shop::CONTEXT_SHOP) {
					$oldContext = Shop::getContext();
					foreach (AdvancedPack::getIdPacksByIdProduct((int)$idProduct) as $idPack) {
						Shop::setContext(Shop::CONTEXT_SHOP, AdvancedPack::getPackIdShop($idPack));
						$this->_updatePackFields((int)$idPack);
					}
					Shop::setContext($oldContext);
				} else {
					foreach (AdvancedPack::getIdPacksByIdProduct((int)$idProduct) as $idPack) {
						$this->_updatePackFields((int)$idPack);
					}
				}
			}
		}
	}
	public function hookActionUpdateQuantity($params) {
		if (isset($params['id_product']) && is_numeric($params['id_product']) && (int)$params['id_product'] > 0) {
			if (AdvancedPack::isValidPack($params['id_product']))
				return;
			if (self::$_validateOrderProcess) {
				self::$_productListQuantityToUpdate[] = (int)$params['id_product'];
				return;
			}
			Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int)$params['id_product'].'*');
			foreach (AdvancedPack::getIdPacksByIdProduct((int)$params['id_product']) as $idPack) {
				$sql = new DbQuery();
				$sql->select('GROUP_CONCAT(DISTINCT `id_product_attribute_pack`)');
				$sql->from('pm_advancedpack_cart_products', 'acp');
				$sql->where('acp.`id_pack`='.(int)$idPack);
				$sql->where('acp.`id_order` IS NULL');
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
				if ($result !== false && !empty($result)) {
					$result = array_map('intval', explode(',', $result));
					if (self::_isFilledArray($result))
						foreach ($result as $idProductAttribute)
							if ((int)$idProductAttribute > 0)
								AdvancedPack::setStockAvailableQuantity((int)$idPack, (int)$idProductAttribute, AdvancedPack::getPackAvailableQuantity((int)$idPack, AdvancedPack::getIdProductAttributeListByIdPack((int)$idPack, (int)$idProductAttribute)));
				}
				if (!self::$_preventUpdateQuantityCompleteHook)
					AdvancedPack::updatePackStock((int)$idPack);
			}
		}
	}
	private function _massUpdateQuantity($productList) {
		if (self::_isFilledArray($productList)) {
			$productList = array_unique($productList);
			$idPackList = $idProductList = array();
			foreach ($productList as $idProduct) {
				$tmpIdPackList = AdvancedPack::getIdPacksByIdProduct((int)$idProduct);
				if (self::_isFilledArray($tmpIdPackList)) {
					$idPackList = array_merge($tmpIdPackList, $idPackList);
					$idProductList[] = (int)$idProduct;
				}
			}
			$idPackList = array_unique($idPackList);
			$idProductList = array_unique($idProductList);
			if (self::_isFilledArray($idPackList)) {
				foreach ($idPackList as $idPack) {
					$sql = new DbQuery();
					$sql->select('GROUP_CONCAT(DISTINCT `id_product_attribute_pack`)');
					$sql->from('pm_advancedpack_cart_products', 'acp');
					$sql->where('acp.`id_pack`='.(int)$idPack);
					$sql->where('acp.`id_order` IS NULL');
					$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
					if ($result !== false && !empty($result)) {
						$result = array_map('intval', explode(',', $result));
						if (self::_isFilledArray($result))
							foreach ($result as $idProductAttribute)
								if ((int)$idProductAttribute > 0)
									AdvancedPack::setStockAvailableQuantity((int)$idPack, (int)$idProductAttribute, AdvancedPack::getPackAvailableQuantity((int)$idPack, AdvancedPack::getIdProductAttributeListByIdPack((int)$idPack, (int)$idProductAttribute)));
					}
					AdvancedPack::updatePackStock((int)$idPack);
				}
			}
		}
	}
	public function hookActionProductDelete($params) {
		if (isset($params['product']) && Validate::isLoadedObject($params['product'])) {
			$clearCache = false;
			if (AdvancedPack::isValidPack($params['product']->id)) {
				Db::getInstance()->delete('pm_advancedpack', '`id_pack`='.(int)$params['product']->id);
				Db::getInstance()->delete('pm_advancedpack_products', '`id_pack`='.(int)$params['product']->id);
				Db::getInstance()->delete('pm_advancedpack_cart_products', '`id_order` IS NULL AND `id_pack`='.(int)$params['product']->id);
				Db::getInstance()->delete('pm_advancedpack_products_attributes', '`id_product_pack` NOT IN (SELECT `id_product_pack` FROM `'._DB_PREFIX_.'pm_advancedpack_products`)');
				$clearCache = true;
			} else {
				$packList = AdvancedPack::getIdPacksByIdProduct((int)$params['product']->id);
				Db::getInstance()->delete('pm_advancedpack_products', '`id_product`='.(int)$params['product']->id);
				Db::getInstance()->delete('pm_advancedpack_products_attributes', '`id_product_pack` NOT IN (SELECT `id_product_pack` FROM `'._DB_PREFIX_.'pm_advancedpack_products`)');
				Db::getInstance()->delete('pm_advancedpack_cart_products', '`id_order` IS NULL AND `id_product_pack` NOT IN (SELECT `id_product_pack` FROM `'._DB_PREFIX_.'pm_advancedpack_products`)');
				AdvancedPack::clearAP5Cache();
				foreach ($packList as $idPack) {
					$pack = new AdvancedPack($idPack);
					if (Validate::isLoadedObject($pack)) {
						Db::getInstance()->delete('pm_advancedpack_cart_products', '`id_order` IS NULL AND `id_pack`='.(int)$idPack);
						SpecificPrice::deleteByProductId((int)$idPack);
						$pack->deleteCartProducts();
						$pack->deleteFromCartRules();
						$pack->deleteProductAttributes();
						$pack->active = false;
						$pack->update();
						if (!$clearCache)
							$clearCache = true;
					}
				}
			}
			if ($clearCache)
				AdvancedPack::clearAP5Cache();
		}
	}
	public function hookActionBeforeCartUpdateQty($params) {
	}
	public function hookActionCartSave($params) {
		if (self::$_preventInfiniteLoop)
			return;
		if (AdvancedPack::getPackAttributeGroupId() !== false) {
			$sql = new DbQuery();
			$sql->select('a.`id_attribute`, pac.`id_product_attribute`');
			$sql->from('product_attribute_combination', 'pac');
			$sql->innerJoin('attribute', 'a', 'a.`id_attribute` = pac.`id_attribute` AND a.`id_attribute_group` = ' . (int)AdvancedPack::getPackAttributeGroupId());
			$sql->leftJoin('cart_product', 'cp', 'cp.`id_product_attribute` = pac.`id_product_attribute`');
			$sql->where('cp.`id_product_attribute` IS NULL');
			$idAttributeList = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
			if (self::_isFilledArray($idAttributeList)) {
				foreach ($idAttributeList as $attributeRow) {
					$attribute = new Attribute((int)$attributeRow['id_attribute'], $this->context->language->id);
					if (Validate::isLoadedObject($attribute) && !preg_match('/[0-9]+-defaultCombination/', $attribute->name))
						$attribute->delete();
					$combination = new Combination((int)$attributeRow['id_product_attribute']);
					if (Validate::isLoadedObject($combination) && (!Validate::isLoadedObject($attribute) || (Validate::isLoadedObject($attribute) && !preg_match('/[0-9]+-defaultCombination/', $attribute->name))))
						$combination->delete();
				}
			}
		}
		$idProduct = (int)Tools::getValue('id_product');
		$idProductAttribute = (int)Tools::getValue('id_product_attribute');
		if (!$idProductAttribute && (int)Tools::getValue('ipa'))
			$idProductAttribute = (int)Tools::getValue('ipa');
		$idAddressDelivery = (int)Tools::getValue('id_address_delivery');
		$newCartQuantityUp = abs(Tools::getValue('qty', 1));
		if (!isset($this->context->cookie->id_cart) || !$this->context->cookie->id_cart)
			$this->context->cookie->id_cart = (int)$this->context->cart->id;
		if (Validate::isLoadedObject($params['cart']) && Tools::getIsset('ajax') && Tools::getIsset('add') && Tools::getValue('add') && Tools::getIsset('id_product') && isset($this->context->controller) && is_object($this->context->controller) && get_class($this->context->controller) == 'CartController' && $this->context->controller->isTokenValid() && $this->context->controller->ajax) {
			if (!Tools::getIsset('summary')) {
				if (in_array($idProduct, AdvancedPack::getExclusiveProducts())) {
					self::$_preventInfiniteLoop = true;
					$params['cart']->deleteProduct($idProduct, $idProductAttribute);
					self::$_preventInfiniteLoop = false;
					die(Tools::jsonEncode(array('hasError' => true, 'errors' => array(Tools::displayError($this->l('This product can only be ordered via a pack'), false)))));
				} else {
					if (AdvancedPack::isValidPack($idProduct)) {
						self::$_preventInfiniteLoop = true;
						if (!$idProductAttribute) {
							$idProductAttribute = (int)Product::getDefaultAttribute($idProduct);
							$params['cart']->deleteProduct($idProduct, $idProductAttribute);
							if (AdvancedPack::isInStock($idProduct, $newCartQuantityUp, array(), true, $idProductAttribute))
								AdvancedPack::addPackToCart($idProduct, $newCartQuantityUp, array(), true);
							else
								die(Tools::jsonEncode(array('hasError' => true, 'errors' => array(Tools::displayError($this->getFrontTranslation('errorMaximumQuantity'), false)))));
						} else {
							if (!AdvancedPack::isInStock($idProduct, $newCartQuantityUp, array(), true, $idProductAttribute)) {
								$params['cart']->updateQty($newCartQuantityUp, $idProduct, $idProductAttribute, 0, 'down', $idAddressDelivery);
								die(Tools::jsonEncode(array('hasError' => true, 'errors' => array(Tools::displayError($this->getFrontTranslation('errorMaximumQuantity'), false)))));
							}
						}
						self::$_preventInfiniteLoop = false;
					} else {
						if (!Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$idProduct))) {
							if (!$idProductAttribute)
								$idProductAttribute = (int)Product::getDefaultAttribute($idProduct);
							$currentPackCartStock = AdvancedPack::getPackProductsCartQuantity();
							$stockAvailable = (int)StockAvailable::getQuantityAvailableByProduct((int)$idProduct, (int)$idProductAttribute);
							if (isset($currentPackCartStock[(int)$idProduct][(int)$idProductAttribute])) {
								$stockAvailable -= $currentPackCartStock[(int)$idProduct][(int)$idProductAttribute];
								$stockAvailable -= AdvancedPack::getCartQuantity((int)$idProduct, (int)$idProductAttribute);
								if ($stockAvailable < 0) {
									self::$_preventInfiniteLoop = true;
									$params['cart']->updateQty($newCartQuantityUp, $idProduct, $idProductAttribute, 0, 'down', $idAddressDelivery);
									self::$_preventInfiniteLoop = false;
									die(Tools::jsonEncode(array('hasError' => true, 'errors' => array(Tools::displayError($this->getFrontTranslation('errorMaximumQuantity'), false)))));
								}
							}
						}
					}
				}
			} else if (Tools::getIsset('summary') && Tools::getValue('op', 'up') == 'up' && (int)Tools::getValue('ipa')) {
				if (AdvancedPack::isValidPack($idProduct)) {
					if ($newCartQuantityUp > 0 && !AdvancedPack::isInStock($idProduct, $newCartQuantityUp, array(), true, $idProductAttribute)) {
						self::$_preventInfiniteLoop = true;
						$params['cart']->updateQty($newCartQuantityUp, $idProduct, $idProductAttribute, 0, 'down', $idAddressDelivery);
						self::$_preventInfiniteLoop = false;
						die(Tools::jsonEncode(array('hasError' => true, 'errors' => array(Tools::displayError($this->getFrontTranslation('errorMaximumQuantity'), false)))));
					}
				} else {
					if (!Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$idProduct))) {
						$currentPackCartStock = AdvancedPack::getPackProductsCartQuantity();
						$stockAvailable = (int)StockAvailable::getQuantityAvailableByProduct((int)$idProduct, (int)$idProductAttribute);
						if (isset($currentPackCartStock[(int)$idProduct][(int)$idProductAttribute])) {
							$stockAvailable -= $currentPackCartStock[(int)$idProduct][(int)$idProductAttribute];
							$stockAvailable -= AdvancedPack::getCartQuantity((int)$idProduct, (int)$idProductAttribute);
							if ($stockAvailable < 0) {
								self::$_preventInfiniteLoop = true;
								$params['cart']->updateQty($newCartQuantityUp, $idProduct, $idProductAttribute, 0, 'down', $idAddressDelivery);
								self::$_preventInfiniteLoop = false;
								die(Tools::jsonEncode(array('hasError' => true, 'errors' => array(Tools::displayError($this->getFrontTranslation('errorMaximumQuantity'), false)))));
							}
						}
					}
				}
			}
		} elseif (Validate::isLoadedObject($params['cart']) && !Tools::getIsset('summary') && Tools::getIsset('add') && Tools::getValue('add') && Tools::getIsset('id_product') && isset($this->context->controller) && is_object($this->context->controller) && $this->context->controller->isTokenValid() && !$this->context->controller->ajax) {
			if (in_array($idProduct, AdvancedPack::getExclusiveProducts())) {
				self::$_preventInfiniteLoop = true;
				$params['cart']->deleteProduct($idProduct, $idProductAttribute);
				self::$_preventInfiniteLoop = false;
				$this->context->controller->errors[] = $this->l('This product can only be ordered via a pack');
			} else {
				if (!Tools::getValue('ipa'))
					$idProductAttribute = (int)Product::getDefaultAttribute($idProduct);
				if (AdvancedPack::isValidPack($idProduct)) {
					self::$_preventInfiniteLoop = true;
					if (!Tools::getValue('ipa')) {
						$params['cart']->deleteProduct($idProduct, $idProductAttribute);
						if (AdvancedPack::isInStock($idProduct, $newCartQuantityUp, array(), true, $idProductAttribute))
							AdvancedPack::addPackToCart($idProduct, $newCartQuantityUp, array(), false);
						else
							$this->context->controller->errors[] = $this->getFrontTranslation('errorMaximumQuantity');
					} else {
						if (!AdvancedPack::isInStock($idProduct, $newCartQuantityUp, array(), true, $idProductAttribute)) {
							$params['cart']->updateQty($newCartQuantityUp, $idProduct, (int)$idProductAttribute, 0, 'down', $idAddressDelivery);
							$this->context->controller->errors[] = $this->getFrontTranslation('errorMaximumQuantity');
						}
					}
					self::$_preventInfiniteLoop = false;
				} else {
					if (!Product::isAvailableWhenOutOfStock(StockAvailable::outOfStock((int)$idProduct))) {
						$currentPackCartStock = AdvancedPack::getPackProductsCartQuantity();
						$stockAvailable = (int)StockAvailable::getQuantityAvailableByProduct((int)$idProduct, (int)$idProductAttribute);
						if (isset($currentPackCartStock[(int)$idProduct][(int)$idProductAttribute])) {
							$stockAvailable -= $currentPackCartStock[(int)$idProduct][(int)$idProductAttribute];
							$stockAvailable -= AdvancedPack::getCartQuantity((int)$idProduct, (int)$idProductAttribute);
							if ($stockAvailable < 0) {
								self::$_preventInfiniteLoop = true;
								$params['cart']->updateQty($newCartQuantityUp, $idProduct, $idProductAttribute, 0, 'down', $idAddressDelivery);
								$this->context->controller->errors[] = $this->getFrontTranslation('errorMaximumQuantity');
								self::$_preventInfiniteLoop = false;
								return;
							}
						}
					}
				}
			}
		} else {
			if (Tools::isSubmit('submitReorder') && $id_order = (int)Tools::getValue('id_order')) {
				$this->_duplicateCartWithPacks($id_order);
			}
		}
		if (Validate::isLoadedObject($params['cart']))
			foreach (AdvancedPack::getIdPacksByIdProduct((int)$idProduct) as $idPack) {
				$sql = new DbQuery();
				$sql->select('GROUP_CONCAT(DISTINCT `id_product_attribute_pack`)');
				$sql->from('pm_advancedpack_cart_products', 'acp');
				$sql->where('acp.`id_cart`='.(int)$params['cart']->id);
				$sql->where('acp.`id_pack`='.(int)$idPack);
				$sql->where('acp.`id_order` IS NULL');
				$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
				if ($result !== false && !empty($result)) {
					$result = array_map('intval', explode(',', $result));
					if (self::_isFilledArray($result))
						foreach ($result as $idProductAttribute)
							if ((int)$idProductAttribute > 0)
								AdvancedPack::setStockAvailableQuantity((int)$idPack, (int)$idProductAttribute, AdvancedPack::getPackAvailableQuantity((int)$idPack, AdvancedPack::getIdProductAttributeListByIdPack((int)$idPack, (int)$idProductAttribute), array(), $idProductAttribute));
				}
			}
	}
	private function _duplicateCartWithPacks($id_order) {
		$oldCart = new Cart(Order::getCartIdStatic($id_order, $this->context->customer->id));
		if (Validate::isLoadedObject($oldCart)) {
			self::$_preventInfiniteLoop = true;
			$id_address_delivery = Configuration::get('PS_ALLOW_MULTISHIPPING') ? $this->context->cart->id_address_delivery : 0;
			if (!$this->context->cart->id) {
				if (Context::getContext()->cookie->id_guest) {
					$guest = new Guest(Context::getContext()->cookie->id_guest);
					$this->context->cart->mobile_theme = $guest->mobile_theme;
				}
				$this->context->cart->add();
				if ($this->context->cart->id)
					$this->context->cookie->id_cart = (int)$this->context->cart->id;
			}
			$productList = $oldCart->getProducts();
			foreach ($productList as $product) {
				if (AdvancedPack::isValidPack($product['id_product'], true)) {
					AdvancedPack::addPackToCart($product['id_product'], 1, AdvancedPack::getIdProductAttributeListByIdPack($product['id_product'], $product['id_product_attribute']), false);
				} else {
					$this->context->cart->updateQty(
						(int)$product['quantity'],
						(int)$product['id_product'],
						(int)$product['id_product_attribute'],
						null,
						'up',
						(int)$id_address_delivery,
						new Shop((int)$this->context->cart->id_shop),
						false
					);
				}
			}
			self::$_preventInfiniteLoop = false;
		}
		if (Configuration::get('PS_ORDER_PROCESS_TYPE') == 1)
			Tools::redirect('index.php?controller=order-opc');
		Tools::redirect('index.php?controller=order');
	}
	public function hookActionObjectOrderAddAfter($params) {
		self::$_validateOrderProcess = true;
		$order = $params['object'];
		if (is_object($order) && self::_isFilledArray($order->product_list)) {
			foreach ($order->product_list as $key => $product) {
				if ($product['id_product_attribute'] && AdvancedPack::isValidPack($product['id_product'])) {
					$order->product_list[$key]['attributes'] = $this->displayPackContent($product['id_product'], $product['id_product_attribute'], self::PACK_CONTENT_ORDER_CONFIRMATION_EMAIL);
				}
			}
		}
	}
	public function hookActionObjectCombinationDeleteAfter($params) {
		$combination = $params['object'];
		Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'pm_advancedpack_cart_products` WHERE `id_product_attribute_pack`='.(int)$combination->id);
	}
	private function _postProcessAdminProducts($idPack, $isNewPack = false, $isMajorUpdate = false) {
		$pack = new AdvancedPack($idPack);
		if (Validate::isLoadedObject($pack)) {
			if (Tools::getIsset('ap5_productList') && self::_isFilledArray(Tools::getValue('ap5_productList'))) {
				$packInformations = array();
				$packPositions = array();
				$packSettings = array('fixedPrice' => null, 'allowRemoveProduct' => false);
				$hasAlwaysUseReducEntries = true;
				if (Tools::getIsset('ap5_pack_positions') && Tools::getValue('ap5_pack_positions'))
					$packPositions = explode(',', Tools::getValue('ap5_pack_positions'));
				if (Tools::getIsset('ap5_price_rules') && Tools::getValue('ap5_price_rules') == 3 && Tools::getIsset('ap5_fixed_pack_price') && Tools::getValue('ap5_fixed_pack_price') > 0)
					$packSettings['fixedPrice'] = Tools::getValue('ap5_fixed_pack_price');
				foreach (Tools::getValue('ap5_productList') as $idProductPack) {
					$packInformations[$idProductPack] = array(
						'id_product_pack' => (Tools::strlen($idProductPack) != 16 && is_numeric($idProductPack) && $idProductPack ? (int)$idProductPack : null),
						'id_pack' => $idPack,
						'id_product' => Tools::getValue('ap5_originalIdProduct-' . $idProductPack),
						'quantity' => Tools::getValue('ap5_quantity-' . $idProductPack),
						'reduction_amount' => (Tools::getValue('ap5_reductionType-' . $idProductPack) == 'percentage' ? Tools::getValue('ap5_reductionAmount-' . $idProductPack) / 100 : Tools::getValue('ap5_reductionAmount-' . $idProductPack)),
						'reduction_type' => Tools::getValue('ap5_reductionType-' . $idProductPack),
						'exclusive' => (Tools::getIsset('ap5_exclusive-' . $idProductPack) && Tools::getValue('ap5_exclusive-' . $idProductPack) ? (int)Tools::getValue('ap5_exclusive-' . $idProductPack) : 0),
						'use_reduc' => (Tools::getIsset('ap5_useReduc-' . $idProductPack) && Tools::getValue('ap5_useReduc-' . $idProductPack) ? (int)Tools::getValue('ap5_useReduc-' . $idProductPack) : 0),
						'position' => array_search($idProductPack, $packPositions),
						'default_id_product_attribute' => (Tools::getIsset('ap5_customCombinations-' . $idProductPack) && Tools::getValue('ap5_customCombinations-' . $idProductPack) ? Tools::getValue('ap5_defaultCombination-' . $idProductPack) : (int)Product::getDefaultAttribute((int)Tools::getValue('ap5_originalIdProduct-' . $idProductPack))),
						'customCombinations' => (Tools::getIsset('ap5_customCombinations-' . $idProductPack) && Tools::getValue('ap5_customCombinations-' . $idProductPack) ? Tools::getValue('ap5_combinationInclude-' . $idProductPack) : array()),
					);
					$hasAlwaysUseReducEntries &= $packInformations[$idProductPack]['use_reduc'];
				}
				if ($hasAlwaysUseReducEntries && Tools::getIsset('ap5_price_rules') && Tools::getValue('ap5_price_rules') == 4 && Tools::getIsset('ap5_allow_remove_product') && Tools::getValue('ap5_allow_remove_product') == 1 && sizeof($packInformations) >= 3)
					$packSettings['allowRemoveProduct'] = (bool)Tools::getValue('ap5_allow_remove_product');
				if (self::_isFilledArray($packInformations)) {
					if (!$pack->updatePackContent($packInformations, $packSettings, $isNewPack, $isMajorUpdate)) 
						throw new PrestaShopException($this->l('Unable to update pack content'));
				}
			}
		}
	}
	public function hookActionAdminControllerSetMedia($params) {
		$id_product = (int)Tools::getValue('id_product');
		$product = false;
		if (Tools::getIsset('id_product') && (int)$id_product > 0)
			$product = new Product((int)$id_product, true, $this->context->language->id, $this->context->shop->id);
		if (Tools::getIsset('newpack') || Tools::getIsset('is_real_new_pack') || (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id))) {
			$this->context->controller->addCSS($this->_path . 'css/admin-new-pack.css', 'all');
			if (version_compare(_PS_VERSION_, '1.6.0.0', '<')) {
				$this->context->controller->addJS($this->_path . 'js/admin-bootstrap-ps15.min.js');
				$this->context->controller->addCSS($this->_path . 'css/admin-bootstrap-ps15.css', 'all');
			}
			$this->context->controller->addJS($this->_path . 'js/admin-new-pack.js');
		} else if (Validate::isLoadedObject($product) && !AdvancedPack::isValidPack($product->id)) {
			if (version_compare(_PS_VERSION_, '1.6.0.0', '<'))
				$this->context->controller->addCSS($this->_path . 'css/admin-bootstrap-ps15.css', 'all');
			$this->context->controller->addCSS($this->_path . 'css/admin-product-tab.css', 'all');
		}
	}
	public function hookDisplayBackOfficeHeader($params) {
		if (Tools::getIsset('duplicateproduct') && Tools::getIsset('id_product') && Tools::getValue('id_product') && AdvancedPack::isValidPack((int)Tools::getValue('id_product')))
			Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')) . '&ap5Error=1');
		if (Tools::getIsset('ap5Error') && Tools::getValue('ap5Error')) {
			if (Tools::getValue('ap5Error') == 1)
				$this->context->controller->errors[] = $this->displayName . ' - ' . $this->l('You can\'t duplicate a pack.');
		} else if (Tools::getValue('controller') == 'AdminCarts' && Tools::getIsset('viewcart') && (int)Tools::getValue('id_cart')) {
			$cart = new Cart((int)Tools::getValue('id_cart'));
			if (Validate::isLoadedObject($cart))
				return $this->replacePackSmallAttributes(array('cart' => $cart), 'displayBackOfficeHeader');
		} else if (Tools::getValue('controller') == 'AdminProducts' && Tools::getIsset('id_product') && (int)Tools::getValue('id_product')) {
			$id_product = (int)Tools::getValue('id_product');
			$product = false;
			if (Tools::getIsset('id_product') && (int)$id_product > 0)
				$product = new Product((int)$id_product, true, $this->context->language->id, $this->context->shop->id);
			if (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id) && !AdvancedPack::isFromShop($product->id, Context::getContext()->shop->id)) {
				$this->context->controller->errors[] = $this->l('You must select the right shop in order to continue (where the pack has been created).');
				return '<script type="text/javascript">$(document).ready(function() { if (typeof(ap5_disableProductEdit) != "undefined") ap5_disableProductEdit(); });</script>';
			}
		}
		parent::hookDisplayBackOfficeHeader($params);
	}
	public function hookDisplayAdminProductsExtra($params) {
		if (Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP) {
			$this->context->controller->errors[] = $this->l('You must select a specific shop in order to continue.');
			return '<script type="text/javascript">if (typeof(ap5_disableProductEdit) != "undefined") ap5_disableProductEdit();</script>';
		}
		$id_product = (int)Tools::getValue('id_product');
		$packList = array();
		$packObjects = array();
		$product = false;
		if (Tools::getIsset('id_product') && (int)$id_product > 0)
			$product = new Product((int)$id_product, true, $this->context->language->id, $this->context->shop->id);
		if (Validate::isLoadedObject($product) && !AdvancedPack::isValidPack($product->id)) {
			$packListId = AdvancedPack::getIdPacksByIdProduct((int)$product->id);
			if (self::_isFilledArray($packListId)) {
				foreach ($packListId as $idPack) {
					$packContent = AdvancedPack::getPackContent($idPack, null, true);
					$packList[$idPack] = $packContent;
					$packObjects[$idPack] = new AdvancedPack($idPack, false, $this->context->language->id);
				}
			}
			if (Validate::isLoadedObject($product)) {
				$this->context->smarty->assign(array('currentProduct' => $product, 'packList' => $packList, 'packObjects' => $packObjects));
				return $this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/admin-product-tab-packs-list.tpl');
			}
		} else if ((Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id)) || (Tools::getValue('id_product') == 0 && Tools::getIsset('updateproduct') && Tools::getIsset('is_real_new_pack'))) {
			if (Validate::isLoadedObject($product) && !AdvancedPack::isFromShop($product->id, Context::getContext()->shop->id)) {
				$this->context->controller->errors[] = $this->l('You must select the right shop in order to continue (where the pack has been created).');
				return '<script type="text/javascript">if (typeof(ap5_disableProductEdit) != "undefined") ap5_disableProductEdit();</script>';
			}
			$packPriceRules = 4;
			$packFixedPrice = 0;
			$hasAlwaysUseReducEntries = true;
			$reductionAmountTable = $reductionPercentageTable = $packContent = array();
			if (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id)) {
				$packContent = AdvancedPack::getPackContent($product->id, null, true);
				$packFixedPrice = AdvancedPack::getPackFixedPrice($product->id);
				$warehouseFinalListId = array();
				foreach ($packContent as $idProductPack => $packProduct) {
					if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && Product::usesAdvancedStockManagement($packProduct['productObj']->id)) {
						$warehouseList = Warehouse::getProductWarehouseList($packProduct['productObj']->id, ($packProduct['productObj']->hasAttributes() ? Product::getDefaultAttribute($packProduct['productObj']->id) : 0));
						if (self::_isFilledArray($warehouseList)) {
							$warehouseListId = array();
							foreach ($warehouseList as $warehouseRow)
								$warehouseListId[] = (int)$warehouseRow['id_warehouse'];
							$warehouseListId = array_unique($warehouseListId);
							if (sizeof($warehouseListId) == 1)
								$warehouseFinalListId[] = current($warehouseListId);
						}
					}
					$packContent[$idProductPack]['productCombinations'] = $packProduct['productObj']->getAttributesResume($this->context->language->id);
					$packContent[$idProductPack]['productCombinationsWhiteList'] = AdvancedPack::getProductAttributeWhiteList($packProduct['id_product_pack']);
					if ($packProduct['reduction_type'] == 'amount') {
						$reductionAmountTable[] = $packProduct['reduction_amount'];
					} else if ($packProduct['reduction_type'] == 'percentage') {
						$reductionPercentageTable[] = $packProduct['reduction_amount'];
					}
					$hasAlwaysUseReducEntries &= $packProduct['use_reduc'];
				}
				$reductionPercentageTable = array_unique($reductionPercentageTable);
				$warehouseFinalListId = array_unique($warehouseFinalListId);
				if ($packFixedPrice > 0)
					$packPriceRules = 3;
				else if (count($reductionPercentageTable) == 1 && !count($reductionAmountTable)) {
					if (current($reductionPercentageTable) == 0)
						$packPriceRules = 4;
					else
						$packPriceRules = 1;
				} else if (count($reductionPercentageTable) || count($reductionAmountTable))
					$packPriceRules = 2;
			}
			$this->context->smarty->assign(array(
				'idTaxRulesGroup' => (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id) ? (int)$product->getIdTaxRulesGroup() : null),
				'idWarehouse' => (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id) ? (int)current($warehouseFinalListId) : null),
				'packContent' => $packContent,
				'defaultCurrency' => Currency::getDefaultCurrency(),
				'packPriceRules' => $packPriceRules,
				'packFixedPrice' => $packFixedPrice,
				'packFixedPercentage' => ($packPriceRules == 1 && count($reductionPercentageTable) == 1 ? current($reductionPercentageTable) * 100 : 0),
				'packAllowRemoveProduct' => (Validate::isLoadedObject($product) && AdvancedPack::isValidPack($product->id) && AdvancedPack::getPackAllowRemoveProduct($product->id) && $packPriceRules == 4 && $hasAlwaysUseReducEntries && sizeof($packContent) >= 3),
				'packClassicPrice' => 0,
				'packClassicPriceWt' => 0,
				'discountPercentage' => number_format(0, 2),
				'packPrice' => 0,
				'packPriceWt' => 0,
				'totalPackEcoTax' => 0,
				'totalPackEcoTaxWt' => 0
			));
			return $this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/admin-product-tab-new-pack.tpl');
		} else {
			$this->context->controller->errors[] = $this->l('This product must be saved in order to proceed.');
			return false;
		}
	}
	public function hookDisplayShoppingCartFooter($params) {
		return $this->replacePackSmallAttributes($params, 'displayShoppingCartFooter');
	}
	public function hookDisplayFooter($params) {
		if ((int)Group::getCurrent()->price_display_method) {
			if ((isset($this->context->controller->step) && $this->context->controller->step == 3 || Configuration::get('PS_ORDER_PROCESS_TYPE')) && get_class($this->context->controller) == 'OrderController') {
				$this->context->controller->addJqueryPlugin('typewatch');
				$this->context->controller->addJS(_THEME_JS_DIR_.'cart-summary.js');
			}
			$this->context->controller->addJS($this->_path.'js/shopping-cart.js');
		}
		return $this->replacePackSmallAttributes($params, 'displayFooter');
	}
	public function hookDisplayFooterProduct($params) {
		$config = $this->_getModuleConfiguration();
		if (isset($config['enablePackCrossSellingBlock']) && $config['enablePackCrossSellingBlock']) {
			$currentProductObj = $this->getCurrentProduct();
			if (Validate::isLoadedObject($currentProductObj) && self::_isFilledArray(AdvancedPack::getIdPacksByIdProduct($currentProductObj->id))) {
				$packList = array();
				foreach (AdvancedPack::getIdPacksByIdProduct($currentProductObj->id) as $idPack) {
					$productPackObj = new Product($idPack, true, $this->context->language->id);
					if (Validate::isLoadedObject($productPackObj) && $productPackObj->active && AdvancedPack::isValidPack($idPack)) {
						$packList[$idPack] = array(
							'idPack' => $idPack,
							'packContent' => AdvancedPack::getPackContent($idPack, null, true),
							'packObj' => $productPackObj
						);
					}
				}
				if (self::_isFilledArray($packList)) {
					$this->_assignSmartyImageTypeVars();
					if (isset($config['orderByCrossSelling']) && $config['orderByCrossSelling']) {
						ksort($packList);
						if ($config['orderByCrossSelling'] == 'date_add_desc') {
							krsort($packList);
						} else if ($config['orderByCrossSelling'] == 'price_asc' || $config['orderByCrossSelling'] == 'price_desc') {
							foreach ($packList as $idPack => &$packRow)
								$packRow['packPrice'] = AdvancedPack::getPackPrice($idPack, false);
							self::$_sortArrayByKeyColumn = 'packPrice';
							self::$_sortArrayByKeyOrder = ($config['orderByCrossSelling'] == 'price_asc' ? 1 : 2);
							uasort($packList, 'self::sortArrayByKey');
						} else if ($config['orderByCrossSelling'] == 'random') {
							shuffle($packList);
						}
					}
					if (isset($config['limitPackNbCrossSelling']) && (int)$config['limitPackNbCrossSelling'] > 0)
						$packList = array_slice($packList, 0, $config['limitPackNbCrossSelling']);
					$this->context->smarty->assign(array(
						'bootstrapTheme' => (bool)$config['bootstrapTheme'],
						'enableViewThisPackButton' => (bool)$config['enableViewThisPackButton'],
						'enableBuyThisPackButton' => (bool)$config['enableBuyThisPackButton'],
					));
					$this->context->controller->addCSS($this->_path.'css/owl.carousel.min.css', 'all');
					$this->context->controller->addCSS($this->_path.'css/animate.min.css', 'all');
					$this->context->controller->addCSS($this->_path.'css/product-footer.css', 'all');
					if (version_compare(_PS_VERSION_, '1.6.0.0', '<'))
						$this->context->controller->addCSS($this->_path.'css/product-footer-ps15.css', 'all');
					$this->context->controller->addCSS($this->_path.str_replace('{id_shop}', $this->context->shop->id, self::DYN_CSS_FILE), 'all');
					$this->context->controller->addJS($this->_path.'js/owl.carousel.min.js');
					$this->context->controller->addJS($this->_path.'js/product-footer.js');
					$this->context->smarty->assign(array('packList' => $packList));
					return $this->display(__FILE__, 'views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/product-footer-pack-list.tpl');
				}
			}
		}
	}
	public function hookDisplayLeftColumn($params) {
		return $this->hookDisplayRightColumn($params);
	}
	public function hookDisplayRightColumn($params) {
		$config = $this->_getModuleConfiguration();
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>=') || !isset($this->context->controller->php_self) || $this->context->controller->php_self != 'product' || (bool)$config['bootstrapTheme'])
			return;
		$packObj = $this->getCurrentProduct();
		if (Validate::isLoadedObject($packObj) && AdvancedPack::isValidPack($packObj->id) && $packObj->active) {
			$this->_assignSmartyVars('pack', $packObj->id);
			$group_reduction = GroupReduction::getValueForProduct($packObj->id, (int)Group::getCurrent()->id);
			if ($group_reduction === false)
				$group_reduction = Group::getReduction((int)$this->context->cookie->id_customer) / 100;
			$tax = (float)$packObj->getTaxesRate(new Address((int)$this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
			$product_price_with_tax = Product::getPriceStatic($packObj->id, true, null, 6);
			if (Product::$_taxCalculationMethod == PS_TAX_INC)
				$product_price_with_tax = Tools::ps_round($product_price_with_tax, 2);
			$product_price_without_eco_tax = (float)$product_price_with_tax - $packObj->ecotax;
			$ecotax_rate = (float)Tax::getProductEcotaxRate($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
			$ecotax_tax_amount = Tools::ps_round($packObj->ecotax, 2);
			if (Product::$_taxCalculationMethod == PS_TAX_INC && (int)Configuration::get('PS_TAX'))
				$ecotax_tax_amount = Tools::ps_round($ecotax_tax_amount * (1 + $ecotax_rate / 100), 2);
			$address = new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
			$this->context->smarty->assign(array(
				'allow_oosp' => $packObj->isAvailableWhenOutOfStock((int)$packObj->out_of_stock),
				'ecotax_tax_inc' => $ecotax_tax_amount,
				'ecotax_tax_exc' => Tools::ps_round($packObj->ecotax, 2),
				'ecotaxTax_rate' => $ecotax_rate,
				'productPriceWithoutEcoTax' => (float)$product_price_without_eco_tax,
				'group_reduction' => (1 - $group_reduction),
				'no_tax' => Tax::excludeTaxeOption() || !$packObj->getTaxesRate($address),
				'ecotax' => (!count($this->context->controller->errors) && $packObj->ecotax > 0 ? Tools::convertPrice((float)$packObj->ecotax) : 0),
				'tax_rate' => $tax,
				'tax_enabled' => Configuration::get('PS_TAX')
			));
			return $this->display(__FILE__, 'views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-buy-block-column.tpl');
		}
	}
	protected function replacePackSmallAttributes($params, $fromHookName = false) {
		if ($fromHookName == 'displayFooter' && isset($this->context->controller->step) && $this->context->controller->step == 0 && get_class($this->context->controller) == 'OrderController')
			return;
		if (isset($params['cart']) && Validate::isLoadedObject($params['cart'])) {
			$products = $params['cart']->getProducts();
			if (self::_isFilledArray($products)) {
				$cartPackProducts = array();
				foreach ($products as $cartProduct)
					if ($cartProduct['id_product_attribute'] && AdvancedPack::isValidPack($cartProduct['id_product']))
						$cartPackProducts[$cartProduct['attributes_small']] = array(
							'cart' => Tools::jsonEncode($this->displayPackContent($cartProduct['id_product'], $cartProduct['id_product_attribute'], self::PACK_CONTENT_SHOPPING_CART)),
							'block_cart' => Tools::jsonEncode($this->displayPackContent($cartProduct['id_product'], $cartProduct['id_product_attribute'], self::PACK_CONTENT_BLOCK_CART))
						);
				if (count($cartPackProducts)) {
					$this->context->smarty->assign(array('cartPackProducts' => $cartPackProducts));
					if ($fromHookName == 'displayBackOfficeHeader')
						return $this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/backoffice-footer.tpl');
					else
						return $this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/footer.tpl');
				}
			}
		}
	}
	public function displayPackContent($idPack, $idProductAttribute, $contextType) {
		if ($idProductAttribute && AdvancedPack::isValidPack($idPack)) {
			$packProducts = AdvancedPack::getPackContent($idPack, $idProductAttribute);
			if (self::_isFilledArray($packProducts)) {
				foreach ($packProducts as $key => $packProduct) {
					$product = new Product((int)$packProduct['id_product'], false, $this->context->language->id);
					$packProducts[$key]['product_name'] = $product->name;
					$packProducts[$key]['quantity'] = (int)$packProducts[$key]['quantity'];
					$attributeDatas = AdvancedPack::getProductAttributeList((isset($packProduct['id_product_attribute']) ? (int)$packProduct['id_product_attribute'] : (int)$packProduct['default_id_product_attribute']));
					$packProducts[$key] = array_merge($packProducts[$key], $attributeDatas);
				}
				$this->context->smarty->assign(array('packProducts' => $packProducts));
				if ($contextType == self::PACK_CONTENT_SHOPPING_CART) {
					return $this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/pack-product-list-cart-summary.tpl');
				} else if ($contextType == self::PACK_CONTENT_BLOCK_CART) {
					return $this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/pack-product-list-block-cart.tpl');
				} else if ($contextType == self::PACK_CONTENT_ORDER_CONFIRMATION_EMAIL) {
					return html_entity_decode(trim(strip_tags($this->display(__FILE__, Tools::substr(_PS_VERSION_, 0, 3) . '/pack-product-list-order-confirmation-email.tpl'))), ENT_QUOTES, 'UTF-8');
				}
			}
		}
	}
	public function displayPackContentTable($idPack, $packAttributesList, $packCompleteAttributesList, $packExcludeList = array(), $packErrorsList = array(), $packFatalErrorsList = array()) {
		$productPack = new Product((int)$idPack, true, $this->context->language->id);
		$productPack->quantity = AdvancedPack::getPackAvailableQuantity($idPack, $packAttributesList, $packExcludeList);
		$productsPack = AdvancedPack::getPackContent($idPack, null, true, $packAttributesList);
		$config = $this->_getModuleConfiguration();
		$this->context->smarty->assign(array(
			'bootstrapTheme' => (bool)$config['bootstrapTheme'],
			'autoScrollBuyBlock' => (bool)$config['autoScrollBuyBlock'],
			'productsPack' => $productsPack,
			'packShowProductsAvailability' => (isset($config['showProductsAvailability']) ? $config['showProductsAvailability'] : $this->_defaultConfiguration['showProductsAvailability']),
			'packAvailableQuantity' => AdvancedPack::getPackAvailableQuantity($idPack, $packAttributesList, $packExcludeList),
			'packMaxImagesPerProduct' => AdvancedPack::getMaxImagesPerProduct($productsPack),
			'productsPackErrors' => $packErrorsList,
			'productsPackFatalErrors' => $packFatalErrorsList,
			'packAttributesList' => $packAttributesList,
			'packCompleteAttributesList' => $packCompleteAttributesList,
			'packAllowRemoveProduct' => AdvancedPack::getPackAllowRemoveProduct($idPack),
			'packExcludeList' => $packExcludeList,
			'product' => $productPack,
			'col_img_dir' => _PS_COL_IMG_DIR_,
			'display_qties' => (int)Configuration::get('PS_DISPLAY_QTIES'),
			'allow_oosp' => $productPack->isAvailableWhenOutOfStock((int)$productPack->out_of_stock),
			'tax_enabled' => Configuration::get('PS_TAX'),
			'content_only' => false,
		));
		$this->_assignSmartyImageTypeVars();
		if (version_compare(_PS_VERSION_, '1.6.0.0', '<')) {
			$this->context->smarty->assign(array(
				'static_token' => Tools::getToken(false),
				'priceDisplayPrecision' => _PS_PRICE_DISPLAY_PRECISION_,
			));
		}
		return $this->display(__FILE__, 'views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-product-list.tpl');
	}
	public function displayPackPriceContainer($idPack, $packAttributesList, $packExcludeList = array(), $packErrorsList = array(), $packFatalErrorsList = array()) {
		$productPack = new Product((int)$idPack, true, $this->context->language->id);
		$productPack->quantity = AdvancedPack::getPackAvailableQuantity($idPack, $packAttributesList, $packExcludeList);
		$ecotax_rate = (float)Tax::getProductEcotaxRate($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
		$ecotax_tax_amount = Tools::ps_round($productPack->ecotax, 2);
		if (Product::$_taxCalculationMethod == PS_TAX_INC && (int)Configuration::get('PS_TAX'))
			$ecotax_tax_amount = Tools::ps_round($ecotax_tax_amount * (1 + $ecotax_rate / 100), 2);
		$id_group = (int)Group::getCurrent()->id;
		$group_reduction = GroupReduction::getValueForProduct($productPack->id, $id_group);
		if ($group_reduction === false)
			$group_reduction = Group::getReduction((int)$this->context->cookie->id_customer) / 100;
		$this->context->smarty->assign(array(
			'productsPackErrors' => $packErrorsList,
			'productsPackFatalErrors' => $packFatalErrorsList,
			'packAvailableQuantity' => AdvancedPack::getPackAvailableQuantity($idPack, $packAttributesList, $packExcludeList),
			'product' => $productPack,
			'packAttributesList' => $packAttributesList,
			'packAllowRemoveProduct' => AdvancedPack::getPackAllowRemoveProduct($idPack),
			'packExcludeList' => $packExcludeList,
			'priceDisplayPrecision' => _PS_PRICE_DISPLAY_PRECISION_,
			'tax_enabled' => Configuration::get('PS_TAX'),
			'ecotax_tax_inc' => $ecotax_tax_amount,
			'ecotax_tax_exc' => Tools::ps_round($productPack->ecotax, 2),
			'group_reduction' => $group_reduction,
			'content_only' => false,
		));
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
			return $this->display(__FILE__, 'views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-price-container.tpl');
		} else {
			$config = $this->_getModuleConfiguration();
			$this->context->smarty->assign(array(
				'static_token' => Tools::getToken(false)
			));
			if ((bool)$config['bootstrapTheme'])
				return $this->display(__FILE__, 'views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-price-container.tpl');
			else
				return $this->display(__FILE__, 'views/templates/front/' . Tools::substr(_PS_VERSION_, 0, 3) . '/pack-buy-block-column.tpl');
		}
	}
	private function _updatePackFields($idPack, $isNewPack = false) {
		self::$_preventInfiniteLoop = true;
		AdvancedPack::clearAP5Cache();
		$productPack = new Product((int)$idPack, false, null, AdvancedPack::getPackIdShop($idPack));
		if (AdvancedPack::getPackIdTaxRulesGroup($idPack))
			$productPack->price = AdvancedPack::getPackPrice($idPack, false, false, false);
		else
			$productPack->price = AdvancedPack::getPackPrice($idPack, true, false, false);
		if ($productPack->price === false && isset(Context::getContext()->controller) && get_class(Context::getContext()->controller) == 'AdminProductsController') {
			throw new PrestaShopException($this->l('Unable to get the pack price, please check if all the products are available'));
		}
		$productPack->id_tax_rules_group = AdvancedPack::getPackIdTaxRulesGroup($idPack);
		$productPack->weight = AdvancedPack::getPackWeight($idPack);
		$productPack->is_virtual = (int)AdvancedPack::isVirtualPack($idPack);
		$productPack->ecotax = AdvancedPack::getPackEcoTax($idPack);
		$productPack->out_of_stock = 0;
		StockAvailable::setProductOutOfStock($idPack, 0);
		if (((Configuration::hasKey('PS_FORCE_ASM_NEW_PRODUCT') && Configuration::get('PS_FORCE_ASM_NEW_PRODUCT')) || !Configuration::hasKey('PS_FORCE_ASM_NEW_PRODUCT')) && Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT'))
			$productPack->advanced_stock_management = true;
		$productPack->depends_on_stock = 0;
		StockAvailable::setProductDependsOnStock($idPack, 0);
		if ($isNewPack && !AdvancedPack::clonePackImages($productPack->id)) {
			throw new PrestaShopException($this->l('Unable to clone pack images'));
		}
		if ($productPack->save()) {
			if (!AdvancedPack::clonePackAttributes($productPack->id)) {
				throw new PrestaShopException($this->l('Unable to generate pack attribute combinations'));
			}
			self::$_preventInfiniteLoop = false;
			return AdvancedPack::addPackSpecificPrice($idPack, 0);
		} else {
			throw new PrestaShopException($this->l('Unable to save the pack'));
		}
		self::$_preventInfiniteLoop = false;
		return false;
	}
	public static function getPackAddCartURL($idPack) {
		return Context::getContext()->link->getModuleLink('pm_advancedpack', 'add_pack', array('id_pack' => (int)$idPack, 'rand' => time()));
	}
	public static function getPackUpdateURL($idPack) {
		return Context::getContext()->link->getModuleLink('pm_advancedpack', 'update_pack', array('id_pack' => (int)$idPack, 'id_product' => (int)$idPack, 'rand' => time()));
	}
	public function getFrontTranslation($idTranslation) {
		$translationTab = array(
			'errorWrongCombination' => $this->l('This combination does not exist for this product. Please select another combination.'),
			'errorMaximumQuantity' => $this->l('You already have the maximum quantity available for this product.'),
			'errorSavePackContent' => $this->l('Unable to save pack content, Please contact the webmaster.'),
			'errorGeneratingPrice' => $this->l('Error when generating price for pack. Please contact the webmaster.'),
			'errorOutOfStock' => $this->l('This pack is out of stock.'),
			'errorInvalidPack' => $this->l('This pack is not valid or is no longer available.'),
			'errorInvalidPackChoice' => $this->l('Choice on the pack aren\'t valid.'),
			'errorProductOrCombinationIsOutOfStock' => $this->l('This product or combination is out of stock.'),
			'errorProductIsOutOfStock' => $this->l('This product is out of stock.'),
			'errorProductIsDisabled' => $this->l('This product is not available at this time.'),
			'errorProductAccessDenied' => $this->l('You do not have access to this product.'),
			'errorProductIsNotAvailableForOrder' => $this->l('This product is not available for order.'),
			'errorInvalidExclude' => $this->l('You must keep at least two products.'),
		);
		if (isset($translationTab[$idTranslation]))
			return $translationTab[$idTranslation];
		return false;
	}
	public function getPMNativeCacheId() {
		if (method_exists($this, 'getCacheId'))
			return $this->getCacheId();
		else
			return $this->name.'|'.(int)$this->context->shop->id.'_'.(int)Group::getCurrent()->id.'_'.(int)$this->context->language->id;
	}
	protected function _getCrossSellingOrderByOptions() {
		return array(
			'date_add_asc' => $this->l('Creation date (ascending, older first)'),
			'date_add_desc' => $this->l('Creation date (descending, new first)'),
			'price_asc' => $this->l('Price (ascending)'),
			'price_desc' => $this->l('Price (descending)'),
			'random' => $this->l('Random'),
		);
	}
}
