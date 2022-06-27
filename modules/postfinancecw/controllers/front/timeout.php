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

class PostFinanceCwTimeoutModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->addCSS($this->module->getPath() . 'css/style.css', 'all');
		$this->display_column_left = false;
		$this->display_column_right = false;
		
		parent::initContent();
		
		$transactionId = Tools::getValue('cw_transaction_id');
		if ($transactionId !== null) {
			$transaction = PostFinanceCw_Entity_Transaction::loadById($transactionId);
			if ($transaction !== null) {
				$this->context->smarty->assign(array('transactionExternalId' => $transaction->getTransactionExternalId()));
			}
		}
		$this->setTemplate('timeout.tpl');
	}
}
