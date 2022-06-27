<?php

/*
* 2007-2014 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2014 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class EditorialtopClass extends ObjectModel
{
	/** @var integer editorialtop id */
	public $id;

	/** @var integer editorialtop id shop */
	public $id_shop;

	/** @var string body_title */
	public $body_home_logo_link;

	/** @var string body_title */
	public $body_title;

	/** @var string body_title */
	public $body_subheading;

	/** @var string body_title */
	public $body_paragraph;

	/** @var string body_title */
	public $body_logo_subheading;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'editorialtop',
		'primary' => 'id_editorialtop',
		'multilang' => true,
		'fields' => array(
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
			'body_home_logo_link' => array('type' => self::TYPE_STRING, 'validate' => 'isUrl'),
			'body_title' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
			'body_subheading' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
			'body_paragraph' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
			'body_logo_subheading' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName'),
		)
	);

	public static function getByIdShop($id_shop)
	{
		$id = Db::getInstance()->getValue('SELECT `id_editorialtop` FROM `'._DB_PREFIX_.'editorialtop` WHERE `id_shop` ='.(int)$id_shop);

		return new EditorialtopClass($id);
	}

	public function copyFromPost()
	{
		/* Classical fields */
		foreach ($_POST as $key => $value)
		{
			if (key_exists($key, $this) && $key != 'id_'.$this->table)
				$this->{$key} = $value;
		}

		/* Multilingual fields */
		if (count($this->fieldsValidateLang))
		{
			$languages = Language::getLanguages(false);
			foreach ($languages as $language)
			{
				foreach ($this->fieldsValidateLang as $field => $validation)
				{
					if (Tools::getIsset($field.'_'.(int)$language['id_lang']))
						$this->{$field}[(int)$language['id_lang']] = $_POST[$field.'_'.(int)$language['id_lang']];
				}
			}
		}
	}
}