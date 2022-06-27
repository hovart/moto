<?php

class BlockBestSellerz extends Module
{
	private $_html = '';
	private $_postErrors = array();

	function __construct()
	{
		$this->name = 'blockbestsellerz';
		$this->tab = 'Blocks';
		$this->version = 1.0;

		parent::__construct();

		$this->displayName = $this->l('Top sellers block (zapalm version)');
		$this->description = $this->l('Add a block that displaying the shop\'s top sellers. In this version you will found a new design and two new options: you can set the number of products to be displayed in this block and you can turn on/off the showing random top sellers.');
	}

	public function install()
	{

		if (parent::install() == false OR $this->registerHook('rightColumn') == false OR $this->registerHook('updateOrderStatus') == false OR ProductSale::fillProductSales() == false OR Configuration::updateValue('PRODUCTS_BESTSELLERS_NBR', 4) == false OR Configuration::updateValue('PRODUCTS_BESTSELLERS_RANDOM', 1) == false)
			return false;
		return true;
	}

	public function getContent()
	{
		$output = '<h2>'.$this->displayName.'</h2>';
		if (Tools::isSubmit('submitBlockViewed'))
		{
			if (!$productNbr = Tools::getValue('productNbr') OR empty($productNbr))
				$output .= '<div class="alert error">'.$this->l('You must fill in the \'Products displayed\' field').'</div>';
			elseif (intval($productNbr) == 0)
				$output .= '<div class="alert error">'.$this->l('Invalid number of products.').'</div>';
			else
			{
				Configuration::updateValue('PRODUCTS_BESTSELLERS_NBR', intval($productNbr));
				Configuration::updateValue('PRODUCTS_BESTSELLERS_RANDOM', intval(Tools::getValue('PRODUCTS_BESTSELLERS_RANDOM')));
				$output .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('Confirmation').'" />'.$this->l('Settings updated').'</div>';
			}
		}
		return $output.$this->displayForm();
	}

	public function displayForm()
	{
		global $cookie;

		$output = '
			<fieldset style="width: 400px">
				<legend><img src="'.$this->_path.'logo.gif" alt="" title="" />'.$this->l('Settings').'</legend>
					<form action="'.$_SERVER['REQUEST_URI'].'" method="post">
						<label>'.$this->l('Products displayed').'</label>
						<div class="margin-form">
							<input type="text" name="productNbr" value="'.Configuration::get('PRODUCTS_BESTSELLERS_NBR').'" />
							<p class="clear">'.$this->l('Define the number of products displayed in this block').'</p>
						</div>
						<label>'.$this->l('Show bestsellers randomly').'</label>
						<div class="margin-form">
							<input type="checkbox" name="PRODUCTS_BESTSELLERS_RANDOM"  value="1" '.(Configuration::get('PRODUCTS_BESTSELLERS_RANDOM') ? 'checked="checked"' : '').' />
							<p class="clear">'.$this->l('Check it, if you whant to show bestsellers randomly').'</p>
						</div>				
						<center><input type="submit" name="submitBlockViewed" value="'.$this->l('Save').'" class="button" /></center>
					</form>
			</fieldset>
			<br class="clear">
		';

		$iso_code = Db::getInstance()->ExecuteS('SELECT `iso_code` FROM `'._DB_PREFIX_.'lang` WHERE `active`=1 AND `id_lang`='.$cookie->id_lang);
		$output.= '
				<fieldset style="width: 400px;">
					<legend><img src="../img/admin/manufacturers.gif" /> '.$this->l('Module info').'</legend>
					<div id="dev_div">
						<span><b>'.$this->l('Version').':</b> v1.0</span><br>
						<span><b>'.$this->l('License').':</b> '.$this->l('free and open').'</span><br>
						<span><b>'.$this->l('Forums').':</b> <a class="link" href="http://www.prestashop.com/forums/viewthread/66896/" target="_blank">'.$this->l('english').'</a>, <a class="link" href="http://prestadev.ru/forum/tema-1511.html" target="_blank">'.$this->l('russian').'</a></span><br>
						<span><b>'.$this->l('Website').':</b> <a class="link" href="http://modulez.ru'.($iso_code[0]['iso_code'] == 'ru' ? '' : '/en/').' " target="_blank">modulez.ru'.($iso_code[0]['iso_code'] == 'ru' ? '' : '/en/').'</a><br>
						<span><b>'.$this->l('Contact').':</b> <a class="link" href="http://modulez.ru'.($iso_code[0]['iso_code'] == 'ru' ? '/feedback.php' : '/en/feedback.php').' " target="_blank">modulez.ru'.($iso_code[0]['iso_code'] == 'ru' ? '/feedback.php' : '/en/feedback.php').'</a><br>
						<span style="font-style:italic">('.$this->l('please, send me a message in russian or english only').')</span></span><br>
						<br>
						<span style="font-style:italic">'.$this->l('Thank you for the using this module').'</span>&nbsp;&nbsp;<img src="../modules/orderzeditor/zapalm24x24.jpg" />
					</div>
				</fieldset>
				<br class="clear">
		';

		return $output;
	}

	public function getBestSalesLight($id_lang, $nbProducts = 4, $random = true, $randomNumberProducts = 4)
	{
		global $link, $cookie;

		$sql = '
		SELECT p.id_product, pl.`link_rewrite`, pl.`name`, pl.`description_short`, i.`id_image`, il.`legend`, ps.`quantity` AS sales, p.`ean13`, cl.`link_rewrite` AS category
		FROM `'._DB_PREFIX_.'product_sale` ps 
		LEFT JOIN `'._DB_PREFIX_.'product` p ON ps.`id_product` = p.`id_product`
		LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` AND i.`cover` = 1)
		LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.intval($id_lang).')
		LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (cl.`id_category` = p.`id_category_default` AND cl.`id_lang` = '.intval($id_lang).')
		WHERE p.`active` = 1
		AND p.`id_product` IN (
			SELECT cp.`id_product`
			FROM `'._DB_PREFIX_.'category_group` cg
			LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_category` = cg.`id_category`)
			WHERE cg.`id_group` '.(!$cookie->id_customer ?  '= 1' : 'IN (SELECT id_group FROM '._DB_PREFIX_.'customer_group WHERE id_customer = '.intval($cookie->id_customer).')').'
		)
		GROUP BY p.`id_product`';

		if ($random === true)
		{
			$sql .= ' ORDER BY RAND()';
			$sql .= ' LIMIT 0, '.intval($randomNumberProducts);
		}
		else
		{
			$sql .= ' ORDER BY sales DESC'.
				' LIMIT 0, '.intval($nbProducts);
		}

		$result = Db::getInstance()->ExecuteS($sql);

		if (!$result)
			return $result;

		foreach ($result AS &$row)
		{
			$row['link'] = $link->getProductLink($row['id_product'], $row['link_rewrite'], $row['category'], $row['ean13']);
			$row['id_image'] = Product::defineProductImage($row);
		}

		return $result;
	}

	function hookRightColumn($params)
	{
		global $smarty;
		$currency = new Currency(intval($params['cookie']->id_currency));

		$nb = Configuration::get('PRODUCTS_BESTSELLERS_NBR');
		if (intval(Configuration::get('PRODUCTS_BESTSELLERS_RANDOM'))) {
			$bestsellers = $this->getBestSalesLight(intval($params['cookie']->id_lang), ($nb ? $nb : 4), true, ($nb ? $nb : 4));
		}
		else {
			$bestsellers = ProductSale::getBestSalesLight(intval($params['cookie']->id_lang), 0, ($nb ? $nb : 4));
		}

		$best_sellers = array();
		foreach ($bestsellers AS $bestseller)
		{
			$bestseller['price'] = Tools::displayPrice(Product::getPriceStatic(intval($bestseller['id_product'])), $currency);
			$best_sellers[] = $bestseller;
		}
		$smarty->assign(array(
			'best_sellers' => $best_sellers,
			'mediumSize' => Image::getSize(ImageType::getFormatedName('medium_default')),
			'smallSize' => Image::getSize(ImageType::getFormatedName('small'))
		));
		return $this->display(__FILE__, 'blockbestsellerz.tpl');
	}

	function hookLeftColumn($params)
	{
		return $this->hookRightColumn($params);
	}
}

?>
