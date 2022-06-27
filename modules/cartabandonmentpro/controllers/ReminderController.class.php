<?php
class ReminderController extends FrontController{

	public function __construct ()
	{
			
	}

	public function edit()
	{
		if(!Tools::getValue('tpl')) return false;

		$this->generateTemplate();
		$this->saveDiscounts();
		header('Location: ' . Tools::getValue('uri') . '&justEdited=1&cartabandonment_conf=1');die;
	}
	
	/**
	This function save the discounts sentences
	**/
	private function saveDiscounts()
	{
		// d(array(Tools::getValue('language') => Tools::getValue('discount_val_text')));
		// Configuration::updateValue('CARTABAND_DISC_VAL', array(Tools::getValue('language') => Tools::getValue('discount_val_text')));
		// Configuration::updateValue('CARTABAND_SHIPP_VAL', array(Tools::getValue('language') => Tools::getValue('discount_shipping_text')));
	}
	
	private function getReminds()
	{
		return Db::getInstance()->ExecuteS("SELECT wich_remind FROM "._DB_PREFIX_."cartabandonment_remind_config WHERE active = 1 ORDER BY wich_remind;");
	}
	
	private function getEditedTemplate($id_tpl)
	{
		if(!isset($id_tpl) || is_null($id_tpl) || $id_tpl == 0)
			$id_tpl = null;
		return $id_tpl;
	}
	
	private function sameTemplate()
	{
		$id_tpl = (int)Tools::getValue('edittpl1');

		$id_template = $this->saveTemplate($this->getEditedTemplate($id_tpl), 1, Tools::getValue('name_1'));
		// if(!$id_template)
			// d('Erreur lors de l\'enregistrement du template');
		$this->save(1, $id_template, Tools::getValue('tpl_same'));
		$this->save(2, $id_template, Tools::getValue('tpl_same'));
		$this->save(3, $id_template, Tools::getValue('tpl_same'));
	}
	
	private function generateTemplate()
	{
		for($x = 1;$x <= 3;$x++){
			$id_tpl = (int)Tools::getValue('edittpl' . $x);

			$id_template = $this->saveTemplate($this->getEditedTemplate($id_tpl), $x, Tools::getValue('name_' . $x));
			// if(!$id_template)
				// d('Erreur lors de l\'enregistrement du template');	
			$this->save($x, $id_template, Tools::getValue('tpl_same'));
		}
	}

	private function save($remind, $id_template, $tpl_same)
	{
		if(!is_writable('../modules/cartabandonmentpro/mails/') || !is_writable('../modules/cartabandonmentpro/tpls/'))
			return false;

		$query = "REPLACE INTO " . _DB_PREFIX_ . "cartabandonment_remind_lang VALUE(".$remind.", ".Tools::getValue('language').", ".(int)$id_template.", ".(int)$tpl_same.", ".pSQL(Tools::getValue('id_shop')).")";
		if(!Db::getInstance()->Execute($query))
			d('Erreur lors de l\'enregistrement du template');
	}
	
	private function saveTemplate($id_tpl, $wich_template, $name)
	{
		$model_id = Tools::getValue('model' . $wich_template);
		$template = new Template($id_tpl, new Model($model_id), $wich_template);
		$template->setName($name);
		return $template->save();
	}
	
	public static function setDays($wichRemind, $val)
	{
		$query = "UPDATE " . _DB_PREFIX_ . "cartabandonment_remind_config SET days = " . $val . " WHERE wich_remind = " . $wichRemind;

		return DB::getInstance()->Execute($query);
	}
	public static function setHours($wichRemind, $val)
	{
		$query = "UPDATE " . _DB_PREFIX_ . "cartabandonment_remind_config SET hours = " . $val . " WHERE wich_remind = " . $wichRemind;
		return DB::getInstance()->Execute($query);
	}
	public static function setActive($wichRemind, $val)
	{
		$query = "UPDATE " . _DB_PREFIX_ . "cartabandonment_remind_config SET active = " . $val . " WHERE wich_remind = " . $wichRemind;
		return DB::getInstance()->Execute($query);
	}
	public static function setMaxReminder($val)
	{
		return Configuration::updateValue('CART_MAXREMINDER', $val);
	}
	public static function setMaxReminderWhat($val)
	{
		return Configuration::updateValue('CART_MAXREMINDER_WHAT', $val);
	}
	
	public static function getAbandonedCart($wichReminder, $id_shop = 1)
	{
		$cab_news = Configuration::get('CAB_NEWS', null, null, $id_shop);

		$query	=  'SELECT `ca`.*, `cu`.`firstname`, `cu`.`lastname`, `cu`.`id_customer`, `cu`.`email`
					FROM `'._DB_PREFIX_.'cart` `ca`
					LEFT JOIN `'._DB_PREFIX_.'orders` `ord` ON `ord`.`id_cart` = `ca`.`id_cart`
					INNER JOIN `'._DB_PREFIX_.'customer` `cu` ON `cu`.`id_customer` = `ca`.`id_customer`
					JOIN '._DB_PREFIX_.'cart_product cp ON ca.id_cart = cp.id_cart
					JOIN '._DB_PREFIX_.'customer c ON ca.id_customer = c.id_customer
                    INNER JOIN ' . _DB_PREFIX_ . 'stock_available sa ON sa.id_product = cp.id_product
                    INNER JOIN ' . _DB_PREFIX_ . 'product p ON p.id_product = cp.id_product
                    WHERE ((sa.out_of_stock != 1 AND sa.quantity > 0) OR sa.out_of_stock = 1)
					AND `ord`.`id_order` IS NULL';
		$reminder = ReminderController::getReminder($wichReminder);
		$maxReminder = ReminderController::getMaxReminder($id_shop);
		$query .= ' AND ca.date_upd <= "' . $reminder . '"';

		$query .= ' AND ca.date_upd >= "' . pSql($maxReminder) . '" '; 
		$query .= 'AND (';
		if($wichReminder > 1) {
			$remind = $wichReminder-1;
			$query .= 'ca.id_cart IN (SELECT cr.id_cart FROM '._DB_PREFIX_.'cartabandonment_remind cr WHERE wich_remind = ' . (int)$remind . ')
					   AND ';
		}
		$query .= ' ca.id_cart NOT IN (SELECT cr.id_cart FROM '._DB_PREFIX_.'cartabandonment_remind cr WHERE wich_remind = ' . (int)$wichReminder. '))
					AND ca.id_customer NOT IN (SELECT id_customer FROM '._DB_PREFIX_.'cartabandonment_unsubscribe)
					AND ca.id_shop = '.(int)$id_shop;

		if($cab_news == 0)
			$query .= ' AND c.newsletter = 1';

		$query .= ' GROUP BY cu.email';

		$results = DB::getInstance()->ExecuteS($query);

		return $results;
	}
	
	public static function getRemindersByLanguage($id_lang = 1, $id_shop = 1)
	{
		$query = "SELECT id_template, tpl_same FROM " . _DB_PREFIX_ . "cartabandonment_remind_lang WHERE id_lang = " . (int)$id_lang . " AND id_shop = " . (int)$id_shop;
		return DB::getInstance()->ExecuteS($query);
	}
	
	public static function getReminders($wichRemind)
	{
		return DB::getInstance()->ExecuteS("SELECT * FROM " . _DB_PREFIX_ . "cartabandonment_remind_config WHERE wich_remind = " . (int)$wichRemind);
	}
	
	private static function getReminder($wichRemind)
	{
		$remind = ReminderController::getReminders($wichRemind);
		$startDate = time();

		$date = date('Y-m-d H:i:s', strtotime('-'. $remind[0]['days'] . ' day', $startDate));
		$date = date('Y-m-d H:i:s', strtotime('-'. $remind[0]['hours'] . ' hours', strtotime($date)));
		
		return $date;
	}

	private static function getMaxReminder($id_shop = 1)
	{
		$reminder = Configuration::get('CART_MAXREMINDER_WHAT', null, null, $id_shop);
		$time = Configuration::get('CART_MAXREMINDER', null, null, $id_shop);

		$startDate = time();

		switch($reminder){
			case 'minutes':
				return date('Y-m-d H:i:s', strtotime('-'. $time . ' minutes', $startDate));
				// return $time . ' MINUTE';
			break;
			case 'hours':
				return date('Y-m-d H:i:s', strtotime('-'. $time . ' hours', $startDate));
				// return $time . ' HOUR';
			break;
			default:
			case 'days':
				return date('Y-m-d H:i:s', strtotime('-'. $time . ' day', $startDate));
				// return $time . ' DAY';
			break;
		}
	}

	public static function setNewsletter($val, $id_shop)
	{
		return Configuration::updateValue('CAB_NEWS', $val, false, null, $id_shop);
	}
}