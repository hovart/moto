<?php
if (!defined('_PS_VERSION_'))
	die(header('HTTP/1.0 404 Not Found'));

/**
 * Description of totRules
 *
 * @author Guillaume Deloince
 */
class totSplashScreenTemplateModel extends ObjectModel {

	public $id_totsplashscreen_template,
			$name,
			$link_fb,
			$newsletter,
			$width,
			$height,
			$backgroundColor,
			$opacity,
			$permission_mode,
			$permission_redirect,
			$image_enter,
			$image_leave,
			$message;
	//For 1.4
	protected $table = 'totsplashscreen_template';
	protected $identifier = 'id_totsplashscreen_template';
	protected $fieldsRequired = array(
		'name'
	);
	protected $fieldsValidate = array(
		'name' => 'isString',
		'link_fb' => 'isString',
		'newsletter' => 'isInt',
		'width' => 'isInt',
		'height' => 'isInt',
		'backgroundColor' => 'isString',
		'opacity' => 'isInt',
		'permission_mode' => 'isInt',
		'permission_redirect' => 'isString',
		'image_enter' => 'isString',
		'image_leave' => 'isString'
	);
	//For 1.5
	public static $definition = array(
		'table' => 'totsplashscreen_template',
		'primary' => 'id_totsplashscreen_template',
		'multilang' => true,
		'fields' => array(
			'name' => array('type' => 3, 'validate' => 'isString', 'required' => true),
			'link_fb' => array('type' => 3, 'validate' => 'isString'),
			'newsletter' => array('type' => 1, 'validate' => 'isInt'),
			'width' => array('type' => 1, 'validate' => 'isInt'),
			'height' => array('type' => 1, 'validate' => 'isInt'),
			'backgroundColor' => array('type' => 3, 'validate' => 'isString'),
			'opacity' => array('type' => 1, 'validate' => 'isInt'),
			'permission_mode' => array('type' => 1),
			'permission_redirect' => array('type' => 3, 'validate' => 'isString'),
			'message' => array('type' => 3, 'validate' => 'isString', 'lang' => true),
			'image_enter' => array('type' => 3, 'validate' => 'isString'),
			'image_leave' => array('type' => 3, 'validate' => 'isString')
		)
	);

	public function getFields() {
		if (version_compare('1.5.0', _PS_VERSION_, '<'))
			return parent::getFields();
		else
			return array(
				'name' => pSQL($this->name),
				'link_fb' => pSQL($this->link_fb),
				'newsletter' => (int) $this->newsletter,
				'width' => (int) $this->width,
				'height' => (int) $this->height,
				'backgroundColor' => pSQL($this->backgroundColor),
				'opacity' => (int) $this->opacity,
				'permission_mode' => (int) $this->permission_mode,
				'permission_redirect' => pSQL($this->permission_redirect),
				'image_enter' => pSQL($this->image_enter),
				'image_leave' => pSQL($this->image_leave),
			);
	}

	public function getTranslationsFieldsChild() {

		parent::validateFieldsLang();
		$fieldsArray = array('message');
		$fields = array();
		$languages = Language::getLanguages(false);
		$defaultLanguage = (int) (Configuration::get('PS_LANG_DEFAULT'));
		foreach ($languages as $language) {
			$fields[$language['id_lang']]['id_lang'] = (int) ($language['id_lang']);
			$fields[$language['id_lang']][$this->identifier] = (int) ($this->id);
			foreach ($fieldsArray as $field) {
				if (!Validate::isTableOrIdentifier($field))
					die(Tools::displayError());
				if (isset($this->{$field}[$language['id_lang']]) AND !empty($this->{$field}[$language['id_lang']]))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$language['id_lang']], true);
				elseif (in_array($field, $this->fieldsRequiredLang))
					$fields[$language['id_lang']][$field] = pSQL($this->{$field}[$defaultLanguage], true);
				else
					$fields[$language['id_lang']][$field] = '';
			}
		}

		return $fields;
	}

	public function __construct($id = false, $id_lang = false) {
		parent::__construct($id, $id_lang);
	}

	public static function getTemplates() {
		$sql = "SELECT * FROM "._DB_PREFIX_."totsplashscreen_template";
		return Db::getInstance()->ExecuteS($sql);
	}

}

?>
