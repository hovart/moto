<?php 
/**
  * You are allowed to use this API in your web application.
 *
 * Copyright (C) 2016 by customweb GmbH
 *
 * This program is licenced under the customweb software licence. With the
 * purchase or the installation of the software in your application you
 * accept the licence agreement. The allowed usage is outlined in the
 * customweb software licence which can be found under
 * http://www.sellxed.com/en/software-license-agreement
 *
 * Any modification or distribution is strictly forbidden. The license
 * grants you the installation in one application. For multiuse you will need
 * to purchase further licences at http://www.sellxed.com/shop.
 *
 * See the customweb software licence agreement for more details.
 *
 */

class PostFinanceCwErrorModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 *          			 		  	    
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->addCSS($this->module->getPath() . 'css/style.css', 'all');
		$this->display_column_left = false;
		
		$errorTransactionId = Tools::getValue('cw_transaction_id', null);
		$errorTransaction = PostFinanceCw_Entity_Transaction::loadById($errorTransactionId);
		if ($errorTransaction !== null) {
			$link = new Link();
			$url = $link->getModuleLink('postfinancecw', 'payment', array('id_module' => $errorTransaction->getModuleId() , 'error_transaction_id' => $errorTransactionId), true);
			Tools::redirect($url);
		}
		else {
			die(Tools::displayError("Not all required parameters are passed back from the payment process."));
		}
	}
}
