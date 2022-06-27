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

library_load_class_by_name('Customweb_Util_Html');

class PostFinanceCwRedirectionModuleFrontController extends ModuleFrontController
{
	public $ssl = true;

	/**
	 * @see FrontController::initContent()
	 */
	public function initContent()
	{
		$this->addCSS($this->module->getPath() . 'css/style.css', 'all');
		$this->display_column_left = false;

		parent::initContent();
		$cart = $this->context->cart;

		if (!isset($_GET['cw_transaction_id'])) {
			throw new Exception("No 'transaction_id' provided.");
		}

		$dbTransaction = PostFinanceCw_Entity_Transaction::loadById(intval($_GET['cw_transaction_id']));
		if ($dbTransaction->getTransactionObject() == null) {
			throw new Exception("Not a valid transaction provided");
		}
		
		$module = PostFinanceCw_PaymentMethod::getInstanceById($dbTransaction->getModuleId());
		
		if ($module === NULL) {
			throw new Exception("Could not load payment module. May be the module id is not set.");
		}

		$authorizationAdapter = PostFinanceCw_Util::getAuthorizationAdapter($dbTransaction->getTransactionObject()->getAuthorizationMethod());

		if (!($authorizationAdapter instanceof Customweb_Payment_Authorization_PaymentPage_IAdapter)) {
			throw new Exception("Only supported for payment page authorization.");
		}

		$headerRedirection = $authorizationAdapter->isHeaderRedirectionSupported($dbTransaction->getTransactionObject(), $_REQUEST);

		if ($headerRedirection) {
			$url = $authorizationAdapter->getRedirectionUrl($dbTransaction->getTransactionObject(), $_REQUEST);
			PostFinanceCw_Util::getEntityManager()->persist($dbTransaction);
			header('Location: ' . $url);
			die();
		}
		else {
			$variables = array(
				'paymentMethodName' => $dbTransaction->getTransactionObject()->getPaymentMethod()->getPaymentMethodDisplayName(),
				'form_target_url' => $authorizationAdapter->getFormActionUrl($dbTransaction->getTransactionObject(), $_REQUEST),
				'hidden_fields' => Customweb_Util_Html::buildHiddenInputFields($authorizationAdapter->getParameters($dbTransaction->getTransactionObject(), $_REQUEST)),
			);
			PostFinanceCw_Util::getEntityManager()->persist($dbTransaction);
			$this->context->smarty->assign($variables);
			$this->setTemplate('redirection.tpl');
		}
	}
}
