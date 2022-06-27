<?php
/**
 *
 * PM_AdvancedBackgroundChanger
 *
 * @category  Front Office Features
 * @author    Presta-Module.com <support@presta-module.com>
 * @copyright Presta-Module 2013
 * @version   1.1.13
 * 		_______  ____    ____
 * 	   |_   __ \|_   \  /   _|
 * 		 | |__) | |   \/   |
 * 		 |  ___/  | |\  /| |
 * 		_| |_    _| |_\/_| |_
 * 	   |_____|  |_____||_____|
 *
 *
 *************************************
 **   Advanced Background Changer    *
 **   http://www.presta-module.com   *
 **             V 1.1.13             *
 *************************************/

if (!defined('_PS_VERSION_')) exit ;
require_once (_PS_ROOT_DIR_ . '/modules/pm_advancedbackgroundchanger/abg_core_class.php');
require_once (_PS_ROOT_DIR_ . '/modules/pm_advancedbackgroundchanger/ABG_Group_Class.php');
require_once (_PS_ROOT_DIR_ . '/modules/pm_advancedbackgroundchanger/ABG_Slide_Class.php');
require_once (_PS_ROOT_DIR_ . '/modules/pm_advancedbackgroundchanger/ABG_Czone_Class.php');

class pm_advancedbackgroundchanger extends abg_core_class {
	protected $_require_maintenance = TRUE;
	public static $_module_prefix = 'ABG';
	protected $_file_to_check = array('uploads', 'uploads/temp', 'uploads/slides','cache','css');
	protected $_css_js_to_load = array(
									'jquery',
									'jquerytiptip',
									'jquerytools',
									'selectmenu',
									'admincore',
									'adminmodule',
									'datatables',
									'jgrowl',
									'multiselect',
									'uploadify',
									'colorpicker',
									'switchbutton',
									'template',
									'buttons',
									'codemirrorcore',
									'codemirrormixed',
									'codemirrorcss',
							);

	protected $is_pro = 'false';
	const ADVANCED_CSS_FILE = 'css/abg_advanced.css';
 	const ADVANCED_CSS_FILE_RESTORE = 'css/reset-abg_advanced.css';
	protected $_copyright_link = array(
		'link'	=> '',
		'img'	=> '//www.presta-module.com/img/logo-module.JPG'
	);

	protected $_registerOnHooks = array('header');
	const INSTALL_SQL_FILE = 'install.sql';
	const UNINSTALL_SQL_FILE = 'uninstall.sql';
	public function __construct() {
		$this -> name = 'pm_advancedbackgroundchanger';
		if (_PS_VERSION_ < 1.4)
			$this->tab = 'Presta-Module';
		else {
			$this->author = 'Presta-Module';
			$this -> tab = 'front_office_features';
		}
		$this->module_key = 'da98f7f3a486e02115bd9096270d142a';

		$this -> version = '1.1.13';
		$this -> need_instance = 0;

		parent::__construct();

		$this -> displayName = $this->l('Advanced Background Changer');
		$this -> description = $this->l('This module helps you to define backgrounds for your all pages.');

		$doc_url_tab['fr'] = 'http://www.presta-module.com/docs/fr/advancedbackgroundchanger/';
		$doc_url_tab['en'] = 'http://www.presta-module.com/docs/en/advancedbackgroundchanger/';
		$doc_url = $doc_url_tab['en'];
		if ($this->_iso_lang == 'fr') $doc_url = $doc_url_tab['fr'];

		$forum_url_tab['fr'] = 'http://www.prestashop.com/forums/topic/178945-module-pm-advanced-background-changer-diaporama-de-fond-de-page/';
		$forum_url_tab['en'] = 'http://www.prestashop.com/forums/topic/178946-module-pm-advanced-background-changer-background-slideshows/';
		$forum_url = $forum_url_tab['en'];
		if ($this->_iso_lang == 'fr') $forum_url = $forum_url_tab['fr'];

		$this->_support_link = array(
			array('link' => $forum_url, 'target' => '_blank', 'label' => $this->l('Forum topic')),
			
			array('link' => 'http://addons.prestashop.com/contact-community.php?id_product=5695', 'target' => '_blank', 'label' => $this->l('Support contact')),
		);
	}

	public function install() {
		if (parent::install() == false)
			return false;
		$this -> checkIfModuleIsUpdate(true, false);

		return true;
	}

	public function l($s, $specific = false, $id_lang = null) {
		if (_PS_VERSION_ < 1.2) {
			$translated_text = parent::l($s);
			if ($translated_text != $s) return $translated_text;
			if ($specific != false) {
				$this->page = $specific;
				$translated_text = parent::l($s);
				if ($translated_text != $s) return $translated_text;
			}
			$this->page = get_class();
			return parent::l($s);
		}
		if (_PS_VERSION_ < 1.4) return parent::l($s, $specific);
		return parent::l($s, $specific, $id_lang);
	}

	public function generateAdvancedCss($update = false) {
		if(!file_exists(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE))
		fopen(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE, 'w');
		if (! trim(file_get_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE)))
		file_put_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE, file_get_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE_RESTORE));
		if ($update && file_exists(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE)) {
			if (!preg_match('/abg_click_zone/', file_get_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE))) {
				$updateCSS = '.abg_click_zone { z-index: -1; }';
				file_put_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE, $updateCSS."\n".file_get_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE));
			}
		}
	}

	public function checkIfModuleIsUpdate($updateDb = false, $displayConfirm = true) {
		parent::checkIfModuleIsUpdate($updateDb, $displayConfirm);
		$isUpdate = true;
		if (!$updateDb && $this -> version != Configuration::get('PM_' . self::$_module_prefix . '_LAST_VERSION', false))
			return false;
		if ($updateDb) {
			unset($_GET['makeUpdate']);
			Configuration::updateValue('PM_' . self::$_module_prefix . '_LAST_VERSION', $this -> version);
			$this->installDb();

			$tableTest = self::Db_getRow('SELECT id_group FROM '._DB_PREFIX_.'pm_advanced_bg_group');
			if (empty($tableTest))
				Configuration::deleteByName(self::$_module_prefix.'_ALREADY_USE');

			$this->updateDb();
			$this->generateAdvancedCss(true);
			$this->_pmClearCache();
			if ($isUpdate && $displayConfirm)
				$this -> _html .= $this -> displayConfirmation($this->l('Module updated successfully'));
			else
				$this -> _html .= $this -> displayError($this->l('Module update fail'));
		}
		return $isUpdate;
	}

	public function updateDb() {
		$toAdd = array (
			array ('pm_advanced_bg_group', 'id_shop', 'int(10) unsigned NOT NULL DEFAULT "'.(_PS_VERSION_ >= 1.5 ? Configuration::get('PS_SHOP_DEFAULT') : 1).'"', 'id_group' ),
		);
		foreach ($toAdd as $table => $infos)
			$this->columnExists($infos[0], $infos[1], true, $infos[2], $infos[3]);
	}
	private function columnExists($table, $column, $createIfNotExist = false, $type = false, $insertAfter = false) {
		$resultset = Db::getInstance()->ExecuteS("SHOW COLUMNS FROM `" . _DB_PREFIX_ . $table . "`");
		foreach ( $resultset as $row )
			if ($row ['Field'] == $column)
				return true;

		if ($createIfNotExist && Db::getInstance()->Execute('ALTER TABLE `' . _DB_PREFIX_ . $table . '` ADD `' . $column . '` ' . $type . ' ' . ($insertAfter ? ' AFTER `' . $insertAfter . '`' : '') . ''))
			return true;
		return false;
	}

	function installDB() {
		if (!file_exists(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE))
			return (false);
		else if (!$sql = file_get_contents(dirname(__FILE__) . '/' . self::INSTALL_SQL_FILE))
			return (false);
		$sql = str_replace('PREFIX_', _DB_PREFIX_, $sql);
		if (_PS_VERSION_ >= 1.4)
			$sql = str_replace('MYSQL_ENGINE', _MYSQL_ENGINE_, $sql);
		else
			$sql = str_replace('MYSQL_ENGINE', 'MyISAM', $sql);
		$sql = preg_split("/;\s*[\r\n]+/", $sql);
		foreach ($sql as $query)
			if (!self::Db_Execute(trim($query)))
				return (false);
		return true;
	}

	public static function getGroupsStatic($id_customer){
		$groups = array();
		$result = self::Db_ExecuteS('
		SELECT cg.`id_group`
		FROM '._DB_PREFIX_.'customer_group cg
		WHERE cg.`id_customer` = '.(int)($id_customer));
		foreach ($result AS $group) $groups[] = (int)$group['id_group'];
		return $groups;
	}

	private function getGroupCategoriesOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
        SELECT cl.id_category, cl.name
        FROM  ' . _DB_PREFIX_ . 'category_lang cl
		WHERE  cl.`id_lang`=' . (int)$this -> _cookie -> id_lang . '
		AND (cl.name LIKE \'%' . str_replace('%20', ' ', pSQL($q)) . '%\')
        ORDER BY cl.name ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . ( int )$limit : ''));
		return $result;
	}

	private function getGroupProductsOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
        SELECT cl.id_product, cl.name
        FROM  ' . _DB_PREFIX_ . 'product_lang cl
		WHERE  cl.`id_lang`=' . (int)$this -> _cookie -> id_lang . '
		AND (cl.name LIKE \'%' . str_replace('%20', ' ', pSQL($q)) . '%\')
        ORDER BY cl.name ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . ( int )$limit : ''));
		return $result;
	}

	private function getGroupManufacturersOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
        SELECT cl.id_manufacturer, cl.name
        FROM  ' . _DB_PREFIX_ . 'manufacturer cl
		WHERE (cl.name LIKE \'%' . str_replace('%20', ' ', pSQL($q)) . '%\')
        ORDER BY cl.name ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . ( int )$limit : ''));
		return $result;
	}

	private function getGroupSuppliersOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
        SELECT cl.id_supplier, cl.name
        FROM  ' . _DB_PREFIX_ . 'supplier cl
		WHERE (cl.name LIKE \'%' .str_replace('%20', ' ', pSQL($q)) . '%\')
        ORDER BY cl.name ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . ( int )$limit : ''));
		return $result;
	}

	private function getGroupCmsOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
        SELECT cl.id_cms, cl.meta_title
        FROM  ' . _DB_PREFIX_ . 'cms_lang cl
		WHERE (cl.meta_title LIKE \'%' . str_replace('%20', ' ', pSQL($q)) . '%\')
		AND cl.id_lang = '.$this->_cookie->id_lang.'
        ORDER BY cl.meta_title ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . ( int )$limit : ''));
		return $result;
	}

	private function getSlidegroupOnLive($q, $limit, $start) {
		$result = self::Db_ExecuteS('
        SELECT g.id_group, g.name
        FROM  ' . _DB_PREFIX_ . 'pm_advanced_bg_group g
		WHERE (g.name LIKE \'%' . str_replace('%20', ' ', pSQL($q)) . '%\')
		'. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
        ORDER BY g.name ASC ' . ($limit ? 'LIMIT ' . $start . ', ' . ( int )$limit : ''));
		return $result;
	}

	public static function OneKeyArray($array,$deepvalue,$deepkey=false){
		if(!is_array($array))
			return FALSE;

		$ret = array();
		foreach ($array as $key => $value)
			if($deepkey)
				$ret [$value[$deepkey]] =  $value[$deepvalue];
			else
				$ret [] =  $value[$deepvalue];

		return $ret;
	}

	public function getGroupAvailableAsArray() {
		$ret = self::Db_ExecuteS('
						SELECT *
						FROM ' . _DB_PREFIX_ . 'pm_advanced_bg_group g'
						. (_PS_VERSION_ >= 1.5 ? ' WHERE g.`id_shop`='.(int)Context::getContext()->shop->id : '')
					);

		$res = array();
		foreach ($ret as $value) {

			$group =  new ABG_Group_Class ($value['id_group']);

			$string = ($group->categories?implode(',',self::OneKeyArray($group->categories,'name')):'').
			($group->products?','.implode(',', self::OneKeyArray($group->products,'name')):'').
			($group->manufacturers?','.implode(',', self::OneKeyArray($group->manufacturers,'name')):'').
			($group->cms?','.implode(',', self::OneKeyArray($group->cms,'meta_title')):'').
			($group->suppliers?','.implode(',', self::OneKeyArray($group->suppliers,'name')):'').
			($group->pages?','.implode(', ', self::OneKeyArray($group->pages,'page')):'').
			($group->default_group?','.$this->l('DEFAULT RULE'):'');
			$string = preg_replace('/^,/', '', $string);

			$string2 = ($group->usergroup?implode(', ', self::OneKeyArray($group->usergroup,'name')):'');

			$res[$value['id_group']] = '<span style="' . ($value['activation'] ? 'font-weight : bold ;' : 'font-style:italic;') . '"> ' .
			$value['name'] . ' ('.($string?$string:$this->l('Undisplayed')).'; '.$this->l('FOR').': '.($string2?$string2:$this->l('Everyone')).')'.'</span> ';
		}

		return $res;
	}

	public function getUserGroupAsArray() {
		$ret = self::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'group` m,
				`'._DB_PREFIX_.'group_lang` ml
			WHERE m.id_group = ml.id_group
			AND ml.id_lang = '.$this->_cookie->id_lang
			);

		return self::OneKeyArray($ret,'name','id_group');

	}

	public function getRulesAsArray() {
		$res = ABG_Group_Class::getGroups();

		$ret = array();
		foreach ($res as $key => $value)
				$ret [$value['id_group']] =  $value['name'];

		return $ret;
	}

	public function getSlidesAsArray($only_vegas = false) {
		$res = ABG_Slide_Class::getSlides(false, false, $only_vegas);

		$ret = array();
		foreach ($res as $key => $value)
				$ret [$value['id_slide']] =  '<img style="margin-right: 10px;" src="' .$this->_path. 'timthumb.php?src=./uploads/slides/'. $value['image'] . '&w=30&h=30&zc=2" />'.$value['name'];

		return $ret;
	}

	protected function _postSaveProcess($params) {
		$this->initVar();
		parent::_postSaveProcess($params);
		if($params['class'] == 'ABG_Group_Class'  && $_POST['image_'.(int) Configuration::get('PS_LANG_DEFAULT').'_temp_file_lang']) {
			$id_group = $params['obj']->id;
			$slide = new ABG_Slide_Class();

			$slide->name = (Tools::isSubmit('Submit_Group')?$this->l('My Background'):$this->l('My first Background'));
			$slide->date_start = (string)date('Y-m-d 00:00:00');
			$slide->date_end = (string)date('Y-m-d 00:00:00');
			$slide->activation = 1;
			$slide->bg_position = '50% 50%';
			$slide->bg_halign = 50;
			$slide->bg_valign = 50;
			$slide->bg_repeat = 'no-repeat';
			$slide->bg_fixed = 1;
			$slide->fade_time = 2000;
			$slide->bg_type = 2;
			$id_lang_conf = (int) Configuration::get('PS_LANG_DEFAULT');
			foreach (Language::getLanguages(false) as $key => $value) {
				if(!$_POST['image_'.$value['id_lang'].'_temp_file_lang'] && !isset($_POST['image_all_lang']))
					continue;

				if(($_POST['image_'.$value['id_lang'].'_temp_file_lang'] && !isset($_POST['image_all_lang']) ) ||
				($_POST['image_'.$id_lang_conf.'_temp_file_lang'] && isset($_POST['image_all_lang']) &&
				$_POST['image_'.$value['id_lang'].'_temp_file_lang'] && $value['id_lang'] != $id_lang_conf) ){

					$img_tmp = dirname(__FILE__).'/uploads/temp/'.$_POST['image_'.$value['id_lang'].'_temp_file_lang'];
					$img_final = uniqid().'.'.abg_core_class::_getFileExtension($_POST['image_'.$value['id_lang'].'_temp_file_lang']) ;
					$chemin_fichier_final = dirname(__FILE__).'/uploads/slides/'.$img_final;
					rename($img_tmp,$chemin_fichier_final);
					$slide->image[$value['id_lang']] = $img_final;
				}
				elseif($_POST['image_'.$id_lang_conf.'_temp_file_lang'] && isset($_POST['image_all_lang'])){

					$img_tmp = dirname(__FILE__).'/uploads/temp/'.$_POST['image_'.$id_lang_conf.'_temp_file_lang'];
					$img_final = uniqid().'.'.abg_core_class::_getFileExtension($_POST['image_'.$id_lang_conf.'_temp_file_lang']) ;
					$chemin_fichier_final = dirname(__FILE__).'/uploads/slides/'.$img_final;
					copy($img_tmp,$chemin_fichier_final);
					$slide->image[$value['id_lang']] = $img_final;
				}
			}

			if(file_exists(dirname(__FILE__).'/uploads/temp/'.$_POST['image_'.$id_lang_conf.'_temp_file_lang']))
				unlink(dirname(__FILE__).'/uploads/temp/'.$_POST['image_'.$id_lang_conf.'_temp_file_lang']);
			$slide->save();

			$req = abg_core_class::Db_getRow('
								SELECT MAX(sort) as pos
								FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` igis
								WHERE id_group = '.(int)$id_group);
			$pos = (int)$req['pos'] + 1;

			abg_core_class::Db_Execute('	INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` ( id_sort , `id_slide`, id_group , sort )
											VALUES ( NULL , "'.(int)$slide->id.'", "'.(int)$id_group.'" , '.$pos.')
										');

		}

		if(Tools::getValue('from_wizard') == 1){
			Configuration::updateValue(self::$_module_prefix.'_ALREADY_USE',1);
		}
	}
	protected function skipWizard() {
		Configuration::updateValue(self::$_module_prefix.'_ALREADY_USE',1);
		$this -> _cleanOutput();
		$this->_html .= 'toogleWizardPanel();';
		$this -> _echoOutput(true);
	}

	protected function saveAdvancedStyles($content = false) {
		if (file_put_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE, ($content ? $content : Tools::getValue('abg_css'))))
			$this->errors[] = $this->l('Successfully saved.');
		else
			$this->errors [] = $this->l('Error while saving configuration.');
	}

	protected function _postProcess() {
		if(Tools::isSubmit('Submit_Styles')){
			$this->saveAdvancedStyles();
		}

		if (Tools::getValue('getItem') && Tools::getValue('itemType') == 'groupcategories') {//type d'item renseigné dans l'input multiselect ajax
			$this -> _cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);

			if (!$query || strlen($query) < 1) {
				self::_cleanBuffer();
				die();
			}

			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this -> getGroupCategoriesOnLive($query, $limit, $start);
			$item_id_column = 'id_category';
			$item_name_column = 'name';
			if ($items)
				foreach ($items as $row)
					$this -> _html .= $row[$item_id_column] . '=' . $row[$item_name_column] . "\n";
			$this -> _echoOutput(true);
			die();
		}

		if (Tools::getValue('getItem') && Tools::getValue('itemType') == 'groupproducts') {//type d'item renseigné dans l'input multiselect ajax
			$this -> _cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);

			if (!$query || strlen($query) < 1) {
				self::_cleanBuffer();
				die();
			}

			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this -> getGroupProductsOnLive($query, $limit, $start);
			$item_id_column = 'id_product';
			$item_name_column = 'name';
			if ($items)
				foreach ($items as $row)
					$this -> _html .= $row[$item_id_column] . '=' . $row[$item_name_column] . "\n";
			$this -> _echoOutput(true);
			die();
		}

		if (Tools::getValue('getItem') && Tools::getValue('itemType') == 'groupmanufacturer') {//type d'item renseigné dans l'input multiselect ajax
			$this -> _cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);

			if (!$query || strlen($query) < 1) {
				self::_cleanBuffer();
				die();
			}

			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this -> getGroupManufacturersOnLive($query, $limit, $start);
			$item_id_column = 'id_manufacturer';
			$item_name_column = 'name';
			if ($items)
				foreach ($items as $row)
					$this -> _html .= $row[$item_id_column] . '=' . $row[$item_name_column] . "\n";
			$this -> _echoOutput(true);
			die();
		}

		if (Tools::getValue('getItem') && Tools::getValue('itemType') == 'groupsupplier') {//type d'item renseigné dans l'input multiselect ajax
			$this -> _cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);

			if (!$query || strlen($query) < 1) {
				self::_cleanBuffer();
				die();
			}

			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this -> getGroupSuppliersOnLive($query, $limit, $start);
			$item_id_column = 'id_supplier';
			$item_name_column = 'name';
			if ($items)
				foreach ($items as $row)
					$this -> _html .= $row[$item_id_column] . '=' . $row[$item_name_column] . "\n";
			$this -> _echoOutput(true);
			die();
		}

		if (Tools::getValue('getItem') && Tools::getValue('itemType') == 'groupcms') {//type d'item renseigné dans l'input multiselect ajax
			$this -> _cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);

			if (!$query || strlen($query) < 1) {
				self::_cleanBuffer();
				die();
			}

			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this -> getGroupCmsOnLive($query, $limit, $start);
			$item_id_column = 'id_cms';
			$item_name_column = 'meta_title';
			if ($items)
				foreach ($items as $row)
					$this -> _html .= $row[$item_id_column] . '=' . $row[$item_name_column] . "\n";
			$this -> _echoOutput(true);
			die();
		}

		if (Tools::getValue('getItem') && Tools::getValue('itemType') == 'slidegroup') {//type d'item renseigné dans l'input multiselect ajax
			$this -> _cleanOutput();
			$item = Tools::getValue('itemType');
			$query = Tools::getValue('q', false);

			if (!$query || strlen($query) < 1) {
				self::_cleanBuffer();
				die();
			}

			$limit = Tools::getValue('limit', 100);
			$start = Tools::getValue('start', 0);
			$items = $this -> getSlidegroupOnLive($query, $limit, $start);
			$item_id_column = 'id_group';
			$item_name_column = 'name';
			if ($items)
				foreach ($items as $row)
					$this -> _html .= $row[$item_id_column] . '=' . $row[$item_name_column] . "\n";
			$this -> _echoOutput(true);
		}

		// Check specials pages
		if(Tools::isSubmit('Submit_Group') && (_PS_VERSION_ < 1.4 ? isset($_POST['special_pages']) && $_POST['special_pages']!=='' : Tools::getIsset('special_pages')) && Tools::getValue('bool_spe')) {
			if (_PS_VERSION_ < 1.4) {
				$pages_array = explode(',', $_POST['special_pages']);
			} else {
				$pages_array = array_unique(Tools::getValue('special_pages'));
			}

			foreach ($pages_array as $path) {
				if (_PS_VERSION_ < 1.4) {
					$path = trim($path);
					if(!preg_match('#^[~:\#,%&_=\.\? \+\-/a-zA-Z0-9]*$#', $path)){
						$this->errors[] = $this->l('Error : Bad path format for').' \"'.$path.'\"';
						continue;
					}
				}

				switch (Tools::getValue('usertype')) {
						case 2:
							$string = ' AND NOT g.usertype = 1 '.(Tools::getValue('usergrouplive') ?' AND (b.id_group IN ('.implode(',',Tools::getValue('usergrouplive') ).')  OR ISNULL(b.id_group))':' AND ISNULL(b.id_group)');
							break;
						case 1:
							$string = ' AND NOT g.usertype = 2 ';
							break;

						default:
							$string = '';
							break;
				}
				$test = abg_core_class::Db_getRow('
				SELECT *
				FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_page` a
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` b ON (a.id_group = b.id_rule )
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.id_group = a.id_group '. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' )
				WHERE page = "'.$path.'" '.
				$string.
				( Tools::getValue('id_group')?'AND NOT a.id_group = '.Tools::getValue('id_group') : '').
				( Tools::getValue('date_start') == Tools::getValue('date_end')?' ':
				' AND (( "'.Tools::getValue('date_start').'" BETWEEN g.date_start AND g.date_end
				OR   "'.Tools::getValue('date_end').'" BETWEEN g.date_start AND g.date_end )
				OR g.date_start = g.date_end)'));

				if(is_array($test) && sizeof($test))
					$this->errors[] = $this->l('Error : the url').' \"'.$path.'\" '.
					$this->l('is already associate to a rule').' '.$this->l('for this users groups.');

			}
		}

		if (Tools::isSubmit('Submit_Group') && isset($_POST['categorieslive']) && sizeof($_POST['categorieslive'])) {
			switch (Tools::getValue('usertype')) {
					case 2:
						$string = ' AND NOT g.usertype = 1 '.(Tools::getValue('usergrouplive') ?' AND (b.id_group IN ('.implode(',',Tools::getValue('usergrouplive') ).')  OR ISNULL(b.id_group))':' AND ISNULL(b.id_group)');
						break;
					case 1:
						$string = ' AND NOT g.usertype = 2 ';
						break;

					default:
						$string = '';
						break;
			}
			foreach ($_POST['categorieslive'] as $cat) {
				$test = abg_core_class::Db_getRow('
				SELECT * FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcat` a
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` b ON (a.id_group = b.id_rule )
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.id_group = a.id_group '. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' )
				WHERE a.id_cat= '.(int)$cat.' '.
				$string
				.( Tools::getValue('id_group')?' AND NOT a.id_group = '.Tools::getValue('id_group') : ' ').
				( Tools::getValue('date_start') == Tools::getValue('date_end')?' ':
				' AND (( "'.Tools::getValue('date_start').'" BETWEEN g.date_start AND g.date_end
				OR   "'.Tools::getValue('date_end').'" BETWEEN g.date_start AND g.date_end )
				OR g.date_start = g.date_end)')
				);
				if(is_array($test) && sizeof($test)){
					$name = abg_core_class::Db_getRow('
					SELECT * FROM `'._DB_PREFIX_.'category_lang`
					WHERE id_category= "'.(int)$cat.'"
					AND id_lang = '.$this->_cookie->id_lang);
					$this->errors[] = $this->l('Error : the category').' \"'.$name['name'].'\" '.
					$this->l('is already associate to a rule').' '.$this->l('for this users groups.');
				}
			}
		}

		if (Tools::isSubmit('Submit_Group') && isset($_POST['productslive']) && sizeof($_POST['productslive'])) {
			switch (Tools::getValue('usertype')) {
					case 2:
						$string = ' AND NOT g.usertype = 1 '.(Tools::getValue('usergrouplive') ?' AND (b.id_group IN ('.implode(',',Tools::getValue('usergrouplive') ).')  OR ISNULL(b.id_group))':' AND ISNULL(b.id_group)');
						break;
					case 1:
						$string = ' AND NOT g.usertype = 2 ';
						break;

					default:
						$string = '';
						break;
			}
			foreach ($_POST['productslive'] as $cat) {
				$test = abg_core_class::Db_getRow('
				SELECT *
				FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idproduct` a
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` b ON (a.id_group = b.id_rule )
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.id_group = a.id_group '. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' )
				WHERE id_product = '.(int)$cat.' '.
				$string.
				( Tools::getValue('id_group')?'AND NOT a.id_group = '. Tools::getValue( 'id_group') : '').
				( Tools::getValue('date_start') == Tools::getValue('date_end')?' ':
				' AND (( "'.Tools::getValue('date_start').'" BETWEEN g.date_start AND g.date_end
				OR   "'.Tools::getValue('date_end').'" BETWEEN g.date_start AND g.date_end )
				OR g.date_start = g.date_end)')
				);

				if(is_array($test) && sizeof($test)){
					$name = abg_core_class::Db_getRow('
					SELECT * FROM `'._DB_PREFIX_.'product_lang`
					WHERE id_product = "'.(int)$cat.'"
					AND id_lang = '.$this->_cookie->id_lang);

					$this->errors[] = $this->l('Error : the product').' \"'.$name['name'].'\" '.
					$this->l('is already associate to a rule').' '.$this->l('for this users groups.');
				}
			}
		}

		if (Tools::isSubmit('Submit_Group') && isset($_POST['manufacturerslive']) && sizeof($_POST['manufacturerslive'])) {
			switch (Tools::getValue('usertype')) {
					case 2:
						$string = ' AND NOT g.usertype = 1 '.(Tools::getValue('usergrouplive') ?' AND (b.id_group IN ('.implode(',',Tools::getValue('usergrouplive') ).')  OR ISNULL(b.id_group))':' AND ISNULL(b.id_group)');
						break;
					case 1:
						$string = ' AND NOT g.usertype = 2 ';
						break;

					default:
						$string = '';
						break;
			}
			foreach ($_POST['manufacturerslive'] as $cat) {
				$test = abg_core_class::Db_getRow('
				SELECT *
				FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idman` a
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` b ON (a.id_group = b.id_rule )
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.id_group = a.id_group '. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' )
				WHERE id_manufacturer = '.(int)$cat.' '.
				$string.
				( Tools::getValue('id_group')?'AND NOT a.id_group = '. Tools::getValue( 'id_group') : '').
				( Tools::getValue('date_start') == Tools::getValue('date_end')?' ':
				' AND (( "'.Tools::getValue('date_start').'" BETWEEN g.date_start AND g.date_end
				OR   "'.Tools::getValue('date_end').'" BETWEEN g.date_start AND g.date_end )
				OR g.date_start = g.date_end)'));


				if(is_array($test) && sizeof($test)){
					$name = abg_core_class::Db_getRow('SELECT * FROM `'._DB_PREFIX_.'manufacturer` WHERE id_manufacturer = "'.(int)$cat.'" ');
					$this->errors[] = $this->l('Error : the manufacturer').' \"'.$name['name'].'\" '.
					$this->l('is already associate to a rule').' '.$this->l('for this users groups.');
				}
			}
		}

		if (Tools::isSubmit('Submit_Group') && isset($_POST['supplierslive']) && sizeof($_POST['supplierslive'])) {
			switch (Tools::getValue('usertype')) {
					case 2:
						$string = ' AND NOT g.usertype = 1 '.(Tools::getValue('usergrouplive') ?' AND (b.id_group IN ('.implode(',',Tools::getValue('usergrouplive') ).')  OR ISNULL(b.id_group))':' AND ISNULL(b.id_group)');
						break;
					case 1:
						$string = ' AND NOT g.usertype = 2 ';
						break;

					default:
						$string = '';
						break;
			}
			foreach ($_POST['supplierslive'] as $cat) {
				$test = abg_core_class::Db_getRow('
				SELECT *
				FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idsupp` a
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` b ON (a.id_group = b.id_rule )
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.id_group = a.id_group '. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' )
				WHERE id_supplier = '.(int)$cat.' '.
				$string.
				( Tools::getValue('id_group')?'AND NOT a.id_group = '. Tools::getValue( 'id_group') : '').
				( Tools::getValue('date_start') == Tools::getValue('date_end')?' ':
				' AND (( "'.Tools::getValue('date_start').'" BETWEEN g.date_start AND g.date_end
				OR   "'.Tools::getValue('date_end').'" BETWEEN g.date_start AND g.date_end )
				OR g.date_start = g.date_end)'));


				if(is_array($test) && sizeof($test)){
					$name = abg_core_class::Db_getRow('SELECT * FROM `'._DB_PREFIX_.'supplier` WHERE id_supplier = "'.(int)$cat.'" ');
					$this->errors[] = $this->l('Error : the supplier').' \"'.$name['name'].'\" '.
					$this->l('is already associate to a rule').' '.$this->l('for this users groups.');
				}
			}
		}

		if (Tools::isSubmit('Submit_Group') && isset($_POST['cmslive']) && sizeof($_POST['cmslive'])) {
			switch (Tools::getValue('usertype')) {
					case 2:
						$string = ' AND NOT g.usertype = 1 '.(Tools::getValue('usergrouplive') ?' AND (b.id_group IN ('.implode(',',Tools::getValue('usergrouplive') ).')  OR ISNULL(b.id_group))':' AND ISNULL(b.id_group)');
						break;
					case 1:
						$string = ' AND NOT g.usertype = 2 ';
						break;

					default:
						$string = '';
						break;
			}
			foreach ($_POST['cmslive'] as $cat) {
				$test = abg_core_class::Db_getRow('
				SELECT *
				FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcms` a
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` b ON (a.id_group = b.id_rule )
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.id_group = a.id_group '. (_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' )
				WHERE id_cms = '.(int)$cat.' '.
				$string.
				( Tools::getValue('id_group')?'AND NOT a.id_group = '.Tools::getValue( 'id_group') : '').
				( Tools::getValue('date_start') == Tools::getValue('date_end')?' ':
				' AND (( "'.Tools::getValue('date_start').'" BETWEEN g.date_start AND g.date_end
				OR   "'.Tools::getValue('date_end').'" BETWEEN g.date_start AND g.date_end )
				OR g.date_start = g.date_end)'));


				if(is_array($test) && sizeof($test)){
					$name = abg_core_class::Db_getRow('SELECT * FROM `'._DB_PREFIX_.'cms_lang` WHERE id_cms = "'.(int)$cat.'" AND id_lang = '.$this->_cookie->id_lang);
					$this->errors[] = $this->l('Error : the page').' \"'.$name['meta_title'].'\" '.
					$this->l('is already associate to a rule').' '.$this->l('for this users groups.');
				}
			}
		}

		if((Tools::isSubmit('Submit_slide')  || Tools::isSubmit('Submit_Group')) && isset($_POST['bg_color']) && $_POST['bg_color'][1] ) {
			$_POST['bg_color'] = $_POST['bg_color'][1] ;
			$color = $_POST['bg_color'] ;

			if(!preg_match('/^#[0-9a-fA-F]{6}$/', trim($color)))
				$this->errors[] = $this->l('Error : Bad hexadecimal format for').' \"'.$color.'\"';
		}

		if (isset($this->errors) && sizeof($this->errors)) {
			$this->_cleanOutput();
			$this->_displayErrorsJs(true);
			$this->_echoOutput(true);
		}

		parent::_postProcess();
	}

	protected function _loadCssJsLibraries() {
		parent::_loadCssJsLibraries();
	}

	protected function _preProcess() {
		parent::_preProcess();
	}

	public function _displayToggle(){
		$this->_html .= '
			<div id="ispro_ctn">
				<span id="ispro_lbl">'.$this->l('Professionnal Mode').' </span>
				<input id="ispro_cb" type="checkbox" '.($this->is_pro == 'true'?'checked':'').'>
			</div>


			<script type="text/javascript">
			    $jqPm(document).ready(function() {
			      $jqPm("#ispro_cb:checkbox").switchbutton();
				  var html_ct = $jqPm("#slide_infos").html();
				  $jqPm("#ispro_cb:checkbox").change(function(){

					$.ajax({
						url: "'.$this -> _base_config_url . '&pm_load_function=ajaxCookieSwitcher",
						data:{"status":$jqPm("#ispro_cb:checkbox").prop("checked")},
						success: function() {
							$jqPm("#slide_infos").slideToggle("medium");

					  		return;
						}
					});
				 });
			   });
			</script>';
	}
	
	public function initVar() {
		global $cookie;
		if(isset($this->_cookie->ispro))
			$this->is_pro = $this->_cookie->ispro;
	}

	public function ajaxCookieSwitcher($status){
		global $cookie;
		$status = Tools::getValue('status');
		$this->_cleanOutput();
		$this->_cookie->ispro = $status;
	}

	// Pour configurer le module
	public function getContent() {
		global $cookie;
		$this->_html .= '<div id="pm_backoffice_wrapper">';
		$this -> _displayTitle($this -> displayName);

		if ($this -> _checkPermissions()) {
			if (_PS_VERSION_ >= 1.5 && Shop::getContext() != Shop::CONTEXT_SHOP) {
				$this->_loadCssJsLibraries();
				$this->_html .= '<div class="module_error alert error">' . $this->l('You must select a specific shop in order to continue, you can\'t create/edit a background from the "all shop" or "group shop" context.'). '</div>';
			} else {
				if (Tools::getValue('makeUpdate')) {
					$this -> checkIfModuleIsUpdate(true);
				}
				//Check if module is update
				if (!$this -> checkIfModuleIsUpdate(false)) {
					$this->_html .= '
						<div class="warning warn clear"><p>' . $this->l('We have detected that you installed a new version of the module on your shop') . '</p>
							<p style="text-align: center"><a href="' . $this->_base_config_url . '&makeUpdate=1" class="button">' . $this->l('Please click here in order to finish the installation process') . '</a></p>
						</div>';
				} else {
					$this -> _preProcess();
					$this -> _loadCssJsLibraries();
					$this -> _postProcess();
					$this->initVar();

					if (!Configuration::get(self::$_module_prefix.'_ALREADY_USE')){
						$this->generateAdvancedCss();

						$this->_html .= '
						<div id="button-wizard">
						<a href="'.$this -> _base_config_url . '&pm_load_function=getWizardForm&class=ABG_Group_Class&pm_reload_after=displayGroupsTable&pm_js_callback=closeDialogIframe|toogleWizardPanel" class="button-bevel green open_on_dialog_iframe">
							<span class="gallery"></span> '.$this->l('Create my first background !').'
						</a><br />
						<span class="skip"><a class="ajax_script_load" href="'.$this -> _base_config_url . '&pm_load_function=skipWizard">'.$this->l('Skip wizard').'</a></span>
						</div>
						<div id="abg_hide_panel">';
					} else {
						$this->_showRating(true);
					}
					
					$this->_displayToggle();
					
					parent::getContent();
					
					$tabsPanelOptions = array(
						'id_panel' => 'ABG_Panel',
						'tabs' => array(
							array('url' => $this -> _base_config_url . '&pm_load_function=displayGroupPanel',
							'label' => $this->l('Manage Backgrounds Rules')),
							array('url' => $this -> _base_config_url . '&pm_load_function=displaySlidePanel', //la fonction qu'il faut appeler dans l'onglet
							'label' => $this->l('Manage Backgrounds')),
							array('url' => $this -> _base_config_url . '&pm_load_function=displayCZonePanel', //la fonction qu'il faut appeler dans l'onglet
							'label' => $this->l('Manage Click Zones')),
							array('url' => $this -> _base_config_url . '&pm_load_function=displayAdvancedStyles', //la fonction qu'il faut appeler dans l'onglet
							'label' => $this->l('Advanced Styles')),
					));

					$this -> _displayTabsPanel($tabsPanelOptions);
					if(!Configuration::get(self::$_module_prefix.'_ALREADY_USE'))
						$this->_html .= '</div>
						<script type="text/javascript">
						$jqPm(document).ready(function() {
							$jqPm("#abg_hide_panel").hide();
						});
						</script>';
					$this -> _pmClear();
					$this -> _displaySupport();
				}
			}
		}
		$this -> _pmClear();
		$this->_html .= '</div>';
		return $this -> _html;
	}

	protected function displayAdvancedStyles() {
		$this -> _startForm(array('id' => 'CssForm', 'iframetarget' => false));
		$this -> _displayTitle($this->l('Edit CSS Styles'));
		$this->_displayTextareaCodeMirror(array(
							'obj' => FALSE,
							'key' => 'abg_css',
							'mode'=>'css',
							'size'=>'95%',
							'data' =>  trim(@file_get_contents(dirname(__FILE__) . '/' . self::ADVANCED_CSS_FILE))
							 ));

		$this->_html .='<BR/>';
		$this -> _displaySubmit($this->l('Save'), 'Submit_Styles');
		$this -> _endForm(array('id' => 'CssForm'));
	}

	protected function displayCZonePanel() {
		$this->_showInfo( '
		<br>'.
			$this->l('You have the possibility to add some linked zones where visitors could click on.'). '<br>'.
			$this->l('Then, you have to associate this zone with a background or a rule.').'
		');

		$this -> _addButton(array(
						'text' => $this->l('Add a Click zone'),
						'href' => $this -> _base_config_url . '&pm_load_function=getZoneForm&class=ABG_cZone_Class&pm_reload_after=displayCZoneTable|displaySlidesTable&pm_js_callback=closeDialogIframe',
						'class' => 'open_on_dialog_iframe'
					));


		$this -> displayCZoneTable();
	}

	protected function displayGroupPanel() {
		$this->_showInfo( '
		<br>'.
			$this->l('You have to create some associations between pages of your website and your backgorunds.'). '<br>'.
			$this->l('So, start creating an association rule on this tab, and then, add as many picture as you want from the tab "Manage Background".').'
		');

		$this -> _addButton(array(
						'text' => $this->l('Add a new Rule'),
						'href' => $this -> _base_config_url . '&pm_load_function=getGroupForm&class=ABG_Group_Class&pm_reload_after=displayGroupsTable|displaySlidesTable&pm_js_callback=closeDialogIframe',
						'class' => 'open_on_dialog_iframe'
					));

		$this -> displayGroupsTable();
	}

	protected function displaySlidePanel() {
		$this->initVar();
		$this->_showInfo( '
		<br>'.
			$this->l('On this tab, you can add all picture you want to use on your website.').
			$this->l('Each background has to be associate to a rule to be displayed.'). '<br />'.'<br />
			<div id="slide_infos">'.
			$this->l('This module permits you to display your background depending on two methods: '). '<br />'
			.'<ul><li><b>'.$this->l('The CSS method').'</b><br />'.
			$this->l('Recommended method for integrators.').
			$this->l('You can use CSS3 properties and add many pictures to a rule to position them individually. Don\t handdle slideshow.').'</li>'.

			'<li><b>'.$this->l('The Javascript method').'</b><br />'.
			$this->l('it allows the display of the background in full screen, whatever resolution your visitors.It also allows changing the image according to timing of your choice with a fade effect.').'</li></ul>'
			.'<br /></div>'

		);
		if($this->is_pro == 'false')
			$this->_html .= '<script type="text/javascript"> $jqPm("#slide_infos").hide();</script>';

		$this -> _addButton(array(
						'text' => $this->l('Add a Background'),
						'href' => $this -> _base_config_url . '&pm_load_function=getSlideForm&class=ABG_Slide_Class&pm_reload_after=displaySlidesTable|displayGroupsTable&pm_js_callback=closeDialogIframe',
						'class' => 'open_on_dialog_iframe'
					));

		$this -> displaySlidesTable();
	}

	protected function sortGroups(){
		$this -> _displayTitle( $this->l('Sort Backgrounds') );
		$params = array(
					'elements' => $this -> getSlideFromGroup(Tools::getValue('id_group')),
					'destination_table' => 'pm_advanced_bg_idgroup_idslide',
					'field_to_update' => 'sort',
					'identifier' => 'id_sort');
		$this -> _displaySortPanel($params);
	}

	public function getSlideFromGroup($id_group) {
		global $cookie;
		$res =array() ;
		$group = new ABG_Group_Class($id_group);
		$data = self::DB_ExecuteS('SELECT * FROM  ' . _DB_PREFIX_ . 'pm_advanced_bg_idgroup_idslide WHERE  id_group =' .$id_group.' ORDER BY sort' );

		foreach ($data as $value) {
			$temp = new ABG_Slide_Class($value['id_slide']);

			$res[$value['id_sort']] = $temp->name.' ('.($temp->bg_type == 1?$this->l('CSS Background'):$this->l('Javascript Background')).')';
			$res[$value['id_sort']] .= '<img style="float: right" src="' .$this->_path. 'timthumb.php?src=./uploads/slides/'. $temp->image[$this->_cookie->id_lang] . '&h=50" alt="miniature_'.$temp->id.'" /><br class="clear" />';
		}
		return $res;
	}

	protected function displayCZoneTable(){
		$this -> _html .= '<div id="displayCZoneTable" rel="' . $this -> _base_config_url . '&pm_load_function=displayCZoneTable">
						 <table id="ZonesTable" cellspacing="0" cellpadding="0" class="display"  style="width:100%;">
	       					 <thead>
		    					<tr>
		                          <th width="20" style="text-align:center;">' . $this->l('ID') . '</th>
		                          <th style="width:auto;text-align:center;">' . $this->l('Title') . '</th>
		                          <th width="250" style="text-align:center;">' . $this->l('Display on') . '</th>
		                          <th width="50" style="text-align:center;">' . $this->l('Edit') . '</th>
		                    	  <th width="50" style="text-align:center;">' . $this->l('Delete') . '</th>
		                    	  <th width="50" style="text-align:center;">' . $this->l('Displayed') . '</th>
		                        </tr>
		                     </thead>';
		$this -> _html .= '<tbody>';
		$czone = ABG_cZone_Class::getZones();
		
		// recupère les items à afficher dans le datatable
		if (is_array($czone) && sizeof($czone)) {
			foreach ($czone as $zone){
				$zone_tmp = new ABG_cZone_Class($zone['id_czone']);
				$slides = $zone_tmp->getZoneSlides();
				$rules = $zone_tmp->getZoneRules();

				$string= NULL ;
				foreach ($slides as $value)
					$string .= '<img src="' .$this->_path. 'timthumb.php?src=./uploads/slides/'. $value['image'] . '&w=50" />';
				foreach ($rules as $value)
					$string .= ($string?', ':''). $value['name'] ;

				$this -> _html .= '<tr>
									<td style="text-align:center;">' . $zone['id_czone'] . '</td>
								 	<td style="text-align:center;">' . $zone['title'] . '</td>
									<td style="text-align:center;">' .($string?$string:$this->l('Undisplayed')).'</td>
									';

				$this -> _html .= '<td style="text-align:center;">';
				$this -> _addButton(array(
				'href' => $this -> _base_config_url . '&pm_load_function=getZoneForm&pm_reload_after=displaySlidesTable|displayCZoneTable&pm_js_callback=closeDialogIframe&class=ABG_Czone_Class&id_czone=' . (int)$zone['id_czone'],
				 'icon_class' => 'ui-icon ui-icon-pencil',
				 'class' => 'open_on_dialog_iframe',
				 'rel' => '800_500_1'
				 ));
				$this -> _html .= '</td><td style="text-align:center;">';
				$this -> _addButton(array('href' => $this -> _base_config_url . '&pm_reload_after=displayCZoneTable&pm_delete_obj=1&class=ABG_Czone_Class&id_czone=' . (int)$zone['id_czone'],
				 'icon_class' => 'ui-icon ui-icon-trash',
				 'class' => 'ajax_script_load',
				 'title' => addcslashes($this->l('Delete item #'), '"') . (int)$zone['id_czone'] . ' ?',
				 'onclick' => 'return confirm("'.$this->l('Do you really want to delete this item ?').'");')
				 );

				 $this -> _html .= '</td><td style="text-align:center;">
				 <a href="' . $this->_base_config_url . '&pm_load_function=StatusSwitcher&switchActivation=1&id_czone=' . $zone['id_czone'] . '" class = "ajax_script_load" >
				 <img src="'.$this->_path.'/img/module_' . ($zone['status'] ? 'install' : 'disabled') . '.png" id="imgActiveZoneClic' .$zone['id_czone']  . '" /></a>

				</td>	';
				$this -> _html .= '</tr>';
			}
		}
		$this -> _html .= '</tbody>
				</table>
		</div>';
		$this -> _initDataTable('ZonesTable');//id de la table
	}

	protected function displayGroupsTable(){
		$this -> _html .= '<div id="displayGroupsTable" rel="' . $this -> _base_config_url . '&pm_load_function=displayGroupsTable">
						 <table id="GroupsTable" cellspacing="0" cellpadding="0" class="display"  style="width:100%;">
	       					 <thead>
		    					<tr>
		                          <th width="20" style="text-align:center;">' . $this->l('ID') . '</th>
		                          <th style="width:auto;text-align:center;">' . $this->l('Name') . '</th>
		                          <th width="250" style="text-align:center;">' . $this->l('Display in') . '</th>
		                          <th width="50" style="text-align:center;">' . $this->l('Duplicate') . '</th>
		                          <th width="50" style="text-align:center;">' . $this->l('Sort') . '</th>
		                          <th width="50" style="text-align:center;">' . $this->l('Edit') . '</th>
		                    	  <th width="50" style="text-align:center;">' . $this->l('Delete') . '</th>
		                    	  <th width="50" style="text-align:center;">' . $this->l('Displayed') . '</th>
		                        </tr>
		                     </thead>';
		$this -> _html .= '<tbody>';
		$groups = ABG_Group_Class::getGroups();

		if (is_array($groups) && sizeof($groups)) {
			foreach ($groups as $group){

				$group_tmp =  new ABG_Group_Class ($group['id_group']);
				$string = ($group_tmp->categories?implode(', ',self::OneKeyArray($group_tmp->categories,'name')):'').
				($group_tmp->products_categories?implode(', ',self::OneKeyArray($group_tmp->products_categories,'name')):'').
				($group_tmp->products?', '.implode(', ', self::OneKeyArray($group_tmp->products,'name')):'').
				($group_tmp->manufacturers?', '.implode(', ', self::OneKeyArray($group_tmp->manufacturers,'name')):'').
				($group_tmp->cms?', '.implode(', ', self::OneKeyArray($group_tmp->cms,'meta_title')):'').
				($group_tmp->suppliers?', '.implode(', ', self::OneKeyArray($group_tmp->suppliers,'name')):'').
				($group_tmp->pages?', '.implode(', ', self::OneKeyArray($group_tmp->pages,'page')):'').
				($group_tmp->default_group?', '.$this->l('DEFAULT RULE'):'');
				$string = preg_replace('/^, /', '', $string);
				$string2 = ($group_tmp->usergroup?implode(', ', self::OneKeyArray($group_tmp->usergroup,'name')):'');

				switch ($group_tmp->usertype) {
					case 0:
						$string3 = $this->l('Everyone');
						break;
					case 1:
						$string3 = $this->l('Visitors');
						break;
					case 2:
						$string3 = ($string2? (string)$string2:$this->l('Customers'));
						break;
					default:
						$string3 = $this->l('Error');
						break;
				}

				$this -> _html .= '<tr>
									<td style="text-align:center;">' . $group['id_group'] . '</td>
								 	<td style="text-align:center;">' . $group['name'] . '</td>
									<td style="text-align:center;">' .($string?$string:$this->l('Undisplayed')).';<br> '.$this->l('FOR').': '.$string3 . '</td>
									';

				$this -> _html .= '<td style="text-align:center;">';
				$this -> _addButton(array(
								'href' => $this -> _base_config_url . '&duplicateGroup=1&pm_load_function=duplicateGroup&pm_reload_after=displaySlidesTable|displayGroupsTable&id_group=' . (int)$group['id_group'],
								'icon_class' => 'ui-icon ui-icon-extlink',
								'class' => 'ajax_script_load',
								'rel' => '800_500_1'));

				$this -> _html .= '</td><td style="text-align:center;">';
				$this -> _addButton(array(
								'href' => $this -> _base_config_url . '&pm_load_function=sortGroups&pm_js_callback=closeDialogIframe&class=ABG_Group_Class&id_group=' . (int)$group['id_group'],
								'icon_class' => 'ui-icon ui-icon-shuffle',
								'class' => 'open_on_dialog_iframe',
								'rel' => '800_500_1'));

				$this -> _html .= '</td><td style="text-align:center;">';
				$this -> _addButton(array('href' => $this -> _base_config_url . '&pm_load_function=getGroupForm&pm_reload_after=displaySlidesTable|displayGroupsTable&pm_js_callback=closeDialogIframe&class=ABG_Group_Class&id_group=' . (int)$group['id_group'], 'icon_class' => 'ui-icon ui-icon-pencil', 'class' => 'open_on_dialog_iframe', 'rel' => '800_500_1'));
				$this -> _html .= '</td><td style="text-align:center;">';
				$this -> _addButton(array('href' => $this -> _base_config_url . '&pm_reload_after=displaySlidesTable|displayGroupsTable&pm_delete_obj=1&class=ABG_Group_Class&id_group=' . (int)$group['id_group'],
				 'icon_class' => 'ui-icon ui-icon-trash',
				 'class' => 'ajax_script_load',
				 'title' => addcslashes($this->l('Delete item #'), '"') . (int)$group['id_group'] . ' ?',
				 'onclick' => 'return confirm("'.$this->l('Do you really want to delete this item ?').'");')
				 );


				 $this -> _html .= '</td><td style="text-align:center;">
				 <a href="' . $this->_base_config_url . '&pm_load_function=StatusSwitcher&switchActivation=1&id_group=' . $group['id_group'] . '" class = "ajax_script_load" >
				 <img src="'.$this->_path.'/img/module_' . ($group['activation'] ? 'install' : 'disabled') . '.png" id="imgActiveSlideshow' .$group['id_group']  . '" /></a>

				</td>	';
				$this -> _html .= '</tr>';
			}
		}
		$this -> _html .= '</tbody>
				</table>
		</div>';
		$this -> _initDataTable('GroupsTable');//id de la table
	}

	protected function duplicateGroup(){
		if( !(Tools::getValue('id_group')) || Tools::getValue('duplicateGroup')!=1 )
			return ;

		$this->_cleanOutput();
		$group_old = new ABG_Group_Class(Tools::getValue('id_group'));
		$iscopy = preg_match('/\-[0-9]+$/', $group_old->name);

		$group_new = $group_old;

		$group_new->id = NULL;
		if(!$iscopy)
			$group_new->name .= '-1';
		else{
			$fig = substr($group_new->name, strlen($group_new->name)-1);
			$group_new->name = str_replace('-'.$fig, '-'.strval(intval($fig)+1), $group_new->name);
		}
		$group_new->categorieslive = $group_old->categories;
		$group_new->productscategorieslive = $group_old->productscategorieslive;
		$group_new->productslive = $group_old->products;
		$group_new->manufacturerslive = $group_old->manufacturers;
		$group_new->supplierslive = $group_old->suppliers;
		$group_new->usergrouplive = $group_old->usergroup;
		$group_new->cmslive = $group_old->cms;
		$group_new->save();

		$this->_html .= 'reloadPanel("displayGroupsTable");reloadPanel("displaySlidesTable");';
		return $this->_html;
	}

	protected function displaySlidesTable(){
		$this -> _html .= '<div id="displaySlidesTable" rel="' . $this -> _base_config_url . '&pm_load_function=displaySlidesTable">
						 <table id="SlidesTable" cellspacing="0" cellpadding="0" class="display"  style="width:100%;">
	       					 <thead>
		    					<tr>
		                          <th width="20" style="text-align:center;">' . $this->l('ID') . '</th>
		                          <th style="width:auto;text-align:center;">' . $this->l('Name') . '</th>
		                          <th width="250" style="text-align:center;">' . $this->l('Thumb') . '</th>
		                          <th width="200" style="text-align:center;">' . $this->l('In Rule') . '</th>
		                          <th width="60" style="text-align:center;">' . $this->l('Edit') . '</th>
		                    	  <th width="60" style="text-align:center;">' . $this->l('Delete') . '</th>
		                    	  <th width="50" style="text-align:center;">' . $this->l('Displayed') . '</th>
		                        </tr>
		                     </thead>';
		$this -> _html .= '<tbody>';
		$slides = ABG_Slide_Class::getSlides(false, false, false, true);

		//recupère les items à afficher dans le datatable
		if (is_array($slides) && sizeof($slides)) {
			foreach ($slides as $slide){
				$sli = new ABG_Slide_Class($slide['id_slide']);


				$groups=array();
				foreach ($sli->groups as $key => $value)
					$groups[] = $value['name'];

				if(file_exists(dirname(__FILE__).'/uploads/slides/'. $slide['image']) && $slide['image'])
					$dim = getimagesize(dirname(__FILE__).'/uploads/slides/'. $slide['image']);
				$this -> _html .= '<tr>
									<td style="text-align:center;">' . $slide['id_slide'] . '</td>
								 	<td style="text-align:center;">' . $slide['name'] . '</td>
								 	<td style="text-align:center;"> <img src="' .$this->_path. 'timthumb.php?src=./uploads/slides/'. $slide['image'] . '&'.(isset($dim) && $dim[0]/$dim[1] > 16/7? 'w='.(string)200 :'h=100' ).'" /></td>
									<td style="text-align:center;">' . (sizeof($groups)?implode(', ',$groups):''). '</td>
									';

				$this -> _html .= '<td style="text-align:center;">';
				$this -> _addButton(array('href' => $this -> _base_config_url . '&pm_load_function=getSlideForm&pm_reload_after=displaySlidesTable|displayGroupsTable&pm_js_callback=closeDialogIframe&class=ABG_Slide_Class&id_slide=' . (int)$slide['id_slide'], 'icon_class' => 'ui-icon ui-icon-pencil', 'class' => 'open_on_dialog_iframe', 'rel' => '800_500_1'));
				$this -> _html .= '</td><td style="text-align:center;">';
				$this -> _addButton(array('href' => $this -> _base_config_url . '&pm_reload_after=displaySlidesTable|displayGroupsTable&pm_delete_obj=1&class=ABG_Slide_Class&id_slide=' . (int)$slide['id_slide'],
				 'icon_class' => 'ui-icon ui-icon-trash',
				 'class' => 'ajax_script_load',
				 'title' => addcslashes($this->l('Delete item #'), '"') . (int)$slide['id_slide'] . ' ?',
				 'onclick' => 'return confirm("'.$this->l('Do you really want to delete this item ?').'");')
				 );

				 $this -> _html .= '</td><td style="text-align:center;">
				 <a href="' . $this->_base_config_url . '&pm_load_function=StatusSwitcher&switchActivation=1&id_slide=' . $slide['id_slide'] .'" class = "ajax_script_load" >
				 			<img src="'.$this->_path.'/img/module_' . ($slide['activation'] ? 'install' : 'disabled') . '.png" id="imgActiveSlideshowElement' .$slide['id_slide']  . '" /></a>

				</td>	';
				$this -> _html .= '</tr>';
			}
		}
		$this -> _html .= '</tbody>
				</table>

		</div>';
		$this -> _initDataTable('SlidesTable');//id de la table
	}

	protected function StatusSwitcher() {
		$this->_cleanOutput();
		if(!isset($_GET['switchActivation']) || !$_GET['switchActivation'] )
			return ;

		if(isset($_GET['id_slide']) && $_GET['id_slide'] ){

			$slide = new ABG_Slide_Class($_GET['id_slide'] );
			$slide->activation = 1  - (int) $slide->activation;
			$slide->save();
			$this->_html .=  '$jqPm("#imgActiveSlideshowElement'.(int)$_GET['id_slide'].'").attr("src","'.$this->_path.'/img/module_' . ($slide->activation ? 'install' : 'disabled') . '.png");';
		}

		if(isset($_GET['id_group']) && $_GET['id_group'] ){

			$group = new ABG_Group_Class($_GET['id_group'] );
			$group->activation = 1  - (int) $group->activation;
			$group->save();
			$this->_html .=  '$jqPm("#imgActiveSlideshow'.(int)$_GET['id_group'].'").attr("src","'.$this->_path.'/img/module_' . ($group->activation ? 'install' : 'disabled') . '.png");';
		}

		if(isset($_GET['id_czone']) && $_GET['id_czone'] ){

			$zone = new ABG_cZone_Class($_GET['id_czone']);
			$zone->status = 1  - (int) $zone->status;
			$zone->save();
			$this->_html .=  '$jqPm("#imgActiveZoneClic'.(int)$_GET['id_czone'].'").attr("src","'.$this->_path.'/img/module_' . ($zone->status ? 'install' : 'disabled') . '.png");';
		}

		$this->_echoOutput(TRUE);
		return ;
	}

	protected function getZoneForm($params) {
		$this->initVar();
		unset($this->_css_js_to_load[array_search("template", $this->_css_js_to_load)]);
		$this -> _startForm(array('id' => 'zoneForm', 'obj' => $params['obj'], 'params' => $params));
		if (is_object($params['obj']) && isset($params['obj']->id) && $params['obj']->id) {
			$this -> _displayTitle($this->l('Edit a click zone'));
		} else {
			$this -> _displayTitle($this->l('Add a new click zone'));
		}
		$this->_html .='<br/><br/>';

		$this -> _displayInputTextLang(array(
									'obj' => $params['obj'],
									'key' => 'title',
									'label' => $this->l('Title appearing when the cursor is over the zone'),
									'required' => true,
									'size' => '360px',
								));

		$this -> _displayInputTextLang(array(
									'obj' => $params['obj'],
									'key' => 'href',
									'label' => $this->l('Link'),
									'required' => true,
									'size' => '360px',
									'tips' => $this->l('The page where the link is pointing on. (the href attribute)'),
								));

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'status',
										'key_db' => 'status',
										'label' => $this->l('Activate'),
										'defaultvalue' => 1,
										'tips' => $this->l('If you choose "yes", this zone will be will be active.'),
									));

		$this -> _displayCheckboxOverflow(array(
										'obj' => $params['obj'],
										'key' => 'slides',
										'label' => $this->l('Select a background to associate the zone with'),
										'options' => $this -> getSlidesAsArray(true), //array (name,value)
										'tips' => $this->l('Choose in which background will be displayed this zone.')
									));

		/*$this -> _displayCheckboxOverflow(array(
										'obj' => $params['obj'],
										'key' => 'rules',
										'label' => $this->l('Select a rule to associate the zone with'),
										'options' => $this -> getRulesAsArray(), //array (name,value)
										'tips' => $this->l('Choose in which rule will be displayed this zone.')
									));
		*/

		$this-> _startFieldset($this->l('Display options'), false, false);
		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'position',
										'key_db' => 'position',
										'label' => $this->l('Zone fixed'),
										'defaultvalue' => 1,
										'tips' => $this->l('If you choose "yes", the zone do not move when you will scroll.'),
									));

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'border',
										'key_db' => 'border',
										'label' => $this->l('Border for development'),
										'defaultvalue' => 1,
										'tips' => $this->l('If you want to see the zone, choose "yes" and the zone will be display with a black border. Thanks to this, you can display the zone precisely and then disable the border.'),
										'onclick' => 'display_border_color();',
									));
		$this->_html .='<div id="border_color">';
		$this ->_displayInputGradient(array(
									'obj' => $params['obj'],
									'key' => 'color',
									'label' => $this->l('Border color'),
									'tips' => $this->l('Select the border color.'),
									'gradient' => FALSE
								));

		$this->_html .='</div>
		<script type="text/javascript">
			$jqPm(document).ready(function() { display_border_color(); });
		</script>';

		$this->_displayHeightField(array(
											'obj' => $params['obj'],
											'label' => $this->l('Width'),
											'key' => 'width',
											'required' => TRUE,
											'tips' => $this->l('Choose the width of the area.'),
											'defaultvalue' => 1,
										));
		$this->_displayHeightField(array(
											'obj' => $params['obj'],
											'label' => $this->l('Height'),
											'key' => 'height',
											'required' => TRUE,
											'tips' => $this->l('Choose the height of the area.'),
											'defaultvalue' => 1,
										));

		/*$this -> _displaySelect(array(
								'obj' => $params['obj'],
								'label' => $this->l('Side to display the zone'),
								'key' => 'side',
								'options' => array(
											1 => $this->l('Left side'),
											2 => $this->l('Right side'),
											3 => $this->l('Above'),
											4 => $this->l('Below'),
											),
								'required' => true,
								'tips' => $this->l('Choose in which users type will be allowed to see this rule.')
							));	*/

		$this->_displayInputText(array(
											'obj' => $params['obj'],
											'label' => $this->l('Left margin(px)'),
											'key' => 'marginLeft',
											'required' => true,
											'tips' => $this->l('Define the margin between the page and the area.'),
											'size' => '50px'
										));

		$this->_displayInputText(array(
											'obj' => $params['obj'],
											'label' => $this->l('Top margin(px)'),
											'key' => 'marginTop',
											'required' => true,
											'tips' => $this->l('Define the margin between the top and the area.'),
											'size' => '50px'
										));
		$this-> _endFieldset();
		$this->_html .= '<br>';
		$this -> _displaySubmit($this->l('Save'), 'Submit_Zone');
		$this -> _endForm(array('id' => 'zoneForm'));
	}

	protected function getGroupForm($params, $wizard = false) {
		$this->initVar();
		unset($this->_css_js_to_load[array_search("template", $this->_css_js_to_load)]);
		$this -> _startForm(array('id' => 'groupForm', 'obj' => $params['obj'], 'params' => $params));
		
		if ($wizard) {
			$this -> _displayTitle($this->l('First Steps with the module'));
			// Default group because it's the first background, apply on all pages
			$params['obj']->default_group = 1;
			$params['obj']->activation = 1;
			$this->_html .='<input type="hidden" name="from_wizard" value="1" />';
		} else {
			if (is_object($params['obj']) && isset($params['obj']->id) && $params['obj']->id) {
				$this -> _displayTitle($this->l('Edit a rule for a group of backgrounds'));
			} else {
				$this -> _displayTitle($this->l('Add a new rule for a group of backgrounds'));
			}
		}
		$this->_html .='<br/><br/>';

		$this -> _displayInputText(array(
									'obj' => $params['obj'],
									'key' => 'name',
									'label' => $this->l('Name of the rule'),
									'required' => true,
									'tips' => $this->l('Give a name to this set of Backgrounds. Example : "Category X bg" or "Christmas bg"'),
								));

		if ($wizard) $this->_html .= '<div style="display: none">';
			$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'activation',
										'key_db' => 'activation',
										'label' => $this->l('Activate'),
										'defaultvalue' => 1,
										'tips' => $this->l('If you choose "yes", this rule will be will be active.'),
									));

			$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'default_group',
										'key_db' => 'default_group',
										'label' => $this->l('Apply everywhere ?'),
										'onclick' => 'display_assos();',
										'defaultvalue' => 0,
										'tips' => $this->l('This rule will apply on every page of your website'),
									));
		if ($wizard) $this->_html .= '</div>';

		if (!$wizard) $this->_html .= '<div style="display: none">';
		$this -> _displayInputFileLang(array(
										'obj' => $params['obj'],
										'key' => 'image',
										'label' => $this->l('Upload a background'),
										'destination' => '/uploads/slides/',
										'required' => false,
										'tips' => $this->l('You can upload a picture from your hard disk. This picture could be different for each language.'),
										'extend'=> true
									));
		if (!$wizard) $this->_html .= '</div>';

		if ($wizard) $this->_html .= '<div style="display: none">';
		
		$this-> _startFieldset($this->l('Backgrounds Options'))	;
		$this->_html .='<div class="abg_bo_indent">';

		$this ->_displayInputGradient(array(
									'obj' => $params['obj'],
									'key' => 'bg_color',
									'label' => $this->l('Background color'),
									'tips' => $this->l('Select the background color.'),
								));

		$this -> _displayInputText(array(
									'obj' => $params['obj'],
									'key' => 'delay',
									'label' => $this->l('Delay of display'),
									'required' => true,
									'defaultvalue' => '5000',
									'tips' => $this->l('How long each backgrounds will appears. This doesn\'t concern the CSS background or if there is only one Javascript Backgrounds in this rule.'),
								));

		$this ->_displayRadioFileList(
			array(
				'obj' => $params['obj'],
				'key' => 'overlay',
				'label' => $this->l('Texture apply over backgrounds'),
				'dir' => _PS_ROOT_DIR_ . '/modules/' . $this->name . '/img/overlays/',
				'imgdir' => $this->_path . '/img/overlays/',
				'group' => array('texture')
			)
		);

		$this->_html .='</div>';
		$this-> _endFieldset()	;
		$this-> _startFieldset($this->l('Activation periode'))	;
		$this->_html .='<div class="abg_bo_indent">';

		$this -> _displayInputDatePicker(array(
											'obj' => $params['obj'],
											'key' => 'date_start',
											'label' => $this->l('Start Date'),
											'defaultvalue' => (string)date('Y-m-d 00:00:00'),
											'required' => true,
											'tips' => $this->l('The date to begin to display this rule.')
										));

		$this -> _displayInputDatePicker(array(
		'obj' => $params['obj'],
		'key' => 'date_end',
		'label' => $this->l('End Date'),
		'defaultvalue' => (string)date('Y-m-d 00:00:00'),
		'required' => true,
		'tips' => $this->l('The date to stop to display the rule.').'<br>'.
		$this->l(' NOTE : ').'<br>'.
		$this->l(' If you set the same date as "start date", the rule will always be displayed.')));

		$this->_html .='</div>';
		$this-> _endFieldset()	;
		$this-> _startFieldset($this->l('Associations'))	;

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_cat',
										'key_db' => 'bool_cat',
										'label' => $this->l('Apply this rule to some categories'),
										'defaultvalue' => (sizeof($params['obj'] -> categories)?1:0),
										'onclick' => 'display_cat_picker();'
									));

		$this -> _html .= '<div id="category_picker" class="abg_bo_indent">';
		// Categories for product_picker
		$this->_displayCategoryTree(array(
			'label' => $this->l('Categories'),
			'input_name' => 'categorieslive',
			'selected_cat' => (sizeof($params['obj'] -> categories)?$params['obj'] -> categories:array(0)),
			'category_root_id' => (_PS_VERSION_ >= 1.5 ? Category::getRootCategory()->id_category : 1)
		));
		$this -> _html .= '</div>';
		
		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_prod_cat',
										'key_db' => 'bool_prod_cat',
										'label' => $this->l('Apply this rule to products into these categories'),
										'defaultvalue' => (sizeof($params['obj'] -> products_categories)?1:0),
										'onclick' => 'display_prod_cat_picker();'
									));

		$this -> _html .= '<div id="products_category_picker" class="abg_bo_indent">';
		$this->_displayCategoryTree(array(
			'label' => $this->l('Categories'),
			'input_name' => 'productscategorieslive',
			'selected_cat' => (sizeof($params['obj'] -> products_categories)?$params['obj'] -> products_categories:array(0)),
			'category_root_id' => (_PS_VERSION_ >= 1.5 ? Category::getRootCategory()->id_category : 1)
		));
		$this -> _html .= '</div>';

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_prod',
										'key_db' => 'bool_prod',
										'label' => $this->l('Apply this rule to some products'),
										'defaultvalue' => (sizeof($params['obj'] -> products)?1:0),
										'onclick' => 'display_prod_picker();'
									));

		$this -> _html .= '<div id="product_picker" class="abg_bo_indent">';
		//multiselect table
		$this -> _displayAjaxSelectMultiple(array(
					'selectedoptions' => $params['obj'] -> products, //retourne le tableau associatif de ce qu'on veut afficher dans le tableau (à gauche)
					'key' => 'productslive',
					'label' => $this->l('Products'),
					'remoteurl' => $this -> _base_config_url . '&getItem=1&itemType=groupproducts',//postprocess
					'limit' => 50,
					'limitincrement' => 20,
					'remoteparams' => false,
					// New parameters
					'idcolumn' => 'id_product',
					'namecolumn' => 'name',
					'triggeronliclick' => true,
					'displaymore' => true,
				));
		$this -> _html .= '</div>';

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_manu',
										'key_db' => 'bool_manu',
										'label' => $this->l('Apply this rule to some manufacturers'),
										'defaultvalue' => (sizeof($params['obj'] -> manufacturers)?1:0),
										'onclick' => 'display_manu_picker();'
									));

		$this -> _html .= '<div id="manu_picker" class="abg_bo_indent" >';
		$this -> _displayAjaxSelectMultiple(array(
					'selectedoptions' => $params['obj'] -> manufacturers, //retourne le tableau associatif de ce qu'on veut afficher dans le tableau (à gauche)
					'key' => 'manufacturerslive',
					'label' => $this->l('Manufacturers'),
					'remoteurl' => $this -> _base_config_url . '&getItem=1&itemType=groupmanufacturer',//postprocess
					'limit' => 50,
					'limitincrement' => 20,
					'remoteparams' => false,
					'idcolumn' => 'id_manufacturer',
					'namecolumn' => 'name',
					'triggeronliclick' => true,
					'displaymore' => true,
				));
		$this -> _html .= '</div>';

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_supp',
										'key_db' => 'bool_supp',
										'label' => $this->l('Apply this rule to some suppliers'),
										'defaultvalue' => (sizeof($params['obj']->suppliers)?1:0),
										'onclick' => 'display_supp_picker();'
									));

		$this -> _html .= '<div id="supp_picker" class="abg_bo_indent" >';
		$this -> _displayAjaxSelectMultiple(array(
					'selectedoptions' => $params['obj']->suppliers, //retourne le tableau associatif de ce qu'on veut afficher dans le tableau (à gauche)
					'key' => 'supplierslive',
					'label' => $this->l('Suppliers'),
					'remoteurl' => $this -> _base_config_url . '&getItem=1&itemType=groupsupplier',//postprocess
					'limit' => 50,
					'limitincrement' => 20,
					'remoteparams' => false,
					'idcolumn' => 'id_supplier',
					'namecolumn' => 'name',
					'triggeronliclick' => true,
					'displaymore' => true,
				));
		$this -> _html .= '</div>';

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_cms',
										'key_db' => 'bool_cms',
										'label' => $this->l('Apply this rule to some CMS pages'),
										'defaultvalue' => (self::_isFilledArray($params['obj']->cms) ?true:false),
										'onclick' => 'display_cms_picker();'
									));

		$this -> _html .= '<div id="cms_picker" class="abg_bo_indent" >';
		$this -> _displayAjaxSelectMultiple(array(
					'selectedoptions' => (self::_isFilledArray($params['obj']->cms) ?$params['obj']->cms:array()), //retourne le tableau associatif de ce qu'on veut afficher dans le tableau (à gauche)
					'key' => 'cmslive',
					'label' => $this->l('CMS Pages'),
					'remoteurl' => $this -> _base_config_url . '&getItem=1&itemType=groupcms',//postprocess
					'limit' => 50,
					'limitincrement' => 20,
					'remoteparams' => false,
					'idcolumn' => 'id_cms',
					'namecolumn' => 'meta_title',
					'triggeronliclick' => true,
					'displaymore' => true,
				));
		$this -> _html .= '</div>';

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bool_spe',
										'key_db' => 'bool_spe',
										'label' => $this->l('Apply this rule to some special pages'),
										'defaultvalue' => (sizeof($params['obj']->pages) ?true:false),
										'onclick' => 'display_spe_picker();'
									));



		$this->_html .='<div class="abg_bo_indent" id="special_pages">';

		if (_PS_VERSION_ < 1.4) {
			$this -> _html .= '
				<label>Chemins des pages (s&eacute;par&eacute;s par des virgules)</label>
				<div class="margin-form">
					<small style="float:left;padding-top:3px;">http://'._DB_SERVER_.__PS_BASE_URI__.'</small>
					<input id="special_pages" class="ui-corner-all ui-input-pm" type="text" value="'.(sizeof($params['obj']->pages) ?implode(', ', self::OneKeyArray($params['obj']->pages,'page')):'').'" name="special_pages" style="width:200px">
					<img title="'.$this->l('If you want to apply this rule to some special pages, write their path here separating by comas. ').$this->l('Example :  ').'home , my_shop/mypage , category/form.php" id="special_pages-tips" class="pm_tips" width="16px" height="16px" src="'.$this->_path.'img/question.png">
					<script type="text/javascript">initTips("#special_pages");</script>
					<div class="clear"></div>
				</div>';
		} else {
			if ($params['obj']->id) {
				$specialPagesAssociation = $params['obj']->getSpecialPages(true, $this->_cookie->id_lang);
			} else {
				$specialPagesAssociation = array();
			}
			// Choix des controllers
			$this -> _html .= '<div id="controller_picker">';
			$this -> _displayAjaxSelectMultiple(array(
				'selectedoptions' => $specialPagesAssociation,
				'key' => 'special_pages',
				'label' => $this->l('Select the controller where you want the search engine to be shown'),
				'remoteurl' => $this -> _base_config_url . '&getItem=1&itemType=controller',
				'limit' => 50,
				'limitincrement' => 20,
				'remoteparams' => false,
				'idcolumn' => 'page',
				'namecolumn' => 'title',
				'triggeronliclick' => true,
				'displaymore' => true,
			));
			$this -> _html .= '</div>';
		}

		$this -> _html .= '</div>';
		$this-> _endFieldset()	;
		$this-> _startFieldset($this->l('Restrictions'))	;

		$this -> _displaySelect(array(
								'obj' => $params['obj'],
								'label' => $this->l('Users concerned by this rule'),
								'key' => 'usertype',
								'options' => array(
											0 => $this->l('all'),
											1 => $this->l('Visitors only'),
											2 => $this->l('Customers only'), ),
								'onchange'=> (_PS_VERSION_ >= 1.2?'display_usergroup_cb();' : ''),
								'required' => true,
								'tips' => $this->l('Choose in which users type will be allowed to see this rule.')
							));

		$this->_html .='<div class="abg_bo_indent" id="users_groups">';

		if(_PS_VERSION_ >= 1.2)
			$this -> _displayCheckboxOverflow(array(
										'obj' => $params['obj'],
										'key' => 'usergrouplive',
										'label' => $this->l('Customers Groups (All by default)'),
										'options' => $this -> getUserGroupAsArray(), //array (name,value)
										'tips' => $this->l('Choose in which users group will be allowed to see this rule.').
										$this->l('If you don\'t check any groups, every customer will be concerned. (all uncheck = all check)')
									));

		$this -> _html .= '</div>';
		$this-> _endFieldset()	;

		if(!$wizard && 'false' === $this->is_pro )
			$this->_html .='

			<style type="text/css">
			#groupForm>.margin-form:nth-of-type(2),#groupForm>label:nth-of-type(2),fieldset:nth-of-type(4),fieldset:nth-of-type(1){
				display:none;
			}
			fieldset:nth-of-type(2){
				margin-top : -40px;
			}
			</style>';

		$this->_html .='<br/><br/>';
		
		if ($wizard) $this->_html .= '</div>';

		$this->_html .='
		<script type="text/javascript">
			display_cms_picker();
			display_spe_picker();
			display_cat_picker();
			display_prod_cat_picker();
			display_prod_picker();
			display_manu_picker();
			display_supp_picker();
			'.(_PS_VERSION_ < 1.2? '':'display_usergroup_cb();').'
			display_assos();

		</script>';
		$this -> _displaySubmit($this->l('Save'), 'Submit_Group');
		$this -> _endForm(array('id' => 'groupForm'));
	}

	protected function getWizardForm($params) {
		$this->getGroupForm($params, true);
	}
	
	protected function getSlideForm($params) {
		$this->initVar();
		$this -> _startForm(array('id' => 'slideForm', 'obj' => $params['obj'], 'params' => $params));
		if (is_object($params['obj']) && isset($params['obj']->id) && $params['obj']->id) {
			$this -> _displayTitle($this->l('Edit a background'));
		} else {
			$this -> _displayTitle($this->l('Add a new background'));
		}

		$this->_html .='<br><div id="header_pro">';
		$this -> _displayInputText(array(
									'obj' => $params['obj'],
									'key' => 'name',
									'label' => $this->l('Name of the Background'),
									'required' => true,
									'tips' => $this->l('Give a name to this background.'),
								));

		$this -> _displaySelect(array(
								'obj' => $params['obj'],
								'label' => $this->l('Background Type'),
								'key' => 'bg_type',
								'options' => array(
											'' => $this->l('Choose a type'),
											1 => $this->l('CSS Background'),
											2=> $this->l('Javascript Background'), ),
								'onchange'=> 'display_formByType();',
								'required' => true,
								'tips' => $this->l('CSS Backgrounds are not animate and not resized. Javascript Backgrounds are fullscreen resized and they define a slideshow.')
							));

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'activation',
										'key_db' => 'activation',
										'label' => $this->l('Activate'),
										'defaultvalue' => 1,
										'tips' => $this->l('If you choose "yes", this background will be will be active.'),
									));

		$this->_html .='</div>';
		$this -> _displayCheckboxOverflow(array(
										'obj' => $params['obj'],
										'key' => 'groupslive',
										'label' => $this->l('Rules'),
										'options' => $this -> getGroupAvailableAsArray(), //array (name,value)
										'tips' => $this->l('Choose in which rules the background will be diplayed.').'<br>'.
										$this->l('Note:').'<br>'.
										$this->l('Rules in italic are not actives.')
									));

		if (_PS_VERSION_ >= 1.5 && !Shop::isFeatureActive()) {
			$shop = Context::getContext()->shop;
			if ($_SERVER['HTTP_HOST'] != $shop->domain && $_SERVER['HTTP_HOST'] != $shop->domain_ssl) {
				$this->_showWarning($this->l('You are currently connected under the following domain name:').' <span style="color: #CC0000;">'.$_SERVER['HTTP_HOST'].'</span><br />' .
				$this->l('This is different from the main shop domain name set in the "Multistore" page under the "Advanced Parameters" menu:').' <span style="color: #CC0000;">'.$shop->domain.'</span><br />' . 
				$this->l('Please use the same domain in order to continue'));
				$this -> _endForm(array('id' => 'slideForm'));
				return;
			}
		}
		
		$this -> _displayInputFileLang(array(
										'obj' => $params['obj'],
										'key' => 'image',
										'label' => $this->l('Upload a background'),
										'destination' => '/uploads/slides/',
										'required' => false,
										'tips' => $this->l('You can upload a picture from your hard disk. This picture could be different for each language.'),
										'extend'=> (isset($params['obj']->id)?FALSE:true)
									));

		$this-> _startFieldset($this->l('Background Options'),false,true,'display_formByType();');

		$this-> _html .= '<div id="pm_bg_slide_form">';
		$this -> _displayInputText(array(
									'obj' => $params['obj'],
									'key' => 'bg_halign',
									'label' => $this->l('Horizontal alignment (percent)'),
									'defaultvalue' => 50,
									'required' => true,
								));

		$this -> _displayInputText(array(
									'obj' => $params['obj'],
									'key' => 'bg_valign',
									'label' => $this->l('Vertical alignment (percent)'),
									'defaultvalue' => 50,
									'required' => true,
								));

		$this -> _displayInputText(array(
									'obj' => $params['obj'],
									'key' => 'fade_time',
									'label' => $this->l('Time of fade transistion'),
									'defaultvalue' => 2000,
									'required' => true,
									'tips' => $this->l('The duration of the fade transistion'),
								));

		$this-> _html .= '</div><div id="pm_bg_static_form">';

		$this->_displayBackgroundPosition(array(
											'obj' => $params['obj'],
											'label' => $this->l('Background position'),
											'key' => 'bg_position',
											'required' => TRUE,
											'tips' => 'Choose where you want to display the background.',
											'defaultvalue' => ($this->is_pro != 'false'?0:2),
										));

		$this -> _displaySelect(array(
								'obj' => $params['obj'],
								'label' => $this->l('Background repeat'),
								'key' => 'bg_repeat',
								'options' => array(
											'repeat' => 'repeat',
											'repeat-x' => 'repeat-x',
											'repeat-y' => 'repeat-y',
											'no-repeat' => 'no-repeat'  ),
								'defaultvalue' => 'no-repeat',
								'required' => true,
								'tips' => $this->l('If you want to repeat the background.').
								'Repeat-x : '.$this->l('horizontal repeat').'<br>'.
								'Repeat-y : '.$this->l('vertical repeat').'<br>'.
								'Repeat : '.$this->l('horizontal and vertical repeat')
							));
		$this-> _html .= '</div>';

		$this -> _displayInputActive(array(
										'obj' => $params['obj'],
										'key_active' => 'bg_fixed',
										'key_db' => 'bg_fixed',
										'label' => $this->l('Background fixed'),
										'defaultvalue' => 1,
										'tips' => $this->l('If you choose "yes", the background do not move when you will scroll.'),
									));

		$this-> _endFieldset()	;
		$this-> _html .= '<br />';
		$this-> _startFieldset($this->l('Activation periode'))	;
		$this -> _displayInputDatePicker(array(
											'obj' => $params['obj'],
											'key' => 'date_start',
											'label' => $this->l('Start Date'),
											'defaultvalue' => (string)date('Y-m-d 00:00:00'),
											'required' => true,
											'tips' => $this->l('The date to begin to display this background.')
										));
		$this -> _displayInputDatePicker(array(
		'obj' => $params['obj'],
		'key' => 'date_end',
		'label' => $this->l('End Date'),
		'defaultvalue' => (string)date('Y-m-d 00:00:00'),
		'required' => true,
		'tips' => $this->l('The date to stop to display the background. ').'<br>'.
										$this->l('NOTE :').'<br>'.
										$this->l(' If you set the same date as "start date", the background will always be displayed.')));
		$this-> _endFieldset();

		if('false' === $this->is_pro)
			$this->_html .='<script type="text/javascript">
				$jqPm("fieldset").hide();
				$jqPm("#header_pro").hide();
				$jqPm(".margin-form:eq(1) select").val(2);
				$jqPm(".margin-form:eq(0) input").val("'.$this->l('picture').'_'.(isset($params['obj']->id)?(string)$params['obj']->id:'auto').'");
				$jqPm("input[name=\"bg_position_percent[]\"]").val("50");
			</script>';

		$this->_html .='<br/>';
		$this -> _displaySubmit($this->l('Save'), 'Submit_slide');
		$this -> _endForm(array('id' => 'slideForm'));
	}

	public function getSlideZones($id_group,$actif=false){
		global $cookie;
		return( json_encode(abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_clickzone` s,`'._DB_PREFIX_.'pm_advanced_bg_clickzone_lang` sl, `'._DB_PREFIX_.'pm_advanced_bg_idslide_idczone` sz
			WHERE s.id_czone = sl.id_czone
			AND s.id_czone = sz.id_czone
			AND sl.id_lang = '.(isset($this->_cookie->id_lang)?$this->_cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			AND sz.id_slide = '.$id_group.'
			'.($actif?'
			AND s.status = 1
			':'')
			)));
	}
	
	private function isMobileBrowser() {
		if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$_SERVER['HTTP_USER_AGENT'])||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($_SERVER['HTTP_USER_AGENT'],0,4)))
			return true;
		return false;
	}
	
	private function isIncompatibleBrowser() {
		preg_match('/MSIE ([0-9]+)/', $_SERVER['HTTP_USER_AGENT'],$test);
		return (isset($test[1]) && intval($test[1]) < 7);
	}

	public function hookHeader(){
		global $smarty;
		if ($this->isIncompatibleBrowser() || $this->isMobileBrowser() || Tools::getValue('content_only') == 1) return '';
		
		$include_customer_groups = false;
		$customer_groups = array();

		if (_PS_VERSION_ >= 1.5){
			if ($this->_context->customer->id_default_group != 1) {
				$customer_groups = $this->_context->customer->getGroups();
				$include_customer_groups = true;
			}
		} elseif (_PS_VERSION_ >= 1.4){
			if (isset($this->_cookie->logged)) {
				$customer = new Customer($this->_cookie->id_customer);
				$customer_groups = $customer->getGroups();
				$include_customer_groups = true;
			}
		} elseif (_PS_VERSION_ >= 1.3){
			if (isset($this->_cookie->logged)) {
				$customer = new Customer($this->_cookie->id_customer);
				$customer_groups = $customer->getGroups();
				$include_customer_groups = true;
			}
		} elseif (_PS_VERSION_ >= 1.2){
			if (isset($this->_cookie->logged)) {
				$customer = new Customer($this->_cookie->id_customer);
				$customer_groups = $customer->getGroups();
				$include_customer_groups = true;
			}
		}

		if (self::_isFilledArray($customer_groups))
			$customer_groups = implode(',', $customer_groups);

		if ($_SERVER['SCRIPT_NAME'] == __PS_BASE_URI__.'index.php' && (!isset($_GET['controller']) || (isset($_GET['controller']) && $_GET['controller'] == 'index'))) {
			$curIdCategory = (_PS_VERSION_ < 1.5 ? 1 : Category::getRootCategory()->id_category ) ;
		} elseif (isset($_GET['id_category']))
			$curIdCategory = $_GET['id_category'];

		// Si on est sur une page product
		$curIdProduct = Tools::getValue('id_product',false);
		if (isset($curIdProduct) && $curIdProduct) {
			$req = self::db_getRow('SELECT n.*
			FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idproduct` n
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
			WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			AND n.id_product = '.$curIdProduct .' AND '
			.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
			ORDER BY id_group DESC');
			if(!$req || is_array($req) && !sizeof($req)) {
				// Check if we have some products categories matching here
				$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idprodcat` n
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
				JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = n.`id_cat`)
				WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
				'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
				AND cp.id_product = '.$curIdProduct.' AND '
				.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
				ORDER BY id_group DESC');
			}
		}

		// Si on est sur une page manufacturer
		elseif (isset($_GET['id_manufacturer']) && $_GET['id_manufacturer'])
			$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idman` n
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
			WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			AND n.id_manufacturer = '.$_GET['id_manufacturer'].' AND '
			.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
			ORDER BY id_group DESC');

		// Si on est sur une page category
		elseif (isset($curIdCategory) && $curIdCategory)
			$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcat` n
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
			WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			AND n.id_cat = '.$curIdCategory.' AND '
			.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
			ORDER BY id_group DESC');

		// Si on est sur une page supplier
		elseif (isset($_GET['id_supplier']) && $_GET['id_supplier'])
			$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idsupp` n
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
			WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			AND n.id_supplier = '.$_GET['id_supplier'].' AND '
			.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
			ORDER BY id_group DESC');

		// Si on est sur une page cms
		elseif(isset($_GET['id_cms']) && $_GET['id_cms'])
			$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idcms` n
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
			WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			AND n.id_cms = '.$_GET['id_cms'].' AND '
			.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
			ORDER BY id_group DESC');

		// Si on est sur une page spéciale
		if (!isset($req) || !$req) {
			if (_PS_VERSION_ < 1.4) {
				if (__PS_BASE_URI__ != '/') {
					$page_name = str_replace(__PS_BASE_URI__, '', $_SERVER['REQUEST_URI']);
				} else {
					$page_name = substr($_SERVER['REQUEST_URI'], 1, strlen($_SERVER['REQUEST_URI']));
				}
				if (strpos($page_name, '?') > 0) $page_name = substr($page_name, 0, strpos($page_name, '?'));
			
				$cur_url = array();
				$cur_url[] = '"'.pSQL(str_replace(__PS_BASE_URI__, '', $_SERVER['SCRIPT_NAME'])).'"';
				$cur_url[] = '"'.pSQL($page_name).'"';
			} else {
				$cur_url = array();
				$cur_url[] = "'" . pSQL(self::_getSmartyVarValue('page_name')) . "'";
			}
			
			$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_page` n
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
			LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
			WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			AND n.page IN ('.implode(',', $cur_url).') AND '
			.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
			ORDER BY id_group DESC');
			
			// S'il existe une page default, on l'applique
			if (!$req || !is_array($req) || !sizeof($req)) {
				$req = self::db_getRow('SELECT n.* FROM `'._DB_PREFIX_.'pm_advanced_bg_group` n
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idrule_idgroup` abi ON (abi.`id_rule` = n.`id_group`)
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (g.`id_group` = n.`id_group`)
				WHERE g.`activation` = 1 AND ((NOW() BETWEEN g.`date_start` and g.`date_end`) OR (g.`date_start` = g.`date_end`))
				'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
				AND n.default_group = 1 AND '
				.($include_customer_groups ?' NOT g.usertype = 1 AND (abi.`id_group` IN ('.$customer_groups.') OR ISNULL(abi.`id_group`)) ' : ' NOT g.usertype = 2 ').'
				ORDER BY id_group DESC');
			}
		}

		if(!isset($req) || !sizeof($req)) return '';

		$group = new ABG_Group_Class($req['id_group']);
		if($group->activation == 0)	return '';

		$slides = $group->getGroupSlides(true,'slide');
		$statics = $group->getGroupSlides(true,'static');

		$zones = array();
		foreach ($slides as $slide) {
			$slide_tmp = new ABG_Slide_Class($slide['id_slide']);
			foreach ($slide_tmp->zones as  $value)
				$zones[] = $value;
		}
		foreach ($statics as $static) {
			$slide_tmp = new ABG_Slide_Class($static['id_slide']);
			foreach ($slide_tmp->zones as  $value)
				$zones[] = $value;
		}
		foreach ($group->zones as $value) {
			if(!in_array($value['id_czone'], self::OneKeyArray($zones, 'id_czone')))
				$zones[] = $value;
		}

		$smarty->assign(array(
			'pm_group' => (isset($group) ? $group : ''),
			'abg_overlay' => (isset($group) && isset($group->overlay) && $group->overlay ? str_replace('|png', '', $group->overlay) : 0),
			'pm_bg_slide' => $slides,
			'pm_bg_static' => $statics,
			'pm_bg_zone' => $zones,
			'pm_bg_zone_js' => json_encode($zones),
			'pm_bg_class' => $this
		));
		
		if (_PS_VERSION_ >= 1.4) {
			$this->_addCSS($this->_path.self::ADVANCED_CSS_FILE, 'all');
			if (self::_isFilledArray($slides)) {
				$this->_addCSS($this->_path.'css/jquery.vegas.css', 'all');
				$this->_addJS($this->_path.'js/jquery.vegas.js');
			}
		}

		return $this->display(__FILE__, 'conf_bg.tpl');
	}

	protected function _displayInputFileLang($configOptions) {
		$defaultOptions = array(
			'uplodify' => true,
			'filetype' => '*.jpg;*.gif;*.png',
			'tips' => false,
			'extend' => false,
		);
		$configOptions = $this->_parseOptions($defaultOptions, $configOptions);
		
		// Generate secure key
		if (_PS_VERSION_ >= 1.5) {
			if (Configuration::getGlobalValue('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY') === false) Configuration::updateGlobalValue('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY', Tools::passwdGen(16));
		} else {
			if (Configuration::get('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY') === false) Configuration::updateValue('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY', Tools::passwdGen(16));
		}

		$isImage = false;
		if (preg_match('/jpg|jpeg|gif|bmp|png/i', $configOptions['filetype'])) $isImage = true;
		$this->_html .= '<label>' . $configOptions['label'] . '</label>
          <div class="margin-form">';
		foreach ( $this->_languages as $language ) {
			$this->_html .= '<div id="lang' . $configOptions['key'] . '_' . $language ['id_lang'] . '" class="pmFlag pmFlagLang_' . $language ['id_lang'] . '" style="display: ' . ($language ['id_lang'] == $this->_default_language ? 'block' : 'none') . '; float: left;">
          		 <div style="float:left;width:150px;">
					<input type="hidden" name="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_temp_file_lang_destination_lang" id="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_destination_lang" value="' . $configOptions['destination'] . '" />
		      		<input type="hidden" name="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_temp_file_lang" id="' . $configOptions['key'] . '_' . $language ['id_lang'] . '" value="" />
          		</div>';

			$this->_html .= '</div>';
		}
		$key_flag = $this->displayPMFlags();
		if (isset($configOptions['tips']) && $configOptions['tips']) {
			$this->_html .= '<img title="' . $configOptions['tips'] . '" id="' . $configOptions['key'] . '-tips" class="pm_tips" src="' . $this->_path . 'img/question.png" width="16px" height="16px" />';
			$this->_html .= '<script type="text/javascript">initTips("#' . $configOptions['key'] . '")</script>';
		}
		if ($configOptions['uplodify']) {
			$this->_pmClear();
			foreach ( $this->_languages as $language ) {
				$this->_html .= '<div id="wrapper_preview-' . $configOptions['key'] . '_' . $language ['id_lang'] . '" class="wrapper_preview-' . $configOptions['key'] . '">';
				$this->_html .= '<div id="preview-' . $configOptions['key'] . '_' . $language ['id_lang'] . '" class="ui-state-highlight pm_preview_upload pm_preview_upload-' . $configOptions['key'] . '" style="' . ($configOptions['obj'] && isset($configOptions['obj']->{$configOptions['key']} [$language ['id_lang']]) && $configOptions['obj']->{$configOptions['key']} [$language ['id_lang']] ? '' : 'display:none;') . '">';
				$file_location_dir = dirname(__FILE__) . $configOptions['destination'];
				//Check if have file and is exists
				if ($configOptions['obj'] && isset($configOptions['obj']->{$configOptions['key']} [$language ['id_lang']]) && $configOptions['obj']->{$configOptions['key']} [$language ['id_lang']] && file_exists($file_location_dir . $configOptions['obj']->{$configOptions['key']} [$language ['id_lang']])) {
					if ($isImage) {
						$this->_html .= '<img src="' . (substr($this->_path, 0, - 1) . $configOptions['destination'] . $configOptions['obj']->{$configOptions['key']} [$language ['id_lang']]) . '" id="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_file" />';
					} else {
						$this->_html .= '<a href="' . (substr($this->_path, 0, - 1) . $configOptions['destination'] . $configOptions['obj']->{$configOptions['key']} [$language ['id_lang']]) . '" target="_blank" class="pm_view_file_upload_link" id="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_file">' . $this->l('View file', $this->_coreClassName) . '</a>';
					}
				}
				$this->_html .= '<br /><span>' . $this->l('Delete this file', $this->_coreClassName) . '</span><input type="checkbox" name="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_unlink_lang" value="1" onclick="$jqPm(\'#preview-' . $configOptions['key'] . '_' . $language ['id_lang'] . '\').slideUp(\'fast\')"  /><input type="hidden" name="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_old_file_lang" id="' . $configOptions['key'] . '_' . $language ['id_lang'] . '_old_file" value="' . ($configOptions['obj'] && isset($configOptions['obj']->{$configOptions['key']} [$language ['id_lang']]) && $configOptions['obj']->{$configOptions['key']} [$language ['id_lang']] ? trim($configOptions['obj']->{$configOptions['key']} [$language ['id_lang']]) : '') . '" />';

				if($configOptions['extend'])
					$this->_html .= '
					<div class="abg_cb">
						<small>'.$this->l('Apply to all languages without picture', $this->_coreClassName).'</small>&nbsp;<input type="checkbox" value=1 name="'.$configOptions['key'] .'_all_lang">
					</div>';
				$this->_html .= '</div>';
				$this->_html .= '</div>';
				$this->_html .= '<script type="text/javascript">
				  $jqPm("#' . $configOptions['key'] . '_' . $language ['id_lang'] . '").uploadify({
				    "uploader"  : "' . $this->_path . 'js/uploadify/uploadify.swf",
				    "script"    : "' . $this->_path . 'js/uploadify/uploadify.php?secureKey='.urlencode('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY'.'---'.(_PS_VERSION_ >= 1.5 ? Configuration::getGlobalValue('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY') : Configuration::get('PM_'.self::$_module_prefix.'_UPLOAD_SECURE_KEY'))).'",
				    "cancelImg" : "' . $this->_path . 'js/uploadify/cancel.png",
				    "folder"    : "uploads/temp",
				    "auto"      : true,
	  				"buttonText"  : "' . $this->l('Choose file') . '",
					"onClearQueue"      : function() {
						$jqPm("input[type=submit].ui-state-default").attr("disabled", "disabled").removeClass("ui-state-default").addClass("ui-state-disabled");
					},
				  	"onComplete"  : function(event, ID, fileObj, response, data) {
						response = $jqPm.trim(response);
				  		$jqPm("#' . $configOptions['key'] . '_' . $language ['id_lang'] . '").uploadifySettings("scriptData" , {"filename":response});
				  		$jqPm("#' . $configOptions['key'] . '_' . $language ['id_lang'] . '").val(response);
				  		$jqPm("#' . $configOptions['key'] . '_' . $language ['id_lang'] . '_file").remove();
				  		' . ($isImage ? '$jqPm("#preview-' . $configOptions['key'] . '_' . $language ['id_lang'] . '").prepend("<img src=\'"+_modulePath+"uploads/temp/"+response+"\' id=\'' . $configOptions['key'] . '_' . $language ['id_lang'] . '_file\' />");' : '$jqPm("#preview-' . $configOptions['key'] . '_' . $language ['id_lang'] . '").prepend("<a href=\'"+_modulePath+"uploads/temp/"+response+"\' target=\'_blank\' class=\'pm_view_file_upload_link\' id=\'' . $configOptions['key'] . '_' . $language ['id_lang'] . '_file\'>' . $this->l('View file', $this->_coreClassName) . '</a>");') . '

						$jqPm("input[name=' . $configOptions['key'] . '_' . $language ['id_lang'] . '_unlink_lang]").attr("checked","").removeAttr("checked");
						$jqPm("#preview-' . $configOptions['key'] . '_' . $language ['id_lang'] . '").slideDown("fast");
						$jqPm("input[type=submit].ui-state-disabled").removeAttr("disabled").removeClass("ui-state-disabled").addClass("ui-state-default");
				  	}
				 });
				</script>';
			}
			$this->_html .= '<script type="text/javascript">
				 $jqPm("#'.$key_flag.'-menu li a").unbind("mouseup").bind("mouseup",function() {
					 setTimeout(function() {
					 	var currentIdLang = $jqPm("#'.$key_flag.' option:selected").val();
						$jqPm(".wrapper_preview-' . $configOptions['key'] . '").hide();
						$jqPm("#wrapper_preview-' . $configOptions['key'] . '_"+currentIdLang).show();
						if(currentIdLang =='.Configuration::get('PS_LANG_DEFAULT').' )
							$jqPm(".abg_cb").show("medium");
						else
							$jqPm(".abg_cb").hide("medium");
					},100);
				 });
				 $jqPm("#'.$key_flag.'-menu li.ui-selectmenu-item-selected a").trigger("mouseup");
			</script>';
		}
		$this->_pmClear();
		$this->_html .= '</div>';
	}
}