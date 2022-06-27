<?php
if (!defined('_PS_VERSION_'))
	exit;

class CartAbandonmentPro extends Module
{
	protected static $lang_cache;

	public function __construct()
	{
		$this->name 					= 'cartabandonmentpro';
		$this->tab 						= 'advertising_marketing';
		$this->version 					= '1.6.11';
		$this->author 					= 'PrestaShop';
		$this->module_key 				= '011df651e7ac1913166469984d0cf519';
		$this->need_instance			= 0;
		$this->ps_version_compliancy	= array('min' => '1.5.0.0', 'max' => '1.6.1.0');
		$this->dependencies				= array();
		$this->bootstrap				= true;
		parent::__construct();

		$this->displayName				= $this->l('Cart Abandonment Pro');
		$this->description				= $this->l('Send an automatic mail to customers that abandoned their shopping cart.');

		$this->confirmUninstall			= $this->l('Are you sure you want to uninstall?');

		$this->css_path 				= $this->_path.'views/css/';
		$this->js_path 					= $this->_path.'views/js/';

		if (version_compare(_PS_VERSION_, '1.6', '<'))
			$this->getLang();
	}

	public function install()
	{
		Configuration::updateValue('CART_MAXREMINDER', 7);
		Configuration::updateValue('CART_MAXREMINDER_WHAT', 'days');

		$token = uniqid(rand(), true);
		foreach(Shop::getShops() as $shop) {
			Configuration::set('CAB_NEWS', 1, null, $shop['id_shop']);
			Configuration::updateValue('CARTABAND_TOKEN', $token, false, $shop['id_shop_group'], $shop['id_shop']);
		}

		Configuration::updateValue('CARTABAND_TOKEN', $token);

		Configuration::updateValue('CARTABAND_DISCOUNT', 0);
		self::initDirectory('tpls','0777');
		self::initDirectory('mails', '0777');

		if(!$this->installDB() || !parent::install())
			return false;
		return true;
	}

	private function installDB()
	{
		$queries[0] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_template` (
				  `id_template` int(11) NOT NULL AUTO_INCREMENT,
				  `id_model` int(11) NOT NULL,
				  `name` varchar(100) NOT NULL,
				  `id_lang` int(11),
				  `id_shop` int(11),
				  `active` int(11),
				  `order` int(11),
				  PRIMARY KEY (`id_template`)
				  );";
		$queries[1] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_template_field` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `id_template` int(11) NOT NULL,
					  `id_field` int(11) NOT NULL,
					  `value` longtext NOT NULL,
					  `column` varchar(10) NOT NULL,
					  PRIMARY KEY (`id`)
					);";
		$queries[2] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_template_color` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `id_template` int(11) NOT NULL,
				  `id_color` int(11) NOT NULL,
				  `value` varchar(15) NOT NULL,
				  PRIMARY KEY (`id`)
				);";
		$queries[3] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_remind_config` (
				  `wich_remind` int(11) NOT NULL,
				  `days` int(11) NOT NULL,
				  `hours` int(11) NOT NULL,
				  `active` int(11) NOT NULL,
				  `id_shop` int(11) NOT NULL,
				  PRIMARY KEY (`wich_remind`)
				);";
		$queries[4] = "INSERT INTO `"._DB_PREFIX_."cartabandonment_remind_config` VALUES (1, 0, 2, 1, 0);";
		$queries[5] = "INSERT INTO `"._DB_PREFIX_."cartabandonment_remind_config` VALUES (2, 2, 0, 0, 0);";
		$queries[6] = "INSERT INTO `"._DB_PREFIX_."cartabandonment_remind_config` VALUES (3, 5, 0, 0, 0);";

		$queries[7] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_remind` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `wich_remind` int(11) NOT NULL,
				  `id_cart` int(11) NOT NULL,
				  `send_date` date NOT NULL,
				  PRIMARY KEY (`id`)
				);";
		$queries[8] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_remind_lang` (
				  `wich_remind` int(11) NOT NULL,
				  `id_lang` int(11) NOT NULL,
				  `id_template` int(11) NOT NULL,
				  `tpl_same` int(11) NOT NULL,
				  `id_shop` int(11) NOT NULL,
				  PRIMARY KEY (`wich_remind`,`id_lang`,`id_template`)
				);";
		$queries[9] = "ALTER TABLE `"._DB_PREFIX_."cartabandonment_remind` ADD `visualize` INT NOT NULL DEFAULT '0',
				ADD `click` INT NOT NULL DEFAULT '0';";
		$queries[10] = "ALTER TABLE `"._DB_PREFIX_."cartabandonment_remind` ADD `click_cart` INT NOT NULL DEFAULT '0';";
		$queries[11] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonment_unsubscribe` (
				`id_customer` int(11) NOT NULL,
				PRIMARY KEY (`id_customer`)
				) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		$queries[12] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonmentpro_cartrule` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `type` varchar(10) NOT NULL,
					  `value` float NOT NULL,
					  `date_start` date DEFAULT NULL,
					  `date_end` date DEFAULT NULL,
					  `valid` varchar(5) NOT NULL,
					  `valid_value` int(11) DEFAULT NULL,
					  `valid_date` date DEFAULT NULL,
					  `min_amount` float DEFAULT NULL,
					  `max_amount` int(11) DEFAULT NULL,
					  `id_template` int(11) NOT NULL,
					  `tranche` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ";
		$queries[13] = "ALTER TABLE  `"._DB_PREFIX_."cartabandonment_template` CHANGE  `name`  `name` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
		foreach($queries as $query)
		{
			$test = Db::getInstance()->Execute($query);
		}
		return true;
	}

	private function updateTables()
	{
		$queries[0] = "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."cartabandonmentpro_cartrule` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `type` varchar(10) NOT NULL,
					  `value` float NOT NULL,
					  `date_start` date DEFAULT NULL,
					  `date_end` date DEFAULT NULL,
					  `valid` varchar(5) NOT NULL,
					  `valid_value` int(11) DEFAULT NULL,
					  `valid_date` date DEFAULT NULL,
					  `min_amount` float DEFAULT NULL,
					  `max_amount` int(11) DEFAULT NULL,
					  `id_template` int(11) NOT NULL,
					  `tranche` int(11) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)
					) ";
		foreach($queries as $query)
		{
			$test = Db::getInstance()->Execute($query);
		}
		return true;
	}
	public function uninstall()
	{
		$query  = "DROP TABLE `"._DB_PREFIX_."cartabandonment_template`;";
		$query2 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_template_field`;";
		$query3 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_template_color`;";
		$query4 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_conf`;";
		$query4 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_remind`;";
		$query5 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_remind_lang`;";
		$query6 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_remind_config`;";
		$query7 = "DROP TABLE `"._DB_PREFIX_."cartabandonment_unsubscribe`;";
		$query8 = "DROP TABLE `"._DB_PREFIX_."cartabandonmentpro_cartrule`;";

		Db::getInstance()->Execute("DELETE FROM "._DB_PREFIX_."cronjobs WHERE description = 'Automatic cronjob cartabandonment pro 1'");
		Db::getInstance()->Execute("DELETE FROM "._DB_PREFIX_."cronjobs WHERE description = 'Automatic cronjob cartabandonment pro 2'");
		Db::getInstance()->Execute("DELETE FROM "._DB_PREFIX_."cronjobs WHERE description = 'Automatic cronjob cartabandonment pro 3'");

		return Db::getInstance()->Execute($query)
			&& Db::getInstance()->Execute($query2)
			&& Db::getInstance()->Execute($query3)
			&& Db::getInstance()->Execute($query4)
			&& Db::getInstance()->Execute($query5)
			&& Db::getInstance()->Execute($query6)
			&& parent::uninstall();
	}

	 /**
	* Loads asset resources
	*/
	public function loadAsset()
	{
		$css_compatibility = $js_compatibility = array();

		// Load CSS
		$css = array(
			$this->css_path.'bootstrap-select.min.css',
			$this->css_path.'DT_bootstrap.css',
			$this->css_path.'fix.css',
			$this->css_path.'views/css/reset.css',
			$this->css_path.'views/css/cartabandonmentpro.css',
			$this->css_path.'views/css/datepicker.css',
			$this->css_path.'faq.css',
			$this->css_path.'sweet-alert.css',
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$css_compatibility = array(
				$this->css_path.'bootstrap.min.css',
				$this->css_path.'bootstrap.extend.css',
				$this->css_path.'bootstrap-responsive.min.css',
				$this->css_path.'font-awesome.min.css'
			);
			$css = array_merge($css_compatibility, $css);
		}
		$this->context->controller->addCSS($css, 'all');

		// Load JS
		$js = array(
			$this->js_path.$this->name.'.js',
			$this->js_path.'bootstrap-select.min.js',
			$this->js_path.'bootstrap-dialog.js',
			$this->js_path.'jquery.autosize.min.js',
			$this->js_path.'jquery.dataTables.js',
			$this->js_path.'DT_bootstrap.js',
			$this->js_path.'dynamic_table_init.js',
			$this->js_path.'jscolor.js',
			$this->js_path.'tinymce/tinymce.min.js',
			$this->js_path.'bootstrap-datepicker.js',
			$this->js_path.'faq.js',
			$this->js_path.'sweet-alert.min.js'
		);
		if (version_compare(_PS_VERSION_, '1.6', '<'))
		{
			$js_compatibility = array(
				$this->js_path.'bootstrap.min.js'
			);
			$js = array_merge($js_compatibility, $js);
		}
		$this->context->controller->addJS($js);

		// Clean memory
		unset($js, $css, $js_compatibility, $css_compatibility);
	}

	public function getContent()
	{
		require_once dirname(__FILE__).'/controllers/GodController.class.php';
		require_once dirname(__FILE__).'/classes/Model.class.php';
		require_once dirname(__FILE__).'/classes/Template.class.php';
		require_once dirname(__FILE__).'/controllers/TemplateController.class.php';
		require_once dirname(__FILE__).'/controllers/ConfController.class.php';
		require_once dirname(__FILE__).'/controllers/ReminderController.class.php';
		require_once dirname(__FILE__).'/controllers/StatsController.class.php';
		require_once dirname(__FILE__).'/controllers/DiscountsController.class.php';

		$this->updateTables();
		$god = new GodController();
		unset($god);
		$this->loadAsset();

		$this->initCronJob();
		$this->handleCron();
		$this->handleDiscounts();
		$this->viewEdit();
		$this->initVars();
		$this->initStats();
		self::initRight('views/js/fileman/Uploads');

		return $this->display(__FILE__, GodController::getTemplate());
	}

	private function handleDiscounts()
	{
		$discounts_tab = 0;
		$template_disc = 1;
		$discounts_save = 0;
		Configuration::updateValue('CARTABAND_DISCOUNT', 0);
		if(Tools::isSubmit('discounts_template'))
		{
				$template_disc = Tools::getValue('discounts_template');
				$discounts_tab = 1;
		}

		if(Tools::isSubmit('discounts_form_submit'))
		{
			if(Tools::getValue('discounts_active') == 1)
			{
				if(Tools::getValue('discounts_different_val') == 0)
				{
					$query = 'DELETE FROM '._DB_PREFIX_."cartabandonmentpro_cartrule WHERE id_template = ".(int)Tools::getValue('discounts_template').";";
					Db::getInstance()->Execute($query);
					$query = 'INSERT INTO '._DB_PREFIX_.'cartabandonmentpro_cartrule VALUE (NULL, "'.Tools::getValue('discounts_type').'", "'.Tools::getValue('discounts_value').'", NULL, NULL, "'.Tools::getValue('discounts_validity').'", "'.Tools::getValue('discounts_validity_days').'", NULL, '.Tools::getValue('discounts_min').', '.Tools::getValue('discounts_max').', '.Tools::getValue('discounts_template').', 0);';
					Db::getInstance()->Execute($query);
				}
				else
				{
					$query = 'DELETE FROM '._DB_PREFIX_."cartabandonmentpro_cartrule WHERE id_template = ".(int)Tools::getValue('discounts_template').";";
					Db::getInstance()->Execute($query);

					$tranches = Tools::getValue('discounts_tranche');

					for($x = 1; $x <= 3; $x++)
					{
						$query = 'INSERT INTO '._DB_PREFIX_.'cartabandonmentpro_cartrule VALUE (NULL, "'.Tools::getValue('discounts_type_'.$x).'", "'.Tools::getValue('discounts_value_'.$x).'", NULL, NULL, "time", "'.Tools::getValue('discounts_validity_days_'.$x).'", NULL, '.Tools::getValue('discounts_min_'.$x).', '.Tools::getValue('discounts_max_'.$x).', '.Tools::getValue('discounts_template').', '.$x.');';
						Db::getInstance()->Execute($query);
						if($tranches == $x)
							break;
					}
				}
				Configuration::updateValue('CARTABAND_DISCOUNT', 1);
			}
			else
			{
				$query = 'DELETE FROM '._DB_PREFIX_."cartabandonmentpro_cartrule WHERE id_template = ".(int)Tools::getValue('discounts_template').";";
				Db::getInstance()->Execute($query);
			}
			$discounts_tab = 1;
			$discounts_save = 1;
			Configuration::updateValue('CARTABAND_TRANCHE', Tools::getValue('discounts_tranche'));
			Configuration::updateValue('CARTABAND_DISCOUNT_'.$template_disc, Tools::getValue('discounts_active_val'));
			Configuration::updateValue('CARTABAND_DIF_DISC_'.$template_disc, Tools::getValue('discounts_different_val2'));
			DiscountsController::saveDiscountsTxt($this->context->shop->id);
		}

		$query = 'SELECT * FROM '._DB_PREFIX_.'cartabandonmentpro_cartrule WHERE id_template = '.(int)$template_disc.' ORDER BY tranche;';
		$discounts = Db::getInstance()->ExecuteS($query);
		if(empty($discounts))
		{
			$discounts[0]['type'] = 'currency';
			$discounts[1]['type'] = 'currency';
			$discounts[2]['type'] = 'currency';
		}
		else if(count($discounts) < 2)
		{
			$discounts[1]['type'] = 'currency';
			$discounts[2]['type'] = 'currency';
		}
		else if(count($discounts) < 3)
			$discounts[2]['type'] = 'currency';

		$tranches = Configuration::get('CARTABAND_TRANCHE');
		if(!$tranches)
				$tranches = 1;

		$errors['val'] = $this->l('Discount value is not correct.');
		$errors['valid'] = $this->l('Discount validity is not correct.');

		$errors['value_1'] = $this->l('Discount value for range 1 is not correct.');
		$errors['valid_1'] = $this->l('Discount validity for range 1 is not correct.');
		$errors['min_1'] = $this->l('Minimum value for range 1 is not correct.');

		$errors['value_2'] = $this->l('Discount value for range 2 is not correct.');
		$errors['valid_2'] = $this->l('Discount validity for range 2 is not correct.');
		$errors['min_2'] = $this->l('Minimum value for range 2 is not correct.');

		$errors['value_3'] = $this->l('Discount value for range 3 is not correct.');
		$errors['valid_3'] = $this->l('Discount validity for range 3 is not correct.');
		$errors['min_3'] = $this->l('Minimum value for range 3 is not correct.');

		$this->context->smarty->assign('errors', $errors);
		$this->context->smarty->assign('tranches', $tranches);
		$this->context->smarty->assign('template_disc', $template_disc);
		$this->context->smarty->assign('discounts2', $discounts);
		$this->context->smarty->assign('discounts_tab', $discounts_tab);
		$this->context->smarty->assign('currency', Currency::getDefaultCurrency()->sign);
		$this->context->smarty->assign('discountsActive', Configuration::get('CARTABAND_DISCOUNT_'.$template_disc));
		$this->context->smarty->assign('discountsDif', Configuration::get('CARTABAND_DIF_DISC_'.$template_disc));
		$this->context->smarty->assign('discounts_tranche', $tranches);
		$this->context->smarty->assign('discounts_save', $discounts_save);
	}

	private function handleCron()
	{
		if(Tools::getIsset('activateCronJob'))
		{
			$cronOK = '';
			Db::getInstance()->Execute("DELETE FROM "._DB_PREFIX_."cronjobs WHERE description LIKE '%Automatic cronjob cartabandonment pro%'");

			if(Tools::getValue('activateCronJob') == 1)
			{
				$activated = Db::getInstance()->ExecuteS("SELECT COUNT(*) as activated FROM "._DB_PREFIX_."cronjobs WHERE description = 'automatic cronjob cartabandonment pro'");
				if($activated[0]['activated'] == 0)
				{
					$uri = _PS_BASE_URL_.__PS_BASE_URI__;
					foreach(Shop::getShops() as $shop)
					{
						Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cronjobs VALUES (NULL, NULL, 'Automatic cronjob cartabandonment pro 1 shop ".$shop['id_shop']."', '".$uri."modules/cartabandonmentpro/send.php?id_shop=".$shop['id_shop']."&token=".Configuration::get('CARTABAND_TOKEN', null, $shop['id_shop_group'], $shop['id_shop'])."&wich_remind=1', '-1', '-1', '-1', '-1', NULL, 0, 1, ".$shop['id_shop'].", ".$shop['id_shop_group'].")");
						Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cronjobs VALUES (NULL, NULL, 'Automatic cronjob cartabandonment pro 2 shop ".$shop['id_shop']."', '".$uri."modules/cartabandonmentpro/send.php?id_shop=".$shop['id_shop']."&token=".Configuration::get('CARTABAND_TOKEN', null, $shop['id_shop_group'], $shop['id_shop'])."&wich_remind=2', '-1', '-1', '-1', '-1', NULL, 0, 1, ".$shop['id_shop'].", ".$shop['id_shop_group'].")");
						Db::getInstance()->Execute("INSERT INTO "._DB_PREFIX_."cronjobs VALUES (NULL, NULL, 'Automatic cronjob cartabandonment pro 3 shop ".$shop['id_shop']."', '".$uri."modules/cartabandonmentpro/send.php?id_shop=".$shop['id_shop']."&token=".Configuration::get('CARTABAND_TOKEN', null, $shop['id_shop_group'], $shop['id_shop'])."&wich_remind=3', '-1', '-1', '-1', '-1', NULL, 0, 1, ".$shop['id_shop'].", ".$shop['id_shop_group'].")");
					}
					$cronOK = '<div id="alertCron" class="alert alert-success">
						'.$this->l('Your cron tasks have been successfully activated with PrestaShop cron tasks webservice.').'
						<button type="button" class="close" data-dismiss="alert">×</button>
					</div>';
				}
			}
			else if(Tools::getValue('activateCronJob') == 0)
			{
				$cronOK = '<div id="alertCron" class="alert alert-success">
					'.$this->l('Your cron tasks have been successfully deactivated with PrestaShop cron tasks webservice.').'
					<button type="button" class="close" data-dismiss="alert">×</button>
				</div>';
			}
			$this->context->smarty->assign('cronOK', $cronOK);
		}
	}

	private function initCronJob()
	{
		$warning = false;
		$cronjobs = Module::isInstalled('cronjobs');

		if (!$cronjobs)
		{
			if(file_exists(_PS_MODULE_DIR_.'cronjobs/cronjobs.php'))
			{
				$cronjobs_obj = Module::getInstanceByName('cronjobs');
				if (!Module::isInstalled('cronjobs'))
					$install = $cronjobs_obj->install();
				if(!Module::isEnabled('cronjobs'))
					$activate = Module::enableByName('cronjobs');
				unset($cronjobs_obj);
			}
			else{
				$install_link = $this->context->link->getAdminLink('AdminModules', false).'&module_name=cronjobs&anchor=Cronjobs&token='.Tools::getAdminTokenLite('AdminModules');
				$warning = $this->l('You need to install the free module').' <a href= "'.$install_link.'">'.$this->l('Cron jobs').' to activate automatic reminder</a>';
			}
		}

		unset($cronjobs);
		$this->context->smarty->assign('warning', $warning);
	}

	private function initStats()
	{
		$this->context->smarty->assign('carts1', ReminderController::getAbandonedCart(1, $this->context->shop->id));
		$this->context->smarty->assign('carts2', ReminderController::getAbandonedCart(2, $this->context->shop->id));
		$this->context->smarty->assign('carts3', ReminderController::getAbandonedCart(3, $this->context->shop->id));
		$this->context->smarty->assign('stats', StatsController::getStatsForReminder());
		$this->context->smarty->assign('unsubscribe', StatsController::getUnsubscribe());
	}

	/* initRight is use to verify the right of a directory */

	public static function initRight($path, $right = '0777'){
		$prems = substr(sprintf('%o', fileperms(dirname(__FILE__).'/'.$path)), -4);
		if ($prems != $right)
			@chmod(dirname(__FILE__).'/'.$path, octdec((int)$right));
	}

	/* initDirectory is use to check if the directory exist and permit to change the right */

	public static function initDirectory($path, $right = '0777'){
		$iso = Language::getIsoById(Tools::getValue('language'));
		if(!is_dir(dirname(__FILE__).'/'.$path))
		{
			mkdir(dirname(__FILE__).'/'.$path);
			$fp = fopen(dirname(__FILE__).'/'.$path.'/index.php', 'w+');
			fwrite($fp, "<?php die();");
			fclose($fp);
		}

		self::initRight($path, $right);
	}

	private function initVars(){
		$this->context->smarty->assign('token', Configuration::get('CARTABAND_TOKEN', null, $this->context->shop->id_shop_group, $this->context->shop->id));

        $languages = Language::getLanguages(true, $this->context->shop->id);
        if(empty($languages))
            $languages = Language::getLanguages(true);

		$id_lang = Tools::getValue('id_lang');
		$id_lang_doc = $this->context->employee->id_lang;

		if(!$id_lang)
			$id_lang = Configuration::get('PS_LANG_DEFAULT');

		$this->context->smarty->assign('languages', $languages);
		$this->context->smarty->assign('lang_default', Configuration::get('PS_LANG_DEFAULT', null, $this->context->shop->id_shop_group, $this->context->shop->id));
		$logo = Configuration::get('PS_LOGO');
		$this->context->smarty->assign('logo', $this->context->shop->domain.__PS_BASE_URI__.'img/'.$logo);
		$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
		$this->context->smarty->assign('uri', $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
		$this->context->smarty->assign('url', $protocol . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__);

		$this->context->smarty->assign('dirname', dirname(__FILE__));

		$this->initReminders();

		foreach($languages as $language)
		{
			$this->context->smarty->assign('discount_val_text_'.$language['id_lang'], Configuration::get('CARTABAND_DISC_VAL', $language['id_lang']));
			$this->context->smarty->assign('discount_shipping_text_'.$language['id_lang'], Configuration::get('CARTABAND_SHIPP_VAL', $language['id_lang']));
		}

		$this->context->smarty->assign('templates', TemplateController::getAllTemplates($this->context->shop->id, $id_lang));
		$this->context->smarty->assign('id_shop', $this->context->shop->id);
		$this->context->smarty->assign('id_lang', $id_lang);
		$this->context->smarty->assign('language', $id_lang);
		$this->context->smarty->assign('iso_lang', Language::getIsoById($id_lang));
		$this->context->smarty->assign('iso_lang_doc', Language::getIsoById($id_lang_doc));
		$this->context->smarty->assign('lang_select', self::$lang_cache);
		$this->context->smarty->assign('token_send', Configuration::get('CARTABAND_TOKEN', null, $this->context->shop->id_shop_group, $this->context->shop->id));

		$this->context->smarty->assign('template_name_1', TemplateController::getTemplateName(1));
		$this->context->smarty->assign('template_name_2', TemplateController::getTemplateName(2));
		$this->context->smarty->assign('template_name_3', TemplateController::getTemplateName(3));

		$conf = Tools::getValue('cartabandonment_conf');
		if(!isset($conf))
			$conf = 0;
		$this->context->smarty->assign('conf', $conf);

		$discounts = Tools::getValue('cartabandonment_discount');
		if(!isset($discounts))
			$discounts = 0;
		$this->context->smarty->assign('discounts', $discounts);

		$this->initEdit($id_lang);

		if(Tools::getValue('justEdited') == 1) $edit = 1;
		else							 	   				 $edit = 0;

		if (Module::isInstalled('cronjobs'))
			$activated = Db::getInstance()->ExecuteS("SELECT COUNT(*) as activated FROM "._DB_PREFIX_."cronjobs WHERE description LIKE '%Automatic cronjob cartabandonment pro%'");
		if(!isset($activated[0]['activated']) || is_null($activated[0]['activated']))
			$activated[0]['activated'] = false;

		$this->context->smarty->assign(array(
			'var_ajax' 		 => $this->setVarAjax(),
			'base_url' 		 => $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
			'ps_version' 	 => (bool)version_compare(_PS_VERSION_, '1.6', '>'),
			'edit' 			 => $edit,
			'cronActivated'	 => $activated[0]['activated'],
			'isWritable'	 => (int)(is_writable('../modules/cartabandonmentpro/mails/') && is_writable('../modules/cartabandonmentpro/tpls/')),
			'module_version' => $this->version,
			'newsletter' 	 => Configuration::get('CAB_NEWS', null, $this->context->shop->id_shop_group, $this->context->shop->id)
		));
	}

	// Get reminders
	private function initReminders(){
		$reminder = ReminderController::getReminders(1);
		$this->context->smarty->assign('first_reminder_days', $reminder[0]['days']);
		$this->context->smarty->assign('first_reminder_hours', $reminder[0]['hours']);
		$this->context->smarty->assign('first_reminder_active', $reminder[0]['active']);

		$reminder = ReminderController::getReminders(2);
		$this->context->smarty->assign('second_reminder_days', $reminder[0]['days']);
		$this->context->smarty->assign('second_reminder_hours', $reminder[0]['hours']);
		$this->context->smarty->assign('second_reminder_active', $reminder[0]['active']);

		$reminder = ReminderController::getReminders(3);
		$this->context->smarty->assign('third_reminder_days', $reminder[0]['days']);
		$this->context->smarty->assign('third_reminder_hours', $reminder[0]['hours']);
		$this->context->smarty->assign('third_reminder_active', $reminder[0]['active']);

		$this->context->smarty->assign('max_reminder', Configuration::get('CART_MAXREMINDER', null, null, Context::getContext()->shop->id));
		$this->context->smarty->assign('max_reminder_what', Configuration::get('CART_MAXREMINDER_WHAT', null, null, Context::getContext()->shop->id));
	}

	// Edit a template
	private function viewEdit(){
		if(Tools::getValue('viewedit') == 1){
			$this->context->smarty->assign('viewedit', 1);
			$this->context->smarty->assign('edittpl', Tools::getValue('tpl'));
			$this->context->smarty->assign('viewedit', 1);

			$editor = TemplateController::getEditor(Tools::getValue('tpl'));
			$this->context->smarty->assign('modelFile', '../../../model/' . $editor[0]['id_model'] . '_form_edit.tpl');

			$this->context->smarty->assign('tplDetails', $editor);
			$this->context->smarty->assign('tplColors', TemplateController::getEditorColors(Tools::getValue('tpl')));
			$this->context->smarty->assign('tplFields', TemplateController::getEditorFields(Tools::getValue('tpl')));
		}
		else
			$this->context->smarty->assign('viewedit', 0);
	}

	private function initEdit($id_lang){
		$reminders 	= ReminderController::getRemindersByLanguage($id_lang, $this->context->shop->id);
		if(!$reminders){
			$this->context->smarty->assign('editor', 0);
			return false;
		}
		$this->context->smarty->assign('editor', 1);

		if($reminders[0]['tpl_same'])
			$this->context->smarty->assign('id_tpl_same', $reminders[0]['id_template']);

		$x = 1;
		foreach($reminders as $reminder){
			$template_id 	= $reminder['id_template'];
			$model_id 		= TemplateController::getModelByTemplate($template_id);
			$this->context->smarty->assign('template_file_' . $x, $template_id . '.html');
			$this->context->smarty->assign('template_file_' . $x, $template_id . '.html');
			$this->context->smarty->assign('template_file_' . $x, $template_id . '.html');
			$this->context->smarty->assign('edit_template_id' . $x, $template_id);
			$this->context->smarty->assign('edit_template_id' . $x, $template_id);
			$this->context->smarty->assign('edit_template_id' . $x, $template_id);
			$this->context->smarty->assign('edit_model_id' . $x, $model_id);
			$this->context->smarty->assign('edit_model_id' . $x, $model_id);
			$this->context->smarty->assign('edit_model_id' . $x, $model_id);
			$x++;
		}
	}

	private function getLang()
	{
		if (self::$lang_cache == null && !is_array(self::$lang_cache))
		{
			self::$lang_cache = array();
			if ($languages = Language::getLanguages())
			{
				foreach ($languages as $row)
				{
						$exprow = explode(' (', $row['name']);
						$subtitle = (isset($exprow[1]) ? trim(Tools::substr($exprow[1], 0, -1)) : '');
						self::$lang_cache[$row['iso_code']] = array (
								'title' => trim($exprow[0]),
								'subtitle' => $subtitle
						);
				}
				// Clean memory
				unset($row, $exprow, $result, $subtitle, $languages);
			}
		}
	}

	/**
	* Set some JS vars for Ajax
	* @return string
	*/
	private function setVarAjax()
	{
		return ('<script>
		var admin_module_controller = \'AdminCartAbandonmentHelpingController\';
		var admin_module_ajax_url = \''.$this->context->link->getAdminLink('AdminCartAbandonmentHelpingController').'\';
		var current_id_tab = '.(int)$this->context->controller->id.';
		</script>');
	}

	/**
	* Install Tab
	* @return boolean
	*/
	private function installTab()
	{
		$tab = new Tab();
		$tab->active = 1;
		$tab->class_name = 'AdminCartAbandonmentHelpingController';
		$tab->name = array();
		foreach (Language::getLanguages(true) as $lang)
		$tab->name[$lang['id_lang']] = 'Cart Abandonment';
		unset($lang);
		$tab->id_parent = -1;
		$tab->module = $this->name;
		return $tab->add();
	}

	/**
	* Uninstall Tab
	* @return boolean
	*/
	private function uninstallTab()
	{
		$id_tab = (int)Tab::getIdFromClassName('AdminCartAbandonmentHelpingController');
		if ($id_tab)
		{
		$tab = new Tab($id_tab);
		return $tab->delete();
		}
		else
		return false;
	}
}
