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

class RewardsCorePlugin extends RewardsGenericPlugin
{
	public $name = 'core';

	public function install()
	{
		// hooks
		if (!$this->registerHook('displayHeader') || !$this->registerHook('displayAdminCustomers') || !$this->registerHook('displayCustomerAccount')
			|| !$this->registerHook('displayMyAccountBlock') || !$this->registerHook('displayMyAccountBlockFooter'))
			return false;

		// conf
		$idEn = Language::getIdByIso('en');
		$desc = array();
		$rewards_payment_txt = array();
		foreach (Language::getLanguages() AS $language) {
			$tmp = $this->l('Loyalty reward', (int)$language['id_lang']);
			$desc[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('Loyalty reward', $idEn);
			$tmp = $this->l('rewards_payment_txt', (int)$language['id_lang']);
			$rewards_payment_txt[(int)$language['id_lang']] = isset($tmp) && !empty($tmp) ? $tmp : $this->l('rewards_payment_txt', $idEn);
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

		if (!Configuration::updateValue('REWARDS_VERSION', $this->version)
		|| !Configuration::updateValue('REWARDS_PAYMENT', 0)
		|| !Configuration::updateValue('REWARDS_VOUCHER', 1)
		|| !Configuration::updateValue('REWARDS_VOUCHER_GROUPS', $groups_config)
		|| !Configuration::updateValue('REWARDS_PAYMENT_INVOICE',  1)
		|| !Configuration::updateValue('REWARDS_PAYMENT_RATIO',  100)
		|| !Configuration::updateValue('REWARDS_PAYMENT_TXT', $rewards_payment_txt)
		|| !Configuration::updateValue('REWARDS_MINIMAL', 0)
		|| !Configuration::updateValue('REWARDS_MINIMAL_TAX', 0)
		|| !Configuration::updateValue('REWARDS_MINIMAL_SHIPPING', 0)
		|| !Configuration::updateValue('REWARDS_VOUCHER_DETAILS', $desc)
		|| !Configuration::updateValue('REWARDS_VOUCHER_CATEGORY', $category_config)
		|| !Configuration::updateValue('REWARDS_VOUCHER_CUMUL_S', 0)
		|| !Configuration::updateValue('REWARDS_VOUCHER_PREFIX', 'FID')
		|| !Configuration::updateValue('REWARDS_VOUCHER_DURATION', 365)
		|| !Configuration::updateValue('REWARDS_VOUCHER_BEHAVIOR', 0)
		|| !Configuration::updateValue('REWARDS_DISPLAY_CART', 1)
		|| !Configuration::updateValue('REWARDS_WAIT_RETURN_PERIOD', 1)
		|| !Configuration::updateValue('REWARDS_USE_CRON', 0)
		|| !Configuration::updateValue('REWARDS_DURATION', 0)
		|| !Configuration::updateValue('REWARDS_CRON_SECURE_KEY', Tools::strtoupper(Tools::passwdGen(16)))
		|| !Configuration::updateValue('REWARDS_REMINDER', 0)
		|| !Configuration::updateValue('REWARDS_REMINDER_MINIMUM', 5)
		|| !Configuration::updateValue('REWARDS_REMINDER_FREQUENCY', 30)
		|| !Configuration::updateValue('REWARDS_INITIAL_CONDITIONS', 0)
		|| !Configuration::updateGlobalValue('PS_CART_RULE_FEATURE_ACTIVE', 1))
			return false;

		foreach (Currency::getCurrencies() as $currency) {
			Configuration::updateValue('REWARDS_PAYMENT_MIN_VALUE_'.(int)($currency['id_currency']), 0);
			Configuration::updateValue('REWARDS_VOUCHER_MIN_VALUE_'.(int)($currency['id_currency']), 0);
		}

		// database
		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards` (
			`id_reward` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_reward_state` INT UNSIGNED NOT NULL DEFAULT 1,
			`id_customer` INT UNSIGNED NOT NULL,
			`id_order` INT UNSIGNED DEFAULT NULL,
			`id_cart_rule` INT UNSIGNED DEFAULT NULL,
			`id_payment` INT UNSIGNED DEFAULT NULL,
			`credits` DECIMAL(20,2) NOT NULL DEFAULT \'0.00\',
			`plugin` VARCHAR(20) NOT NULL DEFAULT \'loyalty\',
			`reason` VARCHAR(80) DEFAULT NULL,
			`date_add` DATETIME NOT NULL,
			`date_upd` DATETIME NOT NULL,
			PRIMARY KEY (`id_reward`),
			INDEX index_rewards_reward_state (`id_reward_state`),
			INDEX index_rewards_order (`id_order`),
			INDEX index_rewards_cart_rule (`id_cart_rule`),
			INDEX index_rewards_customer (`id_customer`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_history` (
			`id_reward_history` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_reward` INT UNSIGNED DEFAULT NULL,
			`id_reward_state` INT UNSIGNED NOT NULL DEFAULT 1,
			`credits` DECIMAL(20,2) NOT NULL DEFAULT \'0.00\',
			`date_add` DATETIME NOT NULL,
			PRIMARY KEY (`id_reward_history`),
			INDEX `index_rewards_history_reward` (`id_reward`),
			INDEX `index_rewards_history_reward_state` (`id_reward_state`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_state` (
			`id_reward_state` INT UNSIGNED NOT NULL,
			`id_order_state` TEXT,
			PRIMARY KEY (`id_reward_state`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_state_lang` (
			`id_reward_state` INT UNSIGNED NOT NULL,
			`id_lang` INT UNSIGNED NOT NULL,
			`name` varchar(64) NOT NULL,
			UNIQUE KEY `index_unique_rewards_state_lang` (`id_reward_state`,`id_lang`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_payment` (
			`id_payment` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`credits` DECIMAL(20,2) NOT NULL DEFAULT \'0.00\',
			`detail` TEXT,
			`invoice` VARCHAR(100) DEFAULT NULL,
			`paid` TINYINT(1) NOT NULL DEFAULT \'0\',
			`date_add` DATETIME NOT NULL,
			`date_upd` DATETIME NOT NULL,
			PRIMARY KEY (`id_payment`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_account` (
			`id_customer` INT UNSIGNED NOT NULL,
			`date_last_remind` DATETIME DEFAULT NULL,
			`date_add` DATETIME NOT NULL,
			`date_upd` DATETIME NOT NULL,
			PRIMARY KEY (`id_customer`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_template` (
			`id_template` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`name` varchar(100) NOT NULL,
			`plugin` VARCHAR(20) NOT NULL,
			PRIMARY KEY (`id_template`),
			UNIQUE KEY `index_unique_rewards_template` (`name`, `plugin`),
  			INDEX `index_rewards_template_plugin` (`plugin`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_template_config` (
			`id_template_config` INT UNSIGNED NOT NULL AUTO_INCREMENT,
			`id_template` INT UNSIGNED NOT NULL,
			`name` varchar(254) NOT NULL,
			`value` TEXT,
			PRIMARY KEY (`id_template_config`),
			UNIQUE KEY `index_unique_rewards_template_config` (`id_template`, `name`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_template_config_lang` (
			`id_template_config` INT UNSIGNED NOT NULL,
			`id_lang` INT UNSIGNED NOT NULL,
			`value` TEXT,
			PRIMARY KEY (`id_template_config`, `id_lang`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		Db::getInstance()->Execute('
		CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'rewards_template_customer` (
			`id_template` INT UNSIGNED NOT NULL,
			`id_customer` INT UNSIGNED NOT NULL,
			PRIMARY KEY (`id_template`, `id_customer`)
		) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8 ;');

		if (!RewardsStateModel::insertDefaultData())
			return false;

		return true;
	}

	public function uninstall()
	{
		/*Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards`;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_state`;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_state_lang`;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_history`;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_payment`;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_account`;');
		Db::getInstance()->Execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'rewards_template`;');*/
		Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'hook` WHERE `name` like \'rewards%\'');

		Db::getInstance()->Execute('
			DELETE FROM `'._DB_PREFIX_.'configuration_lang`
			WHERE `id_configuration` IN (SELECT `id_configuration` from `'._DB_PREFIX_.'configuration` WHERE `name` like \'REWARDS_%\')');

		Db::getInstance()->Execute('
			DELETE FROM `'._DB_PREFIX_.'configuration`
			WHERE `name` like \'REWARDS_%\'');

		return true;
	}

	public function isActive()
	{
		return true;
	}

	public function getTitle()
	{
		return $this->l('Rewards account');
	}

	public function getDetails($reward, $admin)
	{
		return false;
	}

	public function postProcess($params=null)
	{
		$this->instanceDefaultStates();

		// on initialise le template à chaque chargement
		$this->initTemplate();

		if (Tools::isSubmit('submitReward')) {
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				if (empty($this->id_template)) {
					Configuration::updateValue('REWARDS_USE_CRON', (int)Tools::getValue('rewards_use_cron'));
					Configuration::updateValue('REWARDS_PAYMENT_GROUPS', implode(",", (array)Tools::getValue('rewards_payment_groups')));
					Configuration::updateValue('REWARDS_VOUCHER_GROUPS', implode(",", (array)Tools::getValue('rewards_voucher_groups')));
					Configuration::updateValue('REWARDS_WAIT_RETURN_PERIOD', (int)Tools::getValue('wait_order_return'));
					Configuration::updateValue('REWARDS_DURATION', (int)Tools::getValue('rewards_duration'));

					$this->rewardStateValidation->id_order_state = implode(",", Tools::getValue('id_order_state_validation'));
					$this->rewardStateCancel->id_order_state = implode(",", Tools::getValue('id_order_state_cancel'));
					$this->rewardStateValidation->save();
					$this->rewardStateCancel->save();
				}

				MyConf::updateValue('REWARDS_VOUCHER', (int)Tools::getValue('rewards_voucher'), null, $this->id_template);
				MyConf::updateValue('REWARDS_PAYMENT', (int)Tools::getValue('rewards_payment'), null, $this->id_template);
				MyConf::updateValue('REWARDS_PAYMENT_INVOICE',  (int)Tools::getValue('rewards_payment_invoice'), null, $this->id_template);
				MyConf::updateValue('REWARDS_PAYMENT_RATIO', (float)Tools::getValue('rewards_payment_ratio'), null, $this->id_template);
				MyConf::updateValue('REWARDS_VOUCHER_PREFIX', Tools::getValue('voucher_prefix'), null, $this->id_template);
				MyConf::updateValue('REWARDS_VOUCHER_DURATION', (int)Tools::getValue('voucher_duration'), null, $this->id_template);
				MyConf::updateValue('REWARDS_DISPLAY_CART', (int)Tools::getValue('display_cart'), null, $this->id_template);
				MyConf::updateValue('REWARDS_VOUCHER_CUMUL_S', (int)Tools::getValue('cumulative_voucher_s'), null, $this->id_template);
				MyConf::updateValue('REWARDS_MINIMAL', (float)Tools::getValue('minimal'), null, $this->id_template);
				MyConf::updateValue('REWARDS_MINIMAL_TAX', Tools::getValue('include_tax'), null, $this->id_template);
				MyConf::updateValue('REWARDS_MINIMAL_SHIPPING', Tools::getValue('include_shipping'), null, $this->id_template);
				MyConf::updateValue('REWARDS_VOUCHER_BEHAVIOR', (int)Tools::getValue('voucher_behavior'), null, $this->id_template);
				MyConf::updateValue('REWARDS_VOUCHER_CATEGORY', implode(",", Tools::getValue('voucher_category')), null, $this->id_template);

				$arrayVoucherDetails = array();
				$languages = Language::getLanguages();
				foreach ($languages AS $language) {
					$arrayVoucherDetails[(int)($language['id_lang'])] = Tools::getValue('voucher_details_'.(int)($language['id_lang']));
				}
				MyConf::updateValue('REWARDS_VOUCHER_DETAILS', $arrayVoucherDetails, null, $this->id_template);

				foreach (Tools::getValue('rewards_voucher_min_value') as $id_currency => $value)
					MyConf::updateValue('REWARDS_VOUCHER_MIN_VALUE_'.(int)($id_currency), (float)$value, null, $this->id_template);
				foreach (Tools::getValue('rewards_payment_min_value') as $id_currency => $value)
					MyConf::updateValue('REWARDS_PAYMENT_MIN_VALUE_'.(int)($id_currency), (float)$value, null, $this->id_template);

				$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
			} else
				$this->instance->errors = $this->instance->displayError(implode('<br />', $this->_errors));
		} else if (Tools::isSubmit('submitRewardsNotifications')) {
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				Configuration::updateValue('REWARDS_REMINDER', (int)Tools::getValue('rewards_reminder'));
				Configuration::updateValue('REWARDS_REMINDER_MINIMUM', (float)Tools::getValue('rewards_reminder_minimum'));
				Configuration::updateValue('REWARDS_REMINDER_FREQUENCY', (int)Tools::getValue('rewards_reminder_frequency'));
				$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
			} else
				$this->instance->errors = $this->instance->displayError(implode('<br />', $this->_errors));
		} else if (Tools::isSubmit('submitRewardText')) {
			$this->_postValidation();

			if (!sizeof($this->_errors)) {
				if (empty($this->id_template)) {
					foreach (Language::getLanguages() AS $language) {
						$this->rewardStateDefault->name[(int)($language['id_lang'])] = Tools::getValue('default_reward_state_'.(int)($language['id_lang']));
						$this->rewardStateValidation->name[(int)($language['id_lang'])] = Tools::getValue('validation_reward_state_'.(int)($language['id_lang']));
						$this->rewardStateCancel->name[(int)($language['id_lang'])] = Tools::getValue('cancel_reward_state_'.(int)($language['id_lang']));
						$this->rewardStateConvert->name[(int)($language['id_lang'])] = Tools::getValue('convert_reward_state_'.(int)($language['id_lang']));
						$this->rewardStateDiscounted->name[(int)($language['id_lang'])] = Tools::getValue('discounted_reward_state_'.(int)($language['id_lang']));
						$this->rewardStateReturnPeriod->name[(int)($language['id_lang'])] = Tools::getValue('return_period_reward_state_'.(int)($language['id_lang']));
						$this->rewardStateWaitingPayment->name[(int)($language['id_lang'])] = Tools::getValue('waiting_payment_reward_state_'.(int)($language['id_lang']));
						$this->rewardStatePaid->name[(int)($language['id_lang'])] = Tools::getValue('paid_reward_state_'.(int)($language['id_lang']));
					}
					$this->rewardStateDefault->save();
					$this->rewardStateValidation->save();
					$this->rewardStateCancel->save();
					$this->rewardStateConvert->save();
					$this->rewardStateDiscounted->save();
					$this->rewardStateReturnPeriod->save();
					$this->rewardStateWaitingPayment->save();
					$this->rewardStatePaid->save();
				}

				MyConf::updateValue('REWARDS_GENERAL_TXT', Tools::getValue('rewards_general_txt'), true, $this->id_template);
				MyConf::updateValue('REWARDS_PAYMENT_TXT', Tools::getValue('rewards_payment_txt'), true, $this->id_template);
				$this->instance->confirmation = $this->instance->displayConfirmation($this->l('Settings updated.'));
			} else
				$this->instance->errors = $this->instance->displayError(implode('<br />', $this->_errors));
		} else if (Tools::getValue('accept_payment')) {
			RewardsPaymentModel::acceptPayment((int)Tools::getValue('accept_payment'));
		} else if (Tools::isSubmit('submitRewardReminder')) {
			RewardsAccountModel::sendReminder((int)$params['id_customer']);
		} else if (Tools::isSubmit('submitRewardUpdate')) {
			// manage rewards update
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				$reward = new RewardsModel((int)Tools::getValue('id_reward_to_update'));
				$reward->id_reward_state = (int)Tools::getValue('reward_state_' . Tools::getValue('id_reward_to_update'));
				$reward->credits = (float)Tools::getValue('reward_value_' . Tools::getValue('id_reward_to_update'));
				if ($reward->plugin=="free")
					$reward->reason = Tools::getValue('reward_reason_' . Tools::getValue('id_reward_to_update'));
				$reward->save();
				return $this->instance->displayConfirmation($this->l('The reward has been updated.'));
			} else
				return $this->instance->displayError(implode('<br />', $this->_errors));
		} else if (Tools::isSubmit('submitNewReward')) {
			$this->_postValidation();
			if (!sizeof($this->_errors)) {
				$reward = new RewardsModel();
				$reward->id_reward_state = (int)Tools::getValue('new_reward_state');
				$reward->id_customer = (int)$params['id_customer'];
				$reward->credits = (float)Tools::getValue('new_reward_value');
				$reward->plugin = 'free';
				$reward->reason = Tools::getValue('new_reward_reason');
				$reward->save();
				$_POST['new_reward_value'] = $_POST['new_reward_reason'] = $_POST['new_reward_state'] = null;
				return $this->instance->displayConfirmation($this->l('The new reward has been created.'));
			} else
				return $this->instance->displayError(implode('<br />', $this->_errors));
		}
	}

	private function _postValidation()
	{
		$this->_errors = array();

		$languages = Language::getLanguages();
		if (Tools::isSubmit('submitReward')) {
			$currency = array();
			$currencies = Currency::getCurrencies();
			foreach ($currencies as $value) {
				$currency[$value['id_currency']] = htmlentities($value['name'], ENT_NOQUOTES, 'utf-8');
			}

			if (empty($this->id_template)) {
				$states_valid = Tools::getValue('id_order_state_validation');
				$states_cancel = Tools::getValue('id_order_state_cancel');
				if (!is_array($states_valid) || !sizeof($states_valid))
					$this->_errors[] = $this->l('You must choose the states when reward is awarded');
				if (!is_array($states_cancel) || !sizeof($states_cancel))
					$this->_errors[] = $this->l('You must choose the states when reward is cancelled');
				if (is_array($states_valid) && is_array($states_cancel) && count(array_intersect($states_valid, $states_cancel)) > 0)
					$this->_errors[] = $this->l('You can\'t choose the same state(s) for validation and cancellation');
				if (!is_numeric(Tools::getValue('rewards_duration')) || Tools::getValue('rewards_duration') < 0)
					$this->_errors[] = $this->l('The validity of the rewards is required/invalid.');
				if (Tools::getValue('rewards_payment') && !is_array(Tools::getValue('rewards_payment_groups')))
					$this->_errors[] = $this->l('Please select at least 1 customer group allowed to ask for payment');
				if (Tools::getValue('rewards_voucher') && !is_array(Tools::getValue('rewards_voucher_groups')))
					$this->_errors[] = $this->l('Please select at least 1 customer group allowed to transform rewards into vouchers');
			}
			if (!Tools::getValue('rewards_payment') && !Tools::getValue('rewards_voucher'))
				$this->_errors[] = $this->l('You have to enable payment or/and transformation into vouchers');
			if (!Tools::getValue('rewards_payment_ratio') || !Validate::isUnsignedFloat(Tools::getValue('rewards_payment_ratio')) || (float)Tools::getValue('rewards_payment_ratio') > 100 || (float)Tools::getValue('rewards_payment_ratio') < 1)
				$this->_errors[] = $this->l('The convertion rate must be a number between 1 and 100');
			foreach (Tools::getValue('rewards_payment_min_value') as $id_currency => $value)
				if (!empty($value) && !Validate::isUnsignedFloat($value))
					$this->_errors[] = $this->l('Minimum required in account for payment and the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
			foreach (Tools::getValue('rewards_voucher_min_value') as $id_currency => $value)
				if (!empty($value) && !Validate::isUnsignedFloat($value))
					$this->_errors[] = $this->l('Minimum required in account for transformation and the currency').' '.$currency[$id_currency].' '.$this->l('is invalid.');
			foreach ($languages as $language) {
				if (Tools::getValue('voucher_details_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Voucher description is required for').' '.$language['name'];
			}
			if (Tools::getValue('voucher_prefix') == '' || !Validate::isDiscountName(Tools::getValue('voucher_prefix')))
				$this->_errors[] = $this->l('Prefix for the voucher code is required/invalid.');
			if (!is_numeric(Tools::getValue('voucher_duration')) || Tools::getValue('voucher_duration') <= 0)
				$this->_errors[] = $this->l('The validity of the voucher is required/invalid.');
			if (!is_numeric(Tools::getValue('minimal')) || Tools::getValue('minimal') < 0)
				$this->_errors[] = $this->l('The minimum value is required/invalid.');
			if (Tools::getValue('rewards_voucher') && (!is_array(Tools::getValue('voucher_category')) || !sizeof(Tools::getValue('voucher_category'))))
				$this->_errors[] = $this->l('You must choose at least one category for voucher\'s action');
		} else if (Tools::isSubmit('submitRewardsNotifications') && (int)Tools::getValue('rewards_reminder') == 1) {
			if (Tools::getValue('rewards_reminder_minimum') && !Validate::isUnsignedFloat(Tools::getValue('rewards_reminder_minimum')))
				$this->_errors[] = $this->l('Minimum required in account to receive a mail is required/invalid.');
			if (!is_numeric(Tools::getValue('rewards_reminder_frequency')) || Tools::getValue('rewards_reminder_frequency') <= 0)
				$this->_errors[] = $this->l('The frequency of the emails is required/invalid.');
		} else if (Tools::isSubmit('submitRewardText')) {
			foreach ($languages as $language) {
				if (Tools::getValue('default_reward_state_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Label is required for Initial state in').' '.$language['name'];
				if (Tools::getValue('validation_reward_state_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Label is required for validation state in').' '.$language['name'];
				if (Tools::getValue('cancel_reward_state_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Label is required for cancellation state in').' '.$language['name'];
				if (Tools::getValue('convert_reward_state_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Label is required for converted state in').' '.$language['name'];
				if (Tools::getValue('discounted_reward_state_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Label is required for unavailable state in').' '.$language['name'];
				if (Tools::getValue('return_period_reward_state_'.(int)($language['id_lang'])) == '')
					$this->_errors[] = $this->l('Label is required for Return period not exceeded state in').' '.$language['name'];
			}
		} else if (Tools::isSubmit('submitRewardUpdate') && (int)Tools::getValue('id_reward_to_update') != 0) {
			 if (!Validate::isUnsignedFloat(Tools::getValue('reward_value_' . Tools::getValue('id_reward_to_update'))) || (float)Tools::getValue('reward_value_' . Tools::getValue('id_reward_to_update')) == 0)
			 	$this->_errors[] = $this->l('The value of the reward is required/invalid.');
			 if (Tools::getValue('reward_reason_' . Tools::getValue('id_reward_to_update'))==='')
			 	$this->_errors[] = $this->l('The reason of the reward is required/invalid.');
		} else if (Tools::isSubmit('submitNewReward')) {
			 if (!Validate::isUnsignedFloat(Tools::getValue('new_reward_value')) || (float)Tools::getValue('new_reward_value') == 0)
			 	$this->_errors[] = $this->l('The value of the reward is required/invalid.');
			 if (Tools::getValue('new_reward_reason') == '')
			 	$this->_errors[] = $this->l('The reason of the reward is required/invalid.');
		}
	}

	public function getContent()
	{
		$this->postProcess();

		$categories = $this->instance->getCategories();
		$order_states = OrderState::getOrderStates((int)$this->context->language->id);
		$groups = Group::getGroups($this->context->language->id);
		$rewards_voucher_groups = explode(',', Configuration::get('REWARDS_VOUCHER_GROUPS'));
		$rewards_payment_groups = explode(',', Configuration::get('REWARDS_PAYMENT_GROUPS'));

		$currencies = Currency::getCurrencies();
		$defaultLanguage = (int)Configuration::get('PS_LANG_DEFAULT');
		$languages = Language::getLanguages();

		$html = $this->getTemplateForm($this->id_template, $this->name, $this->l('Rewards account')).'
		<div class="tabs" style="display: none">
			<ul>
				<li><a href="#tabs-'.$this->name.'-1">'.$this->l('Settings').'</a></li>
				<li class="not_templated"><a href="#tabs-'.$this->name.'-2">'.$this->l('Notifications').'</a></li>
				<li><a href="#tabs-'.$this->name.'-3">'.$this->l('Texts').'</a></li>
			</ul>
			<div id="tabs-'.$this->name.'-1">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">
					<input type="hidden" name="plugin" id="plugin" value="'.$this->name.'" />
					<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-1" />
					<fieldset>
						<div class="not_templated">
							<label class="t" style="width: 100% !important"><strong>'.$this->l('Settings for rewards obtained through a command').'</strong></label>
							<div class="clear" style="padding-top: 5px"></div>
							<label class="indent">'.$this->l('Reward is awarded when the order is').'</label>
							<div class="margin-form">
								<select name="id_order_state_validation[]" multiple="multiple" class="multiselect">';
		foreach ($order_states AS $order_state)	{
			$html .= '				<option '.(is_array($this->rewardStateValidation->getValues()) && in_array($order_state['id_order_state'], $this->rewardStateValidation->getValues()) ? 'selected':'').' value="' . $order_state['id_order_state'] . '" style="background-color:' . $order_state['color'] . '"> '.$order_state['name'].'</option>';
		}
		$html .= '
								</select>
							</div>
							<div class="clear"></div>
							<label class="indent">'.$this->l('Reward is cancelled when the order is').'</label>
							<div class="margin-form">
								<select name="id_order_state_cancel[]" multiple="multiple" class="multiselect">';
		foreach ($order_states AS $order_state)	{
			$html .= '			<option '.(is_array($this->rewardStateCancel->getValues()) && in_array($order_state['id_order_state'], $this->rewardStateCancel->getValues()) ? 'selected':'').' value="' . $order_state['id_order_state'] . '" style="background-color:' . $order_state['color'] . '"> '.$order_state['name'].'</option>';
		}
		$html .= '
								</select>
							</div>
							<div class="clear"></div>
							<label class="indent">'.$this->l('Transformation is allowed only when return period is exceeded').'</label>&nbsp;
							<div class="margin-form">
								<label class="t" for="wait_order_return_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
								<input type="radio" id="wait_order_return_on" name="wait_order_return" value="1" '.(Tools::getValue('wait_return_period', Configuration::get('REWARDS_WAIT_RETURN_PERIOD')) ? 'checked="checked"' : '').' /> <label class="t" for="wait_order_return_on">' . $this->l('Yes') . '</label>
								<label class="t" for="wait_order_return_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
								<input type="radio" id="wait_order_return_off" name="wait_order_return" value="0" '.(!Tools::getValue('wait_return_period', Configuration::get('REWARDS_WAIT_RETURN_PERIOD')) ? 'checked="checked"' : '').' /> <label class="t" for="wait_order_return_off">' . $this->l('No') . '</label>
								- '.(Configuration::get('PS_ORDER_RETURN')==1 ? $this->l('Order return period = ') . ' ' . Configuration::get('PS_ORDER_RETURN_NB_DAYS') . ' ' . $this->l('days') : $this->l('Actually, order return is not allowed')).'
							</div>
							<div class="clear"></div>
							<label class="t" style="width: 100% !important; padding-top: 10px; display: block"><strong>'.$this->l('Settings for automatic actions').'</strong></label>
							<div class="clear" style="padding-top: 5px"></div>
							<label class="indent">'.$this->l('How do you want to execute automatic actions (unlock rewards, send reminders)').'</label>
							<div class="margin-form">
								<label class="t" for="rewards_use_cron_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
								<input type="radio" id="rewards_use_cron_on" name="rewards_use_cron" value="1" '.(Tools::getValue('rewards_use_cron', Configuration::get('REWARDS_USE_CRON')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_use_cron_on">' . $this->l('Crontab') . '</label>
								<label class="t" for="rewards_use_cron_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
								<input type="radio" id="rewards_use_cron_off" name="rewards_use_cron" value="0" '.(Tools::getValue('rewards_use_cron', Configuration::get('REWARDS_USE_CRON')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_use_cron_off">' . $this->l('I don\'t know') . '</label> - ' . $this->l('will be called on every page load') . '
							</div>
							<div class="clear optional rewards_use_cron_optional">
								<div class="margin-form" style="width: 95% !important; padding-left: 30px">'.$this->l('Place this URL in crontab or call it manually daily :').' '.Tools::getShopDomain(true, true).__PS_BASE_URI__.'modules/allinone_rewards/cron.php?secure_key='.Configuration::get('REWARDS_CRON_SECURE_KEY').'</div>
							</div>
							<div class="clear"></div>
						</div>
						<label class="t" style="width: 100% !important; padding-top: 10px; display: block"><strong>'.$this->l('Payment settings').'</strong></label>
						<div class="clear" style="padding-top: 5px"></div>
						<label class="indent">'.$this->l('Allow customers to ask for payment (cash)').'</label>
						<div class="margin-form">
							<label class="t" for="rewards_payment_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="rewards_payment_on" name="rewards_payment" value="1" '.(Tools::getValue('rewards_payment', MyConf::get('REWARDS_PAYMENT', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_payment_on">' . $this->l('Yes') . '</label>
							<label class="t" for="rewards_payment_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="rewards_payment_off" name="rewards_payment" value="0" '.(Tools::getValue('rewards_payment', MyConf::get('REWARDS_PAYMENT', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_payment_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear"></div>
						<label class="indent">'.$this->l('Allow customers to transform rewards into vouchers').'</label>
						<div class="margin-form">
							<label class="t" for="rewards_voucher_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="rewards_voucher_on" name="rewards_voucher" value="1" '.(Tools::getValue('rewards_voucher', MyConf::get('REWARDS_VOUCHER', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_voucher_on">' . $this->l('Yes') . '</label>
							<label class="t" for="rewards_voucher_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="rewards_voucher_off" name="rewards_voucher" value="0" '.(Tools::getValue('rewards_voucher', MyConf::get('REWARDS_VOUCHER', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_voucher_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear not_templated">
							<label>'.$this->l('Validity of the rewards before being canceled if not used (in days, 0=unlimited)').'</label>
							<div class="margin-form">
								<input type="text" size="4" maxlength="4" id="rewards_duration" name="rewards_duration" value="'.Tools::getValue('rewards_duration', Configuration::get('REWARDS_DURATION')).'" />
							</div>
						</div>
					</fieldset>
					<fieldset id="rewards_payment" class="rewards_payment_optional">
						<legend>'.$this->l('Settings applied for the rewards payment').'</legend>
						<div class="not_templated">
							<label>'.$this->l('Customers groups allowed to ask for payment').'</label>
							<div class="margin-form">
								<select name="rewards_payment_groups[]" multiple="multiple" class="multiselect">';
		foreach($groups as $group) {
			$html .= '				<option '.(is_array($rewards_payment_groups) && in_array($group['id_group'], $rewards_payment_groups) ? 'selected':'').' value="'.$group['id_group'].'"> '.$group['name'].'</option>';
		}
		$html .= '
								</select>
							</div>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('An invoice must be uploaded to ask for payment').'</label>
						<div class="margin-form">
							<label class="t" for="rewards_payment_invoice_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="rewards_payment_invoice_on" name="rewards_payment_invoice" value="1" '.(Tools::getValue('rewards_payment_invoice', MyConf::get('REWARDS_PAYMENT_INVOICE', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_payment_invoice_on">' . $this->l('Yes') . '</label>
							<label class="t" for="rewards_payment_invoice_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="rewards_payment_invoice_off" name="rewards_payment_invoice" value="0" '.(Tools::getValue('rewards_payment_invoice', MyConf::get('REWARDS_PAYMENT_INVOICE', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_payment_invoice_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear"></div>
						<div>
							<table>
								<tr>
									<td class="label">' . $this->l('Currency used by the member') . '</td>
									<td align="left">' . $this->l('Minimum required in account to be able to ask for payment') . '</td>
								</tr>';
		foreach ($currencies as $currency) {
			$html .= '
								<tr>
									<td><label class="indent">' . htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8') . '</label></td>
									<td align="left"><input '. ((int)$currency['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT') ? 'class="currency_default"' : '') . ' type="text" size="8" maxlength="8" name="rewards_payment_min_value['.(int)($currency['id_currency']).']" id="rewards_payment_min_value['.(int)($currency['id_currency']).']" value="'.Tools::getValue('rewards_payment_min_value['.(int)($currency['id_currency']).']', MyConf::get('REWARDS_PAYMENT_MIN_VALUE_'.(int)($currency['id_currency']), null, $this->id_template)).'" /> <label class="t">'.$currency['sign'].'</label>'.((int)$currency['id_currency'] != (int)Configuration::get('PS_CURRENCY_DEFAULT') ? ' <a href="#" onClick="return convertCurrencyValue(this, \'rewards_payment_min_value\', '.$currency['conversion_rate'].')"><img src="'._MODULE_DIR_.'allinone_rewards/img/convert.gif" style="vertical-align: middle !important"></a>' : '').'</td>
								</tr>';
		}
		$html .= '
							</table>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Convertion rate').'<br/><small>'.$this->l('Example: for 100€ in reward account, if ratio is 75 then the customer will get only 75€ payment').'</small></label>
						<div class="margin-form">
							<input type="text" size="4" maxlength="4" id="rewards_payment_ratio" name="rewards_payment_ratio" value="'.Tools::getValue('rewards_payment_ratio', MyConf::get('REWARDS_PAYMENT_RATIO', null, $this->id_template)).'" />
						</div>
					</fieldset>
					<fieldset id="rewards_voucher" class="rewards_voucher_optional">
						<legend>'.$this->l('Settings applied when transforming rewards into vouchers').'</legend>
						<div class="not_templated">
							<label>'.$this->l('Customers groups allowed to transform rewards into vouchers').'</label>
							<div class="margin-form">
								<select name="rewards_voucher_groups[]" multiple="multiple" class="multiselect">';
		foreach($groups as $group) {
			$html .= '				<option '.(is_array($rewards_voucher_groups) && in_array($group['id_group'], $rewards_voucher_groups) ? 'selected':'').' value="'.$group['id_group'].'"> '.$group['name'].'</option>';
		}
		$html .= '
								</select>
							</div>
							<div class="clear"></div>
						</div>
						<div style="padding-bottom: 5px">
							<table>
								<tr>
									<td class="label">' . $this->l('Currency used by the member') . '</td>
									<td align="left">' . $this->l('Minimum required in account to be able to transform rewards into vouchers') . '</td>
								</tr>';
		foreach ($currencies as $currency) {
			$html .= '
								<tr>
									<td><label class="indent">' . htmlentities($currency['name'], ENT_NOQUOTES, 'utf-8') . '</label></td>
									<td align="left"><input '. ((int)$currency['id_currency'] == (int)Configuration::get('PS_CURRENCY_DEFAULT') ? 'class="currency_default"' : '') . ' type="text" size="8" maxlength="8" name="rewards_voucher_min_value['.(int)($currency['id_currency']).']" id="rewards_voucher_min_value['.(int)($currency['id_currency']).']" value="'.Tools::getValue('rewards_voucher_min_value['.(int)($currency['id_currency']).']', MyConf::get('REWARDS_VOUCHER_MIN_VALUE_'.(int)($currency['id_currency']), null, $this->id_template)).'" /> <label class="t">'.$currency['sign'].'</label>'.((int)$currency['id_currency'] != (int)Configuration::get('PS_CURRENCY_DEFAULT') ? ' <a href="#" onClick="return convertCurrencyValue(this, \'rewards_voucher_min_value\', '.$currency['conversion_rate'].')"><img src="'._MODULE_DIR_.'allinone_rewards/img/convert.gif" style="vertical-align: middle !important"></a>' : '').'</td>
								</tr>';
		}
		$html .= '
							</table>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Voucher details (will appear in cart next to voucher code)').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="voucher_details_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="voucher_details_'.$language['id_lang'].'" value="'.htmlentities(Tools::getValue('voucher_details_'.$language['id_lang'], MyConf::get('REWARDS_VOUCHER_DETAILS', (int)$language['id_lang'], $this->id_template)), ENT_QUOTES, 'utf-8').'" />
							</div>';
		$html .= '
						</div>
						<div class="clear" style="margin-top: 20px"></div>
						<label>'.$this->l('Prefix for the voucher code (at least 3 letters long)').'</label>
						<div class="margin-form">
							<input type="text" size="10" maxlength="10" id="voucher_prefix" name="voucher_prefix" value="'.Tools::getValue('voucher_prefix', MyConf::get('REWARDS_VOUCHER_PREFIX', null, $this->id_template)).'" />
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Validity of the voucher (in days)').'</label>
						<div class="margin-form">
							<input type="text" size="4" maxlength="4" id="voucher_duration" name="voucher_duration" value="'.Tools::getValue('voucher_duration', MyConf::get('REWARDS_VOUCHER_DURATION', null, $this->id_template)).'" />
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Display vouchers in the cart summary').'</label>&nbsp;
						<div class="margin-form">
							<label class="t" for="display_cart_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="display_cart_on" name="display_cart" value="1" '.(Tools::getValue('display_cart', MyConf::get('REWARDS_DISPLAY_CART', null, $this->id_template)) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="display_cart_on">' . $this->l('Yes') . '</label>
							<label class="t" for="display_cart_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="display_cart_off" name="display_cart" value="0" '.(Tools::getValue('display_cart', MyConf::get('REWARDS_DISPLAY_CART', null, $this->id_template)) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="display_cart_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Cumulative with other vouchers').'</label>
						<div class="margin-form">
							<label class="t" for="cumulative_voucher_s_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
							<input type="radio" id="cumulative_voucher_s_on" name="cumulative_voucher_s" value="1" '.(Tools::getValue('cumulative_voucher_s', MyConf::get('REWARDS_VOUCHER_CUMUL_S', null, $this->id_template)) ? 'checked="checked"' : '').' /> <label class="t" for="cumulative_voucher_s_on">' . $this->l('Yes') . '</label>
							<label class="t" for="cumulative_voucher_s_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
							<input type="radio" id="cumulative_voucher_s_off" name="cumulative_voucher_s" value="0" '.(!Tools::getValue('cumulative_voucher_s', MyConf::get('REWARDS_VOUCHER_CUMUL_S', null, $this->id_template)) ? 'checked="checked"' : '').' /> <label class="t" for="cumulative_voucher_s_off">' . $this->l('No') . '</label>
						</div>
						<div class="clear" style="margin-top: 20px"></div>
						<label>'.$this->l('Minimum amount in which the voucher can be used').'</label>
						<div class="margin-form">
							<input type="text" size="2" name="minimal" value="'.Tools::getValue('minimal', (float)MyConf::get('REWARDS_MINIMAL', null, $this->id_template)).'" /> '.$this->context->currency->sign.'&nbsp;
							<select name="include_tax">
								<option '.(!Tools::getValue('include_tax', MyConf::get('REWARDS_MINIMAL_TAX', null, $this->id_template))?'selected':'').' value="0">'.$this->l('VAT Excl.').'</option>
								<option '.(Tools::getValue('include_tax', MyConf::get('REWARDS_MINIMAL_TAX', null, $this->id_template))?'selected':'').' value="1">'.$this->l('VAT Incl.').'</option>
							</select>
							<select name="include_shipping">
								<option '.(!Tools::getValue('include_shipping', MyConf::get('REWARDS_MINIMAL_SHIPPING', null, $this->id_template))?'selected':'').' value="0">'.$this->l('Shipping Excluded').'</option>
								<option '.(Tools::getValue('include_shipping', MyConf::get('REWARDS_MINIMAL_SHIPPING', null, $this->id_template))?'selected':'').' value="1">'.$this->l('Shipping Included').'</option>
							</select>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('If the voucher is not depleted when used').'</label>&nbsp;
						<div class="margin-form">
							<select name="voucher_behavior">
								<option '.(!Tools::getValue('voucher_behavior', (int)MyConf::get('REWARDS_VOUCHER_BEHAVIOR', null, $this->id_template)) ?'selected':'').' value="0">'.$this->l('Cancel the remaining amount').'</option>
								<option '.(Tools::getValue('voucher_behavior', (int)MyConf::get('REWARDS_VOUCHER_BEHAVIOR', null, $this->id_template)) ?'selected':'').' value="1">'.$this->l('Create a new voucher with remaining amount').'</option>
							</select>
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Vouchers can be used in the following categories :').'</label>
						<div class="margin-form">
							<table cellspacing="0" cellpadding="0" class="table">
								<tr>
									<th><input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, \'voucher_category[]\', this.checked)" /></th>
									<th>'.$this->l('ID').'</th>
									<th style="width: 400px">'.$this->l('Name').'</th>
								</tr>';
		$index = Tools::getValue('voucher_category') ? Tools::getValue('voucher_category') : explode(',', MyConf::get('REWARDS_VOUCHER_CATEGORY', null, $this->id_template));
		$current = current(current($categories));
		$html .= $this->recurseCategoryForInclude('voucher_category', $index, $categories, $current, $current['infos']['id_category']);
		$html .= '
							</table>
						</div>
					</fieldset>
					<div class="clear center"><input type="submit" name="submitReward" id="submitReward" value="'.$this->l('Save settings').'" class="button" /></div>
				</form>
			</div>
			<div id="tabs-'.$this->name.'-2" class="not_templated">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post">
				<input type="hidden" name="plugin" id="plugin" value="'.$this->name.'" />
				<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-2" />
				<fieldset>
					<legend>'.$this->l('Notifications').'</legend>
					<label>'.$this->l('Send a periodic email to the customer with his rewards account balance').'</label>
					<div class="margin-form">
						<label class="t" for="rewards_reminder_on"><img src="../img/admin/enabled.gif" alt="'.$this->l('Enabled').'" title="'.$this->l('Yes').'" /></label>
						<input type="radio" id="rewards_reminder_on" name="rewards_reminder" value="1" '.(Tools::getValue('rewards_reminder', Configuration::get('REWARDS_REMINDER')) == 1 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_reminder_on">' . $this->l('Yes') . '</label>
						<label class="t" for="rewards_reminder_off" style="margin-left: 10px"><img src="../img/admin/disabled.gif" alt="'.$this->l('Disabled').'" title="'.$this->l('No').'" /></label>
						<input type="radio" id="rewards_reminder_off" name="rewards_reminder" value="0" '.(Tools::getValue('rewards_reminder', Configuration::get('REWARDS_REMINDER')) == 0 ? 'checked="checked"' : '').' /> <label class="t" for="rewards_reminder_off">' . $this->l('No') . '</label>
					</div>
					<div class="clear optional rewards_reminder_optional">
						<div class="clear"></div>
						<label>'.$this->l('Minimum required in account to receive an email').'</label>
						<div class="margin-form">
							<input type="text" size="3" name="rewards_reminder_minimum" value="'.Tools::getValue('rewards_reminder_minimum', (float)Configuration::get('REWARDS_REMINDER_MINIMUM')).'" /> '.$this->context->currency->sign.'&nbsp;
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Frequency of the emails (in days)').'</label>
						<div class="margin-form">
							<input type="text" size="3" name="rewards_reminder_frequency" value="'.Tools::getValue('rewards_reminder_frequency', (float)Configuration::get('REWARDS_REMINDER_FREQUENCY')).'" />
						</div>
					</div>
				</fieldset>
				<div class="clear center"><input class="button" name="submitRewardsNotifications" id="submitRewardsNotifications" value="'.$this->l('Save settings').'" type="submit" /></div>
				</form>
			</div>
			<div id="tabs-'.$this->name.'-3">
				<form action="'.Tools::safeOutput($_SERVER['REQUEST_URI']).'" method="post" enctype="multipart/form-data">
					<input type="hidden" name="plugin" id="plugin" value="'.$this->name.'" />
					<input type="hidden" name="tabs-'.$this->name.'" value="tabs-'.$this->name.'-3" />
					<fieldset class="not_templated">
						<legend>'.$this->l('Labels of the different rewards states displayed in the rewards account').'</legend>
						<label>'.$this->l('Initial').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="default_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="default_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateDefault->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateDefault->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateDefault->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Unavailable').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="discounted_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="discounted_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateDiscounted->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateDiscounted->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateDiscounted->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Converted').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="convert_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="convert_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateConvert->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateConvert->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateConvert->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Validation').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="validation_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="validation_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateValidation->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateValidation->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateValidation->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Return period not exceeded').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="return_period_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="return_period_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateReturnPeriod->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateReturnPeriod->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateReturnPeriod->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Cancelled').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="cancel_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="cancel_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateCancel->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateCancel->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateCancel->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Waiting for payment').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="waiting_payment_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="waiting_payment_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStateWaitingPayment->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStateWaitingPayment->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStateWaitingPayment->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
						<div class="clear"></div>
						<label>'.$this->l('Paid').'</label>
						<div class="margin-form translatable">';
		foreach ($languages as $language)
			$html .= '
							<div class="lang_'.$language['id_lang'].'" id="paid_reward_state_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').'; float: left;">
								<input size="33" type="text" name="paid_reward_state_'.$language['id_lang'].'" value="'.(isset($this->rewardStatePaid->name[(int)$language['id_lang']]) ? htmlentities($this->rewardStatePaid->name[(int)$language['id_lang']], ENT_QUOTES, 'utf-8') : htmlentities($this->rewardStatePaid->name[(int)$defaultLanguage], ENT_QUOTES, 'utf-8')).'" />
							</div>';
		$html .= '
						</div>
					</fieldset>
					<fieldset>
						<legend>'.$this->l('Text to display in the rewards account').'</legend>
						<div class="translatable">';
		foreach ($languages AS $language) {
			$html .= '
							<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte autoload_rte" cols="80" rows="25" name="rewards_general_txt['.$language['id_lang'].']">'.MyConf::get('REWARDS_GENERAL_TXT', (int)$language['id_lang'], $this->id_template).'</textarea>
							</div>';
		}
		$html .= '
						</div>
					</fieldset>
					<fieldset>
						<legend>'.$this->l('Recommendations for the payment (bank information, invoice, delay...)').'</legend>
						<div class="translatable">';
		foreach ($languages AS $language) {
			$html .= '
							<div class="lang_'.$language['id_lang'].'" style="display: '.($language['id_lang'] == $defaultLanguage ? 'block' : 'none').';float: left;">
								<textarea class="rte autoload_rte" cols="80" rows="25" name="rewards_payment_txt['.$language['id_lang'].']">'.MyConf::get('REWARDS_PAYMENT_TXT', (int)$language['id_lang'], $this->id_template).'</textarea>
							</div>';
		}
		$html .= '
						</div>
					</fieldset>
					<div class="clear center"><input type="submit" name="submitRewardText" id="submitRewardText" value="'.$this->l('Save settings').'" class="button" /></div>
				</form>
			</div>
		</div>';

		return $html;
	}

	// add the css used by the module
	public function hookDisplayHeader()
	{
		$this->context->controller->addCSS($this->instance->getPath().'css/allinone_rewards.css', 'all');

		// Convertit les récompenses à l'état ReturnPeriodId en ValidationId si la date de retour est dépassée, et envoie les mails de rappel
		if (!Configuration::get('REWARDS_USE_CRON')) {
			RewardsModel::checkRewardsStates();
			RewardsAccountModel::sendReminder();
		}
	}

	// display the link to access to the rewards account
	public function hookDisplayCustomerAccount($params)
	{
		return $this->instance->display($this->instance->path, 'customer-account.tpl');
	}

	public function hookDisplayMyAccountBlock($params)
	{
		return $this->instance->display($this->instance->path, 'my-account.tpl');
	}

	public function hookDisplayMyAccountBlockFooter($params)
	{
		return $this->hookDisplayMyAccountBlock($params);
	}

	// display rewards account information in customer admin page
	public function hookDisplayAdminCustomers($params)
	{
		$customer = new Customer((int)$params['id_customer']);
		if ($customer && !Validate::isLoadedObject($customer))
			die(Tools::displayError('Incorrect object Customer.'));

		$msg = $this->postProcess($params);
		$totals = RewardsModel::getAllTotalsByCustomer((int)$params['id_customer']);
		$rewards = RewardsModel::getAllByIdCustomer((int)$params['id_customer'], true);
		$payments = RewardsPaymentModel::getAllByIdCustomer((int)$params['id_customer']);
		$rewards_account = new RewardsAccountModel((int)$params['id_customer']);

		$states_for_update = array(RewardsStateModel::getDefaultId(), RewardsStateModel::getValidationId(), RewardsStateModel::getCancelId(), RewardsStateModel::getReturnPeriodId());

		$smarty_values = array(
			'customer' => $customer,
			'msg' => $msg,
			'totals' => $totals,
			'rewards' => $rewards,
			'rewards_duration' => (int)Configuration::get('REWARDS_DURATION'),
			'payments' => $payments,
			'payment_authorized' => (int)Configuration::get('REWARDS_PAYMENT'),
			'rewards_account' => $rewards_account,
			'states_for_update' => $states_for_update,
			'sign' => $this->context->currency->sign,
			'rewardStateDefault' => $this->rewardStateDefault->name[(int)$this->context->language->id],
			'rewardStateValidation' => $this->rewardStateValidation->name[(int)$this->context->language->id],
			'rewardStateCancel' => $this->rewardStateCancel->name[(int)$this->context->language->id],
			'rewardStateConvert' => $this->rewardStateConvert->name[(int)$this->context->language->id],
			'rewardStateDiscounted' => $this->rewardStateDiscounted->name[(int)$this->context->language->id],
			'rewardStateReturnPeriod' => $this->rewardStateReturnPeriod->name[(int)$this->context->language->id],
			'rewardStateWaitingPayment' => $this->rewardStateWaitingPayment->name[(int)$this->context->language->id],
			'rewardStatePaid' => $this->rewardStatePaid->name[(int)$this->context->language->id],
			'new_reward_value' => (float)Tools::getValue('new_reward_value'),
			'new_reward_state' => (int)Tools::getValue('new_reward_state'),
			'new_reward_reason' => Tools::getValue('new_reward_reason')
		);
		$this->context->smarty->assign($smarty_values);
		return $this->instance->display($this->instance->path, 'admincustomer.tpl');
	}
}