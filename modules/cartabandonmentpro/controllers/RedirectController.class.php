<?php
class RedirectController extends ModuleFrontController
{     
	public function __construct(){}
	
	public function redirect()
	{
		$id_cart 	 = Tools::getValue('id_cart');
		$secure_key  = Tools::getValue('secure_key');		
		$wichRemind = Tools::getValue('wichRemind');
		$link = Tools::getValue('link');
		
		switch($link){
			case 'cart':
				$query = '
					SELECT `lastname`, `firstname`, `passwd`, `email`, `id_currency`, `id_cart`, ca.id_customer, cu.secure_key
					FROM `'._DB_PREFIX_.'customer` `cu`
					LEFT JOIN `'._DB_PREFIX_.'cart` `ca`
					ON `ca`.`id_customer` = `cu`.`id_customer` AND `ca`.`secure_key` = `cu`.`secure_key` 
					WHERE `ca`.`id_cart`='.(int)$id_cart.' AND `ca`.`secure_key`="'.pSQL($secure_key).'"';
				$result = DB::getInstance()->getRow($query);

				$customer = new Customer($result['id_customer']);

				$context = Context::getContext();
				$context->cookie->id_cart = $id_cart;
				$context->cookie->id_customer = (int)$customer->id;
				$context->cookie->customer_lastname = $customer->lastname;
				$context->cookie->customer_firstname = $customer->firstname;
				$context->cookie->logged = 1;
				$context->cookie->is_guest = $customer->is_guest;
				$context->cookie->passwd = $customer->passwd;
				$context->cookie->email = $customer->email;
				$this->context = $context;

				$query = "UPDATE "._DB_PREFIX_."cartabandonment_remind SET click_cart = 1 WHERE wich_remind = ".(int)$wichRemind." AND id_cart = ".(int)$id_cart;
				Db::getInstance()->Execute($query);
				Tools::redirect(__PS_BASE_URI__.'order.php?step=0');
				// Tools::redirect(__PS_BASE_URI__.'order.php?step=0&token='.Tools::getToken(false));
			break;
			case 'shop':
				$query = "UPDATE "._DB_PREFIX_."cartabandonment_remind SET click = 1 WHERE wich_remind = ".(int)$wichRemind." AND id_cart = ".(int)$id_cart;
				Db::getInstance()->Execute($query);
				Tools::redirect(__PS_BASE_URI__.'index.php');
			break;
			case 'unsubscribe':
				$id_customer = Tools::getValue('id_customer');
				$query = "INSERT INTO "._DB_PREFIX_."cartabandonment_unsubscribe VALUES (".$id_customer.");";
				if(Db::getInstance()->Execute($query))
					die('OK');
				else
					die('Error');
				
			break;
			default:
				Tools::redirect();
			break;
		}
	}
}