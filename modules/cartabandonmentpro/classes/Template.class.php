<?php
class Template extends ObjectModel {

	public $id_template = null;
	public $model = null;
	private $fields = array();
	private $wich_template = 0;
	private $name = '';

	public function __construct($id_template = null, $model = null, $wich_template = 0) {
		$this->id_template = $id_template;
		$this->model = $model;
		$this->wich_template = $wich_template;
	}

	private function insertTemplate($id_template, $active) {

	}

	public function save($null_values = false, $autodate = true) {
		if (!is_null($this->id_template) && $this->id_template > 0) {
			$active = TemplateController::isActive($this->id_template);
			$id_template = $this->id_template;
		} else {
			$active = 1;
			$id_template = 'NULL';
		}
		$query = "REPLACE INTO " . _DB_PREFIX_ . "cartabandonment_template VALUES (" . pSQL($id_template) . ", " . (int) $this->model->getId() . ", '" . pSQL($this->name) . "', " . Tools::getValue('language') . ", " . pSQL(Tools::getValue('id_shop')) . ", " . $active . ", 1)";

		if (!Db::getInstance()->Execute($query))
			return false;

		$this->id_template = Db::getInstance()->Insert_ID();

		$content = $this->model->getContent();

		$this->editContent($content);

		$iso = Language::getIsoById(Tools::getValue('language'));
		CartAbandonmentPro::initDirectory('mails/' . $iso);

		if(!is_writable('../modules/cartabandonmentpro/mails/'))
		{
			$query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_template";
			Db::getInstance()->Execute($query);
			$query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_remind_lang";
			Db::getInstance()->Execute($query);
			return false;
		}
		$fp = fopen('../modules/cartabandonmentpro/mails/' . $iso . '/' . $this->id_template . '.html', 'w+');
		fwrite($fp, $content);
		fclose($fp);

		$content = $this->model->getContentEdit($this->wich_template);
		$this->editContent($content, false);

		if(!is_writable('../modules/cartabandonmentpro/tpls/'))
		{
			$query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_template";
			Db::getInstance()->Execute($query);
			$query = "TRUNCATE " . _DB_PREFIX_ . "cartabandonment_remind_lang";
			Db::getInstance()->Execute($query);
			return false;
		}

		$fp = fopen('../modules/cartabandonmentpro/tpls/' . $this->id_template . '.html', 'w+');
		fwrite($fp, $content);
		fclose($fp);

		return $this->id_template;
	}

	// This function edits the newsletter
	// left column, right column, center column and the colors
	private function editContent(&$content, $save = true) {
		$this->editLeftColumn($content, $save);
		$this->editRightColumn($content, $save);
		$this->editCenter($content, $save);
		$this->editColors($content, $save);
		$context = Context::getContext();
		$logo = $context->shop->getBaseUrl() . 'img/' . Configuration::get('PS_LOGO');
		$content = str_replace('%logo%', $logo, $content);
	}

	// Replace all content in left column
	private function editLeftColumn(&$content, $save = true) {
		if (!$this->model->getLeftColumn())
			return false;
		for ($nb = 1; $nb <= $this->model->getTxtsLeft(); $nb++) {
			$content = str_replace('%left_' . $nb . '%', Tools::getValue('left_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template), $content);
			if ($save)
				$this->saveColumn('left', $nb, Tools::getValue('left_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template));
		}
	}

	// Replace all content in right column
	private function editRightColumn(&$content, $save = true) {
		if (!$this->model->getRightColumn())
			return false;
		for ($nb = 1; $nb <= $this->model->getTxtsRight(); $nb++) {
			$content = str_replace('%right_' . $nb . '%', Tools::getValue('right_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template), $content);
			if ($save)
				$this->saveColumn('right', $nb, Tools::getValue('right_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template));
		}
	}

	// Replace all content in center column
	private function editCenter(&$content, $save = true) {
		for ($nb = 1; $nb <= $this->model->getTxtsCenter(); $nb++) {
			$content = str_replace('%center_' . $nb . '%', Tools::getValue('center_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template), $content);
			if ($save)
				$this->saveColumn('center', $nb, Tools::getValue('center_' . $nb . '_' . $this->wich_template));
		}
	}

	// Replace all colors
	private function editColors(&$content, $save = true) {
		for ($nb = 1; $nb <= $this->model->getColors(); $nb++) {
			$content = str_replace('%color_' . $nb . '%', Tools::getValue('color_picker_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template), $content);
			if ($save) {
				Db::getInstance()->Execute("DELETE FROM " . _DB_PREFIX_ . "cartabandonment_template_color WHERE id_template = " . (int) $this->id_template);
				Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "cartabandonment_template_color VALUES (NULL, " . (int) $this->id_template . ", " . $nb . ", '" . Tools::getValue('color_picker_' . $this->model->getId() . '_' . $nb . '_' . $this->wich_template) . "')");
			}
		}
	}

	// Save One column in database
	private function saveColumn($column, $id_field, $value) {
		if (!isset($column) || !isset($id_field) || !isset($value))
			return false;
		return Db::getInstance()->Execute("INSERT INTO " . _DB_PREFIX_ . "cartabandonment_template_field VALUES (NULL, " . (int) $this->id_template . ", " . $id_field . ", '" . $value . "', '" . $column . "')");
	}

	// This function replace all %TAGS% before sending the newsletter
	// if id_cart is NULL, it means that this is a test (test send or preview)
	public function editTemplate($content, $id_cart = NULL, $id_lang = null, $id_shop = null) {
		$lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
		if (is_null($id_lang))
			$id_lang = $lang_default;
		if (is_null($id_shop))
			$id_shop = (int)Context::getContext()->shop->id;

		// Accept all, no verifications of quantities and/or stock gestion
		$query = '
						SELECT ca.id_cart, pl.id_product, pl.name, c.firstname, c.lastname, c.id_lang, ca.secure_key, ca.id_customer, gl.name as gender_name
						FROM `' . _DB_PREFIX_ . 'cart` ca
						JOIN ' . _DB_PREFIX_ . 'cart_product cp ON ca.id_cart = cp.id_cart
						JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
						JOIN ' . _DB_PREFIX_ . 'product_lang pl ON cp.id_product = pl.id_product
						LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
						WHERE pl.id_lang = c.id_lang AND (pl.id_lang = ' . (int) $id_lang . ' OR pl.id_lang = ' . $lang_default . ')';

		$stock_management = (int)Configuration::get('PS_STOCK_MANAGEMENT');
		$general_stock_management = (int)Configuration::get('PS_ORDER_OUT_OF_STOCK');

		if ($stock_management != 0 && $general_stock_management == 0)
		{
				$query = '
								SELECT ca.id_cart, pl.id_product, pl.name, c.firstname, c.lastname, c.id_lang, ca.secure_key, ca.id_customer, gl.name as gender_name, pl.link_rewrite
								FROM `' . _DB_PREFIX_ . 'cart` ca
								JOIN ' . _DB_PREFIX_ . 'cart_product cp ON ca.id_cart = cp.id_cart
								JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
								JOIN ' . _DB_PREFIX_ . 'product_lang pl ON cp.id_product = pl.id_product
								LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
								INNER JOIN ' . _DB_PREFIX_ . 'stock_available sa ON sa.id_product = cp.id_product
								INNER JOIN ' . _DB_PREFIX_ . 'product p ON p.id_product = cp.id_product
								WHERE ( (sa.out_of_stock != 1 AND sa.quantity > 0) OR sa.out_of_stock = 1)
								AND sa.id_product_attribute = cp.id_product_attribute
								AND p.available_for_order = 1
								AND pl.id_lang = c.id_lang
								AND (pl.id_lang = ' . (int) $id_lang . ' OR pl.id_lang = ' . $lang_default . ')';
		}

		if (is_null($id_cart))
			$query .= ' LIMIT 1';
		else
			$query .= ' AND ca.id_cart = ' . (int) $id_cart;

		$products = Db::getInstance()->ExecuteS($query);

		$html = '<table>';
		// $cartProducts = $this->model->getCartProducts();

		$link = new Link();
		$products_added = array();
		foreach ($products as $product) {
			if (in_array($product['id_product'], $products_added))
				continue;
			$products_added[] = $product['id_product'];
			$img = Template::getImage($product['id_product'], $id_lang);
			// $img = Product::getCover($product['id_product']);
            $link = Context::getContext()->link->getProductLink($product['id_product'], $product['link_rewrite']);
			$html .= '<tr><td colspan="3" height="10">&nbsp;</td></tr>';
			$html .= '<tr>';
			$html .= '<td width="1">';
			$html .= '<a target="_blank" style="text-decoration: none;" href="' . $link . '"><img width="170" valign="bottom" src="http://' . $img . '"></a>';
			$html .= '</td>';
			$html .= '<td align="left" valign="bottom">&nbsp;&nbsp;&nbsp;&nbsp;<a style="text-decoration: none;" target="_blank" href="' . $link . '">' . $product['name'] . '</a></td>';
			$html .= '</tr>';
			// $product .= str_replace('%IMG%', '<img src="http://' . $img . '">', $cartProducts);
			// $product .= str_replace('%NAME%', Product::getProductName($product['id_product'], null, $product['id_lang']), $product);
			// $html .= $product;
		}
		$html .= '</table>';

		if (empty($products)) {
			$products = Db::getInstance()->ExecuteS('
			SELECT c.firstname, c.lastname, c.id_lang, gl.name as gender_name
			FROM `' . _DB_PREFIX_ . 'cart` ca
			JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
			JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
			WHERE
			ca.id_cart = ' . (int) $id_cart);
		}
		$content = str_replace('%CART_PRODUCTS%', $html, $content);
		$content = str_replace('%SHOP_OPEN_LINK%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . '" target="_blank">', $content);
		$content = str_replace('%SHOP_CLOSE_LINK%', '</a>', $content);
		$content = str_replace('%FIRSTNAME%', $products[0]['firstname'], $content);
		$content = str_replace('%LASTNAME%', $products[0]['lastname'], $content);
		$content = str_replace('%GENDER%', $products[0]['gender_name'], $content);
		$content = str_replace('%SHOP_LINK_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . '" target="_blank">', $content);
		$content = str_replace('%SHOP_LINK_CLOSE%', '</a>', $content);
		$content = str_replace('%UNUBSCRIBE_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . '" target="_blank">', $content);
		$content = str_replace('%UNUBSCRIBE_CLOSE%', '</a>', $content);
        $content = str_replace('%UNSUBSCRIBE_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . '" target="_blank">', $content);
		$content = str_replace('%UNSUBSCRIBE_CLOSE%', '</a>', $content);

        $token_cart = md5(_COOKIE_KEY_ . 'recover_cart_' . $id_cart);
		$token = Configuration::get('CARTABAND_TOKEN');

        $content = str_replace('%CART_LINK_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . 'modules/cartabandonmentpro/redirectCart.php?token=' . $token . '&token_cart=' . $token_cart . '&id_cart=' . $id_cart . '&id_customer=' . $products[0]['id_customer'] . '&secure_key=' . $products[0]['secure_key'] . '" target="_blank">', $content);
		$content = str_replace('%CART_LINK_CLOSE%', '</a>', $content);

		$shopName = Db::getInstance()->getValue('SELECT `name` FROM '._DB_PREFIX_.'shop WHERE `id_shop` =  \''.(int)$id_shop.'\'');
		$content = str_replace('%SHOP_NAME%', $shopName, $content);

		return $content;
	}

	private static function editCart($content, $id_cart = NULL, $id_lang = 1) {

	}

	public static function editTitleBeforeSending($title, $id_cart = NULL, $id_lang = 1) {
		if (is_null($id_lang))
			$id_lang = Configuration::get('PS_LANG_DEFAULT');
		$query = '
				SELECT c.firstname, c.lastname, gl.name as gender_name
				FROM `' . _DB_PREFIX_ . 'cart` ca
				JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
				LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender';
		if (is_null($id_cart))
			$query .= ' LIMIT 1';
		else
			$query .= ' WHERE ca.id_cart = ' . (int) $id_cart;

		$products = Db::getInstance()->ExecuteS($query);

		$title = str_replace('%FIRSTNAME%', $products[0]['firstname'], $title);
		$title = str_replace('%LASTNAME%', $products[0]['lastname'], $title);
		$title = str_replace('%GENDER%', $products[0]['gender_name'], $title);
		return $title;
	}

	public static function editDiscount($voucher, $content, $id_lang) {
		$value = false;
		$type = '';
		if ($voucher->reduction_percent > 0) {
			$value = $voucher->reduction_percent;
			$discount_txt = Configuration::get('CARTABAND_DISC_VAL', $id_lang);
			$type = "%";
		} else if ($voucher->reduction_amount > 0) {
			$value = $voucher->reduction_amount;
			$discount_txt = Configuration::get('CARTABAND_DISC_VAL', $id_lang);
			$type = Currency::getDefaultCurrency()->sign;
		} else
			$discount_txt = Configuration::get('CARTABAND_SHIPP_VAL', $id_lang);

		$dates = explode(' ', $voucher->date_to);
		$dates = explode('-', $dates[0]);

		$discount_txt = str_replace('%DISCOUNT_VALUE%', $value . ' ' . $type, $discount_txt);
		$discount_txt = str_replace('%DISCOUNT_VALID_DAY%', $dates[2], $discount_txt);
		$discount_txt = str_replace('%DISCOUNT_VALID_MONTH%', $dates[1], $discount_txt);
		$discount_txt = str_replace('%DISCOUNT_VALID_YEAR%', $dates[0], $discount_txt);
		$discount_txt = str_replace('%DISCOUNT_CODE%', $voucher->code, $discount_txt);

		$content = str_replace('%DISCOUNT_TXT%', $discount_txt, $content);

		return $content;
	}

	public function checkStock($product_id, $id_shop)
	{
			$general_stock_management = (int)Configuration::get('PS_ORDER_OUT_OF_STOCK');
			/* General flag that checks if shop can sell out of stock products */
			$flag = StockAvailable::outOfStock($product_id, $id_shop);
			/* Flag on specific products that checks if shop can sell the product when out of stock */
			$quantity_available = StockAvailable::getQuantityAvailableByProduct($product_id, null, $id_shop);
			/* Checks the quantity available of the current product */

			if (($general_stock_management == 1 && $flag == 2) || $flag == 1 || $quantity_available > 0)	/* Checking if available stock */
					return 1;
			return 0;
	}

	public static function editBeforeSending($content, $id_cart = NULL, $id_lang = 1, $wichRemind, $id_shop = NULL) {
		$lang_default = Configuration::get('PS_LANG_DEFAULT');
		if (is_null($id_shop))
			$id_shop = 1;

		// Accept all, no verifications of quantities and/or stock gestion
		$query = '
						SELECT ca.id_cart, pl.id_product, pl.name, c.firstname, c.lastname, c.id_lang, ca.secure_key, ca.id_customer, gl.name as gender_name
						FROM `' . _DB_PREFIX_ . 'cart` ca
						JOIN ' . _DB_PREFIX_ . 'cart_product cp ON ca.id_cart = cp.id_cart
						JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
						JOIN ' . _DB_PREFIX_ . 'product_lang pl ON cp.id_product = pl.id_product
						LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON (c.id_gender = gl.id_gender AND (gl.id_lang = c.id_lang OR gl.id_lang = ' . $lang_default . '))
						WHERE pl.id_lang = c.id_lang AND (pl.id_lang = ' . (int) $id_lang . ' OR pl.id_lang = ' . $lang_default . ')';
		$stock_management = (int)Configuration::get('PS_STOCK_MANAGEMENT');
		$general_stock_management = (int)Configuration::get('PS_ORDER_OUT_OF_STOCK');

		if ($stock_management != 0 && $general_stock_management == 0)
		{
				$query = '
								SELECT ca.id_cart, pl.id_product, pl.name, c.firstname, c.lastname, c.id_lang, ca.secure_key, ca.id_customer, gl.name as gender_name, pl.link_rewrite
								FROM `' . _DB_PREFIX_ . 'cart` ca
								JOIN ' . _DB_PREFIX_ . 'cart_product cp ON ca.id_cart = cp.id_cart
								JOIN ' . _DB_PREFIX_ . 'customer c ON ca.id_customer = c.id_customer
								JOIN ' . _DB_PREFIX_ . 'product_lang pl ON cp.id_product = pl.id_product
								LEFT JOIN ' . _DB_PREFIX_ . 'gender_lang gl ON c.id_gender = gl.id_gender
								INNER JOIN ' . _DB_PREFIX_ . 'stock_available sa ON sa.id_product = cp.id_product
								INNER JOIN ' . _DB_PREFIX_ . 'product p ON p.id_product = cp.id_product
								WHERE ((sa.out_of_stock != 1 AND sa.quantity > 0) OR sa.out_of_stock = 1)
								AND sa.id_product_attribute = cp.id_product_attribute
								AND p.available_for_order = 1
								AND pl.id_lang = c.id_lang
								AND (pl.id_lang = ' . (int) $id_lang . ' OR pl.id_lang = ' . $lang_default . ')';
		}

		if (is_null($id_cart))
			$query .= ' LIMIT 1';
		else
			$query .= ' AND ca.id_cart = ' . (int) $id_cart;

		$products = Db::getInstance()->ExecuteS($query);

		if (empty($products))
			return false;

		if (strpos($content, '%CART_PRODUCTS%')) {
			$html = '<table width="100%">';
			// $cartProducts = $this->model->getCartProducts();

			$link = new Link();
			$products_added = array();
			foreach ($products as $product) {
				if (in_array($product['id_product'], $products_added))
					continue;
				$products_added[] = $product['id_product'];
				$img = Template::getImage($product['id_product'], $id_lang);
				// $img = Product::getCover($product['id_product']);
                $link = Context::getContext()->link->getProductLink($product['id_product'], $product['link_rewrite']);
				$html .= '<tr><td height="10">&nbsp;</td></tr>';
				$html .= '<tr>';
				$html .= '<td>';
				$html .= '<a width="1" target="_blank" style="text-decoration: none;" href="' . $link . '"><img width="170" valign="bottom" src="http://' . $img . '"></a>';
				$html .= '</td>';
				$html .= '<td align="left" valign="bottom"><a style="text-decoration: none;" target="_blank" href="' . $link . '">' . $product['name'] . '</a></td>';
				$html .= '</tr>';
				// $product .= str_replace('%IMG%', '<img src="http://' . $img . '">', $cartProducts);
				// $product .= str_replace('%NAME%', Product::getProductName($product['id_product'], null, $product['id_lang']), $product);
				// $html .= $product;
			}
			$html .= '</table>';

			$content = str_replace('%CART_PRODUCTS%', $html, $content);
		}
		$token_cart = md5(_COOKIE_KEY_ . 'recover_cart_' . $id_cart);
		$token = Configuration::get('CARTABAND_TOKEN');
		$content = str_replace('%SHOP_LINK_OPEN%', '
		<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . 'modules/cartabandonmentpro/redirectShop.php?token=' . $token . '&wichRemind=' . $wichRemind . '&id_cart=' . $id_cart . '&link=shop&id_customer=' . $products[0]['id_customer'] . '" target="_blank">', $content);
		$content = str_replace('%SHOP_LINK_CLOSE%', '</a>', $content);
		$content = str_replace('%CART_LINK_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . 'modules/cartabandonmentpro/redirectShop.php?token=' . $token . '&token_cart=' . $token_cart . '&wichRemind=' . $wichRemind . '&id_cart=' . $id_cart . '&link=cart&id_customer=' . $products[0]['id_customer'] . '&recover_cart=' . $id_cart . '&secure_key=' . $products[0]['secure_key'] . '" target="_blank">', $content);
		$content = str_replace('%CART_LINK_CLOSE%', '</a>', $content);

		$content = str_replace('%FIRSTNAME%', $products[0]['firstname'], $content);
		$content = str_replace('%LASTNAME%', $products[0]['lastname'], $content);
		$content = str_replace('%GENDER%', $products[0]['gender_name'], $content);

		$shopName = Db::getInstance()->getValue('SELECT `name` FROM '._DB_PREFIX_.'shop WHERE `id_shop` =  \''.(int)$id_shop.'\'');
		$content = str_replace('%SHOP_NAME%', $shopName, $content);

		$content = str_replace('%UNUBSCRIBE_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . 'modules/cartabandonmentpro/redirectShop.php?token=' . $token . '&token_cart=' . $token_cart . '&wichRemind=' . $wichRemind . '&id_cart=' . $id_cart . '&link=unsubscribe&id_customer=' . $products[0]['id_customer'] . '&recover_cart=' . $id_cart . '&secure_key=' . $products[0]['secure_key'] . '" target="_blank">', $content);
		$content = str_replace('%UNUBSCRIBE_CLOSE%', '</a>', $content);
        $content = str_replace('%UNSUBSCRIBE_OPEN%', '<a href="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . 'modules/cartabandonmentpro/redirectShop.php?token=' . $token . '&token_cart=' . $token_cart . '&wichRemind=' . $wichRemind . '&id_cart=' . $id_cart . '&link=unsubscribe&id_customer=' . $products[0]['id_customer'] . '&recover_cart=' . $id_cart . '&secure_key=' . $products[0]['secure_key'] . '" target="_blank">', $content);
		$content = str_replace('%UNSUBSCRIBE_CLOSE%', '</a>', $content);


		return '<img width="1" height="1" src="' . Tools::getShopDomain(true) . __PS_BASE_URI__ . 'modules/cartabandonmentpro/visualize.php?token=' . $token . '&wichRemind=' . $wichRemind . '&id_cart=' . $id_cart . '"> ' . $content;
	}

	private static function getImage($p_id, $id_lang) {
		$images = Db::getInstance()->ExecuteS('
			SELECT id_image
			FROM ' . _DB_PREFIX_ . 'image
			WHERE id_product = ' . (int) $p_id . '
			ORDER BY cover DESC');

		$query = 'SELECT link_rewrite FROM ' . _DB_PREFIX_ . 'product_lang
				  WHERE id_product = ' . (int) $p_id . ' AND id_lang = ' . (int) $id_lang;

		$link_rewrite = Db::getInstance('PS_USE_SQL_SLAVE')->ExecuteS($query);
		$link = new Link();

		if (isset($images[0])) {
			$images = $images[0];
		} else {
			$images = Array(
				'id_image' => 0
			);
		}

		if (version_compare('PS_VERSION', '1.5.0.17') >= 0) {
			if (Configuration::get('PS_LEGACY_IMAGES')) {
				$imageLink = Tools::getShopDomain(true) . '/img/p/' . $p_id . '-' . $images['id_image'] . '-home_default.jpg';
			} else {
				$imageLink = $link->getImageLink($link_rewrite[0]['link_rewrite'], $images['id_image']);
			}
		} else {
			$imageLink = $link->getImageLink($link_rewrite[0]['link_rewrite'], (int) $p_id . '-' . (int) $images['id_image']);
			$imageLink = str_replace('.jpg', '-medium_default.jpg', $imageLink);
			$imageLink = str_replace('.png', '-medium_default.png', $imageLink);
		}
		return $imageLink;
	}

	public function setWichTemplate($val) {
		$this->wich_template = $val;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

}
