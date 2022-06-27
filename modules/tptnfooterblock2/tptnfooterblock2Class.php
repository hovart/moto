<?php

class FooterBlock2Class extends ObjectModel
{
	public $id;
	public $id_shop;
	public $url;
	public $text;

	public static $definition = array(
		'table' => 'tptnfooterblock2',
		'primary' => 'id_tptnfooterblock2',
		'multilang' => true,
		'fields' => array(
			'id_shop' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'text' =>		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true),
			'url' =>		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isAnything', 'required' => true)
		)
	);

	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST AS $key => $value)
			if (key_exists($key, $this) AND $key != 'id_'.$this->table)
				$this->{$key} = $value;

		/* Multilingual fields */
		if (sizeof($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages AS $language)
				foreach ($this->fieldsValidateLang AS $field => $validation)
					if (isset($_POST[$field.'_'.(int)($language['id_lang'])]))
						$this->{$field}[(int)($language['id_lang'])] = $_POST[$field.'_'.(int)($language['id_lang'])];
		}
	}
}
