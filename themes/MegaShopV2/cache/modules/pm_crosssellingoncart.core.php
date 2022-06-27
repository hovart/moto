<?php
/**
 * PM_CrossSellingOnCart Merchandizing Feature
 *
 * @category merchandizing_features
 * @author Presta-Module.com <support@presta-module.com>
 * @copyright Presta-Module 2014
 * @version 2.2.2
 *
 **************************************
 **        CrossSellingOnCart         *
 **   http://www.presta-module.com    *
 **               V 2.2.2             *
 **************************************
 * +
 * +Languages: EN, FR
 * +PS version: 1.6, 1.5, 1.4, 1.3
 *
 * */

class PM_CrossSellingOnCartModule extends Module {
	public static $_module_prefix = 'CSOC';
	protected $_html;
	protected $errors = array ();
	public $base_config_url;
	protected $defaultLanguage;
	protected $languages;
	protected $_iso_lang;
	protected $_cookie;
	protected $_smarty;
	public $prefixFieldsOptions;
	protected $_copyright_link = array(
		'link'	=> 'http://www.presta-module.com/',
		'img'	=> '//www.presta-module.com/img/logo-module.jpg'
	);
	protected $_support_link = false;
	protected $_getting_started = false;
	
	function __construct($prefixFieldsOptions = 'PM_CSOC') {
		$this->name = 'pm_crosssellingoncart';
		$this->author = 'Presta-Module';
		$this->need_instance = 0;
		if (version_compare(_PS_VERSION_, '1.4.0.0', '<'))
			$this->tab = 'Presta-Module';
		else {
			$this->tab = 'merchandizing';
			$this->module_key = '4cf8891dfa30ed7ae18f4cc37d612b24';
		}
		$this->version = '2.2.2';

		/* The parent construct is required for translations */
		parent::__construct();
		
		$this->initClassVar();
		$this->prefixFieldsOptions = $prefixFieldsOptions;
		$this->_fieldsOptions = array(
			$this->prefixFieldsOptions.'_IDENTICAL_PRODUCTY' => array('title' => $this->l('Display products already in cart ?'), 'desc' => '', 'cast' => 'intval', 'type' => 'bool','default'=> false),
			$this->prefixFieldsOptions.'_NB_PRODUCT' => array('title' => $this->l('Products quantity display'),'desc' => '', 'cast' => 'intval', 'type' => 'text','default'=> 3),
			$this->prefixFieldsOptions.'_IMAGE_SIZE' => array ('title' => $this->l('Product image size'), 'desc' => '', 'type' => 'select', 'list' => array (), 'identifier' => 'name', 'default' => 'medium' ),
			$this->prefixFieldsOptions.'_TITLE_BLOC' => array('title' => $this->l('Title Block'),'desc' => '', 'type' => 'textLang'),
			$this->prefixFieldsOptions.'_ACCESSORIES' => array('title' => $this->l('Display accessories'), 'desc' => '', 'cast' => 'intval', 'type' => 'bool', 'default'=> false),
			$this->prefixFieldsOptions.'_NB_ACCESSORIES' => array('title' => $this->l('Maximum accessories to display'),'desc' => '', 'cast' => 'intval', 'type' => 'text','default'=> 3,'bloc_id'=> 'NB_ACCESSORIES','script'=>'$jqPm("input[name='.$this->prefixFieldsOptions.'_ACCESSORIES'.']").click(function() {if ($jqPm(this).val() == 1)$jqPm("#NB_ACCESSORIES").show();else $jqPm("#NB_ACCESSORIES").hide();});if ($jqPm("input[name='.$this->prefixFieldsOptions.'_ACCESSORIES'.']").val() == 1)$jqPm("#NB_ACCESSORIES").show();else $jqPm("#NB_ACCESSORIES").hide();'),
			$this->prefixFieldsOptions.'_CROSSSELLING' => array('title' => $this->l('Display "Frequently Bought Together" products'), 'desc' => '', 'cast' => 'intval', 'type' => 'bool', 'default'=> true),
			$this->prefixFieldsOptions.'_NB_CROSSSELLING' => array('title' => $this->l('Maximum "Frequently Bought Together" products to display'),'desc' => '', 'cast' => 'intval', 'type' => 'text','default'=> 3,'bloc_id'=> 'NB_CROSSSELLING','script'=>'$jqPm("input[name='.$this->prefixFieldsOptions.'_CROSSSELLING'.']").click(function() {if ($jqPm(this).val() == 1)$jqPm("#NB_CROSSSELLING").show();else $jqPm("#NB_CROSSSELLING").hide();});if ($jqPm("input[name='.$this->prefixFieldsOptions.'_CROSSSELLING'.']").val() == 1)$jqPm("#NB_CROSSSELLING").show();else $jqPm("#NB_CROSSSELLING").hide();'),
		);
		$image = PM_CrossSellingOnCart::getImageType();
		foreach ( $image as $type )
			$this->_fieldsOptions [$this->prefixFieldsOptions.'_IMAGE_SIZE'] ['list'] [] = array ('name' => $type );
		
		if ($this->_onBackOffice()) {
			$this->displayName = $this->l('Cross Selling On Cart');
			$this->description = $this->l('Display a selection of products on the cart summary');
			
			$doc_url_tab['fr'] = 'http://www.presta-module.com/docs/fr/modalcart/configuration_de_cross_selling_on_cart_dans_modal_cart.php';
			$doc_url_tab['en'] = 'http://www.presta-module.com/docs/en/modalcart/cross_selling_configuration_in_modal_cart.php';
			$doc_url = $doc_url_tab['en'];
			if ($this->_iso_lang == 'fr') $doc_url = $doc_url_tab['fr'];

			$forum_url_tab['fr'] = 'http://www.prestashop.com/forums/topic/102385-module-pm-cross-selling-on-cart-est-maintenant-compatible-avec-modalcart/';
			$forum_url_tab['en'] = 'http://www.prestashop.com/forums/topic/102388-module-cross-selling-on-cart/';
			$forum_url = $forum_url_tab['en'];
			if ($this->_iso_lang == 'fr') $forum_url = $forum_url_tab['fr'];

			$this->_support_link = array(
				array('link' => $forum_url, 'target' => '_blank', 'label' => $this->l('Forum topic')),
				array('link' => $doc_url, 'target' => '_blank', 'label' => $this->l('Online documentation')),
				array('link' => 'http://www.presta-module.com/contact-form.php', 'target' => '_blank', 'label' => $this->l('Support contact')),
			);
		}
	}
	
	function install(){
		if (!parent::install())
			return false;
			Configuration::updateValue('PM_'.self::$_module_prefix.'_LAST_VERSION', $this->version);
			Configuration::updateValue($this->prefixFieldsOptions.'_PRODUCT_SELECTION', serialize(array()));

		$this->_installDefaultConfig();
		if (!$this->registerHook('shoppingCart') && !$this->registerHook('header')) return false;
		return true;
	}
	
	protected function _installDefaultConfig() {
		foreach ( $this->_fieldsOptions as $key => $field ) {
			$val = $field ['default'];
			if (trim($val)) {
				if (is_array($val)) {
					$val = serialize($val);
				}
				if (Configuration::get($key) === false) {
					if (! Configuration::updateValue($key, $val))
						return false;
				}
			}
		}
		return true;
	}
	
	function uninstall() {
		$this->_uninstallDefaultConfig();
		Configuration::deleteByName($this->prefixFieldsOptions.'_PRODUCT_SELECTION');
		return parent::uninstall();
	}
	
	protected function _uninstallDefaultConfig() {
		foreach ($this->_fieldsOptions as $key => $field) Configuration::deleteByName($key);
		return true;
	}
	
	protected function _checkIfModuleIsUpdate($updateDb = false, $displayConfirm = true) {
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
			$this->registerHook('displayBackOfficeHeader');
		if (!$this->isRegisteredInHook('displayHeader') && !$this->isRegisteredInHook('header'))
			$this->registerHook('header');
		if (! $updateDb && $this->version != Configuration::get('PM_'.self::$_module_prefix.'_LAST_VERSION'))
			return false;
		if ($updateDb) {
 			unset($_GET['makeUpdate']);
			Configuration::updateValue('PM_'.self::$_module_prefix.'_LAST_VERSION', $this->version);
			if ($displayConfirm) {
				$this->_html .= $this->displayConfirmation($this->l('Module updated successfully'));
			}
		}
		return true;
	}

	public function isRegisteredInHook($hook){
		return Db::getInstance()->getValue('
		SELECT COUNT(*)
		FROM `'._DB_PREFIX_.'hook_module` hm
		LEFT JOIN `'._DB_PREFIX_.'hook` h ON (h.`id_hook` = hm.`id_hook`)
		WHERE h.`name` = \''.pSQL($hook).'\'
		AND hm.`id_module` = '.(int)($this->id)
		);
	}
	
	public function saveConfig() {
		if (Tools::getValue('submitAdvancedStyles')) {
			$this->_saveAdvancedStyles();
			$this->_html .= $this->displayConfirmation($this->l('Configuration updated successfully'));
		} else if (Tools::getValue('submitOptions_'.$this->prefixFieldsOptions)) {
			foreach ( $this->_fieldsOptions as $key => $field ) {
				if ($field['type'] == 'textLang' || $field['type'] == 'textareaLang') {
					$languages = Language::getLanguages();
					$list = array();
					foreach ($languages as $language)
						$list[$language['id_lang']] = (isset($field['cast']) ? $field['cast'](Tools::getValue($key.'_'.$language['id_lang'])) : Tools::getValue($key.'_'.$language['id_lang']));

					Configuration::updateValue($key, $list, ($field['type'] == 'textareaLang' ? true:false));
				} else {
					if (! isset($field ['disable']))
						Configuration::updateValue($key, (isset($field ['cast']) ? $field ['cast'](Tools::getValue($key)) : Tools::getValue($key)));
				}
			}
			
			if (!isset($_POST['products']) || empty($_POST['products']))
				Configuration::updateValue($this->prefixFieldsOptions.'_PRODUCT_SELECTION', serialize(array()));
			else
				Configuration::updateValue($this->prefixFieldsOptions.'_PRODUCT_SELECTION', serialize($_POST['products']));
			
			$this->_html .= $this->displayConfirmation($this->l('Configuration updated successfully'));
		}
	}
	
	function displayShareConfig($share_base_config_url) {
		$this->base_config_url = $share_base_config_url;

		$this->initClassVar();
		$this->_preProcess();
		$this->_postProcess();

		$this->_html .= '<script type="text/javascript" src="' . $this->_path . 'js/adminCore.js"></script>';

		if (file_exists('/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/css/admin.css'))
			$this->_html .= '<link type="text/css" rel="stylesheet" href="' . $this->_path . 'css/admin.css" />';

		if (file_exists('/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/js/admin.js'))
		$this->_html .= '<script type="text/javascript" src="' . $this->_path . 'js/admin.js"></script>';


		$this->_html .= '<link rel="stylesheet" href="' . $this->_path . 'js/multiselect/ui.multiselect.css" type="text/css" />
							<script type="text/javascript" src="' . $this->_path . 'js/multiselect/jquery.tmpl.1.1.1.js"></script>
							<script type="text/javascript" src="' . $this->_path . 'js/multiselect/jquery.blockUI.js"></script>
							<script type="text/javascript" src="' . $this->_path . 'js/multiselect/ui.multiselect.js"></script>';

		$this->displayConfig();

		return $this->_html;
	}
	
	function displayConfig() {
		if (! isset($this->_fieldsOptions) or !self::_isFilledArray($this->_fieldsOptions))
			return;

		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$arrayKeysLang = array();

		$this->_html .= '<form action="' . $this->base_config_url . '" id="formGlobal_' . $this->name . '" name="form_' . $this->name . '" method="post" class="width3">
			<fieldset><legend class="ui-state-default"><img src="'.__PS_BASE_URI__.'modules/'.$this->name.'/logo.gif" /> '.$this->l('Settings') .'</legend>';

		foreach ($this->_fieldsOptions as $key => $field) {
			if ($field ['type'] == 'textLang' || $field ['type'] == 'textareaLang') {
				$arrayKeysLang[] = $key;
			}
		}
		$keysLang = implode($arrayKeysLang,'¤');
		foreach ($this->_fieldsOptions as $key => $field) {
			$val = Tools::getValue($key, Configuration::get($key, false));
			if (isset($field ['bloc_id']) && $field ['bloc_id']) {
				$this->_html .= '<div id="'.$field ['bloc_id'].'">';
			}
			$this->_html .= '
			<label>' . $field ['title'] . ' </label>
			<div class="margin-form">';
			switch ($field ['type']) {
				case 'select' :
					$this->_html .= '<select id="' . $key . '" name="' . $key . '">';
					foreach ( $field ['list'] as $value )
						$this->_html .= '<option
							value="' . (isset($field ['cast']) ? $field ['cast']($value [$field ['identifier']]) : $value [$field ['identifier']]) . '"' . (($val === false && isset($field ['default']) && $field ['default'] === $value [$field ['identifier']]) || ($val == $value [$field ['identifier']]) ? ' selected="selected"' : '') . '>' . $value ['name'] . '</option>';
						$this->_html .= '</select>';
						if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
							$this->_html .= '<script type="text/javascript">$jqPm("#'.$key.'").chosen({ disable_search: true, max_selected_options: 1, inherit_select_classes: true });</script>';
						} else {
							$this->_html .= '<script type="text/javascript">$jqPm("#'.$key.'").selectmenu({wrapperElement: "<div class=\'ui_select_menu\' />"});</script>';
						}
						$this->_pmClear();
					$this->_html .= '<div class="clear"></div>';
					break;
				case 'bool' :
					if (isset($field['default']) && $field['default'] == true && $val === false)
						$val = $field['default'];
					$this->_html .= '<label class="t" for="' . $key . '_on"><img src="'.$this->_path.'images/yes.png" alt="' . $this->l('Yes') . '" title="' . $this->l('Yes') . '" /></label>
						<input type="radio" name="' . $key . '" id="' . $key . '_on" value="1"' . ($val ? ' checked="checked"' : '') . '' . (isset($field ['disable']) && $field ['disable'] ? 'disabled="disabled"' : '') . ' />
						<label class="t" for="' . $key . '_on"> ' . $this->l('Yes') . '</label>
						<label class="t" for="' . $key . '_off"><img src="'.$this->_path.'images/no.png" alt="' . $this->l('No') . '" title="' . $this->l('No') . '" style="margin-left: 10px;" /></label>
						<input type="radio" name="' . $key . '" id="' . $key . '_off" value="0" ' . (! $val ? 'checked="checked"' : '') . '' . (isset($field ['disable']) && $field ['disable'] ? 'disabled="disabled"' : '') . '/>
						<label class="t" for="' . $key . '_off"> ' . $this->l('No') . '</label>';
					$this->_pmClear();
					break;
				case 'textLang' :
					foreach ( $languages as $language ) {
						$val = Tools::getValue($key . '_' . $language ['id_lang'], Configuration::get($key, $language ['id_lang']));
						$this->_html .= '
							<div id="' . $key . '_' . $language ['id_lang'] . '" style="display: ' . ($language ['id_lang'] == $defaultLanguage ? 'block' : 'none') . '; float: left; margin-right: 10px;">
							<input ' . (isset($field ['size']) ? 'size="' . $field ['size'] . '"' : '' ).' type="text" class="ui-corner-all ui-input-pm" name="' . $key . '_' . $language ['id_lang'] . '" value="' . $val . '" size="40" />
							</div>';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $keysLang, $key,true);
					$this->_html .= '<br style="clear:both" />';
					$this->_pmClear();
					break;
				case 'textareaLang':
					$this->initTinyMceAtEnd = true;
					foreach ($languages as $language) {
						$val = Tools::getValue($key.'_'.$language['id_lang'], Configuration::get($key, $language['id_lang']));
						$this->_html .= '
							<div id="'.$key.'_'.$language['id_lang'].'" style="display: ' . ($language ['id_lang'] == $defaultLanguage ? 'block' : 'none') . '; float: left;">
							<textarea class="rte" cols="100" rows="20" name="'.$key.'_'.$language['id_lang'].'">'.$val.'</textarea>
							</div>&nbsp;';
					}
					$this->_html .= $this->displayFlags($languages, $defaultLanguage, $keysLang, $key,true);
					$this->_html .= '<br style="clear:both">';
					$this->_pmClear();
					break;
				case 'text' :
					default :
						$this->_html .= '<input type="text" class="ui-corner-all ui-input-pm" name="' . $key . '" value="' . ($val === false && isset($field ['default']) && $field ['default'] ? $field ['default'] : $val) . '" size="40" />' . (isset($field ['suffix']) ? $field ['suffix'] : '');
						$this->_pmClear();
			}
			$this->_html .= (isset($field ['desc']) ? ' &nbsp; <span>' . $field ['desc'] . '</span>' : '');
			$this->_html .= '</div>';
			if (isset($field ['bloc_id']) && $field ['bloc_id']) {
				$this->_html .= '</div>';
			}
			if (isset($field ['script']) && $field ['script']) {
				$this->_html .= '<script type="text/javascript">'.$field ['script'].'</script>';
			}
		}
			
		$productSelection = array();
		$postProductSelection = array_filter(unserialize(Configuration::get($this->prefixFieldsOptions.'_PRODUCT_SELECTION')));
		if (self::_isFilledArray($postProductSelection))
			$productSelection = $this->getElementProducts($postProductSelection, $this->_cookie->id_lang, false);

		$this->_html .='<h2>'.$this->l('Enforced products').'</h2>';
		$this->_html .= '<div class="pm_multiselect_container">';
		$this->_displayAjaxSelectMultiple(array('selectedoptions' => $productSelection,
												'key' => 'products',
												'label' => $this->l('Products'),
												'remoteurl' => $this->base_config_url . '&getItem=1&itemType=product',
												'idcolumn' => 'id_product',
												'namecolumn' => 'name')
		);

		$this->_html .= '<center>
			<input type="submit" value="' . $this->l('   Save   ') . '" name="submitOptions_'.$this->prefixFieldsOptions.'" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
			</center>
			<br />';
		$this->_html .= '</fieldset></form>';
			
	}
	
	const DYNAMIC_CSS = 'css/pm_crosssellingoncart_dynamic.css';
	protected function _saveAdvancedStyles($content = false) {
		$content = $content ? $content : Tools::getValue(self::$_module_prefix . '_css');
		Configuration::updateValue('PM_'.self::$_module_prefix.'_ADVANCED_STYLES', base64_encode($content));
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=') && Shop::isFeatureActive()) {
			$contextShops = Shop::getContextListShopID();
		} else {
			$contextShops = array(1);
		}
		foreach ($contextShops as $id_shop) {
			$dynamic_css_file = str_replace('.css','-'.$id_shop.'.css','/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/' . self::DYNAMIC_CSS);
			if (self::_getAdvancedStylesDb($id_shop) !== false) $content = self::_getAdvancedStylesDb($id_shop);
			file_put_contents($dynamic_css_file, $content);
		}
	}
	
	public static function _getAdvancedStylesDb($id_shop = NULL) {
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=') && $id_shop != NULL) {
			$advanced_css_file_db = Configuration::get('PM_'.self::$_module_prefix.'_ADVANCED_STYLES', null, null, $id_shop);
		} else {
			$advanced_css_file_db = Configuration::get('PM_'.self::$_module_prefix.'_ADVANCED_STYLES');
		}
		if ($advanced_css_file_db !== false) return base64_decode($advanced_css_file_db);
		return false;
	}
	
	public function displayAdvancedStyles() {
		if (!is_writable('/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/css') || !is_dir('/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/css')) {
			$this->_html .= '<div class="warning warn clear"><p>' . $this->l('Before being able to configure the advanced styles, make sure to set write permissions to the "css" folder into the module directory').'</p></div>';
		} else {
			$this->_html .= '<script src="' . $this->_path . 'js/codemirror/codemirror.js" type="text/javascript"></script>
							<link rel="stylesheet" href="' . $this->_path . 'js/codemirror/codemirror.css" type="text/css" />
							<link rel="stylesheet" href="' . $this->_path . 'js/codemirror/default.css" type="text/css" />
							<script src="' . $this->_path . 'js/codemirror/css.js" type="text/javascript"></script>';
			$this->_html .='<h2>'.$this->l('Advanced settings').'</h2>';
			
			
			$this->_html .= '<form action="' . $this->base_config_url . '" id="formGlobal_' . $this->name . '" name="form_' . $this->name . '" method="post" class="width3">
				<fieldset><legend class="ui-state-default"><img src="'.__PS_BASE_URI__.'modules/'.$this->name.'/images/document-code.png" /> '.$this->l('Advanced styles') .'</legend>';
				
			$this->_html .= '<div class="margin-form">
				<div class="dynamicTextarea" style="width:95%;"><textarea style="width:9%;height:150px;" rows="5" name="CSOC_css" id="CSOC_css">'. trim(self::_getAdvancedStylesDb()) .'</textarea></div>';
			$this->_html .= '<div class="clear"></div></div>';
			$this->_html .= '<script type="text/javascript">
			   var editorCSOC_css = CodeMirror.fromTextArea(document.getElementById("CSOC_css"), {mode:  "css"});
			  </script>';
			
			$this->_html .= '<center>
				<input type="submit" value="' . $this->l('   Save   ') . '" name="submitAdvancedStyles" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" />
				</center>
				<br />';
			$this->_html .= '</fieldset></form>';
		}
	}
	
	public function initClassVar() {
		global $smarty, $cookie, $currentIndex;
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
			$this->_context = Context::getContext();
			$this->_cookie = $this->_context->cookie;
			$this->_smarty = $this->_context->smarty;
		} else {
			$this->_cookie = $cookie;
			$this->_smarty = $smarty;
		}
		if (!$this->base_config_url)
			$this->base_config_url = (version_compare(_PS_VERSION_, '1.5.0.0', '<') ? $currentIndex : $_SERVER['SCRIPT_NAME'].(($controller = Tools::getValue('controller')) ? '?controller='.$controller: '')) . '&configure=' . $this->name . '&token=' . Tools::getValue('token');
		$defaultLanguage = intval(Configuration::get('PS_LANG_DEFAULT'));
		$languages = Language::getLanguages();
		$this->defaultLanguage = $defaultLanguage;
		$this->_iso_lang = Language::getIsoById($this->_cookie->id_lang);
		$this->languages = $languages;

	}
	
	function includeAdminCssJs() {
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
			$this->context->controller->addJqueryUI(array('ui.draggable', 'ui.droppable', 'ui.sortable', 'ui.widget', 'ui.dialog', 'ui.tabs'), 'base');
		} else {
			if (version_compare(_PS_VERSION_, '1.5.0.0', '<'))
				$this->_html .= '<script type="text/javascript" src="'.$this->_path . 'js/jquery.min.js"></script>';
			$this->_html .= '<link type="text/css" rel="stylesheet" href="' . $this->_path . 'js/jqueryui/1.8.9/themes/custom-theme/jquery-ui-1.8.16.custom.css" />
							<script type="text/javascript" src="'.$this->_path . 'js/jquery-ui-1.8.11.min.js"></script>';
		}
		
		$this->_html .= '<script type="text/javascript">';
		if (version_compare(_PS_VERSION_, '1.5.0.0', '<'))
			$this->_html .= 'var $jqPm = jQuery.noConflict(true);';
		else
			$this->_html .= 'var $jqPm = jQuery;';
		$this->_html .= '</script>';

		$this->_html .= '<script type="text/javascript" src="' . $this->_path . 'js/adminCore.js"></script>';
		$this->_html .= '<script type="text/javascript">
							var _modulePath = "' . $this->_path . '";
							var _base_config_url = "' . $this->base_config_url . '";
							var id_language = Number(' . $this->defaultLanguage . ');
						</script>';
		$this->_html .= '<link type="text/css" rel="stylesheet" href="' . $this->_path . 'js/jGrowl/jquery.jgrowl.css" />
						<script type="text/javascript" src="' . $this->_path . 'js/jGrowl/jquery.jgrowl_minimized.js"></script>';


		if (file_exists('/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/css/admin.css'))
			$this->_html .= '<link type="text/css" rel="stylesheet" href="' . $this->_path . 'css/admin.css" />';
		if (file_exists('/home/ddauteuil/www/mgd-beta/modules/pm_crosssellingoncart' . '/js/admin.js'))
		$this->_html .= '<script type="text/javascript" src="' . $this->_path . 'js/admin.js"></script>';


		$this->_html .= '<link rel="stylesheet" href="' . $this->_path . 'js/multiselect/ui.multiselect.css" type="text/css" />
							 <script type="text/javascript" src="' . $this->_path . 'js/multiselect/jquery.tmpl.1.1.1.js"></script>
							 <script type="text/javascript" src="' . $this->_path . 'js/multiselect/jquery.blockUI.js"></script>
							 <script type="text/javascript" src="' . $this->_path . 'js/multiselect/ui.multiselect.js"></script>';

		if (version_compare(_PS_VERSION_, '1.6.0.0', '>=')) {
			$this->context->controller->addJqueryPlugin('chosen');
		} else {
			$this->_html .= '<script type="text/javascript" src="' . $this->_path . 'js/ui.selectmenu.js"></script>';
		}
	}
	
	public function _postProcess() {
		// Dismiss Addons rating
		if (isset($_GET['dismissRating'])) {
			$this->_cleanOutput();
			if (version_compare(_PS_VERSION_, '1.5.0.0', '>='))
				Configuration::updateGlobalValue('PM_'.self::$_module_prefix.'_DISMISS_RATING', 1);
			else
				Configuration::updateValue('PM_'.self::$_module_prefix.'_DISMISS_RATING', 1);
			die;
		}
		
		$this->saveConfig();
		if (isset($_GET ['orderProductSelection'])) {
			$order = $_GET ['orderProductSelection'] ?$_GET ['orderProductSelection'] : array ();
			Configuration::updateValue($this->prefixFieldsOptions.'_PRODUCT_SELECTION', serialize($order));
			$this->_cleanOutput();
			$this->_html .= '<script type="text/javascript">parent.parent.show_info("' . $this->l('Configuration saved successfully') . '");</script>';
			$this->_echoOutput(true);
		} elseif (Tools::getValue('getItem')) {
			$this->_cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);
			if (! $query || strlen($query) < 1) {
				if (ob_get_length() > 0) ob_clean();
				die();
			}
			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this->getProductsOnLive($query, $limit, $start);
			$item_id_column = 'id_product';
			$item_name_column = 'name';

			if (self::_isFilledArray($items))
				foreach ($items as $row)
					$this->_html .= $row [$item_id_column] . '=' . $row [$item_name_column] . "\n";
			$this->_html = rtrim($this->_html, "\n");
			$this->_echoOutput(true);
		}
	}
	
	public function _preProcess(){
		if (Configuration::get($this->prefixFieldsOptions.'_PRODUCT_SELECTION') === false)
			Configuration::updateValue($this->prefixFieldsOptions.'_PRODUCT_SELECTION', serialize(array()));
	}
	
	public static function getImageType() {
		$result = array ();
		$result = self::Db_ExecuteS('
			SELECT it. `id_image_type`, it.`name`, it.`products`, it.`width`, it.`height`
			FROM `' . _DB_PREFIX_ . 'image_type` it
			WHERE it.`products` = 1
		');
		$image = array();
		foreach ( $result as $k => $img )
			$image [$k] = $img ['name'];

		return $image;
	}
	
	public function getContent() {
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) $this->_html .= '<div id="pm_backoffice_wrapper" class="pm_bo_ps_'.substr(str_replace('.', '', _PS_VERSION_), 0, 2).'">';
		$this->initClassVar();
		$this->_displayTitle($this->displayName);
		if (Tools::getValue('makeUpdate'))
			$this->_checkIfModuleIsUpdate(true);
		if (!$this->_checkIfModuleIsUpdate(false)) {
			$this->_html .= '
				<div class="warning warn clear"><p>' . $this->l('We have detected that you installed a new version of the module on your shop') . '</p>
					<p style="text-align: center"><a href="' . $this->base_config_url . '&makeUpdate=1" class="button">' . $this->l('Please click here in order to finish the installation process') . '</a></p>
				</div>';
			$this->includeAdminCssJs();
		} else {
			$this->_preProcess();
			$this->includeAdminCssJs();
			$this->_postProcess();
			$this->_showRating(false);
			$this->displayConfig();
			$this->displayAdvancedStyles();
			$this->_displaySupport();
		}
		$this->_html .= '</div>';
		return $this->_html;
	}
	
	public function getElementProducts($products,$id_lang,$getProductsProperties = true) {
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
			$result = self::Db_ExecuteS('
				SELECT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, MAX(product_attribute_shop.id_product_attribute) id_product_attribute, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, pl.`description`, pl.`description_short`, pl.`available_now`,
					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, MAX(image_shop.`id_image`) id_image,
					il.`legend`, m.`name` AS manufacturer_name, cl.`name` AS category_default,
					DATEDIFF(product_shop.`date_add`, DATE_SUB(NOW(),
					INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new,
					product_shop.price AS orderprice
				FROM `' . _DB_PREFIX_ . 'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product`)
				' . Context::getContext()->shop->addSqlAssociation('product', 'p') . '
				' . Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.`default_on` = 1') . '
				' . Product::sqlStock('p', 'product_attribute_shop', false, Context::getContext()->shop) . '
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (
					p.`id_product` = pl.`id_product`
					AND pl.`id_lang` = ' . (int)$id_lang . Context::getContext()->shop->addSqlRestrictionOnLang('pl') . '
				)
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (
					p.`id_category_default` = cl.`id_category`
					AND cl.`id_lang` = ' . (int)$id_lang . Context::getContext()->shop->addSqlRestrictionOnLang('cl') . '
				)
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (p.`id_product` = i.`id_product`)
				' . Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover=1') . '
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int)$id_lang . ')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
					AND tr.`id_country` = ' . (int)Context::getContext()->country->id . '
					AND tr.`id_state` = 0
				)
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = ' . (int)$id_lang . ')
				WHERE product_shop.`id_shop` = ' . (int)Context::getContext()->shop->id . '
				AND product_shop.`active` = 1
				AND product_shop.`id_product` IN (' . implode(',', $products) . ')
				AND product_shop.`visibility` IN ("both", "catalog")
				AND IF (
					(stock.`id_product_attribute` = 0 AND pa.`id_product_attribute` IS NULL)
					OR 
					(product_attribute_shop.`id_product_attribute` IS NOT NULL AND stock.`id_product_attribute`=product_attribute_shop.`id_product_attribute`), 1, 0
				)
				AND IF (stock.`quantity` > 0, 1, IF (stock.`out_of_stock` = 2, ' . Configuration::get('PS_ORDER_OUT_OF_STOCK') . ' = 1, stock.`out_of_stock` = 1))
				GROUP BY product_shop.`id_product`
				ORDER BY FIELD(product_shop.`id_product`, '.implode(',', $products) . ')
			');
		} elseif (version_compare(_PS_VERSION_, '1.4.0.0', '>=')) {
			$result = self::Db_ExecuteS('
				SELECT p.*, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, (p.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1)) AS orderprice
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . ')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . ')
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int)$id_lang . ')
				LEFT JOIN `'._DB_PREFIX_.'tax_rule` tr ON (p.`id_tax_rules_group` = tr.`id_tax_rules_group`
					AND tr.`id_country` = ' . (int)Country::getDefaultCountryId() . '
					AND tr.`id_state` = 0
				)
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON (t.`id_tax` = tr.`id_tax`)
				LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = ' . (int)$id_lang . ')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE p.`id_product` IN (' . implode(',', $products) . ')
				AND p.`active` = 1
				AND IF (
					(pa.`id_product_attribute` IS NULL AND p.`quantity` > 0)
					OR 
					(pa.`id_product_attribute` IS NOT NULL AND pa.`quantity` > 0), 1, IF(p.`out_of_stock` = 2, ' . Configuration::get('PS_ORDER_OUT_OF_STOCK') . ' = 1, p.`out_of_stock` = 1)
				)
				ORDER BY FIELD(p.`id_product`, '.implode(',', $products) . ')
			');
		} else {
			$result = self::Db_ExecuteS('
				SELECT p.*, pa.`id_product_attribute`, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`, il.`legend`, m.`name` AS manufacturer_name, tl.`name` AS tax_name, t.`rate`, cl.`name` AS category_default, DATEDIFF(p.`date_add`, DATE_SUB(NOW(), INTERVAL '.(Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20).' DAY)) > 0 AS new, (p.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1) - IF((DATEDIFF(`reduction_from`, CURDATE()) <= 0 AND DATEDIFF(`reduction_to`, CURDATE()) >=0) OR `reduction_from` = `reduction_to`, IF(`reduction_price` > 0, `reduction_price`, (p.`price` * IF(t.`rate`,((100 + (t.`rate`))/100),1) * `reduction_percent` / 100)),0)) AS orderprice
				FROM `'._DB_PREFIX_.'product` p
				LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (p.`id_product` = pa.`id_product` AND default_on = 1)
				LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (p.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . intval($id_lang) . ')
				LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . intval($id_lang) . ')
				LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
				LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = ' . intval($id_lang) . ')
				LEFT JOIN `'._DB_PREFIX_.'tax` t ON t.`id_tax` = p.`id_tax`
				LEFT JOIN `'._DB_PREFIX_.'tax_lang` tl ON (t.`id_tax` = tl.`id_tax` AND tl.`id_lang` = ' . intval($id_lang) . ')
				LEFT JOIN `'._DB_PREFIX_.'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
				WHERE p.`id_product` IN (' . implode(',', $products) . ')
				AND p.`active` = 1
				AND IF (
					(pa.`id_product_attribute` IS NULL AND p.`quantity` > 0)
					OR 
					(pa.`id_product_attribute` IS NOT NULL AND pa.`quantity` > 0), 1, IF(p.`out_of_stock` = 2, ' . Configuration::get('PS_ORDER_OUT_OF_STOCK') . ' = 1, p.`out_of_stock` = 1)
				)
				ORDER BY FIELD(p.`id_product`, ' . implode(',', $products) . ')
			');
		}
		if ($getProductsProperties)
			/* Modify SQL result */
			return Product::getProductsProperties($id_lang, $result);
		else return $result;
	}
	
	protected function sortProducts() {
		$this->_displayTitle( $this->l('Sort Products') );
		$productSelection = array();
		$postProductSelection = array_filter(unserialize(Configuration::get($this->prefixFieldsOptions.'_PRODUCT_SELECTION')));
		if (self::_isFilledArray($postProductSelection))
			$productSelection = $this->getElementProducts($postProductSelection, $this->_cookie->id_lang, false);

		$this->_displaySortPanel($productSelection);
	}
	
	protected function _displayAjaxSelectMultiple($configOptions) {
		$defaultOptions = array(
			'remoteurl' => false,
			'limit' => 50,
			'limitincrement' => 20,
			'remoteparams' => false,
			'tips' => false,
			'triggeronliclick' => true,
			'displaymore' => true,
			'idcolumn' => '',
			'namecolumn' => ''
		);
		$configOptions = $this->_parseOptions($defaultOptions, $configOptions);

		$this->_html .= '<div class="margin-form">';
		$this->_html .= '<select id="multiselect' . $configOptions['key'] . '" class="multiselect" multiple="multiple" name="' . $configOptions['key'] . '[]">';
		if ($configOptions['selectedoptions'] && self::_isFilledArray($configOptions['selectedoptions'])) {
			$index_column = false;
			if (isset($configOptions['namecolumn']) && isset($configOptions['idcolumn']) && !empty($configOptions['namecolumn']) && !empty($configOptions['idcolumn'])) $index_column = true;
			foreach ( $configOptions['selectedoptions'] as $value => $option ) {
				if ($index_column) {
					$this->_html .= '<option value="' . (int) $option[$configOptions['idcolumn']] . '" selected="selected">' . htmlentities($option[$configOptions['idcolumn']] . ' - ' .$option[$configOptions['namecolumn']], ENT_COMPAT, 'UTF-8') . '</option>';
				} else {
					$this->_html .= '<option value="' . (int) $value . '" selected="selected">' . htmlentities($option, ENT_COMPAT, 'UTF-8') . '</option>';
				}
			}
		}
		$this->_html .= '</select>';
		$this->_html .= '<script type="text/javascript">
			$jqPm("#multiselect' . $configOptions['key'] . '").multiselect({
				locale: {
						addAll:\''.addcslashes($this->l('Add all'), "'").'\',
						removeAll:\''.addcslashes($this->l('Remove all'), "'").'\',
						itemsCount:\''.addcslashes($this->l('#{count} items selected'), "'").'\',
						itemsTotal:\''.addcslashes($this->l('#{count} items total'), "'").'\',
						busy:\''.addcslashes($this->l('Please wait...'), "'").'\',
						errorDataFormat:\''.addcslashes($this->l('Cannot add options, unknown data format'), "'").'\',
						errorInsertNode:"'.addcslashes($this->l('There was a problem trying to add the item').':\n\n\t[#{key}] => #{value}\n\n'.addcslashes($this->l('The operation was aborted.'), '"'), "'").'",
						errorReadonly:\''.addcslashes($this->l('The option #{option} is readonly'), "'").'\',
						errorRequest:\''.addcslashes($this->l('Sorry! There seemed to be a problem with the remote call. (Type: #{status})'), "'").'\',
						sInputSearch:\''.addcslashes($this->l('Please enter the first letters of the search item'), "'").'\',
						sInputShowMore:\''.addcslashes($this->l('Show more'), "'").'\'
					},
				remoteUrl: "' . $configOptions['remoteurl'] . '",
				remoteLimit:' . (int) $configOptions['limit'] . ',
				remoteStart:0,
				remoteLimitIncrement:' . (int) $configOptions['limitincrement'] . ($configOptions['remoteparams'] ? ', remoteParams: { ' . $configOptions['remoteparams'] . ' }' : '') . ',
				triggerOnLiClick: '. (($configOptions['triggeronliclick'] == true) ? 'true' : 'false') .',
				displayMore: '. (($configOptions['displaymore'] == true) ? 'true' : 'false') .'
			});
			</script>';
		$this->_pmClear();
		$this->_html .= '</div>';
	}
	
	protected function _pmClear(){
		$this->_html .= '<div class="clear"></div>';
	}
	
	protected function _cleanOutput() {
		$this->_html = '';
		if (ob_get_length() > 0) ob_clean();
	}
	
	protected function _echoOutput($die = false) {
		echo $this->_html;
		if ($die) die();
	}
	
	protected function _parseOptions($defaultOptions = array(), $options = array()) {
		if (self::_isFilledArray($options)) $options = array_change_key_case($options, CASE_LOWER);
		if (isset($options['tips']) && !empty($options['tips'])) $options['tips'] = stripslashes(addcslashes($options['tips'], '"'));
		if (self::_isFilledArray($defaultOptions)) {
			$defaultOptions = array_change_key_case($defaultOptions, CASE_LOWER);
			foreach ($defaultOptions as $option_name=>$option_value)
				if (!isset($options[$option_name])) $options[$option_name] = $defaultOptions[$option_name];
		}
		return $options;
	}
	
	protected function getProductsOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
			SELECT p.`id_product`, p.`quantity`, CONCAT(p.`id_product`, \' - \', IFNULL(CONCAT(NULLIF(TRIM(p.reference), \'\'), \' - \'), \'\'), pl.`name`) AS name
			FROM `' . _DB_PREFIX_ . 'product` p, `' . _DB_PREFIX_ . 'product_lang` pl'. (version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? ', `' . _DB_PREFIX_ . 'product_shop` ps ' : '') . '
			WHERE p.`id_product`= pl.`id_product`
			'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? ' AND p.`id_product`=ps.`id_product` ' : '').'
			'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Shop::addSqlRestriction(false, 'ps') : '').'
			AND pl.`id_lang`=' . (int)$this->defaultLanguage . '
			AND p.`active` = 1
			AND ((p.`id_product` LIKE \'%' . pSQL($q) . '%\') OR (pl.`name` LIKE \'%' . pSQL($q) . '%\') OR (p.`reference` LIKE \'%' . pSQL($q) . '%\') OR (pl.`description` LIKE \'%' . pSQL($q) . '%\') OR (pl.`description_short` LIKE \'%' . pSQL($q) . '%\'))
			'.(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? 'GROUP BY p.`id_product`' : '').'
			ORDER BY pl.`name` ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . (int) $limit : ''));
		
		if (self::_isFilledArray($result))
			return $result;
	}
	
	public static function Db_ExecuteS($q) {
		if (version_compare(_PS_VERSION_, '1.4.0.0', '>=')) return Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($q);
		else return Db::getInstance()->ExecuteS($q);
	}
	
	protected function _getAccessoriesLight($productList, $limit) {
		if (!self::_isFilledArray($productList)) return false;
		return self::Db_ExecuteS('
		SELECT `id_product_2` AS id_product
		FROM `'._DB_PREFIX_.'accessory`
		WHERE `id_product_1` IN ('.(implode(',', $productList)).')
		ORDER BY RAND()
		LIMIT '.(int)$limit);
	}
	
	protected function _getCrossSellingLight($productList, $limit) {
		if (!self::_isFilledArray($productList)) return false;
		$ordersListResult = self::Db_executeS('SELECT DISTINCT od.id_order FROM '._DB_PREFIX_.'orders o JOIN '._DB_PREFIX_.'order_detail od ON (od.id_order = o.id_order) WHERE o.valid = 1 AND od.product_id IN ('.(implode(',', $productList)).') LIMIT 200');
		if (self::_isFilledArray($ordersListResult)) {
			$orderListId = array();
			foreach ($ordersListResult as $orderListRow)
				$orderListId[] = (int)$orderListRow['id_order'];
			return self::Db_executeS('SELECT DISTINCT od.product_id FROM '._DB_PREFIX_.'order_detail od WHERE od.product_id NOT IN ('.implode(',', $productList).') AND od.id_order IN ('.implode(',', $orderListId).') LIMIT '.(int)$limit);
		}
		return false;
	}
	
	function hookHeader($params) {
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=')) {
			$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/owl-carousel/owl.carousel.min.js');
			if (isset($this->context->controller->php_self) && ($this->context->controller->php_self == 'order' || $this->context->controller->php_self == 'order-opc'))
				$this->context->controller->addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/pm_crosssellingoncart.js');
			$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/owl-carousel/owl.carousel.css', 'all');
			$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/owl-carousel/owl.theme.css', 'all');
			$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/css/pm_crosssellingoncart.css', 'all');
			$this->context->controller->addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/'.str_replace('.css', '-'.Context::getContext()->shop->id.'.css', self::DYNAMIC_CSS), 'all');
		} else {
			if (version_compare(_PS_VERSION_, "1.4.9.0", '<='))
				Tools::addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/psold14fix-jquery.min.js');
			Tools::addJS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/owl-carousel/owl.carousel.min.js');
			Tools::addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/owl-carousel/owl.carousel.css', 'all');
			Tools::addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/js/owl-carousel/owl.theme.css', 'all');
			Tools::addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/css/pm_crosssellingoncart.css', 'all');
			Tools::addCSS(__PS_BASE_URI__ . 'modules/' . $this->name . '/'.str_replace('.css', '-1.css', self::DYNAMIC_CSS), 'all');
		}
	}
	
	function hookShoppingCart($params) {
		if (isset($params['products']))
			$cart_products = $params['products'];
		else {
			global $cart;
			$cart_products = $cart->getProducts(true);
		}
		global $link;
		
		$displayProductAlreadyInCart = Configuration::get($this->prefixFieldsOptions.'_IDENTICAL_PRODUCTY',false);
		$nbProductSelection = Configuration::get($this->prefixFieldsOptions.'_NB_PRODUCT');
		$nbAccessories = Configuration::get($this->prefixFieldsOptions.'_NB_ACCESSORIES');
		if (!$nbAccessories)
			$nbAccessories = (int)$prefixFieldsOptions[$this->prefixFieldsOptions.'_NB_ACCESSORIES']['default'];
		$nbCrossSelling = Configuration::get($this->prefixFieldsOptions.'_NB_CROSSSELLING');
		if (!$nbCrossSelling)
			$nbCrossSelling = (int)$prefixFieldsOptions[$this->prefixFieldsOptions.'_NB_CROSSSELLING']['default'];
		
		$imageSize = Configuration::get($this->prefixFieldsOptions.'_IMAGE_SIZE');
		$blockTitle = Configuration::get($this->prefixFieldsOptions.'_TITLE_BLOC', $this->_cookie->id_lang);
		$postProductSelection = @array_filter(unserialize(Configuration::get($this->prefixFieldsOptions.'_PRODUCT_SELECTION')));
		$productSelection = array();
		
		//Product already on cart
		$productsOnCart = array();
		foreach($cart_products as $k=>$v) {
			$productsOnCart[] = $v['id_product'];
		}
		$productsOnCart = array_unique($productsOnCart);
		
		if (Configuration::get($this->prefixFieldsOptions.'_ACCESSORIES')) {
			$productAccessories = $this->_getAccessoriesLight($productsOnCart, $nbAccessories);
			if (self::_isFilledArray($productAccessories))
				foreach ($productAccessories AS $product)
					$postProductSelection[] = (int)$product['id_product'];
		}
		
		if (Configuration::get($this->prefixFieldsOptions.'_CROSSSELLING')) {
			$productsCrossSelling = $this->_getCrossSellingLight($productsOnCart, $nbCrossSelling);
			if (self::_isFilledArray($productsCrossSelling))
				foreach ($productsCrossSelling AS $product)
					$postProductSelection[] = (int)$product['product_id'];
		}
		
		if (self::_isFilledArray($postProductSelection)){
			$postProductSelection = array_unique($postProductSelection);
			// Check if products already in cart
			if (!$displayProductAlreadyInCart)
				$postProductSelection = array_diff($postProductSelection, $productsOnCart);
			
			//rand product array
			//shuffle($postProductSelection);
			if (self::_isFilledArray($postProductSelection))
				$productSelection = $this->getElementProducts($postProductSelection, $this->_cookie->id_lang);
		}
		
		$this->_smarty->assign(array(
			'csoc_order_page_link' => (version_compare(_PS_VERSION_, "1.5.0.0", '>=') ? Context::getContext()->link->getPageLink((isset($this->context->controller->php_self) && ($this->context->controller->php_self == 'order' || $this->context->controller->php_self == 'order-opc') ? $this->context->controller->php_self : 'order')) : $link->getPageLink('order.php')),
			'csoc_product_selection' => $productSelection,
			'csoc_bloc_title' => $blockTitle,
			'csoc_products_quantity' => $nbProductSelection,
			'csoc_prefix' => $this->prefixFieldsOptions,
			'tax_enabled' => Configuration::get('PS_TAX'),
			'imageSize' => $imageSize,
			'ps_version' => _PS_VERSION_,
			'csoc_static_token' => Tools::getToken(false)
		));
		
		if (version_compare(_PS_VERSION_, '1.6.0.0', '>='))
			return $this->display(__FILE__, 'pm_crosssellingoncart-16.tpl');
		else
			return $this->display(__FILE__, 'pm_crosssellingoncart.tpl');
	}
	
	function hookMCBelow($params) {
		$this->prefixFieldsOptions = 'PM_MC_CSOC';
		return $this->hookShoppingCart($params);
	}
	
	protected function _displayTitle($title) {
		$this->_html .= '<h2>' . $title . '</h2>';
	}
	
	// Begin _getPMdata
	protected $pm_lk;
	protected function _getPMdata() {
		$param = array();
		$param[] = 'ver-'._PS_VERSION_;
		$param[] = 'current-'.$this->name;
		$param[] = 'lk-'.$this->pm_lk;
		$result = Db::getInstance()->ExecuteS('SELECT DISTINCT name FROM '._DB_PREFIX_.'module WHERE name LIKE "pm_%"');
		if ($result && self::_isFilledArray($result)) {
			foreach ($result as $module) {
				$instance = Module::getInstanceByName($module['name']);
				if ($instance && isset($instance->version)) $param[] = $module['name'].'-'.$instance->version;
			}
		}
		return urlencode(base64_encode(implode('|', $param)));
	}
	// End _getPMdata
	
	// Begin __displayCS
	protected function _displayCS() {
		$this->_html .= '<div id="pm_panel_cs_modules_bottom" class="pm_panel_cs_modules_bottom"><br />';
		$this->_displayTitle($this->l('Check all our modules'));
		$this->_html .= '<iframe src="//www.presta-module.com/cross-selling-modules-footer?pm='.$this->_getPMdata().'" scrolling="no"></iframe></div>';
	}
	// End __displayCS

	// Begin _displaySupport
	/**
	 * Display copyright and support email block
	 *
	 * @see _isFilledArray
	 * @see _displayTitle
	 * @see _displaySubTitle
	 * @see _includeHTMLAtEnd
	 * @return void
	 */
	protected function _displaySupport() {
		$this->_html .= '<div id="pm_footer_container" class="ui-corner-all ui-tabs ui-tabs-panel">';
		$this->_displayCS();
		$this->_html .= '<div id="pm_support_informations" class="pm_panel_bottom"><br />';
		if (method_exists($this, '_displayTitle'))
			$this->_displayTitle($this->l('Information & Support', (isset($this->_coreClassName) ? $this->_coreClassName : false)));
		else
			$this->_html .= '<h2>' . $this->l('Information & Support', (isset($this->_coreClassName) ? $this->_coreClassName : false)) . '</h2>';
		
		$this->_html .= '<ul class="pm_links_block">';
		$this->_html .= '<li class="pm_module_version"><strong>' . $this->l('Module Version: ', (isset($this->_coreClassName) ? $this->_coreClassName : false)) . '</strong> ' . $this->version . '</li>';
		
		if (isset($this->_getting_started) && self::_isFilledArray($this->_getting_started))
			$this->_html .= '<li class="pm_get_started_link"><a href="javascript:;" class="pm_link">'. $this->l('Getting started', (isset($this->_coreClassName) ? $this->_coreClassName : false)) .'</a></li>';
		
		if (self::_isFilledArray($this->_support_link))
			foreach($this->_support_link as $infos)
				$this->_html .= '<li class="pm_useful_link"><a href="'.$infos['link'].'" target="_blank" class="pm_link">'.$infos['label'].'</a></li>';
		$this->_html .= '</ul>';
		
		if (isset($this->_copyright_link) && $this->_copyright_link) {
			$this->_html .= '<div class="pm_copy_block">';
			if (isset($this->_copyright_link['link']) && !empty($this->_copyright_link['link'])) $this->_html .= '<a href="'.$this->_copyright_link['link'].'"'.((isset($this->_copyright_link['target']) AND $this->_copyright_link['target']) ? ' target="'.$this->_copyright_link['target'].'"':'').''.((isset($this->_copyright_link['style']) AND $this->_copyright_link['style']) ? ' style="'.$this->_copyright_link['style'].'"':'').'>';
			$this->_html .= '<img src="'.str_replace('_PATH_',$this->_path,$this->_copyright_link['img']).'" />';
			if (isset($this->_copyright_link['link']) && !empty($this->_copyright_link['link'])) $this->_html .= '</a>';
			$this->_html .= '</div>';
		}
		$this->_html .= '</div>';
		$this->_html .= '</div>';
		
		// Get started images
		if (isset($this->_getting_started) && self::_isFilledArray($this->_getting_started)) {
			$this->_html .= "<script type=\"text/javascript\">
			$('.pm_get_started_link a').click(function() { $.fancybox([";
			$get_started_image_list = array();
			foreach ($this->_getting_started as $get_started_image)
				$get_started_image_list[] = "{ 'href': '".$get_started_image['href']."', 'title': '".htmlentities($get_started_image['title'], ENT_QUOTES, 'UTF-8')."' }";
			$this->_html .= implode(',', $get_started_image_list);
			$this->_html .= "
					], {
					'padding'			: 0,
					'transitionIn'		: 'none',
					'transitionOut'		: 'none',
					'type'				: 'image',
					'changeFade'		: 0
				}); });
			</script>";
		}
		// /Get started images
		
		// To execute some javascript  at end of content configuration
		if (method_exists($this, '_includeHTMLAtEnd')) $this->_includeHTMLAtEnd();
	}
	// End _displaySupport
	
	public static function _isFilledArray($array) {
		return ($array && is_array($array) && sizeof($array));
	}
	
	// Begin _showRating
	/**
	 * Display rating invitation message
	 *
	 * @author Steph
	 * @return void
	 */
	protected function _showRating($show = false) {
		$dismiss = (int)(version_compare(_PS_VERSION_, '1.5.0.0', '>=') ? Configuration::getGlobalValue('PM_'.self::$_module_prefix.'_DISMISS_RATING') : Configuration::get('PM_'.self::$_module_prefix.'_DISMISS_RATING'));
		if ($show && $dismiss != 1 && self::_getNbDaysModuleUsage() >= 3) {
			$this->_html .= '
			<div id="addons-rating-container" class="ui-widget note">
				<div style="margin-top: 20px; margin-bottom: 20px; padding: 0 .7em; text-align: center;" class="ui-state-highlight ui-corner-all">
					<p class="invite">'
						. $this->l('You are satisfied with our module and want to encourage us to add new features ?')
						. '<br/>'
						. '<a href="http://addons.prestashop.com/ratings.php" target="_blank"><strong>'
						. $this->l('Please rate it on Prestashop Addons, and give us 5 stars !')
						. '</strong></a>
					</p>
					<p class="dismiss">'
						. '[<a href="javascript:void(0);">'
						. $this->l('No thanks, I don\'t want to help you. Close this dialog.')
						. '</a>]
					 </p>
				</div>
			</div>';
		}
	}
	// End _showRating
	
	// Begin _getNbDaysModuleUsage
	protected static function _getNbDaysModuleUsage() {
		$sql = 'SELECT DATEDIFF(NOW(),date_add)
				FROM '._DB_PREFIX_.'configuration
				WHERE name = \''.pSQL('PM_'.self::$_module_prefix.'_LAST_VERSION').'\'
				ORDER BY date_add ASC';
		return (int)Db::getInstance()->getValue($sql);
	}
	// End _getNbDaysModuleUsage
	
	// Begin hookDisplayBackOfficeHeader
	public function hookDisplayBackOfficeHeader($params) {
		if (isset(Context::getContext()->controller) && isset(Context::getContext()->controller->controller_name) && strtolower(Context::getContext()->controller->controller_name) == 'adminmodules') {
			$this->_MHM_update();
		}
	}
	// End hookDisplayBackOfficeHeader
	
	// Begin _MHM_needUpdate
	protected function _MHM_needUpdate() {
		if (version_compare(_PS_VERSION_, '1.5.0.0', '>=') && defined('Module::CACHE_FILE_MUST_HAVE_MODULES_LIST')) {
			if (file_exists(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST) && is_readable(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST) && is_writable(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST)) {
				$content = file_get_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST);
				if (!preg_match('#PM_MODS#', $content)) return true;
			}
		}
		return false;
	}
	// End _MHM_needUpdate
	
	// Begin _MHM_update
	protected function _MHM_update() {
		if ($this->_MHM_needUpdate()) {
			$this->initClassVar();
			$content = file_get_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST);
			if (strlen($content) == 0) $content = '<?xml version="1.0" encoding="UTF-8"?><modules></modules>';
			$new_content = Tools::file_get_contents('http://www.presta-module.com/cross-selling-modules-footer?xml=1&iso='.$this->_iso_lang.'&pm='.$this->_getPMdata());
			if ($new_content !== false) {
				$content = str_replace('<modules>', '<modules><!-- PM_MODS -->'.$new_content.'<!-- /PM_MODS -->', $content);
				@file_put_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST, $content);
			} else {
				$content = str_replace('<modules>', '<modules><!-- PM_MODS --><!-- /PM_MODS -->', $content);
				@file_put_contents(_PS_ROOT_DIR_.Module::CACHE_FILE_MUST_HAVE_MODULES_LIST, $content);
			}
		}
	}
	// End _MHM_update
	
	// Begin _onBackOffice
	protected function _onBackOffice() {
		if (isset($this->_cookie->id_employee) && Validate::isUnsignedId($this->_cookie->id_employee)) return true;
		return false;
	}
	// End _onBackOffice
}