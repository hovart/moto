<?php
/**
 * @name Advanced Pack 5
 * @author Presta-Module.com <support@presta-module.com> - http://www.presta-module.com
 * @copyright Presta-Module 2015 - http://www.presta-module.com
 *
 * 		 	 ____     __  __
 * 			|  _ \   |  \/  |
 * 			| |_) |  | |\/| |
 * 			|  __/   | |  | |
 * 			|_|      |_|  |_|
 *
 *
 *************************************
 **         Advanced Pack 5         **
 *************************************
 */
if (!defined('_PS_VERSION_'))
	exit;

class MailAlertsOverride extends MailAlerts
{

	public function hookActionUpdateQuantity($params)
	{
		// We do not have to care about pack email alerts
		if (isset($params['id_product']) && AdvancedPack::isValidPack((int)$params['id_product']))
			return;

		// Run native process
		parent::hookActionUpdateQuantity($params);
	}

}

