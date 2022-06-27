<?php

class ABG_cZone_Class extends ObjectModel{
	public $id;
 	public $status ;
 	public $position ;
 	public $width ;
 	public $height ;
 	public $marginLeft = 0;
 	public $marginTop = 0;
	public $border;
	public $side;
	public $color;
	public $title ;
	public $href;

	public $slides = array();
	public $rules = array();

	protected 	$table 				= 	'pm_advanced_bg_clickzone';
	protected 	$tables 			= 	array('pm_advanced_bg_clickzone','pm_advanced_bg_clickzone_lang');
	public	 	$identifier 		= 	'id_czone';

	protected 	$fieldsRequired 	= 	array(	'status',
												'position',
												'width',
												'height',
												'marginLeft',
												'marginTop',
												'border',
										);

	protected 	$fieldsSize 		= 	array(	'status'	=>3,
												'position'	=>3,
												'width'		=>10,
												'height'	=>10,
												'marginLeft'=> 5,
												'marginTop'=> 5,
												'border'	=>3,
												'side'		=>3,
												'color' 	=> 20,
										);

	protected 	$fieldsValidate 	= 	array(	'status' 		=> 'isUnsignedInt',
												'position' 		=> 'isUnsignedInt',
												'width' 		=> 'isGenericName',
												'height' 		=> 'isGenericName',
												'marginLeft'	=> 'isInt',
												'marginTop'		=> 'isInt',
												'border'		=> 'isUnsignedInt',
												'side'			=> 'isUnsignedInt',
										);

	protected 	$fieldsRequiredLang 	= 	array('title','href');
 	protected 	$fieldsSizeLang 		= 	array('title' => 255,'href' => 255);
 	protected 	$fieldsValidateLang 	=	array('title' => 'isGenericName','href' => 'isUrl');

	public static $definition = array(
		'table' => 'pm_advanced_bg_clickzone',
		'primary' => 'id_czone',
		'multishop' => false,
		'multilang_shop' => false,
		'fields' => array(
			'title' 		=> 				array('type' => 3, 'lang' => true, 'required' => false),
			'href' 			=> 				array('type' => 3, 'lang' => true, 'required' => false)
		)
	);

	public function __construct($id = NULL, $id_lang = NULL) {
		parent::__construct($id, $id_lang);

		if ($id && Tools::getValue('pm_save_obj') != 'ABG_cZone_Class') {
			$this->slides = $this->getZoneSlides();
			$this->rules = $this->getZoneRules();
		}
	}

	public function getFields(){
		parent::validateFields();
		if (isset($this->id))
			$fields['id_czone'] = (int)($this->id);

		$fields['width'] 		= pSQL($this->width);
		$fields['height'] 		= pSQL($this->height);
		$fields['marginLeft'] 	= (int)($this->marginLeft);
		$fields['marginTop'] 	= (int)($this->marginTop);
		$fields['status'] 		= (int)($this->status);
		$fields['position']		= (int)($this->position);
		$fields['border']		= (int)($this->border);
		$fields['side']			= (int)($this->side);
		$fields['color']		= pSQL($this->color);

		return $fields;
	}

	public function getTranslationsFieldsChild(){
		parent::validateFieldsLang();

		$fieldsArray = array('title','href');
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
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']],true);
				elseif (in_array($field, $this->fieldsRequiredLang)){
					if ($this->{$field} != '')
						$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage],true);
				}
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}
		return $fields;
	}

	public function getZoneRules($actif=false){
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_group` g, `'._DB_PREFIX_.'pm_advanced_bg_idrule_idczone` sz
			WHERE g.id_group = sz.id_rule
			AND sz.id_czone = '.$this->id.'
			'.(_PS_VERSION_ >= 1.5 ? ' AND g.`id_shop`='.(int)Context::getContext()->shop->id : '').' 
			'.($actif ? ' AND g.activation = 1 ' : '')
			);
	}

	public function getZoneSlides($actif=false){
		global $cookie;
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_slide` s,`'._DB_PREFIX_.'pm_advanced_bg_slide_lang` sl, `'._DB_PREFIX_.'pm_advanced_bg_idslide_idczone` sz
			WHERE s.id_slide = sl.id_slide
			AND s.id_slide = sz.id_slide
			AND sl.id_lang = '.(isset($cookie->id_lang)? $cookie->id_lang : (int) Configuration::get('PS_LANG_DEFAULT')).'
			AND sz.id_czone = '.$this->id.'
			' . ($actif? ' AND s.status = 1 ' : '')
			);
	}

	public static function getZones($actif=false){
		global $cookie;
		return abg_core_class::Db_ExecuteS('
			SELECT *
			FROM `'._DB_PREFIX_.'pm_advanced_bg_clickzone` s,`'._DB_PREFIX_.'pm_advanced_bg_clickzone_lang` sl
			WHERE s.id_czone = sl.id_czone
			AND sl.id_lang = '.(isset($cookie->id_lang) ? $cookie->id_lang : (int) Configuration::get('PS_LANG_DEFAULT')).'
			'.($actif? ' AND s.status = 1 ' : '')
			);
	}

	public function save($nullValues = false, $autodate = true) {
		if (isset($this->id) && $this->id) {
			abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idslide_idczone` WHERE `'.$this->identifier.'` = '.(int)$this->id);
			abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idrule_idczone` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		}

		if (parent::save($nullValues,$autodate)) {
			if (is_array($this->slides) && sizeof($this->slides)) {
				foreach ($this->slides as $cm) {
					if(is_array($cm)) $id_slide = (int)$cm['id_slide'];
					else $id_slide = (int)$cm;
					abg_core_class::Db_Execute('
						INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idslide_idczone` (id_slide , `'.$this->identifier.'`)
						VALUES ("'.(int)$id_slide.'" , "'.(int)$this->id.'")
					');
				}
			}

			if (is_array($this->rules) && sizeof($this->rules)) {
				foreach ($this->rules as $cm) {
					if(is_array($cm)) $id_rule = (int)$cm['id_rule'];
					else $id_rule = (int)$cm;
					abg_core_class::Db_Execute('
						INSERT INTO `'._DB_PREFIX_.'pm_advanced_bg_idrule_idczone` (id_rule , `'.$this->identifier.'`)
						VALUES ("'.(int)$id_rule.'" , "'.(int)$this->id.'")
					');
				}
			}
			return true;
		}
		return false;
	}

	public function delete(){
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idslide_idczone` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		abg_core_class::Db_Execute('DELETE FROM `'._DB_PREFIX_.'pm_advanced_bg_idrule_idczone` WHERE `'.$this->identifier.'` = '.(int)$this->id);
		return parent::delete();
	}
}
