<?php
/*
* 2007-2013 PrestaShop
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
*  @copyright  2007-2013 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_'))
	exit;

class HomeFeaturedModule extends Module
{
	protected $_html = '';
	protected $_postErrors = array();

	function __construct()
	{
		$this->name = 'homefeatured';
		$this->tab = 'front_office_features';
		$this->version = '1.1';
		$this->author = 'PrestaShop';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Featured products on the homepage.');
		$this->description = $this->l('Displays featured products in the middle of your homepage.');
	}

	function install()
	{
		$this->_clearCache('homefeatured.tpl');
		Configuration::updateValue('HOME_FEATURED_NBR', 8);

		if (!parent::install()
			|| !$this->registerHook('displayHome')
			|| !$this->registerHook('header')
			|| !$this->registerHook('addproduct')
			|| !$this->registerHook('updateproduct')
			|| !$this->registerHook('deleteproduct')
		)
			return false;
		return true;
	}
	
	public function uninstall()
	{
		$this->_clearCache('homefeatured.tpl');
		return parent::uninstall();
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitHomeFeatured'))
		{
			$nbr = (int)Tools::getValue('nbr');
			if (!$nbr OR $nbr <= 0 OR !Validate::isInt($nbr))
				$errors[] = $this->l('An invalid number of products has been specified.');
			else
				Configuration::updateValue('HOME_FEATURED_NBR', (int)($nbr));
			if (isset($errors) AND sizeof($errors))
				$output .= $this->displayError(implode('<br />', $errors));
			else
				$output .= $this->displayConfirmation($this->l('Your settings have been updated.'));
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		$output = '
		<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
			<fieldset><legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
				<p>'.$this->l('To add products to your homepage, simply add them to the "home" category.').'</p><br />
				<label>'.$this->l('Define the number of products to be displayed.').'</label>
				<div class="margin-form">
					<input type="text" size="5" name="nbr" value="'.Tools::safeOutput(Tools::getValue('nbr', (int)(Configuration::get('HOME_FEATURED_NBR')))).'" />
					<p class="clear">'.$this->l('Define the number of products that you would like to display on homepage (default: 8).').'</p>

				</div>
				<center><input type="submit" name="submitHomeFeatured" value="'.$this->l('Save').'" class="button" /></center>
			</fieldset>
		</form>';
		return $output;
	}

	public function hookDisplayHeader($params)
	{
		$this->hookHeader($params);
	}

	public function hookHeader($params)
	{
		$this->context->controller->addCSS(($this->_path).'homefeatured.css', 'all');
	}

	public function hookDisplayHome($params)
	{
		if (!$this->isCached('homefeatured.tpl', $this->getCacheId('homefeatured')))
		{
			$category = new Category(Context::getContext()->shop->getCategory(), (int)Context::getContext()->language->id);
			$nb = (int)Configuration::get('HOME_FEATURED_NBR');
			$products = $category->getProducts((int)Context::getContext()->language->id, 1, ($nb ? $nb : 8));

			$this->smarty->assign(array(
				'products' => $products,
				'add_prod_display' => Configuration::get('PS_ATTRIBUTE_CATEGORY_DISPLAY'),
				'homeSize' => Image::getSize(ImageType::getFormatedName('home')),
			));
		}
		return $this->display(__FILE__, 'homefeatured.tpl', $this->getCacheId('homefeatured'));
	}

	public function hookAddProduct($params)
	{
		$this->_clearCache('homefeatured.tpl');
	}

	public function hookUpdateProduct($params)
	{
		$this->_clearCache('homefeatured.tpl');
	}

	public function hookDeleteProduct($params)
	{
		$this->_clearCache('homefeatured.tpl');
	}
}
