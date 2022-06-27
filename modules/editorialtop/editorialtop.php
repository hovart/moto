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

if (!defined('_PS_VERSION_'))
	exit;

class Editorialtop extends Module
{
	public function __construct()
	{
		$this->name = 'editorialtop';
		$this->tab = 'front_office_features';
		$this->version = '2.1';
		$this->author = 'PrestaShop';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Home text editor');
		$this->description = $this->l('A text-edit module for your homepage.');
		$path = dirname(__FILE__);
		if (strpos(__FILE__, 'Module.php') !== false)
			$path .= '/../modules/'.$this->name;
		include_once $path.'/EditorialtopClass.php';
	}

	public function install()
	{
		if (!parent::install() || !$this->registerHook('displayHome') || !$this->registerHook('displayHeader'))
			return false;

		$res = Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'editorialtop` (
			`id_editorialtop` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,
			`body_home_logo_link` varchar(255) NOT NULL,
			PRIMARY KEY (`id_editorialtop`))
			ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		if ($res)
			$res &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'editorialtop_lang` (
				`id_editorialtop` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
				`body_title` varchar(255) NOT NULL,
				`body_subheading` varchar(255) NOT NULL,
				`body_paragraph` text NOT NULL,
				`body_logo_subheading` varchar(255) NOT NULL,
				PRIMARY KEY (`id_editorialtop`, `id_lang`))
				ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8');

		if ($res)
			foreach (Shop::getShops(false) as $shop)
				$res &= $this->createExampleEditorialtop($shop['id_shop']);

		if (!$res)
			$res &= $this->uninstall();

		return $res;
	}

	private function createExampleEditorialtop($id_shop)
	{
		$editorialtop = new EditorialtopClass();
		$editorialtop->id_shop = (int)$id_shop;
		$editorialtop->body_home_logo_link = 'http://www.prestashop.com';
		foreach (Language::getLanguages(false) as $lang)
		{
			$editorialtop->body_title[$lang['id_lang']] = 'Lorem ipsum dolor sit amet';
			$editorialtop->body_subheading[$lang['id_lang']] = 'Excepteur sint occaecat cupidatat non proident';
			$editorialtop->body_paragraph[$lang['id_lang']] = '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>';
			$editorialtop->body_logo_subheading[$lang['id_lang']] = 'Lorem ipsum presta shop amet';
		}

		return $editorialtop->add();
	}

	public function uninstall()
	{
		$res = Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'editorialtop`');
		$res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'editorialtop_lang`');

		if (!$res || !parent::uninstall())
			return false;

		return true;
	}

	private function initForm()
	{
		$languages = Language::getLanguages(false);
		foreach ($languages as $k => $language)
			$languages[$k]['is_default'] = (int)($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));

		$helper = new HelperForm();
		$helper->module = $this;
		$helper->name_controller = 'editorialtop';
		$helper->identifier = $this->identifier;
		$helper->token = Tools::getAdminTokenLite('AdminModules');
		$helper->languages = $languages;
		$helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
		$helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
		$helper->allow_employee_form_lang = true;
		$helper->toolbar_scroll = true;
		$helper->toolbar_btn = $this->initToolbar();
		$helper->title = $this->displayName;
		$helper->submit_action = 'submitUpdateEditorialtop';

		$this->fields_form[0]['form'] = array(
			'tinymce' => true,
			'legend' => array(
				'title' => $this->displayName,
				'image' => $this->_path.'logo.gif'
			),
			'submit' => array(
				'name' => 'submitUpdateEditorialtop',
				'title' => $this->l('Save '),
				'class' => 'button'
			),
			'input' => array(
				array(
					'type' => 'text',
					'label' => $this->l('Main title'),
					'name' => 'body_title',
					'lang' => true,
					'size' => 64,
					'desc' => $this->l('Appears along top of your homepage'),
				),
				array(
					'type' => 'text',
					'label' => $this->l('Subheading'),
					'name' => 'body_subheading',
					'lang' => true,
					'size' => 64,
				),
				array(
					'type' => 'textarea',
					'label' => $this->l('Introductory text'),
					'name' => 'body_paragraph',
					'lang' => true,
					'autoload_rte' => true,
					'desc' => $this->l('For example... explain your mission, highlight a new product, or describe a recent event.'),
					'cols' => 60,
					'rows' => 30
				),
				array(
					'type' => 'file',
					'label' => $this->l('Homepage logo'),
					'name' => 'body_homepage_logo',
					'display_image' => true
				),
				array(
					'type' => 'text',
					'label' => $this->l('Homepage logo link'),
					'name' => 'body_home_logo_link',
					'size' => 33,
				),
				array(
					'type' => 'text',
					'label' => $this->l('Homepage logo subheading'),
					'name' => 'body_logo_subheading',
					'lang' => true,
					'size' => 33,
				),
			)
		);

		return $helper;
	}

	private function initToolbar()
	{
		$this->toolbar_btn['save'] = array(
			'href' => '#',
			'desc' => $this->l('Save')
		);

		return $this->toolbar_btn;
	}

	public function getContent()
	{
		$this->_html = '';
		$this->postProcess();

		$helper = $this->initForm();

		$id_shop = (int)$this->context->shop->id;
		$editorialtop = EditorialtopClass::getByIdShop($id_shop);

		if (!$editorialtop) //if editorialtop ddo not exist for this shop => create a new example one
			$this->createExampleEditorialtop($id_shop);

		foreach ($this->fields_form[0]['form']['input'] as $input) //fill all form fields
		{
			if ($input['name'] != 'body_homepage_logo')
				$helper->fields_value[$input['name']] = $editorialtop->{$input['name']};
		}

		$file = dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg';
		$helper->fields_value['body_homepage_logo']['image'] = (file_exists($file) ? '<img src="'.$this->_path.'homepage_logo_'.(int)$id_shop.'.jpg">' : '');
		if ($helper->fields_value['body_homepage_logo'] && file_exists($file))
			$helper->fields_value['body_homepage_logo']['size'] = filesize($file) / 1000;

		$this->_html .= $helper->generateForm($this->fields_form);

		return $this->_html;
	}

	public function postProcess()
	{
		$errors = '';
		$id_shop = (int)$this->context->shop->id;
		// Delete logo image
		if (Tools::isSubmit('deleteImage'))
		{
			if (!file_exists(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
				$errors .= $this->displayError($this->l('This action cannot be made.'));
			else
			{
				unlink(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('EDITORIALtop_IMAGE_DISABLE', 1);
				$this->_clearCache('editorialtop.tpl');
				Tools::redirectAdmin('index.php?tab=AdminModules&configure='.$this->name.'&token='.Tools::getAdminToken('AdminModules'.(int)(Tab::getIdFromClassName('AdminModules')).(int)$this->context->employee->id));
			}
			$this->_html .= $errors;
		}

		if (Tools::isSubmit('submitUpdateEditorialtop'))
		{
			$id_shop = (int)$this->context->shop->id;
			$editorialtop = EditorialtopClass::getByIdShop($id_shop);
			$editorialtop->copyFromPost();
			if (empty($editorialtop->id_shop))
				$editorialtop->id_shop = (int)$id_shop;
			$editorialtop->save();

			/* upload the image */
			if (isset($_FILES['body_homepage_logo']) && isset($_FILES['body_homepage_logo']['tmp_name']) && !empty($_FILES['body_homepage_logo']['tmp_name']))
			{
				Configuration::set('PS_IMAGE_GENERATION_METHOD', 1);
				if (file_exists(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
					unlink(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg');
				if ($error = ImageManager::validateUpload($_FILES['body_homepage_logo']))
					$errors .= $error;
				elseif (!($tmp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS')) || !move_uploaded_file($_FILES['body_homepage_logo']['tmp_name'], $tmp_name))
					return false;
				elseif (!ImageManager::resize($tmp_name, dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
					$errors .= $this->displayError($this->l('An error occurred while attempting to upload the image.'));
				if (isset($tmp_name))
					unlink($tmp_name);
			}
			$this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully.')) : $errors;
			if (file_exists(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg'))
			{
				list($width, $height, $type, $attr) = getimagesize(dirname(__FILE__).'/homepage_logo_'.(int)$id_shop.'.jpg');
				Configuration::updateValue('EDITORIALtop_IMAGE_WIDTH', (int)round($width));
				Configuration::updateValue('EDITORIALtop_IMAGE_HEIGHT', (int)round($height));
				Configuration::updateValue('EDITORIALtop_IMAGE_DISABLE', 0);
			}
			$this->_clearCache('editorialtop.tpl');
		}
	}

	public function hookDisplayHome($params)
	{
		if (!$this->isCached('editorialtop.tpl', $this->getCacheId()))
		{
			$id_shop = (int)$this->context->shop->id;
			$editorialtop = EditorialtopClass::getByIdShop($id_shop);
			if (!$editorialtop)
				return;
			$editorialtop = new EditorialtopClass((int)$editorialtop->id, $this->context->language->id);
			if (!$editorialtop)
				return;
			$this->smarty->assign(array(
				'editorialtop' => $editorialtop,
				'default_lang' => (int)$this->context->language->id,
				'image_width' => Configuration::get('EDITORIALtop_IMAGE_WIDTH'),
				'image_height' => Configuration::get('EDITORIALtop_IMAGE_HEIGHT'),
				'id_lang' => $this->context->language->id,
				'homepage_logo' => !Configuration::get('EDITORIALtop_IMAGE_DISABLE') && file_exists('modules/editorialtop/homepage_logo_'.(int)$id_shop.'.jpg'),
				'image_path' => $this->_path.'homepage_logo_'.(int)$id_shop.'.jpg'
			));
		}

		return $this->display(__FILE__, 'editorialtop.tpl', $this->getCacheId());
	}

	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS(($this->_path).'css/editorialtop.css', 'all');
	}
}
