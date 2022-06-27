<?php

if (!defined('_PS_VERSION_'))
	exit;

class ProductsByCategorySlider extends Module
{

	public function __construct()
	{
		$this->name = 'productsbycategoryslider';
		$this->version = '1.1.3';
		$this->author = 'PrestaShop';
		$this->module_key = '01aef4e0fb1921f05f6d99d421ee7013';
		$this->tab = 'front_office_features';
		$this->need_instance = 0;

		parent::__construct();

		$this->displayName = $this->l('Products By Category Slider');
		$this->description = $this->l('Display products of the same category on the product page with a slider.');

		if (!$this->isRegisteredInHook('header'))
			$this->registerHook('header');
	}

	public function installDB()
	{
		return Db::getInstance()->Execute('
				CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'categoryslide` (
				`id_product` INT NOT NULL
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');
	}

	public function uninstallDB()
	{
		return Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'categoryslide`');
	}

	public function install()
	{
	 	if (!parent::install() OR !$this->registerHook('productfooter') OR !$this->registerHook('header') OR !$this->installDB())
	 		return false;
	 	return true;
	}

	public function uninstall()
	{
	 	if (!parent::uninstall() OR !$this->uninstallDB())
	 		return false;
	 	return true;
	}

	private function getCurrentProduct($products, $id_current)
	{
		if ($products)
			foreach ($products AS $key => $product)
				if ($product['id_product'] == $id_current)
					return $key;
		return false;
	}

	public function getContent()
	{
		global $smarty, $cookie;

		$conf = '';
		// If user change products to show on front
		if (Tools::isSubmit('submit') && !empty($_POST))
		{
			$productsInBase = $this->selectProductsInBase();
			foreach ($_POST as $product => $id)
				if (!in_array($id, $productsInBase) && $product != "submit")
				Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('INSERT INTO `'._DB_PREFIX_.'categoryslide` VALUES ('.(int)$id.')');
			foreach ($productsInBase as $product => $id)
				if (!in_array($id, $_POST))
				Db::getInstance(_PS_USE_SQL_SLAVE_)->Execute('DELETE FROM `'._DB_PREFIX_.'categoryslide` WHERE id_product = '.(int)$id);
			$conf = $this->displayConfirmation($this->l('Update finished'));
		}

		$manufacturers = Category::getCategories((int)($cookie->id_lang));
		foreach ($manufacturers as $category => $val)
			foreach ($val as $tab => $infos)
				$tabCategories[] = $infos["infos"];
		$taxes = Product::getTaxCalculationMethod();

		foreach ($tabCategories as $tabCategory)
		{
			$productsList = new Product();
			$products = $productsList->getproducts((int)($cookie->id_lang), 0, 100, 'id_product', 'asc'); /* 100 products max. */
			foreach ($products as $product => $val)
			{
				if ($taxes == 0 OR $taxes == 2)
					$products[$product]['displayed_price'] = Product::getPriceStatic((int)$val['id_product'], true, NULL, 2);
				elseif ($taxes == 1)
				$products[$product]['displayed_price'] = Product::getPriceStatic((int)$val['id_product'], false, NULL, 2);
				$products[$product]['disabled'] = $this->selectProductInBase((int)$val['id_product']);
			}
		}

		// Assign & display tpl
		$smarty->assign(array(
				'categories' => $tabCategories,
				'products' => $products
		));

		return $conf.$this->display(__FILE__, 'bo.tpl');
	}

	public function selectProductInBase($id)
	{
		$select = '
		SELECT id_product
		FROM `'._DB_PREFIX_.'categoryslide`
		WHERE id_product = '.(int)$id
		;
		if (Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($select))
			return 1;
		return 0;
	}

	public function selectProductsInBase()
	{
		$select = '
		SELECT id_product
		FROM `'._DB_PREFIX_.'categoryslide`
		ORDER BY id_product ASC'
		;
		if ($products = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($select))
			foreach ($products as $product => $id)
			$array[] = (int)$id["id_product"];
		else
			$array[] = 0;
		return $array;
	}

	public function hookProductFooter($params)
	{
		global $smarty, $cookie;

		$idProduct = (int)(Tools::getValue('id_product'));
		$product = new Product((int)($idProduct));
		$productsInBase = $this->selectProductsInBase();

		/* If the visitor has came to this product by a category, use this one */
		if (isset($params['category']->id_category))
			$category = $params['category'];
		/* Else, use the default product category */
		else
		{
			if (isset($product->id_category_default) AND $product->id_category_default > 1)
				$category = New Category((int)($product->id_category_default));
		}

		if (!Validate::isLoadedObject($category) OR !$category->active)
			return;

		// Get infos
		$categoryProducts = $category->getProducts((int)($cookie->id_lang), 1, 100); /* 100 products max. */
		$sizeOfCategoryProducts = (int)sizeof($categoryProducts);
		$middlePosition = 0;

		// Remove current product from the list
		if (is_array($categoryProducts) AND sizeof($categoryProducts) AND shuffle($categoryProducts))
		{
			foreach ($categoryProducts AS $key => $categoryProduct)
				if ($categoryProduct['id_product'] == $idProduct || in_array($categoryProduct["id_product"], $productsInBase))
					unset($categoryProducts[$key]);

			$taxes = Product::getTaxCalculationMethod();
			foreach ($categoryProducts AS $key => $categoryProduct)
				if ($categoryProduct['id_product'] != $idProduct)
				{
					if ($taxes == 0 OR $taxes == 2)
						$categoryProducts[$key]['displayed_price'] = Product::getPriceStatic((int)$categoryProduct['id_product'], true, NULL, 2);
					elseif ($taxes == 1)
						$categoryProducts[$key]['displayed_price'] = Product::getPriceStatic((int)$categoryProduct['id_product'], false, NULL, 2);
				}

			// Get positions
			$middlePosition = round($sizeOfCategoryProducts / 2, 0);
			$productPosition = $this->getCurrentProduct($categoryProducts, (int)$idProduct);

			// Flip middle product with current product
			if ($productPosition)
			{
				$tmp = $categoryProducts[$middlePosition-1];
				$categoryProducts[$middlePosition-1] = $categoryProducts[$productPosition];
				$categoryProducts[$productPosition] = $tmp;
			}

			// If products tab higher than 30, slice it
			if ($sizeOfCategoryProducts > 30)
			{
				$categoryProducts = array_slice($categoryProducts, $middlePosition - 15, 30, true);
				$middlePosition = 15;
			}
		}

		// Display tpl
		$smarty->assign('categoryProducts', $categoryProducts);

		return $this->display(__FILE__, 'productsbycategoryslider.tpl');
	}

	public function hookHeader($params)
	{
		$this->context->controller->addCSS($this->_path.'css/style.css', 'all');
		$this->context->controller->addJS($this->_path.'js/jquery.bxSlider.categoryslider.js');
		$this->context->controller->addJS($this->_path.'js/jquery.easing.1.3.js');
	}
}