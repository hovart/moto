<?php

class ABG_Slide_Class extends ObjectModel{
	public $id;
 	public $name ;
 	public $date_start ;
	public $date_end ;
	public $activation;
 	public $bg_color ;
 	public $bg_valign ;
 	public $bg_halign ;
	public $bg_position ;
	public $bg_repeat ;
	public $bg_fixed ;
	public $fade_time ;
	public $sort ;
	public $bg_type;

	public $image ;

	public $groups = array();
	public $zones = array();
	public $groupslive = array();

	protected 	$tables 			= 	array ('pm_advanced_bg_slide', 'pm_advanced_bg_slide_lang');
	protected 	$table 				= 	'pm_advanced_bg_slide';
	public	 	$identifier 		= 	'id_slide';

	protected 	$fieldsRequired 	= 	array(	'activation',
												'name',
												'bg_type',
												'date_start',
												'date_end'	,
										);

	protected 	$fieldsSize 		= 	array(	'activation' => 3,
												'name'=> 50,
												'bg_position'=> 255,
												'bg_color'=> 20,
												'bg_repeat'	=> 20,
												'bg_fixed'	=> 20,
												'sort'	=> 3,
												'bg_type'	=> 3,
												'fade_time'	=> 10,
										);

	protected 	$fieldsValidate 	= 	array(	'activation' 	=> 'isUnsignedInt',
												'name' 			=> 'isGenericName',
												'bg_position' 	=> 'isGenericName',
												'bg_valign' 	=> 'isUnsignedInt',
												'bg_halign' 	=> 'isUnsignedInt',
												'bg_repeat'	=> 'isGenericName',
												'date_start'=> 'isDate',
												'date_end' 	=> 'isDate'	,
												'bg_fixed'	=> 'isGenericName'	,
												'sort'	=> 'isUnsignedInt'	,
												'bg_type'	=> 'isUnsignedInt'	,
												'fade_time'	=> 'isUnsignedInt',
										);

	protected 	$fieldsRequiredLang 	= 	array(	'image');

 	protected 	$fieldsSizeLang 	= 	array(	'image' => 32
										);

 	protected 	$fieldsValidateLang =	array(	'image' => 'isFileName'
 										);

	public static $definition = array(
		'table' => 'pm_advanced_bg_slide',
		'primary' => 'id_slide',
		'multishop' => false,
		'multilang_shop' => false,
		'fields' => array(
			'image' 		=> 				array('type' => 3, 'lang' => true, 'required' => false)
		)
	);

	public function __construct($id = NULL, $id_lang = NULL) {
		parent::__construct($id, $id_lang);
		if($id) {
			$this->groups = $this->getSlideGroup();
			$this->zones = $this->getSlideZones(TRUE);
		}
	}

	public function getSlideGroup() {
		return( abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_group` g,
				`'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` igis
			WHERE igis.'.$this->identifier.' = '.(int)$this->id .'
			AND g.id_group = igis.id_group
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').'
			;'));
	}

	public function getFields(){
		parent::validateFields();
		if (isset($this->id))
			$fields['id_slide'] = (int)($this->id);

		$fields['name'] 		= pSQL($this->name);
		$fields['date_start'] 	= pSQL($this->date_start);
		$fields['date_end'] 	= pSQL($this->date_end);
		$fields['bg_position'] 	= pSQL($this->bg_position);
		$fields['bg_valign'] 	= pSQL($this->bg_valign);
		$fields['bg_halign'] 	= pSQL($this->bg_halign);
		$fields['bg_color'] 	= pSQL($this->bg_color);
		$fields['bg_repeat'] 	= pSQL($this->bg_repeat);
		$fields['bg_fixed'] 	= (int)($this->bg_fixed);
		$fields['fade_time'] 	= (int)($this->fade_time);
		$fields['sort'] 		= (int)($this->sort);
		$fields['bg_type'] 		= (int)($this->bg_type);
		$fields['activation']	= (int)($this->activation);

		return $fields;
	}
	
	public static function getSlides($id_lang = false, $actif = false, $only_vegas = false, $without_associated_groups = false){
		if(!$id_lang)
			$id_lang = Configuration::get('PS_LANG_DEFAULT') ;
		
		if ($without_associated_groups) {
			return( abg_core_class::Db_ExecuteS('
				SELECT s.*, sl.*
				FROM `'._DB_PREFIX_.'pm_advanced_bg_slide` s
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_slide_lang` sl ON (s.id_slide = sl.id_slide AND sl.id_lang = '.$id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` igis ON (igis.id_slide = s.id_slide)
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_group` g ON (igis.id_group = g.id_group'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').')
				WHERE '.($only_vegas ? ' s.bg_type=2 ' : ' 1 ').'
				'.($actif?'
				AND s.activation = 1
				AND ((NOW() BETWEEN s.`date_start` and s.`date_end`)
				OR (s.`date_start` = s.`date_end`))':'')
				. ' GROUP BY s.id_slide'));
		} else {
			return( abg_core_class::Db_ExecuteS('
				SELECT s.*, sl.*
				FROM `'._DB_PREFIX_.'pm_advanced_bg_group` g
				LEFT JOIN `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` igis ON (g.id_group = igis.id_group '.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').')
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_slide` s ON (s.id_slide = igis.id_slide '.($only_vegas ? ' AND s.bg_type=2 ' : '').')
				JOIN `'._DB_PREFIX_.'pm_advanced_bg_slide_lang` sl ON (s.id_slide = sl.id_slide AND sl.id_lang = '.$id_lang.')
				'.($actif?'
				WHERE s.activation = 1
				AND ((NOW() BETWEEN s.`date_start` and s.`date_end`)
				OR (s.`date_start` = s.`date_end`))':'')
				. ' GROUP BY s.id_slide'));
		}
	}
	
	public function getSlideZones($actif=false){
		global $cookie;
		return( abg_core_class::Db_ExecuteS('
			SELECT s.*, sl.*, sz.*
			FROM `'._DB_PREFIX_.'pm_advanced_bg_clickzone` s,`'._DB_PREFIX_.'pm_advanced_bg_clickzone_lang` sl, `'._DB_PREFIX_.'pm_advanced_bg_idslide_idczone` sz
			WHERE s.id_czone = sl.id_czone
			AND s.id_czone = sz.id_czone
			AND sl.id_lang = '.(isset($cookie->id_lang)?$cookie->id_lang:(int) Configuration::get('PS_LANG_DEFAULT')).'
			AND sz.id_slide = '.$this->id.'
			'.($actif?'
			AND s.status = 1
			':'')
			));
	}
	
	public function getTranslationsFieldsChild(){
		parent::validateFieldsLang();

		$fieldsArray = array('image');
		$fields = array();
		$languages = Language::getLanguages(false);
		$defaultLanguage = Configuration::get('PS_LANG_DEFAULT');
		foreach ($languages as $language){
			$fields[$language['id_lang']]['id_lang'] = $language['id_lang'];
			$fields[$language['id_lang']][$this->identifier] = (int)($this->id);
			foreach ($fieldsArray as $field){
				if (!Validate::isTableOrIdentifier($field))
					die(Tools::displayError());

				//Check fields validity
				if (isset($this->{$field}[$language['id_lang']]) AND !empty($this->{$field}[$language['id_lang']]))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']]);
				elseif (in_array($field, $this->fieldsRequiredLang)){
					/*if ($this->{$field} != '')
						$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage]);*/
				}
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
	}
	
	private function saveDependance() {
		if (is_array($this->groupslive) && sizeof($this->groupslive)) {

			//On récupère les id_sort, id_group des elements à ne pas changer
			$ids = abg_core_class::Db_ExecuteS('
				SELECT idgids.`id_sort`, idgids.`id_group`
				FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` idgids, `'._DB_PREFIX_.'pm_advanced_bg_group` g
				WHERE g.`id_group`=idgids.`id_slide`
				AND idgids.`id_group` IN ('.implode(',',$this->groupslive).')
				'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').'
				AND idgids.`id_slide` = '.$this->id
			);
			//On supprime ceux qu'on doit supprimer
			abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide`
				WHERE `'.$this->identifier.'` = '.(int)$this->id.(is_array($ids) && sizeof($ids)?'
				 AND NOT id_sort IN ('.implode(',',pm_advancedbackgroundchanger::OneKeyArray($ids,'id_sort')).')':''));
			foreach ($this->groupslive as $group) {
				if(in_array($group, pm_advancedbackgroundchanger::OneKeyArray($ids,'id_group')))
					continue;

				$req = abg_core_class::Db_getRow('
									SELECT MAX(sort) as pos
									FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` igis
									WHERE id_group = '.(int)$group);
				$pos = (int)$req['pos'] + 1;
				abg_core_class::Db_Execute('	INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` ( id_sort , `id_slide`, id_group , sort )
												VALUES ( NULL , "'.(int)$this->id.'", "'.(int)$group.'" , '.$pos.')
											');
			}
		}
		else
			abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide`
			WHERE `'.$this->identifier.'` = '.(int)$this->id);
	}

	public function save($nullValues = false, $autodate = true) {
		if (parent::save($nullValues,$autodate)){
			if (Tools::isSubmit('Submit_slide'))
				$this->saveDependance();
			return true;
		}
		return false;
	}

	public function delete(){
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idgroup_idslide` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		foreach( Language::getLanguages(false) as $value){
			if(isset($this->image[$value['id_lang']]) && strlen(trim($this->image[$value['id_lang']])) )
				if(file_exists(dirname(__FILE__). '/uploads/slides/'.$this->image[$value['id_lang']]))
					unlink(dirname(__FILE__). '/uploads/slides/'.$this->image[$value['id_lang']]);

		}
		return parent::delete();
	}
}