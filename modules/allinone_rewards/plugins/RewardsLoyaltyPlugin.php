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

require_once(_PS_MODULE_DIR_.'/allinone_rewards/plugins/RewardsGenericPlugin.php');

class RewardsLoyaltyPlugin extends RewardsGenericPlugin
{
	public $name = 'loyalty';

	public function install()
	{
		// hooks
		if (!$this->registerHook('displayRightColumnProduct') || !$this->registerHook('displayShoppingCartFooter')
		|| !$this->registerHook('actionValidateOrder') || !$this->registerHook('actionOrderStatusUpdate')
		|| !$this->registerHook('actionProductCancel') || !$this->registerHook('actionOrderReturn')
		|| !$this->registerHook('displayAdminOrder') || !$this->registerHook('displayAdminProductsExtra') || !$this->registerHook('ActionAdminControllerSetMedia'))
			return false;

		$groups_config = '';
		$groups = Group::getGroups((int)(Configuration::get('PS_LANG_DEFAULT')));
		foreach ($groups AS $group)
			$groups_config .= (int)$group['id_group'].',';
		$groups_config = rtrim($groups_config, ',');

		$category_config = '';
		$categories = Category::getSimpleCategories((int)(Configuration::get('PS_LANG_DEFAULT')));
		foreach ($categories AS $category)
			$category_config .= (int)$category['id_category'].',';
		$category_config = rtrim($category_config, ',');

		if (!Configuration::updateValue('RLOYALTY_TYPE', 0)
		|| !Configuration::updateValue('RLOYALTY_POINT_VALUE', 0.50)
		|| !Configuration::updateValue('RLOYALTY_POINT_RATE', 10)
		|| !Configuration::updateValue('RLOYALTY_PERCENTAGE', 5)
		|| !Configuration::updateValue('RLOYALTY_DEFAULT_PRODUCT_REWARD', 0)
		|| !Configuration::updateValue('RLOYALTY_DEFAULT_PRODUCT_TYPE', 0)
		|| !Configuration::updateValue('RLOYALTY_MULTIPLIER', 1)
		|| !Configuration::updateValue('RLOYALTY_DISCOUNTED_ALLOWED', 1)
		|| !Configuration::updateValue('RLOYALTY_ACTIVE', 0)
		|| !Configuration::updateValue('RLOYALTY_MAIL_VALIDATION', 1)
		|| !Configuration::updateValue('RLOYALTY_MAIL_CANCELPROD', 1)
		|| !Configuration::updateValue('RLOYALTY_GROUPS', $groups_config)
		|| !Configuration::updateValue('RLOYALTY_CATEGORIES', $category_config))
			return false;

		// database
		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_product` (
			`id_reward_product` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_product` INT UNSIGNED NOT NULL,
			`type` INT UNSIGNED NOT NULL DEFAULT 0,
			`value` INT UNSIGNED NOT NULL DEFAULT 0,
			`date_from` DATETIME,
			`date_to` DATETIME,
			PRIMARY KEY (`id_reward_product`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		// create an invisible tab so we can call an admin controller to manage the product rewards in the product page
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = "AdminProductReward";
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
			$tab->name[$lang['id_lang']] = 'AllinoneRewards Product Reward';
		$tab->id_parent = -1;
		$tab->module = $this->instance->name;

		if (!$tab->add())
			return false;

		return true;
	}

	public function uninstall()
	{
		$id_tab = (int)Tab::getIdFromClassName('AdminProductReward');
		if ($id_tab) {
			$tab = new Tab($id_tab);
			$tab->delete();
		}

		//Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_product`;');
		Db::getInstance()->Execute('
			DELETE FROM `'._DB_PREFIX_.'configuration_lang`
			WHERE `id_configuration` IN (SELECT `id_configuration` from `'._DB_PREFIX_.'configuration` WHERE `name` like \'RLOYALTY_%\')');

		Db::getInstance()->Execute('
			DELETE FROM `'._DB_PREFIX_.'configuration`
			WHERE `name` like \'RLOYALTY_%\'');

		return true;
	}

	public function isActive()
	{
		$id_template=0;
		if (isset($this->context->customer))
			$id_template = (int)MyConf::getIdTemplate('loyalty', $this->context->customer->id);
		return MyConf::get('RLOYALTY_ACTIVE', null, $id_template);
	}

	public function getTitle()
	{
		return $this->l('Loyalty program');
	}

	public function getDetails($reward, $admin)
	{
		if ($admin)
			return $this->l('Order');
		else
			return $this->l('Order #') . sprintf('%06d', $reward['id_order']);
	}

	public function postProcess($params=null)
	{
		// on initialise le template à chaque chargement
		$this->initTemplate();

		if (Tools::isSubmit('submitLoyalty')) {
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				if (empty($this->id_template)) {
					Configuration::updateValue('RLOYALTY_GROUPS', implode(",", Tools::getValue('rloyalty_groups')));
				}
				MyConf::updateValue('RLOYALTY_ACTIVE', (int)Tools::getValue('rloyalty_active'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_TYPE', (int)Tools::getValue('rloyalty_type'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_POINT_VALUE', (float)Tools::getValue('rloyalty_point_value'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_POINT_RATE', (float)Tools::getValue('rloyalty_point_rate'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_PERCENTAGE', (float)Tools::getValue('rloyalty_percentage'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_DEFAULT_PRODUCT_REWARD', (float)Tools::getValue('rloyalty_default_product_reward'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_DEFAULT_PRODUCT_TYPE', (int)Tools::getValue('rloyalty_default_product_type'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_MULTIPLIER', (float)Tools::getValue('rloyalty_multiplier'), null, $this->id_template);
				MyConf::updateValue('RLOYALTY_DISCOUNTED_ALLOWED', (int)Tools::getValue('rloyalty_discounted_allowed'), null, $this->id_template);
				if (!Tools::getValue('rloyalty_type') || (int)Tools::getValue('rloyalty_type') == 1)
					MyConf::updateValue('RLOYALTY_CATEGORIES', implode(",", Tools::getValue('rloyalty_categories')), null, $this->id_template);
				$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
			} else
				$this->instance->errors = $this->instance->displayError(implode('<br />', $this->_errors));
		} else if (Tools::isSubmit('submitLoyaltyNotifications')) {
			Configuration::updateValue('RLOYALTY_MAIL_VALIDATION', (int)Tools::getValue('mail_validation'));
			Configuration::updateValue('RLOYALTY_MAIL_CANCELPROD', (int)Tools::getValue('mail_cancel_product'));
			$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
		}
	}

	private function _postValidation()
	{
		$this->_errors = array();
		if (empty($this->id_template)) {
			if (!is_array(Tools::getValue('rloyalty_groups')))
				$this->_errors[] = $this->l('Please select at least 1 customer group allowed to get loyalty rewards');
		}
		if (!is_numeric(Tools::getValue('rloyalty_point_rate')) || Tools::getValue('rloyalty_point_rate') <= 0)
			$this->_errors[] = $this->l('The ratio is required/invalid.');
		if (!is_numeric(Tools::getValue('rloyalty_point_value')) || Tools::getValue('rloyalty_point_value') <= 0)
			$this->_errors[] = $this->l('The value is required/invalid.');
		if (!is_numeric(Tools::getValue('rloyalty_percentage')) || Tools::getValue('rloyalty_percentage') <= 0)
			$this->_errors[] = $this->l('The percentage is required/invalid.');
		if (!is_numeric(Tools::getValue('rloyalty_default_product_reward')) || Tools::getValue('rloyalty_default_product_reward') < 0)
			$this->_errors[] = $this->l('The default reward is invalid.');
		if (!is_numeric(Tools::getValue('rloyalty_multiplier')) || Tools::getValue('rloyalty_multiplier') <= 0)
			$this->_errors[] = $this->l('The coefficient multiplier is required/invalid.');
		if ((!Tools::getValue('rloyalty_type') || (int)Tools::getValue('rloyalty_type')==1) && !is_array(Tools::getValue('rloyalty_categories')) || !sizeof(Tools::getValue('rloyalty_categories')))
			$this->_errors[] = $this->l('You must choose at least one category of products');
	}

	public function getContent()
	{
		$this->postProcess();

		$currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
		$groups = Group::getGroups((int)$this->context->language->id);
		$allowed_groups = explode(',', Configuration::get('RLOYALTY_GROUPS'));
		$categories = $this->instance->getCategories();

		$html = $this->getTemplateForm($this->id_template, $this->name, $this->l('Loyalty')).'
		<div class="tabs" style="display: none">
			<ul>
				<li><a href="#tabs-'.$this->name.'-1">'.$this->l('Settings').'</a></li>
				<li class="not_templated"><a href="#tabs-'.$this->name.'-2">'.$this->l('Notifications').'</a></li>
			</ul>
			<div id="tabs-'.$this->name.'-1">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
					<input type="hidden" name="plugin" id="plugin" value="' . $this->name . '" />
					<fieldset>
						<legend>'.$this->l('General settings').'</legend>
						<label>'.$this->l('Activate loyalty program').'</label>
						<div class="margin-form">
							<label class="t" for="loyalty_active_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="loyalty_active_on" name="rloyalty_active" value="1" '.(Tools::getValue('rloyalty_active', MyConf::get('RLOYALTY_ACTIVE', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="loyalty_active_on">' . $this->l('Yes') . '</label>
							<label class="t" for="loyalty_active_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="loyalty_active_off" name="rloyalty_active" value="0" '.(Tools::getValue('rloyalty_active', MyConf::get('RLOYALTY_ACTIVE', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="loyalty_active_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear not_templated">
							<label>'.$this->l('Customers groups allowed to get loyalty rewards').'</label>
							<div class="margin-form">
								<select name="rloyalty_groups[]" multiple="multiple" class="multiselect">';
		foreach($groups as $group) {
			$html .= '				<option '.(is_array($allowed_groups) && in_array($group['id_group'], $allowed_groups) ? 'selected':'').' value="'.$group['id_group'].'"> '.$group['name'].'</option>';
		}
		$html .= '
								</select>
							</div>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('How is calculated the reward ?').'</label>
						<div class="margin-form">
							<input type="radio" id="loyalty_type_range" name="rloyalty_type" value="0" '.(Tools::getValue('rloyalty_type', MyConf::get('RLOYALTY_TYPE', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="loyalty_type_range">' . $this->l('Based on the total of the cart') . '</label>
							&nbsp;<input type="radio" id="loyalty_type_percentage" name="rloyalty_type" value="1" '.(Tools::getValue('rloyalty_type', MyConf::get('RLOYALTY_TYPE', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="loyalty_type_percentage">' . $this->l('% of the total of the cart') . '</label>
							&nbsp;<input type="radio" id="loyalty_type_product" name="rloyalty_type" value="2" '.(Tools::getValue('rloyalty_type', MyConf::get('RLOYALTY_TYPE', null, $this->id_template)) == 2 ? 'checked="checked"' : '').' /> <label class="t" for="loyalty_type_product">' . $this->l('Product per product') . '</label>
						</div>
						<div class="clear optional reward_type_optional_0">
							<label></label>
							<div class="margin-form">'.$this->l('All vouchers will be deduced before calculating the total').'</div>
							<div class="clear"></div>
							<label>'.$this->l('For every').'</label>
							<div class="margin-form">
								<input type="text" size="3" id="rloyalty_point_rate" name="rloyalty_point_rate" value="'.Tools::getValue('rloyalty_point_rate', (float)MyConf::get('RLOYALTY_POINT_RATE', null, $this->id_template)).'" /> <label class="t">'.$currency->sign.' '.$this->l('spent on the shop').'</label>
							</div>
							<div class="clear"></div>
							<label>'.$this->l('Customer gets').'</label>
							<div class="margin-form">
								<input type="text" size="3" name="rloyalty_point_value" id="rloyalty_point_value" value="'.Tools::getValue('rloyalty_point_value', (float)MyConf::get('RLOYALTY_POINT_VALUE', null, $this->id_template)).'" /> <label class="t">'.$currency->sign.'</label>
							</div>
						</div>
						<div class="clear optional reward_type_optional_1">
							<label></label>
							<div class="margin-form">'.$this->l('All vouchers will be deduced before calculating the total').'</div>
							<div class="clear"></div>
							<label>'.$this->l('Percentage').'</label>
							<div class="margin-form">
								<input type="text" size="3" name="rloyalty_percentage" value="'.Tools::getValue('rloyalty_percentage', (float)MyConf::get('RLOYALTY_PERCENTAGE', null, $this->id_template)).'" /> %
							</div>
						</div>
						<div class="clear optional reward_type_optional_2">
							<!--<label></label>
							<div class="margin-form">'.$this->l('Products offered by vouchers will not give any reward').'</div>
							<div class="clear"></div>-->
							<label>'.$this->l('Default reward for product with no custom value').'</label>
							<div class="margin-form">
								<input type="text" size="3" name="rloyalty_default_product_reward" value="'.Tools::getValue('rloyalty_default_product_reward', (float)MyConf::get('RLOYALTY_DEFAULT_PRODUCT_REWARD', null, $this->id_template)).'" />
								<select name="rloyalty_default_product_type">
									<option '.(Tools::getValue('rloyalty_default_product_type', (float)MyConf::get('RLOYALTY_DEFAULT_PRODUCT_TYPE', null, $this->id_template)) == 0 ? 'selected' : '').' value="0">% '.$this->l('of its own price').'</option>
									<option '.(Tools::getValue('rloyalty_default_product_type', (float)MyConf::get('RLOYALTY_DEFAULT_PRODUCT_TYPE', null, $this->id_template)) == 1 ? 'selected' : '').' value="1">'.$currency->sign.'</option>
								</select>
							</div>
							<div class="clear"></div>
							<label>'.$this->l('Coefficient multiplier (all rewards will be multiplied by this coefficient)').'</label>
							<div class="margin-form">
								<input type="text" size="3" name="rloyalty_multiplier" value="'.Tools::getValue('rloyalty_multiplier', (float)MyConf::get('RLOYALTY_MULTIPLIER', null, $this->id_template)).'" />
							</div>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Give rewards on discounted products').' </label>
						<div class="margin-form">
							<label class="t" for="rloyalty_discounted_allowed_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="rloyalty_discounted_allowed_on" name="rloyalty_discounted_allowed" value="1" '.(MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $this->id_template) ? 'checked="checked" ' : '').'/> <label class="t" for="rloyalty_discounted_allowed_on">' . $this->l('Yes') . '</label>
							<label class="t" for="rloyalty_discounted_allowed_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="rloyalty_discounted_allowed_off" name="rloyalty_discounted_allowed" value="0" '.(!MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $this->id_template) ? 'checked="checked" ' : '').'/> <label class="t" for="rloyalty_discounted_allowed_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear reward_type_optional_0 reward_type_optional_1">
							<label>'.$this->l('Categories of products allowing to get loyalty rewards').'</label>
							<div class="margin-form">
								<table cellspacing="0" cellpadding="0" class="table">
									<tr>
										<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'rloyalty_categories[]\', this.checked)" /></th>
										<th>'.$this->l('ID').'</th>
										<th style="width: 400px">'.$this->l('Name').'</th>
									</tr>';
		$index = Tools::getValue('rloyalty_categories') ? Tools::getValue('rloyalty_categories') : explode(',', MyConf::get('RLOYALTY_CATEGORIES', null, $this->id_template));
		$current = current(current($categories));
		$html .= $this->recurseCategoryForInclude('rloyalty_categories', $index, $categories, $current, $current['infos']['id_category']);
		$html .= '
								</table>
							</div>
						</div>
					</fieldset>
					<div class="clear center"><input type="submit" name="submitLoyalty" id="submitLoyalty" value="'.$this->l('Save settings').'" class="button" /></div>
				</form>
			</div>
			<div id="tabs-'.$this->name.'-2" class="not_templated">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
				<input type="hidden" name="plugin" id="plugin" value="' . $this->name . '" />
				<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-2" />
				<fieldset>
					<legend>'.$this->l('Notifications').'</legend>
					<label>'.$this->l('Send a mail to the customer on reward validation/cancellation').'</label>
					<div class="margin-form">
						<label class="t" for="mail_validation_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="mail_validation_on" name="mail_validation" value="1" '.(Tools::getValue('mail_validation', Configuration::get('RLOYALTY_MAIL_VALIDATION')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_validation_on">' . $this->l('Yes') . '</label>
						<label class="t" for="mail_validation_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="mail_validation_off" name="mail_validation" value="0" '.(Tools::getValue('mail_validation', Configuration::get('RLOYALTY_MAIL_VALIDATION')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_validation_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Send a mail to the customer on reward modification (product canceled)').'</label>
					<div class="margin-form">
						<label class="t" for="mail_cancel_product_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="mail_cancel_product_on" name="mail_cancel_product" value="1" '.(Tools::getValue('mail_cancel_product', Configuration::get('RLOYALTY_MAIL_CANCELPROD')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_cancel_product_on">' . $this->l('Yes') . '</label>
						<label class="t" for="mail_cancel_product_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="mail_cancel_product_off" name="mail_cancel_product" value="0" '.(Tools::getValue('mail_cancel_product', Configuration::get('RLOYALTY_MAIL_CANCELPROD')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_cancel_product_off">' . $this->l('No') . '</label>
					</div>
				</fieldset>
				<div class="clear center"><input class="button" name="submitLoyaltyNotifications" id="submitLoyaltyNotifications" value="'.$this->l('Save settings').'" type="submit" /></div>
				</form>
			</div>
		</div>';

		return $html;
	}

	// check if customer is in a group which is allowed to get loyalty rewards
	// if bCheckDefault is true, then return true if the default group is checked (to know if we display the rewards for people not logged in)
	private function _isCustomerAllowed($customer, $bCheckDefault=false)
	{
		$allowed_groups = explode(',', Configuration::get('RLOYALTY_GROUPS'));
		if (Validate::isLoadedObject($customer)) {
			// if the customer is linked to a template, then it overrides the groups setting
			if ((int)MyConf::getIdTemplate('loyalty', $customer->id))
				return true;
			$customer_groups = $customer->getGroups();
			return sizeof(array_intersect($allowed_groups, $customer_groups)) > 0;
		} else if ($bCheckDefault && in_array(1, $allowed_groups)) {
			return true;
		}
	}

	// convert the string into an array of object(array) which have id_category as key
	private function _getAllowedCategories()
	{
		$id_template=0;
		if (isset($this->context->customer))
			$id_template = (int)MyConf::getIdTemplate('loyalty', $this->context->customer->id);
		$allowed_categories = array();
		$categories = explode(',', MyConf::get('RLOYALTY_CATEGORIES', null, $id_template));
		foreach($categories as $category) {
			$allowed_categories[] = array('id_category' => $category);
		}
		return $allowed_categories;
	}

	// check if the product is in a category which is allowed to give loyalty rewards
	// or if a reward is defined on that product
	private function _isProductAllowed($id_product)
	{
		$id_template = (int)MyConf::getIdTemplate('loyalty', $this->context->customer->id);
		if ((int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 0 || (int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 1)
			return Product::idIsOnCategoryId($id_product, $this->_getAllowedCategories());
		else
			return RewardsProductModel::isProductRewarded($id_product, $id_template);
	}

	// Return the reward calculated from a price in a specific currency, and converted in the 2nd currency
	protected function getNbCreditsByPrice($id_customer, $price, $idCurrencyFrom, $idCurrencyTo = NULL, $extraParams = array())
	{
		$id_template = (int)MyConf::getIdTemplate('loyalty', $id_customer);
		if (!isset($idCurrencyTo))
			$idCurrencyTo = $idCurrencyFrom;

		if (Configuration::get('PS_CURRENCY_DEFAULT') != $idCurrencyFrom) {
			// converti de la devise du client vers la devise par défaut
			$price = Tools::convertPrice($price, Currency::getCurrency($idCurrencyFrom), false);
		}
		/* Prevent division by zero */
		$credits = 0;
		if ((int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 0) {
			$credits = floor(number_format($price, 2, '.', '') / (float)MyConf::get('RLOYALTY_POINT_RATE', null, $id_template)) * (float)MyConf::get('RLOYALTY_POINT_VALUE', null, $id_template);
		} else if ((int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 1) {
			$credits = number_format($price, 2, '.', '') * (float)MyConf::get('RLOYALTY_PERCENTAGE', null, $id_template) / 100;
		}
		return round(Tools::convertPrice($credits, Currency::getCurrency($idCurrencyTo)), 2);
	}

	// Hook called on product page
	// TODO : manage the actual id_product_attribute selected on the product page instead of using the most expensive
	public function hookDisplayRightColumnProduct($params)
	{
		$display = true;
		$id_template = (int)MyConf::getIdTemplate('loyalty', $this->context->customer->id);
		$rewards_on_total = (int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 2 ? false : true;

		$product = new Product((int)Tools::getValue('id_product'));
		if ($this->_isCustomerAllowed($this->context->customer, true) && Validate::isLoadedObject($product) && $this->_isProductAllowed($product->id)) {
			if (Validate::isLoadedObject($params['cart'])) {
				$credits_before = RewardsModel::getCartPriceForReward($params['cart'], $rewards_on_total, MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template), $this->_getAllowedCategories());
				$credits_after = RewardsModel::getCartPriceForReward($params['cart'], $rewards_on_total, MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template), $this->_getAllowedCategories(), $product);
				if ($rewards_on_total) {
					$credits_before = (float)$this->getNbCreditsByPrice($this->context->customer->id, $credits_before, $this->context->currency->id);
					$credits_after = (float)($this->getNbCreditsByPrice($this->context->customer->id, $credits_after, $this->context->currency->id));
				}
				$credits = (float)($credits_after - $credits_before);
			} else {
				if (!(int)(MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template)) && RewardsModel::isDiscountedProduct($product->id)) {
					$credits = $credits_before = $credits_after = 0;
					$this->context->smarty->assign('no_pts_discounted', 1);
				} else {
					$credits_before = 0;
					$credits_after = RewardsModel::getCartPriceForReward(null, $rewards_on_total, MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template), $this->_getAllowedCategories(), $product);
					if ($rewards_on_total) {
						$credits_after = (float)($this->getNbCreditsByPrice($this->context->customer->id, $credits_after, $this->context->currency->id));
					}
					$credits = $credits_after;
				}
			}

			// si pas de crédit, pas un produit discount, et pas en mode tranche, on affiche rien
			if ($credits == 0 && (int)MyConf::get('RLOYALTY_TYPE', null, $id_template) != 0 && !$this->context->smarty->getTemplateVars('no_pts_discounted')) {
				$display = false;
			}

			if ($display) {
				$this->context->smarty->assign(array(
					'credits' => (float)$credits,
					'total_credits' => (float)$credits_after,
					'credits_in_cart' => (float)$credits_before,
					'minimum' => round(Tools::convertPrice((float) MyConf::get('RLOYALTY_POINT_RATE', null, $id_template), $this->context->currency), 2)
				));
				return $this->instance->display($this->instance->path, 'product.tpl');
			}
		}
		return false;
	}

	public function hookDisplayShoppingCartFooter($params)
	{
		if ($this->_isCustomerAllowed($this->context->customer, true)) {
			if (Validate::isLoadedObject($params['cart'])) {
				$id_template = (int)MyConf::getIdTemplate('loyalty', $this->context->customer->id);
				$rewards_on_total = (int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 2 ? false : true;
				$credits = RewardsModel::getCartPriceForReward($params['cart'], $rewards_on_total, MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, (int)MyConf::getIdTemplate('loyalty', $this->context->customer->id)), $this->_getAllowedCategories());
				$credits = RewardsModel::getCurrencyValue($credits, Configuration::get('PS_CURRENCY_DEFAULT'));

				if ($rewards_on_total) {
					$credits = $this->getNbCreditsByPrice($this->context->customer->id, $credits, $this->context->currency->id);
				}
				$this->context->smarty->assign(array(
					 'credits' => (float)$credits,
					 'guest_checkout' => (int)Configuration::get('PS_GUEST_CHECKOUT_ENABLED')
				));
			} else
				$this->context->smarty->assign(array('credits' => 0));
			return $this->instance->display($this->instance->path, 'shopping-cart.tpl');
		}
		return false;
	}

	public function hookActionValidateOrder($params)
	{
		if (!Validate::isLoadedObject($params['customer']) || !Validate::isLoadedObject($params['order']))
			die(Tools::displayError('Missing parameters'));

		if ($this->_isCustomerAllowed(new Customer((int)$params['customer']->id))) {
			$id_template = (int)MyConf::getIdTemplate('loyalty', $params['customer']->id);
			$rewards_on_total = (int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 2 ? false : true;
			$credits = RewardsModel::getOrderPriceForReward($params['order'], $rewards_on_total, MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template), $this->_getAllowedCategories());
			if ($rewards_on_total)
				$credits = $this->getNbCreditsByPrice((int)$params['customer']->id, $credits, $params['order']->id_currency, Configuration::get('PS_CURRENCY_DEFAULT'));

			$reward = new RewardsModel();
			$reward->id_customer = (int)$params['customer']->id;
			$reward->id_order = (int)$params['order']->id;
			$reward->credits = $credits;
			$reward->plugin = $this->name;
			if (!MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template) && (float)$reward->credits == 0) {
				$reward->id_reward_state = RewardsStateModel::getDiscountedId();
				$reward->save();
			} else if ((float)$reward->credits > 0) {
				$reward->id_reward_state = RewardsStateModel::getDefaultId();
				$reward->save();
			}
			return true;
		}
		return false;
	}

	public function hookActionOrderStatusUpdate($params)
	{
		$this->instanceDefaultStates();

		if (!Validate::isLoadedObject($orderState = $params['newOrderStatus']) || !Validate::isLoadedObject($order = new Order((int)$params['id_order'])) || !Validate::isLoadedObject($customer = new Customer((int)$order->id_customer)))
			die(Tools::displayError('Missing parameters'));

		// if state become validated or cancelled
		if ($orderState->id != $order->getCurrentState() && (in_array($orderState->id, $this->rewardStateValidation->getValues()) || in_array($orderState->id, $this->rewardStateCancel->getValues())))	{
			// check if a reward has been granted for this order
			if (!Validate::isLoadedObject($reward = new RewardsModel(RewardsModel::getByOrderId($order->id))))
				return false;
			// if no reward on discount, and state = DiscountId, do nothing
			if (!MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, (int)MyConf::getIdTemplate('loyalty', $order->id_customer)) && $reward->id_reward_state == RewardsStateModel::getDiscountedId())
				return true;

			if ($reward->id_reward_state != RewardsStateModel::getConvertId()) {
				// if not already converted, then cancel or validate the reward
				if (in_array($orderState->id, $this->rewardStateValidation->getValues())) {
					// if reward is locked during return period
					if (Configuration::get('REWARDS_WAIT_RETURN_PERIOD') && Configuration::get('PS_ORDER_RETURN') && (int)Configuration::get('PS_ORDER_RETURN_NB_DAYS') > 0) {
						$reward->id_reward_state = RewardsStateModel::getReturnPeriodId();
						$template = 'loyalty-return-period';
						$subject = $this->l('Reward validation', (int)$order->id_lang);
					} else {
						$reward->id_reward_state = RewardsStateModel::getValidationId();
						$template = 'loyalty-validation';
						$subject = $this->l('Reward validation', (int)$order->id_lang);
					}
				} else {
					$reward->id_reward_state = RewardsStateModel::getCancelId();
					$template = 'loyalty-cancellation';
					$subject = $this->l('Reward cancellation', (int)$order->id_lang);
				}
				$reward->save();

				// send notification
				if (Configuration::get('RLOYALTY_MAIL_VALIDATION')) {
					$data = array(
						'{customer_firstname}' => $customer->firstname,
						'{customer_lastname}' => $customer->lastname,
						'{order}' => sprintf('%06d', $order->id),
						'{link_rewards}' => $this->context->link->getModuleLink('allinone_rewards', 'rewards', array(), true),
						'{customer_reward}' => Tools::displayPrice(round(Tools::convertPrice((float)$reward->credits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency));
					if ($reward->id_reward_state = RewardsStateModel::getReturnPeriodId()) {
						$data['{reward_unlock_date}'] = Tools::displayDate($reward->getUnlockDate(), null, true);
					}
					$this->instance->sendMail((int)$order->id_lang, $template, $subject, $data, $customer->email, $customer->firstname.' '.$customer->lastname);
				}
			}
		}
		return true;
	}

	// Hook called in tab AdminOrders when a product is cancelled
	public function hookActionProductCancel($params)
	{
		if (!Validate::isLoadedObject($order = $params['order'])
		|| !Validate::isLoadedObject($customer = new Customer((int)$order->id_customer))
		|| !Validate::isLoadedObject($reward = new RewardsModel((int)(RewardsModel::getByOrderId((int)($order->id)))))
		|| $reward->id_reward_state == RewardsStateModel::getConvertId())
			return false;

		$id_template = (int)MyConf::getIdTemplate('loyalty', $order->id_customer);
		$rewards_on_total = (int)MyConf::get('RLOYALTY_TYPE', null, $id_template) == 2 ? false : true;

		$oldCredits = $reward->credits;
		$reward->credits = RewardsModel::getOrderPriceForReward($order, $rewards_on_total, MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template), $this->_getAllowedCategories());
		if ($rewards_on_total)
			$reward->credits = $this->getNbCreditsByPrice((int)$order->id_customer, $reward->credits, $order->id_currency, Configuration::get('PS_CURRENCY_DEFAULT'));

		// test if there was an update, because product return doesn't change the cart price
		if ((float)$oldCredits != (float)$reward->credits) {
			if (!MyConf::get('RLOYALTY_DISCOUNTED_ALLOWED', null, $id_template) && (float)$reward->credits == 0)
				$reward->id_reward_state = RewardsStateModel::getDiscountedId();
			else if ((float)$reward->credits == 0)
				$reward->id_reward_state = RewardsStateModel::getCancelId();
			$reward->save();
			// TODO : historize changes

			// send notifications
			if (Configuration::get('RLOYALTY_MAIL_CANCELPROD')) {
				$data = array(
					'{customer_firstname}' => $customer->firstname,
					'{customer_lastname}' => $customer->lastname,
					'{order}' => sprintf('%06d', $order->id),
					'{old_customer_reward}' => Tools::displayPrice(round(Tools::convertPrice((float)$oldCredits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency),
					'{new_customer_reward}' => Tools::displayPrice(round(Tools::convertPrice((float)$reward->credits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency));
				$this->instance->sendMail((int)$order->id_lang, 'loyalty-cancel-product', $this->l('Reward modification', (int)$order->id_lang), $data, $customer->email, $customer->firstname.' '.$customer->lastname);
			}
		}
		return true;
	}

	// Hook called in tab AdminOrder
	public function hookDisplayAdminOrder($params)
	{
		if (Validate::isLoadedObject($reward = new RewardsModel(RewardsModel::getByOrderId($params['id_order'])))) {
			$rewardsStateModel = new RewardsStateModel($reward->id_reward_state);

			$smarty_values = array(
				'reward' => $reward,
				'reward_state' => $rewardsStateModel->name[$this->context->language->id]
			);
			$this->context->smarty->assign($smarty_values);
			return $this->instance->display($this->instance->path, 'adminorders.tpl');
		}
	}

	// Hook called in tab AdminProduct
	public function hookDisplayAdminProductsExtra($params)
	{
		if (Validate::isLoadedObject($product = new Product((int)Tools::getValue('id_product')))) {
			$smarty_values = array(
				'product_rewards' => RewardsProductModel::getProductRewardsList($product->id),
				'currency' => Context::getContext()->currency,
				'product_rewards_url' => Context::getContext()->link->getAdminLink('AdminProductReward').'&ajax=1&id_product='.$product->id
			);
			$this->context->smarty->assign($smarty_values);
			return $this->instance->display($this->instance->path, 'adminproductsextra.tpl');
		}
	}

	public function hookActionAdminControllerSetMedia($params)
	{
    	// add necessary javascript to products back office
    	if ($this->context->controller->controller_name == 'AdminProducts' && Tools::getValue('id_product')) {
        	$this->context->controller->addJS($this->instance->getPath().'js/admin-product.js');
    	}
	}
}