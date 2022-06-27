<?php
include_once('../../config/config.inc.php');
$token = Tools::getValue('token_cartabandonment');
$id_shop = Context::getContext()->shop->id;
$token_bdd = Configuration::get('CARTABAND_TOKEN', null, null, $id_shop);

if(strlen($token_bdd) > 0 && isset($token_bdd) && isset($token) && $token_bdd == $token)
{
	require_once dirname(__FILE__).'/controllers/ReminderController.class.php';
	$wichReminder = Tools::getValue('wichReminder');
	$action = Tools::getValue('action');
	
	switch($action){
		case 'setDays':
			$value = Tools::getValue('val');
			echo ReminderController::setDays($wichReminder, $value, $id_shop);
		break;
		case 'setHours':
			$value = Tools::getValue('val');
			echo ReminderController::setHours($wichReminder, $value, $id_shop);
		break;
		case 'setActive':
			$value = Tools::getValue('val');
			echo ReminderController::setActive($wichReminder, $value, $id_shop);
		break;
		case 'setMaxReminder':
			$value = Tools::getValue('val');
			echo ReminderController::setMaxReminder($value, $id_shop);
		break;
		case 'setMaxReminderWhat':
			$value = Tools::getValue('val');
			echo ReminderController::setMaxReminderWhat($value, $id_shop);
		break;
		case 'setNewsletter':
			$value = Tools::getValue('val');
			echo ReminderController::setNewsletter($value, $id_shop);
		break;
	}
	die;
}
else{
	echo 'hack ...';die;
}