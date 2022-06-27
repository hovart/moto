<?php
/**
 * All-in-one Rewards Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Yann BONNAILLIE - ByWEB
 * @copyright 2012-2014 Yann BONNAILLIE - ByWEB (http://www.prestaplugins.com)
 * @license   Commercial license see license.txt
 * Support by mail  : contact@prestaplugins.com
 * Support on forum : Patanock
 * Support on Skype : Patanock13
 */

if (!defined('_PS_VERSION_'))
	exit;

require_once(dirname(__FILE__).'/RewardsAccountModel.php');

class RewardsModel extends ObjectModel
{
	public $id_reward_state;
	public $id_customer;
	public $id_order;
	public $id_cart_rule;
	public $id_payment;
	public $credits;
	public $plugin;
	public $reason;
	public $date_add;
	public $date_upd;

	public static $definition = array(
		'table' => 'rewards',
		'primary' => 'id_reward',
		'fields' => array(
			'id_reward_state' =>	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_customer' =>		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_cart_rule' =>		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_payment' =>			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'id_order' =>			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'credits' =>			array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => true),
			'plugin' =>				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 20),
			'reason' =>				array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 80),
			'date_add' =>			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
			'date_upd' =>			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		)
	);

	public function save($historize = true, $nullValues = false, $autodate = true)
	{
		if (parent::save($nullValues, $autodate)) {
			// create the account first time a reward is created for that customer
			$rewardsAccount = new RewardsAccountModel($this->id_customer);
			if (!Validate::isLoadedObject($rewardsAccount)) {
				$rewardsAccount->id_customer = $this->id_customer;
				$rewardsAccount->save();
			}

			if ($historize)
				$this->historize();
			return true;
		}
		return false;
	}

	public static function isNotEmpty() {
		Db::getInstance()->ExecuteS('SELECT 1 FROM `'._DB_PREFIX_.'rewards`');
		return (bool)Db::getInstance()->NumRows();
	}

	public static function importFromLoyalty() {
		$pointValue = (float)Configuration::get('PS_LOYALTY_POINT_VALUE');
		if ($pointValue > 0) {
			Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'rewards` (id_reward, id_reward_state, id_customer, id_order, id_cart_rule, credits, plugin, date_add, date_upd)
				SELECT id_loyalty, id_loyalty_state, id_customer, id_order, id_cart_rule, points * ' . $pointValue. ', \'loyalty\', date_add, date_upd FROM `'._DB_PREFIX_.'loyalty`');
			Db::getInstance()->Execute('
				INSERT INTO `'._DB_PREFIX_.'rewards_history` (id_reward, id_reward_state, credits, date_add)
				SELECT id_loyalty, id_loyalty_state, points * ' . $pointValue. ', date_add FROM `'._DB_PREFIX_.'loyalty_history`');
			$row = Db::getInstance()->getRow('SELECT IFNULL(MAX(id_reward),0)+1 AS nextid FROM `'._DB_PREFIX_.'rewards`');
			Db::getInstance()->Execute('ALTER TABLE `'._DB_PREFIX_.'rewards` AUTO_INCREMENT=' . $row['nextid']);
		}
	}

	public static function getByOrderId($id_order)
	{
		if (!Validate::isUnsignedId($id_order))
			return false;

		$result = Db::getInstance()->getRow('
		SELECT r.id_reward
		FROM `'._DB_PREFIX_.'rewards` r
		WHERE r.plugin=\'loyalty\' AND r.id_order = '.(int)$id_order);

		return isset($result['id_reward']) ? $result['id_reward'] : false;
	}

	// renvoie le prix total d'une commande pour le calcul de la récompense, dans la devise du panier sauf si $bTotal = false où on convertit dans la devise par défaut
	public static function getOrderPriceForReward($order, $bTotal, $bDiscountAllowed, $allowedCategories = NULL)
	{
		if (!Validate::isLoadedObject($order))
			return false;

		$returned = array();
		$orderDetails = $order->getProductsDetail();
		if (is_array($orderDetails)) {
			foreach($orderDetails as $detail) {
				$returned[(int)$detail['product_id'].'_'.(int)$detail['product_attribute_id']] = (int)$detail['product_quantity_return'];
			}
		}

		$result = self::getCartPriceForReward(new Cart((int)$order->id_cart), $bTotal, $bDiscountAllowed, $allowedCategories, NULL, $returned);
		if (!$bTotal)
			$result = round(Tools::convertPrice($result, $order->id_currency, false), 2);
		return $result;
	}

	// indique si un produit bénéficie d'une réduction. Les prix dégressifs renvoient faux pour donner quand même des récompenses.
	public static function isDiscountedProduct($id_product, $id_product_attribute=0)
	{
		$context = Context::getContext();
		$cart_quantity = !$context->cart ? 0 : Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT SUM(`quantity`)
			FROM `'._DB_PREFIX_.'cart_product`
			WHERE `id_product` = '.(int)$id_product.' AND `id_cart` = '.(int)$context->cart->id.' AND `id_product_attribute` = '.(int)$id_product_attribute
		);
		$quantity = $cart_quantity ? $cart_quantity : 1;
		$ids = Address::getCountryAndState((int)$context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
		$id_country = (int)($ids['id_country'] ? $ids['id_country'] : Configuration::get('PS_COUNTRY_DEFAULT'));

		$row = SpecificPrice::getSpecificPrice((int)$id_product, $context->shop->id, (int)$context->currency->id, $id_country, $context->customer->id_default_group, $quantity, (int)$id_product_attribute, 0, 0, $quantity);
		if ($row && ($row['from'] != '0000-00-00 00:00:00' || $row['to'] != '0000-00-00 00:00:00' || $row['from_quantity'] == 1))
			return true;
		return false;
	}

	// renvoie le prix total d'un panier pour le calcul de la récompense, dans la devise du panier
	public static function getCartPriceForReward($cart, $bTotal, $bDiscountAllowed, $allowedCategories = NULL, $newProduct = NULL, $returned = NULL)
	{
		$benefits = false;
		$total = 0;
		$context = Context::getContext();
		$cartProducts = array();
		$taxesEnabled = Product::getTaxCalculationMethod();
		$cart_currency = $context->currency;
		$id_template = 0;

		if (Validate::isLoadedObject($cart)) {
			$cartProducts = $cart->getProducts();
			$taxesEnabled = Product::getTaxCalculationMethod((int)$cart->id_customer);
			$cart_currency = new Currency((int)$cart->id_currency);
			$id_template = (int)MyConf::getIdTemplate('loyalty', (int)$cart->id_customer);
		}

		if (isset($newProduct) && !empty($newProduct)) {
			$cartProductsNew = array();
			$cartProductsNew['id_product'] = (int)$newProduct->id;
			$cartProductsNew['id_product_attribute'] = (int)$newProduct->getIdProductAttributeMostExpensive();
			$cartProductsNew['price'] = number_format($newProduct->getPrice(false, $cartProductsNew['id_product_attribute']), 2, '.', '');
			if ($taxesEnabled != PS_TAX_EXC) {
				$cartProductsNew['price_wt'] = number_format($newProduct->getPrice(true, $cartProductsNew['id_product_attribute']), 2, '.', '');
			}
			$cartProductsNew['cart_quantity'] = 1;
			if ($benefits) {
				$product_attribute = $newProduct->getAttributeCombinationsById($cartProductsNew['id_product_attribute'], (int)(Configuration::get('PS_LANG_DEFAULT')));
				$cartProductsNew['wholesale_price'] = isset($product_attribute[0]['wholesale_price']) && (float)($product_attribute[0]['wholesale_price']) > 0 ? (float) $product_attribute[0]['wholesale_price'] : (float)$newProduct->wholesale_price;
			}
			$cartProducts[] = $cartProductsNew;
		}
		foreach ($cartProducts AS $product) {
			if ((!$bDiscountAllowed && self::isDiscountedProduct($product['id_product'], (int)$product['id_product_attribute'])) || (is_array($allowedCategories) && !Product::idIsOnCategoryId($product['id_product'], $allowedCategories))) {
				if (isset($context->smarty) && is_object($newProduct) && $product['id_product'] == $newProduct->id)
					$context->smarty->assign('no_pts_discounted', 1);
				continue;
			}

			$quantity = (int)($product['cart_quantity'] - (isset($returned[(int)$product['id_product'].'_'.(int)$product['id_product_attribute']]) ? (int)$returned[(int)$product['id_product'].'_'.(int)$product['id_product_attribute']] : 0));
			// si la récompense est basée sur le total du panier
			if ($bTotal) {
				if ($benefits)
					$total += ($product['price'] - ((float)$product['wholesale_price'] * (float)$cart_currency->conversion_rate)) * $quantity;
				else
					$total += ($taxesEnabled == PS_TAX_EXC ? $product['price'] : $product['price_wt']) * $quantity;
			} else {
				$price = $taxesEnabled == PS_TAX_EXC ? $product['price'] : $product['price_wt'];
				$total += (float)RewardsProductModel::getProductReward((int)$product['id_product'], $price, $quantity, $cart_currency->id, $id_template);
			}
		}

		if (Validate::isLoadedObject($cart) && $bTotal)	{
			foreach ($cart->getCartRules(CartRule::FILTER_ACTION_REDUCTION) AS $cartRule)
				$total -= $cartRule['value_real'];
		}
		if ($total < 0)
			$total = 0;

		return $total;
	}

	public static function getCurrencyValue($credits, $idCurrencyTo)
	{
		return round(Tools::convertPrice($credits, Currency::getCurrency((int)$idCurrencyTo)), 2);
	}

	public static function getAllTotalsByCustomer($id_customer)
	{
		$rewards = array();
		$rewards['total'] = 0;
		$rewards[RewardsStateModel::getConvertId()] = 0;
		$rewards[RewardsStateModel::getValidationId()] = 0;
		$rewards[RewardsStateModel::getDefaultId()] = 0;
		$rewards[RewardsStateModel::getReturnPeriodId()] = 0;
		$rewards[RewardsStateModel::getWaitingPaymentId()] = 0;
		$rewards[RewardsStateModel::getPaidId()] = 0;
		$query = '
		SELECT id_reward_state, SUM(r.credits) AS credits
		FROM `'._DB_PREFIX_.'rewards` r
		WHERE r.id_customer = '.(int)($id_customer).'
		GROUP BY id_reward_state';
		$totals = Db::getInstance()->ExecuteS($query);
		foreach($totals as $total) {
			$rewards[$total['id_reward_state']] = (float) $total['credits'];
			if ((int)$total['id_reward_state'] != RewardsStateModel::getCancelId() && (int)$total['id_reward_state'] != RewardsStateModel::getDiscountedId())
				$rewards['total'] += $rewards[$total['id_reward_state']];
		}
		return $rewards;
	}

	public static function getAllByIdCustomer($id_customer, $admin = false, $onlyValidate = false, $pagination = false, $nb = 10, $page = 1, $currency = NULL)
	{
		$context = Context::getContext();

		$query = '
		SELECT r.id_order AS id_order, r.id_reward_state, r.date_add AS date, DATE_ADD(r.date_upd, INTERVAL '.(int)Configuration::get('REWARDS_DURATION').' DAY) AS validity, (o.total_paid - o.total_shipping) AS total_without_shipping, o.id_currency, r.credits, r.id_reward, r.id_reward_state, r.plugin, r.reason, rsl.name AS state
		FROM `'._DB_PREFIX_.'rewards` r
		LEFT JOIN `'._DB_PREFIX_.'orders` o USING (id_order)
		LEFT JOIN `'._DB_PREFIX_.'rewards_state_lang` rsl ON (r.id_reward_state = rsl.id_reward_state AND rsl.id_lang = '.(int)$context->language->id.')
		WHERE r.id_customer = '.(int)($id_customer);
		if ($onlyValidate === true)
			$query .= ' AND r.id_reward_state = '.(int)RewardsStateModel::getValidationId();
		$query .= ' GROUP BY r.id_reward ORDER BY r.date_add DESC '.
		($pagination ? 'LIMIT '.(((int)($page) - 1) * (int)($nb)).', '.(int)($nb) : '');

		$module = new allinone_rewards();
		$rewards = Db::getInstance()->ExecuteS($query);
		foreach($rewards as $key => $reward) {
			if ($currency != NULL) {
				$rewards[$key]['credits'] = self::getCurrencyValue($reward['credits'], $currency);
			}
			if ($reward['plugin'] != 'free') {
				$rewards[$key]['detail'] = $module->{$reward['plugin']}->getDetails($reward, $admin);
			} else {
				$rewards[$key]['detail'] = $reward['reason'];
			}
		}

		return $rewards;
	}

	public static function createDiscount($credits)
	{
		$context = Context::getContext();
		$id_template = (int)MyConf::getIdTemplate('core', (int)$context->customer->id);

		/* Generate a discount code */
		$code = NULL;
		do $code = MyConf::get('REWARDS_VOUCHER_PREFIX', null, $id_template).Tools::passwdGen(6);
		while (CartRule::cartRuleExists($code));

		/* Voucher creation and affectation to the customer */
		$cartRule = new CartRule();
		$cartRule->id_customer = (int)$context->customer->id;
		$cartRule->date_from = date('Y-m-d H:i:s', time() - 1); /* remove 1s because of a strict comparison between dates in getCustomerCartRules */
		$cartRule->date_to = date('Y-m-d H:i:s', time() + (int)MyConf::get('REWARDS_VOUCHER_DURATION', null, $id_template)*24*60*60);
		$cartRule->description = MyConf::get('REWARDS_VOUCHER_DETAILS', (int)$context->language->id, $id_template);
		$cartRule->quantity = 1;
		$cartRule->quantity_per_user = 1;
		$cartRule->highlight = (int)MyConf::get('REWARDS_DISPLAY_CART', null, $id_template);
		$cartRule->partial_use = (int)MyConf::get('REWARDS_VOUCHER_BEHAVIOR', null, $id_template);
		$cartRule->code = $code;
		$cartRule->active = 1;
		$cartRule->reduction_amount = self::getCurrencyValue($credits, $context->currency->id);
		$cartRule->reduction_tax = 1;
		$cartRule->reduction_currency = (int)$context->currency->id;
		$cartRule->minimum_amount = (float)MyConf::get('REWARDS_MINIMAL', null, $id_template);
		$cartRule->minimum_amount_tax = (int)MyConf::get('REWARDS_MINIMAL_TAX', null, $id_template);
		$cartRule->minimum_amount_currency = (int)Configuration::get('PS_CURRENCY_DEFAULT');
		$cartRule->minimum_amount_shipping = (int)MyConf::get('REWARDS_MINIMAL_SHIPPING', null, $id_template);
		$cartRule->cart_rule_restriction = (int)(!(bool)MyConf::get('REWARDS_VOUCHER_CUMUL_S', null, $id_template));

		$languages = Language::getLanguages(true);
		$default_text = MyConf::get('REWARDS_VOUCHER_DETAILS', (int)Configuration::get('PS_LANG_DEFAULT'), $id_template);
		foreach ($languages AS $language)
		{
			$text = MyConf::get('REWARDS_VOUCHER_DETAILS', (int)$language['id_lang'], $id_template);
			$cartRule->name[(int)($language['id_lang'])] = $text ? $text : $default_text;
		}

		$categories = explode(',', MyConf::get('REWARDS_VOUCHER_CATEGORY', null, $id_template));
		if (is_array($categories) && count($categories) > 0)
			$cartRule->product_restriction = 1;
		$cartRule->add();

		/* if this discount is only available for a list of categories */
		if (is_array($categories) && count($categories) > 0) {
			/* cart must contain 1 product from 1 of the selected categories */
			Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_product_rule_group` (`id_cart_rule`, `quantity`) VALUES ('.(int)$cartRule->id.', 1)');
			$id_product_rule_group = Db::getInstance()->Insert_ID();

			/* create the category rule */
			Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_product_rule` (`id_product_rule_group`, `type`) VALUES ('.$id_product_rule_group.', \'categories\')');
			$id_product_rule = Db::getInstance()->Insert_ID();

			/* insert the list of categories */
			$values = array();
			foreach($categories as $category)
				$values[] = '('.(int)$id_product_rule.','.(int)$category.')';
			$values = array_unique($values);
			if (count($values))
				Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'cart_rule_product_rule_value` (`id_product_rule`, `id_item`) VALUES '.implode(',', $values));
		}

		// If the discount has no cart rule restriction, then it must be added to the white list of the other cart rules that have restrictions
		if ((int)MyConf::get('REWARDS_VOUCHER_CUMUL_S', null, $id_template))
		{
			Db::getInstance()->execute('
			INSERT INTO `'._DB_PREFIX_.'cart_rule_combination` (`id_cart_rule_1`, `id_cart_rule_2`) (
				SELECT id_cart_rule, '.(int)$cartRule->id.' FROM `'._DB_PREFIX_.'cart_rule` WHERE cart_rule_restriction = 1
			)');
		}

		/* Register order(s) which contributed to create this discount */
		self::registerDiscount($cartRule);
	}

	public static function registerDiscount($cartRule)
	{
		if (!Validate::isLoadedObject($cartRule))
			die(Tools::displayError('Incorrect object Discount.'));
		$items = self::getAllByIdCustomer((int)$cartRule->id_customer, false, true);
		foreach($items AS $item)
		{
			$r = new RewardsModel((int)$item['id_reward']);
			$r->id_cart_rule = (int)$cartRule->id;
			$r->id_reward_state = (int)RewardsStateModel::getConvertId();
			$r->save();
		}
	}

	public static function registerPayment($payment)
	{
		$context = Context::getContext();

		if (!Validate::isLoadedObject($payment))
			die(Tools::displayError('Incorrect object RewardsPaymentModel.'));
		$items = self::getAllByIdCustomer((int)$context->customer->id, false, true);
		foreach($items AS $item)
		{
			$r = new RewardsModel((int)$item['id_reward']);
			$r->id_payment = (int)$payment->id;
			$r->id_reward_state = (int)RewardsStateModel::getWaitingPaymentId();
			$r->save();
		}
	}

	public static function acceptPayment($id_payment)
	{
		$query = 'SELECT * FROM `'._DB_PREFIX_.'rewards` r WHERE r.id_payment='.(int)$id_payment.' AND r.id_reward_state='.(int)RewardsStateModel::getWaitingPaymentId();
		$items = Db::getInstance()->ExecuteS($query);
		foreach($items AS $item)
		{
			$r = new RewardsModel((int)$item['id_reward']);
			$r->id_reward_state = (int)RewardsStateModel::getPaidId();
			$r->save();
		}
		return $items[0]['id_customer'];
	}

	// Convert rewards in ReturnPeriodId or ValidationId state if return date is over
	// Cancel rewards if validity has expired (based on date_upd)
	public static function checkRewardsStates() {
		// rewards waiting for the end of the return period
		$query = '
		SELECT r.id_reward
		FROM `'._DB_PREFIX_.'rewards` r
		WHERE r.id_reward_state = '.(int)RewardsStateModel::getReturnPeriodId();

		// rewards which have been in return period since time > return period nb days
		if (Configuration::get('REWARDS_WAIT_RETURN_PERIOD') && Configuration::get('PS_ORDER_RETURN') && (int)Configuration::get('PS_ORDER_RETURN_NB_DAYS') > 0) {
			$query .= '
			AND (
				DATE_ADD(r.date_upd, INTERVAL '.(int)Configuration::get('PS_ORDER_RETURN_NB_DAYS').' DAY) < NOW()
				OR EXISTS (
					SELECT id_reward
					FROM `'._DB_PREFIX_.'rewards_history` rh
					WHERE rh.id_reward = r.id_reward
					AND rh.id_reward_state = '.(int)RewardsStateModel::getReturnPeriodId().'
					AND DATE_ADD(date_add, INTERVAL '.(int)Configuration::get('PS_ORDER_RETURN_NB_DAYS').' DAY) < NOW()
				)
			)';
		}

		$rows = Db::getInstance()->ExecuteS($query);
		if (is_array($rows)) {
			foreach ($rows AS $row)	{
				$reward = new RewardsModel((int)$row['id_reward']);
				$reward->id_reward_state = (int)RewardsStateModel::getValidationId();
				$reward->save();
			}
		}

		// rewards with expired validity
		if (Configuration::get('REWARDS_DURATION')) {
			$query = '
			SELECT r.id_reward
			FROM `'._DB_PREFIX_.'rewards` r
			WHERE r.id_reward_state = '.(int)RewardsStateModel::getValidationId().'
			AND DATE_ADD(r.date_upd, INTERVAL '.(int)Configuration::get('REWARDS_DURATION').' DAY) < NOW()';
			$rows = Db::getInstance()->ExecuteS($query);
			if (is_array($rows)) {
				foreach ($rows AS $row)	{
					$reward = new RewardsModel((int)$row['id_reward']);
					$reward->id_reward_state = (int)RewardsStateModel::getCancelId();
					$reward->save();
				}
			}
		}
	}

	public function getUnlockDate() {
		$query = '
			SELECT DATE_ADD(date_add, INTERVAL '.(int)Configuration::get('PS_ORDER_RETURN_NB_DAYS').' DAY) AS unlock_date
			FROM `'._DB_PREFIX_.'rewards_history` rh
			WHERE rh.id_reward = '.$this->id.'
			AND rh.id_reward_state = '.(int)RewardsStateModel::getReturnPeriodId().'
			ORDER BY date_add ASC';
		$result = Db::getInstance()->getRow($query);
		return $result['unlock_date'];
	}

	// Register all transaction in a specific history table
	private function historize()
	{
		Db::getInstance()->Execute('
		INSERT INTO `'._DB_PREFIX_.'rewards_history` (`id_reward`, `id_reward_state`, `credits`, `date_add`)
		VALUES ('.(int)($this->id).', '.(int)($this->id_reward_state).', '.(float)($this->credits).', NOW())');
	}

	// check if customer is in a group which is allowed to transform rewards into vouchers or ask for payment
	static public function isCustomerAllowedForVoucher($id_customer)
	{
		return self::_isCustomerAllowed($id_customer, 'REWARDS_VOUCHER');
	}

	static public function isCustomerAllowedForPayment($id_customer)
	{
		return self::_isCustomerAllowed($id_customer, 'REWARDS_PAYMENT');
	}

	static private function _isCustomerAllowed($id_customer, $key) {
		$customer = new Customer($id_customer);
		if (Validate::isLoadedObject($customer)) {
			$id_template = (int)MyConf::getIdTemplate('core', $customer->id);
			// if the customer is linked to a template, then it overrides the groups setting
			if (MyConf::get($key, null, $id_template)) {
				if ($id_template)
					return true;
				$allowed_groups = explode(',', Configuration::get($key.'_GROUPS'));
				$customer_groups = $customer->getGroups();
				return sizeof(array_intersect($allowed_groups, $customer_groups)) > 0;
			}
		}
		return false;
	}
}