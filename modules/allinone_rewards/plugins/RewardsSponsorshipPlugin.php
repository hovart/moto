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
require_once(_PS_MODULE_DIR_.'/allinone_rewards/models/RewardsModel.php');
require_once(_PS_MODULE_DIR_.'/allinone_rewards/models/RewardsSponsorshipModel.php');

class RewardsSponsorshipPlugin extends RewardsGenericPlugin
{
	public $name = 'sponsorship';
	private $_configuration;
	private $_popup = false;

	public function install()
	{
		// hooks
		if (!$this->registerHook('displayTop') || !$this->registerHook('displayHeader') || !$this->registerHook('displayFooter')
		|| !$this->registerHook('displayCustomerAccount') || !$this->registerHook('displayMyAccountBlock') || !$this->registerHook('displayMyAccountBlockFooter')
		|| !$this->registerHook('actionCustomerAccountAdd') || !$this->registerHook('displayCustomerAccountForm')
		|| !$this->registerHook('actionValidateOrder')|| !$this->registerHook('displayOrderConfirmation') || !$this->registerHook('actionOrderStatusUpdate')
		|| !$this->registerHook('displayAdminCustomers') || !$this->registerHook('displayAdminOrder') || !$this->registerHook('ActionAdminControllerSetMedia')
		|| !$this->registerHook('actionProductCancel'))
			return false;

		$idEn = Language::getIdByIso('en');
		$desc = array();
		$account_txt = array();
		$order_txt = array();
		$popup_txt = array();
		$rules_txt = array();
		foreach (Language::getLanguages() AS $language) {
			$tmp = $this->l('Sponsorship', (int)$language['id_lang']);
			$desc[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('Sponsorship', $idEn);
			$tmp = $this->l('account_txt', (int)$language['id_lang']);
			$account_txt[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('account_txt', $idEn);
			$tmp = $this->l('order_txt', (int)$language['id_lang']);
			$order_txt[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('order_txt', $idEn);
			$tmp = $this->l('popup_txt', (int)$language['id_lang']);
			$popup_txt[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('popup_txt', $idEn);
			$tmp = $this->l('rules_txt', (int)$language['id_lang']);
			$rules_txt[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('rules_txt', $idEn);
		}

		$groups_config = '';
		$groups = Group::getGroups((int)Configuration::get('PS_LANG_DEFAULT'));
		foreach ($groups AS $group)
			$groups_config .= (int)$group['id_group'].',';
		$groups_config = rtrim($groups_config, ',');

		$category_config = '';
		$categories = Category::getSimpleCategories((int)Configuration::get('PS_LANG_DEFAULT'));
		foreach ($categories AS $category)
			$category_config .= (int)$category['id_category'].',';
		$category_config = rtrim($category_config, ',');

		if (!Configuration::updateValue('RSPONSORSHIP_ORDER_QUANTITY_S', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_VOUCHER_DETAILS', $desc)
		|| !Configuration::updateValue('RSPONSORSHIP_VOUCHER_PREFIX_GC', 'REWARD')
		|| !Configuration::updateValue('RSPONSORSHIP_VOUCHER_DURATION_GC', 365)
		|| !Configuration::updateValue('RSPONSORSHIP_CATEGORIES_GC', $category_config)
		|| !Configuration::updateValue('RSPONSORSHIP_REWARD_TYPE_S', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_DISCOUNT_TYPE_GC', 2)
		|| !Configuration::updateValue('RSPONSORSHIP_FREESHIPPING_GC', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_CUMUL_GC', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_QUANTITY_GC', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_VOUCHER_BEHAVIOR', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_MINIMAL_TAX_GC', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_NB_FRIENDS', 5)
		|| !Configuration::updateValue('RSPONSORSHIP_REWARD_S', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_REWARD_PERCENTAGE', 5)
		|| !Configuration::updateValue('RSPONSORSHIP_ON_EVERY_ORDER', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_DURATION', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_DISCOUNTED_ALLOWED', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_DISCOUNT_GC', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_UNLOCK_SHIPPING', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_ACTIVE', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_REDIRECT', 'home')
		|| !Configuration::updateValue('RSPONSORSHIP_MAIL_REGISTRATION', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_MAIL_ORDER', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_MAIL_REGISTRATION_S', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_MAIL_ORDER_S', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_MAIL_VALIDATION_S', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_MAIL_CANCELPROD_S', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_UNLIMITED_LEVELS', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_OPEN_INVITER', 0)
		|| !Configuration::updateValue('RSPONSORSHIP_OPEN_INVITER_LOGIN', '')
		|| !Configuration::updateValue('RSPONSORSHIP_OPEN_INVITER_KEY', '')
		|| !Configuration::updateValue('RSPONSORSHIP_AFTER_ORDER', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_POPUP', 1)
		|| !Configuration::updateValue('RSPONSORSHIP_POPUP_DELAY', 3)
		|| !Configuration::updateValue('RSPONSORSHIP_POPUP_KEY', Tools::passwdGen())
		|| !Configuration::updateValue('RSPONSORSHIP_ACCOUNT_TXT', $account_txt, true)
		|| !Configuration::updateValue('RSPONSORSHIP_ORDER_TXT', $order_txt, true)
		|| !Configuration::updateValue('RSPONSORSHIP_POPUP_TXT', $popup_txt, true)
		|| !Configuration::updateValue('RSPONSORSHIP_RULES_TXT', $rules_txt, true)
		|| !Configuration::updateValue('RSPONSORSHIP_GROUPS', $groups_config))
			return false;

		if (version_compare(_PS_VERSION_, '1.5.2', '<')) {
			Configuration::set('RSPONSORSHIP_ACCOUNT_TXT', $account_txt);
			Configuration::set('RSPONSORSHIP_ORDER_TXT', $order_txt);
			Configuration::set('RSPONSORSHIP_POPUP_TXT', $popup_txt);
			Configuration::set('RSPONSORSHIP_RULES_TXT', $rules_txt);
		}

		foreach (Currency::getCurrencies() as $currency) {
			Configuration::updateValue('RSPONSORSHIP_REWARD_VALUE_S_'.(int)($currency['id_currency']), 5);
			Configuration::updateValue('RSPONSORSHIP_VOUCHER_VALUE_GC_'.(int)($currency['id_currency']), 5);
			Configuration::updateValue('RSPONSORSHIP_MINIMUM_VALUE_GC_'.(int)($currency['id_currency']), 0);
			Configuration::updateValue('RSPONSORSHIP_UNLOCK_GC_'.(int)($currency['id_currency']), 0);
		}

		// database
		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_sponsorship` (
			`id_sponsorship` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_sponsor` INT UNSIGNED NOT NULL,
			`channel` INT UNSIGNED DEFAULT 1,
			`email` VARCHAR(255) NOT NULL,
			`lastname` VARCHAR(128) DEFAULT NULL,
			`firstname` VARCHAR(128) DEFAULT NULL,
			`id_customer` INT UNSIGNED DEFAULT NULL,
			`id_cart_rule` INT UNSIGNED DEFAULT NULL,
			`date_end` DATETIME DEFAULT \'0000-00-00 00:00:00\',
			`date_add` DATETIME NOT NULL,
			`date_upd` DATETIME NOT NULL,
			PRIMARY KEY (`id_sponsorship`),
			UNIQUE KEY `index_unique_sponsorship_email` (`email`),
			KEY `index_id_customer` (`id_customer`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_sponsorship_detail` (
			`id_reward` INT UNSIGNED NOT NULL,
			`id_sponsorship` INT UNSIGNED DEFAULT \'0\',
			`level_sponsorship` INT UNSIGNED DEFAULT \'0\',
			PRIMARY KEY (`id_reward`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		return true;
	}

	public function uninstall()
	{
		//Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_sponsorship_detail`;');
		//Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_sponsorship`;');
		Db::getInstance()->Execute('
			DELETE FROM `'._DB_PREFIX_.'configuration_lang`
			WHERE `id_configuration` IN (SELECT `id_configuration` from `'._DB_PREFIX_.'configuration` WHERE `name` like \'RSPONSORSHIP_%\')');

		Db::getInstance()->Execute('
			DELETE FROM `'._DB_PREFIX_.'configuration`
			WHERE `name` like \'RSPONSORSHIP_%\'');

		return true;
	}

	// get the configuration by level
	private function _initConf($id_template)
	{
		unset($this->_configuration);
		$this->_configuration['reward_type'] = explode(',', MyConf::get('RSPONSORSHIP_REWARD_TYPE_S', null, $id_template));
		$this->_configuration['reward_percentage'] = explode(',', MyConf::get('RSPONSORSHIP_REWARD_PERCENTAGE', null, $id_template));
		$this->_configuration['unlimited'] = ((int) Configuration::get('RSPONSORSHIP_UNLIMITED_LEVELS') > 0);

		$currencies = Currency::getCurrencies();
		foreach ($currencies as $currency) {
			$values = explode(',', myConf::get('RSPONSORSHIP_REWARD_VALUE_S_'.$currency['id_currency'], null, $id_template));
			foreach($this->_configuration['reward_percentage'] as $level => $percentage) {
				$this->_configuration['reward_value'][$level][$currency['id_currency']] = isset($values[$level]) ? $values[$level] : 0;
			}
		}
	}

	public function isActive()
	{
		if ($this->context->customer->isLogged()) {
			// si le client est loggué on regarde si le parrainage est actif pour lui
			$id_template = (int)MyConf::getIdTemplate('sponsorship', $this->context->customer->id);
			return MyConf::get('RSPONSORSHIP_ACTIVE', null, $id_template);
		} else {
			// sinon, on teste si au moins un modèle est actif car dans ce cas il faut toujours afficher le champs de parrainage sur le formulaire d'inscription
			// et traiter les URL
			return Configuration::get('RSPONSORSHIP_ACTIVE') || MyConf::isActiveAtLeastOnce('RSPONSORSHIP_ACTIVE');
		}
	}

	public function getTitle()
	{
		return $this->l('Sponsorship program');
	}

	public function getDetails($reward, $admin) {
		$row = RewardsSponsorshipModel::getRewardDetails($reward['id_reward']);
		if (!$admin) {
			if ($row['level_sponsorship'] == 1)
				return $this->l('Sponsorship').' - '.$row['firstname'].' '.$row['lastname'];
			else
				return $this->l('Sponsorship').' - '.$this->l('level').' '.$row['level_sponsorship'];
		} else
			return $this->l('Sponsorship').' '.$row['firstname'].' '.$row['lastname'].' ('.$this->l('level').' '.$row['level_sponsorship'].')';
	}

	public function postProcess($params=null)
	{
		if (Tools::isSubmit('submitSponsorship')) {
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				if (empty($this->id_template)) {
					Configuration::updateValue('RSPONSORSHIP_REDIRECT', Tools::getValue('sponsorship_redirect'));
					Configuration::updateValue('RSPONSORSHIP_OPEN_INVITER', (int)Tools::getValue('open_inviter'));
					Configuration::updateValue('RSPONSORSHIP_OPEN_INVITER_LOGIN', Tools::getValue('open_inviter_login'));
					Configuration::updateValue('RSPONSORSHIP_OPEN_INVITER_KEY', Tools::getValue('open_inviter_key'));
					Configuration::updateValue('RSPONSORSHIP_AFTER_ORDER', Tools::getValue('after_order'));
					Configuration::updateValue('RSPONSORSHIP_POPUP', Tools::getValue('popup'));
					Configuration::updateValue('RSPONSORSHIP_POPUP_DELAY', (int)Tools::getValue('popup_delay'));
					Configuration::updateValue('RSPONSORSHIP_GROUPS', implode(",", Tools::getValue('rsponsorship_groups')));
					if (Tools::getValue('popup_reset'))
						Configuration::updateValue('RSPONSORSHIP_POPUP_KEY', Tools::passwdGen());
					// For levels
					Configuration::updateValue('RSPONSORSHIP_UNLIMITED_LEVELS', (int)Tools::getValue('unlimited_levels') > 0 ? count(Tools::getValue('reward_type_s')) : 0);
				}
				MyConf::updateValue('RSPONSORSHIP_ACTIVE', (int)Tools::getValue('sponsorship_active'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_NB_FRIENDS', (int)Tools::getValue('nb_friends'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_ORDER_QUANTITY_S', (int)Tools::getValue('order_quantity_s'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_VOUCHER_DETAILS', Tools::getValue('description_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_VOUCHER_PREFIX_GC', Tools::getValue('voucher_prefix_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_VOUCHER_DURATION_GC', (int)Tools::getValue('voucher_duration_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_CATEGORIES_GC', implode(",", Tools::getValue('voucher_categories_gc')), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_CUMUL_GC', (int)Tools::getValue('cumulative_voucher_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_VOUCHER_BEHAVIOR', (int)Tools::getValue('voucher_behavior_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_QUANTITY_GC', (int)Tools::getValue('voucher_quantity_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_MINIMAL_TAX_GC', (int)Tools::getValue('include_tax_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_REWARD_S', (int)Tools::getValue('reward_s'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_DURATION', (int)Tools::getValue('rsponsorship_duration'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_ON_EVERY_ORDER', (int)Tools::getValue('reward_on_every_order'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_DISCOUNT_GC', (int)Tools::getValue('discount_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_UNLOCK_SHIPPING', (int)Tools::getValue('unlock_shipping'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_DISCOUNT_TYPE_GC', (int)Tools::getValue('discount_type_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_FREESHIPPING_GC', (int)Tools::getValue('freeshipping_gc'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_DISCOUNTED_ALLOWED', (int)Tools::getValue('rsponsorship_discounted_allowed'), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_SHARE_IMAGE_URL', Tools::getValue('share_image_url'), null, $this->id_template);
				foreach (Tools::getValue('discount_value_gc') as $id_currency => $discount_value_gc) {
					if ((int)Tools::getValue('discount_type_gc') == 0)
						MyConf::updateValue('RSPONSORSHIP_VOUCHER_VALUE_GC_'.(int)($id_currency), 0, null, $this->id_template);
					else
						MyConf::updateValue('RSPONSORSHIP_VOUCHER_VALUE_GC_'.(int)($id_currency), (float)$discount_value_gc, null, $this->id_template);
				}
				foreach (Tools::getValue('minimum_value_gc') as $id_currency => $minimum_value_gc)
					MyConf::updateValue('RSPONSORSHIP_MINIMUM_VALUE_GC_'.(int)$id_currency, (float)$minimum_value_gc, null, $this->id_template);
				foreach (Tools::getValue('unlock_gc') as $id_currency => $unlock_gc)
					MyConf::updateValue('RSPONSORSHIP_UNLOCK_GC_'.(int)$id_currency, (float)$unlock_gc, null, $this->id_template);
				// For levels
				MyConf::updateValue('RSPONSORSHIP_REWARD_TYPE_S', implode(",", Tools::getValue('reward_type_s')), null, $this->id_template);
				MyConf::updateValue('RSPONSORSHIP_REWARD_PERCENTAGE', implode(",", Tools::getValue('reward_percentage')), null, $this->id_template);
				foreach (Tools::getValue('reward_value_s') as $id_currency => $reward_value_s) {
					if (is_array($reward_value_s)) {
						MyConf::updateValue('RSPONSORSHIP_REWARD_VALUE_S_'.(int)$id_currency, implode(",", $reward_value_s), null, $this->id_template);
					}
				}
				$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
			} else
				$this->instance->errors =  $this->instance->displayError(implode('<br />', $this->_errors));
		} else if (Tools::isSubmit('submitSponsorshipNotifications')) {
			Configuration::updateValue('RSPONSORSHIP_MAIL_REGISTRATION', (int)Tools::getValue('mail_admin_registration'));
			Configuration::updateValue('RSPONSORSHIP_MAIL_ORDER', (int)Tools::getValue('mail_admin_order'));
			Configuration::updateValue('RSPONSORSHIP_MAIL_REGISTRATION_S', (int)Tools::getValue('mail_sponsor_registration'));
			Configuration::updateValue('RSPONSORSHIP_MAIL_ORDER_S', (int)Tools::getValue('mail_sponsor_order'));
			Configuration::updateValue('RSPONSORSHIP_MAIL_VALIDATION_S', (int)Tools::getValue('mail_sponsor_validation'));
			Configuration::updateValue('RSPONSORSHIP_MAIL_CANCELPROD_S', (int)Tools::getValue('mail_sponsor_cancel_product'));
			$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
		} else if (Tools::isSubmit('submitSponsorshipText')) {
			MyConf::updateValue('RSPONSORSHIP_ACCOUNT_TXT', Tools::getValue('account_txt'), true, $this->id_template);
			MyConf::updateValue('RSPONSORSHIP_ORDER_TXT', Tools::getValue('order_txt'), true, $this->id_template);
			MyConf::updateValue('RSPONSORSHIP_POPUP_TXT', Tools::getValue('popup_txt'), true, $this->id_template);
			MyConf::updateValue('RSPONSORSHIP_RULES_TXT', Tools::getValue('rules_txt'), true, $this->id_template);
			$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
		} else if (Tools::isSubmit('submitSponsor') && (int)Tools::getValue('new_sponsor')) {
			$customer = new Customer((int)$params['id_customer']);
			$new_sponsor = new Customer((int)Tools::getValue('new_sponsor'));
			if ($this->_createSponsorship($new_sponsor, $customer, true, (bool)Tools::getValue('generate_voucher'), (int)Tools::getValue('generate_currency')))
				return $this->instance->displayConfirmation($this->l('The sponsor has been updated.'));
			else
				return $this->instance->displayError($this->l('The sponsor update failed.'));
		} else if (Tools::isSubmit('submitSponsorshipEndDate') && (int)Tools::getValue('id_sponsorship_to_update')) {
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				$sponsorship = new RewardsSponsorshipModel((int)Tools::getValue('id_sponsorship_to_update'));
				$sponsorship->date_end = Tools::getValue('date_end_' . Tools::getValue('id_sponsorship_to_update'));
				$sponsorship->save();
				return $this->instance->displayConfirmation($this->l('The sponsorship end date has been updated.'));
			} else
				return $this->instance->displayError(implode('<br />', $this->_errors));
		}
	}

	private function _postValidation()
	{
		$this->_errors = array();

		if (Tools::isSubmit('submitSponsorship')) {
			$currency = array();
			$currencies = Currency::getCurrencies();
			foreach ($currencies as $value) {
				$currency[$value['id_currency']] = htmlentities($value['name'], ENT_NOQUOTES, 'utf-8');
			}

			if (empty($this->id_template)) {
				if (Tools::getValue('popup') && (!is_numeric(Tools::getValue('popup_delay')) || Tools::getValue('popup_delay') <= 0))
					$this->_errors[] = $this->l('The number of days before opening the popup again, is invalid.');
				if (Tools::getValue('open_inviter') && Tools::getValue('open_inviter_login') == '')
					$this->_errors[] = $this->l('The Open Inviter username is empty.');
				if (Tools::getValue('open_inviter') && Tools::getValue('open_inviter_key') == '')
					$this->_errors[] = $this->l('The Open Inviter key is empty.');
				if (Tools::getValue('share_image_url') && !Validate::isAbsoluteUrl(Tools::getValue('share_image_url')))
					$this->_errors[] = $this->l('The url to force for Facebook share is invalid.');
				if (!is_numeric(Tools::getValue('order_quantity_s')) || Tools::getValue('order_quantity_s') < 0)
					$this->_errors[] = $this->l('The number of orders to be able to become a sponsor is invalid.');
				if (!is_numeric(Tools::getValue('nb_friends')) || Tools::getValue('nb_friends') <= 0)
					$this->_errors[] = $this->l('The number of lines displayed in the invitation form is required/invalid.');
				if (!is_array(Tools::getValue('rsponsorship_groups')))
					$this->_errors[] = $this->l('Please select at least 1 customer group allowed to sponsor its friends');

				if(Tools::getValue('reward_s')) {
					if (!is_numeric(Tools::getValue('rsponsorship_duration')) || Tools::getValue('rsponsorship_duration') < 0)
						$this->_errors[] = $this->l('The duration of the sponsorship is required/invalid.');
					foreach (Tools::getValue('reward_percentage') as $level => $reward_percentage) {
						if (!Validate::isUnsignedFloat($reward_percentage))
							$this->_errors[] = $this->l('The percentage of the sponsored\'s order is invalid for level').' '.($level+1);
					}
					foreach (Tools::getValue('reward_value_s') as $id_currency =>  $reward_value_s) {
						if (is_array($reward_value_s)) {
							foreach($reward_value_s as $level => $value) {
								if (empty($value))
									$this->_errors[] = $this->l('Reward amount for the level').' '.($level+1).' '.$this->l('and the currency').' '.$currency[$id_currency].' '.$this->l('is empty.');
								elseif (!Validate::isUnsignedFloat($value))
									$this->_errors[] = $this->l('Reward amount for the level').' '.($level+1).' '.$this->l('and the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
							}
						}
					}
					foreach (Tools::getValue('unlock_gc') as $id_currency => $unlock_gc) {
						if (!empty($unlock_gc) && !Validate::isUnsignedFloat($unlock_gc))
							$this->_errors[] = $this->l('Minimum unlock amount for the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
					}
				}
				if (Tools::getValue('discount_gc')) {
					foreach (Tools::getValue('discount_value_gc') as $id_currency => $discount_value_gc) {
						if (empty($discount_value_gc) && (int)Tools::getValue('discount_type_gc') != 0)
							$this->_errors[] = $this->l('Voucher value for the currency').' '.$currency[$id_currency].' '.$this->l('is empty.');
						elseif (!Validate::isUnsignedFloat($discount_value_gc))
							$this->_errors[] = $this->l('Voucher value for the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
					}
					foreach (Tools::getValue('minimum_value_gc') as $id_currency => $minimum_value_gc) {
						if (!empty($minimum_value_gc) && !Validate::isUnsignedFloat($minimum_value_gc))
							$this->_errors[] = $this->l('Minimum order amount for the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
					}
					foreach (Tools::getValue('description_gc') as $id_language => $description) {
						$lang = Language::getLanguage($id_language);
						if (empty($description))
							$this->_errors[] = $this->l('Voucher description is required for').' '.$lang['name'];
					}
					if (Tools::getValue('voucher_prefix_gc') == '' || !Validate::isDiscountName(Tools::getValue('voucher_prefix_gc')))
						$this->_errors[] = $this->l('Prefix for the voucher code is required/invalid.');
					if (!is_numeric(Tools::getValue('voucher_duration_gc')) || (int)Tools::getValue('voucher_duration_gc') <= 0)
						$this->_errors[] = $this->l('The validity of the voucher is required/invalid.');
					if (!is_numeric(Tools::getValue('voucher_quantity_gc')) || (int)Tools::getValue('voucher_quantity_gc') <= 0)
						$this->_errors[] = $this->l('The number of times the voucher can be used is required/invalid.');
					if (!is_array(Tools::getValue('voucher_categories_gc')) || !sizeof(Tools::getValue('voucher_categories_gc')))
						$this->_errors[] = $this->l('You must choose at least one category of products');
					if ((int)Tools::getValue('discount_type_gc')==0 && (int)Tools::getValue('freeshipping_gc')==0)
						$this->_errors[] = $this->l('You must offer at least free shipping or/and discount.');
				}
			} else {
				if (Tools::getValue('share_image_url') && !Validate::isAbsoluteUrl(Tools::getValue('share_image_url')))
					$this->_errors[] = $this->l('The url to force for Facebook share is invalid.');
				if (!is_numeric(Tools::getValue('order_quantity_s')) || Tools::getValue('order_quantity_s') < 0)
					$this->_errors[] = $this->l('The number of orders to be able to become a sponsor is invalid.');

				if(Tools::getValue('reward_s')) {
					if (!is_numeric(Tools::getValue('rsponsorship_duration')) || Tools::getValue('rsponsorship_duration') < 0)
						$this->_errors[] = $this->l('The duration of the sponsorship is required/invalid.');
					foreach (Tools::getValue('reward_percentage') as $level => $reward_percentage) {
						if (!Validate::isUnsignedFloat($reward_percentage))
							$this->_errors[] = $this->l('The percentage of the sponsored\'s order is invalid for level').' '.($level+1);
					}
					foreach (Tools::getValue('reward_value_s') as $id_currency =>  $reward_value_s) {
						if (is_array($reward_value_s)) {
							foreach($reward_value_s as $level => $value) {
								if (empty($value))
									$this->_errors[] = $this->l('Reward amount for the level').' '.($level+1).' '.$this->l('and the currency').' '.$currency[$id_currency].' '.$this->l('is empty.');
								elseif (!Validate::isUnsignedFloat($value))
									$this->_errors[] = $this->l('Reward amount for the level').' '.($level+1).' '.$this->l('and the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
							}
						}
					}
					foreach (Tools::getValue('unlock_gc') as $id_currency => $unlock_gc) {
						if (!empty($unlock_gc) && !Validate::isUnsignedFloat($unlock_gc))
							$this->_errors[] = $this->l('Minimum unlock amount for the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
					}
				}
				if (Tools::getValue('discount_gc')) {
					foreach (Tools::getValue('discount_value_gc') as $id_currency => $discount_value_gc) {
						if (empty($discount_value_gc) && (int)Tools::getValue('discount_type_gc') != 0)
							$this->_errors[] = $this->l('Voucher value for the currency').' '.$currency[$id_currency].' '.$this->l('is empty.');
						elseif (!Validate::isUnsignedFloat($discount_value_gc))
							$this->_errors[] = $this->l('Voucher value for the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
					}
					foreach (Tools::getValue('minimum_value_gc') as $id_currency => $minimum_value_gc) {
						if (!empty($minimum_value_gc) && !Validate::isUnsignedFloat($minimum_value_gc))
							$this->_errors[] = $this->l('Minimum order amount for the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
					}
					foreach (Tools::getValue('description_gc') as $id_language => $description) {
						$lang = Language::getLanguage($id_language);
						if (empty($description))
							$this->_errors[] = $this->l('Voucher description is required for').' '.$lang['name'];
					}
					if (Tools::getValue('voucher_prefix_gc') == '' || !Validate::isDiscountName(Tools::getValue('voucher_prefix_gc')))
						$this->_errors[] = $this->l('Prefix for the voucher code is required/invalid.');
					if (!is_numeric(Tools::getValue('voucher_duration_gc')) || (int)Tools::getValue('voucher_duration_gc') <= 0)
						$this->_errors[] = $this->l('The validity of the voucher is required/invalid.');
					if (!is_numeric(Tools::getValue('voucher_quantity_gc')) || (int)Tools::getValue('voucher_quantity_gc') <= 0)
						$this->_errors[] = $this->l('The number of times the voucher can be used is required/invalid.');
					if (!is_array(Tools::getValue('voucher_categories_gc')) || !sizeof(Tools::getValue('voucher_categories_gc')))
						$this->_errors[] = $this->l('You must choose at least one category of products');
					if ((int)Tools::getValue('discount_type_gc')==0 && (int)Tools::getValue('freeshipping_gc')==0)
						$this->_errors[] = $this->l('You must offer at least free shipping or/and discount.');
				}
			}
		} else if (Tools::isSubmit('submitSponsorshipEndDate')) {
			if (Tools::getValue('date_end_' . Tools::getValue('id_sponsorship_to_update')) && !Validate::isDate(Tools::getValue('date_end_' . Tools::getValue('id_sponsorship_to_update'))))
				$this->_errors[] = $this->l('The date is invalid.');
		}
	}

	public function getContent()
	{
		// on initialise le template à chaque chargement
		$this->initTemplate();

		if (Tools::getValue('stats')) {
			$id_sponsor = Tools::getValue('id_sponsor');
			echo $this->_getStatistics(empty($id_sponsor) ? null : $id_sponsor);
			exit;
		} else {
			if (Tools::isSubmit('submitSponsorship') || Tools::isSubmit('submitSponsorshipNotifications') || Tools::isSubmit('submitSponsorshipText'))
				$this->postProcess();

			$this->_initConf($this->id_template);
			return $this->_displayForm();
		}
	}

	private function _displayForm()
	{
		// Languages preliminaries
		$defaultLanguage = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages();

		$currencies = Currency::getCurrencies();
		$groups = Group::getGroups($this->context->language->id);
		$allowed_groups = explode(',', Configuration::get('RSPONSORSHIP_GROUPS'));
		$categories = $this->instance->getCategories();

		$html = $this->getTemplateForm($this->id_template, $this->name, $this->l('Sponsorship')).'
		<div class="tabs" style="display: none">
			<ul>
				<li><a href="#tabs-'.$this->name.'-1">'.$this->l('Settings').'</a></li>
				<li class="not_templated"><a href="#tabs-'.$this->name.'-2">'.$this->l('Notifications').'</a></li>
				<li><a href="#tabs-'.$this->name.'-3">'.$this->l('Texts').'</a></li>
				<li class="not_templated"><a href="'.$_SERVER['REQUEST_URI'].'&stats=1">'.$this->l('Statistics').'</a></li>
			</ul>
			<div id="tabs-'.$this->name.'-1">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
				<input type="hidden" name="plugin" id="plugin" value="' . $this->name . '" />
				<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-1" />
				<fieldset>
					<legend>'.$this->l('General settings').'</legend>
					<div>
						<label>'.$this->l('Activate sponsorship program').'</label>
						<div class="margin-form">
							<label class="t" for="sponsorship_active_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="sponsorship_active_on" name="sponsorship_active" value="1" '.(Tools::getValue('sponsorship_active', MyConf::get('RSPONSORSHIP_ACTIVE', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="sponsorship_active_on">' . $this->l('Yes') . '</label>
							<label class="t" for="sponsorship_active_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="sponsorship_active_off" name="sponsorship_active" value="0" '.(Tools::getValue('sponsorship_active', MyConf::get('RSPONSORSHIP_ACTIVE', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="sponsorship_active_off">' . $this->l('No') . '</label>
						</div>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Give a reward to the sponsor').'</label>
					<div class="margin-form">
						<label class="t" for="reward_s_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="reward_s_on" name="reward_s" value="1" '.(Tools::getValue('reward_s', MyConf::get('RSPONSORSHIP_REWARD_S', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="reward_s_on">' . $this->l('Yes') . '</label>
						<label class="t" for="reward_s_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="reward_s_off" name="reward_s" value="0" '.(Tools::getValue('reward_s', MyConf::get('RSPONSORSHIP_REWARD_S', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="reward_s_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Give a reward to the sponsored').'</label>
					<div class="margin-form">
						<label class="t" for="discount_gc_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="discount_gc_on" name="discount_gc" value="1" '.(Tools::getValue('discount_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_GC', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="discount_gc_on">' . $this->l('Yes') . '</label>
						<label class="t" for="discount_gc_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="discount_gc_off" name="discount_gc" value="0" '.(Tools::getValue('discount_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_GC', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="discount_gc_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear not_templated">
						<label>'.$this->l('Propose the sponsorship program on the order confirmation page').'</label>
						<div class="margin-form">
							<label class="t" for="after_order_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="after_order_on" name="after_order" value="1" '.(Tools::getValue('after_order', Configuration::get('RSPONSORSHIP_AFTER_ORDER')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="after_order_on">' . $this->l('Yes') . '</label>
							<label class="t" for="after_order_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="after_order_off" name="after_order" value="0" '.(Tools::getValue('after_order', Configuration::get('RSPONSORSHIP_AFTER_ORDER')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="after_order_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Open a popup to propose sponsorship program to customers').'</label>
						<div class="margin-form">
							<label class="t" for="popup_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="popup_on" name="popup" value="1" '.(Tools::getValue('popup', Configuration::get('RSPONSORSHIP_POPUP')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="popup_on">' . $this->l('Yes') . '</label>
							<label class="t" for="popup_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="popup_off" name="popup" value="0" '.(Tools::getValue('popup', Configuration::get('RSPONSORSHIP_POPUP')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="popup_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear optional popup_optional">
							<div class="clear"></div>
							<label>'.$this->l('Reset the last opening date for all customers').'</label>
							<div class="margin-form">
								<label class="t" for="popup_reset_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
								<input type="radio" id="popup_reset_on" name="popup_reset" value="1" /> <label class="t" for="popup_reset_on">' . $this->l('Yes') . '</label>
								<label class="t" for="popup_reset_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
								<input type="radio" id="popup_reset_off" name="popup_reset" value="0" checked /> <label class="t" for="popup_reset_off">' . $this->l('No') . '</label>
							</div>
							<div class="clear"></div>
							<label>'.$this->l('Delay before opening the popup again for the same customer (in days)').'</label>
							<div class="margin-form">
								<input type="text" size="2" maxlength="2" name="popup_delay" id="popup_delay" value="'.(Tools::getValue('popup_delay', Configuration::get('RSPONSORSHIP_POPUP_DELAY'))).'" />
							</div>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Use Open Inviter to invite friends - create an account on').' <a href="http://openinviter.com" target="_blank">openinviter.com</a></label>
						<div class="margin-form">
							<label class="t" for="open_inviter_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="open_inviter_on" name="open_inviter" value="1" '.(Tools::getValue('open_inviter', Configuration::get('RSPONSORSHIP_OPEN_INVITER')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="open_inviter_on">' . $this->l('Yes') . '</label>
							<label class="t" for="open_inviter_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="open_inviter_off" name="open_inviter" value="0" '.(Tools::getValue('open_inviter', Configuration::get('RSPONSORSHIP_OPEN_INVITER')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="open_inviter_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear optional open_inviter_optional">
							<div class="clear"></div>
							<label>'.$this->l('Open Inviter username').'</label>
							<div class="margin-form">
								<input type="text" size="25" maxlength="40" name="open_inviter_login" id="open_inviter_login" value="'.(Tools::getValue('open_inviter_login', Configuration::get('RSPONSORSHIP_OPEN_INVITER_LOGIN'))).'" />
							</div>
							<div class="clear"></div>
							<label>'.$this->l('Open Inviter key').'</label>
							<div class="margin-form">
								<input type="text" size="40" maxlength="40" name="open_inviter_key" id="open_inviter_key" value="'.(Tools::getValue('open_inviter_key', Configuration::get('RSPONSORSHIP_OPEN_INVITER_KEY'))).'" />
							</div>
						</div>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Url of the image to force for Facebook share (at least 200*200px)').'</label>
					<div class="margin-form">
						<input type="text" size="60" name="share_image_url" id="share_image_url" value="'.(Tools::getValue('share_url_image', MyConf::get('RSPONSORSHIP_SHARE_IMAGE_URL', null, $this->id_template))).'" />
					</div>
					<div class="clear"/>
					<label>'.$this->l('Number of lines displayed in the invitation form').'</label>
					<div class="margin-form">
						<input type="text" size="3" maxlength="3" name="nb_friends" id="nb_friends" value="'.(int)(Tools::getValue('nb_friends', MyConf::get('RSPONSORSHIP_NB_FRIENDS', null, $this->id_template))).'" />
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Number of orders to be able to become a sponsor (0 is allowed)').'</label>
					<div class="margin-form">
						<input type="text" size="3" maxlength="3" name="order_quantity_s" id="order_quantity_s" value="'.(int)(Tools::getValue('order_quantity_s', MyConf::get('RSPONSORSHIP_ORDER_QUANTITY_S', null, $this->id_template))).'" />
					</div>
					<div class="clear not_templated">
						<label>'.$this->l('Customers groups allowed to sponsor their friends').'</label>
						<div class="margin-form">
							<select name="rsponsorship_groups[]" multiple="multiple" class="multiselect">';
		foreach($groups as $group) {
			$html .= '			<option '.(is_array($allowed_groups) && in_array($group['id_group'], $allowed_groups) ? 'selected':'').' value="'.$group['id_group'].'"> '.$group['name'].'</option>';
		}
		$html .= '
							</select>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Target of the sponsorship link').'</label>
						<div class="margin-form">
							<input type="radio" id="sponsorship_redirect_home" name="sponsorship_redirect" value="home" '.(Tools::getValue('sponsorship_redirect', Configuration::get('RSPONSORSHIP_REDIRECT')) == 'home' ? 'checked="checked"' : '').' /> <label class="t" for="sponsorship_redirect_home" style="padding-right: 10px">' . $this->l('Homepage') . '</label>
							<input type="radio" id="sponsorship_redirect_form" name="sponsorship_redirect" value="form" '.(Tools::getValue('sponsorship_redirect', Configuration::get('RSPONSORSHIP_REDIRECT')) == 'form' ? 'checked="checked"' : '').' /> <label class="t" for="sponsorship_redirect_form">' . $this->l('Subscription form') . '</label>
						</div>
					</div>
				</fieldset>
				<fieldset class="sponsor_optional">
					<legend>'.$this->l('Sponsor\'s settings').'</legend>
					<label>'.$this->l('Duration of the sponsorship (in days, 0=unlimited)').'</label>
					<div class="margin-form">
						<input type="text" size="4" maxlength="4" id="rsponsorship_duration" name="rsponsorship_duration" value="'.Tools::getValue('rsponsorship_duration', MyConf::get('RSPONSORSHIP_DURATION', null, $this->id_template)).'" />
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Get a reward for every orders from a sponsored friend, when total > 0 (shipping excluded)').'</label>
					<div class="margin-form">
						<label class="t" for="discount_gc_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="reward_on_every_order_on" name="reward_on_every_order" value="1" '.(Tools::getValue('reward_on_every_order', MyConf::get('RSPONSORSHIP_ON_EVERY_ORDER', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="discount_gc_on">'.$this->l('Yes').'</label>
						<label class="t" for="discount_gc_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="reward_on_every_order_off" name="reward_on_every_order" value="0" '.(Tools::getValue('reward_on_every_order', MyConf::get('RSPONSORSHIP_ON_EVERY_ORDER', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="discount_gc_off">'.$this->l('No, only for the first one').'</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Take in account the discounted products to calculate the total of the order').'</label>
					<div class="margin-form">
						<label class="t" for="rsponsorship_discounted_allowed_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="rsponsorship_discounted_allowed_on" name="rsponsorship_discounted_allowed" value="1" '.(Tools::getValue('rsponsorship_discounted_allowed', MyConf::get('RSPONSORSHIP_DISCOUNTED_ALLOWED', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="rsponsorship_discounted_allowed_on">' . $this->l('Yes') . '</label>
						<label class="t" for="rsponsorship_discounted_allowed_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="rsponsorship_discounted_allowed_off" name="rsponsorship_discounted_allowed" value="0" '.(Tools::getValue('rsponsorship_discounted_allowed', MyConf::get('RSPONSORSHIP_DISCOUNTED_ALLOWED', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="rsponsorship_discounted_allowed_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear" style="padding-top: 10px"></div>
					<label class="t" style="width: 100% !important"><strong>'.$this->l('Minimum amount required for the sponsored\'s order to unlock the sponsor\'s reward (discounted products included)').'</strong></label>
					<div class="clear" style="padding-top: 5px"></div>
					<label class="indent">'.$this->l('Calculated using').'</label>
					<div class="margin-form">
						<input type="radio" id="unlock_shipping_on" name="unlock_shipping" value="1" '.(Tools::getValue('unlock_shipping', MyConf::get('RSPONSORSHIP_UNLOCK_SHIPPING', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="unlock_shipping_on" style="padding-right: 10px">' . $this->l('Total with shipping included') . '</label>
						<input type="radio" id="unlock_shipping_off" name="unlock_shipping" value="0" '.(Tools::getValue('unlock_shipping', MyConf::get('RSPONSORSHIP_UNLOCK_SHIPPING', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="unlock_shipping_off">' . $this->l('Total without shipping') . '</label>
					</div>
					<div class="clear">';
		foreach ($currencies as $currency) {
			$html .= '
						<div class="clear"></div>
						<label class="indent">'.$this->l('Minimum value for an order in').' '.htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8').'</label>
						<div class="margin-form"><input '. ((int)$currency['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT') ? 'class="currency_default"' : '') . ' type="text" size="8" maxlength="8" name="unlock_gc['.(int)($currency['id_currency']).']" id="unlock_gc['.(int)($currency['id_currency']).']" value="'.Tools::getValue('unlock_gc['.(int)($currency['id_currency']).']', MyConf::get('RSPONSORSHIP_UNLOCK_GC_'.(int)$currency['id_currency'], null, $this->id_template)).'" /> <label class="t">'.$currency['sign'].'</label>'.((int)$currency['id_currency'] != (int)Configuration::get('PS_CURRENCY_DEFAULT') ? ' <a href="#" onClick="return convertCurrencyValue(this, \'unlock_gc\', '.$currency['conversion_rate'].')"><img src="'._MODULE_DIR_.'allinone_rewards/img/convert.gif" style="vertical-align: middle !important"></a>' : '').'</div>';
		}
		$html .= '
					</div>
					<div class="clear" style="padding-top: 10px"></div>
					<label class="t" style="width: 100% !important"><strong>'.$this->l('If you want to reward the sponsors on several levels, you can define as many levels as necessary').'</strong></label>';

		foreach($this->_configuration['reward_type'] as $level => $reward_type) {
			$html .= '
					<div class="clear level_information">
						<label class="level"><strong>'.$this->l('Reward\'s settings for level').' <span class="numlevel">'.($level + 1).'</span></strong> <a class="not_templated" href="#" onClick="return delSponsorshipLevel(this)"><img src="../img/admin/delete.gif" alt="'.$this->l('Delete this level').'" align="absmiddle"></a></label>
						<div class="clear"></div>
						<label class="indent">'.$this->l('Reward type').'</label>
						<div class="margin-form">
							<input type="radio" onClick="checkType($(this))" name="reward_type_s['.$level.']" value="1" '.($reward_type == 1 ? 'checked="checked"' : '').' /> <label class="t" style="padding-right: 10px">'.$this->l('Fixed amount').'</label>
							<input type="radio" onClick="checkType($(this))" name="reward_type_s['.$level.']" value="2" '.($reward_type == 2 ? 'checked="checked"' : '').' /> <label class="t">'.$this->l('% of the order\'s total (shipping excluded)').'</label>
						</div>
						<div class="reward_amount">';
			foreach ($currencies as $currency) {
				$html .= '
							<div class="clear"></div>
							<label class="indent">'.$this->l('Reward value for an order in').' '.htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8').'</label>
							<div class="margin-form"><input '. ((int)$currency['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT') ? 'class="currency_default"' : '') . ' type="text" size="8" maxlength="8" name="reward_value_s['.(int)($currency['id_currency']).']['.$level.']" value="'.$this->_configuration['reward_value'][$level][$currency['id_currency']].'" /> <label class="t">'.$currency['sign'].'</label>'.((int)$currency['id_currency'] != (int)Configuration::get('PS_CURRENCY_DEFAULT') ? ' <a href="#" onClick="return convertCurrencyValue(this, \'reward_value_s\', '.$currency['conversion_rate'].')"><img src="'._MODULE_DIR_.'allinone_rewards/img/convert.gif" style="vertical-align: middle !important"></a>' : '').'</div>';
			}
			$html .= '
						</div>
						<div class="clear"></div>
						<div class="reward_percentage">
							<label class="indent">'.$this->l('Percentage').'</label>
							<div class="margin-form">
								<input type="text" size="3" name="reward_percentage['.$level.']" value="'.$this->_configuration['reward_percentage'][$level].'" /> %
							</div>
						</div>
					</div>';
		}
		$html .= '
					<div class="clear not_templated">
						<label>'.$this->l('All next levels will use the settings from level').' <span id="unlimited_level">'.count($this->_configuration['reward_type']).'</span></label>
						<div class="margin-form">
							<label class="t" for="unlimited_levels_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="unlimited_levels_on" name="unlimited_levels" value="1" '.(Tools::getValue('unlimited_levels', $this->_configuration['unlimited']) ? 'checked="checked"' : '').' /> <label class="t" for="unlimited_levels_on">' . $this->l('Yes') . '</label>
							<label class="t" for="unlimited_levels_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="unlimited_levels_off" name="unlimited_levels" value="0" '.(Tools::getValue('unlimited_levels', Configuration::get('RSPONSORSHIP_UNLIMITED_LEVELS')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="unlimited_levels_off">' . $this->l('No, we don\'t need any additional levels') . '</label>
						</div>
						<div class="clear center">
							<input class="button not_templated" style="margin-top: 10px" id="add_level" value="'.$this->l('Add a level').'" type="button" />
						</div>
					</div>
				</fieldset>
				<fieldset id="sponsored" class="sponsored_optional">
					<legend>'.$this->l('Sponsored\'s settings').'</legend>
					<label>'.$this->l('Voucher details (will appear in cart next to voucher code)').'</label>
					<div class="margin-form translatable">';
		$description_gc = Tools::getValue('description_gc');
		foreach ($languages as $language) {
			$html .= '
						<div class="lang_'.$language['id_lang'].'" id="descgc_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
							<input size="30" type="text" name="description_gc['.$language['id_lang'].']" value="'.htmlentities($description_gc[(int)$language['id_lang']] ? $description_gc[(int)$language['id_lang']] : MyConf::get('RSPONSORSHIP_VOUCHER_DETAILS', (int)$language['id_lang'], $this->id_template), ENT_QUOTES, 'utf-8').'" />
						</div>';
		}
		$html .= '
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Prefix for the voucher code (at least 3 letters long)').'</label>
					<div class="margin-form">
						<input type="text" size="10" maxlength="10" id="voucher_prefix_gc" name="voucher_prefix_gc" value="'.Tools::getValue('voucher_prefix_gc', MyConf::get('RSPONSORSHIP_VOUCHER_PREFIX_GC', null, $this->id_template)).'" />
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Validity of the voucher (in days)').'</label>
					<div class="margin-form">
						<input type="text" size="4" maxlength="4" id="voucher_duration_gc" name="voucher_duration_gc" value="'.Tools::getValue('voucher_duration_gc', MyConf::get('RSPONSORSHIP_VOUCHER_DURATION_GC', null, $this->id_template)).'" />
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Number of times the voucher can be used by the sponsored friend').'</label>
					<div class="margin-form">
						<input type="text" size="4" maxlength="4" id="voucher_quantity_gc" name="voucher_quantity_gc" value="'.Tools::getValue('voucher_quantity_gc', MyConf::get('RSPONSORSHIP_QUANTITY_GC', null, $this->id_template)).'" />
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Free shipping').'</label>
					<div class="margin-form">
						<label class="t" for="freeshipping_gc_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="freeshipping_gc_on" name="freeshipping_gc" value="1" '.(Tools::getValue('freeshipping_gc', MyConf::get('RSPONSORSHIP_FREESHIPPING_GC', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="freeshipping_gc_on">' . $this->l('Yes') . '</label>
						<label class="t" for="freeshipping_gc_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="freeshipping_gc_off" name="freeshipping_gc" value="0" '.(Tools::getValue('freeshipping_gc', MyConf::get('RSPONSORSHIP_FREESHIPPING_GC', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="freeshipping_gc_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Apply a discount').'</label>
					<div class="margin-form">
						<input onClick="$(\'#sponsored td.voucher_value\').html(\'' . $this->l('Voucher %') . '\');$(\'#sponsored td.value_cols\').show();$(\'#behavior_gc\').hide();$(\'#voucher_behavior_gc\').val(0)" type="radio" id="discount_type_gc_1" name="discount_type_gc" value="1" '.(Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="discount_type_gc_1" style="padding-right: 10px">'.$this->l('Percentage').'</label>
						<input onClick="$(\'#sponsored td.value_cols\').hide();$(\'#sponsored td.voucher_value\').html(\'' . $this->l('Voucher value') . '\');$(\'#sponsored td.value_cols\').show();$(\'#behavior_gc\').show()" type="radio" id="discount_type_gc_2" name="discount_type_gc" value="2" '.(Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 2 ? 'checked="checked"' : '').' /> <label class="t" for="discount_type_gc_2" style="padding-right: 10px">'.$this->l('Amount').'</label>
						<input onClick="$(\'#sponsored td.value_cols\').hide();$(\'#behavior_gc\').hide();$(\'#voucher_behavior_gc\').val(0)" type="radio" id="discount_type_gc_0" name="discount_type_gc" value="0" '.(Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="discount_type_gc_0">'.$this->l('None').'</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Allowed categories').'</label>
					<div class="margin-form">
						<table cellspacing="0" cellpadding="0" class="table">
							<tr>
								<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'voucher_categories_gc[]\', this.checked)" /></th>
								<th>'.$this->l('ID').'</th>
								<th style="width: 400px">'.$this->l('Name').'</th>
							</tr>';
		$index = Tools::getValue('voucher_categories_gc') ? Tools::getValue('voucher_categories_gc') : explode(',', MyConf::get('RSPONSORSHIP_CATEGORIES_GC', null, $this->id_template));
		$current = current(current($categories));
		$html .= $this->recurseCategoryForInclude('voucher_categories_gc', $index, $categories, $current, $current['infos']['id_category']);
		$html .= '
						</table>
					</div>
					<div id="behavior_gc" style="display:'.(Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 2 ? 'block' : 'none').'">
						<div class="clear"></div>
						<label>'.$this->l('If the voucher is not depleted when used').'</label>&nbsp;
						<div class="margin-form">
							<select name="voucher_behavior_gc" id="voucher_behavior_gc">
								<option '.(!Tools::getValue('voucher_behavior_gc', (int)MyConf::get('RSPONSORSHIP_VOUCHER_BEHAVIOR', null, $this->id_template)) ?'selected':'').' value="0">'.$this->l('Cancel the remaining amount').'</option>
								<option '.(Tools::getValue('voucher_behavior_gc', (int)MyConf::get('RSPONSORSHIP_VOUCHER_BEHAVIOR', null, $this->id_template)) ?'selected':'').' value="1">'.$this->l('Create a new voucher with remaining amount').'</option>
							</select>
						</div>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Cumulative with other vouchers').'</label>
					<div class="margin-form">
						<label class="t" for="cumulative_voucher_gc_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="cumulative_voucher_gc_on" name="cumulative_voucher_gc" value="1" '.(Tools::getValue('cumulative_voucher_gc', MyConf::get('RSPONSORSHIP_CUMUL_GC', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="cumulative_voucher_gc_on">' . $this->l('Yes') . '</label>
						<label class="t" for="cumulative_voucher_gc_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="cumulative_voucher_gc_off" name="cumulative_voucher_gc" value="0" '.(Tools::getValue('cumulative_voucher_gc', MyConf::get('RSPONSORSHIP_CUMUL_GC', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="cumulative_voucher_gc_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('The minimum order\'s amount to use the voucher includes tax').'</label>
					<div class="margin-form">
						<label class="t" for="include_tax_gc_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="include_tax_gc_on" name="include_tax_gc" value="1" '.(Tools::getValue('include_tax_gc', MyConf::get('RSPONSORSHIP_MINIMAL_TAX_GC', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="include_tax_gc_on">' . $this->l('Yes') . '</label>
						<label class="t" for="include_tax_gc_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="include_tax_gc_off" name="include_tax_gc" value="0" '.(Tools::getValue('include_tax_gc', MyConf::get('RSPONSORSHIP_MINIMAL_TAX_GC', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="include_tax_gc_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<div>
						<table>
							<tr>
								<td class="label" style="font-weight: bold">' . $this->l('Currency used by the sponsored when registering') . '</td>
								<td width="165" class="voucher_value value_cols" style="font-weight: bold; display:'.(Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 0 ? 'none' : 'block').'">' . (Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 1 ? $this->l('Voucher %') : $this->l('Voucher value')) . '</td>
								<td class="value_cols" style="width: 30px">&nbsp;</td>
								<td width="200" style="font-weight: bold">' . $this->l('Minimum order\'s amount') . '</td>
							</tr>';
		foreach ($currencies as $currency) {
			$html .= '
							<tr>
								<td><label class="indent">' . htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8') . '</label></td>
								<td align="left" class="value_cols" style="display:'.(Tools::getValue('discount_type_gc', MyConf::get('RSPONSORSHIP_DISCOUNT_TYPE_GC', null, $this->id_template)) == 0 ? 'none' : 'block').'"><input '. ((int)$currency['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT') ? 'class="currency_default"' : '') . ' type="text" size="8" maxlength="8" name="discount_value_gc['.(int)($currency['id_currency']).']" id="discount_value_gc['.(int)($currency['id_currency']).']" value="'.Tools::getValue('discount_value_gc['.(int)($currency['id_currency']).']', MyConf::get('RSPONSORSHIP_VOUCHER_VALUE_GC_'.(int)$currency['id_currency'], null, $this->id_template)).'" />'.((int)$currency['id_currency'] != (int)Configuration::get('PS_CURRENCY_DEFAULT') ? ' <a href="#" onClick="return convertCurrencyValue(this, \'discount_value_gc\', '.$currency['conversion_rate'].')"><img src="'._MODULE_DIR_.'allinone_rewards/img/convert.gif" style="vertical-align: middle !important"></a>' : '').'</td>
								<td class="value_cols">&nbsp;</td>
								<td align="left"><input '. ((int)$currency['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT') ? 'class="currency_default"' : '') . ' type="text" size="8" maxlength="8" name="minimum_value_gc['.(int)($currency['id_currency']).']" id="minimum_value_gc['.(int)($currency['id_currency']).']" value="'.Tools::getValue('minimum_value_gc['.(int)($currency['id_currency']).']', MyConf::get('RSPONSORSHIP_MINIMUM_VALUE_GC_'.(int)$currency['id_currency'], null, $this->id_template)).'" />'.((int)$currency['id_currency'] != (int)Configuration::get('PS_CURRENCY_DEFAULT') ? ' <a href="#" onClick="return convertCurrencyValue(this, \'minimum_value_gc\', '.$currency['conversion_rate'].')"><img src="'._MODULE_DIR_.'allinone_rewards/img/convert.gif" style="vertical-align: middle !important"></a>' : '').'</td>
							</tr>';
		}
		$html .= '
						</table>
					</div>
				</fieldset>
				<div class="clear center"><input class="button" name="submitSponsorship" id="submitSponsorship" value="'.$this->l('Save settings').'" type="submit" /></div>
				</form>
			</div>
			<div id="tabs-'.$this->name.'-2" class="not_templated">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
				<input type="hidden" name="plugin" id="plugin" value="' . $this->name . '" />
				<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-2" />
				<fieldset>
					<legend>'.$this->l('Notifications').'</legend>
					<label>'.$this->l('Send a mail to the admin on sponsored registration').'</label>
					<div class="margin-form">
						<label class="t" for="mail_admin_registration_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="mail_admin_registration_on" name="mail_admin_registration" value="1" '.(Tools::getValue('mail_admin_registration', Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_admin_registration_on">' . $this->l('Yes') . '</label>
						<label class="t" for="mail_admin_registration_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="mail_admin_registration_off" name="mail_admin_registration" value="0" '.(Tools::getValue('mail_admin_registration', Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_admin_registration_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Send a mail to the admin on sponsored order').'</label>
					<div class="margin-form">
						<label class="t" for="mail_admin_order_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="mail_admin_order_on" name="mail_admin_order" value="1" '.(Tools::getValue('mail_admin_order', Configuration::get('RSPONSORSHIP_MAIL_ORDER')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_admin_order_on">' . $this->l('Yes') . '</label>
						<label class="t" for="mail_admin_order_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="mail_admin_order_off" name="mail_admin_order" value="0" '.(Tools::getValue('mail_admin_order', Configuration::get('RSPONSORSHIP_MAIL_ORDER')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_admin_order_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<label>'.$this->l('Send a mail to the sponsor on sponsored registration').'</label>
					<div class="margin-form">
						<label class="t" for="mail_sponsor_registration_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="mail_sponsor_registration_on" name="mail_sponsor_registration" value="1" '.(Tools::getValue('mail_sponsor_registration', Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION_S')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_registration_on">' . $this->l('Yes') . '</label>
						<label class="t" for="mail_sponsor_registration_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="mail_sponsor_registration_off" name="mail_sponsor_registration" value="0" '.(Tools::getValue('mail_sponsor_registration', Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION_S')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_registration_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear"></div>
					<div class="sponsor_optional">
						<label>'.$this->l('Send a mail to the sponsor(s) on sponsored order').'</label>
						<div class="margin-form">
							<label class="t" for="mail_sponsor_order_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="mail_sponsor_order_on" name="mail_sponsor_order" value="1" '.(Tools::getValue('mail_sponsor_order', Configuration::get('RSPONSORSHIP_MAIL_ORDER_S')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_order_on">' . $this->l('Yes') . '</label>
							<label class="t" for="mail_sponsor_order_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="mail_sponsor_order_off" name="mail_sponsor_order" value="0" '.(Tools::getValue('mail_sponsor_order', Configuration::get('RSPONSORSHIP_MAIL_ORDER_S')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_order_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Send a mail to the sponsor(s) on reward validation/cancellation').'</label>
						<div class="margin-form">
							<label class="t" for="mail_sponsor_validation_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="mail_sponsor_validation_on" name="mail_sponsor_validation" value="1" '.(Tools::getValue('mail_sponsor_validation', Configuration::get('RSPONSORSHIP_MAIL_VALIDATION_S')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_validation_on">' . $this->l('Yes') . '</label>
							<label class="t" for="mail_sponsor_validation_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="mail_sponsor_validation_off" name="mail_sponsor_validation" value="0" '.(Tools::getValue('mail_sponsor_validation', Configuration::get('RSPONSORSHIP_MAIL_VALIDATION_S')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_validation_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Send a mail to the sponsor(s) on reward modification (product canceled)').'</label>
						<div class="margin-form">
							<label class="t" for="mail_sponsor_cancel_product_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="mail_sponsor_cancel_product_on" name="mail_sponsor_cancel_product" value="1" '.(Tools::getValue('mail_sponsor_cancel_product', Configuration::get('RSPONSORSHIP_MAIL_CANCELPROD_S')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_cancel_product_on">' . $this->l('Yes') . '</label>
							<label class="t" for="mail_sponsor_cancel_product_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="mail_sponsor_cancel_product_off" name="mail_sponsor_cancel_product" value="0" '.(Tools::getValue('mail_sponsor_cancel_product', Configuration::get('RSPONSORSHIP_MAIL_CANCELPROD_S')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="mail_sponsor_cancel_product_off">' . $this->l('No') . '</label>
						</div>
					</div>
				</fieldset>
				<div class="clear center"><input class="button" name="submitSponsorshipNotifications" id="submitSponsorshipNotifications" value="'.$this->l('Save settings').'" type="submit" /></div>
				</form>
			</div>
			<div id="tabs-'.$this->name.'-3">
				<form method="post" action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" enctype="multipart/form-data">
					<input type="hidden" name="plugin" id="plugin" value="' . $this->name . '" />
					<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-3" />
					<fieldset>
						<legend>'.$this->l('Text for the sponsorship form displayed in the customer account').'</legend>
						<div class="translatable">';
		foreach ($languages AS $language) {
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="account_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte autoload_rte" cols="120" rows="25" name="account_txt['.$language['id_lang'].']">'.MyConf::get('RSPONSORSHIP_ACCOUNT_TXT', (int)$language['id_lang'], $this->id_template).'</textarea>
							</div>';
		}
		$html .= '

						</div>
					</fieldset>
					<fieldset>
						<legend>'.$this->l('Text for the sponsorship popup displayed after an order').'</legend>
						<div class="translatable">';
		foreach ($languages AS $language) {
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="order_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte autoload_rte" cols="120" rows="25" name="order_txt['.$language['id_lang'].']">'.MyConf::get('RSPONSORSHIP_ORDER_TXT', (int)$language['id_lang'], $this->id_template).'</textarea>
							</div>';
		}
		$html .= '
						</div>
					</fieldset>
					<fieldset>
						<legend>'.$this->l('Text for the sponsorship popup displayed every X days').'</legend>
						<div class="translatable">';
		foreach ($languages AS $language) {
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="popup_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte autoload_rte" cols="120" rows="25" name="popup_txt['.$language['id_lang'].']">'.MyConf::get('RSPONSORSHIP_POPUP_TXT', (int)$language['id_lang'], $this->id_template).'</textarea>
							</div>';
		}
		$html .= '
						</div>
					</fieldset>
					<fieldset>
						<legend>'.$this->l('Sponsorship program rules').'</legend>
						<div class="translatable">';
		foreach ($languages AS $language) {
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="rules_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte autoload_rte" cols="120" rows="25" name="rules_txt['.$language['id_lang'].']">'.MyConf::get('RSPONSORSHIP_RULES_TXT', (int)$language['id_lang'], $this->id_template).'</textarea>
							</div>';
		}
		$html .= '
						</div>
					</fieldset>
					<div class="clear center"><input type="submit" name="submitSponsorshipText" value="'.$this->l('Save settings').'" class="button"/></div>
				</form>
			</div>
		</div>';
		return $html;
	}

	private function _getStatistics($id_sponsor=null)
	{
		$stats = RewardsSponsorshipModel::getAdminStatistics();
		if (!isset($id_sponsor)) {
			$result = "
			<div class='statistics'>
				<div>".$this->l('Only validated orders are taken in account in these statistics.')."</div>
				<div class='title'>".$this->l('General synthesis')."</div>
				<table class='general'>
					<tr class='title'>
						<td colspan='2' style='text-align: center'>".$this->l('Sponsors')."</td>
						<td colspan='5' style='text-align: center'>".$this->l('Sponsored friends')."</td>
					</tr>
					<tr class='title'>
						<td>".$this->l('Sponsors')."</td>
						<td>".$this->l('Total rewards')."</td>
						<td>".$this->l('Pending')."</td>
						<td>".$this->l('Registered')."</td>
						<td>".$this->l('With orders')."</td>
						<td>".$this->l('Number of orders')."</td>
						<td>".$this->l('Total orders')."</td>
					</tr>
					<tr>
						<td>".(int)$stats['nb_sponsors']."</td>
						<td>".Tools::displayPrice($stats['total_rewards'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
						<td>".(int)$stats['nb_pending']."</td>
						<td>".(int)$stats['nb_sponsored']."</td>
						<td>".(int)$stats['nb_buyers']."</td>
						<td>".(int)$stats['nb_orders']."</td>
						<td>".Tools::displayPrice($stats['total_orders'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
					</tr>
				</table>

				<div class='title'>".$this->l('Details by registration channel')."</div>
				<table class='channels'>
					<tr class='title'>
						<td class='title'>".$this->l('Channels')."</td>
						<td>".$this->l('Registered')."</td>
						<td>".$this->l('With orders')."</td>
						<td>".$this->l('Number of orders')."</td>
						<td class='price'>".$this->l('Total orders')."</td>
						<td class='price'>".$this->l('Total rewards')."</td>
					</tr>
					<tr>
						<td class='title'>".$this->l('Email invitation')."</td>
						<td>".(int)$stats['nb_sponsored1']."</td>
						<td>".(int)$stats['nb_buyers1']."</td>
						<td>".(int)$stats['nb_orders1']."</td>
						<td class='price'>".Tools::displayPrice($stats['total_orders1'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
						<td class='price'>".Tools::displayPrice($stats['total_rewards_channel1'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
					</tr>
					<tr>
						<td class='title'>".$this->l('Sponsorship link')."</td>
						<td>".(int)$stats['nb_sponsored2']."</td>
						<td>".(int)$stats['nb_buyers2']."</td>
						<td>".(int)$stats['nb_orders2']."</td>
						<td class='price'>".Tools::displayPrice($stats['total_orders2'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
						<td class='price'>".Tools::displayPrice($stats['total_rewards_channel2'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
					</tr>
					<tr>
						<td class='title'>".$this->l('Facebook')."</td>
						<td>".(int)$stats['nb_sponsored3']."</td>
						<td>".(int)$stats['nb_buyers3']."</td>
						<td>".(int)$stats['nb_orders3']."</td>
						<td class='price'>".Tools::displayPrice($stats['total_orders3'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
						<td class='price'>".Tools::displayPrice($stats['total_rewards_channel3'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
					</tr>
					<tr>
						<td class='title'>".$this->l('Twitter')."</td>
						<td>".(int)$stats['nb_sponsored4']."</td>
						<td>".(int)$stats['nb_buyers4']."</td>
						<td>".(int)$stats['nb_orders4']."</td>
						<td class='price'>".Tools::displayPrice($stats['total_orders4'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
						<td class='price'>".Tools::displayPrice($stats['total_rewards_channel4'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
					</tr>
					<tr>
						<td class='title'>".$this->l('Google +1')."</td>
						<td>".(int)$stats['nb_sponsored5']."</td>
						<td>".(int)$stats['nb_buyers5']."</td>
						<td>".(int)$stats['nb_orders5']."</td>
						<td class='price'>".Tools::displayPrice($stats['total_orders5'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
						<td class='price'>".Tools::displayPrice($stats['total_rewards_channel5'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
					</tr>
				</table>

				<div class='title'>".$this->l('Details by sponsor')."</div>
				<table class='sponsors'>
					<tr class='title'>
						<td colspan='4' style='text-align: center'>".$this->l('Sponsors')."</td>
						<td colspan='5' style='text-align: center'>".$this->l('Sponsored friends (Level 1)')."</td>
					</tr>
					<tr class='title'>
						<td>&nbsp;</td>
						<td class='title'>".$this->l('Name')."</td>
						<td class='price reward'>".$this->l('Rewards')."</td>
						<td class='price reward'>".$this->l('Indirect rewards')."</td>
						<td>".$this->l('Pending')."</td>
						<td>".$this->l('Registered')."</td>
						<td>".$this->l('With orders')."</td>
						<td>".$this->l('Orders')."</td>
						<td class='price'>".$this->l('Total')."</td>
					</tr>";
			$token = Tools::getAdminToken('AdminCustomers'.(int)Tab::getIdFromClassName('AdminCustomers').(int)$this->context->employee->id);
			if (isset($stats['sponsors'])) {
				foreach($stats['sponsors'] as $sponsor) {
					$result .= "
					<tr id='line_".$sponsor['id_sponsor']."'>
						<td>";
					if (isset($stats['sponsored'][$sponsor['id_sponsor']]) && is_array($stats['sponsored'][$sponsor['id_sponsor']])) {
						$result .= '
							<a href="javascript:showDetails(\''.$sponsor['id_sponsor'].'\', \''.$_SERVER['REQUEST_URI'].'\')"><img src="'._PS_ADMIN_IMG_.'details.gif"></a>
						';
					}
					$result .= "
						</td>
						<td class='title'><a href='?tab=AdminCustomers&id_customer=".$sponsor['id_sponsor']."&viewcustomer&token=".$token."'>" . $sponsor['lastname'] . " " . $sponsor['firstname'] . "</a></td>
						<td class='price reward'>" . Tools::displayPrice(isset($sponsor['direct_rewards']) ? $sponsor['direct_rewards'] : 0, (int)Configuration::get('PS_CURRENCY_DEFAULT')) . "</td>
						<td class='price reward'>" . Tools::displayPrice(isset($sponsor['indirect_rewards']) ? $sponsor['indirect_rewards'] : 0, (int)Configuration::get('PS_CURRENCY_DEFAULT')) . "</td>
						<td>".(int)$sponsor['nb_pending']."</td>
						<td>".(int)$sponsor['nb_registered']."</td>
						<td>".(int)$sponsor['nb_buyers']."</td>
						<td>".(int)$sponsor['nb_orders']."</td>
						<td class='price'>" . Tools::displayPrice((float)$sponsor['total_orders'], (int)Configuration::get('PS_CURRENCY_DEFAULT')) . "</td>
					</tr>";
				}
			}
			$result .= "
				</table>
			</div>";
		} else if (isset($stats['sponsored'][$id_sponsor]) && is_array($stats['sponsored'][$id_sponsor])) {
			$result = "
					<tr class='details'>
						<td colspan='9'>
							<table style='width: 90%; margin: 0 auto; margin-bottom: 30px'>
								<tr class='title'>
									<td>".$this->l('Levels')."</td>
									<td>".$this->l('Channels')."</td>
									<td class='title'>".$this->l('Name of the friends')."</td>
									<td>".$this->l('Number of orders')."</td>
									<td class='price'>".$this->l('Total orders')."</td>
									<td class='price'>".$this->l('Total rewards')."</td>
								</tr>";
			foreach($stats['sponsored'][$id_sponsor] as $sponsored) {
				$channel = $this->l('Email invitation');
				if ($sponsored['channel']==2)
					$channel = $this->l('Sponsorship link');
				else if ($sponsored['channel']==3)
					$channel = $this->l('Facebook');
				else if ($sponsored['channel']==4)
					$channel = $this->l('Twitter');
				else if ($sponsored['channel']==5)
					$channel = $this->l('Google +1');
				$result .= "
								<tr>
									<td>".$sponsored['level_sponsorship']."</td>
									<td>".$channel."</td>
									<td class='title'>".$sponsored['lastname']." ".$sponsored['firstname']."</td>
									<td>".$sponsored['nb_orders']."</td>
									<td class='price'>".Tools::displayPrice($sponsored['total_orders'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
									<td class='price'>".Tools::displayPrice($sponsored['total_rewards'], (int)Configuration::get('PS_CURRENCY_DEFAULT'))."</td>
								</tr>";
			}
			$result .= "
							</table>
						</td>
					</tr>";
		}
		return $result;
	}

	// Return the reward calculated from a price in a specific currency, and converted in the 2nd currency
	protected function getNbCreditsByPrice($price, $idCurrencyFrom, $idCurrencyTo = NULL, $extraParams = array())
	{
		if (!isset($idCurrencyTo))
			$idCurrencyTo = $idCurrencyFrom;

		// for a fixed reward, special offers are always taken in account
		if (Configuration::get('PS_CURRENCY_DEFAULT') != $idCurrencyFrom)
		{
			// convert from customer's currency to default currency
			$price = (float)Tools::convertPrice($price, Currency::getCurrency($idCurrencyFrom), false);
		}
		if ($price > 0) {
			if ((int)$extraParams['type'] == 1) {
				$credits = (float)number_format($extraParams['value'], 2, '.', '');
				// convert from customer's currency to default currency
				$credits = round(Tools::convertPrice($credits, Currency::getCurrency($idCurrencyFrom), false), 2);
			} else {
				$credits = (float)number_format($price, 2, '.', '') * (float)$extraParams['value'] / 100;
			}
			return round(Tools::convertPrice($credits, Currency::getCurrency($idCurrencyTo)), 2);
		} else {
			return 0;
		}
	}

	// check if a sponsorship link has been clicked
	private function _checkSponsorshipLink()
	{
		if (Tools::getValue('s')) {
			$sponsor = null;
			$sponsorship = new RewardsSponsorshipModel(RewardsSponsorshipModel::decodeSponsorshipMailLink(Tools::getValue('s')));
			if (Validate::isLoadedObject($sponsorship))
				$sponsor = new Customer($sponsorship->id_sponsor);
			else
				$sponsor = new Customer(RewardsSponsorshipModel::decodeSponsorshipLink(Tools::getValue('s')));
			if (Validate::isLoadedObject($sponsor) && RewardsSponsorshipModel::isCustomerAllowed($sponsor)) {
				$this->context->cookie->rewards_sponsor_id = $sponsor->id;
				$this->context->cookie->rewards_sponsor_channel = (Tools::getValue('c') && is_numeric(Tools::getValue('c'))) ? Tools::getValue('c') : 2;
				$this->context->cookie->rewards_sponsorship_id = Validate::isLoadedObject($sponsorship) ? $sponsorship->id : '';
			}

			if (Configuration::get('RSPONSORSHIP_REDIRECT') == 'form' && basename($_SERVER['PHP_SELF']) != 'authentication.php' && basename($_SERVER['PHP_SELF']) != 'order-confirmation.php')
				Tools::redirect('index.php?controller=authentication&create_account=1');
		}
	}

	// add css and js for the sponsorship form and popup
	public function hookDisplayHeader()
	{
		if (RewardsSponsorshipModel::isCustomerAllowed($this->context->customer)) {
			$this->context->controller->addjqueryPlugin('fancybox');
			if (version_compare(_PS_VERSION_, '1.5.5.0', '<'))
				$this->context->controller->addJS($this->instance->getPath().'js/sponsorship-before-1550.js');
			else
				$this->context->controller->addJS($this->instance->getPath().'js/sponsorship.js');
		}
		$ogimage = MyConf::get('RSPONSORSHIP_SHARE_IMAGE_URL', null, (int)MyConf::getIdTemplate('sponsorship', $this->context->customer->id));
		if (isset($this->context->customer) && $ogimage && ($this->context->controller instanceof IndexController || $this->context->controller instanceof AuthController)) {
			$this->context->smarty->assign(array('ogimage' => $ogimage));
			return $this->instance->display($this->instance->path, 'header.tpl');
		}
	}


	// Hook call on every page. Check if a sponsorId is sent.
	// If yes, in case of subscription, sponsor is automatically recognized
	public function hookDisplayTop($params)
	{
		// check if the sponsor in cookie is still authorized to sponsor
		if (!empty($this->context->cookie->rewards_sponsor_id)) {
			$sponsor = new Customer($this->context->cookie->rewards_sponsor_id);
			if (!RewardsSponsorshipModel::isCustomerAllowed($sponsor)) {
				unset($this->context->cookie->rewards_sponsor_id);
				unset($this->context->cookie->rewards_sponsorship_id);
			}
		}

		$this->_checkSponsorshipLink();
		return false;
	}

	// Open sponsorship popup
	public function hookDisplayFooter($params)
	{
		// if popup is activated and cookie time is over
		$key = 'rewards_sponsor' . Configuration::get('RSPONSORSHIP_POPUP_KEY');
		if (Configuration::get('RSPONSORSHIP_POPUP') && (!$this->context->cookie->$key || ($this->context->cookie->$key + (Configuration::get('RSPONSORSHIP_POPUP_DELAY')*86400)) < time())
			&& strpos($_SERVER['REQUEST_URI'], "/sponsorship.php") === false)
			return $this->_popup(true);
		return false;
	}

	public function hookDisplayCustomerAccount($params)
	{
		if (RewardsSponsorshipModel::isCustomerAllowed($this->context->customer))
			return $this->instance->display($this->instance->path, 'customer-account-sponsorship.tpl');
	}

	public function hookDisplayMyAccountBlock($params)
	{
		if (RewardsSponsorshipModel::isCustomerAllowed($this->context->customer))
			return $this->instance->display($this->instance->path, 'my-account-sponsorship.tpl');
	}

	public function hookDisplayMyAccountBlockFooter($params)
	{
		return $this->hookDisplayMyAccountBlock($params);
	}

	// Add an additional input on bottom for the sponsor's email address
	public function hookDisplayCustomerAccountForm($params)
	{
		$this->_checkSponsorshipLink();

		if (!empty($this->context->cookie->rewards_sponsorship_id)) {
			// sponsorship from mail link
			$sponsorship = new RewardsSponsorshipModel($this->context->cookie->rewards_sponsorship_id);
			if (Validate::isLoadedObject($sponsorship)) {
				// hack for display sponsorship information in form
				$_POST['customer_firstname'] = $sponsorship->firstname;
				$_POST['firstname'] = $sponsorship->firstname;
				$_POST['customer_lastname'] = $sponsorship->lastname;
				$_POST['lastname'] = $sponsorship->lastname;
				$_POST['email'] = $sponsorship->email;
				$_POST['email_create'] = $sponsorship->email;
				$_POST['sponsorship_invisible'] = '1';
			}
		} else if (!empty($this->context->cookie->rewards_sponsor_id)) {
			// sponsorship from other sponsorship link
			$sponsor = new Customer($this->context->cookie->rewards_sponsor_id);
			if (Validate::isLoadedObject($sponsor) && RewardsSponsorshipModel::isCustomerAllowed($sponsor)) {
				$_POST['sponsorship_invisible'] = '1';
			}
		}
		return $this->instance->display($this->instance->path, 'authentication.tpl');
	}

	// Create the sponsorship, and a discount for the customer
	public function hookActionCustomerAccountAdd($params)
	{
		$newCustomer = $params['newCustomer'];
		if (!Validate::isLoadedObject($newCustomer))
			return false;

		$sponsor = null;
		if (!empty($this->context->cookie->rewards_sponsor_id)) {
			// sponsor already in the cookie
			$sponsor = new Customer($this->context->cookie->rewards_sponsor_id);
		} else {
			// sponsor email entered on the form
			$postVars = $params['_POST'];
			if (empty($postVars) || !isset($postVars['sponsorship']) || empty($postVars['sponsorship']))
				return false;
			$sponsor = new Customer();
			if (Validate::isEmail(trim($postVars['sponsorship'])))
				$sponsor=$sponsor->getByEmail(trim($postVars['sponsorship']));
			else
				$sponsor = new Customer(RewardsSponsorshipModel::decodeSponsorshipLink(trim($postVars['sponsorship'])));
		}
		return $this->_createSponsorship($sponsor, $newCustomer);
	}

	private function _createSponsorship($sponsor, $customer, $force=false, $voucher=true, $currency=0) {
		if (Validate::isLoadedObject($sponsor) && RewardsSponsorshipModel::isCustomerAllowed($sponsor) && $sponsor->email != $customer->email)
		{
			$id_template = (int)MyConf::getIdTemplate('sponsorship', $sponsor->id);
			$stats = $sponsor->getStats();
			$nbOrdersSponsor = (int)($stats['nb_orders']);
			if ($nbOrdersSponsor >= (int)(MyConf::get('RSPONSORSHIP_ORDER_QUANTITY_S', null, $id_template)) || $force) {
				if ($id_sponsorship = RewardsSponsorshipModel::isMailSponsorised($sponsor->id, $customer->email, true)) {
					$sponsorship = new RewardsSponsorshipModel((int)$id_sponsorship);
					// guest account turning into real account
					// This should be improved, because both customer should be considerated as sponsored, so probably create a new sponsorship !
					if (!empty($sponsorship->id_customer))
						return false;
					$sponsorship->id_customer = $customer->id;
					$sponsorship->firstname = $customer->firstname;
					$sponsorship->lastname = $customer->lastname;
				} else {
					// if this customer has been sponsored by another sponsor, it is deleted
					RewardsSponsorshipModel::deleteSponsoredByOther($customer->email);
					$sponsorship = new RewardsSponsorshipModel();
					$sponsorship->id_sponsor = $sponsor->id;
					$sponsorship->email = $customer->email;
					$sponsorship->id_customer = $customer->id;
					$sponsorship->firstname = $customer->firstname;
					$sponsorship->lastname = $customer->lastname;
					if (!empty($this->context->cookie->rewards_sponsor_channel))
						$sponsorship->channel = $this->context->cookie->rewards_sponsor_channel;
					else
						$sponsorship->channel = 2;
				}
				if ((int)MyConf::get('RSPONSORSHIP_DURATION', null, $id_template))
					$sponsorship->date_end = date('Y-m-d H:i:s', time() + (int)MyConf::get('RSPONSORSHIP_DURATION', null, $id_template)*24*60*60);
				$sponsorship->save();

				// send notifications
				if (Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION') || Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION_S')) {
					$data = array(
						'{sponsored_firstname}' => $customer->firstname,
						'{sponsored_lastname}' => $customer->lastname,
						'{sponsored_email}' => $customer->email,
						'{sponsor_firstname}' => $sponsor->firstname,
						'{sponsor_lastname}' => $sponsor->lastname,
						'{sponsor_email}' => $sponsor->email);
					if (Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION'))
						$this->instance->sendMail((int)Configuration::get('PS_LANG_DEFAULT'), 'sponsorship-registration-admin', $this->l('Sponsorship'), $data, Configuration::get('PS_SHOP_EMAIL'), NULL);
					if (Configuration::get('RSPONSORSHIP_MAIL_REGISTRATION_S')) {
						$lang = (int)Configuration::get('PS_LANG_DEFAULT');
						if (version_compare(_PS_VERSION_, '1.5.4.0', '>='))
							$lang = (int)$sponsor->id_lang;
						$this->instance->sendMail($lang, 'sponsorship-registration', $this->l('Sponsorship', $lang), $data, $sponsor->email, $sponsor->firstname.' '.$sponsor->lastname);
					}
				}

				if (MyConf::get('RSPONSORSHIP_DISCOUNT_GC', null, $id_template) && $voucher) {
					// when called from back-end, currency is provided
					if ($currency == 0)
						$currency = (int)$this->context->currency->id;
					if ($sponsorship->registerDiscount($currency))
					{
						$cartRule = new CartRule((int)$sponsorship->id_cart_rule);
						if (Validate::isLoadedObject($cartRule))
						{
							$data = array(
								'{firstname}' => $customer->firstname,
								'{lastname}' => $customer->lastname,
								'{nb_discount}' => $cartRule->quantity_per_user,
								'{voucher_num}' => $cartRule->code);
							if ((float)$cartRule->reduction_amount > 0 || (float)$cartRule->reduction_percent > 0) {
								$template = 'sponsorship-voucher';
								if ((float)$cartRule->reduction_percent > 0)
									$data['{voucher_amount}'] = $this->instance->getDiscountReadyForDisplay(1, $cartRule->free_shipping, $cartRule->reduction_percent);
								else
									$data['{voucher_amount}'] = $this->instance->getDiscountReadyForDisplay(2, $cartRule->free_shipping, $cartRule->reduction_amount, $currency);
							} else
								$template = 'sponsorship-voucher-freeshipping';
							$this->instance->sendMail((int)$this->context->language->id, $template, $this->l('Congratulations!'), $data, $customer->email, $customer->firstname.' '.$customer->lastname);
						}
					}
				}
				return true;
			}
		}
		return false;
	}

	// Create all sponsorship rewards for an order
	private function _createAllRewards($order, $customer)
	{
		$limit = count(explode(',', Configuration::get('RSPONSORSHIP_REWARD_TYPE_S'))) - 1;

		// All sponsors who should get a reward
		$sponsorships = RewardsSponsorshipModel::getSponsorshipAscendants($customer->id);

		// loop on sponsor, starting from the nearest
		$sponsorsMailHtml = $sponsorsMailTxt = '';
		$bMail = false;
		$level = 0;
		foreach($sponsorships as $sponsorship) {
			$id_template = (int)MyConf::getIdTemplate('sponsorship', $sponsorship['id_sponsor']);
			$this->_initConf($id_template);
			$price = (float)RewardsModel::getOrderPriceForReward($order, true, MyConf::get('RSPONSORSHIP_DISCOUNTED_ALLOWED', null, $id_template));
			if ($price > 0) {
				$reward = new RewardsModel();
				$reward->plugin = $this->name;
				$reward->id_customer = (int)$sponsorship['id_sponsor'];
				$reward->id_order = (int)$order->id;
				$reward->id_reward_state = RewardsStateModel::getDefaultId();

				// try to get settings for the level, if not found last will be used
				$indice = $level;
				if (!isset($this->_configuration['reward_type'][$level]))
					$indice = $limit;
				$extraParams = array();
				$extraParams['type'] = (int)$this->_configuration['reward_type'][$indice];
				$extraParams['value'] = (float)($extraParams['type'] == 1 ? $this->_configuration['reward_value'][$indice][$order->id_currency] : $this->_configuration['reward_percentage'][$indice]);
				$reward->credits = (float)$this->getNbCreditsByPrice($price, $order->id_currency, Configuration::get('PS_CURRENCY_DEFAULT'), $extraParams);
				// if sponsor's reward=0 (only special offers, voucher used, or % set to 0 in BO)
				if ($reward->credits == 0)
					continue;
				if ($reward->save()) {
					RewardsSponsorshipModel::saveDetails($reward->id, (int)$sponsorship['id_sponsorship'], $level+1);
					$bMail = true;

					// send customer's notifications
					if (Configuration::get('RSPONSORSHIP_MAIL_ORDER_S') || Configuration::get('RSPONSORSHIP_MAIL_ORDER')) {
						$sponsor = new Customer((int) $sponsorship['id_sponsor']);
						$rewardAmount = Tools::displayPrice(round(Tools::convertPrice((float)$reward->credits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency);
						$data = array(
							'{sponsored_firstname}' => $customer->firstname,
							'{sponsored_lastname}' => $customer->lastname,
							'{sponsored_email}' => $customer->email,
							'{sponsor_firstname}' => $sponsor->firstname,
							'{sponsor_lastname}' => $sponsor->lastname,
							'{sponsor_email}' => $sponsor->email,
							'{sponsor_reward}' => $rewardAmount,
							'{link_rewards}' => $this->context->link->getModuleLink('allinone_rewards', 'rewards', array(), true));
						$template = 'sponsorship-order';
						if ($level > 0)
							$template = 'sponsorship-order-levels';
						if (Configuration::get('RSPONSORSHIP_MAIL_ORDER_S')) {
							$lang = (int)Configuration::get('PS_LANG_DEFAULT');
							if (version_compare(_PS_VERSION_, '1.5.4.0', '>='))
								$lang = (int)$sponsor->id_lang;
							$this->instance->sendMail($lang, $template, $this->l('Sponsorship', $lang), $data, $sponsor->email, $sponsor->firstname.' '.$sponsor->lastname);
						}

						// text for the admin notification
						$sponsorsMailHtml .= $this->l('Level').' '.($level+1).' : '. $sponsor->firstname.' '.$sponsor->lastname.' ('.$sponsor->email.') '.$this->l('will receive').' '.$rewardAmount.'<br>';
						$sponsorsMailTxt .= $this->l('Level').' '.($level+1).' : '. $sponsor->firstname.' '.$sponsor->lastname.' ('.$sponsor->email.') '.$this->l('will receive').' '.$rewardAmount.'\r\n';
					}
				}
				$level++;
			}
		}
		// admin notification
		if ($bMail && Configuration::get('RSPONSORSHIP_MAIL_ORDER')) {
			$data = array(
				'{sponsored_firstname}' => $customer->firstname,
				'{sponsored_lastname}' => $customer->lastname,
				'{sponsored_email}' => $customer->email,
				'{sponsors_html}' => $sponsorsMailHtml,
				'{sponsors_txt}' => $sponsorsMailTxt);
			$this->instance->sendMail((int)Configuration::get('PS_LANG_DEFAULT'), 'sponsorship-order-admin', $this->l('Sponsorship'), $data, Configuration::get('PS_SHOP_EMAIL'), NULL);
		}
	}

	// give reward to sponsor in "Waiting for validation" state
	// send notification to sponsor to inform them a sponsored placed an order
	public function hookActionValidateOrder($params)
	{
		if (!Validate::isLoadedObject($customer = $params['customer']) || !Validate::isLoadedObject($order = $params['order']))
			die(Tools::displayError('Missing parameters for hookActionValidateOrder'));

		// check if the customer has been sponsored
		$sponsorship = new RewardsSponsorshipModel(RewardsSponsorshipModel::isSponsorised((int)$customer->id, true, true));
		if (!Validate::isLoadedObject($sponsorship))
			return false;

		$id_template = (int)MyConf::getIdTemplate('sponsorship', $sponsorship->id_sponsor);

		// ATTENTION en cas de multilevel, actuellement tous les niveaux suivants dépendent des tests du parrain direct.
		// A passer dans createAllRewards et à tester à chaque niveau si on m'en fait la demande.
		// if sponsor is allowed to get a reward
		if (MyConf::get('RSPONSORSHIP_REWARD_S', null, $id_template)) {
			// if there's reward only on the first order and the sponsor has already beeen rewarded for this customer, do nothing
			if ((int)MyConf::get('RSPONSORSHIP_ON_EVERY_ORDER', null, $id_template) == 0 && RewardsSponsorshipModel::isAlreadyRewarded($sponsorship->id))
				return false;

			// Shipping included in minimum to unlock sponsor's reward ?
			$total_unlock = (float)$order->total_paid;
			if ((int)MyConf::get('RSPONSORSHIP_UNLOCK_SHIPPING', null, $id_template) == 0)
				$total_unlock = (float)$order->total_paid - (float)$order->total_shipping;

			// Check if minimum is reached
			if ($total_unlock >= (float)MyConf::get('RSPONSORSHIP_UNLOCK_GC_' . $order->id_currency, null, $id_template)) {
				$this->_createAllRewards($order, $customer);
				return true;
			}
		}
		return false;
	}

	// modify all rewards for a given order
	private function _updateStatusAllRewards($order, $customer, $orderState)
	{
		// all sponsors who will get a reward
		$sponsorships = RewardsSponsorshipModel::getSponsorshipAscendants($customer->id);

		// loop on sponsor, starting from the nearest
		$level = 0;
		foreach($sponsorships as $sponsorship) {
			// if a reward has been granted for this sponsorship
			if (!Validate::isLoadedObject($reward = new RewardsModel(RewardsSponsorshipModel::getByOrderId($order->id, $sponsorship['id_sponsorship']))))
				return false;

			if ($reward->id_reward_state != RewardsStateModel::getConvertId()) {
				$sponsor = new Customer((int) $sponsorship['id_sponsor']);
				$lang = (int)Configuration::get('PS_LANG_DEFAULT');
				if (version_compare(_PS_VERSION_, '1.5.4.0', '>='))
					$lang = (int)$sponsor->id_lang;

				// if not already converted, validate or cancel the reward
				if (in_array($orderState->id, $this->rewardStateValidation->getValues())) {
					// if reward is locked during return period
					if (Configuration::get('REWARDS_WAIT_RETURN_PERIOD') && Configuration::get('PS_ORDER_RETURN') && (int)Configuration::get('PS_ORDER_RETURN_NB_DAYS') > 0) {
						$reward->id_reward_state = RewardsStateModel::getReturnPeriodId();
						$template = 'sponsorship-return-period';
						$subject = $this->l('Reward validation', $lang);
					} else {
						$reward->id_reward_state = RewardsStateModel::getValidationId();
						$template = 'sponsorship-validation';
						$subject = $this->l('Reward validation', $lang);
					}
				} else {
					$reward->id_reward_state = RewardsStateModel::getCancelId();
					$template = 'sponsorship-cancellation';
					$subject = $this->l('Reward cancellation', $lang);
				}
				$reward->save();

				// send customers's notifications
				if (Configuration::get('RSPONSORSHIP_MAIL_VALIDATION_S')) {
					if ($level > 0)
						$template .= '-levels';

					$data = array(
						'{sponsored_firstname}' => $customer->firstname,
						'{sponsored_lastname}' => $customer->lastname,
						'{sponsored_email}' => $customer->email,
						'{sponsor_firstname}' => $sponsor->firstname,
						'{sponsor_lastname}' => $sponsor->lastname,
						'{sponsor_email}' => $sponsor->email,
						'{sponsor_reward}' => Tools::displayPrice(round(Tools::convertPrice((float)$reward->credits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency),
						'{link_rewards}' => $this->context->link->getModuleLink('allinone_rewards', 'rewards', array(), true));
					if ($reward->id_reward_state = RewardsStateModel::getReturnPeriodId()) {
						$data['{reward_unlock_date}'] = Tools::displayDate($reward->getUnlockDate(), null, true);
					}
					$this->instance->sendMail($lang, $template, $subject, $data, $sponsor->email, $sponsor->firstname.' '.$sponsor->lastname);
				}
			}
			$level++;
		}
	}

	// Validate or cancel the sponsor's rewards
	// Send mail to notify about validation or cancellation of the reward
	public function hookActionOrderStatusUpdate($params)
	{
		$this->instanceDefaultStates();

		if (!Validate::isLoadedObject($orderState = $params['newOrderStatus']) || !Validate::isLoadedObject($order = new Order((int)$params['id_order'])) || !Validate::isLoadedObject($customer = new Customer((int)$order->id_customer)))
			die (Tools::displayError('Missing parameters for hookActionOrderStatusUpdate'));

		// check if a sponsorship is in progress
		$sponsorship = new RewardsSponsorshipModel(RewardsSponsorshipModel::isSponsorised((int)$customer->id, true));
		if (!Validate::isLoadedObject($sponsorship))
			return false;

		// if sponsor is allowed to get a reward
		if (MyConf::get('RSPONSORSHIP_REWARD_S', null, (int)MyConf::getIdTemplate('sponsorship', $sponsorship->id_sponsor))) {
			// if status change to validation status or cancellation status for the reward
			if ($orderState->id != $order->getCurrentState() && (in_array($orderState->id, $this->rewardStateValidation->getValues()) || in_array($orderState->id, $this->rewardStateCancel->getValues()))) {
				$this->_updateStatusAllRewards($order, $customer, $orderState);
				return true;
			}
		}
		return false;
	}

	// calulate all rewards after a product has been canceled
	private function _cancelProductAllRewards($order, $customer)
	{
		$limit = count(explode(',', Configuration::get('RSPONSORSHIP_REWARD_TYPE_S'))) - 1;

		// all sponsors who will get a reward
		$sponsorships = RewardsSponsorshipModel::getSponsorshipAscendants($customer->id);

		// loop on sponsor, starting from the nearest
		$level = 0;
		foreach($sponsorships as $sponsorship) {
			$id_template = (int)MyConf::getIdTemplate('sponsorship', $sponsorship['id_sponsor']);
			$this->_initConf($id_template);
			$price = (float)RewardsModel::getOrderPriceForReward($order, true, MyConf::get('RSPONSORSHIP_DISCOUNTED_ALLOWED', null, $id_template));

			// if a reward has been granted for this sponsorship
			if (!Validate::isLoadedObject($reward = new RewardsModel(RewardsSponsorshipModel::getByOrderId($order->id, $sponsorship['id_sponsorship']))))
				return false;

			if ($reward->id_reward_state != RewardsStateModel::getConvertId()) {
				$oldCredits = $reward->credits;

				// try to get settings for the level, if not found last will be used
				$indice = $level;
				if (!isset($this->_configuration['reward_type'][$level]))
					$indice = $limit;
				$extraParams = array();
				$extraParams['type'] = (int)$this->_configuration['reward_type'][$indice];
				$extraParams['value'] = (float)($extraParams['type'] == 1 ? $this->_configuration['reward_value'][$indice][$order->id_currency] : $this->_configuration['reward_percentage'][$indice]);
				$reward->credits = (float)$this->getNbCreditsByPrice($price, $order->id_currency, Configuration::get('PS_CURRENCY_DEFAULT'), $extraParams);

				// test if something has changed, because return product doesn't change the price of the cart
				if ((float)$oldCredits != (float)$reward->credits) {
					if (!MyConf::get('RSPONSORSHIP_DISCOUNTED_ALLOWED', null, $id_template) && (float)$reward->credits == 0)
						$reward->id_reward_state = RewardsStateModel::getDiscountedId();
					else if ((float)$reward->credits == 0)
						$reward->id_reward_state = RewardsStateModel::getCancelId();
					$reward->save();
					// TODO : historize changes

					// send notification
					if (Configuration::get('RSPONSORSHIP_MAIL_CANCELPROD_S')) {
						$sponsor = new Customer((int) $sponsorship['id_sponsor']);
						$data = array(
							'{sponsored_firstname}' => $customer->firstname,
							'{sponsored_lastname}' => $customer->lastname,
							'{sponsored_email}' => $customer->email,
							'{sponsor_firstname}' => $sponsor->firstname,
							'{sponsor_lastname}' => $sponsor->lastname,
							'{sponsor_email}' => $sponsor->email,
							'{old_sponsor_reward}' => Tools::displayPrice(round(Tools::convertPrice((float)$oldCredits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency),
							'{new_sponsor_reward}' => Tools::displayPrice(round(Tools::convertPrice((float)$reward->credits, Currency::getCurrency((int)$order->id_currency)), 2), (int)$order->id_currency));
						$template = 'sponsorship-cancel-product';
						if ($level > 0)
							$template = 'sponsorship-cancel-product-levels';

						$lang = (int)Configuration::get('PS_LANG_DEFAULT');
						if (version_compare(_PS_VERSION_, '1.5.4.0', '>='))
							$lang = (int)$sponsor->id_lang;
						$this->instance->sendMail($lang, $template, $this->l('Reward modification', $lang), $data, $sponsor->email, $sponsor->firstname.' '.$sponsor->lastname);
					}
				}
			}
			$level++;
		}
	}

	// calculate reward when a product is canceled
	public function hookActionProductCancel($params)
	{
		if (!Validate::isLoadedObject($order = $params['order']) || !Validate::isLoadedObject($customer = new Customer((int)$order->id_customer)))
			return false;

		// if the sponsorship exists
		$sponsorship = new RewardsSponsorshipModel(RewardsSponsorshipModel::isSponsorised((int)$customer->id, true));
		if (!Validate::isLoadedObject($sponsorship))
			return false;

		// if sponsor is allowed to get a reward
		if (MyConf::get('RSPONSORSHIP_REWARD_S', null, (int)MyConf::getIdTemplate('sponsorship', $sponsorship->id_sponsor))) {
			$this->_cancelProductAllRewards($order, $customer);
		}
		return true;
	}

	// display the sponsorship form
	public function hookDisplayOrderConfirmation($params)
	{
		if (Configuration::get('RSPONSORSHIP_AFTER_ORDER'))
			return $this->_popup();
		return false;
	}

	// open the sponsorship popup
	private function _popup($scheduled=false)
	{
		if (!$this->_popup && $this->context->customer->isLogged() && Validate::isLoadedObject($this->context->customer) && RewardsSponsorshipModel::isCustomerAllowed($this->context->customer)) {
			$stats = $this->context->customer->getStats();
			$nbOrdersCustomer = (int)$stats['nb_orders'];
			// if nb orders required to sponsor is reached
			if ($nbOrdersCustomer >= (int)MyConf::get('RSPONSORSHIP_ORDER_QUANTITY_S', null, (int)MyConf::getIdTemplate('sponsorship', $this->context->customer->id))) {
				$this->_popup = true;
				$key = 'rewards_sponsor' . Configuration::get('RSPONSORSHIP_POPUP_KEY');
				$this->context->cookie->$key = time();
				$this->context->smarty->assign(array('scheduled' => $scheduled));
				return $this->instance->display($this->instance->path, 'popup.tpl');
			}
		}
		return false;
	}

	// Display sponsorship information in the order page
	public function hookDisplayAdminOrder($params)
	{
		$smarty_values = array(
			'rewards' => RewardsSponsorshipModel::getAllSponsorshipRewardsByOrderId($params['id_order'])
		);
		$this->context->smarty->assign($smarty_values);
		return $this->instance->display($this->instance->path, 'adminorders-sponsorship.tpl');
	}

	// Display sponsorship information in the customer page
	public function hookDisplayAdminCustomers($params)
	{
		$customer = new Customer((int)$params['id_customer']);
		if (!Validate::isLoadedObject($customer))
			die (Tools::displayError('Incorrect object Customer.'));

		$msg = $this->postProcess($params);

		$stats = RewardsSponsorshipModel::getAdminStatistics((int)$customer->id);
		$customerStats = $stats['sponsors'][(int)$customer->id];
		$friends = $stats['sponsored'][(int)$customer->id];

		if ($id_sponsorship = RewardsSponsorshipModel::isSponsorised((int)$customer->id, true)) {
			$sponsorship = new RewardsSponsorshipModel((int)$id_sponsorship);
			$sponsor = new Customer((int)$sponsorship->id_sponsor);
		}

		$smarty_values = array(
			'msg' => $msg,
			'sponsor' => isset($sponsor) ? $sponsor : null,
			'sponsorship_code' => RewardsSponsorshipModel::getSponsorshipLink($customer),
			'sponsorship_allowed' => RewardsSponsorshipModel::isCustomerAllowed($customer),
			'available_sponsors' => RewardsSponsorshipModel::getAvailableSponsors((int)$params['id_customer']),
			'discount_gc' => (int)Configuration::get('RSPONSORSHIP_DISCOUNT_GC'),
			'currencies' => Currency::getCurrencies(),
			'default_currency' => Configuration::get('PS_CURRENCY_DEFAULT'),
			'friends' => $friends,
			'stats' => $customerStats
		);
		$this->context->smarty->assign($smarty_values);
		return $this->instance->display($this->instance->path, 'admincustomer-sponsorship.tpl');
	}

	public function hookActionAdminControllerSetMedia($params)
	{
    	// add necessary javascript to customers back office
		if ($this->context->controller->controller_name == 'AdminCustomers') {
			$this->context->controller->addjQueryPlugin('date');

			$this->context->controller->addJqueryUI(array(
				'ui.core',
				'ui.effect',
				'ui.widget',
				'ui.accordion',
				'ui.slider',
				'ui.datepicker'
			));

			$this->context->controller->addJS(_PS_JS_DIR_.'jquery/plugins/timepicker/jquery-ui-timepicker-addon.js');
			$this->context->controller->addCSS(_PS_JS_DIR_.'jquery/plugins/timepicker/jquery-ui-timepicker-addon.css');
		}
	}
}