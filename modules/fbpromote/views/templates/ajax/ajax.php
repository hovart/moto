<?php

	try 
	{
		// include config
		if (!include_once(realpath(dirname(__FILE__) . "/../../../../..") . '/config/config.inc.php'))
			throw new Exception("config file is unavailable (1)");
		
		// include init
		if (!include_once(_PS_ROOT_DIR_.'/init.php'))
			throw new Exception("init file is unavailable (2)");
			
		// include class fbpromote
		if (!include_once(_PS_MODULE_DIR_ . 'fbpromote/fbpromote.php'))
			throw new Exception("module core file is unavailable (3)");
		
		global $cookie;
		$id_guest = (int)$cookie->id_guest;
		$id_currency = (int)$cookie->id_currency;
		
		$fbpromote = new FBPromote();
		$id_discount = (int)$fbpromote->hasAlreadyVoucher($id_guest);
		$extra_message = ((int)($id_discount != 0)) ? $fbpromote->translate('DISPLAY_AGAIN').'<br /><br />' : '';
		
		switch (Tools::getValue('like'))
		{
			case 1:
				if (empty($id_discount))
				{
					if (!($id_discount=(int)$fbpromote->createVoucher($id_guest, $id_currency)))
						throw new Exception("cannot create voucher");
				}
				else
				{
					if ($fbpromote->isVoucherAlreadyUsed($id_discount))
						throw new Exception($fbpromote->translate('USED'));
					
					if (!$fbpromote->isVoucherValid($id_discount))
						throw new Exception($fbpromote->translate('EXPIRED').' : '.$id_discount);
				}
				
				if (version_compare(_PS_VERSION_, '1.5', '>')) 
					$discount = new CartRule($id_discount);
				else
					$discount = new Discount($id_discount);
				
				if (version_compare(_PS_VERSION_, '1.5', '>')) 
					echo $extra_message.$fbpromote->translate('VALID').' '.$discount->code.' '.$fbpromote->translate('AVAILABLE').' '.$fbpromote->formatDate($discount->date_to);
				else
					echo $extra_message.$fbpromote->translate('VALID').' '.$discount->name.' '.$fbpromote->translate('AVAILABLE').' '.$fbpromote->formatDate($discount->date_to);
				
				exit();
				break;
				
			case 0:
				$fbpromote->deleteVoucher((int)$cookie->id_guest);
				echo $fbpromote->translate('UNLIKE');
				exit();
				
			default:
				throw new Exception("service is unavailable");
		}			
	}
	
	catch (Exception $e)
	{
		echo $e->getMessage();
		exit();
	}
	